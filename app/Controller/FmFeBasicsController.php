<?php
App::uses('AppController', 'Controller');
/**
 * FmFeBasics Controller
 *
 * @property FmFeBasic $FmFeBasic
 * @property PaginatorComponent $Paginator
 */
class FmFeBasicsController extends AppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Session', 'Acl', 'Cookie', 'Ctrl', 'RequestHandler', 'ErpLogManagement','Paginator', 'Search', 'String');

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->set('title_for_layout', 'Devizni izvodi banke - MikroERP');

        //Check for query
        $conditions = array();
        $conditions['NOT'] = array('FmFeBasic.deleted' => 1);
        if(!empty($this->request->query)){
            $this->request->data['FmFeBasic'] = $this->request->query;

            //Check for business account
            if(!empty($this->request->query['fm_business_account_id'])){
                $conditions[] = array('FmFeBasic.fm_business_account_id' => $this->request->query['fm_business_account_id']);
            }            
            //Search for fe number
            if(!empty($this->request->query['fe_number'])){
                $conditions[] = array('FmFeBasic.fe_number' => $this->request->query['fe_number']);
            }
            //Check for account type
            if(!empty($this->request->query['fe_date_from'])){
                $conditions[] = array('FmFeBasic.fe_date >=' => $this->request->query['fe_date_from']);
            }            
            //Check for bank
            if(!empty($this->request->query['fe_date_to'])){
                $conditions[] = array('FmFeBasic.fe_date <=' => $this->request->query['fe_date_to']);
            }
        }

        //Set data
        $settings = array();
        $settings['conditions'] = $conditions;
        $settings['order'] = array('FmFeBasic.ordinal' => 'ASC');
		$settings['contain'] = array('FmBusinessAccount' => array('CbBank.code', 'Currency.iso'), 'FmBusinessAccount.account_number');
        $this->Paginator->settings = $settings;

		$this->FmFeBasic->recursive = -1;
		
		$this->FmFeBasic->Behaviors->load('Containable');
		$fe_basics = $this->Paginator->paginate('FmFeBasic');	
		$this->FmFeBasic->Behaviors->unload('Containable');

		$this->set('fe_basics', $fe_basics);

		//Set accounts
		$accounts = $this->FmFeBasic->FmBusinessAccount->getAccountList(array('skip_local' => true));
		$this->set('accounts', $accounts);

		//Set users
		$users = $this->FmFeBasic->User->getAllUsers();
		$this->set('users', $users);
	}//~!

	/**
	 * save method
	 *
	 * @return void
	 */
	public function save($id=null) {
		$this->set('title_for_layout', 'Snimanje - Devizni izvodi banke - MikroERP');

		if(!empty($id)){
			//Check if exists
			$fe_basic = $this->FmFeBasic->find('first', array('conditions' => array('FmFeBasic.id' => $id), 'recursive' => -1));
			if(empty($fe_basic)){
				$this->Session->setFlash("Izvod nije validan", 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}
			//Check if deleted
			if(!empty($fe_basic['FmFeBasic']['deleted'])){
				$this->Session->setFlash("Izvod je obrisan i nije moguća izmena", 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}
			//Check if verified
			if(!empty($fe_basic['FmFeBasic']['user_id_verified'])){
				$this->Session->setFlash("Izvod je verifikovan i nije moguća izmena", 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}			
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			//Check if basic is new
			if(!empty($id)){
				$this->request->data['FmFeBasic']['id'] = $id;
				$this->request->data['FmFeBasic']['ordinal'] = $fe_basic['FmFeBasic']['ordinal'];
				$this->request->data['FmFeBasic']['fe_number'] = $fe_basic['FmFeBasic']['fe_number'];
			}else{
				$this->FmFeBasic->create();
			}

			//Set user verified to null
			$this->request->data['FmFeBasic']['user_id_verified'] = null;

			//Save to DB
			if($this->FmFeBasic->save($this->request->data)){
				//Get operator
				$user = $this->Session->read('Auth.User');

				//Save action log        		
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeBasic has been saved');

	            //Set message and redirect to index page
				$this->Session->setFlash(__('Osnovne podaci o izvodu banke su snimljeni.'), 'flash_success');
        		return $this->redirect(array('action' => 'view', $this->FmFeBasic->id));
			}else{
                $errors = $this->FmFeBasic->validationErrors;
				$this->Session->setFlash('Osnovne podaci o izvodu banke nisu snimljeni! Greška: '.array_shift($errors)[0], 'flash_error');
			}
		}else{
			if(!empty($fe_basic)){
				$this->request->data['FmFeBasic'] = $fe_basic['FmFeBasic'];
			}
		}

		//Set accounts
		$accounts = $this->FmFeBasic->FmBusinessAccount->getAccountList(array('skip_local' => true));
		$this->set('accounts', $accounts);
	}//~!

	/**
	 * view method
	 * @param $id - FmFeBasic.id
	 * @return void
	 */
	public function view($id) {
		//Set title
		$this->set('title_for_layout', 'Pregled izvoda - Devizni izvodi banke - MikroERP');

		//Disable cache
		$this->disableCache();

		//Check if basic info exists
		$fe_basic = $this->FmFeBasic->getFeBasic($id);
		if(empty($fe_basic)){
			$this->Session->setFlash("Izvod nije validan", 'flash_error');
			return $this->redirect(array('action' => 'index'));
		}
		//Check if deleted
		if(!empty($fe_basic['FmFeBasic']['deleted'])){
			$this->Session->setFlash("Izvod je obrisan i nije moguća izmena", 'flash_error');
			return $this->redirect(array('action' => 'index'));
		}
		$this->set('fe_basic', $fe_basic);

		//Get inflow/outflow sums
		$flow_sums = $this->FmFeBasic->FmFeTransaction->getFlowSums($id);
		$this->set('flow_sums', $flow_sums);

		//Get all transactions
		$fe_transactions = $this->FmFeBasic->FmFeTransaction->getAllTransactions($id);
		$this->set('fe_transactions', $fe_transactions);

		//Set defaults
		$this->set('payer_recipients', $this->FmFeBasic->FmFeTransaction->FmFeTransactionType->payer_recipients);
		$this->set('transaction_types', $this->FmFeBasic->FmFeTransaction->FmFeTransactionType->transaction_types);
		$this->set('flow_types', $this->FmFeBasic->FmFeTransaction->flow_types);
		
		if($this->request->is('ajax')){
			//Custom render
			$this->autoRender = false;
			$this->render('records');
		}
	}//~!

	/**
	 * delete method
	 * @param $id - FmFeBasic.id
	 * @return void
	 */
	public function delete($id=null) {
		//Save to DB
		$delete_result = $this->FmFeBasic->deleteBasic($id);
		if(empty($delete_result['error'])){
			//Get operator
			$user = $this->Session->read('Auth.User');	
			
			//Save action log        		
			$input_data = serialize($delete_result);
            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeBasic has been deleted');

		    //Set success message and redirect to index page
			$this->Session->setFlash(__('Izvod banke je obrisan'), 'flash_success');
		}else{
			$this->Session->setFlash($delete_result['error'], 'flash_error');
		}

		return $this->redirect(array('action' => 'index'));
	}//~!
}