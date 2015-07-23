<?php
App::uses('AppController', 'Controller');
App::uses('ShareException', 'Lib');

class ApiRequestsController extends AppController {
    public $name = 'ApiRequests';
    
	public $uses = array('Request', 'Share');

    private function canChangeStatus($request = NULL, $status = NULL) {
        $canChangeStatus = false;
        
        if (($request != NULL) && ($status != NULL)) {
            if (($status == SHARE_REQUEST_STATUS_ACCEPTED) || ($status == SHARE_REQUEST_STATUS_DECLINED)) {
                $canChangeStatus = ($request['Request']['status'] == SHARE_REQUEST_STATUS_PENDING);
            } else if ($status == SHARE_REQUEST_STATUS_CANCELLED) {
                $canChangeStatus = (($request['Request']['status'] == SHARE_REQUEST_STATUS_PENDING) || ($request['Request']['status'] == SHARE_REQUEST_STATUS_ACCEPTED));
            }
        }
        return $canChangeStatus;
    }

    private function canAcceptRequest($request = NULL, $userExternalId = NULL) {
        $canAcceptRequest = false;
        
        //Check parameters
        if (($request != NULL) && ($userExternalId != NULL) && $this->canChangeStatus($request, SHARE_REQUEST_STATUS_ACCEPTED)) {
            //Get Share identifier
            $shareId = $request['Request']['share_id'];

            //Get related Share
            $share = $this->Share->find('first', array(
                'conditions' => array(
                    'Share.id' => $shareId
                )
            ));
                        
            if ($this->isShareOpened($share) && $this->doesUserOwnShare($share, $userExternalId) && $this->canParticipate($share, $request['User']['external_id'])) {
                $canAcceptRequest = true;
            }
        }
        
        return $canAcceptRequest;
    }
    
    private function canDeclineRequest($request = NULL, $userExternalId = NULL) {
        $canDeclineRequest = false;
        
        //Check parameters
        if (($request != NULL) && ($userExternalId != NULL) && $this->canChangeStatus($request, SHARE_REQUEST_STATUS_DECLINED)) {
            //Get Share identifier
            $shareId = $request['Request']['share_id'];

            //Get related Share
            $share = $this->Share->find('first', array(
                'conditions' => array(
                    'Share.id' => $shareId
                )
            ));

            if ($this->isShareOpened($share) && $this->doesUserOwnShare($share, $userExternalId)) {
                $canDeclineRequest = true;
            }
        }
        
        return $canDeclineRequest;
    }
    
    private function canCancelRequest($request = NULL, $userExternalId = NULL) {
        $canCancelRequest = false;
        
        //Check parameters
        if (($request != NULL) && ($userExternalId != NULL) && $this->canChangeStatus($request, SHARE_REQUEST_STATUS_CANCELLED)) {
            //Get Share identifier
            $shareId = $request['Request']['share_id'];

            //Get related Share
            $share = $this->Share->find('first', array(
                'conditions' => array(
                    'Share.id' => $shareId
                )
            ));
            
            if ($this->isShareOpened($share) && ($this->doesUserOwnShare($share, $userExternalId) || ($request['User']['external_id'] == $userExternalId))) {
                $canCancelRequest = true;
            }
        }
        
        return $canCancelRequest;
    }
    
