<?php
App::uses('AppController', 'Controller');
App::uses('ShareException', 'Lib');

define('SHARE_PUSH_NOTIFICATION_NEW_REQUEST', 'SHARE_PUSH_NOTIFICATION_NEW_REQUEST');
define('SHARE_PUSH_NOTIFICATION_REQUEST_ACCEPTED', 'SHARE_PUSH_NOTIFICATION_REQUEST_ACCEPTED');
define('SHARE_PUSH_NOTIFICATION_REQUEST_DECLINED', 'SHARE_PUSH_NOTIFICATION_REQUEST_DECLINED');
define('SHARE_PUSH_NOTIFICATION_PARTICIPATION_CANCELLED', 'SHARE_PUSH_NOTIFICATION_PARTICIPATION_CANCELLED');

class ApiRequestsController extends AppController {
    public $name = 'ApiRequests';
    
	public $uses = array('Request', 'Share');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add', 'accept', 'decline', 'cancel');
    }

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
                        
            if ($this->isShareOpened($share) && !$this->isShareExpired($share) && $this->doesUserOwnShare($share, $userExternalId) && $this->canParticipate($share, $request['User']['external_id'])) {
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

            if ($this->isShareOpened($share) && !$this->isShareExpired($share) && $this->doesUserOwnShare($share, $userExternalId)) {
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
            
            if ($this->isShareOpened($share) && !$this->isShareExpired($share) && ($this->doesUserOwnShare($share, $userExternalId) || ($request['User']['external_id'] == $userExternalId))) {
                $canCancelRequest = true;
            }
        }
        
        return $canCancelRequest;
    }
    
    protected function internAdd($userExternalId = NULL, $shareId = NULL) {
        $response = NULL;
        
        if (($userExternalId != NULL) && ($shareId != NULL)) {
            //Get user id
            $userId = $this->getUserId($userExternalId);
            
            if ($userId != NULL) {
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
                            $this->sendPushNotif($share['Share']['user_id'], 'Vous avez une nouvelle demande.', SHARE_PUSH_NOTIFICATION_NEW_REQUEST, array("share_id" => $shareId));

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
                throw new ShareException(SHARE_STATUS_CODE_NOT_FOUND, SHARE_ERROR_CODE_RESOURCE_NOT_FOUND, "User not found");
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

                try {
                    //Intern add
                    $response = $this->internAdd($userExternalId, $shareId);

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
                        $this->sendPushNotif($request['Request']['user_id'], 'Votre demande a été acceptée.', SHARE_PUSH_NOTIFICATION_REQUEST_ACCEPTED, array("request_id" => $requestId));
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
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS);
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
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
                        $this->sendPushNotif($request['Request']['user_id'], 'Votre demande a été refusée.', SHARE_PUSH_NOTIFICATION_REQUEST_DECLINED, array("request_id" => $requestId));
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
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS);
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
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
                        $this->sendPushNotif($request['Request']['user_id'], 'Votre participation a été annulée.', SHARE_PUSH_NOTIFICATION_PARTICIPATION_CANCELLED, array("request_id" => $requestId));
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
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS);
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }
}
