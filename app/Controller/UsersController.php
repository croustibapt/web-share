<?php
App::uses('ApiUsersController', 'Controller');

class UsersController extends ApiUsersController {
	public $uses = array('User');

    public function index() {
        //Get share types
		$users = $this->User->find('list');
        $this->set('users', $users);
    }

	public function add($userExternalId = NULL, $authToken = NULL, $username = NULL, $mail = NULL) {
        if ($this->request->is('POST')) {
            try {
                //Try to save the user
                $response = $this->internAdd($userExternalId, $this->request->data['User']['username'], $mail);

                //If it succeeded
                if ($response != NULL) {
                    //Save auth session
                    $this->saveAuthSession($userExternalId, $mail, $authToken, $username);

                    //Redirect to home
                    $this->redirect('/');
                }
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        }

        $this->set('userExternalId', $userExternalId);
        $this->set('username', $username);
        $this->set('mail', $mail);
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
        if ($this->isLocalUserSessionAuthenticated()) {
            if ($this->request->is('GET')) {
                try {
                    //Intern home
                    $user = $this->interHome(true);

                    $this->set('user', $user);
                } catch (ShareException $e) {
                    $this->set('error', $e);
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    public function authenticate() {
        if ($this->request->is('POST')) {
            //User external id
            $userExternalId = NULL;
            if (isset($this->request->data['userExternalId'])) {
                $userExternalId = urldecode($this->request->data['userExternalId']);
            }

            //User auth token
            $userAuthToken = NULL;
            if (isset($this->request->data['userAuthToken'])) {
                $userAuthToken = urldecode($this->request->data['userAuthToken']);
            }

            //User mail
            $userMail = NULL;
            if (isset($this->request->data['userMail'])) {
                $userMail = urldecode($this->request->data['userMail']);
            }

            //Username
            $username = NULL;
            if (isset($this->request->data['username'])) {
                $username = urldecode($this->request->data['username']);
            }

            //Check parameters
            if (($userExternalId != NULL) && ($userAuthToken != NULL) && ($userMail != NULL)) {
                //Try to get the related user
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.external_id' => $userExternalId
                    )
                ));

                //If a user was found
                if ($user != NULL) {
                    //Save session
                    $this->saveAuthSession($userExternalId, $userMail, $userAuthToken, $user['User']['username']);

                    //Redirect to referer
                    $this->redirect($this->referer());
                } else {
                    //Redirect to user/add
                    $this->redirect(array('controller' => 'users', 'action' => 'add', $userExternalId, $userAuthToken, $username, $userMail));
                }
            }
        }
    }

    public function logout() {
        if ($this->request->is('GET')) {
            $this->invalidateLocalUserSession();
        }

        //Redirect to referer
        $this->redirect($this->referer());
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
