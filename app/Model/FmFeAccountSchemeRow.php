<?php
class FmFeAccountSchemeRow extends AppModel{
	var $name = 'FmFeAccountSchemeRow'; 
    var $conditions = array(
        'required' => "Obavezan",
        'bank_cost_exist' => "Provizija banke",
        'exchange_diff_positive' => "Kursna razlika pozitivna",
        'exchange_diff_negative' => "Kursna razlika negativna"
    );

    public $belongsTo = array(
        'FmFeAccountScheme' => array(
            'className' => 'FmFeAccountScheme',
            'foreignKey' => 'fm_fe_account_scheme_id'
        )
    );

    public $hasMany = array(
        'FmFeAccountSchemeRecord' => array(
            'className' => 'FmFeAccountSchemeRecord',
            'foreignKey' => 'fm_fe_account_scheme_row_id'
        )
    );    

    public $validate = array(
        'fm_fe_account_scheme_id' => array(
            'fmFeAccountSchemeIdRule1' => array(
                'rule' => array('accountSchemeValidation'),
                'message' => 'Šema knjiženja nije validana',
                'required' => true
            )
        ),
        'ordinal' => array(
            'ordinalRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Red u šemi knjiženja nije definisan'
            ),
            'ordinalRule2' => array(
                'rule' => array('isUniqueValidation'),
                'message' => 'Red u šemi knjiženja nije jedinstven',
                'required' => true
            )
        ),
        'conditions' => array(
            'conditionsRule1' => array(
                'rule' => array('conditionsValidation'),
                'message' => 'Uslov za kreiranje reda u šemi nije validan',
                'required' => true
            )
        )
	);

    /**
     * Function for pre-validate logic
     *
     * @throws nothing
     * @param $options = array() with option parameters
     * @return boolean
     */
    public function beforeValidate($options = array()) {
        //Assign new ordinal for new row
        if(empty($this->data['FmFeAccountSchemeRow']['id'])){
            $row_count = $this->find('count', array('conditions' => array('FmFeAccountSchemeRow.fm_fe_account_scheme_id' => $this->data['FmFeAccountSchemeRow']['fm_fe_account_scheme_id']), 'recursive' => -1));
            $this->data['FmFeAccountSchemeRow']['ordinal'] = $row_count + 1;
        }
        return true;
    }//~!

    /**
     * Chech if account scheme exist in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function accountSchemeValidation($check){
        $conditions = array('FmFeAccountScheme.id' => $this->data['FmFeAccountSchemeRow']['fm_fe_account_scheme_id']);
        $scheme = $this->FmFeAccountScheme->find('count', array('conditions' => $conditions, 'recursive' => -1));
        return ($scheme > 0) ? true : false;
    }//~!    

    /**
     * Check if row ordinal number is unique for account scheme
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function isUniqueValidation($check){
        $conditions = array(
            'FmFeAccountSchemeRow.ordinal' => $this->data['FmFeAccountSchemeRow']['ordinal'],
            'FmFeAccountSchemeRow.fm_fe_account_scheme_id' => $this->data['FmFeAccountSchemeRow']['fm_fe_account_scheme_id']
        );
        if(!empty($this->data['FmFeAccountSchemeRow']['id'])){
            $conditions['NOT'] = array('FmFeAccountSchemeRow.id' => $this->data['FmFeAccountSchemeRow']['id']);
        }
        $row = $this->find('count', array('conditions' => $conditions, 'recursive' => -1));
        return empty($row);
    }//~!

    /**
     * Check for conditions validation
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function conditionsValidation($check){
        return array_key_exists($this->data['FmFeAccountSchemeRow']['conditions'], $this->conditions);
    }//~! 

    /**
     * Extract scheme record field data from form
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    private function getSchemeRecordFieldData($field, $form_data){
        $result = array();

        try {
            //Check if field is valid
            if(!array_key_exists($field, $this->FmFeAccountSchemeRecord->document_fields)){
                throw new Exception('Polje nije validno!');
            }

            $field_data = array();
            foreach ($form_data['FmFeAccountSchemeRow'] as $key => $value) {
                //Check for field key
                if(strpos($key, $field.'_') !== false){
                    $field_key = str_replace($field.'_', '', $key);
                    $field_data[$field_key] = $value;
                }
            }

            //Check for field data
            if(empty($field_data)){
                throw new Exception('Traženo polje nije definisano!');
            }

            //Set return result
            $result['field_data'] = $field_data;
            $result['success'] = true;
        } catch (Exception $e) {
            //Save error message
            $result['success'] = false;
            $result['message'] = $e->getMessage();          
        }

        return $result;
    }//~!

    /**
     * Method for saving row and records
     *
     * @throws nothing
     * @param $form_data - array with row and record data
     * @return result with data and errors
     */
    public function saveRow($form_data){
        //Init result
        $result = array();
        $result['error_rows'] = array();

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();        

        try {           
            //Save/create basic row info first
            if(empty($form_data['FmFeAccountSchemeRow']['id'])){
                $this->create();
            }

            //Save to DB
            if(!$this->save($form_data)){
                $errors = $this->validationErrors;
                throw new Exception('Red ne može biti snimljen! Greška: '.array_shift($errors)[0]);
            }

            //Assign ID if row created
            if(empty($form_data['FmFeAccountSchemeRow']['id'])){
                $form_data['FmFeAccountSchemeRow']['id'] = $this->id;
            }

            //Process and save account scheme records
            $scheme_record_field_no = $this->FmFeAccountSchemeRecord->document_field_no;
            $scheme_record_fields = $this->FmFeAccountSchemeRecord->document_fields;
            $scheme_records = array();

            foreach ($scheme_record_field_no as $field_no => $field) {
                //Init variables
                $scheme_record = array();

                //Get field data
                $data = $this->getSchemeRecordFieldData($field, $form_data);

                //Check field data
                if(!$data['success']){
                    $result['error_rows'][$field_no] = 'Polje '.$scheme_record_fields[$field].' u obrascu ne može biti učitano! Greška: '.$data['message'];
                    throw new Exception($result['error_rows'][$field_no]);
                }
                //Check for operation used information
                if(empty($data['field_data']['operation_used'])){
                    throw new Exception('Operacija nad poljem nije definisana!');
                }
                //Set operation used
                $operation_used = $data['field_data']['operation_used'];

                //Check if scheme record already exists
                $scheme_record = $this->FmFeAccountSchemeRecord->find('first', array(
                    'conditions' => array(
                        'FmFeAccountSchemeRecord.fm_fe_account_scheme_row_id' => $form_data['FmFeAccountSchemeRow']['id'],
                        'FmFeAccountSchemeRecord.document_field' => $field
                    ),
                    'fields' => array('FmFeAccountSchemeRecord.id'),
                    'recursive' => -1
                ));

                //Set connections
                $codebook_connection_id = null;
                $fm_fe_document_link_id = null;

                if($operation_used == 'equal_codebook' && !empty($data['field_data']['connection'])){
                    $codebook_connection_id = intval($data['field_data']['connection']);
                }
                if($operation_used == 'equal_document_link' && !empty($data['field_data']['connection'])){
                    $fm_fe_document_link_id = intval($data['field_data']['connection']);   
                }

                $scheme_record['FmFeAccountSchemeRecord']['fm_fe_account_scheme_row_id'] = $form_data['FmFeAccountSchemeRow']['id'];
                $scheme_record['FmFeAccountSchemeRecord']['codebook_connection_id'] = $codebook_connection_id;
                $scheme_record['FmFeAccountSchemeRecord']['fm_fe_document_link_id'] = $fm_fe_document_link_id;
                $scheme_record['FmFeAccountSchemeRecord']['document_field'] = $field;
                $scheme_record['FmFeAccountSchemeRecord']['operation_used'] = $operation_used;

                $scheme_record['FmFeAccountSchemeRecord']['record_value'] = null;
                if(in_array($operation_used, array('fixed_value', 'equal_codebook', 'equal_document_link', 'equals_col')) && isset($data['field_data']['record_value'])){
                    if(!empty($data['field_data']['record_value']) || $data['field_data']['record_value'] === '0' || $data['field_data']['record_value'] !== 0){
                        $scheme_record['FmFeAccountSchemeRecord']['record_value'] = $data['field_data']['record_value'];
                    }
                }

                //Set record title
                $scheme_record['FmFeAccountSchemeRecord']['record_title'] = null;
                if($operation_used == 'equal_document_link' && isset($data['field_data']['connection_title'])){
                    $scheme_record['FmFeAccountSchemeRecord']['record_title'] = $data['field_data']['connection_title'];
                }
                if($operation_used == 'equal_codebook' && isset($data['field_data']['record_title'])){
                    $scheme_record['FmFeAccountSchemeRecord']['record_title'] = $data['field_data']['record_title'];
                }

                //Set arithmetic cols
                $scheme_record['FmFeAccountSchemeRecord']['arithmetic_first_col'] = null;
                $scheme_record['FmFeAccountSchemeRecord']['arithmetic_second_col'] = null;

                if(in_array($operation_used, array('divide_fields', 'multiply_fields')) && !empty($data['field_data']['arithmetic_col_1']) && !empty($data['field_data']['arithmetic_col_2'])){
                    $scheme_record['FmFeAccountSchemeRecord']['arithmetic_first_col'] = $data['field_data']['arithmetic_col_1'];
                    $scheme_record['FmFeAccountSchemeRecord']['arithmetic_second_col'] = $data['field_data']['arithmetic_col_2'];
                }

                //Set absolute and negative flag
                $scheme_record['FmFeAccountSchemeRecord']['absolute_value'] = $data['field_data']['absolute_value'];
                $scheme_record['FmFeAccountSchemeRecord']['negative_value'] = $data['field_data']['negative_value'];

                //Validate record
                $this->FmFeAccountSchemeRecord->set($scheme_record);
                if (!$this->FmFeAccountSchemeRecord->validates()) {
                    // didn't validate logic
                    $errors = $this->FmFeAccountSchemeRecord->validationErrors;              
                    $result['error_rows'][$field_no] = 'Greška u redu '.$field_no.': '.array_shift($errors)[0];
                }              

                //Add to scheme record list
                $scheme_records[] = $scheme_record;
            }

            //Check for row errors
            if(!empty($result['error_rows'])){
                $row_error_msg = array_values($result['error_rows'])[0];
                throw new Exception('Greška polja u obrascu za knjiženje: '.$row_error_msg);
            }

            //Save scheme record list if available
            if(!empty($scheme_records)){
                if(!$this->FmFeAccountSchemeRecord->saveMany($scheme_records)){
                    $errors = $this->FmFeAccountSchemeRecord->validationErrors;
                    throw new Exception('Polja u obrascu za knjiženje nisu snimljena! Greška: '.array_shift($errors[0])[0]);
                }
            }else{
                throw new Exception('Nijedno polje u obrascu za knjiženje nije definisano!');
            }

            //Set return result
            $result = $scheme_records;
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
     * Method for deleting row and records
     *
     * @throws nothing
     * @param $id - FmFeAccountSchemeRow.id
     * @return delete result array
     */
    public function deleteRow($id){
        //Init result
        $result = array();

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();        

        try {
            //Check for row existance
            $account_scheme_row = $this->find('first', array('conditions' => array('FmFeAccountSchemeRow.id' => $id), 'recursive' => 1));
            if(empty($account_scheme_row)){
                throw new Exception('Red za brisanje ne postoji!');
            }

            //Check for account sheme valid dates
            if(!empty($account_scheme_row['FmFeAccountScheme']['valid_to'])){
                throw new Exception('Šema knjiženja je istekla i nije moguće brisanje reda!');
            }           
            if(!empty($account_scheme_row['FmFeAccountScheme']['valid_from'])){
                throw new Exception('Šema knjiženja je aktivna i nije moguće brisanje reda!');
            }

            //Delete row
            if(!$this->delete($id)){
                throw new Exception('Greška prilikom brisanja reda!');
            }

            //Update all next ordinals
            $conditions = array(
                'FmFeAccountSchemeRow.fm_fe_account_scheme_id' => $account_scheme_row['FmFeAccountSchemeRow']['fm_fe_account_scheme_id'], 
                'FmFeAccountSchemeRow.ordinal >' => $account_scheme_row['FmFeAccountSchemeRow']['ordinal']
            );
            $update_result = $this->updateAll(array('FmFeAccountSchemeRow.ordinal' => 'FmFeAccountSchemeRow.ordinal - 1'), $conditions);
            if(!$update_result){
                throw new Exception('Brojevi redova šeme knjiženja nisu osveženi!');
            }

            //Delete records
            $document_fields = $this->FmFeAccountSchemeRecord->document_fields;
            foreach ($account_scheme_row['FmFeAccountSchemeRecord'] as $record) {
                //Delete record
                if(!$this->FmFeAccountSchemeRecord->delete($record['id'])){
                    throw new Exception('Greška prilikom brisanja polja '.$document_fields[$record['document_field']].' u redu!');
                }
            }

            //Set return result
            $result = $account_scheme_row;
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
     * Check if all records are available for account scheme row
     *
     * @throws nothing
     * @param $id - FmFeAccountSchemeRow.id
     * @return boolean
     */
    public function checkAllRecordsAvailable($id){
        //Check for row existance
        $account_scheme_row = $this->find('first', array('conditions' => array('FmFeAccountSchemeRow.id' => $id), 'recursive' => -1));
        if(empty($account_scheme_row)){
            return false;
        }

        //Check for records existance
        foreach ($this->FmFeAccountSchemeRecord->document_fields as $field_key => $field_title) {
            $field_count = $this->FmFeAccountSchemeRecord->find('count', array(
                'conditions' => array('FmFeAccountSchemeRecord.fm_fe_account_scheme_row_id' => $id, 'FmFeAccountSchemeRecord.document_field' => $field_key),
                'recursive' => -1
            ));
            if($field_count != 1){
                return false;
            }
        }
        return true;
    }//~!    

    /**
     * Get account scheme records and rows
     *
     * @throws nothing
     * @param $fm_fe_account_scheme_id - FmFeAccountScheme.id
     * @return boolean
     */
    public function getRows($fm_fe_account_scheme_id){
        return $this->find('all', array('conditions' => array('FmFeAccountSchemeRow.fm_fe_account_scheme_id' => $fm_fe_account_scheme_id), 'fields' => array('FmFeAccountSchemeRow.*'), 'recursive' => 1));
    }//~!    
}
?>