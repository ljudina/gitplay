<?php
class FmFeTransactionEntry extends AppModel{
	var $name = 'FmFeTransactionEntry'; 
    var $entry_statuses = array('opened' => 'Otvoren', 'closed' => 'Zatvoren');
    var $account_manners = array('proform' => "valuta po PF", 'foreign_exchange' => "valuta naplate");
    var $transaction_type_manners = array('by_proform' => array('proform' => "valuta po PF", 'foreign_exchange' => "valuta naplate"));

    public $belongsTo = array(
        'FmFeTransaction' => array(
            'className' => 'FmFeTransaction',
            'foreignKey' => 'fm_fe_transaction_id'
        ),
        'FmChartAccount' => array(
            'className' => 'FmChartAccount',
            'foreignKey' => 'fm_chart_account_id'
        ),
        'FmTrafficStatus' => array(
            'className' => 'FmTrafficStatus',
            'foreignKey' => 'fm_traffic_status_id'
        ),
        'FmAccountOrderRecord' => array(
            'className' => 'FmAccountOrderRecord',
            'foreignKey' => 'fm_account_order_record_id'
        ),
        'Order' => array(
            'className' => 'Order',
            'foreignKey' => 'order_id'
        ),
        'PrimaryDocumentType' => array(
            'className' => 'CodebookDocumentType',
            'foreignKey' => 'primary_document_type_id'
        ),
        'SecondaryDocumentType' => array(
            'className' => 'CodebookDocumentType',
            'foreignKey' => 'secondary_document_type_id'
        ),
        'Currency' => array(
            'className' => 'Currency',
            'foreignKey' => 'currency_id'
        )
    );

    public $hasMany = array(
        'FmFeTransactionRecord' => array(
            'className' => 'FmFeTransactionRecord',
            'foreignKey' => 'fm_fe_transaction_record_id'
        )
    );    

