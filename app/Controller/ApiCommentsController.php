<?php
App::uses('AppController', 'Controller');
App::uses('ShareException', 'Lib');

class ApiCommentsController extends AppController {
    public $name = 'ApiComments';
    
	public $uses = array('Comment', 'Share', 'User');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('get', 'add');
    }
        
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
            throw new ShareException(SHARE_STATUS_CODE_BAD_REQUEST, SHARE_ERROR_CODE_BAD_PARAMETERS, "Bad parameters");
        }
        
        return $response;
    }
    
    public function add() {        
        if ($this->request->is('put', 'mobile', 'json')) {
            //Check credentials
            if ($this->checkCredentials($this->request)) {
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
            } else {
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS);
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }

    protected function internGet($shareId = NULL, $page = 1, $limit = SHARE_COMMENTS_LIMIT) {
        $response = NULL;

        $share = NULL;
        if ($shareId != NULL) {
            $share = $this->Share->find('first', array(
                'conditions' => array(
                    'Share.id' => $shareId
                )
            ));
        }

        //If correct identifier
        if ($share != NULL) {
            //Current page
            $response['page'] = $page;

            //Get start parameter
            $offset = ($page - 1) * $limit;

            //Comments
            $comments = $this->Comment->find('all', array(
                'limit' => $limit,
                'offset' => $offset,
                'conditions' => array(
                    'Comment.share_id' => $shareId
                ),
                'order' => 'Comment.id DESC'
            ));
            $response['results'] = array();

            //Format comments response
            $this->formatComments($response['results'], $comments);

            //Total results count
            $totalComments = $this->Comment->find('count', array(
                'conditions' => array(
                    'Comment.share_id' => $shareId
                )
            ));
            $response['total_results'] = $totalComments;

            //Total pages
            $totalPages = ceil($totalComments / $limit);
            $response['total_pages'] = $totalPages;

            //Return limit
            $response['limit'] = $limit;
        } else {
            throw new ShareException(SHARE_STATUS_CODE_BAD_REQUEST, SHARE_ERROR_CODE_BAD_PARAMETERS, "Bad Share identifier");
        }

        return $response;
    }
    
    public function get() { 
        if ($this->request->is('get', 'mobile', 'json')) {
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

            //Get limit parameter
            $limit = SHARE_COMMENTS_LIMIT;
            if (isset($this->params['url']['limit']) && is_numeric($this->params['url']['limit'])) {
                $limit = $this->params['url']['limit'];
            }
            
            try {
                //Intern get
                $response = $this->internGet($shareId, $page, $limit);

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        }
    }
}
