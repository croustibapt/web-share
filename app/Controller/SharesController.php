<?php
App::uses('ApiSharesController', 'Controller');

class SharesController extends ApiSharesController {
    //
    public function home() {

    }
    
    //
    public function search() {
        $date = 'all';

        $shareTypeCategory = -1;
        $shareType = -1;

        $searchNELatitude = NULL;
        $searchNELongitude = NULL;
        $searchSWLatitude = NULL;
        $searchSWLongitude = NULL;

        $address = '';

        if ($this->request->is('POST')) {
            $data = $this->request->data;

            //Get start and end date
            $date = $data['Share']['date'];

            //
            $shareTypeCategory = $data['Share']['share_type_category'];
            
            if (isset($data['Share']['share_type'])) {
                $shareType = $data['Share']['share_type'];
            }

            $address = $data['Share']['address'];

            //Location
            $viewport = json_decode(urldecode($data['Share']['viewport']));
            //pr($viewport);

            if ($viewport != '') {
                $searchSWLatitude = $viewport->za->A;
                $searchSWLongitude = $viewport->qa->j;

                $searchNELatitude = $viewport->za->j;
                $searchNELongitude = $viewport->qa->A;
            } else {
                if ($address != '') {
                    $cityGeocodingUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . SHARE_GOOGLE_MAPS_API_KEY;

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $cityGeocodingUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $geocodingResponse = json_decode(curl_exec($ch), true);

                    if ($geocodingResponse != null) {
                        $results = $geocodingResponse['results'];

                        if (count($results) > 0) {
                            $bestResult = $results[0];

                            $address = $bestResult['formatted_address'];

                            $searchNELatitude = $bestResult['geometry']['viewport']['northeast']['lat'];
                            $searchNELongitude = $bestResult['geometry']['viewport']['northeast']['lng'];

                            $searchSWLatitude = $bestResult['geometry']['viewport']['southwest']['lat'];
                            $searchSWLongitude = $bestResult['geometry']['viewport']['southwest']['lng'];
                        }
                    }
                }
            }

            /*//Search zoom
            $searchZoom = $data['Share']['search_zoom'];*/
        }

        $this->set('date', $date);

        $this->set('shareTypeCategory', $shareTypeCategory);
        $this->set('shareType', $shareType);

        $this->set('address', $address);

        $this->set('searchNELatitude', $searchNELatitude);
        $this->set('searchNELongitude', $searchNELongitude);
        $this->set('searchSWLatitude', $searchSWLatitude);
        $this->set('searchSWLongitude', $searchSWLongitude);
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
