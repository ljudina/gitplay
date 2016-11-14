<?php
class FmFeDocumentLink extends AppModel{
	var $name = 'FmFeDocumentLink';

    public $validate = array(
        'document_name' => array(
            'documentNameRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Naziv dokumenta za povezivanje nije definisan',
                'required' => true
            )
        ),
        'field_name' => array(
            'fieldNameRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Polje dokumenta za povezivanje nije definisano',
                'required' => true
            )
        ),
        'model_name' => array(
            'modelNameRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Naziv modela dokumenta za povezivanje nije definisano',
                'required' => true
            ),            
            'modelNameRule2' => array(
                'rule' => array('modelExistsValidation'),
                'message' => 'Naziv modela dokumenta nije validan',
                'required' => true
            )
        ),
        'model_field' => array(
            'modelFieldRule1' => array(
                'rule' => 'notEmpty',
                'message' => 'Polje modela dokumenta za povezivanje nije definisano',
                'required' => true
            ),            
            'modelFieldRule2' => array(
                'rule' => array('modelFieldExistsValidation'),
                'message' => 'Polje modela dokumenta nije validno',
                'required' => true
            )
        )
	);

    /**
     * Check if model name is defined in framework
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function modelExistsValidation($check){
        if(!empty($this->data['FmFeDocumentLink']['model_name'])){
            $model_list = array_flip(App::objects('model'));
            return array_key_exists($this->data['FmFeDocumentLink']['model_name'], $model_list);
        }
        return false;
    }//~!

    /**
     * Check if model field exists in db
     *
     * @throws nothing
     * @param $check - form input value
     * @return boolean
     */
    public function modelFieldExistsValidation($check){
        if(!empty($this->data['FmFeDocumentLink']['model_name'])){
            $this->Model = ClassRegistry::init($this->data['FmFeDocumentLink']['model_name']);
            $schema = $this->Model->schema();
            return array_key_exists($this->data['FmFeDocumentLink']['model_field'], $schema);    
        }
        return false;
    }//~!

    /**
     * Check if provided document link exists
     *
     * @throws nothing
     * @param $fm_fe_document_link_id - FmFeDocumentLink.id, $data_value - model field value
     * @return boolean
     */
    public function checkDocumentLinkExists($fm_fe_document_link_id, $data_value){
        //Get document link
        $document_link = $this->find('first', array(
                'conditions' => array('FmFeDocumentLink.id' => $fm_fe_document_link_id),
                'recursive' => -1
            )
        );

        //Check for document link
        if(!empty($document_link)){
            //Check if model function exists
            $Model = ClassRegistry::init($document_link['FmFeDocumentLink']['model_name']);
            $model_count = $Model->find('count', array('conditions' => array($document_link['FmFeDocumentLink']['model_name'].'.'.$document_link['FmFeDocumentLink']['model_field'] => $data_value), 'recursive' => -1));
            return !empty($model_count);
        }

        return false;
    }//~!    
}
?>