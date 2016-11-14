<?php
class FmFeTransaction extends AppModel{
	var $name = 'FmFeTransaction'; 
    var $flow_types = array('inflow' => 'Priliv', 'outflow' => 'Odliv');
    var $transaction_statuses = array('opened' => 'Otvoren', 'closed' => 'Zatvoren');

    var $payer_recipients = array('customer' => 'Kupac', 'supplier' => 'Dobavljač', 'company' => 'MikroElektronika', 'bank' => 'Banka');
    var $transaction_types = array(
        'by_invoice' => "Uplata/Isplata po računu",
        'by_proform' => "Uplata/Isplata po predračunu",
        'return_goods' => "Povraćaj sredstava",
        'unknown' => "Nepoznato",
        'foreign_exchange' => "Otkup deviza",
        'cash_payment_in' => "Uplata efektive",
        'cash_payment_out' => "Isplata efektive",
        'inside_company_transfer' => "Prenos u okviru istog pravnog lica",
        'charges' => "Provizije I ostali troškovi",
        'interest' => "Kamate",
        'loan_funds' => "Kreditna sredstva",
        'loan_return' => "Povraćaj kredita"
    );
    var $transaction_links = array(
        'customer' => array('by_invoice' => "Uplata/Isplata po računu", 'by_proform' => "Uplata/Isplata po predračunu", 'return_goods' => "Povraćaj sredstava", 'unknown' => "Nepoznato"),
        'supplier' => array('by_invoice' => "Uplata/Isplata po računu", 'by_proform' => "Uplata/Isplata po predračunu", 'return_goods' => "Povraćaj sredstava", 'unknown' => "Nepoznato"),
        'company' => array('foreign_exchange' => "Otkup deviza", 'cash_payment_in' => "Uplata efektive", 'cash_payment_out' => "Isplata efektive", 'inside_company_transfer' => "Prenos u okviru istog pravnog lica"),
        'bank' => array('charges' => "Provizije I ostali troškovi", 'interest' => "Kamate", 'loan_funds' => "Kreditna sredstva", 'loan_return' => "Povraćaj kredita")
    );

    public $belongsTo = array(
        'FmFeBasic' => array(
            'className' => 'FmFeBasic',
            'foreignKey' => 'fm_fe_basic_id'
        ),
        'Client' => array(
            'className' => 'Client',
            'foreignKey' => 'client_id'
        ),
        'FmFeTransactionType' => array(
            'className' => 'FmFeTransactionType',
            'foreignKey' => 'fm_fe_transaction_type_id'
        )
    );

