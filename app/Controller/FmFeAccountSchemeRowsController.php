<?php
App::uses('AppController', 'Controller');
/**
 * FmFeAccountSchemeRows Controller
 *
 * @property FmFeAccountSchemeRow $FmFeAccountSchemeRow
 * @property PaginatorComponent $Paginator
 */
class FmFeAccountSchemeRowsController extends AppController {

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Session', 'Acl', 'Cookie', 'Ctrl', 'RequestHandler', 'ErpLogManagement','Paginator', 'Search', 'String');

	/**
	 * save method
	 * $fm_fe_account_scheme_id - FmFeAccountScheme.id, $id - FmFeAccountSchemeRow.id
	 * @return void
	 */
	public function save($fm_fe_account_scheme_id, $id=null) {
		//Set fullscreen layout
		$this->layout = 'fullscreen';

		//Set title
		$this->set('title_for_layout', 'Snimanje reda - Šeme knjiženja - Devizni izvodi banke - MikroERP');

		//Check for account scheme
		$account_scheme = $this->FmFeAccountSchemeRow->FmFeAccountScheme->find('first', array('conditions' => array('FmFeAccountScheme.id' => $fm_fe_account_scheme_id), 'recursive' => -1));
		if(empty($account_scheme)){
			//Set message and redirect to index
			$this->Session->setFlash(__('Šema knjiženja nije definisana!'), 'flash_success');
			return $this->redirect(array('controller' => 'FmFeAccountSchemes', 'action' => 'index'));
		}
		if(!empty($account_scheme['FmFeAccountScheme']['valid_to'])){
			//Set message and redirect to index
			$this->Session->setFlash(__('Šema knjiženja je istekla i nije moguća izmena redova!'), 'flash_success');
			return $this->redirect(array('controller' => 'FmFeAccountSchemes', 'action' => 'view', $fm_fe_account_scheme_id));
		}			
		if(!empty($account_scheme['FmFeAccountScheme']['valid_from'])){
			//Set message and redirect to index
			$this->Session->setFlash(__('Šema knjiženja je aktivna i nije moguća izmena redova!'), 'flash_success');
			return $this->redirect(array('controller' => 'FmFeAccountSchemes', 'action' => 'view', $fm_fe_account_scheme_id));
		}
		$this->set('account_scheme', $account_scheme);

		//If id defined check for row existance
		if(!empty($id)){
			$account_scheme_row = $this->FmFeAccountSchemeRow->find('first', array('conditions' => array('FmFeAccountSchemeRow.id' => $id), 'recursive' => 1));
			if(empty($account_scheme_row)){
				//Set message and redirect to index
				$this->Session->setFlash(__('Red šeme knjiženja nije definisan!'), 'flash_success');
				return $this->redirect(array('action' => 'index'));
			}
			$this->set('account_scheme_row', $account_scheme_row);
		}

		//Set conditions
		$this->set('conditions', $this->FmFeAccountSchemeRow->conditions);

		//Set account scheme record rows
		$this->set('document_fields', $this->FmFeAccountSchemeRow->FmFeAccountSchemeRecord->document_fields);
		$this->set('document_field_no', $this->FmFeAccountSchemeRow->FmFeAccountSchemeRecord->document_field_no);		
		$this->set('used_operations', $this->FmFeAccountSchemeRow->FmFeAccountSchemeRecord->used_operations);

		//Check if form is submitted
		if($this->request->is('post') || $this->request->is('put')){			
			//Check if account scheme row exists			
			if(!empty($id)){
				$this->request->data['FmFeAccountSchemeRow']['id'] = $id;
				$this->request->data['FmFeAccountSchemeRow']['ordinal'] = $account_scheme_row['FmFeAccountSchemeRow']['ordinal'];
			}

			//Set account link
			$this->request->data['FmFeAccountSchemeRow']['fm_fe_account_scheme_id'] = $fm_fe_account_scheme_id;

			//Save to DB
			$result = $this->FmFeAccountSchemeRow->saveRow($this->request->data);
			if($result['success']){
				//Get operator
				$user = $this->Session->read('Auth.User');

				//Save action log        		
				$input_data = serialize($this->request->data);
	            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeAccountSchemeRow has been saved');

	            //Set message and redirect to index
				$this->Session->setFlash(__('Red šeme knjiženja je snimljen.'), 'flash_success');
				return $this->redirect(array('controller' => 'FmFeAccountSchemes', 'action' => 'view', $fm_fe_account_scheme_id));
			}else{
				$this->set('error_rows', $result['error_rows']);
				$this->Session->setFlash('Red šeme knjiženja nije snimljen! Greška: '.$result['message'], 'flash_error');
			}
		}else{
			//Load existing data
			if(!empty($account_scheme_row)){
				//Load basic form
				$this->request->data['FmFeAccountSchemeRow'] = $account_scheme_row['FmFeAccountSchemeRow'];
				//Set scheme records
				foreach ($account_scheme_row['FmFeAccountSchemeRecord'] as $record) {
					$this->request->data['FmFeAccountSchemeRow'][$record['document_field'].'_operation_used'] = $record['operation_used'];
					if(!empty($record['codebook_connection_id'])){
						$codebook_connection = $this->FmFeAccountSchemeRow->FmFeAccountSchemeRecord->CodebookConnection->find('first', array(
							'conditions' => array('CodebookConnection.id' => $record['codebook_connection_id']),
							'recursive' => -1
						));
						if(!empty($codebook_connection)){
							$this->request->data['FmFeAccountSchemeRow'][$record['document_field'].'_connection_title'] = $codebook_connection['CodebookConnection']['code'].' - '.$codebook_connection['CodebookConnection']['name'];
						}

						$this->request->data['FmFeAccountSchemeRow'][$record['document_field'].'_connection'] = $record['codebook_connection_id'];	
						$this->request->data['FmFeAccountSchemeRow'][$record['document_field'].'_record_title'] = $record['record_title'];
					}
					if(!empty($record['fm_fe_document_link_id'])){
						$this->request->data['FmFeAccountSchemeRow'][$record['document_field'].'_connection'] = $record['fm_fe_document_link_id'];	
						$this->request->data['FmFeAccountSchemeRow'][$record['document_field'].'_connection_title'] = $record['record_title'];
					}
					$this->request->data['FmFeAccountSchemeRow'][$record['document_field'].'_record_value'] = $record['record_value'];					
					$this->request->data['FmFeAccountSchemeRow'][$record['document_field'].'_arithmetic_col_1'] = $record['arithmetic_first_col'];
					$this->request->data['FmFeAccountSchemeRow'][$record['document_field'].'_arithmetic_col_2'] = $record['arithmetic_second_col'];
					$this->request->data['FmFeAccountSchemeRow'][$record['document_field'].'_absolute_value'] = $record['absolute_value'];
					$this->request->data['FmFeAccountSchemeRow'][$record['document_field'].'_negative_value'] = $record['negative_value'];
				}
			}
		}
	}//~!

	/**
	 * delete method
	 * $id - FmFeAccountScheme.id
	 * @return void
	 */
	public function delete($id) {
		//Delete row and records from db
		$result = $this->FmFeAccountSchemeRow->deleteRow($id);
		if($result['success']){
			//Get operator
			$user = $this->Session->read('Auth.User');

			//Save action log        		
			$input_data = serialize($result);
            $this->ErpLogManagement->erplog($user['id'],  $this->params['controller'], $this->params['action'], $input_data, 'form', 'The FmFeAccountSchemeRow has been deleted');

		    //Set success message
			$this->Session->setFlash(__('Red '.$result['FmFeAccountSchemeRow']['ordinal'].'. je obrisan.'), 'flash_success');			
		}else{
	    	//Set error message
			$this->Session->setFlash(__('Red nije obrisan. Greška: '.$result['message']), 'flash_error');
		}

		//Redirect to account scheme view page
		return $this->redirect(array('controller' => 'FmFeAccountSchemes', 'action' => 'view', $result['FmFeAccountSchemeRow']['fm_fe_account_scheme_id']));
	}//~!
}