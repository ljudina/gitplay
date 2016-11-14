<?php
class FmFeAccountSchemeRecord extends AppModel{
	var $name = 'FmFeAccountSchemeRecord';

    var $document_fields = array(
        'fm_chart_account_id' => "Šifra konta",
        'codebook_connection_data_id' => "Šifra analitike",
        'codebook_document_type_id' => "Vrsta dokumenta",
        'codebook_document_code' => "Broj dokumenta",
        'transaction_desc' => "Opis transakcije",
        'primary_document_type_id' => "Vrsta primarne veze",
        'primary_document_code' => "Broj primarne veze",
        'secondary_document_type_id' => "Vrsta sekundarne veze",
        'secondary_document_code' => "Broj sekundarne veze",
        'fm_traffic_status_id' => "Šifra klasifikacije",
        'currency_id' => "Šifra devizne valute",
        'foreign_debit' => "Devizna stavka - DUGUJE",
        'foreign_credit' => "Devizna stavka - POTRAŽUJE",
        'exchange_rate_date' => "Datum kursa",
        'exchange_rate' => "Devizni kurs za preračun stavke",
        'domestic_debit' => "Stavka u RSD - DUGUJE",
        'domestic_credit' => "Stavka u RSD - POTRAŽUJE"
    );

    var $document_field_no = array(
        1 => 'fm_chart_account_id',
        2 => 'codebook_connection_data_id',
        3 => 'codebook_document_type_id',
        4 => 'codebook_document_code',
        5 => 'transaction_desc',
        6 => 'primary_document_type_id',
        7 => 'primary_document_code',
        8 => 'secondary_document_type_id',
        9 => 'secondary_document_code',
        10 => 'fm_traffic_status_id',
        11 => 'currency_id',
        12 => 'foreign_debit',
        13 => 'foreign_credit',
        14 => 'exchange_rate_date',
        15 => 'exchange_rate',
        16 => 'domestic_debit',
        17 => 'domestic_credit'
    );

    var $used_operations = array(
        'fixed_value' => "Fiksna vrednost",
        'equals_col' => "Jednako koloni",
        'equal_prev_row' => "Jednako je prethodnom polju",
        'no_data' => "Bez podatka",
        'divide_fields' => "Podeli kolone", 
        'multiply_fields' => "Pomnoži kolone",
        'equal_codebook' => "Veži sa šifarnikom", 
        'equal_document_link' => "Veži sa dokumentom" 
    );

    public $belongsTo = array(
        'FmFeAccountSchemeRow' => array(
            'className' => 'FmFeAccountSchemeRow',
            'foreignKey' => 'fm_fe_account_scheme_row_id'
        ),
        'CodebookConnection' => array(
            'className' => 'CodebookConnection',
            'foreignKey' => 'codebook_connection_id'
        ),
        'FmFeDocumentLink' => array(
            'className' => 'FmFeDocumentLink',
            'foreignKey' => 'fm_fe_document_link_id'
        )
    );

