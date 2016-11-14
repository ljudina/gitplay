<?php
class FmFeAccountScheme extends AppModel{
	var $name = 'FmFeAccountScheme'; 

    public $belongsTo = array(
        'UserStart' => array(
            'className' => 'User',
            'foreignKey' => 'user_id_start'
        ),
        'UserEnd' => array(
            'className' => 'User',
            'foreignKey' => 'user_id_end'
        )
    );

    public $hasMany = array(
        'FmFeAccountSchemeRow' => array(
            'className' => 'FmFeAccountSchemeRow',
            'foreignKey' => 'fm_fe_account_scheme_id'
        )
    );

    public $validate = array(
        'code' => array(
            'codeRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Br. šeme knjiženja nije definisan'
            ),
            'codeRule2' => array(
                'rule' => 'isUnique',
                'message' => 'Br. šeme knjiženja nije validan',
                'required' => true
            )
        ),
        'scheme_desc' => array(
            'schemeDescRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Opis šeme knjiženja nije definisan',
                'required' => true
            )
        ),
        'valid_from' => array(
            'validFromRule1' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Datum početka važenja šeme knjiženja nije validan',
                'allowEmpty' => true
            ),
            'validFromRule2' => array(
                'rule' => array('validFromFutureValidation'),
                'message' => 'Datum početka važenja šeme knjiženja ne može biti u budućnosti.',
                'required' => true
            )
        ),
        'valid_to' => array(
            'validToRule1' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Datum završetka važenja šeme knjiženja nije validan',
                'allowEmpty' => true
            ),
            'validToRule2' => array(
                'rule' => array('validToFutureValidation'),
                'message' => 'Datum završetka važenja šeme knjiženja ne može biti u budućnosti.',
                'required' => true
            ),
            'validToRule3' => array(
                'rule' => array('validToValidFromComparison'),
                'message' => 'Datum završetka važenja šeme knjiženja mora društva biti posle datuma početka važenja šeme knjiženja',
                'required' => true
            )
        ),
        'user_id_start' => array(
            'userIdStartRule1' => array(
                'rule' => array('userStartValidation'),
                'message' => 'Operater koji je započeo period važenja šeme knjiženja nije validan',
                'required' => true
            )
        ),
        'user_id_end' => array(
            'userIdEndRule1' => array(
                'rule' => array('userEndValidation'),
                'message' => 'Operater koji je završio period važenja šeme knjiženja nije validan',
                'required' => true
            )
        )
	);

    /**
     * Check if valid from date is in future
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function validFromFutureValidation($check){
        if(!empty($this->data['FmFeAccountScheme']['valid_from'])){
            $valid_from_time = strtotime($this->data['FmFeAccountScheme']['valid_from']);
            $today = strtotime(date('Y-m-d'));
            return $today >= $valid_from_time;
        }
        return true;
    }//~!

    /**
     * Check if valid to date is in future
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function validToFutureValidation($check){
        if(!empty($this->data['FmFeAccountScheme']['valid_to'])){
            $valid_to_time = strtotime($this->data['FmFeAccountScheme']['valid_to']);
            $today = strtotime(date('Y-m-d'));
            return $today >= $valid_to_time;
        }
        return true;
    }//~!    

    /**
     * Check if valid to date is in future
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function validToValidFromComparison($check){
        if(!empty($this->data['FmFeAccountScheme']['valid_to'])){
            if(!empty($this->data['FmFeAccountScheme']['valid_from'])){
                $valid_from_time = strtotime($this->data['FmFeAccountScheme']['valid_from']);
                $valid_to_time = strtotime($this->data['FmFeAccountScheme']['valid_to']);

                return $valid_from_time <= $valid_to_time;
            }else{
                return false;
            }
        }   
        return true;     
    }//~!

    /**
     * If scheme period started check if user that started scheme exist in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function userStartValidation($check){
        if(!empty($this->data['FmFeAccountScheme']['valid_from'])){
            $user = $this->UserStart->find('count', array('conditions' => array('UserStart.id' => $this->data['FmFeAccountScheme']['user_id_start']), 'recursive' => -1));
            return ($user > 0) ? true : false;
        }
        return true;
    }//~!

    /**
     * If scheme period ended check if user that ended scheme exist in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function userEndValidation($check){
        if(!empty($this->data['FmFeAccountScheme']['valid_to'])){
            $user = $this->UserEnd->find('count', array('conditions' => array('UserEnd.id' => $this->data['FmFeAccountScheme']['user_id_end']), 'recursive' => -1));
            return ($user > 0) ? true : false;
        }
        return true;
    }//~!    

    /**
     * Function for deleting scheme with all rows and records from db
     *
     * @throws nothing
     * @param $id - FmFeAccountScheme.id
     * @return $result with success or error message
     */
    public function deleteScheme($id){
        //Init result
        $result = array();

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();        

        try {
            //Check for row existance
            $this->UserStart->virtualFields = array();
            $this->UserEnd->virtualFields = array();
            $account_scheme = $this->find('first', array('conditions' => array('FmFeAccountScheme.id' => $id), 'recursive' => 1));
            if(empty($account_scheme)){
                throw new Exception('Šema knjiženja za brisanje ne postoji!');
            }

            //Check for account sheme valid dates
            if(!empty($account_scheme['FmFeAccountScheme']['valid_to'])){
                throw new Exception('Šema knjiženja je istekla i nije moguće brisanje!');
            }           
            if(!empty($account_scheme['FmFeAccountScheme']['valid_from'])){
                throw new Exception('Šema knjiženja je aktivna i nije moguće brisanje!');
            }

            //Delete scheme
            if(!$this->delete($id)){
                throw new Exception('Greška prilikom brisanja šeme knjiženja!');
            }

            //Delete all rows
            foreach ($account_scheme['FmFeAccountSchemeRow'] as $row) {
                $row_delete_result = $this->FmFeAccountSchemeRow->deleteRow($row['id']);
                if($row_delete_result['success']){
                    $account_scheme['rows'][$row['ordinal']] = $row_delete_result;
                }else{
                    throw new Exception('Red '.$row['ordinal'].'. nije obrisan. Greška: '.$row_delete_result['message']);
                }
            }

            //Unset row table data
            unset($account_scheme['FmFeAccountSchemeRow']);

            //Set return result
            $result = $account_scheme;
            $result['success'] = true;
        } catch (Exception $e) {
            //Save error message
            $result['success'] = false;
            $result['message'] = $e->getMessage();          
        }

        //Check for transaction
        if($result['success']) {
            $dataSource->commit();
        } else {
            $dataSource->rollback();
        }

        return $result;        
    }//~!

    /**
     * Check account scheme conditions for starting
     *
     * @throws nothing
     * @param $id - FmFeAccountScheme.id
     * @return $result with success or error message
     */
    public function checkConditionsMet($id){
        $result = array();
        try {
            //Check for account scheme
            $this->UserStart->virtualFields = array();
            $this->UserEnd->virtualFields = array();
            $account_scheme = $this->find('first', array('conditions' => array('FmFeAccountScheme.id' => $id), 'recursive' => 1));
            if(empty($account_scheme)){
                throw new Exception('Šema knjiženja nije validna!');
            }

            //Check for existing rows
            if(empty($account_scheme['FmFeAccountSchemeRow'])){
                throw new Exception('Za šemu knjiženja '.$account_scheme['FmFeAccountScheme']['code'].' nije definisan nijedan red!');
            }

            //Validate rows
            foreach ($account_scheme['FmFeAccountSchemeRow'] as $row) {
                if(!$this->FmFeAccountSchemeRow->checkAllRecordsAvailable($row['id'])){
                    throw new Exception('Za red br. '.$row['ordinal'].' nisu definisana sva polja!');
                }
            }

            $result['success'] = true;            
        } catch (Exception $e) {
            //Save error message
            $result['success'] = false;
            $result['message'] = $e->getMessage();          
        }
        return $result;         
    }//~!

    /**
     * Start account scheme validity period
     *
     * @throws nothing
     * @param $id - FmFeAccountScheme.id
     * @return $result with success or error message
     */
    public function startPeriod($id){
        $result = array();
        try {
            //Check for account scheme
            $this->UserStart->virtualFields = array();
            $this->UserEnd->virtualFields = array();            
            $account_scheme = $this->find('first', array('conditions' => array('FmFeAccountScheme.id' => $id), 'recursive' => -1));
            if(empty($account_scheme)){
                throw new Exception('Šema knjiženja nije validna!');
            }
            if(!empty($account_scheme['FmFeAccountScheme']['valid_to'])){
                throw new Exception('Šema knjiženja je završen i nije moguće ponovno pokretanje!');
            }           
            if(!empty($account_scheme['FmFeAccountScheme']['valid_from'])){
                throw new Exception('Period važenja šeme knjiženja je počeo!');
            }

            //Check if conditions met
            $conditions = $this->checkConditionsMet($id);
            if(!$conditions['success']){
                throw new Exception('Uslovi za početak važenja šeme knjiženja nisu zadovoljeni! Greška: '.$conditions['message']);
            }

            //Save to db
            $account_scheme['FmFeAccountScheme']['valid_from'] = date('Y-m-d');
            $account_scheme['FmFeAccountScheme']['user_id_start'] = AuthComponent::user('id');
            if(!$this->save($account_scheme)){
                $errors = $this->validationErrors;
                throw new Exception(array_shift($errors)[0]);                
            }
            $result = $account_scheme;
            $result['success'] = true;            
        } catch (Exception $e) {
            //Save error message
            $result['success'] = false;
            $result['message'] = $e->getMessage();          
        }
        return $result;         
    }//~!

    /**
     * End account scheme validity period
     *
     * @throws nothing
     * @param $id - FmFeAccountScheme.id
     * @return $result with success or error message
     */
    public function endPeriod($id){
        $result = array();
        try {
            //Check for account scheme
            $account_scheme = $this->find('first', array('conditions' => array('FmFeAccountScheme.id' => $id), 'recursive' => -1));
            if(empty($account_scheme)){
                throw new Exception('Šema knjiženja nije validna!');
            }
            if(!empty($account_scheme['FmFeAccountScheme']['valid_to'])){
                throw new Exception('Šema knjiženja je završen i nije moguće ponovno stopiranje!');
            }           
            if(empty($account_scheme['FmFeAccountScheme']['valid_from'])){
                throw new Exception('Period važenja šeme knjiženja nije počeo!');
            }

            //Save to db
            $account_scheme['FmFeAccountScheme']['valid_to'] = date('Y-m-d');
            $account_scheme['FmFeAccountScheme']['user_id_end'] = AuthComponent::user('id');
            if(!$this->save($account_scheme)){
                $errors = $this->validationErrors;
                throw new Exception(array_shift($errors)[0]);
            }
            $result = $account_scheme;
            $result['success'] = true;
        } catch (Exception $e) {
            //Save error message
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }//~!    

    /**
     * Return a list of active/started schemes
     *
     * @throws nothing
     * @param none
     * @return $array with FmFeAccountScheme.id => FmFeAccountScheme.code list
     */
    public function getActiveSchemeList(){
        return $this->find('list', array(
            'conditions' => array('FmFeAccountScheme.valid_to' => null, 'NOT' => array('FmFeAccountScheme.valid_from' => null)),
            'fields' => array('FmFeAccountScheme.id', 'FmFeAccountScheme.code'), 
            'recursive' => -1
        ));
    }//~!    

    /**
     * Get account scheme with rows and records
     *
     * @throws nothing
     * @param $fm_fe_account_scheme_id - FmFeAccountScheme.id
     * @return $array with account scheme data or false on error
     */
    public function getAccountScheme($fm_fe_account_scheme_id){
        $result = array();
        
        //Clear virtual fields
        $this->UserStart->virtualFields = array();
        $this->UserEnd->virtualFields = array();

        //Get account scheme
        $account_scheme = $this->find('first', array(
            'conditions' => array('FmFeAccountScheme.id' => $fm_fe_account_scheme_id, 'FmFeAccountScheme.valid_to' => null, 'NOT' => array('FmFeAccountScheme.valid_from' => null)),
            'fields' => array('FmFeAccountScheme.*'),
            'recursive' => -1
        ));

        //Check account scheme
        if(empty($account_scheme)){
            return false;
        }

        //Get rows
        $scheme_rows = $this->FmFeAccountSchemeRow->getRows($fm_fe_account_scheme_id);

        //Check rows
        if(empty($scheme_rows)){
            return false;
        }

        $result = $account_scheme;
        $result['FmFeAccountSchemeRow'] = $scheme_rows;

        return $result;
    }//~!
}
?>