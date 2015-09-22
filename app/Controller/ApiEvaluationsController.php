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
    
    private function canEvaluateParticipant($request = NULL, $share = NULL, $userIdFrom = NULL, $userIdTo = NULL) {
        $canEvaluate = false;
        
        //Check parameters
        if (($request != NULL) && ($share != NULL) && ($userIdFrom != NULL) && ($userIdTo != NULL)) {
            $participant = false;
            $hasAlreadyEvaluateShare = true;
            
            if (($userIdFrom == $share['Share']['user_id']) && ($userIdTo == $request['Request']['user_id'])) {
                $participant = true;
                $hasAlreadyEvaluateShare = ($request['Request']['participant_evaluation_id'] == $userIdTo);
            }
                        
            $shareFull = ($share['0']['participation_count'] == $share['Share']['places']);
            if ($participant && !$hasAlreadyEvaluateShare && $shareFull && $this->isShareOpened($share)) {
                $canEvaluate = true;
            }
        }
        
        return $canEvaluate;
    }

    private function canEvaluateCreator($request = NULL, $share = NULL, $userIdFrom = NULL, $userIdTo = NULL) {
        $canEvaluate = false;
        
        //Check parameters
        if (($request != NULL) && ($share != NULL) && ($userIdFrom != NULL) && ($userIdTo != NULL)) {
            $creator = false;
            $hasAlreadyEvaluateShare = true;
            
            if (($userIdFrom == $request['Request']['user_id']) && ($userIdTo == $share['Share']['user_id'])) {
                $creator = true;
                $hasAlreadyEvaluateShare = ($request['Request']['creator_evaluation_id'] == $userIdTo);
            }
                        
            $shareFull = ($share['0']['participation_count'] == $share['Share']['places']);
            if ($creator && !$hasAlreadyEvaluateShare && $shareFull && $this->isShareOpened($share)) {
                $canEvaluate = true;
            }
        }
        
        return $canEvaluate;
    }
    
    protected function internAdd($userExternalIdFrom = NULL, $requestId = NULL, $userExternalIdTo = NULL, $rating = NULL, $message = NULL) {
        $response = NULL;
        
        if (($userExternalIdFrom != NULL) && ($requestId != NULL) && ($userExternalIdTo != NULL) && ($rating != NULL)) {
            //Get user id
            $userIdFrom = $this->getUserId($userExternalIdFrom);
            $userIdTo = $this->getUserId($userExternalIdTo);
            
            if (($userIdFrom != NULL) && ($userIdTo != NULL)) {
                //Get related Request
                $request = $this->Request->find('first', array(
                    'conditions' => array(
                        'Request.id' => $requestId
                    )
                ));
                
                //And related Share
                $sql = "SELECT *, X(Share.location) as latitude, Y(Share.location) as longitude, (SELECT COUNT(Request.id) FROM requests Request WHERE Request.share_id = Share.id AND Request.status = 1) AS participation_count FROM shares AS Share, users AS User, share_types AS ShareType, share_type_categories ShareTypeCategory WHERE Share.user_id = User.id AND Share.share_type_id = ShareType.id AND ShareType.share_type_category_id = ShareTypeCategory.id AND Share.id = ".$request['Request']['share_id']." LIMIT 1;";

                $shares = $this->Share->query($sql);
                $share = $shares[0];

                //If found
                if ($request != NULL) {
                    //Can evaluate?
                    $canEvaluateParticipant = $this->canEvaluateParticipant($request, $share, $userIdFrom, $userIdTo);
                    $canEvaluateCreator = $this->canEvaluateCreator($request, $share, $userIdFrom, $userIdTo);
                    if ($canEvaluateParticipant || $canEvaluateCreator) {
                        //Format data
                        $dataEvaluation['Evaluation']['rating'] = $rating;
                        $dataEvaluation['Evaluation']['message'] = $message;

                        //echo json_encode($dataSharesUsers);
                        //exit();

                        //Try to save Evaluation
                        $evaluation = $this->Evaluation->save($dataEvaluation);

                        //If it worked
                        if ($evaluation != NULL) {
                            if ($canEvaluateParticipant) {
                                $dataRequest['participant_evaluation_id'] = $evaluation['Evaluation']['id'];
                            }
                            if ($canEvaluateCreator) {
                                $dataRequest['creator_evaluation_id'] = $evaluation['Evaluation']['id'];
                            }

                            $this->Request->id = $requestId;
                            if ($this->Request->save($dataRequest)) {
                                //Send push notif
                                $this->sendPushNotif($userIdTo, 'Vous avez une nouvelle évaluation.', SHARE_PUSH_NOTIFICATION_EVALUATION_ADDED, array("request_id" => $requestId));

                                //Format response
                                $response['evaluation_id'] = $evaluation['Evaluation']['id'];
                                $this->formatISODate($response['created'], $evaluation['Evaluation']['created']);
                            } else {
                                throw new ShareException(SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR, SHARE_ERROR_CODE_SAVE_FAILED, "Request update failed");
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
                $userExternalIdFrom = $this->getUserExternalId($this->request);

                try {
                    //Intern add
                    $response = $this->internAdd($userExternalIdFrom, $data['request_id'], $data['user_external_id'], $data['rating'], $data['message']);

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
