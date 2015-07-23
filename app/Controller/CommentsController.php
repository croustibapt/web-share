<?php
App::uses('ApiCommentsController', 'Controller');

class CommentsController extends ApiCommentsController {
    public $name = 'CommentsController';
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('add');
    }
    
    public function add() {
        if ($this->request->is('put', 'ajax')) {
            //Get user external identifier
            $userExternalId = $this->Auth->user('external_id');

            //Get data
            $data = $this->request->input('json_decode', true);
            $shareId = $data['share_id'];
            $message = $data['message'];

            $this->sendResponse(SHARE_STATUS_CODE_OK, $userExternalId);

            try {
                //Intern add
                $response = $this->internAdd($userExternalId, $shareId, $message);
                
                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_CREATED, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }
    
    public function get() { 
        if ($this->request->is('get', 'ajax')) {
            //Get data
            $shareId = $this->params['url']['share_id'];
            $page = $this->params['url']['page'];

            try {
                //Intern add
                $response = $this->internGet($shareId, $page);

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }
}
