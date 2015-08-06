<?php
App::uses('ApiUsersController', 'Controller');

class UsersController extends ApiUsersController {
	public $uses = array('User');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('shares', 'requests', 'registerPush');
        $this->Auth->allow('fbLogin', 'login');
    }

    private function internLogin($userExternalId, $mail, $userAuthToken, $username) {
        //Save session
        $this->request->addParams(array(
            SHARE_HEADER_AUTH_EXTERNAL_ID => $userExternalId,
            SHARE_HEADER_AUTH_MAIL => $mail,
            SHARE_HEADER_AUTH_TOKEN => $userAuthToken,
            SHARE_HEADER_AUTH_USERNAME => $username
        ));

        if ($this->Auth->login()) {
            CakeLog::write('debug', 'login succeeded');

            CakeLog::write('debug', $this->Auth->user('token'));

            //Set flash success TEMP
            $this->Session->setFlash('Bienvenue '.$username, 'flash-success');

            $this->redirect('/');
            //$this->redirect($this->Auth->redirectUrl());
        } else {
            CakeLog::write('debug', 'login failed');

            //Set flash error
            $this->Session->setFlash('Login failed', 'flash-error');

            $this->redirect('/');
        }
    }

    public function fbLogin() {
        CakeLog::write('debug', 'fblogin1');

        if ($this->request->is('get')) {
            CakeLog::write('debug', 'fblogin2');

            //Facebook session stuff
            /*$this->autoRender = false;
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }*/

            $facebook = new Facebook\Facebook([
                'app_id' => SHARE_FACEBOOK_APP_ID,
                'app_secret' => SHARE_FACEBOOK_APP_SECRET,
                'default_graph_version' => 'v2.2',
                'persistent_data_handler' => new CakePersistentDataHandler($this)
            ]);

            //Get redirect helper
            $helper = $facebook->getRedirectLoginHelper();

            CakeLog::write('debug', 'fblogin3');

            try {
                //Try to get the associated token
                $accessToken = $helper->getAccessToken();

                CakeLog::write('debug', 'fblogin3.1');

                $oAuth2Client = $facebook->getOAuth2Client();

                CakeLog::write('debug', 'fblogin3.2');

                CakeLog::write('debug', 'fblogin4');

                //If it's not a long lived token
                if (!$accessToken->isLongLived()) {
                    //Try to exchange it for a long-lived one
                    try {
                        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                    } catch (Facebook\Exceptions\FacebookSDKException $e) {
                        //If it's failed we will keep it
                        $this->Session->setFlash('Nous n\'avons pas réussi à avoir un token de longue durée.', 'flash-warning');
                    }
                }

                //Get the access token metadata from /debug_token
                $tokenMetadata = $oAuth2Client->debugToken($accessToken);

                CakeLog::write('debug', 'fblogin5');

                //Extract useful information
                $userExternalId = $tokenMetadata->getUserId();
                $userAuthToken = $accessToken->getValue();

                CakeLog::write('debug', $userExternalId);
                //Try to get the related user from the database
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.external_id' => $userExternalId
                    )
                ));

                //pr($user);

                //If a user was found
                if ($user != NULL) {
                    CakeLog::write('debug', $user['User']['id']);

                    //Log user
                    $this->internLogin($userExternalId, $user['User']['mail'], $userAuthToken, $user['User']['username']);
                } else {
                    //If no user was found we try to get the information from Facebook about its profile
                    $response = $facebook->get('/me', $userAuthToken);
                    $user = $response->getGraphUser();

                    //Redirect to user/add
                    $firstName = $user->getFirstName();
                    $mail = $user->getField('email');

                    //Redirect to home
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'add',
                        '?' => array(
                            'externalId' => $userExternalId,
                            'username' => $firstName,
                            'mail' => $mail,
                            'authToken' => $userAuthToken
                    )));
                }
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                CakeLog::write('debug', $e->getMessage());

                //Set flash error
                $this->Session->setFlash($e->getMessage(), 'flash-danger');

                //Redirect to home
                $this->redirect('/');
            }
        }
    }

    public function login() {
        //Set flash error
        $this->Session->setFlash('Vous devez être authentifié', 'flash-warning');

        //Redirect to home
        $this->redirect('/');

        /*if ($this->request->is('get')) {
            //
            if (!$this->Auth->loggedIn()) {
                $facebook = new Facebook\Facebook([
                    'app_id' => SHARE_FACEBOOK_APP_ID,
                    'app_secret' => SHARE_FACEBOOK_APP_SECRET,
                    'default_graph_version' => 'v2.2',
                    'persistent_data_handler' => new CakePersistentDataHandler($this)
                ]);

                $helper = $facebook->getRedirectLoginHelper();

                $permissions = ['email']; // Optional permissions

                $callbackUrl = Router::url(array(
                    "controller" => "users",
                    "action" => "fbLogin"
                ), true);

                $loginUrl = $helper->getLoginUrl($callbackUrl.'/', $permissions);

                $this->set('loginUrl', $loginUrl);
            }
        }*/
    }

	public function add() {
        if ($this->request->is('post')) {
            try {
                //Try to save the user
                $userExternalId = $this->request->data['User']['external_id'];
                $username = $this->request->data['User']['username'];
                $mail = $this->request->data['User']['mail'];
                $authToken = $this->request->data['User']['auth_token'];

                //
                $this->internAdd($userExternalId, $username, $mail);

                //Log user
                $this->internLogin($userExternalId, $mail, $authToken, $username);
            } catch (ShareException $e) {
                //Set flash error
                $this->Session->setFlash($e->getMessage(), 'flash-danger');

                //Set validation errors
                $this->User->validationErrors = $e->getValidationErrors();
            }
        }
	}

    public function details($externalId = NULL) {
        if ($this->request->is('get', 'ajax')) {
            try {
                //Intern home
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

    public function shares() {
        if ($this->request->is('get', 'ajax')) {
            try {
                //Get user identifier
                $userExternalId = $this->Auth->user('external_id');
                //pr($userExternalId);

                //Share date
                $startDate = NULL;
                if (isset($this->params['url']['startDate'])/* && is_numeric($this->params['url']['startDate'])*/) {
                    $startDate = $this->params['url']['startDate'];
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
                $response = $this->internShares($userExternalId, $startDate, $page, $limit);

                //Send JSON response
                $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }

    public function requests() {
        if ($this->request->is('get', 'ajax')) {
            try {
                //Get user identifier
                $userExternalId = $this->Auth->user('external_id');
                //pr($userExternalId);

                //Intern requests
                $response = $this->internRequests($userExternalId);

                //Send JSON response
                $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }

    public function account() {

    }

    public function fbLogout() {
        CakeLog::write('debug', 'logout');
        if ($this->request->is('get')) {
            $this->redirect($this->Auth->logout());
        }
    }

    /*public function index() {
        //Get share types
		$users = $this->User->find('list');
        $this->set('users', $users);
    }*/

    /*public function delete() {
        if ($this->request->is('POST')) {
            $userId = $this->request->data['User']['id'];

            if (($userId != NULL) && $this->User->delete($userId, true)) {
                //Redirect
                $this->redirect($this->referer());
            } else {
                $this->Session->setFlash('Unable to delete this User.', 'default', array(), 'nok');
            }
        }
    }*/
}
