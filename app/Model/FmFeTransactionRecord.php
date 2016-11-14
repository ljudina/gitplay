<?php
class FmFeTransactionRecord extends AppModel{
	var $name = 'FmFeTransactionRecord'; 

    public $belongsTo = array(
        'FmFeTransaction' => array(
            'className' => 'FmFeTransaction',
            'foreignKey' => 'fm_fe_transaction_id'
        ),
        'FmFeTransactionEntry' => array(
            'className' => 'FmFeTransactionEntry',
            'foreignKey' => 'fm_fe_transaction_entry_id'
        ),        
        'FmChartAccount' => array(
            'className' => 'FmChartAccount',
            'foreignKey' => 'fm_chart_account_id'
        ),        
        'CodebookConnectionData' => array(
            'className' => 'CodebookConnectionData',
            'foreignKey' => 'codebook_connection_data_id'
        ),
        'CodebookDocumentType' => array(
            'className' => 'CodebookDocumentType',
            'foreignKey' => 'codebook_document_type_id'
        ),
        'PrimaryDocumentType' => array(
            'className' => 'CodebookDocumentType',
            'foreignKey' => 'primary_document_type_id'
        ),
        'SecondaryDocumentType' => array(
            'className' => 'CodebookDocumentType',
            'foreignKey' => 'secondary_document_type_id'
        ),
        'FmTrafficStatus' => array(
            'className' => 'FmTrafficStatus',
            'foreignKey' => 'fm_traffic_status_id'
        ),
        'Currency' => array(
            'className' => 'Currency',
            'foreignKey' => 'currency_id'
        )
    );

