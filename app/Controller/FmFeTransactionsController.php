<?php
App::uses('AppController', 'Controller');
/**
 * FmFeTransactions Controller
 *
 * @property FmFeTransaction $FmFeTransaction
 * @property PaginatorComponent $Paginator
 */
class FmFeTransactionsController extends AppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Session', 'Acl', 'Cookie', 'Ctrl', 'RequestHandler', 'ErpLogManagement','Paginator', 'Search', 'String');

	/**
	 * index method
	 * $fm_fe_basic_id - FmFeBasic.id
	 * @return void
	 */
	public function index($fm_fe_basic_id) {
		//Check if ajax call
		if ($this->request->is('ajax')) {
			//Disable caching for ajax calls
			$this->disableCache();

			//Check if fe basic exists
			$fe_basic = $this->FmFeTransaction->FmFeBasic->getFeBasic($fm_fe_basic_id);
			if(empty($fe_basic)){
				return false;
			}
			//Check if fe basic deleted
			if(!empty($fe_basic['FmFeBasic']['deleted'])){
				return false;
			}
			//Check if verified
			if(!empty($fe_basic['FmFeBasic']['user_id_verified'])){
				return false;
			}

			//Set foreign exchange basic info
			$this->set('fe_basic', $fe_basic);

			//Get inflow/outflow sums
			$flow_sums = $this->FmFeTransaction->getFlowSums($fm_fe_basic_id);
			$this->set('flow_sums', $flow_sums);

			//Get all transactions
			$fe_transactions = $this->FmFeTransaction->getAllTransactions($fm_fe_basic_id);
			$this->set('fe_transactions', $fe_transactions);

			//Set defaults
			$this->set('payer_recipients', $this->FmFeTransaction->FmFeTransactionType->payer_recipients);
			$this->set('transaction_types', $this->FmFeTransaction->FmFeTransactionType->transaction_types);
			$this->set('flow_types', $this->FmFeTransaction->flow_types);

		}else{
			throw new NotFoundException(__('Stranica ne postoji!'));
		}			
	}//~!

	/**
	 * save method
	 * $fm_fe_basic_id - FmFeBasic.id, $id - FmFeTransaction.id
	 * @return void
	 */
	public function save($fm_fe_basic_id, $id=null) {
		if ($this->request->is('ajax')) {
			//Disable caching for ajax calls
			$this->disableCache();			
			
			//Check if fe basic exists
			$fe_basic = $this->FmFeTransaction->FmFeBasic->getFeBasic($fm_fe_basic_id);
			if(empty($fe_basic)){
				return false;
			}
			//Check if fe basic deleted
			if(!empty($fe_basic['FmFeBasic']['deleted'])){
				return false;
			}
			//Check if verified
			if(!empty($fe_basic['FmFeBasic']['user_id_verified'])){
				return false;
			}

			//Set foreign exchange basic info
			$this->set('fe_basic', $fe_basic);

			//Get inflow/outflow sums
			$flow_sums = $this->FmFeTransaction->getFlowSums($fm_fe_basic_id);
			$this->set('flow_sums', $flow_sums);

			//Get all transactions
			$fe_transactions = $this->FmFeTransaction->getAllTransactions($fm_fe_basic_id);
			$this->set('fe_transactions', $fe_transactions);

			//Set defaults
			$this->set('payer_recipients', $this->FmFeTransaction->FmFeTransactionType->payer_recipients);
			$this->set('transaction_types', $this->FmFeTransaction->FmFeTransactionType->transaction_types);
			$this->set('flow_types', $this->FmFeTransaction->flow_types);
			$this->set('transaction_links', $this->FmFeTransaction->FmFeTransactionType->transaction_links);			

			$this->set('save_form', true);

			//Check for existing fe transaction
			if(!empty($id)){
				//Get transaction for editing				
				$result = $this->FmFeTransaction->getTransactionForEditing($id);
				if(!$result['success']){
					$this->Session->setFlash($result['message'], 'flash_error');
					return $this->redirect(array('action' => 'index'));					
				}
				$fe_transaction = $result['fe_transaction'];
			}

			//Check if form is submitted
			if ($this->request->is('post') || $this->request->is('put')) {
				//Check if basic is new
				if(!empty($id)){
					$this->request->data['FmFeTransaction']['id'] = $id;
					$this->request->data['FmFeTransaction']['ordinal'] = $fe_transaction['FmFeTransaction']['ordinal'];
				}else{
					$this->FmFeTransaction->create();
				}

				//Set basic info
				$this->request->data['FmFeTransaction']['fm_fe_basic_id'] = $fm_fe_basic_id;
				$this->request->data['FmFeTransaction']['transaction_status'] = 'opened';

				//Set transaction type
				$this->request->data['FmFeTransaction']['fm_fe_transaction_type_id'] = null;
				if(!empty($this->request->data['FmFeTransaction']['transaction_type']) && !empty($this->request->data['FmFeTransaction']['payer_recipient'])){
					$transaction_type = $this->request->data['FmFeTransaction']['transaction_type'];
					$payer_recipient = $this->request->data['FmFeTransaction']['payer_recipient'];

					$fm_fe_transaction_type = $this->FmFeTransaction->FmFeTransactionType->getType($payer_recipient, $transaction_type);
					if(!empty($fm_fe_transaction_type)){
						$this->request->data['FmFeTransaction']['fm_fe_transaction_type_id'] = $fm_fe_transaction_type['FmFeTransactionType']['id'];
					}
				}

				//Save to DB
				if($this->FmFeTransaction->save($this->request->data)){
					//Get operator
					$user = $this->Session->read('Auth.User');

					//Save action log
					$input_data = serialize($this->request->data);
		            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeTransaction has been saved');

					//Refresh inflow/outflow sums
					$flow_sums = $this->FmFeTransaction->getFlowSums($fm_fe_basic_id);
					$this->set('flow_sums', $flow_sums);

		            //Refresh transactions
					$fe_transactions = $this->FmFeTransaction->getAllTransactions($fm_fe_basic_id);
					$this->set('fe_transactions', $fe_transactions);

		            //Set message and reset form
					$this->Session->setFlash(__('Devizna transakcija je snimljena.'), 'flash_success');
					if(empty($id)){
						$this->request->data['FmFeTransaction'] = array();
						$this->request->data['FmFeTransaction']['payer_recipient'] = 'customer';
						$this->request->data['FmFeTransaction']['fm_fe_basic_id'] = $fm_fe_basic_id;
					}
				}else{
	                $errors = $this->FmFeTransaction->validationErrors;
					$this->Session->setFlash('Devizna transakcija nije snimljena! Greška: '.array_shift($errors)[0], 'flash_error');
				}
			}else{
				if(!empty($fe_transaction)){
					$this->request->data['FmFeTransaction'] = $fe_transaction['FmFeTransaction'];
					$this->request->data['FmFeTransaction']['client_title'] = $fe_transaction['Client']['title'];
				}else{
					$this->request->data['FmFeTransaction']['payer_recipient'] = 'customer';
				}
				$this->request->data['FmFeTransaction']['fm_fe_basic_id'] = $fm_fe_basic_id;
			}
		}else{
			throw new NotFoundException(__('Stranica ne postoji!'));
		}			
	}//~!

	/**
	 * Close transaction method
	 * @param $id - FmFeTransaction.id
	 * @return void
	 */
	public function close($id) {
		if($this->request->is('ajax')){
			//Disable caching for ajax calls
			$this->disableCache();			

		    //Get transaction for editing
			$result = $this->FmFeTransaction->getTransactionForEditing($id);
			if(!$result['success']){
				$this->Session->setFlash($result['message'], 'flash_error');
				return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'index'));
			}
			$fe_transaction = $result['fe_transaction'];

			//Save to DB
			$close_result = $this->FmFeTransaction->closeTransaction($id);
			if($close_result['success']){
				//Get operator
				$user = $this->Session->read('Auth.User');	
				
				//Save action log
				$input_data = serialize($close_result);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeTransaction has been closed');

			    //Set success message and redirect to index page
				$this->Session->setFlash(__('Transakcija pod rednim brojem '.$fe_transaction['FmFeTransaction']['ordinal'].' je zatvorena!'), 'flash_success');
			}else{
				$this->Session->setFlash($close_result['message'], 'flash_error');
			}

			return $this->redirect(array('controller' => 'FmFeTransactions', 'action' => 'index', $fe_transaction['FmFeTransaction']['fm_fe_basic_id']));
		}else{
			throw new NotFoundException(__('Stranica ne postoji!'));
		}
	}//~!

	/**
	 * delete method
	 * @param $id - FmFeTransaction.id
	 * @return void
	 */
	public function delete($id=null) {
		if($this->request->is('ajax')){
			//Disable caching for ajax calls
			$this->disableCache();			

		    //Get transaction for editing
			$fe_transaction = $this->FmFeTransaction->getFeTransaction($id);
			if(empty($fe_transaction)){
				$this->Session->setFlash($result['message'], 'flash_error');
				return $this->redirect(array('controller' => 'FmFeBasics', 'action' => 'index'));
			}

			//Save to DB
			$delete_result = $this->FmFeTransaction->deleteTransaction($id);
			if(empty($delete_result['error'])){
				//Get operator
				$user = $this->Session->read('Auth.User');	
				
				//Save action log        		
				$input_data = serialize($delete_result);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeTransaction has been deleted');

			    //Set success message and redirect to index page
				$this->Session->setFlash(__('Devizna transakcija pod rednim brojem '.$fe_transaction['FmFeTransaction']['ordinal'].' je obrisana!'), 'flash_success');
			}else{
				$this->Session->setFlash($delete_result['error'], 'flash_error');
			}

			return $this->redirect(array('controller' => 'FmFeTransactions', 'action' => 'index', $fe_transaction['FmFeTransaction']['fm_fe_basic_id']));
		}else{
			throw new NotFoundException(__('Stranica ne postoji!'));
		}
	}//~!
}