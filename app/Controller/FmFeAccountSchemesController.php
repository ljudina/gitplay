<?php
App::uses('AppController', 'Controller');
/**
 * FmFeAccountSchemes Controller
 *
 * @property FmFeAccountScheme $FmFeAccountScheme
 * @property PaginatorComponent $Paginator
 */
class FmFeAccountSchemesController extends AppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Session', 'Acl', 'Cookie', 'Ctrl', 'RequestHandler', 'ErpLogManagement','Paginator', 'Search', 'String');

	/**
	 * index method
	 * @param none
	 * @return void
	 */
	public function index() {
		$this->set('title_for_layout', 'Šeme knjiženja - Devizni izvodi banke - MikroERP');

        //Check for query
        $conditions = array('FmFeAccountScheme.valid_to' => null);
        if(!empty($this->request->query)){
            $this->request->data['FmFeAccountScheme'] = $this->request->query;

            //Search for keywords
            if(!empty($this->request->query['keywords'])){
                $conditions['OR'][] = array('FmFeAccountScheme.code LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('FmFeAccountScheme.scheme_desc LIKE' => '%'.$this->request->query['keywords'].'%');
            }

            //Check for valid from period
            if(!empty($this->request->query['valid_from'])){
                $conditions[] = array('FmFeAccountScheme.valid_from >=' => $this->request->query['valid_from']);
            }            
            //Check for valid to period
            if(!empty($this->request->query['valid_to'])){
                $conditions[] = array('FmFeAccountScheme.valid_to <=' => $this->request->query['valid_to']);
            }
            //Show all schemes
            if(!empty($this->request->query['show_all'])){
                unset($conditions['FmFeAccountScheme.valid_to']);
            }
        }

        //Set data
        $settings = array();
        $settings['conditions'] = $conditions;
        $settings['order'] = array('FmFeAccountScheme.created' => 'DESC');
        $settings['recursive'] = 0;

        $this->FmFeAccountScheme->UserStart->virtualFields = array();
        $this->FmFeAccountScheme->UserEnd->virtualFields = array();

        $this->Paginator->settings = $settings;
		$account_schemes = $this->Paginator->paginate('FmFeAccountScheme');	

		$this->set('account_schemes', $account_schemes);
	}//~!

	/**
	 * save method
	 * $id - FmFeAccountScheme.id
	 * @return void
	 */
	public function save($id=null) {
		//Set title
		$this->set('title_for_layout', 'Snimanje šeme knjiženja - Devizni izvodi banke - MikroERP');

		//If id defined check for existance
		if(!empty($id)){
			$account_scheme = $this->FmFeAccountScheme->find('first', array('conditions' => array('FmFeAccountScheme.id' => $id), 'recursive' => -1));
			if(empty($account_scheme)){
				//Set message and redirect to index
				$this->Session->setFlash(__('Šema knjiženja nije definisana!'), 'flash_success');
				return $this->redirect(array('action' => 'index'));
			}
			if(!empty($account_scheme['FmFeAccountScheme']['valid_to'])){
				//Set message and redirect to index
				$this->Session->setFlash(__('Šema knjiženja je istekla i nije moguća izmena!'), 'flash_success');
				return $this->redirect(array('action' => 'index'));
			}			
			if(!empty($account_scheme['FmFeAccountScheme']['valid_from'])){
				//Set message and redirect to index
				$this->Session->setFlash(__('Šema knjiženja je aktivna i nije moguća izmena!'), 'flash_success');
				return $this->redirect(array('action' => 'index'));
			}
		}

		//Check if form is submitted
		if($this->request->is('post') || $this->request->is('put')){
			//Check if account scheme exists
			if(!empty($id)){
				$this->request->data['FmFeAccountScheme']['id'] = $id;
				$this->request->data['FmFeAccountScheme']['valid_from'] = $account_scheme['FmFeAccountScheme']['valid_from'];
				$this->request->data['FmFeAccountScheme']['valid_to'] = $account_scheme['FmFeAccountScheme']['valid_to'];
				$this->request->data['FmFeAccountScheme']['user_id_start'] = $account_scheme['FmFeAccountScheme']['user_id_start'];
				$this->request->data['FmFeAccountScheme']['user_id_end'] = $account_scheme['FmFeAccountScheme']['user_id_end'];
			}else{
				$this->request->data['FmFeAccountScheme']['valid_from'] = null;
				$this->request->data['FmFeAccountScheme']['valid_to'] = null;
				$this->request->data['FmFeAccountScheme']['user_id_start'] = null;
				$this->request->data['FmFeAccountScheme']['user_id_end'] = null;
				$this->FmFeAccountScheme->create();
			}
			//Save to DB
			if($this->FmFeAccountScheme->save($this->request->data)){
				//Get operator
				$user = $this->Session->read('Auth.User');

				//Save action log        		
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeAccountScheme have been saved');

	            //Set message and redirect to index
				$this->Session->setFlash(__('Šema knjiženja je snimljena.'), 'flash_success');
				return $this->redirect(array('action' => 'index'));
			}else{
                $errors = $this->FmFeAccountScheme->validationErrors;
				$this->Session->setFlash('Šema knjiženja nije snimljena! Greška: '.array_shift($errors)[0], 'flash_error');
			}
		}else{
			//Load existing data
			if(!empty($account_scheme)){
				$this->request->data['FmFeAccountScheme'] = $account_scheme['FmFeAccountScheme'];
			}
		}
	}//~!

	/**
	 * view method
	 * $id - FmFeAccountScheme.id
	 * @return void
	 */
	public function view($id) {		
		//Get account scheme
		$this->FmFeAccountScheme->UserStart->virtualFields = array();
		$this->FmFeAccountScheme->UserEnd->virtualFields = array();
		$account_scheme = $this->FmFeAccountScheme->find('first', array('conditions' => array('FmFeAccountScheme.id' => $id), 'recursive' => 0));
		if(empty($account_scheme)){
			//Set message and redirect to index
			$this->Session->setFlash(__('Šema knjiženja nije definisana!'), 'flash_success');
			return $this->redirect(array('action' => 'index'));
		}
		//Set account scheme
		$this->set('account_scheme', $account_scheme);

		//Get and set account scheme
		$account_scheme_rows = $this->FmFeAccountScheme->FmFeAccountSchemeRow->find('all', array('conditions' => array('FmFeAccountSchemeRow.fm_fe_account_scheme_id' => $id), 'recursive' => 1));

		$this->set('account_scheme_rows', $account_scheme_rows);
		$this->set('conditions', $this->FmFeAccountScheme->FmFeAccountSchemeRow->conditions);

		//Set title
		$this->set('title_for_layout', 'Pregled šeme br. '.$account_scheme['FmFeAccountScheme']['code'].' - Devizni izvodi banke - MikroERP');

		//Set account scheme record rows
		$this->set('document_fields', $this->FmFeAccountScheme->FmFeAccountSchemeRow->FmFeAccountSchemeRecord->document_fields);
		$this->set('document_field_no', $this->FmFeAccountScheme->FmFeAccountSchemeRow->FmFeAccountSchemeRecord->document_field_no);
		$this->set('used_operations', $this->FmFeAccountScheme->FmFeAccountSchemeRow->FmFeAccountSchemeRecord->used_operations);
	}//~!

	/**
	 * delete method
	 * $id - FmFeAccountScheme.id
	 * @return void
	 */
	public function delete($id) {
		//Delete scheme with all rows and records from db
		$result = $this->FmFeAccountScheme->deleteScheme($id);
		if($result['success']){
			//Get operator
			$user = $this->Session->read('Auth.User');

			//Save action log        		
			$input_data = serialize($result);
            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeAccountScheme has been deleted');

		    //Set success message
			$this->Session->setFlash(__('Šema knjiženja '.$result['FmFeAccountScheme']['code'].'. je obrisana.'), 'flash_success');
		}else{
	    	//Set error message
			$this->Session->setFlash(__('Šema knjiženja nije obrisana. Greška: '.$result['message']), 'flash_error');
		}

		//Redirect to account scheme index page
		return $this->redirect(array('controller' => 'FmFeAccountSchemes', 'action' => 'index'));
	}//~!

	/**
	 * Method for starting account scheme period validity
	 * @param $id - FmFeAccountScheme.id
	 * @return void
	 */
	public function start($id) {
		//Start account scheme period validity
		$result = $this->FmFeAccountScheme->startPeriod($id);
		if($result['success']){
			//Get operator
			$user = $this->Session->read('Auth.User');

			//Save action log        		
			$input_data = serialize($this->request->data);
            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeAccountScheme period has started');

            //Set success message
			$this->Session->setFlash(__('Period važenja šeme knjiženja je počeo.'), 'flash_success');

			//Redirect to account scheme view page
			return $this->redirect(array('controller' => 'FmFeAccountSchemes', 'action' => 'view', $id));			
		}else{
			//Set error message
			$this->Session->setFlash('Period važenja šeme knjiženja nije počeo! '.$result['message'], 'flash_error');

			//Redirect to account scheme index page
			return $this->redirect(array('controller' => 'FmFeAccountSchemes', 'action' => 'index'));
		}
	}//~!

	/**
	 * Method for ending account scheme period validity
	 * @param $id - FmFeAccountScheme.id
	 * @return void
	 */
	public function end($id) {
		//End account scheme period validity
		$result = $this->FmFeAccountScheme->endPeriod($id);
		if($result['success']){
			//Get operator
			$user = $this->Session->read('Auth.User');

			//Save action log        		
			$input_data = serialize($this->request->data);
            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeAccountScheme period has ended');

            //Set message and reset form
			$this->Session->setFlash(__('Period važenja šeme knjiženja je završen.'), 'flash_success');
		}else{
			$this->Session->setFlash('Period važenja šeme knjiženja nije završen! Greška: '.$result['message'], 'flash_error');
		}
		//Redirect to account scheme view page
		return $this->redirect(array('controller' => 'FmFeAccountSchemes', 'action' => 'view', $id));		
	}//~!	
}