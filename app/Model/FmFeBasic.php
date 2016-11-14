<?php
class FmFeBasic extends AppModel{
	var $name = 'FmFeBasic'; 

    public $belongsTo = array(
        'FmBusinessAccount' => array(
            'className' => 'FmBusinessAccount',
            'foreignKey' => 'fm_business_account_id'
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id_verified'
        )        
    );

    public $hasMany = array(
        'FmFeTransaction' => array(
            'className' => 'FmFeTransaction',
            'foreignKey' => 'fm_fe_basic_id'
        )
    );

    public $validate = array(
        'ordinal' => array(
            'ordinalRule1' => array(
                'rule' => array('notEmptyCheck'),
                'message' => 'Redni broj nije definisan'
            ),
            'ordinalRule2' => array(
                'rule' => array('ordinalUnique'),
                'message' => 'Redni broj nije jedinstven',
                'required' => true
            )
        ),        
        'fm_business_account_id' => array(
            'fmBusinessAccountRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Poslovni račun nije definisan'
            ),
            'fmBusinessAccountRule2' => array(
                'rule' => array('accountValidation'),
                'message' => 'Poslovni račun nije validan',
                'required' => true
            )
        ),
        'fe_number' => array(
            'feNumberRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Broj izvoda nije definisan'
            ),
            'feNumberRule2' => array(
                'rule' => array('feNumberValidation'),
                'message' => 'Broj izvoda nije validan',
                'required' => true
            )
        ),
        'fe_date' => array(
            'feDateRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Datum izvoda nije definisan'
            ),
            'feDateRule2' => array(
                'rule' => array('feDateValidation'),
                'message' => 'Datum izvoda nije validan',
                'required' => true
            )
        ),
        'exchange_rate' => array(
            'exchangeRateRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Kurs devizne valute nije definisan'
            ),
            'exchangeRateRule2' => array(
                'rule' => array('decimal', 4),
                'message' => 'Kurs devizne valute nije validan',
                'required' => true
            )
        ),
        'previous_balance_currency' => array(
            'previousBalanceCurrencyRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Prethodni saldo u deviznoj valuti nije definisan'
            ),
            'previousBalanceCurrencyRule2' => array(
                'rule' => array('decimal', 2),
                'message' => 'Prethodni saldo u deviznoj valuti nije validan',
                'required' => true
            )
        ),
        'previous_balance_rsd' => array(
            'previousBalanceRsdRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Prethodni saldo u dinarskoj valuti nije definisan'
            ),
            'previousBalanceRsdRule2' => array(
                'rule' => array('decimal', 2),
                'message' => 'Prethodni saldo u dinarskoj valuti nije validan',
                'required' => true
            )
        ),        
        'user_id_verified' => array(
            'userIdRule1' => array(
                'rule' => array('userValidation'),
                'message' => 'Operater koji je verifikovao izvod nije validan',
                'required' => true
            )
        )
	);

    /**
     * Function for pre-save logic
     *
     * @throws nothing
     * @param $options = array() with option parameters
     * @return boolean
     */
    public function beforeValidate($options = array()) {
        //Assign new ordinal for new account
        if(empty($this->data['FmFeBasic']['id'])){
            //Assign new ordinal
            $basic_count = $this->find('count', array('conditions' => array('FmFeBasic.deleted' => 0), 'recursive' => -1));
            $this->data['FmFeBasic']['ordinal'] = $basic_count + 1;

            //Assign foreign exchange number
            $fe_count = $this->find('count', array('conditions' => array(
                    'FmFeBasic.deleted' => 0,
                    'FmFeBasic.fm_business_account_id' => $this->data['FmFeBasic']['fm_business_account_id']
                ),
                'recursive' => -1
            ));
            $this->data['FmFeBasic']['fe_number'] = $fe_count + 1;
        }


        //Convert to decimal
        if(!empty($this->data['FmFeBasic']['previous_balance_currency'])){
            $this->data['FmFeBasic']['previous_balance_currency'] = number_format((float)$this->data['FmFeBasic']['previous_balance_currency'], 2, '.', '');
        }
        if(!empty($this->data['FmFeBasic']['previous_balance_rsd'])){
            $this->data['FmFeBasic']['previous_balance_rsd'] = number_format((float)$this->data['FmFeBasic']['previous_balance_rsd'], 3, '.', '');
        }

        return true;
    }//~!   

    /**
     * Check if ordinal number is set for non deleted accounts
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function notEmptyCheck($check){
        if(empty($this->data['FmFeBasic']['deleted'])){
            return !empty($this->data['FmFeBasic']['ordinal']);
        }
        return true;
    }//~!

    /**
     * Check if ordinal number is unique in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function ordinalUnique($check){
        $conditions = array('FmFeBasic.deleted' => 0, 'FmFeBasic.ordinal' => $this->data['FmFeBasic']['ordinal']);
        if(!empty($this->data['FmFeBasic']['id'])){
            $conditions['NOT'] = array('FmFeBasic.id' => $this->data['FmFeBasic']['id']);
        }        
        $exists = $this->find('count', array('conditions' => $conditions), 'recursive' => -1);
        return ($exists > 0) ? false : true;
    }//~!

    /**
     * Check if business account is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function accountValidation($check){
        $exists = $this->FmBusinessAccount->find('count', array('conditions' => array('FmBusinessAccount.id' => $this->data['FmFeBasic']['fm_business_account_id'])));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * Check if fe number is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function feNumberValidation($check){
        if(empty($this->data['FmFeBasic']['id'])){
            $conditions = array(
                'FmFeBasic.deleted' => 0,
                'FmFeBasic.fm_business_account_id' => $this->data['FmFeBasic']['fm_business_account_id']
            );
            //Get last foreign exchange number
            $last_basic = $this->find('first', array(
                'conditions' => $conditions,
                'order' => array('FmFeBasic.fe_number DESC'), 
                'fields' => array('FmFeBasic.fe_number'), 
                'recursive' => -1
            ));
            if(!empty($last_basic)){
                if(empty($this->data['FmFeBasic']['id'])){
                    return $last_basic['FmFeBasic']['fe_number'] < $this->data['FmFeBasic']['fe_number'];
                }
            }            
            return true;
        }else{
            //Get last foreign exchange number
            $current_basic = $this->find('first', array(
                'conditions' => array('FmFeBasic.id' => $this->data['FmFeBasic']['id']),
                'fields' => array('FmFeBasic.fe_number'),
                'recursive' => -1
            ));
            return $current_basic['FmFeBasic']['fe_number'] == $this->data['FmFeBasic']['fe_number'];
        }        
    }//~!

    /**
     * Check if fe date is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function feDateValidation($check){
        //Set conditions
        $conditions = array(
            'FmFeBasic.deleted' => 0,
            'FmFeBasic.fm_business_account_id' => $this->data['FmFeBasic']['fm_business_account_id']
        );
        //Skip current record if updated
        if(!empty($this->data['FmFeBasic']['id'])){
            $conditions['NOT'] = array('FmFeBasic.id' => $this->data['FmFeBasic']['id']);
        }           
        //Get last foreign exchange date
        $last_basic = $this->find('first', array(
            'conditions' => $conditions,
            'order' => array('FmFeBasic.fe_number DESC'),
            'fields' => array('FmFeBasic.fe_date'),
            'recursive' => -1
        ));

        //Compare dates
        if(!empty($last_basic)){
            $last_time = strtotime($last_basic['FmFeBasic']['fe_date']);
            $current_time = strtotime($this->data['FmFeBasic']['fe_date']);
            return $last_time < $current_time;
        }
        return true;
    }//~!

    /**
     * Check if verifying user is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function userValidation($check){
        if(!empty($this->data['FmFeBasic']['user_id_verified'])){
            $exists = $this->User->find('count', array('conditions' => array('User.id' => $this->data['FmFeBasic']['user_id_verified'])));
            return ($exists > 0) ? true : false;
        }
        return true;
    }//~!

    /**
     * Delete basic foreign exchange by id
     *
     * @throws nothing
     * @param $id - FmFeBasic.id
     * @return boolean
     */
    public function deleteBasic($id){
        $result = array();

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();        

        try {
            //Get selected account
            $fe_basic = $this->find('first', array('conditions' => array('FmFeBasic.id' => $id), 'recursive' => -1));
            if(empty($fe_basic)){    
                throw new Exception('Ovaj devizni izvod nije validan!');
            }
            if(!empty($fe_basic['FmFeBasic']['deleted'])){
                throw new Exception('Ovaj devizni izvod je već obrisan!');
            }

            //Set current ordinal
            $current_ordinal = $fe_basic['FmFeBasic']['ordinal'];

            //Mark fe_basic as deleted
            $fe_basic['FmFeBasic']['deleted'] = 1;
            $fe_basic['FmFeBasic']['ordinal'] = null;

            //Save to DB
            if(!$this->save($fe_basic)){
                $errors = $this->validationErrors;
                throw new Exception('Devizni izvod nije obrisan! Greška: '.array_shift($errors)[0]);
            }

            //Update all next ordinals
            $conditions = array('FmFeBasic.deleted' => 0, 'FmFeBasic.ordinal >' => $current_ordinal, 'FmFeBasic.fm_business_account_id' => $fe_basic['FmFeBasic']['fm_business_account_id']);
            $update_result = $this->updateAll(array('FmFeBasic.ordinal' => 'FmFeBasic.ordinal - 1'), $conditions);
            if(!$update_result){
                throw new Exception('Redni brojevi deviznih izvoda nisu osveženi!');
            }
            
            //Set results for erp log
            $result = $fe_basic;
        } catch (Exception $e) {
            //Save error message
            $result['error'] = $e->getMessage();
        }

        //Check for transaction
        if (empty($result['error'])) {
            $dataSource->commit();
        } else {
            $dataSource->rollback();
        }

        return $result;                        
    }//~!

    /**
     * Get basic foreign exchange by id
     *
     * @throws nothing
     * @param $id - FmFeBasic.id
     * @return FmFeBasic array()
     */
    public function getFeBasic($id){
        $this->Behaviors->load('Containable');
        $result = $this->find('first', array(
            'conditions' => array('FmFeBasic.id' => $id), 
            'contain' => array('FmBusinessAccount' => array('CbBank.code', 'CbBank.name', 'Currency.iso'), 'FmBusinessAccount.account_number'),
            'recursive' => -1
        ));
        $this->Behaviors->unload('Containable');

        return $result;
    }//~!     
}
?>