    public $validate = array(       
        'ordinal' => array(
            'ordinalRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Redni broj stavke nije definisan'
            ),
            'ordinalRule2' => array(
                'rule' => array('ordinalUniqueValidation'),
                'message' => 'Redni broj stavke nije jedinstven',
                'required' => true
            )
        ),     
        'fm_fe_transaction_id' => array(
            'fmFeTransactionIdRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Transakcija deviznog izvoda nije definisana'
            ),
            'fmFeTransactionIdRule2' => array(
                'rule' => array('fmFeTransactionValidation'),
                'message' => 'Transakcija deviznog izvoda nije validna',
                'required' => true
            )
        ),
        'fm_fe_transaction_entry_id' => array(
            'fmFeTransactionEntryIdRule1' => array(
                'rule' => array('fmFeTransactionEntryValidation'),
                'message' => 'Stavka za evidentiranje devizne transakcije nije validna',
                'required' => true
            )
        ),
        'fm_chart_account_id' => array(
            'fmChartAccountIdRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Konto nije definisan'
            ),
            'fmChartAccountIdRule2' => array(
                'rule' => array('fmChartAccountValidation'),
                'message' => 'Konto nije validan',
                'required' => true
            )
        ),        
        'codebook_connection_data_id' => array(
            'codebookConnectionDataIdRule1' => array(
                'rule' => array('codebookConnectionDataValidation'),
                'message' => 'Analitika nije validna',
                'required' => true
            )
        ),
        'codebook_document_type_id' => array(
            'codebookDocumentTypeIdRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Vrsta dokumenta nije definisana'
            ),
            'codebookDocumentTypeIdRule2' => array(
                'rule' => array('codebookDocumentTypeValidation'),
                'message' => 'Vrsta dokumenta nije validna',
                'required' => true
            )
        ),
        'codebook_document_code' => array(
            'codebookDocumentCodeRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Broj dokumenta nije definisan',
                'required' => true
            )
        ),
        'primary_document_type_id' => array(
            'primaryDocumentTypeIdRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Vrsta primarne veze nije definisana'
            ),
            'primaryDocumentTypeIdRule2' => array(
                'rule' => array('primaryDocumentTypeValidation'),
                'message' => 'Vrsta primarne veze nije validna',
                'required' => true
            )
        ),
        'primary_document_code' => array(
            'primaryDocumentCodeRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Broj primarne veze nije definisan',
                'required' => true
            )
        ),
        'secondary_document_type_id' => array(
            'secondaryDocumentTypeIdRule1' => array(
                'rule' => array('secondaryDocumentTypeValidation'),
                'message' => 'Vrsta sekundarne veze nije validna',
                'required' => true
            )
        ),
        'secondary_document_code' => array(
            'secondaryDocumentCodeRule1' => array(
                'rule' => array('secondaryDocumentCodeValidation'),
                'message' => 'Broj sekundarne veze nije validan',
                'required' => true
            )            
        ),
        'fm_traffic_status_id' => array(
            'fmTrafficStatusIdRule1' => array(
                'rule' => array('fmTrafficStatusValidation'),
                'message' => 'Status prometa nije validan',
                'required' => true
            )
        ),
        'currency_id' => array(
            'currencyIdRule1' => array(
                'rule' => array('currencyValidation'),
                'message' => 'Šifra valute nije validna',
                'required' => true
            )
        ),
        'foreign_debit' => array(
            'foreignDebitRule1' => array(
                'rule' => array('decimal', 2),
                'message' => 'Devizna stavka - DUGUJE nije validna',
                'required' => true,
                'allowEmpty' => true
            )
        ),
        'foreign_credit' => array(
            'foreignCreditRule1' => array(
                'rule' => array('decimal', 2),
                'message' => 'Devizna stavka - POTRAŽUJE nije validna',
                'required' => true,
                'allowEmpty' => true
            )
        ),
        'exchange_rate_date' => array(
            'exchangeRateDateRule1' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Datum kursa nije validan',
                'required' => true,
                'allowEmpty' => true
            )
        ),
        'exchange_rate' => array(
            'exchangeRateRule1' => array(
                'rule' => array('decimal', 4),
                'message' => 'Devizni kurs za preračun stavke nije validan',
                'required' => true,
                'allowEmpty' => true
            )
        ),
        'domestic_debit' => array(
            'domesticDebitRule1' => array(
                'rule' => array('decimal', 3),
                'message' => 'Stavka u RSD - DUGUJE nije validna',
                'required' => true,
                'allowEmpty' => true
            )
        ),
        'domestic_credit' => array(
            'domesticCreditRule1' => array(
                'rule' => array('decimal', 3),
                'message' => 'Stavka u RSD - POTRAŽUJE nije validna',
                'required' => true,
                'allowEmpty' => true
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
        if(empty($this->data['FmFeTransactionRecord']['id']) && empty($this->data['FmFeTransactionRecord']['ordinal'])){
            //Assign new ordinal
            $record_count = $this->find('count', array('conditions' => array(
                    'FmFeTransactionRecord.deleted' => 0,
                    'FmFeTransactionRecord.fm_fe_transaction_id' => $this->data['FmFeTransactionRecord']['fm_fe_transaction_id']
                ),
                'recursive' => -1
            ));
            $this->data['FmFeTransactionRecord']['ordinal'] = $record_count + 1;
        }

        //Convert to decimal
        if(!empty($this->data['FmFeTransactionRecord']['foreign_debit']) || 
            $this->data['FmFeTransactionRecord']['foreign_debit'] === '0' || 
            $this->data['FmFeTransactionRecord']['foreign_debit'] === 0
        ){
            $this->data['FmFeTransactionRecord']['foreign_debit'] = number_format(round($this->data['FmFeTransactionRecord']['foreign_debit'], 2), 2, '.', '');
        }else{
            $this->data['FmFeTransactionRecord']['foreign_debit'] = null;
        }
        if(!empty($this->data['FmFeTransactionRecord']['foreign_credit']) || 
            $this->data['FmFeTransactionRecord']['foreign_credit'] === '0' || 
            $this->data['FmFeTransactionRecord']['foreign_credit'] === 0
        ){
            $this->data['FmFeTransactionRecord']['foreign_credit'] = number_format(round($this->data['FmFeTransactionRecord']['foreign_credit'], 2), 2, '.', '');
        }else{
            $this->data['FmFeTransactionRecord']['foreign_credit'] = null;
        }
        if(!empty($this->data['FmFeTransactionRecord']['exchange_rate']) || 
            $this->data['FmFeTransactionRecord']['exchange_rate'] === '0' || 
            $this->data['FmFeTransactionRecord']['exchange_rate'] === 0
        ){
            $this->data['FmFeTransactionRecord']['exchange_rate'] = number_format(round($this->data['FmFeTransactionRecord']['exchange_rate'], 4), 4, '.', '');
        }else{
            $this->data['FmFeTransactionRecord']['exchange_rate'] = null;
        }

        if(!empty($this->data['FmFeTransactionRecord']['domestic_debit']) || 
            $this->data['FmFeTransactionRecord']['domestic_debit'] === '0' || 
            $this->data['FmFeTransactionRecord']['domestic_debit'] === 0
        ){
            $this->data['FmFeTransactionRecord']['domestic_debit'] = number_format(round($this->data['FmFeTransactionRecord']['domestic_debit'], 3), 3, '.', '');
        }else{
            $this->data['FmFeTransactionRecord']['domestic_debit'] = null;
        }
        if(!empty($this->data['FmFeTransactionRecord']['domestic_credit']) || 
            $this->data['FmFeTransactionRecord']['domestic_credit'] === '0' || 
            $this->data['FmFeTransactionRecord']['domestic_credit'] === 0
        ){
            $this->data['FmFeTransactionRecord']['domestic_credit'] = number_format(round($this->data['FmFeTransactionRecord']['domestic_credit'], 3), 3, '.', '');
        }else{
            $this->data['FmFeTransactionRecord']['domestic_credit'] = null;   
        }

        return true;
    }//~!       

    /**
     * Check if transaction ordinal is unique in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function ordinalUniqueValidation($check){
        $conditions = array(
            'FmFeTransactionRecord.fm_fe_transaction_id' => $this->data['FmFeTransactionRecord']['fm_fe_transaction_id'],
            'FmFeTransactionRecord.ordinal' => $this->data['FmFeTransactionRecord']['ordinal'],
            'FmFeTransactionRecord.deleted' => 0
        );
        if(!empty($this->data['FmFeTransactionRecord']['id'])){
            $conditions['NOT'] = array('FmFeTransactionRecord.id' => $this->data['FmFeTransactionRecord']['id']);
        }        
        $exists = $this->find('count', array('conditions' => $conditions));
        return ($exists > 0) ? false : true;
    }//~!

    /**
     * Check if foregin exchange transaction is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function fmFeTransactionValidation($check){
        $exists = $this->FmFeTransaction->find('count', array('conditions' => array('FmFeTransaction.id' => $this->data['FmFeTransactionRecord']['fm_fe_transaction_id']), 'recursive' => -1));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * If exist check if transaction entry is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function fmFeTransactionEntryValidation($check){
        if(!empty($this->data['FmFeTransactionRecord']['fm_fe_transaction_entry_id'])){
            $exists = $this->FmFeTransactionEntry->find('count', array('conditions' => array('FmFeTransactionEntry.id' => $this->data['FmFeTransactionRecord']['fm_fe_transaction_entry_id']), 'recursive' => -1));
            return ($exists > 0) ? true : false;
        }
        return true;
    }//~!

    /**
     * Check if chart account is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function fmChartAccountValidation($check){
        $exists = $this->FmChartAccount->find('count', array('conditions' => array('FmChartAccount.id' => $this->data['FmFeTransactionRecord']['fm_chart_account_id']), 'recursive' => -1));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * Check if codebook connection data is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function codebookConnectionDataValidation($check){
        $exists = $this->CodebookConnectionData->find('count', array(
            'conditions' => array('CodebookConnectionData.id' => $this->data['FmFeTransactionRecord']['codebook_connection_data_id']),
            'recursive' => -1
        ));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * Check if codebook document type is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function codebookDocumentTypeValidation($check){
        $exists = $this->CodebookDocumentType->find('count', array(
            'conditions' => array('CodebookDocumentType.id' => $this->data['FmFeTransactionRecord']['codebook_document_type_id']),
            'recursive' => -1
        ));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * Check if primary document type is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function primaryDocumentTypeValidation($check){
        $exists = $this->PrimaryDocumentType->find('count', array(
            'conditions' => array('PrimaryDocumentType.id' => $this->data['FmFeTransactionRecord']['primary_document_type_id']),
            'recursive' => -1
        ));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * If exists check if secondary document type is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function secondaryDocumentTypeValidation($check){
        if(!empty($this->data['FmFeTransactionRecord']['secondary_document_type_id'])){
            $exists = $this->SecondaryDocumentType->find('count', array(
                'conditions' => array('SecondaryDocumentType.id' => $this->data['FmFeTransactionRecord']['secondary_document_type_id']),
                'recursive' => -1
            ));
            return ($exists > 0) ? true : false;
        }
        return true;
    }//~!

    /**
     * If secondary document type exists check for secondary document code
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function secondaryDocumentCodeValidation($check){
        if(!empty($this->data['FmFeTransactionRecord']['secondary_document_type_id'])){            
            return !empty($this->data['FmFeTransactionRecord']['secondary_document_code']);
        }
        return true;
    }//~!

    /**
     * If exists check if traffic status is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function fmTrafficStatusValidation($check){
        if(!empty($this->data['FmFeTransactionRecord']['fm_traffic_status_id'])){
            $exists = $this->FmTrafficStatus->find('count', array(
                'conditions' => array('FmTrafficStatus.id' => $this->data['FmFeTransactionRecord']['fm_traffic_status_id']),
                'recursive' => -1
            ));
            return ($exists > 0) ? true : false;
        }
        return true;
    }//~!

    /**
     * If exists check if currency is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function currencyValidation($check){
        if(!empty($this->data['FmFeTransactionRecord']['currency_id'])){
            $exists = $this->Currency->find('count', array(
                'conditions' => array('Currency.id' => $this->data['FmFeTransactionRecord']['currency_id']),
                'recursive' => -1
            ));
            return ($exists > 0) ? true : false;
        }
        return true;
    }//~!    

    /**
     * Save record from form data
     *
     * @throws nothing
     * @param $form_data - FmFeTransactionRecord data array
     * @return $result with success or error message
     */
    public function saveTransactionRecord($form_data){
        //Init result
        $result = array();

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();        

        try {
            //Reset codebook connection data
            $form_data['FmFeTransactionRecord']['codebook_connection_data_id'] = null;

            //Get chart account and create connection data
            $chart_account = $this->FmChartAccount->find('first', array('conditions' => array('FmChartAccount.id' => $form_data['FmFeTransactionRecord']['fm_chart_account_id']), 'recursive' => -1));
            if(!empty($chart_account['FmChartAccount']['codebook_connection_id'])){
                //Get codebook connection
                $codebook_connection = $this->FmChartAccount->CodebookConnection->find('first', 
                    array(
                        'conditions' => array('CodebookConnection.id' => $chart_account['FmChartAccount']['codebook_connection_id']), 
                        'fields' => array('CodebookConnection.id', 'Codebook.model_name'),
                        'recursive' => 0
                    )
                );

                //Check for codebook existance
                if(empty($codebook_connection['Codebook']['model_name'])){
                    $this->validationErrors['fm_chart_account_id'] = "Konto nije validan";
                    throw new Exception('Šifra konta nije validna');
                }

                //Check if connection data already exists
                $connection_data = $this->CodebookConnectionData->find('first', 
                    array(
                        'conditions' => array(
                            'CodebookConnectionData.codebook_connection_id' => $codebook_connection['CodebookConnection']['id'],
                            'CodebookConnectionData.data_id' => $form_data['FmFeTransactionRecord']['data_id']
                        ),
                        'recursive' => -1
                    )
                );

                //If it does not exist create new one
                if(empty($connection_data)){
                    //Set connection data
                    $connection_data = array();
                    $connection_data['CodebookConnectionData']['codebook_connection_id'] = $codebook_connection['CodebookConnection']['id'];
                    $connection_data['CodebookConnectionData']['model_name'] = $codebook_connection['Codebook']['model_name'];
                    $connection_data['CodebookConnectionData']['data_id'] = $form_data['FmFeTransactionRecord']['data_id'];

                    $connection_data['CodebookConnectionData']['data_title'] = $this->FmChartAccount->CodebookConnection->getConnectionTitle($codebook_connection['CodebookConnection']['id'], $form_data['FmFeTransactionRecord']['data_id']); //Fetch title from codebook connection data
                    $connection_data['CodebookConnectionData']['data_code'] = $this->FmChartAccount->CodebookConnection->getConnectionCode($codebook_connection['CodebookConnection']['id'], $form_data['FmFeTransactionRecord']['data_id']); //Fetch code from codebook connection data

                    $this->CodebookConnectionData->create();
                    if(!$this->CodebookConnectionData->save($connection_data)){
                        $errors = $this->CodebookConnectionData->validationErrors;
                        throw new Exception('Greška: '.array_shift($errors)[0]);
                    }

                    $connection_data['CodebookConnectionData']['id'] = $this->CodebookConnectionData->id;
                }
                
                $form_data['FmFeTransactionRecord']['codebook_connection_data_id'] = $connection_data['CodebookConnectionData']['id'];
            }

            //If new record create one
            if(empty($form_data['FmFeTransactionRecord']['id'])){
                $this->create();
            }

            //Save to DB
            if(!$this->save($form_data)){
                $errors = $this->validationErrors;
                throw new Exception('Stavka ne može biti snimljena! Greška: '.array_shift($errors)[0]);
            }

            //Set new ID
            if(empty($form_data['FmFeTransactionRecord']['id'])){
                $form_data['FmFeTransactionRecord']['id'] = $this->id;
            }

            //Set return result
            $result = $form_data;
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
     * Save from transaction entry and populate fields connected to account scheme
     *
     * @throws nothing
     * @param $fm_fe_transaction_entry_id - FmFeTransactionEntry.id
     * @return $result with success or error message
     */
    public function saveFromEntry($fm_fe_transaction_entry_id){
        //Init result
        $result = array();    

        try {
            //Check for transaction entry
            $fm_fe_transaction_entry = $this->FmFeTransactionEntry->find('first', array('conditions' => array('FmFeTransactionEntry.id' => $fm_fe_transaction_entry_id), 'recursive' => -1));
            if(empty($fm_fe_transaction_entry)){
                throw new Exception('Evidencija devizne transakcije nije validna!');
            }

            //Check for transaction
            $fm_fe_transaction = $this->FmFeTransaction->getFeTransaction($fm_fe_transaction_entry['FmFeTransactionEntry']['fm_fe_transaction_id']);
            if(empty($fm_fe_transaction)){
                throw new Exception('Devizna transakcija nije validna!');
            }           

            //Check for foreign exchange
            $fm_fe_basic = $this->FmFeTransaction->FmFeBasic->getFeBasic($fm_fe_transaction['FmFeTransaction']['fm_fe_basic_id']);
            if(empty($fm_fe_basic)){
                throw new Exception('Devizni izvod nije validan!');
            }

            //Get account scheme
            $account_scheme = $this->FmFeTransaction->FmFeTransactionType->FmFeAccountScheme->getAccountScheme($fm_fe_transaction['FmFeTransactionType']['fm_fe_account_scheme_id']);
            if(empty($account_scheme)){
                throw new Exception('Šema knjiženja nije validna!');
            }

            //Set document fields
            $document_fields = $this->FmFeTransaction->FmFeTransactionType->FmFeAccountScheme->FmFeAccountSchemeRow->FmFeAccountSchemeRecord->document_fields;

            //If customer proform invoice get order
            if($fm_fe_transaction['FmFeTransaction']['payer_recipient'] == 'customer' && $fm_fe_transaction['FmFeTransaction']['transaction_type'] == 'by_proform'){
                $order = $this->FmFeTransaction->FmFeTransactionEntry->Order->find('first', array(
                    'conditions' => array('Order.id' => $fm_fe_transaction_entry['FmFeTransactionEntry']['order_id']),
                    'recursive' => -1
                ));
                if(empty($order)){
                    throw new Exception('Order nije validan!');
                }                
            }

            //Init transaction records
            $prev_record = array();
            $records = array();

            //Set ordinal from current count
            $current_ordinal = $this->find('count', array('conditions' => array(
                    'FmFeTransactionRecord.deleted' => 0,
                    'FmFeTransactionRecord.fm_fe_transaction_id' => $fm_fe_transaction['FmFeTransaction']['id']
                ),
                'recursive' => -1
            ));
            $ordinal = $current_ordinal + 1;

            //Build transaction records
            foreach ($account_scheme['FmFeAccountSchemeRow'] as $account_scheme_row) {
                //Check for conditions
                $create_row = false;
                switch ($account_scheme_row['FmFeAccountSchemeRow']['conditions']) {
                    case 'required':
                        $create_row = true;
                        break;                    
                    case 'bank_cost_exist':
                        if(!empty($fm_fe_transaction_entry['FmFeTransactionEntry']['foreign_bank_costs']) && $fm_fe_transaction_entry['FmFeTransactionEntry']['foreign_bank_costs'] != '0.00'){
                            $create_row = true;
                        }
                        break;
                    case 'exchange_diff_positive':
                        if($fm_fe_transaction_entry['FmFeTransactionEntry']['exchange_diff'] > 0){
                            $create_row = true;
                        }
                        break;      
                    case 'exchange_diff_negative':
                        if($fm_fe_transaction_entry['FmFeTransactionEntry']['exchange_diff'] < 0){
                            $create_row = true;
                        }
                        break;                        
                    default:
                        $create_row = false;
                        break;
                }

                //Check for row creation
                if($create_row){
                    //Init record array
                    $record = array();                    
                    $columns = array();
                    $post_divide_columns = array();
                    $post_multiply_columns = array();

                    //Init record defaults
                    $record['FmFeTransactionRecord']['ordinal'] = $ordinal;
                    $record['FmFeTransactionRecord']['fm_fe_transaction_id'] = $fm_fe_transaction['FmFeTransaction']['id'];
                    $record['FmFeTransactionRecord']['fm_fe_transaction_entry_id'] = $fm_fe_transaction_entry['FmFeTransactionEntry']['id'];

                    //Process scheme records
                    $col_no = 1;
                    foreach ($account_scheme_row['FmFeAccountSchemeRecord'] as $account_scheme_record) {
                        $field = $account_scheme_record['document_field'];                        

                        $value = null;
                        switch ($account_scheme_record['operation_used']) {
                            case 'fixed_value':
                                $value = $account_scheme_record['record_value'];
                                break;                         
                            case 'equals_col':
                                if(!empty($columns[$account_scheme_record['record_value']])){
                                    $value = $columns[$account_scheme_record['record_value']];
                                }                                
                                break;                                
                            case 'equal_prev_row':
                                if(!empty($prev_record)){                                    
                                    $value = $prev_record['FmFeTransactionRecord'][$field];
                                }                                
                                break;
                            case 'divide_fields':
                                if(!empty($columns[$account_scheme_record['arithmetic_second_col']]) && !empty($columns[$account_scheme_record['arithmetic_first_col']])){
                                    $value = $columns[$account_scheme_record['arithmetic_first_col']] / $columns[$account_scheme_record['arithmetic_second_col']];
                                }else{
                                    $post_divide_columns[$col_no]['field'] = $field;
                                    $post_divide_columns[$col_no]['arithmetic_first_col'] = $account_scheme_record['arithmetic_first_col'];
                                    $post_divide_columns[$col_no]['arithmetic_second_col'] = $account_scheme_record['arithmetic_second_col'];
                                }
                                break;
                            case 'multiply_fields':
                                if(!empty($columns[$account_scheme_record['arithmetic_second_col']]) && !empty($columns[$account_scheme_record['arithmetic_first_col']])){
                                    $value = $columns[$account_scheme_record['arithmetic_first_col']] * $columns[$account_scheme_record['arithmetic_second_col']];
                                }else{
                                    $post_multiply_columns[$col_no]['field'] = $field;
                                    $post_multiply_columns[$col_no]['arithmetic_first_col'] = $account_scheme_record['arithmetic_first_col'];
                                    $post_multiply_columns[$col_no]['arithmetic_second_col'] = $account_scheme_record['arithmetic_second_col'];
                                }
                                break;                                
                            case 'equal_codebook':               
                                    $value = $account_scheme_record['record_value'];
                                break;
                            case 'equal_document_link':
                                    $this->FmFeDocumentLink = $this->FmFeTransaction->FmFeTransactionType->FmFeAccountScheme->FmFeAccountSchemeRow->FmFeAccountSchemeRecord->FmFeDocumentLink;
                                    $document_link = $this->FmFeDocumentLink->find('first', array(
                                        'conditions' => array('FmFeDocumentLink.id' => $account_scheme_record['fm_fe_document_link_id'], 'FmFeDocumentLink.active' => 1),
                                        'recursive' => -1
                                    ));
                                    if(!empty($document_link)){
                                        if($document_link['FmFeDocumentLink']['model_name'] == 'FmFeTransaction'){
                                            if(isset($fm_fe_transaction['FmFeTransaction'][$document_link['FmFeDocumentLink']['model_field']])){
                                                $value = $fm_fe_transaction['FmFeTransaction'][$document_link['FmFeDocumentLink']['model_field']];
                                            }                                            
                                        }
                                        if($document_link['FmFeDocumentLink']['model_name'] == 'FmBusinessAccount'){
                                            if(isset($fm_fe_basic['FmBusinessAccount'][$document_link['FmFeDocumentLink']['model_field']])){
                                                $value = $fm_fe_basic['FmBusinessAccount'][$document_link['FmFeDocumentLink']['model_field']];
                                            }
                                        }
                                        if($document_link['FmFeDocumentLink']['model_name'] == 'FmFeTransactionEntry'){
                                            if(isset($fm_fe_transaction_entry['FmFeTransactionEntry'][$document_link['FmFeDocumentLink']['model_field']])){
                                                $value = $fm_fe_transaction_entry['FmFeTransactionEntry'][$document_link['FmFeDocumentLink']['model_field']];
                                            }
                                        }
                                        if($document_link['FmFeDocumentLink']['model_name'] == 'FmFeTransactionType'){
                                            if(isset($fm_fe_transaction['FmFeTransactionType'][$document_link['FmFeDocumentLink']['model_field']])){
                                                $value = $fm_fe_transaction['FmFeTransactionType'][$document_link['FmFeDocumentLink']['model_field']];
                                            }                                            
                                        }
                                        if($document_link['FmFeDocumentLink']['model_name'] == 'FmFeBasic'){
                                            if(isset($fm_fe_basic['FmFeBasic'][$document_link['FmFeDocumentLink']['model_field']])){
                                                $value = $fm_fe_basic['FmFeBasic'][$document_link['FmFeDocumentLink']['model_field']];
                                            } 
                                        }                                       
                                        if($document_link['FmFeDocumentLink']['model_name'] == 'Order'){
                                            if(isset($order['Order'][$document_link['FmFeDocumentLink']['model_field']])){
                                                $value = $order['Order'][$document_link['FmFeDocumentLink']['model_field']];
                                            }
                                        }                                      
                                    }
                                break;                                                                
                            default:
                                $value = null;
                                break;
                        }

                        //Process connection data
                        if($field == 'codebook_connection_data_id' && !empty($value) && $account_scheme_record['operation_used'] != 'prev_record'){
                            //Convert to codebook connection data id
                            $codebook_connection_id = null;

                            //Get chart account
                            $chart_account = $this->FmChartAccount->find('first', array('conditions' => array('FmChartAccount.id' => $record['FmFeTransactionRecord']['fm_chart_account_id']), 'recursive' => -1));
                            if(empty($chart_account)){
                                throw new Exception('Šifra konta nije validna!');
                            }
                            if(empty($chart_account['FmChartAccount']['codebook_connection_id'])){
                                throw new Exception('Veza konta i šifarnika nije validna!');
                            }

                            $codebook_connection_id = $chart_account['FmChartAccount']['codebook_connection_id'];

                            //Get codebook connection
                            $codebook_connection = $this->CodebookConnectionData->CodebookConnection->find('first', array('conditions' => array(
                                'CodebookConnection.id' => $codebook_connection_id), 
                                'recursive' => 0
                            ));
                            if(empty($codebook_connection)){
                                throw new Exception('Veza sa šifarnikom nije validna!');
                            }

                            //Get connection data
                            $connection_data = $this->CodebookConnectionData->find('first', array(
                                'conditions' => array(
                                    'CodebookConnectionData.codebook_connection_id' => $codebook_connection_id, 
                                    'CodebookConnectionData.data_id' => $value
                                ),
                                'fields' => array('CodebookConnectionData.id'),
                                'recursive' => -1
                            ));
                            if(empty($connection_data)){
                                //Create new codebook connection data
                                $new_codebook_connection_data = array();
                                $new_codebook_connection_data['CodebookConnectionData']['codebook_connection_id'] = $codebook_connection_id;
                                $new_codebook_connection_data['CodebookConnectionData']['model_name'] = $codebook_connection['Codebook']['model_name'];
                                $new_codebook_connection_data['CodebookConnectionData']['data_id'] = $value;

                                //Get data title and code
                                $new_codebook_connection_data['CodebookConnectionData']['data_title'] = $this->FmChartAccount->CodebookConnection->getConnectionTitle($codebook_connection_id, $value);
                                $new_codebook_connection_data['CodebookConnectionData']['data_code'] = $this->FmChartAccount->CodebookConnection->getConnectionCode($codebook_connection_id, $value);

                                //Create new connection in db
                                $this->CodebookConnectionData->create();
                                if(!$this->CodebookConnectionData->save($new_codebook_connection_data)){
                                    $errors = $this->CodebookConnectionData->validationErrors;
                                    throw new Exception('Analitika ne može biti kreirana: '.array_shift($errors)[0]);
                                }
                                $connection_data['CodebookConnectionData']['id'] = $this->CodebookConnectionData->id;
                            }

                            //Set connection data
                            $value = $connection_data['CodebookConnectionData']['id'];
                        }

                        //Check for absolute flag
                        if(!empty($account_scheme_record['absolute_value'])){
                            $value = abs($value);                                            
                        }

                        //Check for negative flag
                        if(!empty($account_scheme_record['negative_value'])){
                            $value = -abs($value);
                        }

                        //Set record value
                        $record['FmFeTransactionRecord'][$field] = $value;
                        $columns[$col_no] = $value;

                        $col_no++;
                    }

                    //Process post divide columns
                    if(!empty($post_divide_columns)){
                        foreach ($post_divide_columns as $col_no => $column) {
                            $field = $column['field'];
                            $arithmetic_first_col = $column['arithmetic_first_col'];
                            $arithmetic_second_col = $column['arithmetic_second_col'];                            

                            if(!empty($columns[$arithmetic_first_col]) && !empty($columns[$arithmetic_second_col])){
                                if($columns[$arithmetic_second_col] != '0'){
                                    $record['FmFeTransactionRecord'][$field] = $columns[$arithmetic_first_col] / $columns[$arithmetic_second_col];
                                }                                
                            }
                        }
                    }

                    //Process post multiply columns
                    if(!empty($post_multiply_columns)){
                        foreach ($post_multiply_columns as $col_no => $column) {
                            $field = $column['field'];
                            $arithmetic_first_col = $column['arithmetic_first_col'];
                            $arithmetic_second_col = $column['arithmetic_second_col'];                            

                            if(!empty($columns[$arithmetic_first_col]) && !empty($columns[$arithmetic_second_col])){
                                $record['FmFeTransactionRecord'][$field] = $columns[$arithmetic_first_col] * $columns[$arithmetic_second_col];                             
                            }
                        }
                    }

                    //Save record
                    $records[] = $record;

                    //Increment ordinal
                    $ordinal++;

                    //Set previous records
                    $prev_record = $record;
                }
            }

            //Check if any record is defined
            if(empty($records)){
                throw new Exception('Nijedan stavka u obrazcu za knjiženje deviznih transakcija nije definisana!');
            }

            //Save records to DB
            if(!$this->saveMany($records)){
                $errors = $this->validationErrors;
                $error_field = array_shift($errors);
                $error_msg = array_shift($error_field);
                throw new Exception('Stavke u obrascu za knjiženje nisu snimljene! Greška: '.array_shift($error_msg));
            }            

            //Set return result
            $result = $records;
            $result['success'] = true;
        } catch (Exception $e) {
            //Save error message
            $result['success'] = false;
            $result['message'] = $e->getMessage();          
        }

        return $result;         
    }//~!

    /**
     * Get all records for transaction
     *
     * @throws nothing
     * @param $fm_fe_transaction_id - FmFeTransaction.id
     * @return $array with transaction records
     */    
    public function getAllTransactionRecords($fm_fe_transaction_id){
        return $this->find('all', array('conditions' => array('FmFeTransactionRecord.fm_fe_transaction_id' => $fm_fe_transaction_id, 'FmFeTransactionRecord.deleted' => 0), 'recursive' => 0));
    }//~!

    /**
     * Get transaction records sum
     *
     * @throws nothing
     * @param $fm_fe_transaction_id - FmFeTransaction.id, $sum_params - array('currency_group' - sum group by currency)
     * @return $array with transaction records sum
     */    
    public function getTransactionRecordsSum($fm_fe_transaction_id, $sum_params=array()){
        $result = array();

        //Set parameters
        $params = array('currency_group');

        //Read optional parameters
        foreach ($params as $parameter) {
            //Set data
            if(!empty($sum_params[$parameter]))
                ${$parameter} = $sum_params[$parameter];
            else
                ${$parameter} = null;
        }

        //Init group
        $group_by = array('FmFeTransactionRecord.fm_fe_transaction_id');
        if($currency_group){
            $group_by[] = 'FmFeTransactionRecord.currency_id';
        }

        //Set virtual fields
        $this->virtualFields = array(
            'sum_foreign_debit' => 'SUM(FmFeTransactionRecord.foreign_debit)',
            'sum_foreign_credit' => 'SUM(FmFeTransactionRecord.foreign_credit)',
            'sum_domestic_debit' => 'SUM(FmFeTransactionRecord.domestic_debit)',
            'sum_domestic_credit' => 'SUM(FmFeTransactionRecord.domestic_credit)'
        );
        //Get results
        $result = $this->find('all', array(
            'conditions' => array(
                'FmFeTransactionRecord.fm_fe_transaction_id' => $fm_fe_transaction_id,
                'FmFeTransactionRecord.deleted' => 0
            ), 
            'group' => $group_by,
            'fields' => array(
                'FmFeTransactionRecord.sum_foreign_debit',
                'FmFeTransactionRecord.sum_foreign_credit',
                'FmFeTransactionRecord.sum_domestic_debit',
                'FmFeTransactionRecord.sum_domestic_credit',
                'Currency.iso'
            ),
            'recursive' => 0
        ));
        $this->virtualFields = array();                  

        return $result;
    }//~!

    /**
     * Set record as deleted
     *
     * @throws nothing
     * @param $fm_fe_transaction_record_id - FmFrTransactionRecord.id
     * @return $result with success or error message and deleted record
     */    
    public function deleteRecord($fm_fe_transaction_record_id){        
        //Init result
        $result = array();    

        try {
            //Get record
            $record = $this->find('first', array('conditions' => array('FmFeTransactionRecord.id' => $fm_fe_transaction_record_id), 'recursive' => -1));            
            if(empty($record)){
                throw new Exception('Stavka obrasca nije validna!');
            }

            //Check if deleted
            if(!empty($record['FmFeTransactionRecord']['deleted'])){
                throw new Exception('Stavka obrasca je već obrisana!');
            } 

            //Set as deleted
            $record['FmFeTransactionRecord']['deleted'] = 1;

            //Save to DB
            if(!$this->save($record)){
                $errors = $this->validationErrors;
                throw new Exception('Stavka obrasca ne može biti obrisana! Greška: '.array_shift($errors)[0]);
            }

            //Set return result
            $result = $record;
            $result['success'] = true;
        } catch (Exception $e) {
            //Save error message
            $result['success'] = false;
            $result['message'] = $e->getMessage();          
        }

        return $result;         
    }//~!

    /**
     * Set all transaction records as deleted
     *
     * @throws nothing
     * @param $fm_fe_transaction_id - FmFeTransaction.id
     * @return $result with success or error message and deleted record
     */    
    public function deleteAllTransactionRecords($fm_fe_transaction_id){
        //Init result
        $result = array();    

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();

        try {
            //Get record
            $records = $this->find('all', array(
                'conditions' => array('FmFeTransactionRecord.fm_fe_transaction_id' => $fm_fe_transaction_id, 'FmFeTransactionRecord.deleted' => 0),
                'recursive' => -1
            ));
            if(empty($records)){
                throw new Exception('Stavke obrasca nisu validne!');
            }

            //Update records
            $updated_records = $this->updateAll(
                array('FmFeTransactionRecord.deleted' => 1),
                array('FmFeTransactionRecord.fm_fe_transaction_id' => $fm_fe_transaction_id)
            );
            if(empty($updated_records)){
                throw new Exception('Greška prilikom storniranja obrasca za knjiženje!');
            }

            //Update transaction entries
            $updated_entries = $this->updateAll(
                array('FmFeTransactionEntry.deleted' => 1),
                array('FmFeTransactionEntry.fm_fe_transaction_id' => $fm_fe_transaction_id)
            );
            if(empty($updated_entries)){
                throw new Exception('Greška prilikom storniranja obrasca za evidentiranje!');
            }

            //Set return result
            $result = $records;
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
}
?>