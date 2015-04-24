<?php
App::uses('AppController', 'Controller');
App::uses('ShareException', 'Lib');

class ApiCommentsController extends AppController {
    public $name = 'ApiComments';
    
	public $uses = array('Comment', 'Share', 'User');
        
    protected function internAdd($userExternalId = NULL, $shareId = NULL, $message = NULL) {
        $response = NULL;

        $user = NULL;
        if ($userExternalId != NULL) {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.external_id' => $userExternalId
                )
            ));
        }

        $share = NULL;
        if ($shareId != NULL) {
            $share = $this->Share->find('first', array(
                'conditions' => array(
                    'Share.id' => $shareId
                )
            ));
        }

        if (($user != NULL) && ($share != NULL) && (strlen($message) > 0)) {
            //Check credentials
            if ($this->checkCredentials($this->request)) {
                $userId = $user['User']['id'];

                //Data comment
                $dataComment['Comment']['user_id'] = $userId;
                $dataComment['Comment']['share_id'] = $shareId;
                $dataComment['Comment']['message'] = urldecode($message);

                //Save it
                $comment = $this->Comment->save($dataComment);                

                //If it succeeded
                if ($comment != NULL) {
                    //Prepare response
                    $response['comment_id'] = $comment['Comment']['id'];
                    $response['user']['external_id'] = $user['User']['external_id'];
                    $response['user']['username'] = $user['User']['username'];

                    $this->formatISODate($response['created'], $comment['Comment']['created']);
                    $this->formatISODate($response['modified'], $comment['Comment']['modified']);
                } else {
                    $validationErrors = $this->Comment->validationErrors;

                    //Check validation errors
                    if ($validationErrors !== NULL) {
                        throw new ShareException(SHARE_STATUS_CODE_PRECONDITION_FAILED, SHARE_ERROR_CODE_VALIDATION_FAILED, "Comment validation failed", $validationErrors);
                    } else {
                        throw new ShareException(SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR, SHARE_ERROR_CODE_SAVE_FAILED, "Comment save failed");
                    }
                }
            } else {
                throw new ShareException(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS, "Bad credentials");
            }
        } else {
            throw new ShareException(SHARE_STATUS_CODE_BAD_REQUEST, SHARE_ERROR_CODE_BAD_PARAMETERS, "Bad parameters");
        }
        
        return $response;
    }
    
    public function apiAdd() {        
        if ($this->request->is('PUT')) {
            //Decode data
            $data = $this->request->input('json_decode', true);

            /*echo json_encode($data);
            exit();*/
            
            //Get user external identifier
            $userExternalId = $this->getUserExternalId($this->request);

            try {            
                //Intern add
                $response = $this->internAdd($userExternalId, $data['share_id'], $data['message']);

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_CREATED, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        }
    }
    
    public function apiGet() { 
        if ($this->request->is('GET')) {
            //Get Share identifier
            $shareId = NULL;
            if (isset($this->params['url']['shareId']) && is_numeric($this->params['url']['shareId'])) {
                $shareId = $this->params['url']['shareId'];
            }

            //Get page parameter
            $page = 1;
            if (isset($this->params['url']['page']) && is_numeric($this->params['url']['page'])) {
                $page = $this->params['url']['page'];
            }
            
            try {
                //Intern get
                $response = $this->internGetComments($shareId, $page);

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        }
    }
}
