<?php
App::uses('AppController', 'Controller');
/**
 * FmBusinessAccounts Controller
 *
 * @property FmBusinessAccount $FmBusinessAccount
 * @property PaginatorComponent $Paginator
 */
class FmBusinessAccountsController extends AppController {

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
		$this->set('title_for_layout', 'Poslovni računi - MikroERP');

        //Check for query
        $conditions = array();
        $conditions['NOT'] = array('FmBusinessAccount.deleted' => 1);
        if(!empty($this->request->query)){
            $this->request->data['FmBusinessAccount'] = $this->request->query;
            
            //Search for account number
            if(!empty($this->request->query['account_number'])){
                $conditions[] = array('FmBusinessAccount.account_number LIKE' => '%'.$this->request->query['account_number'].'%');
            }
            //Check for account type
            if(!empty($this->request->query['account_type'])){
                $conditions[] = array('FmBusinessAccount.account_type' => $this->request->query['account_type']);
            }            
            //Check for bank
            if(!empty($this->request->query['cb_bank_id'])){
                $conditions[] = array('FmBusinessAccount.cb_bank_id' => $this->request->query['cb_bank_id']);
            }
            //Check for currency
            if(!empty($this->request->query['currency_id'])){
                $conditions[] = array('FmBusinessAccount.currency_id' => $this->request->query['currency_id']);
            }
            //Check for order type
            if(!empty($this->request->query['fm_order_type_id'])){
                $conditions[] = array('FmBusinessAccount.fm_order_type_id' => $this->request->query['fm_order_type_id']);
            }
            //Check for chart account
            if(!empty($this->request->query['fm_chart_account_id'])){
                $conditions[] = array('FmBusinessAccount.fm_chart_account_id' => $this->request->query['fm_chart_account_id']);
            }            
        }

        //Set data
        $settings = array();
        $settings['conditions'] = $conditions;
        $settings['order'] = array('FmBusinessAccount.ordinal' => 'ASC');

        $this->Paginator->settings = $settings;

		$this->FmBusinessAccount->recursive = 0;
		$this->set('accounts', $this->Paginator->paginate('FmBusinessAccount'));

		//Set account types
		$account_types = $this->FmBusinessAccount->account_types;
		$this->set('account_types', $account_types);

		//Set banks
		$banks = $this->FmBusinessAccount->CbBank->find('list', array('fields' => array('CbBank.code'), 'recursive' => -1));
		$this->set('banks', $banks);

		//Set currencies
		$currencies = $this->FmBusinessAccount->Currency->getCurrenciesByISO();
		$this->set('currencies', $currencies);		

		//Set order types
		$order_types = $this->FmBusinessAccount->FmOrderType->getOrderTypeList(true);
		$this->set('order_types', $order_types);

