<?php
App::uses('ApiSharesController', 'Controller');

class SharesController extends ApiSharesController {
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('home', 'search', 'details');
    }

    //
    public function home() {
        //pr($this->Auth->user('id'));
    }

    //
    public function search() {
        if ($this->request->is('get')) {
            $data = $this->request->query;
            //pr($data);

            //Place id
            if (isset($data['place_id']) && (strlen($data['place_id']) > 0)) {
                $placeId = $data['place_id'];
                $this->set('placeId', $placeId);
            }

            //Map position
            if (isset($data['lat']) && isset($data['lng']) && isset($data['zoom'])) {
                $this->set('lat', $data['lat']);
                $this->set('lng', $data['lng']);
                $this->set('zoom', $data['zoom']);
            }

            //Get period
            if (isset($data['period'])) {
                $period = $data['period'];
                $this->set('period', $period);
            }

            //Share type category
            if (isset($data['share_type_category'])) {
                $shareTypeCategory = $data['share_type_category'];
                $this->set('shareTypeCategory', $shareTypeCategory);
            }

            //Share type
            if (isset($data['share_type'])) {
                $shareType = $data['share_type'];
                $this->set('shareType', $shareType);
            }
        }
    }

    //
	public function add() {
        $shareTypeId = '';

        if ($this->request->is('POST')) {
            //Get user identifier
            $userExternalId = $this->getUserExternalId($this->request);
            $userId = $this->getUserId($userExternalId);

            //Get POST data
            $data = $this->request->data;
            $shareTypeId = $data['Share']['share_type_id'];
            //pr($data);

            try {
                //Intern add
                $response = $this->internAdd($userId, $data['Share']['latitude'], $data['Share']['longitude'], NULL, NULL,
                    $shareTypeId, $data['Share']['event_date'], $data['Share']['event_time'], $data['Share']['title'],
                    $data['Share']['price'], $data['Share']['places'], $data['Share']['waiting_time'],
                    $data['Share']['meet_place'], $data['Share']['limitations'], NULL, NULL, $data['Share']['message'], NULL, NULL);

                $shareId = $response['share_id'];
                $this->redirect('/share/details/'.$shareId);
            } catch (ShareException $e) {
                $this->Share->validationErrors = $e->getValidationErrors();
            }
        }

        $this->set('shareTypeId', $shareTypeId);
	}

    //
    public function details($shareId = NULL) {
        if ($shareId != NULL) {
            //Get related Share
            $share = $this->Share->find('first', array(
                'conditions' => array(
                    'Share.id' => $shareId
                )
            ));

            //Check if exists
            if ($share != NULL) {
                $this->set('shareId', $share['Share']['id']);
                $this->set('shareStatus', $share['Share']['status']);
                $this->set('shareUserExternalId', $share['User']['external_id']);

                //Get user identifier
                $userExternalId = $this->getUserExternalId($this->request);

                //User can request?
                $canRequest = $this->canRequest($share, $userExternalId);
                $this->set('canRequest', $canRequest);

                //Is expired?
                $isExpired = $this->isShareExpired($share);
                $this->set('isExpired', $isExpired);

                //Is places left?
                $isPlacesLeft = $this->isPlacesLeft($share);
                $this->set('isPlacesLeft', $isPlacesLeft);

                //Own
                $doesUserOwnShare = $this->doesUserOwnShare($share, $userExternalId);
                $this->set('doesUserOwnShare', $doesUserOwnShare);

                //Request status
                $requestStatus = $this->getRequestStatus($share, $userExternalId);
                $this->set('requestStatus', $requestStatus);
            } else {
                //Set warning
                $this->Session->setFlash('No share found', 'flash-warning');

                $this->redirect('/');
            }
        } else {
            //Set warning
            $this->Session->setFlash('Bad share identifier', 'flash-warning');

            $this->redirect('/');
        }
    }

    public function cancel($shareId = NULL) {
        if ($this->request->is('POST')) {
            $data = $this->request->data;

            //Get user identifier
            $userExternalId = $this->getUserExternalId($this->request);

            try {
                //Intern add
                $this->internCancel($shareId, $data['Share']['reason'], $data['Share']['message'], $userExternalId);
            } catch (ShareException $e) {
                //Set warning
                $this->Session->setFlash($e->getMessage(), 'flash-error');

                $this->Share->validationErrors = $e->getValidationErrors();
            }
        }

        //Redirect
        $this->redirect($this->referer());
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
