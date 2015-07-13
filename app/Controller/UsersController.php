<?php
App::uses('ApiUsersController', 'Controller');

class UsersController extends ApiUsersController {
	public $uses = array('User');

    public function index() {
        //Get share types
		$users = $this->User->find('list');
        $this->set('users', $users);
    }

	public function add() {
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

                //Extract useful information
                $userExternalId = $tokenMetadata->getUserId();
                $userAuthToken = $accessToken->getValue();

                //Try to get the related user from the database
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.external_id' => $userExternalId
                    )
                ));

                //If a user was found
                if ($user != NULL) {
                    //Set flash error
                    $this->Session->setFlash('Bienvenue '.$user['User']['username'], 'flash-success');

                    //Save session
                    $this->saveAuth($userExternalId, $user['User']['mail'], $userAuthToken, $user['User']['username']);

                    //Redirect to referer
                    $this->redirect($this->referer());
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
        } else if ($this->request->is('POST')) {
            try {
                //Try to save the user
                $userExternalId = $this->request->data['User']['external_id'];
                $username = $this->request->data['User']['username'];
                $mail = $this->request->data['User']['mail'];
                $authToken = $this->request->data['User']['auth_token'];

                $this->internAdd($userExternalId, $username, $mail);

                //Save auth session
                $this->saveAuth($userExternalId, $mail, $authToken, $username);

                //Set flash success
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
            $this->redirect('/');
        }
    }

    public function logout() {
        if ($this->request->is('GET')) {
            $this->invalidateLocalUserSession();
        }

        //Redirect to referer
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