    public $hasMany = array(
        'FmFeTransactionEntry' => array(
            'className' => 'FmFeTransactionEntry',
            'foreignKey' => 'fm_fe_transaction_id'
        ),
        'FmFeTransactionRecord' => array(
            'className' => 'FmFeTransactionRecord',
            'foreignKey' => 'fm_fe_transaction_id'
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
        'fm_fe_basic_id' => array(
            'fmFeBasicIdRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Osnovni podatak o izvodu nije definisan'
            ),
            'fmFeBasicIdRule2' => array(
                'rule' => array('feBasicValidation'),
                'message' => 'Osnovni podatak o izvodu nije validan',
                'required' => true
            )
        ),
        'flow_type' => array(
            'flowTypeRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Priliv/Odliv nije definisan'
            ),
            'flowTypeRule2' => array(
                'rule' => array('flowTypeValidation'),
                'message' => 'Priliv/Odliv nije validan',
                'required' => true
            )
        ),
        'payer_recipient' => array(
            'payerRecipientRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Isplatilac/Primalac nije definisan'
            ),
            'payerRecipientRule2' => array(
                'rule' => array('payerRecipientValidation'),
                'message' => 'Isplatilac/Primalac nije nije validan',
                'required' => true
            )
        ),
        'transaction_type' => array(
            'transactionTypeRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Vrsta transakcije nije definisana'
            ),
            'transactionTypeRule2' => array(
                'rule' => array('transactionTypeValidation'),
                'message' => 'Vrsta transakcije nije validna',
                'required' => true
            ),
            'transactionTypeRule3' => array(
                'rule' => array('transactionTypeCheck'),
                'message' => 'Vrsta transakcije se ne slaže sa Isplatiocem/Primaocem',
                'required' => true
            )
        ),        
        'client_id' => array(
            'clientIdRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Komitent nije definisan'
            ),
            'clientIdRule2' => array(
                'rule' => array('clientValidation'),
                'message' => 'Komitent nije validan',
                'required' => true
            )
        ),        
        'fm_fe_transaction_type_id' => array(
            'fmFeTransactionTypeIdRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Veza sa sifarnikom deviznih transakcija nije definisana'
            ),
            'fmFeTransactionTypeIdRule2' => array(
                'rule' => array('transactionTypeIdValidation'),
                'message' => 'Veza sa sifarnikom deviznih transakcija nije validna',
                'required' => true
            )
        ),
        'transaction_value' => array(
            'transactionValueRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Ukupna devizna vrednost transakcije nije definisana'
            ),
            'transactionValueRule2' => array(
                'rule' => array('decimal', 2),
                'message' => 'Ukupna devizna vrednost transakcije nije validna',
                'required' => true
            )
        ),
        'transaction_value_rsd' => array(
            'transactionValueRsdRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Ukupna dinarska vrednost transakcije nije definisana'
            ),
            'transactionValueRsdRule2' => array(
                'rule' => array('decimal', 2),
                'message' => 'Ukupna dinarska vrednost transakcije nije validna',
                'required' => true
            )
        ),
        'transaction_status' => array(
            'transactionStatusRule1' => array(
                'rule' => array('transactionStatusValidation'),
                'message' => 'Status transakcije nije validan',
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
        //Assign new ordinal for new transaction
        if(empty($this->data['FmFeTransaction']['id'])){
            //Assign new ordinal
            $transaction_count = $this->find('count', array('conditions' => array(
                    'FmFeTransaction.deleted' => 0,
                    'FmFeTransaction.fm_fe_basic_id' => $this->data['FmFeTransaction']['fm_fe_basic_id']
                ),
                'recursive' => -1
            ));
            $this->data['FmFeTransaction']['ordinal'] = $transaction_count + 1;
        }

        //Convert to decimal
        if(!empty($this->data['FmFeTransaction']['transaction_value'])){
            $this->data['FmFeTransaction']['transaction_value'] = number_format((float)$this->data['FmFeTransaction']['transaction_value'], 2, '.', '');
        }
        if(!empty($this->data['FmFeTransaction']['transaction_value_rsd'])){
            $this->data['FmFeTransaction']['transaction_value_rsd'] = number_format((float)$this->data['FmFeTransaction']['transaction_value_rsd'], 2, '.', '');
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
        if(empty($this->data['FmFeTransaction']['deleted'])){
            return !empty($this->data['FmFeTransaction']['ordinal']);
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
        $conditions = array(
            'FmFeTransaction.deleted' => 0, 
            'FmFeTransaction.ordinal' => $this->data['FmFeTransaction']['ordinal'],
            'FmFeTransaction.fm_fe_basic_id' => $this->data['FmFeTransaction']['fm_fe_basic_id']
        );
        if(!empty($this->data['FmFeTransaction']['id'])){
            $conditions['NOT'] = array('FmFeTransaction.id' => $this->data['FmFeTransaction']['id']);
        }        
        $exists = $this->find('count', array('conditions' => $conditions));
        return ($exists > 0) ? false : true;
    }//~! 

    /**
     * Check if fe basic is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function feBasicValidation($check){
        $exists = $this->FmFeBasic->find('count', array('conditions' => array('FmFeBasic.id' => $this->data['FmFeTransaction']['fm_fe_basic_id'])));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * Check if flow type is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function flowTypeValidation($check){
        return array_key_exists($this->data['FmFeTransaction']['flow_type'], $this->flow_types);
    }//~!

    /**
     * Check if payer/recipient is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function payerRecipientValidation($check){
        return array_key_exists($this->data['FmFeTransaction']['payer_recipient'], $this->payer_recipients);
    }//~!

    /**
     * Check if transaction type is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function transactionTypeValidation($check){
        return array_key_exists($this->data['FmFeTransaction']['transaction_type'], $this->transaction_types);
    }//~!

    /**
     * Check if transaction type is selected for right payer/recipient
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function transactionTypeCheck($check){
        if(!empty($this->data['FmFeTransaction']['transaction_type']) && !empty($this->data['FmFeTransaction']['payer_recipient'])){
            $transaction_type = $this->data['FmFeTransaction']['transaction_type'];
            $payer_recipient = $this->data['FmFeTransaction']['payer_recipient'];

            if(!empty($this->transaction_links[$payer_recipient])){
                $link = $this->transaction_links[$payer_recipient];
                return array_key_exists($transaction_type, $link);
            }
        }
        return false;
    }//~!

    /**
     * Check if selected client is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function clientValidation($check){
        $exists = $this->Client->find('count', array('conditions' => array('Client.id' => $this->data['FmFeTransaction']['client_id'])));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * Check if selected client is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function transactionTypeIdValidation($check){
        $exists = $this->FmFeTransactionType->find('count', array('conditions' => array('FmFeTransactionType.id' => $this->data['FmFeTransaction']['fm_fe_transaction_type_id'])));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * Check if selected transaction status is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function transactionStatusValidation($check){
        return array_key_exists($this->data['FmFeTransaction']['transaction_status'], $this->transaction_statuses);
    }//~!

    /**
     * Get all foreign exchange transaction by fm_fe_basic_id
     *
     * @throws nothing
     * @param $fm_fe_basic_id - FmFeBasic.id
     * @return boolean
     */
    public function getAllTransactions($fm_fe_basic_id){
        //Init variables
        $result = array();

        //Get only non deleted entries and records
        $this->Behaviors->load('Containable');
        $result = $this->find('all', array(
            'conditions' => array(
                'FmFeTransaction.fm_fe_basic_id' => $fm_fe_basic_id,
                'FmFeTransaction.deleted' => 0
            ),
            'order' => array('FmFeTransaction.ordinal ASC'),
            'contain' => array(
                'FmFeBasic', 'Client', 'FmFeTransactionType',
                'FmFeTransactionEntry' => array('conditions' => array('FmFeTransactionEntry.deleted' => 0)),
                'FmFeTransactionRecord' => array('conditions' => array('FmFeTransactionRecord.deleted' => 0))
            ),
            'recursive' => -1
        ));
        $this->Behaviors->unload('Containable');

        //Return results
        return $result;
    }//~!

    /**
     * Get all foreign exchange flow sums by fm_fe_basic_id
     *
     * @throws nothing
     * @param $fm_fe_basic_id - FmFeBasic.id
     * @return boolean
     */
    public function getFlowSums($fm_fe_basic_id){
        //Init variables
        $result = array();

        //Set virtual fields
        $this->virtualFields = array(
            'flow_total' => 'SUM(FmFeTransaction.transaction_value)',
            'flow_total_rsd' => 'SUM(FmFeTransaction.transaction_value_rsd)'
        );

        //Get sums
        $sums = $this->find('all', array(
            'conditions' => array(
                'FmFeTransaction.fm_fe_basic_id' => $fm_fe_basic_id,
                'FmFeTransaction.deleted' => 0
            ),
            'fields' => array('FmFeTransaction.flow_type', 'FmFeTransaction.flow_total', 'FmFeTransaction.flow_total_rsd'),
            'group' => array('FmFeTransaction.flow_type'),
            'recursive' => -1
        ));

        //Clear virtual fields
        $this->virtualFields = array();

        //Set result
        foreach ($sums as $sum) {
            $result[$sum['FmFeTransaction']['flow_type']]['flow_total'] = $sum['FmFeTransaction']['flow_total'];
            $result[$sum['FmFeTransaction']['flow_type']]['flow_total_rsd'] = $sum['FmFeTransaction']['flow_total_rsd'];
        }

        //Return results
        return $result;
    }//~!

    /**
     * Close foreign exchange transaction by id
     *
     * @throws nothing
     * @param $id - FmFeTransaction.id
     * @return $result with success or error message and closed record
     */    
    public function closeTransaction($id){
        //Init result
        $result = array();    

        try {
            //Get selected account
            $fm_fe_transaction = $this->find('first', array('conditions' => array('FmFeTransaction.id' => $id), 'recursive' => -1));
            if(empty($fm_fe_transaction)){    
                throw new Exception('Ova devizna transakcija nije validna!');
            }
            if(!empty($fm_fe_transaction['FmFeTransaction']['deleted'])){
                throw new Exception('Ova devizna transakcija je već obrisana!');
            }
            if($fm_fe_transaction['FmFeTransaction']['transaction_status'] == 'closed'){
                throw new Exception('Ova devizna transakcija je već zatvorena!');
            }

            //Get sums
            $sums = $this->FmFeTransactionRecord->getTransactionRecordsSum($id);

            //If sum is return goods set positive value
            $sum_total_domestic = $sums[0]['FmFeTransactionRecord']['sum_domestic_credit'] - $sums[0]['FmFeTransactionRecord']['sum_domestic_debit'];
            if(in_array($fm_fe_transaction['FmFeTransaction']['payer_recipient'], array('customer'))){
                if(in_array($fm_fe_transaction['FmFeTransaction']['transaction_type'], array('return_goods'))){
                    $sum_total_domestic = abs($sum_total_domestic);
                }
            }

            //Check sum
            $epsilon = 0.01;
            if(abs($sum_total_domestic - $fm_fe_transaction['FmFeTransaction']['transaction_value_rsd']) >= $epsilon) {
                throw new Exception('Dinarska vrednost devizne transakcije se ne slaže sa ukupnim dinarskim vrednostima obrazca za knjiženje!');
            }

            //Set transaction as closed
            $fm_fe_transaction['FmFeTransaction']['transaction_status'] = 'closed';
            if(!$this->save($fm_fe_transaction)){
                $errors = $this->validationErrors;
                throw new Exception('Devizna transakcija ne može biti zatvorena! Greška: '.array_shift($errors)[0]);
            }

            //Set return result
            $result = $fm_fe_transaction;
            $result['success'] = true;
        } catch (Exception $e) {
            //Save error message
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }

        return $result;
    }//~!     

    /**
     * Delete foreign exchange transaction by id
     *
     * @throws nothing
     * @param $id - FmFeBasic.id
     * @return boolean
     */
    public function deleteTransaction($id){
        $result = array();

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();        

        try {
            //Get selected account
            $fm_fe_transaction = $this->find('first', array('conditions' => array('FmFeTransaction.id' => $id), 'recursive' => -1));
            if(empty($fm_fe_transaction)){    
                throw new Exception('Ova devizna transakcija nije validna!');
            }
            if(!empty($fm_fe_transaction['FmFeTransaction']['deleted'])){
                throw new Exception('Ova devizna transakcija je već obrisana!');
            }

            //Set deleted results for log
            $result = $fm_fe_transaction;

            //Set current ordinal
            $current_ordinal = $fm_fe_transaction['FmFeTransaction']['ordinal'];

            //Mark fm_fe_transaction as deleted
            $fm_fe_transaction['FmFeTransaction']['deleted'] = 1;
            $fm_fe_transaction['FmFeTransaction']['ordinal'] = null;

            //Save to DB
            if(!$this->save($fm_fe_transaction)){
                $errors = $this->validationErrors;
                throw new Exception('Devizna transakcija nije obrisana! Greška: '.array_shift($errors)[0]);
            }

            //Update all next ordinals
            $conditions = array(
                'FmFeTransaction.deleted' => 0, 
                'FmFeTransaction.ordinal >' => $current_ordinal, 
                'FmFeTransaction.fm_fe_basic_id' => $fm_fe_transaction['FmFeTransaction']['fm_fe_basic_id']
            );
            $update_result = $this->updateAll(array('FmFeTransaction.ordinal' => 'FmFeTransaction.ordinal - 1'), $conditions);
            if(!$update_result){
                throw new Exception('Redni brojevi deviznih transakcija nisu osveženi!');
            }            
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
     * Get foreign exchange transaction by id
     *
     * @throws nothing
     * @param $id - FmFeTransaction.id
     * @return FmFeTransaction array()
     */
    public function getFeTransaction($id){
        $this->Behaviors->load('Containable');
        $result = $this->find('first', array(
            'conditions' => array(
                'FmFeTransaction.id' => $id,
                'FmFeTransaction.deleted' => 0
            ),
            'contain' => array(
                'FmFeBasic', 'Client', 'FmFeTransactionType',
                'FmFeTransactionEntry' => array('conditions' => array('FmFeTransactionEntry.deleted' => 0)),
                'FmFeTransactionRecord' => array('conditions' => array('FmFeTransactionRecord.deleted' => 0))
            ),
            'recursive' => -1
        ));
        $this->Behaviors->unload('Containable');
        return $result;
    }//~!
    /**
     * Check if transaction is editable
     *
     * @throws nothing
     * @param $id - FmFeTransaction.id
     * @return $result with success or error message and transaction info
     */    
    public function getTransactionForEditing($id){
        //Init result
        $result = array();    

        try {
            //Get transaction
            $fe_transaction = $this->getFeTransaction($id);
            if(empty($fe_transaction)){
                throw new Exception('Devizna transakcija nije validna!');
            }
            //Check if fe basic deleted
            if(!empty($fe_transaction['FmFeBasic']['deleted'])){
                throw new Exception('Devizna izvod je obrisan!');
            }
            //Check if verified
            if(!empty($fe_transaction['FmFeBasic']['user_id_verified'])){
                throw new Exception('Devizna izvod je verifikovan!');
            }
            //Check if fe transaction deleted
            if(!empty($fe_transaction['FmFeTransaction']['deleted'])){
                throw new Exception('Transakcija deviznog izvoda je obrisana!');
            }
            //Check if transaction is closed
            if($fe_transaction['FmFeTransaction']['transaction_status'] == 'closed'){
                throw new Exception('Devizna transakcija je zatvorena!');
            }

            //Set return result
            $result['fe_transaction'] = $fe_transaction;
            $result['success'] = true;
        } catch (Exception $e) {
            //Save error message
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }

        return $result;
    }//~!       
}
?>