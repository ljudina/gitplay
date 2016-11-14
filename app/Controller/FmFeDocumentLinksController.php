<?php
App::uses('AppController', 'Controller');
/**
 * FmFeDocumentLinks Controller
 *
 * @property FmFeDocumentLink $FmFeDocumentLink
 * @property PaginatorComponent $Paginator
 */
class FmFeDocumentLinksController extends AppController {

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
		$this->set('title_for_layout', 'Povezivanje dokumenata - Šeme knjiženja - Devizni izvodi banke - MikroERP');

        //Check for query
        $conditions = array('FmFeDocumentLink.active' => 1);

        //Check for form submission
        if(!empty($this->request->query)){
            $this->request->data['FmFeDocumentLink'] = $this->request->query;

            //Search for keywords
            if(!empty($this->request->query['keywords'])){
                $conditions['OR'][] = array('FmFeDocumentLink.document_name LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('FmFeDocumentLink.field_name LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('FmFeDocumentLink.model_name LIKE' => '%'.$this->request->query['keywords'].'%');
                $conditions['OR'][] = array('FmFeDocumentLink.model_field LIKE' => '%'.$this->request->query['keywords'].'%');
            }

            //Show all links
            if(!empty($this->request->query['show_all'])){
            	unset($conditions['FmFeDocumentLink.active']);
            }            
        }

        //Set data
        $settings = array();
        $settings['conditions'] = $conditions;
        $settings['order'] = array('FmFeDocumentLink.created' => 'DESC');
        $settings['recursive'] = -1;
        $this->Paginator->settings = $settings;		

		$document_links = $this->Paginator->paginate('FmFeDocumentLink');	
		$this->set('document_links', $document_links);
	}//~!

	/**
	 * save method
	 * @param $id - FmFeDocumentLink.id
	 * @return void
	 */
	public function save($id=null) {
		//Set title
		$this->set('title_for_layout', 'Snimanje - Povezivanje dokumenata - Šeme knjiženja - Devizni izvodi banke - MikroERP');

		//Check for document table
		if(!empty($id)){
			//Check if exists
			$document_link = $this->FmFeDocumentLink->find('first', array('conditions' => array('FmFeDocumentLink.id' => $id), 'recursive' => -1));
			if(empty($document_link)){
				$this->Session->setFlash("Dokument za povezivanje nije validan", 'flash_error');
				return $this->redirect(array('action' => 'index'));
			}	
		}

		//Check for form submission
		if ($this->request->is('post') || $this->request->is('put')) {
			//Check if document link is new
			if(!empty($id)){
				$this->request->data['FmFeDocumentLink']['id'] = $id;
			}else{
				$this->FmFeDocumentLink->create();
			}
			//Save to DB
			if($this->FmFeDocumentLink->save($this->request->data)){
				//Get operator
				$user = $this->Session->read('Auth.User');

				//Save action log        		
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeDocumentLink has been saved');

	            //Set message and redirect to index page
				$this->Session->setFlash(__('Dokument za povezivanje je snimljen.'), 'flash_success');
        		return $this->redirect(array('action' => 'index'));
			}else{
                $errors = $this->FmFeDocumentLink->validationErrors;
				$this->Session->setFlash('Dokument za povezivanje nije snimljen! Greška: '.array_shift($errors)[0], 'flash_error');
			}
		}else{
			if(!empty($document_link)){
				$this->request->data['FmFeDocumentLink'] = $document_link['FmFeDocumentLink'];
			}
		}
	}//~!

	/**
	 * Method for searching document links over ajax
	 *
	 * @return void
	 */
	public function searchDocumentLinks() {
		if ($this->request->is('ajax')) {
			$this->disableCache();

			//Init variables
			$result = array();

			//Load terms
			if(!empty($_REQUEST['term']))
				$term = $_REQUEST['term'];
			
	        //Init conditions
	        $conditions = array('FmFeDocumentLink.active' => 1);

            //Search for keywords
            if(!empty($term)){
                $conditions['OR'][] = array('FmFeDocumentLink.document_name LIKE' => '%'.$term.'%');
                $conditions['OR'][] = array('FmFeDocumentLink.field_name LIKE' => '%'.$term.'%');
                $conditions['OR'][] = array('FmFeDocumentLink.model_name LIKE' => '%'.$term.'%');
                $conditions['OR'][] = array('FmFeDocumentLink.model_field LIKE' => '%'.$term.'%');
            }

            //Get results
            $this->FmFeDocumentLink->virtualFields = array('title' => "CONCAT(FmFeDocumentLink.document_name, ' - ', FmFeDocumentLink.field_name)");
            $result = $this->FmFeDocumentLink->find('list', array('conditions' => $conditions, 'fields' => array('FmFeDocumentLink.id', 'FmFeDocumentLink.title'), 'recursive' => -1));
            $this->FmFeDocumentLink->virtualFields = array();

			$this->set('result', $result);
			$this->set('_serialize', 'result');
		}else{
			throw new NotFoundException(__('Stranica ne postoji!'));
		}
	}//~!
}