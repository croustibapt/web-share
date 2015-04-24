<?php
App::uses('ApiSharesController', 'Controller');

class SharesController extends ApiSharesController {
    //
    public function search() {
        $types = NULL;
        $region = NULL;
        $startDate = NULL;
        $endDate = NULL;
        $page = 1;

        //Post parameters
        if ($this->request->is('POST')) {
            $data = $this->request->data;

            //Start date
            $startTimestamp = $data['Share']['start'];
            $startDate = new DateTime();
            $startDate->setTimestamp($startTimestamp);

            //End date
            $endTimestamp = $data['Share']['end'];
            $endDate = new DateTime();
            $endDate->setTimestamp($endTimestamp);
        }

        //Page
        if (isset($this->params['url']['page']) && is_numeric($this->params['url']['page'])) {
            $page = $this->params['url']['page'];
        }
        
        //
        $response = $this->internSearch($types, $startDate, $endDate, $region, $page);

        //
        $this->set('response', $response);
    }
        
	public function add() {
        if ($this->request->is('POST')) {
            //Get user identifier
            $userExternalId = $this->getUserExternalId($this->request);
            $userId = $this->getUserId($userExternalId);

            //Get POST data
            $data = $this->request->data;

            try {
                $eventDate = date('Y-m-d H:i:s', $data['Share']['event_date']);

                //Intern add
                $response = $this->internAdd($userId, $data['Share']['latitude'], $data['Share']['longitude'], NULL, NULL,
                    $data['Share']['share_type_id'], $eventDate, $data['Share']['title'],
                    $data['Share']['price'], $data['Share']['places'], $data['Share']['waiting_time'],
                    $data['Share']['meet_place'], $data['Share']['limitations'], NULL,
                    $data['Share']['message'],
                    NULL, NULL);

                $shareId = $response['share_id'];
                $this->redirect('/share/details/'.$shareId);
            } catch (ShareException $e) {
                $this->Share->validationErrors = $e->getValidationErrors();
            }
        }

        //Get share types
        $shareTypes = $this->ShareType->find('list', array(
            'fields' => array('ShareType.id', 'ShareType.label', 'ShareTypeCategory.label'),
            'recursive' => 0
        ));
        $this->set('shareTypes', $shareTypes);
	}
    
    public function details($shareId = NULL) {
        $response = $this->internDetails($shareId);
        $this->set('share', $response);

        //Get user identifier
        $userExternalId = $this->getUserExternalId($this->request);

        $share['Share'] = $response;

        //Reformat share
        $share['Share']['id'] = $share['Share']['share_id'];
        unset($share['Share']['share_id']);

        $share['User'] = $share['Share']['user'];
        unset($share['Share']['user']);

        if (isset($share['Share']['requests'])) {
            $share['Request'] = $share['Share']['requests'];
            unset($share['Share']['requests']);
        }

        $canRequest = $this->canRequest($share, $userExternalId);
        $this->set('canRequest', $canRequest);

        //Own
        $doesUserOwnShare = $this->doesUserOwnShare($share, $userExternalId);
        $this->set('doesUserOwnShare', $doesUserOwnShare);

        //Request status
        $requestStatus = $this->getRequestStatus($share, $userExternalId);
        $this->set('requestStatus', $requestStatus);

        //Get comments
        $page = 1;
        if (isset($this->params['url']['page']) && is_numeric($this->params['url']['page'])) {
            $page = $this->params['url']['page'];
        }

        $commentsResponse = $this->internGetComments($shareId, $page);
        $this->set('comments', $commentsResponse);
    }

    /*public function delete() {
        if ($this->request->is('POST')) {
            $shareId = $this->request->data['Share']['id'];

            if (($shareId != NULL) && $this->Share->delete($shareId, true)) {
                //Redirect to index
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash("Unable to delete this Share.", 'default', array(), 'nok');
                $this->redirect($this->referer());
            }
        }
    }*/
}
