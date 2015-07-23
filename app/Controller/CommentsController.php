<?php
App::uses('ApiCommentsController', 'Controller');

class CommentsController extends ApiCommentsController {
    public $name = 'CommentsController';
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('get');
    }
    
    public function add() {
        if ($this->request->is('ajax')) {
            //Get data
            $shareId = $this->request->data['shareId'];
            $message = $this->request->data['message'];

            //Get user external identifier
            $userExternalId = $this->Auth->user('external_id');

            try {
                //Intern add
                $response = $this->internAdd($userExternalId, $shareId, $message);
                
                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_CREATED, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, "Method not allowed");
        }
    }
    
    public function get() { 
        if ($this->request->is('ajax')) {
            //Get data
            $shareId = $this->request->data['shareId'];
            $page = $this->request->data['page'];

            try {
                //Intern add
                $response = $this->internGet($shareId, $page);
                
                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, "Method not allowed");
        }
    }
}
