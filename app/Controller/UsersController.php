<?php
App::uses('ApiUsersController', 'Controller');

class UsersController extends ApiUsersController {
	public $uses = array('User');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('fbLogin', 'login');
    }

    public function index() {
        //Get share types
		$users = $this->User->find('list');
        $this->set('users', $users);
    }

    public function fbLogin() {
        if ($this->request->is('GET')) {
            //Get redirect helper
            $helper = $this->facebook->getRedirectLoginHelper();

            try {
                //Try to get the associated token
                $accessToken = $helper->getAccessToken();
                $oAuth2Client = $this->facebook->getOAuth2Client();

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

                CakeLog::write('auth', $tokenMetadata);

                //Extract useful information
                $userExternalId = $tokenMetadata->getUserId();
                $userAuthToken = $accessToken->getValue();

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

                    //Set flash success TEMP
                    $this->Session->setFlash('Bienvenue '.$user['User']['username'], 'flash-success');

                    //Save session
                    //$this->saveAuth($userExternalId, $user['User']['mail'], $userAuthToken, $user['User']['username']);
                    $this->request->addParams(array(
                        SHARE_HEADER_AUTH_EXTERNAL_ID => $userExternalId,
                        SHARE_HEADER_AUTH_MAIL => $user['User']['mail'],
                        SHARE_HEADER_AUTH_TOKEN => $userAuthToken,
                        SHARE_HEADER_AUTH_USERNAME => $user['User']['username']
                    ));

                    if ($this->Auth->login()) {
                        CakeLog::write('debug', 'login succeeded');
                        return $this->redirect($this->Auth->redirectUrl());
                    } else {
                        CakeLog::write('debug', 'login failed');
                    }
                } else {
                    //If no user was found we try to get the information from Facebook about its profile
                    $response = $this->facebook->get('/me', $userAuthToken);
                    $user = $response->getGraphUser();

                    //Redirect to user/add
                    $firstName = $user->getFirstName();
                    $mail = $user->getField('email');

                    //Set form input values
                    $this->set('externalId', $userExternalId);
                    $this->set('username', $firstName);
                    $this->set('mail', $mail);
                    $this->set('authToken', $userAuthToken);
                }
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                //Set flash error
                $this->Session->setFlash($e->getMessage(), 'flash-danger');

                //Redirect to home
                $this->redirect('/');
            }
        }
    }

    public function login() {

    }

	public function add() {
        if ($this->request->is('POST')) {
            try {
                //Try to save the user
                $userExternalId = $this->request->data['User']['external_id'];
                $username = $this->request->data['User']['username'];
                $mail = $this->request->data['User']['mail'];
                $authToken = $this->request->data['User']['auth_token'];

                $this->internAdd($userExternalId, $username, $mail);

                //Save auth session
                //$this->saveAuth($userExternalId, $mail, $authToken, $username);

                //Set flash success TEMP
                $this->Session->setFlash('Bienvenue '.$username, 'flash-success');

                //Redirect to home
                $this->redirect('/');
            } catch (ShareException $e) {
                //Set flash error
                $this->Session->setFlash($e->getMessage(), 'flash-danger');

                //Set validation errors
                $this->User->validationErrors = $e->getValidationErrors();
            }
        } else {
            //Redirect to home
            $this->redirect('/');
        }
	}

    public function details($externalId = NULL) {
        if ($this->request->is('GET')) {
            try {
                //Intern home
                $user = $this->internDetails($externalId);

                $this->set('user', $user);
            } catch (ShareException $e) {
                $this->set('error', $e);
            }
        }
    }

    public function home() {
        if (!$this->isLocalUserSessionAuthenticated()) {
            //Set flash error
            $this->Session->setFlash('You need to be authenticated', 'flash-danger');

            $this->redirect('/');
        }
    }

    public function logout() {
        if ($this->isLocalUserSessionAuthenticated()) {
            if ($this->request->is('GET')) {
                $this->invalidateLocalUserSession();
            }

            //Redirect to home
            $this->redirect('/');
        }

        //Set flash error
        $this->Session->setFlash('You need to be authenticated', 'flash-danger');

        //Redirect to home
        $this->redirect('/');
    }

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
