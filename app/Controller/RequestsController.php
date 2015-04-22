<?php
App::uses('ApiRequestsController', 'Controller');

class RequestsController extends ApiRequestsController {
    public $name = 'Requests';
                    
    public function add() {
        
    }
    
    public function accept($requestId = NULL) {
        if ($this->request->is('GET')) {
            //Get user identifier
            $userExternalId = $this->getUserExternalId($this->request);
            $userId = $this->getUserId($userExternalId);
            
            try {
                //Intern accept                
                if ($this->internAccept($requestId, $userId)) {
                    
                }
            } catch (ShareException $e) {
                //TODO: flash error
            }
            
            $this->redirect($this->referer());
        }
    }
    
    public function decline($requestId = NULL) {
        if ($this->request->is('GET')) {
            //Get user identifier
            $userExternalId = $this->getUserExternalId($this->request);
            $userId = $this->getUserId($userExternalId);
            
            try {
                //Intern accept                
                if ($this->internDecline($requestId, $userId)) {
                    
                }
            } catch (ShareException $e) {
                //TODO: flash error
            }
            
            $this->redirect($this->referer());
        }
    }
    
    public function cancel($requestId = NULL) {
        if ($this->request->is('GET')) {
            //Get user identifier
            $userExternalId = $this->getUserExternalId($this->request);
            $userId = $this->getUserId($userExternalId);
            
            try {
                //Intern accept                
                if ($this->internCancel($requestId, $userId)) {
                    
                }
            } catch (ShareException $e) {
                //TODO: flash error
            }
            
            $this->redirect($this->referer());
        }
    }
}
