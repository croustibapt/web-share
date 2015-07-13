<?php
App::uses('AppController', 'Controller');
App::uses('ShareException', 'Lib');

class ApiUsersController extends AppController {
	public $uses = array('User');

    protected function internAdd($userExternalId = NULL, $username = NULL, $mail = NULL) {
        $response = NULL;
        
        //Check parameters
        if (($userExternalId != NULL) && ($username != NULL) && ($mail != NULL)) {
            $dataUser['User']['external_id'] = $userExternalId;
            $dataUser['User']['username'] = $username;
            $dataUser['User']['mail'] = $mail;

            if ($this->User->save($dataUser)) {
                //Get it back
                $userId = $this->User->id;
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $userId
                    )
                ));

                //Create JSON response
                $response['external_id'] = $user['User']['external_id'];
                $response['username'] = $this->checkEmptyString($user['User']['username']);
                $response['mail'] = $this->checkEmptyString($user['User']['mail']);
                $response['description'] = $this->checkEmptyString($user['User']['description']);

                //Created, modified
                $this->formatISODate($response['created'], $user['User']['created']);
                $this->formatISODate($response['modified'], $user['User']['modified']);
            } else {
                $validationErrors = $this->User->validationErrors;

                //Check validation errors
                if ($validationErrors != NULL) {
                    throw new ShareException(SHARE_STATUS_CODE_PRECONDITION_FAILED, SHARE_ERROR_CODE_VALIDATION_FAILED, "User validation failed", $validationErrors);
                } else {
                    throw new ShareException(SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR, SHARE_ERROR_CODE_SAVE_FAILED, "User save failed");
                }
            }
        } else {
            throw new ShareException(SHARE_STATUS_CODE_BAD_REQUEST, SHARE_ERROR_CODE_BAD_PARAMETERS, "Bad parameters");
        }
        
        return $response;
    }

	public function apiAdd() {
        if ($this->request->is('PUT')) {
            $data = $this->request->input('json_decode', true);
            
            $userExternalId = $this->getUserExternalId($this->request);
            $username = $data['username'];
            $mail = $data['mail'];

            try {
                //Intern add
                $response = $this->internAdd($userExternalId, $username, $mail);

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_CREATED, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        }
	}
    
    protected function internDetails($externalId = NULL) {
        $response = NULL;
        
        //Check parameter
        if ($externalId != NULL) {
            //Find first user
            $user = $this->User->find('first', array(
                'fields' => array('User.username', 'User.external_id', 'User.description', 'User.created', 'User.share_count', 'User.request_count', 'User.comment_count'),
                'conditions' => array(
                    'User.external_id' => $externalId
                )
            ));

            //If found
            if ($user != NULL) {
                //Format data
                $response['username'] = $this->checkEmptyString($user['User']['username']);
                $response['external_id'] = $user['User']['external_id'];
                $response['description'] = $this->checkEmptyString($user['User']['description']);

                //Created
                $this->formatISODate($response['created'], $user['User']['created']);

                $response['share_count'] = $user['User']['share_count'];
                $response['request_count'] = $user['User']['request_count'];
                $response['comment_count'] = $user['User']['comment_count'];
            } else {
                throw new ShareException(SHARE_STATUS_CODE_NOT_FOUND, SHARE_ERROR_CODE_RESOURCE_NOT_FOUND, "User not found");
            }
        } else {
            throw new ShareException(SHARE_STATUS_CODE_BAD_REQUEST, SHARE_ERROR_CODE_BAD_PARAMETERS, "Bad User external identifier");
        }
        
        return $response;
    }
    
    public function apiDetails($externalId = NULL) {
        try {
            //Intern details
            $response = $this->internDetails($externalId);

            //Send JSON respsonse
            $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
        } catch (ShareException $e) {
            $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
        }
    }

    protected function interHome() {
        $response = NULL;

        //Check credentials
        if ($this->checkCredentials($this->request)) {
            //Get user external identifier
            $userExternalId = $this->getUserExternalId($this->request);

            //Find its entity
            $user = $this->User->find('first', array(
                'fields' => array('User.username', 'User.external_id', 'User.created', 'User.share_count', 'User.request_count', 'User.comment_count'),
                'conditions' => array(
                    'User.external_id' => $userExternalId
                )
            ));

            //User
            $response['username'] = $user['User']['username'];
            $response['external_id'] = $user['User']['external_id'];

            //Created
            $this->formatISODate($response['created'], $user['User']['created']);

            $response['comment_count'] = $user['User']['comment_count'];

            //Shares
            $shareCount = $user['User']['share_count'];
            $response['share_count'] = $shareCount;

            if ($shareCount > 0) {
                $sql = "SELECT *, X(Share.location) as latitude, Y(Share.location) as longitude, (SELECT COUNT(Request.id) FROM requests Request WHERE Request.share_id = Share.id AND Request.status = 1) AS participation_count FROM shares Share, users User, share_types ShareType, share_type_categories ShareTypeCategory WHERE Share.user_id = User.id AND Share.share_type_id = ShareType.id AND ShareType.share_type_category_id = ShareTypeCategory.id AND User.external_id = ".$userExternalId." AND Share.event_date >= '".date('Y-m-d')."';";
                $shares = $this->Share->query($sql);

                //pr($shares);

                $response['shares'] = array();

                //Format Shares
                $shareIndex = 0;
                foreach ($shares as $share) {
                    $response['shares'][$shareIndex++] = $this->formatShare($share, false, true);
                }
            }

            //Requests
            $requestCount = $user['User']['request_count'];
            $response['request_count'] = $requestCount;

            if ($requestCount > 0) {
                $requests = $this->Request->find('all', array(
                    'conditions' => array(
                        'User.id' => $user['User']['id'],
                        'Share.event_date >= ' => date('Y-m-d')
                    )
                ));

                foreach ($requests as & $request) {
                    $sql = "SELECT *, X(Share.location) as latitude, Y(Share.location) as longitude, (SELECT COUNT(Request.id) FROM requests Request WHERE Request.share_id = Share.id AND Request.status = 1) AS participation_count FROM shares AS Share, users AS User, share_types AS ShareType, share_type_categories ShareTypeCategory WHERE Share.user_id = User.id AND Share.share_type_id = ShareType.id AND ShareType.share_type_category_id = ShareTypeCategory.id AND Share.id = ".$request['Request']['share_id']." LIMIT 1;";

                    $shares = $this->Share->query($sql);
                    $share = $shares[0];

                    $request['Share'] = $share;
                }

                $this->formatRequests($response['requests'], $requests, true);
            }
        } else {
            throw new ShareException(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS, "Bad credentials");
        }

        return $response;
    }
    
    public function apiHome() {
        if ($this->request->is('GET')) {
            try {
                //Intern home
                $response = $this->interHome();

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        }
    }
    
    public function apiRegisterPush() {
        if ($this->request->is('GET')) {
            //Check credentials
            if ($this->checkCredentials($this->request)) {
                //Get push token
                $token = NULL;
                if (isset($this->params['url']['pushToken'])) {
                    $token = $this->params['url']['pushToken'];
                }

                if ($token != NULL) {
                    //Get user external identifier
                    $userExternalId = $this->getUserExternalId($this->request);
                    $userId = $this->getUserId($userExternalId);

                    //Save changes
                    $this->User->id = $userId;

                    //If it succeeded
                    if ($this->User->saveField('push_token', $token)) {
                        $this->sendResponse(SHARE_STATUS_CODE_OK);
                    } else {
                        $this->sendErrorResponse(SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR, SHARE_ERROR_CODE_SAVE_FAILED, "Unable to register this push token");
                    }
                } else {
                    $this->sendErrorResponse(SHARE_STATUS_CODE_BAD_REQUEST, SHARE_ERROR_CODE_BAD_PARAMETERS, 'Bad Share identifier');
                }
            } else {
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS, "Bad credentials");
            }
        }
    }
}
