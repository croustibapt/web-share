<?php
App::uses('AppController', 'Controller');
App::uses('ShareException', 'Lib');

define('SHARE_PUSH_NOTIFICATION_EVALUATION_ADDED', 'SHARE_PUSH_NOTIFICATION_EVALUATION_ADDED');

class ApiEvaluationsController extends AppController {
    public $name = 'ApiEvaluations';
    
	public $uses = array('Evaluation', 'Request', 'Share');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add');
    }
    
    private function canEvaluate($request = NULL, $userExternalId = NULL, $share = NULL, $userId = NULL) {
        $canEvaluate = false;
        
        //Check parameters
        if (($request != NULL) && ($userExternalId != NULL) && ($userId != nil)) {
            $creator = false;
            $participant = false;
            $hasAlreadyEvaluateShare = true;
            
            if ($userId == $share['Share']['user_id']) {
                $creator = true;
                $hasAlreadyEvaluateShare = ($request['Request']['creator_evaluation_id'] == $userId);
            } else if ($userId == $request['Request']['user_id']) {
                $participant = true;
                $hasAlreadyEvaluateShare = ($request['Request']['participant_evaluation_id'] == $userId);
            }
            
            if (($creator || $participant) && !$hasAlreadyEvaluateShare && ($share['participation_count'] == $share['places']) && $this->isShareOpened($share)) {
                $canEvaluate = true;
            }
        }
        
        return $canEvaluate;
    }
    
    protected function internAdd($userExternalId = NULL, $requestId = NULL, $userId = NULL, $rating = NULL, $message = NULL) {
        $response = NULL;
        
        if (($userExternalId != NULL) && ($requestId != NULL) && ($userId != NULL) && ($rating != NULL)) {
            //Get user id
            $userId = $this->getUserId($userExternalId);
            
            if ($userId != NULL) {
                //Get related Request
                $request = $this->Request->find('first', array(
                    'conditions' => array(
                        'Request.id' => $requestId
                    )
                ));

                //If found
                if ($request != NULL) {
                    //Can interact?
                    if ($this->canEvaluate($share, $userId)) {
                        //Format data
                        $dataEvaluation['Evaluation']['rating'] = $rating;
                        $dataEvaluation['Evaluation']['message'] = $message;

                        //echo json_encode($dataSharesUsers);
                        //exit();

                        //Try to save Evaluation
                        $evaluation = $this->Evaluation->save($dataEvaluation);

                        //If it worked
                        if ($evaluation != NULL) {
                            $dataRequest['rating'] = $rating;
                            $dataRequest['message'] = $message;

                            $this->Request->id = $requestId;
                            if ($this->Request->save(dataRequest)) {
                                //Send push notif
                                $this->sendPushNotif($userId, 'Vous avez une nouvelle évaluation.', SHARE_PUSH_NOTIFICATION_EVALUATION_ADDED, array("request_id" => $requestId));

                                //Format response
                                $response['evaluation_id'] = $evaluation['Evaluation']['id'];
                                $this->formatISODate($response['created'], $evaluation['Evaluation']['created']);
                            } else {

                            }                            
                        } else {
                            throw new ShareException(SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR, SHARE_ERROR_CODE_SAVE_FAILED, "Evaluation save failed");
                        }
                    } else {
                        throw new ShareException(SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, SHARE_ERROR_CODE_RESOURCE_DISABLED, "You cannot evaluate for this Request");
                    }
                } else {
                    throw new ShareException(SHARE_STATUS_CODE_NOT_FOUND, SHARE_ERROR_CODE_RESOURCE_NOT_FOUND, "Request not found");
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
                //Get data
                $data = $this->request->input('json_decode', true);

                //Get user identifier
                $userExternalId = $this->getUserExternalId($this->request);

                try {
                    //Intern add
                    $response = $this->internAdd($userExternalId, $data['request_id'], $data['user_id'], $data['rating'], $data['message']);

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
}
