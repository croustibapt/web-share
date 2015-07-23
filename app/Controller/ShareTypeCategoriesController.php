<?php
App::uses('ApiShareTypeCategoriesController', 'Controller');

class ShareTypeCategoriesController extends ApiShareTypeCategoriesController {
    public $name = 'ShareTypeCategories';
        
	public function add() {
        if ($this->request->is('post')) {
            $dataShareTypeCategory = $this->request->data;
            
            if ($this->ShareTypeCategory->save($dataShareTypeCategory)) {
                //Redirect to index
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash("Unable to add this Share type category.", 'default', array(), 'nok');
            }
        }
	}
    
    public function delete() {
        if ($this->request->is('post')) {
            $shareTypeCategoryId = $this->request->data['ShareTypeCategory']['id'];
            
            if (($shareTypeCategoryId != NULL) && $this->ShareTypeCategory->delete($shareTypeCategoryId, true)) {
                //Redirect to index
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash('Unable to delete this Share type category.', 'default', array(), 'nok');
                $this->redirect($this->referer());
            }
        }
    }
    
    public function get() {
        if ($this->request->is('GET')) {
            
        }
    }
}