    protected function internAdd($userId = NULL, $shareId = NULL) {
        $response = NULL;
        
        if (($userId != NULL) && ($shareId != NULL)) {
            //Get related Share
            $share = $this->Share->find('first', array(
                'conditions' => array(
                    'Share.id' => $shareId
                )
            ));

            //If found
            if ($share != NULL) {
                //Can interact?
                if ($this->canRequest($share, $userId)) {
                    //Format data
                    $dataRequest['Request']['share_id'] = $shareId;
                    $dataRequest['Request']['user_id'] = $userId;
                    $dataRequest['Request']['status'] = SHARE_REQUEST_STATUS_PENDING;

                    //echo json_encode($dataSharesUsers);
                    //exit();

                    //Try to save Request
                    $request = $this->Request->save($dataRequest);

                    //If it worked
                    if ($request != NULL) {
                        //Send push notif
                        $this->sendPushNotif($share['Share']['user_id'], 'Vous avez une nouvelle demande.');

                        //Format response
                        $response['request_id'] = $request['Request']['id'];
                        $this->formatISODate($response['created'], $request['Request']['created']);
                        $this->formatISODate($response['modified'], $request['Request']['modified']);
                    } else {
                        throw new ShareException(SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR, SHARE_ERROR_CODE_SAVE_FAILED, "Request save failed");
                    }
                } else {
                    throw new ShareException(SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, SHARE_ERROR_CODE_RESOURCE_DISABLED, "You cannot request for this Share");
                }
            } else {
                throw new ShareException(SHARE_STATUS_CODE_NOT_FOUND, SHARE_ERROR_CODE_RESOURCE_NOT_FOUND, "Share not found");
            }
        } else {
            throw new ShareException(SHARE_STATUS_CODE_BAD_REQUEST, SHARE_ERROR_CODE_BAD_PARAMETERS, "Bad parameters");
        }
        
        return $response;
    }
                
