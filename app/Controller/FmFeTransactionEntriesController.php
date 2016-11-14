<?php
App::uses('AppController', 'Controller');
/**
 * FmFeTransactionEntries Controller
 *
 * @property FmFeTransactionEntry $FmFeTransactionEntry
 * @property PaginatorComponent $Paginator
 */
class FmFeTransactionEntriesController extends AppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Session', 'Acl', 'Cookie', 'Ctrl', 'RequestHandler', 'ErpLogManagement','Paginator', 'Search', 'String');

	/**
	 * index method
	 * $fm_fe_transaction_id - FmFeTransaction.id
	 * @return void
	 */
	public function index($fm_fe_transaction_id) {
		//Get transaction
		$result = $this->FmFeTransactionEntry->FmFeTransaction->getTransactionForEditing($fm_fe_transaction_id);
		if(!$result['success']){
			$this->Session->setFlash($result['message'], 'flash_error');
			return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'index'));
		}
		$fe_transaction = $result['fe_transaction'];

		//Set foreign exchange transaction info
		$this->set('fe_transaction', $fe_transaction);

		//Get transaction entries
		$fe_transaction_entries = $this->FmFeTransactionEntry->find('all', array(
			'conditions' => array('FmFeTransactionEntry.fm_fe_transaction_id' => $fm_fe_transaction_id),
			'recursive' => -1
		));
		$this->set('fe_transaction_entries', $fe_transaction_entries);
	}//~!

	/**
	 * Load entries method
	 * $fm_fe_transaction_id - FmFeTransaction.id
	 * @return void
	 */
	public function load_entries($fm_fe_transaction_id) {
		//Get transaction
		$result = $this->FmFeTransactionEntry->FmFeTransaction->getTransactionForEditing($fm_fe_transaction_id);
		if(!$result['success']){
			$this->Session->setFlash($result['message'], 'flash_error');
			return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'index'));
		}
		$fe_transaction = $result['fe_transaction'];

		//Set foreign exchange transaction info
		$this->set('fe_transaction', $fe_transaction);

		//Set transaction field titles
		$this->set('flow_types', $this->FmFeTransactionEntry->FmFeTransaction->flow_types);
		$this->set('payer_recipients', $this->FmFeTransactionEntry->FmFeTransaction->FmFeTransactionType->payer_recipients);
		$this->set('transaction_types', $this->FmFeTransactionEntry->FmFeTransaction->FmFeTransactionType->transaction_types);

		//Check for conditions
		if(empty($fe_transaction['FmFeTransactionType']['fm_fe_account_scheme_id']) || !empty($fe_transaction['FmFeTransactionRecord'])){
			//Set error message and redirect to foreign exchange basic
			$this->Session->setFlash('Pregled otvorenih stavki nije moguć!', 'flash_error');
			return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeBasic']['id']));
		}

		//Set business account
		$business_account = $this->FmFeTransactionEntry->FmFeTransaction->FmFeBasic->FmBusinessAccount->find('first', array(
			'conditions' => array('FmBusinessAccount.id' => $fe_transaction['FmFeBasic']['fm_business_account_id']),
			'recursive' => 0
		));
		$this->set('business_account', $business_account);

		//Check if form is submitted
		if ($this->request->is('post') || $this->request->is('put')) {
			//Save to DB
			if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'customer'){
				//Check for transaction type
				if($fe_transaction['FmFeTransaction']['transaction_type'] == 'by_invoice'){
					//Save by invoice
					$load_result = $this->FmFeTransactionEntry->saveCustomerInvoicedEntries($fm_fe_transaction_id, $this->request->data);
				}
				if($fe_transaction['FmFeTransaction']['transaction_type'] == 'by_proform'){
					//Save by proform
					$load_result = $this->FmFeTransactionEntry->saveCustomerProformEntries($fm_fe_transaction_id, $this->request->data);
				}
				if($fe_transaction['FmFeTransaction']['transaction_type'] == 'return_goods'){
					//Save by account records
					$load_result = $this->FmFeTransactionEntry->saveCustomerAccountEntries($fm_fe_transaction_id, $this->request->data);					
				}
				if($fe_transaction['FmFeTransaction']['transaction_type'] == 'unknown'){
					//:TO DO:
				}			
			}
			if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'supplier'){
				//:TO DO:
			}
			if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'company'){
				//:TO DO:
			}
			if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'bank'){
				//:TO DO:
			}
			if($load_result['success']){
				//Get operator
				$user = $this->Session->read('Auth.User');

				//Save action log        		
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeTransactionEntry has been loaded');

	            //Set message and reset form
				$this->Session->setFlash(__('Otvorene stavke su uspešno učitane.'), 'flash_success');
				return $this->redirect(array('controller' => 'FmFeTransactionEntries', 'action' => 'save', $fm_fe_transaction_id));
			}else{
				$this->Session->setFlash('Otvorene stavke ne mogu biti učitane! Greška: '.$load_result['message'], 'flash_error');
			}
		}else{
			//Check for existing entries
			$entries = $this->FmFeTransactionEntry->getTransactionEntriesWithDeleted($fm_fe_transaction_id);
			foreach ($entries as $entry) {
				if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'customer'){
					//Check for transaction type
					if(in_array($fe_transaction['FmFeTransaction']['transaction_type'], array('by_invoice', 'return_goods'))){
						if(empty($entry['FmFeTransactionEntry']['deleted'])){
							$this->request->data['FmFeTransactionEntry']['select_record_'.$entry['FmFeTransactionEntry']['fm_account_order_record_id']] = 1;
						}else{
							$this->request->data['FmFeTransactionEntry']['select_record_'.$entry['FmFeTransactionEntry']['fm_account_order_record_id']] = 0;
						}
					}
					if(in_array($fe_transaction['FmFeTransaction']['transaction_type'], array('by_proform'))){
						if(empty($entry['FmFeTransactionEntry']['deleted'])){
							$this->request->data['FmFeTransactionEntry']['select_order_'.$entry['FmFeTransactionEntry']['order_id']] = 1;
						}else{
							$this->request->data['FmFeTransactionEntry']['select_order_'.$entry['FmFeTransactionEntry']['order_id']] = 0;
						}						
					}
				}	
				$this->request->data['FmFeTransactionEntry']['id_'.$entry['FmFeTransactionEntry']['fm_account_order_record_id']] = $entry['FmFeTransactionEntry']['id'];
			}
		}

		//Check payer/recipient
		$this->autoRender = false;
		if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'customer'){
			if(in_array($fe_transaction['FmFeTransaction']['transaction_type'], array('by_invoice', 'return_goods'))){
				//Check for chart accounts
				if(empty($fe_transaction['FmFeTransactionType']['fm_chart_account_links'])){
					//Set error message and redirect to foreign exchange basic
					$this->Session->setFlash('Devizna transakcija se ne može evidentirati po otvorenim stavkama!', 'flash_error');
					return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeTransaction']['fm_fe_basic_id']));
				}

				//Get codebook connection data
				$codebook_connection_data = $this->FmFeTransactionEntry->FmChartAccount->FmAccountOrderRecord->CodebookConnectionData->find('first', array(
					'conditions' => array('CodebookConnectionData.model_name' => 'Client', 'CodebookConnectionData.data_id' => $fe_transaction['FmFeTransaction']['client_id']),
					'recursive' => -1
				));
				if(empty($codebook_connection_data)){
					//Set error message and redirect to foreign exchange basic view
					$this->Session->setFlash('Analitika otvorenih stavki nije validna!', 'flash_error');
					return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeTransaction']['fm_fe_basic_id']));
				}

				//Get chart account ids
				$fm_chart_account_links = explode(',', $fe_transaction['FmFeTransactionType']['fm_chart_account_links']);

				//Load opened account cards
				$params = array(
					'fm_chart_account_links' => $fm_chart_account_links,
					'codebook_connection_data_id' => $codebook_connection_data['CodebookConnectionData']['id']					
				);
				if($fe_transaction['FmFeTransaction']['transaction_type'] == 'by_invoice'){
					$params['currency_id'] = $business_account['Currency']['id'];
				}
				$customer_opened_records = $this->FmFeTransactionEntry->FmChartAccount->FmAccountOrderRecord->getCustomerOpened($params);
			}

			//Get all exchange rates for date
			$exchange_rates = $this->FmFeTransactionEntry->FmFeTransaction->FmFeBasic->FmBusinessAccount->Currency->ExchangeRate->getDateIntermediateRatesGroupedByISO($fe_transaction['FmFeBasic']['fe_date']);
            if(empty($exchange_rates)){
				//Set error message and redirect to foreign exchange basic view
				$this->Session->setFlash('Devizni kursevi nisu validni!', 'flash_error');
				return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeTransaction']['fm_fe_basic_id']));
            }
			$this->set('exchange_rates', $exchange_rates);

			//Check for transaction type
			if($fe_transaction['FmFeTransaction']['transaction_type'] == 'by_invoice'){				
				$this->set('customer_opened_records', $customer_opened_records);

				//Set title
				$this->set('title_for_layout', 'Pregled potraživanja (otvorene stavke) - Devizni izvodi - Finansijski modul - MikroERP');

				//Render view
				$this->render('customer_opened_records');
			}
			if($fe_transaction['FmFeTransaction']['transaction_type'] == 'by_proform'){
				//Get customer unpaid orders
				$customer_proform_records = $this->FmFeTransactionEntry->FmFeTransaction->Client->ClientOrder->getClientOrdersProformForeignExchange($fe_transaction['FmFeTransaction']['client_id']);
				$this->set('customer_proform_records', $customer_proform_records);

				//Set fullscreen layout
				$this->layout = 'fullscreen';

				//Set title
				$this->set('title_for_layout', 'PREGLED IZDATIIH DEVIZNIH PROFAKTURA - Devizni izvodi - Finansijski modul - MikroERP');

				//Render view
				$this->render('customer_proform_records');
			}
			if($fe_transaction['FmFeTransaction']['transaction_type'] == 'return_goods'){
				//Set account data				
				$this->set('customer_account_records', $customer_opened_records);

				//Set fullscreen layout
				$this->layout = 'fullscreen';

				//Set title
				$this->set('title_for_layout', 'PREGLED OTVORENIH OBAVEZA PREMA INO-KUPCU - Devizni izvodi - Finansijski modul - MikroERP');

				//Render view
				$this->render('customer_account_records');
			}
			if($fe_transaction['FmFeTransaction']['transaction_type'] == 'unknown'){
				//:TO DO:
			}			
		}
		if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'supplier'){
			//:TO DO:
		}
		if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'company'){
			//:TO DO:
		}
		if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'bank'){
			//:TO DO:
		}
	}//~!	

	/**
	 * save method
	 * $fm_fe_transaction_id - FmFeTransaction.id
	 * @return void
	 */
	public function save($fm_fe_transaction_id) {	
		//Set fullscreen layout
		$this->layout = 'fullscreen';

		//Set title
		$this->set('title_for_layout', 'OBRAZAC ZA EVIDENTIRANJE DEVIZNE TRANSAKCIJE - Devizni izvodi - Finansijski modul - MikroERP');

		//Get transaction
		$result = $this->FmFeTransactionEntry->FmFeTransaction->getTransactionForEditing($fm_fe_transaction_id);
		if(!$result['success']){
			$this->Session->setFlash($result['message'], 'flash_error');
			return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'index'));
		}
		$fe_transaction = $result['fe_transaction'];
		$this->set('fe_transaction', $fe_transaction);
		
		//Set transaction field titles
		$this->set('flow_types', $this->FmFeTransactionEntry->FmFeTransaction->flow_types);
		$this->set('payer_recipients', $this->FmFeTransactionEntry->FmFeTransaction->FmFeTransactionType->payer_recipients);
		$this->set('transaction_types', $this->FmFeTransactionEntry->FmFeTransaction->FmFeTransactionType->transaction_types);

		//Check for conditions
		if(empty($fe_transaction['FmFeTransactionType']['fm_fe_account_scheme_id']) || !empty($fe_transaction['FmFeTransactionRecord'])){
			//Set error message and redirect to foreign exchange basic
			$this->Session->setFlash('Pregled obrazca za evidentiranje devizne transakcije nije moguć!', 'flash_error');
			return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeBasic']['id']));
		}		

		//Set business account
		$business_account = $this->FmFeTransactionEntry->FmFeTransaction->FmFeBasic->FmBusinessAccount->find('first', array(
			'conditions' => array('FmBusinessAccount.id' => $fe_transaction['FmFeBasic']['fm_business_account_id']),
			'recursive' => 0
		));
		$this->set('business_account', $business_account);

		//Get transaction entries
		$fe_transaction_entries = $this->FmFeTransactionEntry->getTransactionEntries($fm_fe_transaction_id);
		$this->set('fe_transaction_entries', $fe_transaction_entries);

		//Get all exchange rates for date
		$exchange_rates = $this->FmFeTransactionEntry->FmFeTransaction->FmFeBasic->FmBusinessAccount->Currency->ExchangeRate->getDateIntermediateRatesGroupedByISO($fe_transaction['FmFeBasic']['fe_date']);
        if(empty($exchange_rates)){
			//Set error message and redirect to foreign exchange basic view
			$this->Session->setFlash('Devizni kursevi nisu validni!', 'flash_error');
			return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeTransaction']['fm_fe_basic_id']));
        }
		$this->set('exchange_rates', $exchange_rates);

		//Check if form is submitted
		$form_submitted = false;
		if ($this->request->is('post') || $this->request->is('put')) {
			//Save to DB
			$result = $this->FmFeTransactionEntry->saveMultiple($fm_fe_transaction_id, $this->request->data);
			if($result['success']){
				//Get operator
				$user = $this->Session->read('Auth.User');

				//Save action log        		
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The multiple FmFeTransactionEntry have been saved');

	            //Set message and reset form
				$this->Session->setFlash(__('Obrazac za evidentiranje devizne transakcije je snimljen.'), 'flash_success');
				return $this->redirect(array('controller' => 'FmFeTransactionRecords', 'action' => 'index', $fm_fe_transaction_id));
			}else{
				$this->Session->setFlash('Obrazac za evidentiranje devizne transakcije nije snimljen! Greška: '.$result['message'], 'flash_error');
			}
			$form_submitted = true;
		}
		$this->set('form_submitted', $form_submitted);

		//Set account manners
		$account_manners = array();
		if(array_key_exists($fe_transaction['FmFeTransaction']['transaction_type'], $this->FmFeTransactionEntry->transaction_type_manners)){
			$account_manners = $this->FmFeTransactionEntry->transaction_type_manners[$fe_transaction['FmFeTransaction']['transaction_type']];
		}
		$this->set('account_manners', $account_manners);

		//Set view
		if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'customer'){
			//Check for transaction type
			if($fe_transaction['FmFeTransaction']['transaction_type'] == 'by_invoice'){
				//Render view
				$this->render('customer_invoiced_entries');
			}
			if($fe_transaction['FmFeTransaction']['transaction_type'] == 'by_proform'){					
				//Render view
				$this->render('customer_proform_entries');
			}
			if($fe_transaction['FmFeTransaction']['transaction_type'] == 'return_goods'){
				//Render view
				$this->render('customer_account_entries');
			}
			if($fe_transaction['FmFeTransaction']['transaction_type'] == 'unknown'){
				//:TO DO:
			}			
		}
		if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'supplier'){
			//:TO DO:
		}
		if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'company'){
			//:TO DO:
		}
		if($fe_transaction['FmFeTransaction']['payer_recipient'] == 'bank'){
			//:TO DO:
		}		
	}//~!
}