    public $validate = array(
        'fm_fe_account_scheme_row_id' => array(
            'fmFeAccountSchemeRowIdRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Red šeme za knjiženje nije definisan',
                'required' => true
            ),            
            'fmFeAccountSchemeRowIdRule2' => array(
                'rule' => array('accountSchemeRowValidation'),
                'message' => 'Red šeme za knjiženje nije validan',
                'required' => true
            )
        ),
        'codebook_connection_id' => array(
            'codebookConnectionIdRule1' => array(
                'rule' => array('codebookConnectionValidation'),
                'message' => 'Veza sa šifarnikom nije validna',
                'required' => true
            )
        ),
        'fm_fe_document_link_id' => array(
            'fmFeDocumentLinkIdRule1' => array(
                'rule' => array('documentLinkValidation'),
                'message' => 'Veza sa dokumentom nije validna',
                'required' => true
            )
        ),
        'document_field' => array(
            'documentFieldRule1' => array(
                'rule' => array('documentFieldValidation'),
                'message' => 'Polje zapisa nije validno',
                'required' => true
            )
        ),
        'operation_used' => array(
            'operationUsedRule1' => array(
                'rule' => array('operationUsedValidation'),
                'message' => 'Operacija nad povezivanjem nije validna',
                'required' => true
            )
        ),
        'record_value' => array(
            'recordValueRule1' => array(
                'rule' => array('recordValueExistance'),
                'message' => 'Vrednost polja nije definisana',
                'required' => true
            ),
            'recordValueRule2' => array(
                'rule' => array('recordValueValidation'),
                'message' => 'Vrednost polja nije validna',
                'required' => true
            )            
        ),
        'arithmetic_first_col' => array(
            'arithmeticFirstColRule1' => array(
                'rule' => array('arithmeticFirstColCheck'),
                'message' => 'Prvo polje za aritmetičku operaciju nije definisano',
                'required' => true
            ),
            'arithmeticFirstColRule2' => array(
                'rule' => array('arithmeticFirstRangeCheck'),
                'message' => 'Prvo polje za aritmetičku operaciju nije u opsegu od 1 do 16',
                'required' => true
            )            
        ),
        'arithmetic_second_col' => array(
            'arithmeticSecondColRule1' => array(
                'rule' => array('arithmeticSecondColCheck'),
                'message' => 'Drugo polje za aritmetičku operaciju nije definisano',
                'required' => true
            ),
            'arithmeticSecondColRule2' => array(
                'rule' => array('arithmeticSecondRangeCheck'),
                'message' => 'Drugo polje za aritmetičku operaciju nije u opsegu od 1 do 16',
                'required' => true
            )            
        )
	);

    /**
     * Check if account scheme row exists in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function accountSchemeRowValidation($check){
        $exists = $this->FmFeAccountSchemeRow->find('count', array('conditions' => array('FmFeAccountSchemeRow.id' => $this->data['FmFeAccountSchemeRecord']['fm_fe_account_scheme_row_id'])));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * Check if codebook connection exists in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function codebookConnectionValidation($check){
        if(in_array($this->data['FmFeAccountSchemeRecord']['operation_used'], array('equal_codebook'))){
            $exists = $this->CodebookConnection->find('count', array('conditions' => array('CodebookConnection.id' => $this->data['FmFeAccountSchemeRecord']['codebook_connection_id'])));
            return ($exists > 0) ? true : false;
        }
        return true;
    }//~!

    /**
     * Check if document link exists in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function documentLinkValidation($check){
        if(in_array($this->data['FmFeAccountSchemeRecord']['operation_used'], array('equal_document_link'))){
            $exists = $this->FmFeDocumentLink->find('count', array('conditions' => array('FmFeDocumentLink.id' => $this->data['FmFeAccountSchemeRecord']['fm_fe_document_link_id'])));
            return ($exists > 0) ? true : false;
        }
        return true;
    }//~!

    /**
     * Check if document field is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function documentFieldValidation($check){
        return array_key_exists($this->data['FmFeAccountSchemeRecord']['document_field'], $this->document_fields);
    }//~!

    /**
     * Check if operation used is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function operationUsedValidation($check){
        return array_key_exists($this->data['FmFeAccountSchemeRecord']['operation_used'], $this->used_operations);
    }//~!

    /**
     * Check if record value exists for certain operations
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function recordValueExistance($check){        
        if(in_array($this->data['FmFeAccountSchemeRecord']['operation_used'], array('fixed_value', 'equal_codebook', 'equals_col'))){
            if(isset($this->data['FmFeAccountSchemeRecord']['record_value'])){
                if(empty($this->data['FmFeAccountSchemeRecord']['record_value']) && 
                   $this->data['FmFeAccountSchemeRecord']['record_value'] !== '0' && 
                   $this->data['FmFeAccountSchemeRecord']['record_value'] !== 0){
                    return false;
                }
            }else{
                return false;
            }
        }
        return true;
    }//~!    

    /**
     * Check if first column for arithmetic operation exists for certain operations
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function arithmeticFirstColCheck($check){
        if(in_array($this->data['FmFeAccountSchemeRecord']['operation_used'], array('divide_fields'))){
            return !empty($this->data['FmFeAccountSchemeRecord']['arithmetic_first_col']);
        }
        return true;
    }//~!
    
    /**
     * Check if second column for arithmetic operation exists for certain operations
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function arithmeticSecondColCheck($check){
        if(in_array($this->data['FmFeAccountSchemeRecord']['operation_used'], array('divide_fields'))){
            return !empty($this->data['FmFeAccountSchemeRecord']['arithmetic_second_col']);
        }
        return true;
    }//~!

    /**
     * Check if first column for arithmetic operation is in range
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function arithmeticFirstRangeCheck($check){
        if(in_array($this->data['FmFeAccountSchemeRecord']['operation_used'], array('divide_fields'))){
            return $this->data['FmFeAccountSchemeRecord']['arithmetic_first_col'] >= 1 && $this->data['FmFeAccountSchemeRecord']['arithmetic_first_col'] <= count($this->document_fields);
        }
        return true;
    }//~!

    /**
     * Check if second column for arithmetic operation is in range
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function arithmeticSecondRangeCheck($check){
        if(in_array($this->data['FmFeAccountSchemeRecord']['operation_used'], array('divide_fields'))){
            return $this->data['FmFeAccountSchemeRecord']['arithmetic_second_col'] >= 1 && $this->data['FmFeAccountSchemeRecord']['arithmetic_second_col'] <= count($this->document_fields);
        }
        return true;
    }//~!
    
    /**
     * Check if record value validaion for certain operations
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function recordValueValidation($check){
        if($this->data['FmFeAccountSchemeRecord']['operation_used'] == 'equal_codebook'){
            return $this->CodebookConnection->checkConnectionExists($this->data['FmFeAccountSchemeRecord']['codebook_connection_id'], $this->data['FmFeAccountSchemeRecord']['record_value']);
        }
        return true;
    }//~!
}
?>