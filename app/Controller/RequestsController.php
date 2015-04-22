<?php
App::uses('ApiRequestsController', 'Controller');

class RequestsController extends ApiRequestsController {
    public $name = 'Requests';
                    
    public function add() {
        if ($this->request->is('POST')) {
            //Get user identifier
            $userExternalId = $this->getUserExternalId($this->request);
            $userId = $this->getUserId($userExternalId);

            //Share id
            $shareId = NULL;
            if (isset($this->request->data['Request']['shareId'])) {
                $shareId = urldecode($this->request->data['Request']['shareId']);
            }

            try {
                //Intern accept
                $response = $this->internAdd($userId, $shareId);
            } catch (ShareException $e) {
                //TODO: flash error
            }

            $this->redirect($this->referer());
        }
    }
    
    public function accept($requestId = NULL) {
        if ($this->request->is('GET')) {
            //Get user identifier
            $userExternalId = $this->getUserExternalId($this->request);

            try {
                //Intern accept                
                if ($this->internAccept($requestId, $userExternalId)) {
                    
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

            try {
                //Intern accept                
                if ($this->internDecline($requestId, $userExternalId)) {
                    
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

            try {
                //Intern accept                
                if ($this->internCancel($requestId, $userExternalId)) {
                    
                }
            } catch (ShareException $e) {
                //TODO: flash error
            }
            
            $this->redirect($this->referer());
        }
    }
}
