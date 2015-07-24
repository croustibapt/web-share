<?php
App::uses('ApiRequestsController', 'Controller');

class RequestsController extends ApiRequestsController {
    public $name = 'Requests';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('add', 'accept', 'decline', 'cancel');
    }
                    
    public function add() {
        if ($this->request->is('get', 'ajax')) {
            //Get user identifier
            $userExternalId = $this->Auth->user('external_id');

            //Share id
            $shareId = $this->params['url']['share_id'];

            try {
                //Intern accept
                $response = $this->internAdd($userExternalId, $shareId);
                
                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_CREATED, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }
    
    public function accept($requestId = NULL) {
        if ($this->request->is('get', 'ajax')) {
            //Get user identifier
            $userExternalId = $this->Auth->user('external_id');

            try {
                //Intern accept                
                $this->internAccept($requestId, $userExternalId);

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_OK);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }
    
    public function decline($requestId = NULL) {
        if ($this->request->is('get', 'ajax')) {
            //Get user identifier
            $userExternalId = $this->Auth->user('external_id');

            try {
                //Intern accept                
                $this->internDecline($requestId, $userExternalId);

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_OK);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }
    
    public function cancel($requestId = NULL) {
        if ($this->request->is('get', 'ajax')) {
            //Get user identifier
            $userExternalId = $this->Auth->user('external_id');

            try {
                //Intern accept                
                $this->internCancel($requestId, $userExternalId);

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_OK);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }
}
