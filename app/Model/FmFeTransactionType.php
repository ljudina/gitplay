<?php
class FmFeTransactionType extends AppModel{
	var $name = 'FmFeTransactionType'; 

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
        'FmFeAccountScheme' => array(
            'className' => 'FmFeAccountScheme',
            'foreignKey' => 'fm_fe_account_scheme_id'
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
            ),
            'transactionTypeRule4' => array(
                'rule' => array('transactionTypeUnique'),
                'message' => 'Vrsta transakcije je već vezana za Isplatioca/Primaoca',
                'required' => true
            )
        ),
        'fm_chart_account_links' => array(
            'fmChartAccountLinksRule1' => array(
                'rule' => array('chartAccountLinksCheck'),
                'message' => 'Veza sa konto karticom nije validna',
                'required' => true
            )
        ),
        'fm_fe_account_scheme_id' => array(
            'FmFeAccountSchemeRule1' => array(
                'rule' => array('accountSchemeInfoValidation'),
                'message' => 'Šema za knjiženje nije validna',
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
        //Assign new ordinal for new account
        if(empty($this->data['FmFeTransactionType']['id'])){
            $type_count = $this->find('count', array('conditions' => array('FmFeTransactionType.deleted' => 0), 'recursive' => -1));            
            $this->data['FmFeTransactionType']['ordinal'] = $type_count + 1;
        }

        //Convert fm account links from array to string
        if(!empty($this->data['FmFeTransactionType']['fm_chart_account_links']) && is_array($this->data['FmFeTransactionType']['fm_chart_account_links'])){
            $this->data['FmFeTransactionType']['fm_chart_account_links'] = implode(',', $this->data['FmFeTransactionType']['fm_chart_account_links']);
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
        if(empty($this->data['FmFeTransactionType']['deleted'])){
            return !empty($this->data['FmFeTransactionType']['ordinal']);
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
        $conditions = array('FmFeTransactionType.deleted' => 0, 'FmFeTransactionType.ordinal' => $this->data['FmFeTransactionType']['ordinal']);
        if(!empty($this->data['FmFeTransactionType']['id'])){
            $conditions['NOT'] = array('FmFeTransactionType.id' => $this->data['FmFeTransactionType']['id']);
        }        
        $exists = $this->find('count', array('conditions' => $conditions));
        return ($exists > 0) ? false : true;
    }//~!

    /**
     * Check if payer/recipient is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function payerRecipientValidation($check){
        return array_key_exists($this->data['FmFeTransactionType']['payer_recipient'], $this->payer_recipients);
    }//~!

    /**
     * Check if transaction type is valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function transactionTypeValidation($check){
        return array_key_exists($this->data['FmFeTransactionType']['transaction_type'], $this->transaction_types);
    }//~!

    /**
     * Check if transaction type is selected for right payer/recipient
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function transactionTypeCheck($check){
        if(!empty($this->data['FmFeTransactionType']['transaction_type']) && !empty($this->data['FmFeTransactionType']['payer_recipient'])){
            $transaction_type = $this->data['FmFeTransactionType']['transaction_type'];
            $payer_recipient = $this->data['FmFeTransactionType']['payer_recipient'];

            if(!empty($this->transaction_links[$payer_recipient])){
                $link = $this->transaction_links[$payer_recipient];
                return array_key_exists($transaction_type, $link);
            }
        }
        return false;
    }//~!

    /**
     * Check if transaction type is already assigned for payer/recipient
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function transactionTypeUnique($check){
        $conditions = array(
            'FmFeTransactionType.deleted' => 0, 
            'FmFeTransactionType.payer_recipient' => $this->data['FmFeTransactionType']['payer_recipient'],
            'FmFeTransactionType.transaction_type' => $this->data['FmFeTransactionType']['transaction_type']
        );
        if(!empty($this->data['FmFeTransactionType']['id'])){
            $conditions['NOT'] = array('FmFeTransactionType.id' => $this->data['FmFeTransactionType']['id']);
        }        
        $exists = $this->find('count', array('conditions' => $conditions));
        return ($exists > 0) ? false : true;
    }//~!    

    /**
     * Check if chart account links are valid
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function chartAccountLinksCheck($check){
        if(!empty($this->data['FmFeTransactionType']['fm_chart_account_links'])){
            //Set selected chart account links
            $fm_chart_account_links = explode(',', $this->data['FmFeTransactionType']['fm_chart_account_links']);

            //Get selected chart account links
            $this->FmChartAccount = ClassRegistry::init('FmChartAccount');
            $chart_account_count = $this->FmChartAccount->find('count', array('conditions' => array('FmChartAccount.code' => $fm_chart_account_links), 'recursive' => -1));

            //Check counts
            return $chart_account_count == count($fm_chart_account_links);
        }
        return true;
    }//~!

    /**
     * Check if account scheme info is valid in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function accountSchemeInfoValidation($check){
        if(!empty($this->data['FmFeTransactionType']['fm_fe_account_scheme_id'])){
            $exists = $this->FmFeAccountScheme->find('count', array('conditions' => array('FmFeAccountScheme.id' => $this->data['FmFeTransactionType']['fm_fe_account_scheme_id'])));
            return ($exists > 0) ? true : false;
        }
        return true;
    }//~!

    /**
     * Delete type by id
     *
     * @throws nothing
     * @param $id - FmFeTransactionType.id
     * @return boolean
     */
    public function deleteType($id){
        $result = array();

        //Init transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin();        

        try {
            //Get selected type
            $type = $this->find('first', array('conditions' => array('FmFeTransactionType.id' => $id), 'recursive' => -1));
            if(empty($type)){    
                throw new Exception('Ova devizna transakcija nije validna!');
            }
            if(!empty($type['FmFeTransactionType']['deleted'])){
                throw new Exception('Ova devizna transakcija je već obrisana!');
            }

            //Set current ordinal
            $current_ordinal = $type['FmFeTransactionType']['ordinal'];

            //Mark type as deleted
            $type['FmFeTransactionType']['deleted'] = 1;
            $type['FmFeTransactionType']['ordinal'] = null;

            //Save to DB
            if(!$this->save($type)){
                $errors = $this->validationErrors;
                throw new Exception('Devizna transakcija nije obrisana! Greška: '.array_shift($errors)[0]);
            }

            //Update all next ordinals
            $conditions = array('FmFeTransactionType.deleted' => 0, 'FmFeTransactionType.ordinal >' => $current_ordinal);
            $update_result = $this->updateAll(array('FmFeTransactionType.ordinal' => 'FmFeTransactionType.ordinal - 1'), $conditions);
            if(!$update_result){
                throw new Exception('Redni brojevi deviznih transakcija nisu osveženi!');
            }

            //Set results for erp log
            $result = $type;
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
     * Get transaction type by id
     *
     * @throws nothing
     * @param $payer_recipient element from $this->payer_recipients
     * @param $transaction_type element from $this->transaction_types
     * @return FmFeTransactionType array
     */
    public function getType($payer_recipient, $transaction_type){
        $result = $this->find('first', array(
            'conditions' => array(
                'FmFeTransactionType.payer_recipient' => $payer_recipient,
                'FmFeTransactionType.transaction_type' => $transaction_type,
                'FmFeTransactionType.deleted' => 0
            ), 
            'recursive' => -1
        ));
        return $result;
    }//~!    
}
?>