    public $validate = array(        
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
        'fm_chart_account_id' => array(
            'fmChartAccountIdRule1' => array(
                'rule' => array('fmChartAccountValidation'),
                'message' => 'Konto nije validan',
                'required' => true
            )
        ),
        'fm_traffic_status_id' => array(
            'fmTrafficStatusIdRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Status prometa nije definisan'
            ),
            'fmTrafficStatusIdRule2' => array(
                'rule' => array('fmTrafficStatusValidation'),
                'message' => 'Status prometa nije validan',
                'required' => true
            )
        ),
        'fm_account_order_record_id' => array(
            'fmAccountOrderRecordIdRule1' => array(
                'rule' => array('accountOrderRecordValidation'),
                'message' => 'Veza sa nalogom GK nije validna',
                'required' => true
            )
        ),
        'primary_document_type_id' => array(
            'primaryDocumentTypeIdRule1' => array(
                'rule' => array('primaryDocumentTypeValidation'),
                'message' => 'Vrsta dokumenta za primarnu vezu nije validna',
                'required' => true
            )
        ),
        'primary_document_code' => array(
            'primaryDocumentCodeRule1' => array(
                'rule' => array('primaryDocumentCodeValidation'),
                'message' => 'Broj dokumenta za našu vezu nije validan',
                'required' => true
            )
        ),
        'secondary_document_type_id' => array(
            'secondaryDocumentTypeIdRule1' => array(
                'rule' => array('secondaryDocumentTypeValidation'),
                'message' => 'Vrsta dokumenta za eksternu vezu nije validna',
                'required' => true
            )
        ),
        'secondary_document_code' => array(
            'secondaryDocumentCodeRule1' => array(
                'rule' => array('secondaryDocumentCodeValidation'),
                'message' => 'Broj dokumenta za sekundarnu vezu nije validan',
                'required' => true
            )
        ),
        'foreign_transaction_value' => array(
            'foreignTransactionValueRule1' => array(
                'rule' => array('decimal', 2),
                'message' => 'Devizni iznos doznačenih sredstava nije validan',
                'required' => true
            )
        ),
        'foreign_bank_costs' => array(
            'transactionValueRsdRule1' => array(
                'rule' => array('decimal', 2),
                'message' => 'Devizni iznos bankarske provizije nije validan',
                'required' => true
            )
        ),
        'foreign_diff' => array(
            'foreignDiffRule1' => array(
                'rule' => array('decimal', 2),
                'message' => 'Devizni iznos priliva/odliva na t.r. nije validan',
                'required' => true
            ),
            'foreignDiffRule2' => array(
                'rule' => array('foreignDiffComparison'),
                'message' => 'Devizni iznos priliva/odliva na t.r. mora biti veći od nule',
                'required' => true
            )
        ),
        'domestic_value_exchange' => array(
            'domesticValueExchangeRule1' => array(
                'rule' => array('decimal', 3),
                'message' => 'Dinarski iznos doznačenih sredstava na dan izvoda nije validan',
                'required' => true
            ),
            'domesticValueExchangeRule2' => array(
                'rule' => array('domesticValueExchangeComparison'),
                'message' => 'Dinarski iznos doznačenih sredstava na dan izvoda mora biti veći od nule',
                'required' => true
            )            
        ),
        'domestic_value_invoice' => array(
            'domesticValueInvoiceRule1' => array(
                'rule' => array('domesticValueInvoiceCheck'),
                'message' => 'Dinarski iznos doznačenih sredstava na dan zaduženja nije validan',
                'required' => true
            ),
            'domesticValueInvoiceRule2' => array(
                'rule' => array('domesticValueInvoiceComparison'),
                'message' => 'Dinarski iznos doznačenih sredstava na dan zaduženja mora biti veći od nule',
                'required' => true
            )            
        ),
        'exchange_diff' => array(
            'exchangeDiffRule1' => array(
                'rule' => array('exchangeDiffCheck'),
                'message' => 'Kursna razlika nije validna',
                'required' => true                
            )
        ),
        'bank_commision' => array(
            'bankCommisionRule1' => array(
                'rule' => array('decimal', 3),
                'message' => 'Provizija banke u RSD nije validna',
                'required' => true
            ),
            'bankCommisionRule2' => array(
                'rule' => array('comparison', '>=', 0),
                'message' => 'Provizija banke u RSD mora biti veća ili jednaka nuli'
            )            
        ),
        'domestic_foreign_conversion' => array(
            'domesticForeignConversionRule1' => array(
                'rule' => array('decimal', 3),
                'message' => 'Preračun sredstava u valutu nije validan',
                'required' => true
            ),
            'domesticForeignConversionRule2' => array(
                'rule' => array('domesticForeignConversionComparison'),
                'message' => 'Preračun sredstava u valutu biti veći od nule',
                'required' => true
            )
        ),
        'invoice_exchange_diff' => array(
            'invoiceExchangeDiffRule1' => array(
                'rule' => array('invoiceExchangeDiffCheck'),
                'message' => 'Razlika nenaplaćenog iznosa i iznosa naplate nije validna',
                'required' => true                 
            )            
        ),
        'invoice_exchange_diff' => array(
            'invoiceExchangeDiffRule1' => array(
                'rule' => array('invoiceExchangeDiffCheck'),
                'message' => 'Razlika nenaplaćenog iznosa i iznosa naplate nije validna',
                'required' => true                 
            )            
        ),
        'account_manner' => array(
            'accountMannerRule1' => array(
                'rule' => array('accountMannerCheck'),
                'message' => 'Način rasknjiženja nije validan',
                'required' => true                 
            )            
        ),        
        'currency_id' => array(
            'currencyIdRule1' => array(
                'rule' => array('currencyValidation'),
                'message' => 'Valuta za rasknjiženje nije validna',
                'required' => true
            )
        ),        
        'foreign_exchange_rate' => array(
            'foreignExchangeRateRule1' => array(
                'rule' => array('foreignExchangeRateCheck'),
                'message' => 'Devizni kurs nije validan',
                'required' => true                     
            )
        ),
        'foreign_total' => array(
            'recordForeignTotalRule1' => array(
                'rule' => array('decimal', 2),
                'message' => 'Ukupan nenaplaćeni iznos nije validan',
                'required' => true
            )
        ),
        'domestic_total' => array(
            'domesticTotalRule1' => array(
                'rule' => array('decimal', 3),
                'message' => 'Ukupan nenaplaćeni iznos u RSD nije validan',
                'required' => true
            )
        ),
        'foreign_total_converted' => array(
            'recordForeignTotalConvertedRule1' => array(
                'rule' => array('decimal', 2),
                'message' => 'Ukupan nenaplaćeni iznos u valuti naplate nije validan',
                'required' => true
            )
        ),
        'foreign_paid' => array(            
            'recordForeignPaidRule1' => array(
                'rule' => array('decimal', 2),
                'message' => 'Naplaćen iznos nije validan',
                'required' => true
            )
        ),
        'entry_status' => array(
            'entryStatusRule1' => array(
                'rule' => array('entryStatusValidation'),
                'message' => 'Status stavke nije validan',
                'required' => true
            )
        )
	);

    /**
     * Function for pre-validation logic
     *
     * @throws nothing
     * @param $options = array() with option parameters
     * @return boolean
     */
    public function beforeValidate($options = array()) {
        //Convert to decimal
        if(!empty($this->data['FmFeTransactionEntry']['foreign_transaction_value']) || 
            $this->data['FmFeTransactionEntry']['foreign_transaction_value'] == '0' || 
            $this->data['FmFeTransactionEntry']['foreign_transaction_value'] == 0
        ){
            $this->data['FmFeTransactionEntry']['foreign_transaction_value'] = number_format(round($this->data['FmFeTransactionEntry']['foreign_transaction_value'], 2), 2, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['foreign_transaction_value'] = null;
        }
        if(!empty($this->data['FmFeTransactionEntry']['foreign_bank_costs']) || 
            $this->data['FmFeTransactionEntry']['foreign_bank_costs'] == '0' || 
            $this->data['FmFeTransactionEntry']['foreign_bank_costs'] == 0
        ){
            $this->data['FmFeTransactionEntry']['foreign_bank_costs'] = number_format(round($this->data['FmFeTransactionEntry']['foreign_bank_costs'], 2), 2, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['foreign_bank_costs'] = null;
        }
        if(!empty($this->data['FmFeTransactionEntry']['foreign_diff']) || 
            $this->data['FmFeTransactionEntry']['foreign_diff'] == '0' || 
            $this->data['FmFeTransactionEntry']['foreign_diff'] == 0
        ){
            $this->data['FmFeTransactionEntry']['foreign_diff'] = number_format(round($this->data['FmFeTransactionEntry']['foreign_diff'], 2), 2, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['foreign_diff'] = null;
        }
        if(!empty($this->data['FmFeTransactionEntry']['domestic_value_exchange']) || 
            $this->data['FmFeTransactionEntry']['domestic_value_exchange'] == '0' || 
            $this->data['FmFeTransactionEntry']['domestic_value_exchange'] == 0
        ){
            $this->data['FmFeTransactionEntry']['domestic_value_exchange'] = number_format(round($this->data['FmFeTransactionEntry']['domestic_value_exchange'], 3), 3, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['domestic_value_exchange'] = null;
        }
        if(!empty($this->data['FmFeTransactionEntry']['domestic_value_invoice']) || 
            $this->data['FmFeTransactionEntry']['domestic_value_invoice'] == '0' || 
            $this->data['FmFeTransactionEntry']['domestic_value_invoice'] == 0
        ){
            $this->data['FmFeTransactionEntry']['domestic_value_invoice'] = number_format(round($this->data['FmFeTransactionEntry']['domestic_value_invoice'], 3), 3, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['domestic_value_invoice'] = null;
        }
        if(!empty($this->data['FmFeTransactionEntry']['exchange_diff']) || 
            $this->data['FmFeTransactionEntry']['exchange_diff'] == '0' || 
            $this->data['FmFeTransactionEntry']['exchange_diff'] == 0
        ){
            $this->data['FmFeTransactionEntry']['exchange_diff'] = number_format(round($this->data['FmFeTransactionEntry']['exchange_diff'], 3), 3, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['exchange_diff'] = null;
        }
        if(!empty($this->data['FmFeTransactionEntry']['bank_commision']) || 
            $this->data['FmFeTransactionEntry']['bank_commision'] == '0' || 
            $this->data['FmFeTransactionEntry']['bank_commision'] == 0
        ){
            $this->data['FmFeTransactionEntry']['bank_commision'] = number_format(round($this->data['FmFeTransactionEntry']['bank_commision'], 3), 3, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['bank_commision'] = null;
        }
        if(!empty($this->data['FmFeTransactionEntry']['domestic_foreign_conversion']) || 
            $this->data['FmFeTransactionEntry']['domestic_foreign_conversion'] == '0' || 
            $this->data['FmFeTransactionEntry']['domestic_foreign_conversion'] == 0
        ){
            $this->data['FmFeTransactionEntry']['domestic_foreign_conversion'] = number_format(round($this->data['FmFeTransactionEntry']['domestic_foreign_conversion'], 3), 3, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['domestic_foreign_conversion'] = null;
        }
        if(!empty($this->data['FmFeTransactionEntry']['invoice_exchange_diff']) || 
            $this->data['FmFeTransactionEntry']['invoice_exchange_diff'] == '0' || 
            $this->data['FmFeTransactionEntry']['invoice_exchange_diff'] == 0
        ){
            $this->data['FmFeTransactionEntry']['invoice_exchange_diff'] = number_format(round($this->data['FmFeTransactionEntry']['invoice_exchange_diff'], 2), 2, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['invoice_exchange_diff'] = null;
        } 
        if(!empty($this->data['FmFeTransactionEntry']['final_foreign_value']) || 
            $this->data['FmFeTransactionEntry']['final_foreign_value'] == '0' || 
            $this->data['FmFeTransactionEntry']['final_foreign_value'] == 0
        ){
            $this->data['FmFeTransactionEntry']['final_foreign_value'] = number_format(round($this->data['FmFeTransactionEntry']['final_foreign_value'], 2), 2, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['final_foreign_value'] = null;
        }                 
        if(!empty($this->data['FmFeTransactionEntry']['foreign_exchange_rate']) || 
            $this->data['FmFeTransactionEntry']['foreign_exchange_rate'] == '0' || 
            $this->data['FmFeTransactionEntry']['foreign_exchange_rate'] == 0
        ){
            $this->data['FmFeTransactionEntry']['foreign_exchange_rate'] = number_format(round($this->data['FmFeTransactionEntry']['foreign_exchange_rate'], 4), 4, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['foreign_exchange_rate'] = null;
        }
        if(!empty($this->data['FmFeTransactionEntry']['foreign_total']) || 
            $this->data['FmFeTransactionEntry']['foreign_total'] == '0' || 
            $this->data['FmFeTransactionEntry']['foreign_total'] == 0
        ){
            $this->data['FmFeTransactionEntry']['foreign_total'] = number_format(round($this->data['FmFeTransactionEntry']['foreign_total'], 2), 2, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['foreign_total'] = null;
        }
        if(!empty($this->data['FmFeTransactionEntry']['domestic_total']) || 
            $this->data['FmFeTransactionEntry']['domestic_total'] == '0' || 
            $this->data['FmFeTransactionEntry']['domestic_total'] == 0
        ){
            $this->data['FmFeTransactionEntry']['domestic_total'] = number_format(round($this->data['FmFeTransactionEntry']['domestic_total'], 3), 3, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['domestic_total'] = null;
        }        
        if(!empty($this->data['FmFeTransactionEntry']['foreign_total_converted']) || 
            $this->data['FmFeTransactionEntry']['foreign_total_converted'] == '0' || 
            $this->data['FmFeTransactionEntry']['foreign_total_converted'] == 0
        ){
            $this->data['FmFeTransactionEntry']['foreign_total_converted'] = number_format(round($this->data['FmFeTransactionEntry']['foreign_total_converted'], 2), 2, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['foreign_total_converted'] = null;
        }
        if(!empty($this->data['FmFeTransactionEntry']['foreign_paid']) || 
            $this->data['FmFeTransactionEntry']['foreign_paid'] == '0' || 
            $this->data['FmFeTransactionEntry']['foreign_paid'] == 0
        ){
            $this->data['FmFeTransactionEntry']['foreign_paid'] = number_format(round($this->data['FmFeTransactionEntry']['foreign_paid'], 2), 2, '.', '');
        }else{
            $this->data['FmFeTransactionEntry']['foreign_paid'] = null;
        }

        //Set transaction info for validation
        $fe_transaction = $this->FmFeTransaction->find('first', array(
            'conditions' => array('FmFeTransaction.id' => $this->data['FmFeTransactionEntry']['fm_fe_transaction_id']),
            'fields' => array('FmFeTransaction.payer_recipient', 'FmFeTransaction.transaction_type'),
            'recursive' => -1
        ));
        if(!empty($fe_transaction)){
            $this->data['FmFeTransaction'] = $fe_transaction['FmFeTransaction'];
        }

        return true;
    }//~!

    /**
     * Check if foregin exchange transaction is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function fmFeTransactionValidation($check){
        $exists = $this->FmFeTransaction->find('count', array('conditions' => array('FmFeTransaction.id' => $this->data['FmFeTransactionEntry']['fm_fe_transaction_id']), 'recursive' => -1));
        return ($exists > 0) ? true : false;
    }//~!

    /**
     * Check if chart account is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function fmChartAccountValidation($check){
        //Check if chart account is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('by_invoice'))){
                $exists = $this->FmChartAccount->find('count', array('conditions' => array('FmChartAccount.id' => $this->data['FmFeTransactionEntry']['fm_chart_account_id']), 'recursive' => -1));
                return ($exists > 0) ? true : false;
            }
        }
        return true;
    }//~!

    /**
     * Check if traffic status is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function fmTrafficStatusValidation($check){
        //Check if traffic status is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('by_invoice', 'by_proform'))){
                $exists = $this->FmTrafficStatus->find('count', array('conditions' => array('FmTrafficStatus.id' => $this->data['FmFeTransactionEntry']['fm_traffic_status_id']), 'recursive' => -1));
                return ($exists > 0) ? true : false;
            }
        }
        return true;
    }//~!

    /**
     * Check if account order record is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function accountOrderRecordValidation($check){
        //Check if account order record is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('by_invoice'))){
                $exists = $this->FmAccountOrderRecord->find('count', array('conditions' => array('FmAccountOrderRecord.id' => $this->data['FmFeTransactionEntry']['fm_account_order_record_id']), 'recursive' => -1));
                return ($exists > 0) ? true : false;
            }
        }
        return true;        
    }//~!

    /**
     * Check if account order record is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function orderValidation($check){
        //Check if order is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('by_proform'))){
                $exists = $this->Order->find('count', array('conditions' => array('Order.id' => $this->data['FmFeTransactionEntry']['order_id']), 'recursive' => -1));
                return ($exists > 0) ? true : false;
            }
        }
        return true;
    }//~!    

    /**
     * If set check if primary document type exists in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function primaryDocumentTypeValidation($check){
        if(!empty($this->data['FmFeTransactionEntry']['primary_document_type_id'])){
            $document = $this->PrimaryDocumentType->find('count', array('conditions' => array('PrimaryDocumentType.id' => $this->data['FmFeTransactionEntry']['primary_document_type_id']), 'recursive' => -1));
            return ($document > 0) ? true : false;
        }
        return true;
    }//~!

    /**
     * If set check if primary document code is set
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function primaryDocumentCodeValidation($check){
        if(!empty($this->data['FmFeTransactionEntry']['primary_document_type_id'])){
            return !empty($this->data['FmFeTransactionEntry']['primary_document_code']);
        }
        return true;
    }//~!

    /**
     * If set check if secondary document type exists in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function secondaryDocumentTypeValidation($check){
        if(!empty($this->data['FmFeTransactionEntry']['secondary_document_type_id'])){
            $document = $this->SecondaryDocumentType->find('count', array('conditions' => array('SecondaryDocumentType.id' => $this->data['FmFeTransactionEntry']['secondary_document_type_id']), 'recursive' => -1));
            return ($document > 0) ? true : false;
        }
        return true;
    }//~!

    /**
     * If set check if secondary document code is set
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function secondaryDocumentCodeValidation($check){
        if(!empty($this->data['FmFeTransactionEntry']['secondary_document_type_id'])){
            return !empty($this->data['FmFeTransactionEntry']['secondary_document_code']);
        }
        return true;
    }//~!

    /**
     * If set check if foreign diff is set for closed entries
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function foreignDiffComparison($check){
        //Check if foreign diff is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('by_invoice','by_proform'))){
                if($this->data['FmFeTransactionEntry']['entry_status'] == 'closed'){
                    return $this->data['FmFeTransactionEntry']['foreign_diff'] > 0;
                }
            }
        }
        return true;
    }//~!

    /**
     * If set check if domestic value exchange is set for closed entries
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function domesticValueExchangeComparison($check){
        if($this->data['FmFeTransactionEntry']['entry_status'] == 'closed'){
            return $this->data['FmFeTransactionEntry']['domestic_value_exchange'] > 0;
        }
        return true;
    }//~!

    /**
     * If set check if domestic value invoice is set for invoices
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function domesticValueInvoiceCheck($check){
        //Check if domestic value invoice is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('by_invoice'))){
                return strlen(substr(strrchr($this->data['FmFeTransactionEntry']['domestic_value_invoice'], "."), 1)) == 3;
            }
        }
        return true;
    }//~!

    /**
     * If set check if domestic value invoice is set for closed entries
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function domesticValueInvoiceComparison($check){
        //Check if domestic value invoice is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('by_invoice'))){
                if($this->data['FmFeTransactionEntry']['entry_status'] == 'closed'){
                    return $this->data['FmFeTransactionEntry']['domestic_value_invoice'] > 0;
                }
            }
        }
        return true;        
    }//~!

    /**
     * If set check if exchange diff is set for invoices
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function exchangeDiffCheck($check){
        //Check if exchange difference is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('by_invoice'))){
                return strlen(substr(strrchr($this->data['FmFeTransactionEntry']['exchange_diff'], "."), 1)) == 3;
            }
        }
        return true;
    }//~!

    /**
     * If set check if domestic foreign conversion is set for closed entries
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function domesticForeignConversionComparison($check){
        //Check if domestic foreign conversion is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('by_proform'))){
                if($this->data['FmFeTransactionEntry']['entry_status'] == 'closed'){
                    return $this->data['FmFeTransactionEntry']['domestic_foreign_conversion'] > 0;
                }
            }
        }
        return true;        
    }//~!    

    /**
     * If set check if invoice exchange diff is set for invoices
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function invoiceExchangeDiffCheck($check){
        //Check if exchange difference is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('by_invoice'))){
                return strlen(substr(strrchr($this->data['FmFeTransactionEntry']['invoice_exchange_diff'], "."), 1)) == 2;
            }
        }
        return true;
    }//~!

    /**
     * If set check if account manner is set for transaction type if closed
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function accountMannerCheck($check){
        if($this->data['FmFeTransactionEntry']['entry_status'] == 'closed'){
            if(array_key_exists($this->data['FmFeTransaction']['transaction_type'], $this->transaction_type_manners)){
                return array_key_exists($this->data['FmFeTransactionEntry']['account_manner'], $this->transaction_type_manners[$this->data['FmFeTransaction']['transaction_type']]);
            }else{
                return $this->data['FmFeTransactionEntry']['account_manner'] === null;
            }
        }
        return true;
    }//~!

    /**
     * If set check if final foreign value is set for proform
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function finalForeignValueCheck($check){
        //Check if exchange difference is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('proform'))){
                return strlen(substr(strrchr($this->data['FmFeTransactionEntry']['final_foreign_value'], "."), 1)) == 2;
            }
        }
        return true;
    }//~!    

    /**
     * Check if currency is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function currencyValidation($check){
        //Check if exchange difference is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('proform', 'return_goods'))){
                $exists = $this->Currency->find('count', array('conditions' => array('Currency.id' => $this->data['FmFeTransactionEntry']['currency_id'], 'Currency.local' => 0), 'recursive' => -1));
                return ($exists > 0) ? true : false;
            }
        }
        return true;
    }//~!

    /**
     * If set check if foreign exchange rate is set for invoices
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function foreignExchangeRateCheck($check){
        //Check if exchange difference is needed
        if(in_array($this->data['FmFeTransaction']['payer_recipient'], array('customer'))){
            if(in_array($this->data['FmFeTransaction']['transaction_type'], array('by_invoice'))){
                return strlen(substr(strrchr($this->data['FmFeTransactionEntry']['foreign_exchange_rate'], "."), 1)) == 4;
            }
        }
        return true;
    }//~!

    /**
     * Check if entry status is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function entryStatusValidation($check){
        return array_key_exists($this->data['FmFeTransactionEntry']['entry_status'], $this->entry_statuses);
    }//~!    

    /**
     * Load customer invoiced entries
     *
     * @throws nothing
     * @param $fm_fe_transaction_id - FmFeTransaction.id, $form_data - FmFeTransactionEntry.select_record_*
     * @return $result with success or error message
     */
    public function saveCustomerInvoicedEntries($fm_fe_transaction_id, $form_data){
        //Init result
        $result = array();

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();

        try {
            //Check for transaction
            $fm_fe_transaction = $this->FmFeTransaction->getFeTransaction($fm_fe_transaction_id);
            if(empty($fm_fe_transaction)){
                throw new Exception('Devizna transakcija nije validna!');
            }

            //Check for business account
            $business_account = $this->FmFeTransaction->FmFeBasic->FmBusinessAccount->find('first', array(
                'conditions' => array('FmBusinessAccount.id' => $fm_fe_transaction['FmFeBasic']['fm_business_account_id']),
                'fields' => array('FmBusinessAccount.currency_id'),
                'recursive' => -1
            ));
            if(empty($business_account)){
                throw new Exception('Poslovni račun nije validan!');
            }

            //Get chart account ids
            $fm_chart_account_links = explode(',', $fm_fe_transaction['FmFeTransactionType']['fm_chart_account_links']);

            //Get codebook connection data
            $codebook_connection_data = $this->FmChartAccount->FmAccountOrderRecord->CodebookConnectionData->find('first', array(
                'conditions' => array('CodebookConnectionData.model_name' => 'Client', 'CodebookConnectionData.data_id' => $fm_fe_transaction['FmFeTransaction']['client_id']),
                'recursive' => -1
            ));
            if(empty($codebook_connection_data)){
                throw new Exception('Analitika otvorenih stavki nije validna!');
            }

            //Process form
            $form_entries = array();
            foreach ($form_data['FmFeTransactionEntry'] as $record_field => $record_value) {
                //Process fields
                if(strpos($record_field, 'select_record_') !== false){
                    //Reset entry
                    $entry = array();

                    //Get record data
                    $record_id = intval(str_replace('select_record_', '', $record_field));                    
                    $record_data = $this->FmChartAccount->FmAccountOrderRecord->find('first', array('conditions' => array('FmAccountOrderRecord.id' => $record_id), 'recursive' => -1));
                    if(empty($record_data)){
                        throw new Exception('Odabrana stavka nije validna!');        
                    }
                    $entry['FmFeTransactionEntry']['fm_account_order_record_id'] = $record_id;
                    $entry['selected'] = $record_value;                    
                }
                if(strpos($record_field, 'id_') !== false){
                    //Check if entry exists
                    if(!empty($record_value)){
                        $entry['FmFeTransactionEntry']['id'] = $record_value;
                    }
                    $form_entries[] = $entry;
                }
            }

            //Save entries
            $entries = array();
            foreach ($form_entries as $form_entry) {
                //Init entry
                $entry = array();

                //Get record data
                $record_data = $this->FmAccountOrderRecord->find('first', array(
                    'conditions' => array('FmAccountOrderRecord.id' => $form_entry['FmFeTransactionEntry']['fm_account_order_record_id']), 
                    'recursive' => -1
                ));
                if(empty($record_data)){
                    throw new Exception('Odabrana stavka nije validna!');        
                }

                //Load totals
                $params = array(
                    'fm_chart_account_links' => $fm_chart_account_links,
                    'codebook_connection_data_id' => $codebook_connection_data['CodebookConnectionData']['id'],
                    'currency_id' => $record_data['FmAccountOrderRecord']['currency_id'],
                    'primary_document_type_id' => $record_data['FmAccountOrderRecord']['primary_document_type_id'], 
                    'primary_document_code' => $record_data['FmAccountOrderRecord']['primary_document_code']
                );
                $customer_opened_records = $this->FmAccountOrderRecord->getCustomerOpened($params);                    
                if(empty($customer_opened_records[0])){
                    throw new Exception('Odabrana stavka nije pronadjena!');
                }
                $customer_record = $customer_opened_records[0];

                //Load entry if exists
                if(!empty($form_entry['FmFeTransactionEntry']['id'])){
                    $entry = $this->find('first', array('conditions' => array('FmFeTransactionEntry.id' => $form_entry['FmFeTransactionEntry']['id']), 'recursive' => -1));
                }

                //Set entry data
                if(!empty($form_entry['selected'])){
                    if(!empty($entry['FmFeTransactionEntry']['id'])){
                        //Enable entry
                        $entry['FmFeTransactionEntry']['deleted'] = 0;
                    }else{
                        //Check if entry by transaction, traffic, account order record... maybe exists
                        $entry = $this->find('first', array(
                            'conditions' => array(
                                'FmFeTransactionEntry.fm_fe_transaction_id' => $fm_fe_transaction_id,
                                'FmFeTransactionEntry.fm_traffic_status_id' => $record_data['FmAccountOrderRecord']['fm_traffic_status_id'],
                                'FmFeTransactionEntry.fm_account_order_record_id' => $form_entry['FmFeTransactionEntry']['fm_account_order_record_id']
                            ),
                            'recursive' => -1
                        ));                        
                        if(!empty($entry)){
                            $entry['FmFeTransactionEntry']['deleted'] = 0;
                        }                        
                        //Create entry
                        $entry['FmFeTransactionEntry']['fm_fe_transaction_id'] = $fm_fe_transaction_id;
                        $entry['FmFeTransactionEntry']['fm_chart_account_id'] = $record_data['FmAccountOrderRecord']['fm_chart_account_id'];
                        $entry['FmFeTransactionEntry']['fm_traffic_status_id'] = $record_data['FmAccountOrderRecord']['fm_traffic_status_id'];
                        $entry['FmFeTransactionEntry']['fm_account_order_record_id'] = $form_entry['FmFeTransactionEntry']['fm_account_order_record_id'];
                        $entry['FmFeTransactionEntry']['order_id'] = null;
                        $entry['FmFeTransactionEntry']['primary_document_type_id'] = $record_data['FmAccountOrderRecord']['primary_document_type_id'];
                        $entry['FmFeTransactionEntry']['primary_document_code'] = $record_data['FmAccountOrderRecord']['primary_document_code'];
                        $entry['FmFeTransactionEntry']['secondary_document_type_id'] = $record_data['FmAccountOrderRecord']['secondary_document_type_id'];
                        $entry['FmFeTransactionEntry']['secondary_document_code'] = $record_data['FmAccountOrderRecord']['secondary_document_code'];
                        
                        $entry['FmFeTransactionEntry']['foreign_transaction_value'] = '0.00';
                        $entry['FmFeTransactionEntry']['foreign_bank_costs'] = '0.00';
                        $entry['FmFeTransactionEntry']['foreign_diff'] = '0.00';
                        $entry['FmFeTransactionEntry']['domestic_value_exchange'] = '0.000';
                        $entry['FmFeTransactionEntry']['domestic_value_invoice'] = '0.000';
                        $entry['FmFeTransactionEntry']['exchange_diff'] = '0.000';
                        $entry['FmFeTransactionEntry']['bank_commision'] = '0.000';
                        $entry['FmFeTransactionEntry']['domestic_foreign_conversion'] = '0.000';
                        $entry['FmFeTransactionEntry']['invoice_exchange_diff'] = '0.00';
                        $entry['FmFeTransactionEntry']['final_foreign_value'] = '0.00';                        
                        $entry['FmFeTransactionEntry']['currency_id'] = $business_account['FmBusinessAccount']['currency_id'];
                        $entry['FmFeTransactionEntry']['account_manner'] = null;

                        $entry['FmFeTransactionEntry']['foreign_exchange_rate'] = $record_data['FmAccountOrderRecord']['domestic_exchange_rate'];
                        $entry['FmFeTransactionEntry']['entry_status'] = 'opened';
                    }
                    $entry['FmFeTransactionEntry']['foreign_total'] = $customer_record['FmAccountOrderRecord']['total_foreign_debit'];
                    $entry['FmFeTransactionEntry']['domestic_total'] = $customer_record['FmAccountOrderRecord']['total_domestic_debit'];
                    $entry['FmFeTransactionEntry']['foreign_total_converted'] = $entry['FmFeTransactionEntry']['foreign_total'];
                    $entry['FmFeTransactionEntry']['foreign_paid'] = $customer_record['FmAccountOrderRecord']['total_foreign_credit'];                    
                }else{
                    if(!empty($entry['FmFeTransactionEntry']['id'])){
                        //Delete entry
                        $entry['FmFeTransactionEntry']['deleted'] = 1;
                    }else{
                        continue;
                    }
                }

                //For new and selected entry create new row
                if(empty($form_entry['FmFeTransactionEntry']['id']) && !empty($form_entry['selected'])){
                    $this->create();
                }

                //Save entry
                if(!$this->save($entry)){
                    $errors = $this->validationErrors;
                    throw new Exception(array_shift($errors)[0]);
                }

                //Set final entries
                $entry['FmFeTransactionEntry']['id'] = $this->id;
                if(!empty($form_entry['selected'])){
                    $entries[] = $entry;
                }
            }

            //Check if there are any entries
            if(empty($entries)){
                throw new Exception('Nijedna otvorena stavka nije odabrana!');
            }

            //Set return result
            $result = $entries;
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
     * Load customer proform entries
     *
     * @throws nothing
     * @param $fm_fe_transaction_id - FmFeTransaction.id, $form_data - FmFeTransactionEntry.select_order_*
     * @return $result with success or error message
     */
    public function saveCustomerProformEntries($fm_fe_transaction_id, $form_data){
        //Init result
        $result = array();

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();        

        try {
            //Check for transaction
            $fm_fe_transaction = $this->FmFeTransaction->getFeTransaction($fm_fe_transaction_id);
            if(empty($fm_fe_transaction)){
                throw new Exception('Devizna transakcija nije validna!');
            }

            //Check for business account
            $business_account = $this->FmFeTransaction->FmFeBasic->FmBusinessAccount->find('first', array(
                'conditions' => array('FmBusinessAccount.id' => $fm_fe_transaction['FmFeBasic']['fm_business_account_id']),
                'recursive' => 0
            ));
            if(empty($business_account)){
                throw new Exception('Poslovni račun nije validan!');
            }

            //Get all exchange rates for date
            $exchange_rates = $this->FmFeTransaction->FmFeBasic->FmBusinessAccount->Currency->ExchangeRate->getDateIntermediateRatesGroupedByISO($fm_fe_transaction['FmFeBasic']['fe_date']);
            if(empty($exchange_rates)){
                throw new Exception('Devizni kursevi nisu validni!');
            }

            //Process form
            $form_entries = array();
            foreach ($form_data['FmFeTransactionEntry'] as $record_field => $record_value) {
                //Process fields
                if(strpos($record_field, 'select_order_') !== false){
                    //Reset entry
                    $entry = array();

                    //Get record data
                    $order_id = intval(str_replace('select_order_', '', $record_field));
                    $order_data = $this->Order->find('first', array(
                        'conditions' => array('Order.id' => $order_id), 
                        'fields' => array('Order.*', 'Currency.iso'),
                        'recursive' => 0
                    ));
                    if(empty($order_data)){
                        throw new Exception('Odabrani order nije validan!');
                    }
                    $entry = $order_data;
                    $entry['FmFeTransactionEntry']['order_id'] = $order_id;                                        
                    $entry['selected'] = $record_value;
                }
                if(strpos($record_field, 'id_') !== false){
                    //Check if entry exists
                    if(!empty($record_value)){
                        $entry['FmFeTransactionEntry']['id'] = $record_value;
                    }
                }

                //Add other key values
                if(strpos($record_field, 'select_order_') === false && strpos($record_field, 'id_') === false){
                    $key = str_replace('_'.$order_id, '', $record_field);
                    $entry[$key] = $record_value;
                    if(strpos($record_field, 'converted_diff_') !== false){
                        $form_entries[] = $entry;       
                    }
                }
            }

            //Get all current entries
            $delete_entries_list = $this->getTransactionEntriesList($fm_fe_transaction_id);

            //Save entries
            $entries = array();
            foreach ($form_entries as $form_entry) {
                //Init entry
                $entry = array();

                //Load entry if exists
                if(!empty($form_entry['FmFeTransactionEntry']['id'])){
                    $entry = $this->find('first', array('conditions' => array('FmFeTransactionEntry.id' => $form_entry['FmFeTransactionEntry']['id']), 'recursive' => -1));
                }

                //Set entry data
                if(!empty($form_entry['selected'])){
                    if(!empty($entry['FmFeTransactionEntry']['id'])){
                        //Enable entry
                        $entry['FmFeTransactionEntry']['deleted'] = 0;
                    }else{
                        //Check if entry by transaction, traffic, order... maybe exists
                        $entry = $this->find('first', array(
                            'conditions' => array(
                                'FmFeTransactionEntry.fm_fe_transaction_id' => $fm_fe_transaction_id,
                                'FmFeTransactionEntry.fm_traffic_status_id' => $form_entry['Order']['fm_traffic_status_id'],
                                'FmFeTransactionEntry.order_id' => $form_entry['Order']['id']
                            ),
                            'recursive' => -1
                        ));                        
                        if(!empty($entry)){
                            $entry['FmFeTransactionEntry']['deleted'] = 0;
                        }

                        //Init entry
                        $entry['FmFeTransactionEntry']['fm_fe_transaction_id'] = $fm_fe_transaction_id;
                        $entry['FmFeTransactionEntry']['fm_chart_account_id'] = null;
                        $entry['FmFeTransactionEntry']['fm_traffic_status_id'] = $form_entry['Order']['fm_traffic_status_id'];
                        $entry['FmFeTransactionEntry']['fm_account_order_record_id'] = null;
                        $entry['FmFeTransactionEntry']['order_id'] = $form_entry['Order']['id'];
                        $entry['FmFeTransactionEntry']['primary_document_type_id'] = null;
                        $entry['FmFeTransactionEntry']['primary_document_code'] = null;
                        $entry['FmFeTransactionEntry']['secondary_document_type_id'] = null;
                        $entry['FmFeTransactionEntry']['secondary_document_code'] = null;
                        $entry['FmFeTransactionEntry']['foreign_transaction_value'] = '0.00';
                        $entry['FmFeTransactionEntry']['foreign_bank_costs'] = '0.00';
                        $entry['FmFeTransactionEntry']['foreign_diff'] = '0.00';
                        $entry['FmFeTransactionEntry']['domestic_value_exchange'] = '0.000';
                        $entry['FmFeTransactionEntry']['domestic_value_invoice'] = '0.000';
                        $entry['FmFeTransactionEntry']['exchange_diff'] = '0.000';
                        $entry['FmFeTransactionEntry']['bank_commision'] = '0.000';
                        $entry['FmFeTransactionEntry']['domestic_foreign_conversion'] = '0.00';
                        $entry['FmFeTransactionEntry']['invoice_exchange_diff'] = '0.00';
                        $entry['FmFeTransactionEntry']['final_foreign_value'] = '0.00';
                        $entry['FmFeTransactionEntry']['foreign_exchange_rate'] = null;
                        $entry['FmFeTransactionEntry']['entry_status'] = 'opened';
                    }

                    $entry['FmFeTransactionEntry']['foreign_total'] = $form_entry['payment_diff'];
                    //$entry['FmFeTransactionEntry']['domestic_total'] = //:TO DO:

                    $entry['FmFeTransactionEntry']['foreign_total_converted'] = $form_entry['converted_diff'];
                    $entry['FmFeTransactionEntry']['foreign_paid'] = $form_entry['Order']['total'] - $form_entry['converted_diff'];

                    $entry['FmFeTransactionEntry']['account_manner'] = null;
                    $entry['FmFeTransactionEntry']['currency_id'] = $form_entry['Order']['currency_id'];
                }else{
                    if(!empty($entry['FmFeTransactionEntry']['id'])){
                        //Delete entry
                        $entry['FmFeTransactionEntry']['deleted'] = 1;
                    }else{
                        continue;
                    }
                }

                //For new and selected entry create new row
                if(empty($form_entry['FmFeTransactionEntry']['id']) && !empty($form_entry['selected'])){
                    $this->create();
                }

                //Save entry
                if(!$this->save($entry)){
                    $errors = $this->validationErrors;
                    throw new Exception(array_shift($errors)[0]);
                }

                //Set final entries
                $entry['FmFeTransactionEntry']['id'] = $this->id;
                if(!empty($form_entry['selected'])){
                    $entries[] = $entry;
                }
                //Remove from delete list
                if(!empty($delete_entries_list[$entry['FmFeTransactionEntry']['id']])){
                    unset($delete_entries_list[$entry['FmFeTransactionEntry']['id']]);
                }                
            }

            //Check if there are any entries
            if(empty($entries)){
                throw new Exception('Nijedna otvorena stavka nije odabrana!');
            }

            //Delete old entries from list
            if(!empty($delete_entries_list)){
                //Set IDs for deleting
                $delete_entries_ids = array_keys($delete_entries_list);

                //Update records
                $deleted_entries = $this->updateAll(
                    array('FmFeTransactionEntry.deleted' => 1),
                    array('FmFeTransactionEntry.id' => $delete_entries_ids)
                );
                if(!$deleted_entries){
                    throw new Exception('Stavke za evidentiranje ne mogu biti stornirane!');
                }                
            }            

            //Set return result
            $result = $entries;
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
     * Load customer account entries
     *
     * @throws nothing
     * @param $fm_fe_transaction_id - FmFeTransaction.id, $form_data - FmFeTransactionEntry.select_record_*
     * @return $result with success or error message
     */
    public function saveCustomerAccountEntries($fm_fe_transaction_id, $form_data){
        //Init result
        $result = array();

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();

        try {
            //Check for transaction
            $fm_fe_transaction = $this->FmFeTransaction->getFeTransaction($fm_fe_transaction_id);
            if(empty($fm_fe_transaction)){
                throw new Exception('Devizna transakcija nije validna!');
            }

            //Check for business account
            $business_account = $this->FmFeTransaction->FmFeBasic->FmBusinessAccount->find('first', array(
                'conditions' => array('FmBusinessAccount.id' => $fm_fe_transaction['FmFeBasic']['fm_business_account_id']),
                'recursive' => 0
            ));
            if(empty($business_account)){
                throw new Exception('Poslovni račun nije validan!');
            }

            //Get all exchange rates for date
            $exchange_rates = $this->FmFeTransaction->FmFeBasic->FmBusinessAccount->Currency->ExchangeRate->getDateIntermediateRatesGroupedByISO($fm_fe_transaction['FmFeBasic']['fe_date']);
            if(empty($exchange_rates)){
                throw new Exception('Devizni kursevi nisu validni!');
            }

            //Get chart account ids
            $fm_chart_account_links = explode(',', $fm_fe_transaction['FmFeTransactionType']['fm_chart_account_links']);

            //Get codebook connection data
            $codebook_connection_data = $this->FmChartAccount->FmAccountOrderRecord->CodebookConnectionData->find('first', array(
                'conditions' => array('CodebookConnectionData.model_name' => 'Client', 'CodebookConnectionData.data_id' => $fm_fe_transaction['FmFeTransaction']['client_id']),
                'recursive' => -1
            ));
            if(empty($codebook_connection_data)){
                throw new Exception('Analitika otvorenih stavki nije validna!');
            }

            //Process form
            $form_entries = array();
            foreach ($form_data['FmFeTransactionEntry'] as $record_field => $record_value) {
                //Process fields
                if(strpos($record_field, 'select_record_') !== false){
                    //Reset entry
                    $entry = array();

                    //Get record data
                    $record_id = intval(str_replace('select_record_', '', $record_field));                    
                    $record_data = $this->FmChartAccount->FmAccountOrderRecord->find('first', array('conditions' => array('FmAccountOrderRecord.id' => $record_id), 'recursive' => -1));
                    if(empty($record_data)){
                        throw new Exception('Odabrana stavka nije validna!');        
                    }
                    $entry = $record_data;
                    $entry['FmFeTransactionEntry']['fm_account_order_record_id'] = $record_id;
                    $entry['selected'] = $record_value;                    
                }
                if(strpos($record_field, 'id_') !== false){
                    //Check if entry exists
                    if(!empty($record_value)){
                        $entry['FmFeTransactionEntry']['id'] = $record_value;
                    }
                    $form_entries[] = $entry;
                }
            }

            //Save entries
            $entries = array();
            foreach ($form_entries as $form_entry) {
                //Init entry
                $entry = array();

                //Get record data
                $record_data = $this->FmAccountOrderRecord->find('first', array(
                    'conditions' => array('FmAccountOrderRecord.id' => $form_entry['FmFeTransactionEntry']['fm_account_order_record_id']), 
                    'recursive' => -1
                ));
                if(empty($record_data)){
                    throw new Exception('Odabrana stavka nije validna!');        
                }

                //Load totals
                $params = array(
                    'fm_chart_account_links' => $fm_chart_account_links,
                    'codebook_connection_data_id' => $codebook_connection_data['CodebookConnectionData']['id'],
                    'primary_document_type_id' => $record_data['FmAccountOrderRecord']['primary_document_type_id'],
                    'primary_document_code' => $record_data['FmAccountOrderRecord']['primary_document_code'],
                    'currency_id' => $record_data['FmAccountOrderRecord']['currency_id']
                );
                $customer_opened_records = $this->FmAccountOrderRecord->getCustomerOpened($params);    
                if(empty($customer_opened_records[0])){
                    throw new Exception('Odabrana stavka nije pronadjena!');
                }
                $record = $customer_opened_records[0];

                //Load entry if exists
                if(!empty($form_entry['FmFeTransactionEntry']['id'])){
                    $entry = $this->find('first', array('conditions' => array('FmFeTransactionEntry.id' => $form_entry['FmFeTransactionEntry']['id']), 'recursive' => -1));
                }

                //Set entry data
                if(!empty($form_entry['selected'])){
                    if(!empty($entry['FmFeTransactionEntry']['id'])){
                        //Enable entry
                        $entry['FmFeTransactionEntry']['deleted'] = 0;
                    }else{
                        //Check if entry by transaction, traffic, account order record... maybe exists
                        $entry = $this->find('first', array(
                            'conditions' => array(
                                'FmFeTransactionEntry.fm_fe_transaction_id' => $fm_fe_transaction_id,
                                'FmFeTransactionEntry.fm_traffic_status_id' => $form_entry['FmAccountOrderRecord']['fm_traffic_status_id'],
                                'FmFeTransactionEntry.fm_account_order_record_id' => $form_entry['FmFeTransactionEntry']['fm_account_order_record_id']
                            ),
                            'recursive' => -1
                        ));
                        if(!empty($entry)){
                            $entry['FmFeTransactionEntry']['deleted'] = 0;
                        }

                        //Create entry
                        $entry['FmFeTransactionEntry']['fm_fe_transaction_id'] = $fm_fe_transaction_id;
                        $entry['FmFeTransactionEntry']['fm_chart_account_id'] = $record_data['FmAccountOrderRecord']['fm_chart_account_id'];
                        $entry['FmFeTransactionEntry']['fm_traffic_status_id'] = $record_data['FmAccountOrderRecord']['fm_traffic_status_id'];
                        $entry['FmFeTransactionEntry']['fm_account_order_record_id'] = $form_entry['FmFeTransactionEntry']['fm_account_order_record_id'];
                        $entry['FmFeTransactionEntry']['order_id'] = null;
                        $entry['FmFeTransactionEntry']['primary_document_type_id'] = $record_data['FmAccountOrderRecord']['primary_document_type_id'];
                        $entry['FmFeTransactionEntry']['primary_document_code'] = $record_data['FmAccountOrderRecord']['primary_document_code'];
                        $entry['FmFeTransactionEntry']['secondary_document_type_id'] = null;
                        $entry['FmFeTransactionEntry']['secondary_document_code'] = null;

                        $entry['FmFeTransactionEntry']['foreign_transaction_value'] = '0.00';
                        $entry['FmFeTransactionEntry']['foreign_bank_costs'] = '0.00';
                        $entry['FmFeTransactionEntry']['foreign_diff'] = '0.00';
                        $entry['FmFeTransactionEntry']['domestic_value_exchange'] = '0.000';
                        $entry['FmFeTransactionEntry']['domestic_value_invoice'] = '0.000';
                        $entry['FmFeTransactionEntry']['exchange_diff'] = '0.000';
                        $entry['FmFeTransactionEntry']['bank_commision'] = '0.000';
                        $entry['FmFeTransactionEntry']['domestic_foreign_conversion'] = null;                                            
                        $entry['FmFeTransactionEntry']['invoice_exchange_diff'] = '0.00';
                        $entry['FmFeTransactionEntry']['account_manner'] = null;
                        $entry['FmFeTransactionEntry']['final_foreign_value'] = null;
                        $entry['FmFeTransactionEntry']['currency_id'] = $record_data['FmAccountOrderRecord']['currency_id'];
                        $entry['FmFeTransactionEntry']['foreign_exchange_rate'] = $record_data['FmAccountOrderRecord']['domestic_exchange_rate'];
                        $entry['FmFeTransactionEntry']['entry_status'] = 'opened';
                    }

                    $entry['FmFeTransactionEntry']['foreign_total'] = $record['FmAccountOrderRecord']['total_foreign_credit'] - $record['FmAccountOrderRecord']['total_foreign_debit'];
                    $entry['FmFeTransactionEntry']['domestic_total'] = $record['FmAccountOrderRecord']['total_domestic_credit'] - $record['FmAccountOrderRecord']['total_domestic_debit'];

                    $entry['FmFeTransactionEntry']['foreign_paid'] = null;
                    $coeff_conv = round($exchange_rates[$record['Currency']['iso']] / $exchange_rates[$business_account['Currency']['iso']], 4);
                    $conversion_diff = round($coeff_conv * $entry['FmFeTransactionEntry']['foreign_total'], 2);
                    $entry['FmFeTransactionEntry']['foreign_total_converted'] = $conversion_diff;
                }else{
                    if(!empty($entry['FmFeTransactionEntry']['id'])){
                        //Delete entry
                        $entry['FmFeTransactionEntry']['deleted'] = 1;
                    }else{
                        continue;
                    }
                }

                //For new and selected entry create new row
                if(empty($form_entry['FmFeTransactionEntry']['id']) && !empty($form_entry['selected'])){
                    $this->create();
                }

                //Save entry
                if(!$this->save($entry)){
                    $errors = $this->validationErrors;
                    throw new Exception(array_shift($errors)[0]);
                }

                //Set final entries
                $entry['FmFeTransactionEntry']['id'] = $this->id;
                if(!empty($form_entry['selected'])){
                    $entries[] = $entry;
                }
            }

            //Check if there are any entries
            if(empty($entries)){
                throw new Exception('Nijedna otvorena stavka nije odabrana!');
            }

            //Set return result
            $result = $entries;
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
     * Save multiple entries with selected values
     *
     * @throws nothing
     * @param $fm_fe_transaction_id - FmFeTransaction.id, $form_data - FmFeTransactionEntry.(all form fields)
     * @return $result with success or error message
     */
    public function saveMultiple($fm_fe_transaction_id, $form_data){
        //Init result
        $result = array();

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();        

        try {
            //Check for transaction
            $fm_fe_transaction = $this->FmFeTransaction->find('first', array(
                'conditions' => array('FmFeTransaction.id' => $fm_fe_transaction_id), 
                'fields' => array('FmFeTransaction.*', 'FmFeBasic.fm_business_account_id'),
                'recursive' => 0
            ));
            if(empty($fm_fe_transaction)){
                throw new Exception('Devizna transakcija nije validna!');
            }

            //Check for business account
            $business_account = $this->FmFeTransaction->FmFeBasic->FmBusinessAccount->find('first', array(
                'conditions' => array('FmBusinessAccount.id' => $fm_fe_transaction['FmFeBasic']['fm_business_account_id']),
                'fields' => array('FmBusinessAccount.currency_id'),
                'recursive' => -1
            ));
            if(empty($business_account)){
                throw new Exception('Poslovni račun nije validan!');
            }

            //Init vars
            $entries = array();
            $current_entry_id = null;
            $field_entry_id = null;
            $entry = array();
            $foreign_value_sum = 0;
            
            //Process form fields
            $entries = array();
            $entry_id = null;
            $current_entry_id = null;
            foreach ($form_data['FmFeTransactionEntry'] as $entry_id_field => $entry_value) {
                //Check first field
                if(strpos($entry_id_field, 'foreign_transaction_value_') !== false){
                    $current_entry_id = intval(str_replace('foreign_transaction_value_', '', $entry_id_field));                    
                    $entry = $this->find('first', array('conditions' => array('FmFeTransactionEntry.id' => $current_entry_id), 'recursive' => -1));
                    if(empty($entry)){
                        throw new Exception('Odabrana stavka u obrazcu nije validna!');
                    }                    
                }                

                //Set key
                $key = str_replace('_'.$current_entry_id, '', $entry_id_field);

                //Set value
                $entry['FmFeTransactionEntry'][$key] = $entry_value;

                //Check last field
                if($key == 'invoice_exchange_diff'){
                    $entries[] = $entry;
                }                
            }

            //Close all entries and set record data
            $foreign_value_sum = 0;
            foreach ($entries as $entry) {
                //Init empty fields
                if($entry['FmFeTransactionEntry']['foreign_bank_costs'] == ''){
                    $entry['FmFeTransactionEntry']['foreign_bank_costs'] = '0.00';
                }

                if($entry['FmFeTransactionEntry']['invoice_exchange_diff'] == ''){
                    $entry['FmFeTransactionEntry']['invoice_exchange_diff'] = '0.00';
                }

                if($entry['FmFeTransactionEntry']['bank_commision'] == ''){
                    $entry['FmFeTransactionEntry']['bank_commision'] = '0.000';
                }

                //Check account manner
                if(array_key_exists($fm_fe_transaction['FmFeTransaction']['transaction_type'], $this->transaction_type_manners)){
                    if($fm_fe_transaction['FmFeTransaction']['transaction_type'] == 'by_proform'){                        
                        if($entry['FmFeTransactionEntry']['account_manner'] == 'proform'){
                            //Get order 
                            $order = $this->Order->find('first', array(
                                'conditions' => array('Order.id' => $entry['FmFeTransactionEntry']['order_id']),
                                'fields' => array('Order.currency_id'),
                                'recursive' => -1
                            ));
                            if(empty($order)){
                                throw new Exception('Broj PF nije validan!');
                            }                            
                            $entry['FmFeTransactionEntry']['currency_id'] = $order['Order']['currency_id'];
                        }
                        if($entry['FmFeTransactionEntry']['account_manner'] == 'foreign_exchange'){
                            $entry['FmFeTransactionEntry']['currency_id'] = $business_account['FmBusinessAccount']['currency_id'];
                        }                        
                    }
                }else{
                    $entry['FmFeTransactionEntry']['currency_id'] = $business_account['FmBusinessAccount']['currency_id'];
                }

                //Save sum
                if(in_array($fm_fe_transaction['FmFeTransaction']['payer_recipient'], array('customer'))){
                    if(in_array($fm_fe_transaction['FmFeTransaction']['transaction_type'], array('by_invoice', 'by_proform'))){
                        $foreign_value_sum += $entry['FmFeTransactionEntry']['foreign_diff'];
                    }
                    if(in_array($fm_fe_transaction['FmFeTransaction']['transaction_type'], array('return_goods'))){
                        $foreign_value_sum += $entry['FmFeTransactionEntry']['foreign_transaction_value'];
                    }                        
                }

                //Close entry
                $entry['FmFeTransactionEntry']['entry_status'] = 'closed';

                //Save entry to DB
                if(!$this->save($entry)){
                    $errors = $this->validationErrors;
                    throw new Exception(array_shift($errors)[0]);
                }

                //Create transaction records
                $record_result = $this->FmFeTransactionRecord->saveFromEntry($entry['FmFeTransactionEntry']['id']);
                if(!$record_result['success']){
                    throw new Exception('Evidencija u obrazcu za knjiženje nije snimljena! Greška: '.$record_result['message']);
                }
            }

            //Check if there are any entries
            if(empty($entries)){
                throw new Exception('Nijedna stavka u obrazcu nije sačuvana!');
            }

            //Check for transaction value and foreign value sum
            if($fm_fe_transaction['FmFeTransaction']['transaction_value'] != $foreign_value_sum){
                throw new Exception('Zbirni devizni iznos priliva/odliva i ukupna devizna vrednost po transakciji se ne slažu!');
            }  

            //Set return result
            $result = $entries;
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
     * Get transaction entries
     *
     * @throws nothing
     * @param $fm_fe_transaction_id - FmFeTransaction.id
     * @return $result transaction entries array
     */
    public function getTransactionEntries($fm_fe_transaction_id){
        return $this->find('all', array('conditions' => array('FmFeTransactionEntry.fm_fe_transaction_id' => $fm_fe_transaction_id, 'FmFeTransactionEntry.deleted' => 0), 'recursive' => 0));        
    }//~!

    /**
     * Get transaction entries list
     *
     * @throws nothing
     * @param $fm_fe_transaction_id - FmFeTransaction.id
     * @return $result transaction entries array
     */
    public function getTransactionEntriesList($fm_fe_transaction_id){
        return $this->find('list', array(
            'conditions' => array('FmFeTransactionEntry.fm_fe_transaction_id' => $fm_fe_transaction_id, 'FmFeTransactionEntry.deleted' => 0), 
            'fields' => array('FmFeTransactionEntry.id'),
            'recursive' => -1
        ));
    }//~!

    /**
     * Get all transaction entries including deleted
     *
     * @throws nothing
     * @param $fm_fe_transaction_id - FmFeTransaction.id
     * @return $result transaction entries array
     */
    public function getTransactionEntriesWithDeleted($fm_fe_transaction_id){
        return $this->find('all', array('conditions' => array('FmFeTransactionEntry.fm_fe_transaction_id' => $fm_fe_transaction_id), 'recursive' => 0));
    }//~!    
}
?>