<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

//Twitter (Digits)
define('SHARE_APP_TOKEN', 'kdxjY0pNrdKapJYHguAAox8Yp');
define('SHARE_APP_TOKEN_SECRET', 'VET5zHNxAvcufcut2j6A05OyGXbgVJYoajN8d8EKpwvjw3jhDe');

//Facebook
define('SHARE_FACEBOOK_APP_ID', '329532937243649');
define('SHARE_FACEBOOK_APP_SECRET', '287b6026bd95ce1c0f82a9179261a1c8');

//Google Maps
define('SHARE_GOOGLE_MAPS_API_KEY', 'AIzaSyCgtORANisQp5cQ-B1tMFIunsrIZYSOp-k');

//Session
define('SHARE_LOCAL_USER_SESSION_PREFIX', 'LocalUser');

//Auth
define('SHARE_HEADER_AUTH_EXTERNAL_ID', 'auth-user-external-id');
define('SHARE_HEADER_AUTH_TOKEN', 'auth-user-token');
define('SHARE_HEADER_AUTH_MAIL', 'auth-user-mail');
define('SHARE_HEADER_AUTH_USERNAME', 'auth-user-username');

define('SHARE_CHECK_CREDENTIALS_MAX_INTERVAL', 15);

//
define('SHARE_STATUS_CODE_OK', 200);
define('SHARE_STATUS_CODE_CREATED', 201);

//
define('SHARE_STATUS_CODE_BAD_REQUEST', 400);
define('SHARE_STATUS_CODE_UNAUTHORIZED', 401);
define('SHARE_STATUS_CODE_NOT_FOUND', 404);
define('SHARE_STATUS_CODE_METHOD_NOT_ALLOWED', 405);
define('SHARE_STATUS_CODE_PRECONDITION_FAILED', 412);
define('SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR', 500);

//Internal errors codes
define('SHARE_ERROR_CODE_VALIDATION_FAILED', 1);
define('SHARE_ERROR_CODE_SAVE_FAILED', 2);
define('SHARE_ERROR_CODE_BAD_PARAMETERS', 3);
define('SHARE_ERROR_CODE_ALREADY_EXISTS', 4);
define('SHARE_ERROR_CODE_BAD_CREDENTIALS', 5);
define('SHARE_ERROR_CODE_RESOURCE_NOT_FOUND', 6);
define('SHARE_ERROR_CODE_RESOURCE_DISABLED', 7);

define("SHARE_SEARCH_LIMIT", 10);

define("SHARE_COMMENTS_LIMIT", 5);

//String min size
define("SHARE_SHARE_TITLE_MIN_LENGTH", 15);
define("SHARE_COMMENT_MESSAGE_MIN_LENGTH", 2);

//Share statuses
define("SHARE_STATUS_OPENED", 0);
define("SHARE_STATUS_CLOSED", 1);

//Requests
define("SHARE_REQUEST_STATUS_PENDING", 0);
define("SHARE_REQUEST_STATUS_ACCEPTED", 1);
define("SHARE_REQUEST_STATUS_DECLINED", 2);
define("SHARE_REQUEST_STATUS_CANCELLED", 3);

App::uses('Controller', 'Controller');

