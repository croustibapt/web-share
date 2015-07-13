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
            $helper = $this->facebook->getRedirectLoginHelper();

            try {
                $accessToken = $helper->getAccessToken();

                //The OAuth 2.0 client handler helps us manage access tokens
                $oAuth2Client = $this->facebook->getOAuth2Client();

                if (!$accessToken->isLongLived()) {
                    //Exchanges a short-lived access token for a long-lived one
                    try {
                        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                    } catch (Facebook\Exceptions\FacebookSDKException $e) {
                        //TODO
                    }
                }

                //Get the access token metadata from /debug_token
                $tokenMetadata = $oAuth2Client->debugToken($accessToken);
                //pr($tokenMetadata);

                $userExternalId = $tokenMetadata->getUserId();
                $userAuthToken = $accessToken->getValue();

                //Try to get the related user
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.external_id' => $userExternalId
                    )
                ));

                //If a user was found
                if ($user != NULL) {
                    //Save session
                    $this->saveAuth($userExternalId, $user['User']['mail'], $userAuthToken, $user['User']['username']);

                    //Redirect to referer
                    $this->redirect($this->referer());
                } else {
                    $response = $this->facebook->get('/me', $userAuthToken);
                    $user = $response->getGraphUser();
                    //pr($user);

                    //Redirect to user/add
                    $firstName = $user->getFirstName();
                    $mail = $user->getField('email');

                    $this->set('externalId', $userExternalId);
                    $this->set('username', $firstName);
                    $this->set('mail', $mail);
                    $this->set('authToken', $userAuthToken);
                }
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                //TODO

                //Redirect to home
                $this->redirect('/');
            }
        } else if ($this->request->is('POST')) {
            //pr($this->request->data);

            try {
                //Try to save the user
                $userExternalId = $this->request->data['User']['external_id'];
                $username = $this->request->data['User']['username'];
                $mail = $this->request->data['User']['mail'];
                $authToken = $this->request->data['User']['auth_token'];

                $response = $this->internAdd($userExternalId, $username, $mail);

                //If it succeeded
                if ($response != NULL) {
                    //Save auth session
                    $this->saveAuth($userExternalId, $mail, $authToken, $username);

                    //Redirect to home
                    $this->redirect('/');
                }
            } catch (ShareException $e) {
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
