<?php
App::uses('ApiShareTypesController', 'Controller');

class ShareTypesController extends ApiShareTypesController {
    public $name = 'ShareTypes';
        
	public function add() {
        if ($this->request->is('post')) {
            $dataShareType = $this->request->data;
            
            if ($this->ShareType->save($dataShareType)) {
                //Redirect to index
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash("Unable to add this Share type.", 'default', array(), 'nok');
            }
        }
        
        //Get share type categories
		$shareTypeCategories = $this->ShareTypeCategory->find('list');
        $this->set('shareTypeCategories', $shareTypeCategories);
	}
    
    public function delete() {
        if ($this->request->is('post')) {
            $shareTypeId = $this->request->data['ShareType']['id'];
            
            if (($shareTypeId != NULL) && $this->ShareType->delete($shareTypeId, true)) {
                //Redirect to index
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash('Unable to delete this Share type.', 'default', array(), 'nok');
                $this->redirect($this->referer());
            }
        }
    }
    
    public function get() {
        if ($this->request->is('GET')) {
            
        }
    }
}
