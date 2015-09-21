<?php
App::uses('AppController', 'Controller');
App::uses('ShareException', 'Lib');

class ApiUsersController extends AppController {
	public $uses = array('User');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add', 'details', 'shares', 'requests', 'registerPush');
    }

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

	public function add() {
        if ($this->request->is('put')) {
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
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
	}
    
    protected function internDetails($externalId = NULL) {
        $response = NULL;
        
        //Check parameter
        if ($externalId != NULL) {
            //Find first user
            $user = $this->User->find('first', array(
                'fields' => array('User.username', 'User.external_id', 'User.description', 'User.created', 'User.modified', 'User.share_count', 'User.request_count', 'User.comment_count'),
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
                $this->formatISODate($response['modified'], $user['User']['modified']);

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
    
    public function details($externalId = NULL) {
        if ($this->request->is('get')) {
            try {
                //Intern details
                $response = $this->internDetails($externalId);

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }

    protected function internShares($userExternalId = NULL, $startDate = NULL, $endDate = NULL, $page = 1, $limit = SHARE_USERS_SHARES_LIMIT) {
        $response = NULL;

        if ($userExternalId != NULL) {
            $response['page'] = $page;
            $response['results'] = array();

            //Offset
            $offset = ($page - 1) * $limit;

            $fromAndWhereClause = "FROM shares Share, users User, share_types ShareType, share_type_categories ShareTypeCategory WHERE Share.user_id = User.id AND Share.share_type_id = ShareType.id AND ShareType.share_type_category_id = ShareTypeCategory.id AND User.external_id = " . $userExternalId . " AND Share.status = " . SHARE_STATUS_OPENED;

            //Start date
            if ($startDate != NULL) {
                $sqlStartDate = $startDate->format('Y-m-d');

                $fromAndWhereClause .= " AND Share.start_date >= '" . $sqlStartDate . "'";
            }

            //End date
            if ($endDate != NULL) {
                $sqlEndDate = $endDate->format('Y-m-d');

                $fromAndWhereClause .= " AND Share.start_date < '" . $sqlEndDate . "'";
            }
            $sql = "SELECT *, X(Share.location) as latitude, Y(Share.location) as longitude, (SELECT COUNT(Request.id) FROM requests Request WHERE Request.share_id = Share.id AND Request.status = 1) AS participation_count " . $fromAndWhereClause . " LIMIT " . $limit . " OFFSET " . $offset . ";";
            $shares = $this->Share->query($sql);

            //pr($shares);

            //Format Shares
            $response['results'] = array();

            $shareIndex = 0;
            foreach ($shares as $share) {
                $response['results'][$shareIndex++] = $this->formatShare($share, true);
            }

            //Total results count
            $totalResults = 0;
            $totalShares = $this->Share->query("SELECT COUNT(Share.id) as total_results ".$fromAndWhereClause.";");
            if (count($totalShares) > 0) {
                $totalResults = $totalShares[0][0]['total_results'];
            }

            $response['total_results'] = $totalResults;
            $response['total_pages'] = ceil($totalResults / $limit);

            //Return limit
            $response['limit'] = $limit;
        }

        return $response;
    }

    public function shares() {
        if ($this->request->is('get')) {
            //Check credentials
            if ($this->checkCredentials($this->request)) {
                try {
                    //Get user external identifier
                    $userExternalId = $this->getUserExternalId($this->request);

                    //Start date
                    $startDate = NULL;
                    if (isset($this->params['url']['start']) && is_numeric($this->params['url']['start'])) {
                        $startTimestamp = $this->params['url']['start'];

                        $startDate = new DateTime();
                        $startDate->setTimestamp($startTimestamp);
                    }

                    //End date
                    $endDate = NULL;
                    if (isset($this->params['url']['end']) && is_numeric($this->params['url']['end'])) {
                        $endTimestamp = $this->params['url']['end'];

                        $endDate = new DateTime();
                        $endDate->setTimestamp($endTimestamp);
                    }

                    //Page
                    $page = 1;
                    if (isset($this->params['url']['page']) && is_numeric($this->params['url']['page'])) {
                        $page = $this->params['url']['page'];
                    }

                    //Limit
                    $limit = SHARE_USERS_SHARES_LIMIT;
                    if (isset($this->params['url']['limit']) && is_numeric($this->params['url']['limit'])) {
                        $limit = $this->params['url']['limit'];
                    }

                    //Intern shares
                    $response = $this->internShares($userExternalId, $startDate, $endDate, $page, $limit);

                    //Send JSON respsonse
                    $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
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

    protected function internRequests($userExternalId = NULL, $startDate = NULL, $endDate = NULL, $page = 1, $limit = SHARE_USERS_SHARES_LIMIT) {
        $response = NULL;

        if ($userExternalId != NULL) {
            $response['page'] = $page;
            $response['results'] = array();

            //Offset
            $offset = ($page - 1) * $limit;

            $conditions = array(
                'User.external_id' => $userExternalId,
                'Share.status' => SHARE_STATUS_OPENED
            );

            //Start date
            if ($startDate != NULL) {
                $conditions['Share.start_date >= '] = $startDate->format('Y-m-d');
            }

            //End date
            if ($endDate != NULL) {
                $conditions['Share.start_date < '] = $endDate->format('Y-m-d');
            }

            $requests = $this->Request->find('all', array(
                'conditions' => $conditions,
                'limit' => $limit,
                'offset' => $offset
            ));
            $totalResults = $this->Request->find('count', array(
                'conditions' => $conditions
            ));

            $response['results'] = array();
            foreach ($requests as & $request) {
                $sql = "SELECT *, X(Share.location) as latitude, Y(Share.location) as longitude, (SELECT COUNT(Request.id) FROM requests Request WHERE Request.share_id = Share.id AND Request.status = 1) AS participation_count FROM shares AS Share, users AS User, share_types AS ShareType, share_type_categories ShareTypeCategory WHERE Share.user_id = User.id AND Share.share_type_id = ShareType.id AND ShareType.share_type_category_id = ShareTypeCategory.id AND Share.id = ".$request['Request']['share_id']." LIMIT 1;";

                $shares = $this->Share->query($sql);
                $share = $shares[0];
                
                $request['Share'] = $share;
            }

            $this->formatRequests($response['results'], $requests, true);

            $response['total_results'] = $totalResults;
            $response['total_pages'] = ceil($totalResults / $limit);

            //Return limit
            $response['limit'] = $limit;
        }

        return $response;
    }

    public function requests() {
        if ($this->request->is('get')) {
            //Check credentials
            if ($this->checkCredentials($this->request)) {
                try {
                    //Get user external identifier
                    $userExternalId = $this->getUserExternalId($this->request);

                    //Start date
                    $startDate = NULL;
                    if (isset($this->params['url']['start']) && is_numeric($this->params['url']['start'])) {
                        $startTimestamp = $this->params['url']['start'];

                        $startDate = new DateTime();
                        $startDate->setTimestamp($startTimestamp);
                    }

                    //End date
                    $endDate = NULL;
                    if (isset($this->params['url']['end']) && is_numeric($this->params['url']['end'])) {
                        $endTimestamp = $this->params['url']['end'];

                        $endDate = new DateTime();
                        $endDate->setTimestamp($endTimestamp);
                    }

                    //Page
                    $page = 1;
                    if (isset($this->params['url']['page']) && is_numeric($this->params['url']['page'])) {
                        $page = $this->params['url']['page'];
                    }


                    //Limit
                    $limit = SHARE_USERS_SHARES_LIMIT;
                    if (isset($this->params['url']['limit']) && is_numeric($this->params['url']['limit'])) {
                        $limit = $this->params['url']['limit'];
                    }

                    //Intern requests
                    $response = $this->internRequests($userExternalId, $startDate, $endDate, $page, $limit);

                    //Send JSON respsonse
                    $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
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
    
    public function registerPush() {
        if ($this->request->is('get')) {
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
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS);
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }
}
