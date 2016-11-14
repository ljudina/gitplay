<?php
App::uses('AppController', 'Controller');
/**
 * FmFeTransactionRecords Controller
 *
 * @property FmFeTransactionRecord $FmFeTransactionRecord
 * @property PaginatorComponent $Paginator
 */
class FmFeTransactionRecordsController extends AppController {

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
		//Set title
		$this->set('title_for_layout', 'Obrazac za knjizenje deviznih transakcija - Devizni izvodi - Finansijski modul - MikroERP');

		//Set wider layout
		$this->layout = 'fullscreen';

		//Get transaction
		$fe_transaction = $this->FmFeTransactionRecord->FmFeTransaction->getFeTransaction($fm_fe_transaction_id);
		if(empty($fe_transaction)){
			$this->Session->setFlash("Transakcija nije validna!", 'flash_error');
			return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'index'));
		}

		//Set foreign exchange transaction
		$this->set('fe_transaction', $fe_transaction);

		//Set transaction field titles
		$this->set('flow_types', $this->FmFeTransactionRecord->FmFeTransaction->flow_types);
		$this->set('payer_recipients', $this->FmFeTransactionRecord->FmFeTransaction->FmFeTransactionType->payer_recipients);
		$this->set('transaction_types', $this->FmFeTransactionRecord->FmFeTransaction->FmFeTransactionType->transaction_types);

		//Set business account
		$business_account = $this->FmFeTransactionRecord->FmFeTransaction->FmFeBasic->FmBusinessAccount->find('first', array(
			'conditions' => array('FmBusinessAccount.id' => $fe_transaction['FmFeBasic']['fm_business_account_id']),
			'recursive' => 0
		));
		$this->set('business_account', $business_account);

		//Get all transaction records
		$records = $this->FmFeTransactionRecord->getAllTransactionRecords($fm_fe_transaction_id);
		$this->set('records', $records);

		//If no records redirect to entry page
		if(empty($records)){
			return $this->redirect(array('controller' => 'FmFeTransactionEntries', 'action' => 'load_entries', $fe_transaction['FmFeTransaction']['id']));
		}

		//Get records sum
		$record_sum = $this->FmFeTransactionRecord->getTransactionRecordsSum($fm_fe_transaction_id, array('currency_group' => true));
		$this->set('record_sum', $record_sum);

		//Check if ajax call
		if($this->request->is('ajax')) {
			//Disable caching for ajax calls
			$this->disableCache();

			//Set ajax layout
			$this->layout = 'ajax';

			//Render records
			$this->render('records');
		}
	}//~!

	/**
	 * save method
	 * $fm_fe_transaction_id - FmFeTransactionRecord.id, $id - FmFeTransactionRecord.id
	 * @return void
	 */
	public function save($fm_fe_transaction_id, $id=null) {
		if ($this->request->is('ajax')) {
			//Disable caching for ajax calls
			$this->disableCache();			
			
			//Get transaction
			$result = $this->FmFeTransactionRecord->FmFeTransaction->getTransactionForEditing($fm_fe_transaction_id);
			if(!$result['success']){
				$this->Session->setFlash($result['message'], 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}
			$transaction = $result['fe_transaction'];		

			//Set foreign exchange basic info
			$this->set('transaction', $transaction);
			$this->set('save_form', true);

			//Check if fe transaction exists
			$basic = $this->FmFeTransactionRecord->FmFeTransaction->FmFeBasic->getFeBasic($transaction['FmFeBasic']['id']);
			if(empty($basic)){
				return false;
			}
			$this->set('basic', $basic);

			//Get all transaction records
			$records = $this->FmFeTransactionRecord->getAllTransactionRecords($fm_fe_transaction_id);
			$this->set('records', $records);

			//Get records sum
			$record_sum = $this->FmFeTransactionRecord->getTransactionRecordsSum($fm_fe_transaction_id, array('currency_group' => true));
			$this->set('record_sum', $record_sum);

			//Set transaction desc
			$transaction_desc_read_only = false;
			if(!empty($transaction['FmFeTransactionType']['desc_data'])){
				$this->request->data['FmFeTransactionRecord']['transaction_desc'] = $transaction['FmFeTransactionType']['desc_data'];				
				$transaction_desc_read_only = true;
			}
			$this->set('transaction_desc_read_only', $transaction_desc_read_only);

			//Check for existing fe transaction
			if(!empty($id)){
				//Check if fe basic exists
				$record = $this->FmFeTransactionRecord->find('first', array(
					'conditions' => array('FmFeTransactionRecord.id' => $id),
					'recursive' => 0
				));

				//Check if deleted
				if(!empty($record['FmFeTransactionRecord']['deleted'])){
					return false;
				}
			}

			//Set chart accounts
			$this->set('chart_accounts', $this->FmFeTransactionRecord->FmChartAccount->getChartAccountList(true, false, true));

			//Set chart accounts
			$this->set('codebook_document_types', $this->FmFeTransactionRecord->CodebookDocumentType->getTypeList());

			//Set traffic statuses
			$this->set('traffic_statuses', $this->FmFeTransactionRecord->FmTrafficStatus->listTrafficStatusesCode());

			//Check if form is submitted
			if ($this->request->is('post') || $this->request->is('put')) {
				//Check if basic is new
				if(!empty($id)){
					$this->request->data['FmFeTransactionRecord']['id'] = $id;
					$this->request->data['FmFeTransactionRecord']['ordinal'] = $record['FmFeTransactionRecord']['ordinal'];
				}

				//Set basic info
				$this->request->data['FmFeTransactionRecord']['fm_fe_transaction_id'] = $fm_fe_transaction_id;
				$this->request->data['FmFeTransactionRecord']['fm_fe_transaction_entry_id'] = null;

				//Save to DB
				$result = $this->FmFeTransactionRecord->saveTransactionRecord($this->request->data);
				if($result['success']){
					//Get operator
					$user = $this->Session->read('Auth.User');

					//Save action log        		
					$input_data = serialize($this->request->data);
		            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeTransactionRecord has been saved');

		            //Refresh records
					$records = $this->FmFeTransactionRecord->getAllTransactionRecords($fm_fe_transaction_id);
					$this->set('records', $records);

					//Get records sum
					$record_sum = $this->FmFeTransactionRecord->getTransactionRecordsSum($fm_fe_transaction_id, array('currency_group' => true));
					$this->set('record_sum', $record_sum);

		            //Set message and reset form
					$this->Session->setFlash(__('Stavka je snimljena.'), 'flash_success');
				}else{
					$this->Session->setFlash('Stavka nije snimljena! Greška: '.$result['message'], 'flash_error');
				}
			}else{
				if(!empty($record)){
					$this->request->data['FmFeTransactionRecord'] = $record['FmFeTransactionRecord'];
				}
			}
		}else{
			throw new NotFoundException(__('Stranica ne postoji!'));
		}
	}//~!

	/**
	 * delete method
	 * @param $id - FmFeTransactionRecord.id
	 * @return void
	 */
	public function delete($id) {
		if($this->request->is('ajax')){
			//Disable caching for ajax calls
			$this->disableCache();			

			//Save to DB
			$delete_result = $this->FmFeTransactionRecord->deleteRecord($id);
			if(empty($delete_result['error'])){
				//Get operator
				$user = $this->Session->read('Auth.User');	
				
				//Save action log
				$input_data = serialize($delete_result);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeTransactionRecord has been deleted');

			    //Set success message
				$this->Session->setFlash(__('Stavka pod rednim brojem '.$delete_result['FmFeTransactionRecord']['ordinal'].' je obrisana!'), 'flash_success');
				return $this->redirect(array('controller' => 'FmFeTransactionRecords', 'action' => 'index', $delete_result['FmFeTransactionRecord']['fm_fe_transaction_id']));
			}else{
				//Set error message and redirect to index page
				$this->Session->setFlash($delete_result['error'], 'flash_error');
				return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'index'));
			}			
		}else{
			throw new NotFoundException(__('Stranica ne postoji!'));
		}
	}//~!

	/**
	 * Method canceling/deleting all record for transaction
	 * @param $fm_fe_transaction_id - FmFeTransaction.id
	 * @return void
	 */
	public function cancel($fm_fe_transaction_id) {
		//Get transaction
		$result = $this->FmFeTransactionRecord->FmFeTransaction->getTransactionForEditing($fm_fe_transaction_id);
		if(!$result['success']){
			$this->Session->setFlash($result['message'], 'flash_error');
			return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'index'));
		}
		$fe_transaction = $result['fe_transaction'];

		//Save to DB
		$delete_result = $this->FmFeTransactionRecord->deleteAllTransactionRecords($fm_fe_transaction_id);
		if($delete_result['success']){
			//Get operator
			$user = $this->Session->read('Auth.User');	
			
			//Save action log
			$input_data = serialize($delete_result);
            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'All FmFeTransactionRecord have been deleted');

		    //Set success message and redirect to load entries page
			$this->Session->setFlash(__('Obrazac za knjiženje je storniran!'), 'flash_success');
			return $this->redirect(array('controller' => 'FmFeTransactionEntries', 'action' => 'load_entries', $fe_transaction['FmFeTransaction']['id']));
		}else{
			//Set error message and redirect to basic view page
			$this->Session->setFlash($delete_result['message'], 'flash_error');
			return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'view', $fe_transaction['FmFeTransaction']['fm_fe_basic_id']));
		}		
	}//~!
}