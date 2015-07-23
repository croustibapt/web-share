<?php
App::uses('ApiRequestsController', 'Controller');

class RequestsController extends ApiRequestsController {
    public $name = 'Requests';
                    
    public function add() {
        if ($this->request->is('ajax')) {
            //Get user identifier
            $userId = $this->Auth->user('id');

            //Share id
            $shareId = $this->request->data['shareId'];

            try {
                //Intern accept
                $response = $this->internAdd($userId, $shareId);
                
                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_CREATED, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, "Method not allowed");
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