    public function add() {
        if ($this->request->is('get', 'mobile', 'json')) {
            //Check credentials
            if ($this->checkCredentials($this->request)) {
                //Get Share identifier
                $shareId = NULL;
                if (isset($this->params['url']['shareId']) && is_numeric($this->params['url']['shareId'])) {
                    $shareId = $this->params['url']['shareId'];
                }

                //Get user identifier
                $userExternalId = $this->getUserExternalId($this->request);
                $userId = $this->getUserId($userExternalId);

                try {
                    //Intern add
                    $response = $this->internAdd($userId, $shareId);

                    //Send JSON respsonse
                    $this->sendResponse(SHARE_STATUS_CODE_CREATED, $response);
                } catch (ShareException $e) {
                    $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
                }
            } else {
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS, "Bad credentials");
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, "Method not allowed");
        }
    }
    
    private function changeStatus($request = NULL, $status = NULL) {
        $success = false;
        
        //If correct identifier
        if (($request != NULL) && ($status != NULL)) {
            //Save changes
            $this->Request->id = $request['Request']['id'];

            //If it succeeded
            $success = $this->Request->saveField('status', $status);
        }
        
        return $success;
    }
    
    protected function internAccept($requestId = NULL, $userExternalId = NULL) {
        if (($requestId != NULL) && ($userExternalId != NULL)) {
            //Get related Request
            $request = $this->Request->find('first', array(
                'conditions' => array(
                    'Request.id' => $requestId,
            )));

            //Check if the request exists
            if ($request != NULL) {
                //
                if ($this->canAcceptRequest($request, $userExternalId)) {
                    //Update status
                    if ($this->changeStatus($request, SHARE_REQUEST_STATUS_ACCEPTED)) {
                        //Send push notif
                        $this->sendPushNotif($request['Request']['user_id'], 'Votre demande a été acceptée.');
                    } else {
                        throw new ShareException(SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR, SHARE_ERROR_CODE_SAVE_FAILED, "Request accept failed");
                    }
                } else {
                    throw new ShareException(SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, SHARE_ERROR_CODE_RESOURCE_DISABLED, "You cannot accept this Request");
                }
            } else {
                throw new ShareException(SHARE_STATUS_CODE_NOT_FOUND, SHARE_ERROR_CODE_RESOURCE_NOT_FOUND, "Request not found");
            } 
        }
    }
    
    public function accept($requestId = NULL) {
        if ($this->request->is('get', 'mobile', 'json')) {
            /*sleep(3);
            //TEMP TEST
            $this->sendErrorResponse(SHARE_STATUS_CODE_NOT_FOUND, SHARE_ERROR_CODE_RESOURCE_NOT_FOUND,
            "Request not found", NULL);*/
            
            //Check credentials
            if ($this->checkCredentials($this->request)) {
                //Get user identifier
                $userExternalId = $this->getUserExternalId($this->request);

                try {
                    //Intern accept
                    $this->internAccept($requestId, $userExternalId);

                    //Send JSON respsonse
                    $this->sendResponse(SHARE_STATUS_CODE_OK);
                } catch (ShareException $e) {
                    $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
                }
            } else {
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS, "Bad credentials");
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, "Method not allowed");
        }
    }
    
    protected function internDecline($requestId = NULL, $userExternalId = NULL) {
        if (($requestId != NULL) && ($userExternalId != NULL)) {
            //Get related Request
            $request = $this->Request->find('first', array(
                'conditions' => array(
                    'Request.id' => $requestId,
            )));

            //Check if the request exists
            if ($request != NULL) {
                //
                if ($this->canDeclineRequest($request, $userExternalId)) {
                    //Update status
                    if ($this->changeStatus($request, SHARE_REQUEST_STATUS_DECLINED)) {
                        //Send push notif
                        $this->sendPushNotif($request['Request']['user_id'], 'Votre demande a été refusée.');
                    } else {
                        throw new ShareException(SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR, SHARE_ERROR_CODE_SAVE_FAILED, "Request decline failed");
                    }
                } else {
                    throw new ShareException(SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, SHARE_ERROR_CODE_RESOURCE_DISABLED, "You cannot decline this Request");
                }
            } else {
                throw new ShareException(SHARE_STATUS_CODE_NOT_FOUND, SHARE_ERROR_CODE_RESOURCE_NOT_FOUND, "Request not found");
            } 
        }
    }
    
    public function decline($requestId = NULL) {
        if ($this->request->is('get', 'mobile', 'json')) {
            //If it exists, then check credentials
            if ($this->checkCredentials($this->request)) {
                //Get user identifier
                $userExternalId = $this->getUserExternalId($this->request);

                try {
                    //Intern decline
                    $this->internDecline($requestId, $userExternalId);

                    //Send JSON respsonse
                    $this->sendResponse(SHARE_STATUS_CODE_OK);
                } catch (ShareException $e) {
                    $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
                }
            } else {
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS, "Bad credentials");
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, "Method not allowed");
        }
    }
    
    protected function internCancel($requestId = NULL, $userExternalId = NULL) {
        if (($requestId != NULL) && ($userExternalId != NULL)) {
            //Get related Request
            $request = $this->Request->find('first', array(
                'conditions' => array(
                    'Request.id' => $requestId,
            )));

            //Check if the request exists
            if ($request != NULL) {
                //
                if ($this->canCancelRequest($request, $userExternalId)) {
                    //Update status
                    if ($this->changeStatus($request, SHARE_REQUEST_STATUS_CANCELLED)) {
                        //Send push notif
                        $this->sendPushNotif($request['Request']['user_id'], 'Votre participation a été annulée.');
                    } else {
                        throw new ShareException(SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR, SHARE_ERROR_CODE_SAVE_FAILED, "Request cancel failed");
                    }
                } else {
                    throw new ShareException(SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, SHARE_ERROR_CODE_RESOURCE_DISABLED, "You cannot cancel this Request");
                }
            } else {
                throw new ShareException(SHARE_STATUS_CODE_NOT_FOUND, SHARE_ERROR_CODE_RESOURCE_NOT_FOUND, "Request not found");
            } 
        }
    }
    
    public function cancel($requestId = NULL) {
        if ($this->request->is('get', 'mobile', 'json')) {
            //If it exists, then check credentials
            if ($this->checkCredentials($this->request)) {
                //Get user identifier
                $userExternalId = $this->getUserExternalId($this->request);

                try {
                    //Intern cancel
                    $this->internCancel($requestId, $userExternalId);

                    //Send JSON respsonse
                    $this->sendResponse(SHARE_STATUS_CODE_OK);
                } catch (ShareException $e) {
                    $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
                }
            } else {
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS, "Bad credentials");
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, "Method not allowed");
        }
    }
}
