<?php
App::uses('AppController', 'Controller');
/**
 * FmFeTransactionTypes Controller
 *
 * @property FmFeTransactionType $FmFeTransactionType
 * @property PaginatorComponent $Paginator
 */
class FmFeTransactionTypesController extends AppController {

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
		$this->set('title_for_layout', 'Šifarnik deviznih transakcija - MikroERP');

        //Check for query
        $conditions = array();
        $conditions['NOT'] = array('FmFeTransactionType.deleted' => 1);
        if(!empty($this->request->query)){
            $this->request->data['FmFeTransactionType'] = $this->request->query;
            
            //Search for description
            if(!empty($this->request->query['desc_data'])){
                $conditions[] = array('FmFeTransactionType.desc_data LIKE' => '%'.$this->request->query['desc_data'].'%');
            }
            //Check for payer/recipient
            if(!empty($this->request->query['payer_recipient'])){
                $conditions[] = array('FmFeTransactionType.payer_recipient' => $this->request->query['payer_recipient']);
            }
        }

        //Set data
        $settings = array();
        $settings['conditions'] = $conditions;
        $settings['order'] = array('FmFeTransactionType.ordinal' => 'ASC');

        $this->Paginator->settings = $settings;

		$this->FmFeTransactionType->recursive = 0;
		$this->set('types', $this->Paginator->paginate('FmFeTransactionType'));

		//Set payer/recipients
		$payer_recipients = $this->FmFeTransactionType->payer_recipients;
		$this->set('payer_recipients', $payer_recipients);

		//Set transaction types
		$transaction_types = $this->FmFeTransactionType->transaction_types;
		$this->set('transaction_types', $transaction_types);
	}//~!

	/**
	 * save method
	 *
	 * @return void
	 */
	public function save($id=null) {
		$this->set('title_for_layout', 'Snimanje - Šifarnik deviznih transakcija - MikroERP');

		if(!empty($id)){
			//Check if exists
			$type = $this->FmFeTransactionType->find('first', array('conditions' => array('FmFeTransactionType.id' => $id), 'recursive' => -1));
			if(empty($type)){
				$this->Session->setFlash("Devizna transakcija nije validna", 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}
			//Check if deleted
			if(!empty($account['FmFeTransactionType']['deleted'])){
				$this->Session->setFlash("Devizna transakcija je obrisana i nije moguća izmena", 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			//Check if business account is new
			if(!empty($id)){
				$this->request->data['FmFeTransactionType']['id'] = $id;
				$this->request->data['FmFeTransactionType']['ordinal'] = $type['FmFeTransactionType']['ordinal'];
			}else{
				$this->FmFeTransactionType->create();
			}

			//Save to DB
			if($this->FmFeTransactionType->save($this->request->data)){
				//Get operator
				$user = $this->Session->read('Auth.User');

				//Save action log        		
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeTransactionType has been saved');

	            //Set message and redirect to index page
				$this->Session->setFlash(__('Devizna transakcija je snimljena.'), 'flash_success');
        		return $this->redirect(array('action' => 'index'));
			}else{
                $errors = $this->FmFeTransactionType->validationErrors;
				$this->Session->setFlash('Devizna transakcija nije snimljena! Greška: '.array_shift($errors)[0], 'flash_error');
			}
		}else{
			//Set form data
			if(!empty($type)){
				//Set current data
				$this->request->data['FmFeTransactionType'] = $type['FmFeTransactionType'];

				//Process chart account links
				$fm_chart_account_links = explode(',', $this->request->data['FmFeTransactionType']['fm_chart_account_links']);
				$this->request->data['FmFeTransactionType']['fm_chart_account_links'] = $fm_chart_account_links;
			}else{
				$this->request->data['FmFeTransactionType']['payer_recipient'] = 'customer';
			}
		}

		//Set payer/recipients
		$payer_recipients = $this->FmFeTransactionType->payer_recipients;
		$this->set('payer_recipients', $payer_recipients);

		//Set transaction links
		$transaction_links = $this->FmFeTransactionType->transaction_links;
		$this->set('transaction_links', $transaction_links);

		//Set account schemes
		$this->set('account_schemes', $this->FmFeTransactionType->FmFeAccountScheme->getActiveSchemeList());

		//Set chart accounts
		$this->loadModel('FmChartAccount');
		$chart_accounts = $this->FmChartAccount->getChartAccountList(true, true);
		$this->set('chart_accounts', $chart_accounts);
	}//~!

	/**
	 * delete method
	 *
	 * @return void
	 */
	public function delete($id=null) {
		//Save to DB
		$delete_result = $this->FmFeTransactionType->deleteType($id);
		if(empty($delete_result['error'])){
			//Get operator
			$user = $this->Session->read('Auth.User');	
			
			//Save action log        		
			$input_data = serialize($delete_result);
            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeTransactionType has been deleted');

		    //Set success message and redirect to index page
			$this->Session->setFlash(__('Devizna transakcija je obrisana'), 'flash_success');
		}else{
			$this->Session->setFlash($delete_result['error'], 'flash_error');
		}

		return $this->redirect(array('action' => 'index'));
	}//~!
}