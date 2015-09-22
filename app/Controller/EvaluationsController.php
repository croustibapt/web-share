<?php
App::uses('ApiEvaluationsController', 'Controller');

class EvaluationsController extends ApiEvaluationsController {
    public $name = 'EvaluationsController';
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('add');
    }
    
    public function add() {
        if ($this->request->is('put', 'ajax')) {
            //Get user external identifier
            $userExternalIdFrom = $this->Auth->user('external_id');

            //Get data
            $data = $this->request->input('json_decode', true);

            $requestId = $data['request_id'];
            $userExternalIdTo = $data['user_external_id'];
            $rating = $data['rating'];
            $message = $data['message'];

            try {
                //Intern add
                $response = $this->internAdd($userExternalIdFrom, $requestId, $userExternalIdTo, $rating, $message);
                
                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_CREATED, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }
}
