<?php
App::uses('AppHelper', 'View/Helper');

class FacebookSessionHelper extends AppHelper {
    /*public function isAuthenticated($context) {
        $userExternalId = $context->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_EXTERNAL_ID);
        $mail = $context->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_MAIL);
        $authToken = $context->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_TOKEN);
        $username = $context->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_USERNAME);

        $isAuthenticated = (($userExternalId != NULL) && ($mail != NULL) && ($authToken != NULL) && ($username !=
                NULL));

        return $isAuthenticated;
    }

    public function getAuthToken($context) {
        $authToken = $context->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_TOKEN);
        return $authToken;
    }

    public function getExternalId($context) {
        $userExternalId = $context->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_EXTERNAL_ID);
        return $userExternalId;
    }

    public function getUsername($context) {
        $username = $context->Session->read(SHARE_LOCAL_USER_SESSION_PREFIX.'.'.SHARE_HEADER_AUTH_USERNAME);
        return $username;
    }*/
}