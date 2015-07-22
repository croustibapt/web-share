<?php

App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class ShareAuthenticate extends BaseAuthenticate {
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
    
    public function authenticate(\CakeRequest $request, \CakeResponse $response) {
        return true;
    }
    
    public function getUser(CakeRequest $request) {
        CakeLog::write('debug', 'getUser');        
        
        $returnUser = NULL;
                
        if ($request != NULL) {
            //Get user external id
            $userExternalId = $this->extractUserAuthValue($request, SHARE_HEADER_AUTH_EXTERNAL_ID);
            //Get token
            $userToken = $this->extractUserAuthValue($request, SHARE_HEADER_AUTH_TOKEN);

            if (($userExternalId != NULL) && ($userToken != NULL)) {
                //Get user
                $user = $this->User->find('first', array(
                    'fields' => array('User.id, User.last_check_credentials_date'),
                    'conditions' => array(
                        'User.external_id' => $userExternalId
                    )
                ));

                //If found
                if ($user != NULL) {
                    //Get interval between now and last check credentials date
                    $lastCheckCredentialsDate = date_create($user['User']['last_check_credentials_date']);

                    $now = new DateTime();
                    $nbSeconds = strtotime($now->format('Y-m-d H:i:s')) - strtotime($lastCheckCredentialsDate->format('Y-m-d H:i:s'));

                    //Check if we need to check credentials
                    if ($nbSeconds > SHARE_CHECK_CREDENTIALS_MAX_INTERVAL) {
                        //The OAuth 2.0 client handler helps us manage access tokens
                        $oAuth2Client = $this->facebook->getOAuth2Client();
                        $tokenMetadata = $oAuth2Client->debugToken($userToken);

                        //pr($tokenMetadata);

                        try {
                            //Validation (these will throw FacebookSDKException's when they fail)
                            $tokenMetadata->validateAppId(SHARE_FACEBOOK_APP_ID);
                            $tokenMetadata->validateUserId($userExternalId);
                            $tokenMetadata->validateExpiration();

                            $returnUser = $user;

                            //Update last credential date
                            //TODO: check status
                            $this->User->id = $user['User']['id'];
                            $this->User->saveField('last_check_credentials_date', $now->format('Y-m-d H:i:s'));
                        } catch (Facebook\Exceptions\FacebookSDKException $ex) {
                            //Session not valid, Graph API returned an exception with the reason.
                            $this->invalidateLocalUserSession();
                        }
                    } else {
                        //By pass Facebook check credentials
                        $returnUser = $user;
                    }
                }
            }
        }
        
        return $returnUser;
    }
}