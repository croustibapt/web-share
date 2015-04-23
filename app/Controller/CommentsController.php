<?php
App::uses('ApiCommentsController', 'Controller');

class CommentsController extends ApiCommentsController {
    public $name = 'CommentsController';
    
    public function add() {
        if ($this->request->is('POST')) {
            //Decode data
            $data = $this->request->data;
            $shareId = $data['Comment']['shareId'];
            $message = $data['Comment']['message'];

            //Get user external identifier
            $userExternalId = $this->getUserExternalId($this->request);

            try {
                //Intern add
                $response = $this->internAdd($userExternalId, $shareId,$message);
            } catch (ShareException $e) {
                //$this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        }

        $this->redirect($this->referer());
    }
    
    public function get() { 
        
    }
}