		//Set chart accounts
		$chart_accounts = $this->FmBusinessAccount->FmChartAccount->getChartAccountList(true);
		$this->set('chart_accounts', $chart_accounts);		
	}//~!

	/**
	 * save method
	 *
	 * @return void
	 */
	public function save($id=null) {
		$this->set('title_for_layout', 'Snimanje - Poslovni računi - MikroERP');

		if(!empty($id)){
			//Check if exists
			$account = $this->FmBusinessAccount->find('first', array('conditions' => array('FmBusinessAccount.id' => $id), 'recursive' => -1));
			if(empty($account)){
				$this->Session->setFlash("Poslovni račun nije validan", 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}
			//Check if deleted
			if(!empty($account['FmBusinessAccount']['deleted'])){
				$this->Session->setFlash("Poslovni račun je obrisan i nije moguća izmena", 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			//Check if business account is new
			if(!empty($id)){
				$this->request->data['FmBusinessAccount']['id'] = $id;
				$this->request->data['FmBusinessAccount']['ordinal'] = $account['FmBusinessAccount']['ordinal'];
			}else{
				$this->FmBusinessAccount->create();
			}

			//Save to DB
			if($this->FmBusinessAccount->save($this->request->data)){
				//Get operator
				$user = $this->Session->read('Auth.User');

				//Save action log        		
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmBusinessAccount has been saved');

	            //Set message and redirect to index page
				$this->Session->setFlash(__('Poslovni račun je snimljen.'), 'flash_success');
        		return $this->redirect(array('action' => 'index'));
			}else{
                $errors = $this->FmBusinessAccount->validationErrors;
				$this->Session->setFlash('Poslovni račun nije snimljen! Greška: '.array_shift($errors)[0], 'flash_error');
			}
		}else{
			if(!empty($account)){
				$this->request->data['FmBusinessAccount'] = $account['FmBusinessAccount'];
			}else{
				$this->request->data['FmBusinessAccount']['account_type'] = 'regular';
			}
		}

		//Set account types
		$account_types = $this->FmBusinessAccount->account_types;
		$this->set('account_types', $account_types);

		//Set banks
		$banks = $this->FmBusinessAccount->CbBank->find('list', array('fields' => array('CbBank.code'), 'recursive' => -1));
		$this->set('banks', $banks);

		//Set currencies
		$currencies = $this->FmBusinessAccount->Currency->getCurrenciesByISO();
		$this->set('currencies', $currencies);		

		//Set order types
		$order_types = $this->FmBusinessAccount->FmOrderType->getOrderTypeList(true);
		$this->set('order_types', $order_types);

		//Set chart accounts
		$chart_accounts = $this->FmBusinessAccount->FmChartAccount->getChartAccountList(true);
		$this->set('chart_accounts', $chart_accounts);		
	}//~!

	/**
	 * delete method
	 *
	 * @return void
	 */
	public function delete($id=null) {
		//Save to DB
		$delete_result = $this->FmBusinessAccount->deleteAccount($id);
		if(empty($delete_result['error'])){
			//Get operator
			$user = $this->Session->read('Auth.User');	
			
			//Save action log        		
			$input_data = serialize($delete_result);
            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmBusinessAccount has been deleted');

		    //Set success message and redirect to index page
			$this->Session->setFlash(__('Poslovni račun je obrisan'), 'flash_success');
		}else{
			$this->Session->setFlash($delete_result['error'], 'flash_error');
		}

		return $this->redirect(array('action' => 'index'));
	}//~!

	/**
	 * Method for getting exchange rate information by business account id and exchange date over ajax
	 *
	 * @return void
	 */
	public function getExchangeRate($fm_business_account_id=null, $exchange_date=null) {
		if ($this->request->is('ajax')) {
			$this->disableCache();

			//Init variables
			$result = array();
			$result['exchange_rate'] = "";
			$fm_business_account_id = (int)$fm_business_account_id;

			//Load id from request
			if(!empty($_REQUEST['fm_business_account_id']))	
				$fm_business_account_id = (int)$_REQUEST['fm_business_account_id'];

			//Load id from request
			if(!empty($_REQUEST['exchange_date']))		
				$exchange_date = $_REQUEST['exchange_date'];

			if(!empty($exchange_date)){
				//Get business account
				$business_account = $this->FmBusinessAccount->find('first', array('conditions' => array('FmBusinessAccount.id' => $fm_business_account_id), 'fields' => array('Currency.iso'), 'recursive' => 0));

				//Get currency 
				if(!empty($business_account)){
					$rate = $this->FmBusinessAccount->Currency->ExchangeRate->getIntermediateExchangeRateByDate($business_account['Currency']['iso'], $exchange_date);
					$result['currency_iso'] = $business_account['Currency']['iso'];
					$result['exchange_rate'] = round($rate, 4);
				}
			}

			$this->set('result', $result);
			$this->set('_serialize', 'result');
		}else{
			throw new NotFoundException(__('Stranica ne postoji!'));
		}
	}//~!	
}