require_once APP . 'Vendor' . DS . 'autoload.php';
use Facebook\FacebookSession;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array('Session', 'DebugKit.Toolbar');
    
	public $uses = array('User', 'Share', 'Comment', 'Request');
    
    public $helpers = array('ShareType', 'LocalUser');

    protected function saveAuthSession($userExternalId = NULL, $mail = NULL, $authToken = NULL, $username = NULL) {
        if (($userExternalId != NULL) && ($mail != NULL) && ($authToken != NULL) && ($userExternalId != NULL)) {
            $this->Session->write(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_EXTERNAL_ID, $userExternalId);
            $this->Session->write(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_MAIL, $mail);
            $this->Session->write(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_TOKEN, $authToken);
            $this->Session->write(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_USERNAME, $username);
        }
    }

    protected function isLocalUserSessionAuthenticated() {
        $userExternalId = $this->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_EXTERNAL_ID);
        $mail = $this->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_MAIL);
        $authToken = $this->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_TOKEN);
        $username = $this->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_USERNAME);

        $isAuthenticated = (($userExternalId != NULL) && ($mail != NULL) && ($authToken != NULL) && ($username != NULL));

        return $isAuthenticated;
    }
    
    protected function invalidateLocalUserSession() {
        $this->Session->delete(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_EXTERNAL_ID);
        $this->Session->delete(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_MAIL);
        $this->Session->delete(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_TOKEN);
        $this->Session->delete(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_USERNAME);
    }

    public function beforeFilter() {
        parent::beforeFilter();

        //Initialize Facebook
        FacebookSession::setDefaultApplication(SHARE_FACEBOOK_APP_ID, SHARE_FACEBOOK_APP_SECRET);

        /*//If user is not logged with its session but a cookie is present
        if (!$this->isLocalUserSessionAuthenticated() && $this->isLocalUserCookieAuthenticated()) {
            $userExternalId = $this->Cookie->read(SHARE_HEADER_AUTH_EXTERNAL_ID);
            $phoneNumber = $this->Cookie->read(SHARE_HEADER_AUTH_PHONE_NUMBER);
            $authToken = $this->Cookie->read(SHARE_HEADER_AUTH_TOKEN);
            $username = $this->Cookie->read(SHARE_HEADER_AUTH_USERNAME);

            //Save session
            $this->saveAuthSession($userExternalId, $phoneNumber, $authToken, $username);
        }*/
    }
    
    private function extractUserAuthValue($request = NULL, $key = NULL) {
        $userAuthValue = NULL;
        
        if ($key != NULL) {
            //Try to get it from request headers
            if ($request != NULL) {
                $userAuthValue = $request->header($key);
            }

            if ($userAuthValue == NULL) {
                //If NULL, try to get it from the Session
                $userAuthValue = $this->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.$key);
            }
        }
        
        return $userAuthValue;
    }
    
    protected function checkCredentials($request = NULL) {
        $isAuthenticated = false;
        
        if ($request != NULL) {
            //Get user external id
            $userExternalId = $this->extractUserAuthValue($request, SHARE_HEADER_AUTH_EXTERNAL_ID);
            
            //Get user
            $user = $this->User->find('first', array(
                'fields' => array('User.id, User.last_check_credentials_date'),
                'conditions' => array(
                    'User.external_id' => $userExternalId
                )
            ));
            
            if ($user != NULL) {
                //Get interval between now and last check credentials date
                $lastCheckCredentialsDate = date_create($user['User']['last_check_credentials_date']);
                $nbMinutes = date_diff(new DateTime(), $lastCheckCredentialsDate)->format('%i');

                //Check if we need to check credentials
                if ($nbMinutes > SHARE_CHECK_CREDENTIALS_MAX_INTERVAL) {
                    //Get tokens
                    $userToken = $this->extractUserAuthValue($request, SHARE_HEADER_AUTH_TOKEN);

                    /*$response['token'] = $userToken;

                    echo json_encode($response);
                    exit();*/

                    if ($userToken != NULL) {
                        //If you already have a valid access token:
                        $session = new FacebookSession($userToken);

                        //To validate the session:
                        try {
                            $isAuthenticated = $session->validate();
                        } catch (FacebookRequestException $ex) {
                            //Session not valid, Graph API returned an exception with the reason.
                            $this->invalidateLocalUserSession();
                        } catch (Exception $ex) {
                            //Graph API returned info, but it may mismatch the current app or have expired.
                            $this->invalidateLocalUserSession();
                        }
                    }
                } else {
                    //By pass Facebook check credentials
                    return true;
                }
            } else {
                return false;
            }
        }
        
        return $isAuthenticated;
    }
    
    protected function getUserExternalId($request = NULL) {
        return $this->extractUserAuthValue($request, SHARE_HEADER_AUTH_EXTERNAL_ID);
    }
    
    protected function getUserId($userExternalId = NULL) {
        $userId = NULL;
        
        if ($userExternalId != NULL) {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.external_id' => $userExternalId
                )
            ));
            
            //If found
            if ($user != NULL) {
                $userId = $user['User']['id'];
            }
        }
        
        return $userId;
    }
    
    protected function sendErrorResponse($statusCode = NULL, $errorCode = NULL, $errorMessage = NULL, $validationErrors = NULL) {
        //Error code
        if ($errorCode != NULL) {
            $errorResponse['error_code'] = $errorCode;
        }
        
        //Message
        if ($errorMessage != NULL) {
            $errorResponse['error_message'] = $errorMessage;
        }
        
        //Validation
        if ($validationErrors != NULL) {
            $errorResponse['validation_errors'] = $validationErrors;
        }
        
        $this->sendResponse($statusCode, $errorResponse);
    }
    
    protected function sendResponse($statusCode = NULL, $response = NULL) {
        if ($statusCode != NULL) {
            $this->autoRender = false;
            
            $this->response->type('json');
            $this->response->statusCode($statusCode);
            
            if ($response !== NULL) {
                $this->response->body(json_encode($response));
            }
            
            return $this->response;
        }
    }
    
    protected function formatComments(& $response, $comments) {
        $response = array();
        
        $commentIndex = 0;
        
        /*echo json_encode($comments);
        exit();*/
        
        foreach ($comments as $comment) {
            $response[$commentIndex]['comment_id'] = $comment['Comment']['id'];
            $response[$commentIndex]['message'] = $comment['Comment']['message'];

            //Created
            $this->formatISODate($response[$commentIndex]['created'], $comment['Comment']['created']);

            $response[$commentIndex]['share_id'] = $comment['Comment']['share_id'];
            $response[$commentIndex]['user']['external_id'] = $comment['User']['external_id'];
            $response[$commentIndex]['user']['username'] = $comment['User']['username'];

            $commentIndex++;
        }
    }
    
    protected function formatRequests(& $response, $requests, $returnShare = false) {
        $response = array();
                
        $requestIndex = 0;
        $userParticipate = false;
        
        foreach ($requests as $request) {
            $status = $request['Request']['status'];

            $response[$requestIndex]['request_id'] = $request['Request']['id'];
            $response[$requestIndex]['share_id'] = $request['Request']['share_id'];
            $response[$requestIndex]['status'] = $status;

            //Share
            if ($returnShare) {
                $response[$requestIndex]['share'] = $this->formatShare($request['Share']);
            }

            //User
            $response[$requestIndex]['user']['external_id'] = $request['User']['external_id'];
            $response[$requestIndex]['user']['username'] = $request['User']['username'];
            
            //Return mail only if the request has been accepted
            if ($status == SHARE_REQUEST_STATUS_ACCEPTED) {
                $response[$requestIndex]['user']['mail'] = $request['User']['mail'];
            }

            $requestIndex++;
        }
    }
    
    protected function checkField(& $data, $fieldName) {
        if (isset($data[$fieldName]) && ($data[$fieldName] === '')) {
            $data[$fieldName] = null;
        }
    }

    protected function formatISODate(& $field = NULL, $date = NULL) {
        if ($date != NULL) {
            $dateTime = new DateTime($date);
            $isoDate = date('c', $dateTime->getTimestamp());

            $field = $isoDate;
        }
    }
    
    protected function formatShare($share = NULL, $returnComments = false, $returnRequests = false) {
        $response = NULL;

        /*echo json_encode($share);
        exit();*/
        
        if ($share != NULL) {
            //Create response
            $response = array();
            
            //Format data
            $response['share_id'] = $share['Share']['id'];
            $response['user']['external_id'] = $share['User']['external_id'];
            $response['user']['username'] = $share['User']['username'];

            $response['title'] = $share['Share']['title'];

            //Event date
            $this->formatISODate($response['event_date'], $share['Share']['event_date']);

            $response['share_type']['share_type_id'] = $share['ShareType']['id'];
            $response['share_type']['label'] = $share['ShareType']['label'];
            $response['share_type']['share_type_category_id'] = $share['ShareType']['share_type_category_id'];
            $response['share_type_category']['share_type_category_id'] = $share['ShareTypeCategory']['id'];
            $response['share_type_category']['label'] = $share['ShareTypeCategory']['label'];
            $response['price'] = $share['Share']['price'];
            $response['places'] = $share['Share']['places'];
            
            $response['participation_count'] = $share['0']['participation_count'];
            
            $response['waiting_time'] = $share['Share']['waiting_time'];
            $response['meet_place'] = $share['Share']['meet_place'];
            $response['limitations'] = $share['Share']['limitations'];
            $response['status'] = $share['Share']['status'];
            $response['image_url'] = $share['Share']['image_url'];
            $response['message'] = $share['Share']['message'];

            //Position
            $response['latitude'] = $share['0']['latitude'];
            $response['longitude'] = $share['0']['longitude'];
            $response['accuracy'] = $share['Share']['accuracy'];
            $response['city'] = $share['Share']['city'];
            $response['zip_code'] = $share['Share']['zip_code'];

            $response['radius'] = $share['Share']['radius'];

            //Created, modified
            $this->formatISODate($response['created'], $share['Share']['created']);
            $this->formatISODate($response['modified'], $share['Share']['modified']);
            
            //Comments            
            $commentsCount = $share['Share']['comment_count'];
            $response['comment_count'] = $commentsCount;
                        
            if ($returnComments && ($commentsCount > 0)) {
                $comments = $this->Comment->find('all', array(
                    'limit' => SHARE_COMMENTS_LIMIT,
                    'conditions' => array('Comment.share_id' => $share['Share']['id']),
                    'order' => 'Comment.created DESC'
                ));
                $this->formatComments($response['comments'], $comments);
            }
            
            //Requests            
            $requestCount = $share['Share']['request_count'];
            $response['request_count'] = $requestCount;
            
            if ($returnRequests && ($requestCount > 0)) {
                $requests = $this->Request->find('all', array(
                    'conditions' => array(
                        'Request.share_id' => $share['Share']['id']
                    ),
                    'order' => 'Request.created ASC'
                ));
                $this->formatRequests($response['requests'], $requests);
            }
        }
        
        return $response;
    }
    
    protected function sendPushNotif($userId = NULL, $message = NULL) {
        $success = false;
        
        if (($userId != NULL) && ($message != NULL)) {
            //Find related user
            $user = $this->User->find('first', array(
                'conditions' => array('User.id' => $userId)
            ));
            
            //If found
            if ($user != NULL) {            
                $url = 'https://api.parse.com/1/push';

                $appId = 'U1a7oCAekLjUEbuDpM3cILW29g2idD8IrPrzzW01';
                $restKey = 'FSggSYpxDBaGnpiTepmEjR2ILJI2ofH1SkfN6Wiv';

                $headers = array(
                    "Content-Type: application/json",
                    "X-Parse-Application-Id: " . $appId,
                    "X-Parse-REST-API-Key: " . $restKey
                );

                $objectData = '{"where":{"deviceToken":{"$in":["'.$user['User']['push_token'].'"]}},"data":{"alert":"'.$message.'"}}';

                $rest = curl_init();
                curl_setopt($rest,CURLOPT_URL,$url);
                curl_setopt($rest,CURLOPT_POST,1);
                curl_setopt($rest,CURLOPT_POSTFIELDS,$objectData);
                curl_setopt($rest,CURLOPT_HTTPHEADER,$headers);
                curl_setopt($rest,CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($rest,CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($rest);
                curl_close($rest);
            }
        }
        
        return $success;
    }

    protected function isShareOpened($share = NULL) {
        return ($share['Share']['status'] == SHARE_STATUS_OPENED);
    }

    protected function isPlacesLeft($share = NULL) {
        $isPlacesLeft = false;

        if ($share != NULL) {
            $leftPlaces = $share['Share']['places'];

            //Check place
            if (isset($share['Request'])) {

                foreach ($share['Request'] as $request) {
                    if ($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) {
                        $leftPlaces--;
                    }
                }
            }

            $isPlacesLeft = ($leftPlaces > 0);
        }

        return $isPlacesLeft;
    }

    protected function doesUserOwnShare($share = NULL, $userExternalId = NULL) {
        $doesUserOwnShare = false;

        if (($share != NULL) && ($userExternalId != NULL)) {
            $doesUserOwnShare = ($share['User']['external_id'] == $userExternalId);
        }

        return $doesUserOwnShare;
    }

    protected function canParticipate($share = NULL, $userExternalId = NULL) {
        $canParticipate = false;

        if (($share != NULL) && ($userExternalId != NULL)) {
            //Check if user does not already participate
            if (!$this->doesUserOwnShare($share, $userExternalId) && $this->isShareOpened($share) && $this->isPlacesLeft($share)) {
                $canParticipate = true;
            }
        }

        return $canParticipate;
    }

    protected function getRequestStatus($share = NULL, $userExternalId = NULL) {
        $requestStatus = NULL;

        if (($share != NULL) && ($userExternalId != NULL)) {
            //Find first Request
            $request = $this->Request->find('first', array(
                'conditions' => array(
                    'Request.share_id' => $share['Share']['id'],
                    'User.external_id' => $userExternalId
                )
            ));

            if ($request != NULL) {
                $requestStatus = $request['Request']['status'];
            }
        }

        return $requestStatus;
    }

    protected function canRequest($share = NULL, $userExternalId = NULL) {
        $canRequest = false;

        if (($share != NULL) && ($userExternalId != NULL)) {
            //Check if user does not already participate
            if ($this->canParticipate($share, $userExternalId)) {
                $requestStatus = $this->getRequestStatus($share, $userExternalId);
                $canRequest = ($requestStatus == NULL);
            }
        }

        return $canRequest;
    }

    protected function getShareTypeCategoryTypes($shareTypeCategory = NULL) {
        $types = NULL;

        if ($shareTypeCategory != NULL) {
            $types = $this->ShareType->find('list', array(
                'fields' => array('ShareType.id'),
                'recursive' => 0,
                'conditions' => array(
                    'ShareTypeCategory.label' => $shareTypeCategory
                )
            ));
        }

        return $types;
    }

    protected function getShareType($shareTypeCategory = NULL, $shareType = NULL) {
        $type = NULL;

        if (($shareTypeCategory != NULL) && ($shareType != NULL)) {
            $type = $this->ShareType->find('first', array(
                'fields' => array('ShareType.id'),
                'conditions' => array(
                    'ShareTypeCategory.label' => $shareTypeCategory,
                    'ShareType.label' => $shareType
                )
            ));
        }

        return $type;
    }
}
