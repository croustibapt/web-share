<?php

App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class ShareAuthenticate extends BaseAuthenticate {
    //
    public function authenticate(CakeRequest $request, CakeResponse $response) {
        CakeLog::write('debug', 'ShareAuthenticate start');

        $sessionUser = false;

        if ($request != NULL) {
            $userModel = ClassRegistry::init('User');

            //Get user external id
            $userExternalId = $request->params[SHARE_HEADER_AUTH_EXTERNAL_ID];
            $userToken = $request->params[SHARE_HEADER_AUTH_TOKEN];

            if (($userExternalId != NULL) && ($userToken != NULL)) {
                //Get user
                $user = $userModel->find('first', array(
                    'fields' => array('User.id, User.external_id, User.mail, User.username, User.last_check_credentials_date'),
                    'conditions' => array(
                        'User.external_id' => $userExternalId
                    )
                ));
                $user['User']['token'] = $userToken;

                //If found
                if ($user != NULL) {
                    //Get interval between now and last check credentials date
                    $lastCheckCredentialsDate = date_create($user['User']['last_check_credentials_date']);

                    $now = new DateTime();
                    $nbSeconds = strtotime($now->format('Y-m-d H:i:s')) - strtotime($lastCheckCredentialsDate->format('Y-m-d H:i:s'));

                    //Check if we need to check credentials
                    if ($nbSeconds > SHARE_CHECK_CREDENTIALS_MAX_INTERVAL) {
                        //Initialize Facebook object
                        $facebook = new Facebook\Facebook([
                            'app_id' => SHARE_FACEBOOK_APP_ID,
                            'app_secret' => SHARE_FACEBOOK_APP_SECRET,
                            'default_graph_version' => 'v2.2'
                        ]);

                        //The OAuth 2.0 client handler helps us manage access tokens
                        $oAuth2Client = $facebook->getOAuth2Client();
                        $tokenMetadata = $oAuth2Client->debugToken($userToken);

                        //pr($tokenMetadata);

                        try {
                            //Validation (these will throw FacebookSDKException's when they fail)
                            $tokenMetadata->validateAppId(SHARE_FACEBOOK_APP_ID);
                            $tokenMetadata->validateUserId($userExternalId);
                            $tokenMetadata->validateExpiration();

                            CakeLog::write('debug', 'Facebook validation succeeded');

                            $sessionUser = $user['User'];

                            //Update last credential date
                            //TODO: check status
                            $userModel->id = $user['User']['id'];
                            $userModel->saveField('last_check_credentials_date', $now->format('Y-m-d H:i:s'));
                        } catch (Facebook\Exceptions\FacebookSDKException $ex) {
                            //Session not valid, Graph API returned an exception with the reason.
                            //$this->invalidateLocalUserSession();
                        }
                    } else {
                        //By pass Facebook check credentials
                        $sessionUser = $user['User'];
                    }
                }
            }
        }

        return $sessionUser;
    }

    /*public function getUser(CakeRequest $request) {
        //Get user
        $userModel = ClassRegistry::init('User');
        $user = $userModel->find('first');

        return $user;
    }*/
}