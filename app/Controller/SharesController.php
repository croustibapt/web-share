<?php
App::uses('ApiSharesController', 'Controller');

class SharesController extends ApiSharesController {
    //
    public function home() {

    }
    
    //
    public function search() {
        $period = 'all';

        $shareTypeCategory = -1;
        $shareType = -1;

        $address = '';
        $viewPort = NULL;

        if ($this->request->is('GET')) {
            $data = $this->request->query;
            //pr($data);

            //Get start and end date
            $period = $data['period'];

            //Share type category
            $shareTypeCategory = $data['share_type_category'];

            //Share type
            if (isset($data['share_type'])) {
                $shareType = $data['share_type'];
            }

            $address = $data['address'];

            //ViewPort
            $viewPortObject = json_decode(urldecode($data['viewport']));
            //pr($viewPortObject);

            if ($viewPortObject != NULL) {
                $viewPort['northeast']['lat'] = $viewPortObject->za->j;
                $viewPort['northeast']['lng'] = $viewPortObject->ra->A;
                $viewPort['southwest']['lat'] = $viewPortObject->za->A;
                $viewPort['southwest']['lng'] = $viewPortObject->ra->j;
            }
        }

        //
        if ($viewPort == NULL) {
            if ($address != '') {
                $cityGeocodingUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . SHARE_GOOGLE_MAPS_API_KEY;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $cityGeocodingUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $geoCodingResponse = json_decode(curl_exec($ch), true);

                if ($geoCodingResponse != null) {
                    $results = $geoCodingResponse['results'];

                    if (count($results) > 0) {
                        $bestResult = $results[0];

                        $address = $bestResult['formatted_address'];

                        $viewPort['northeast']['lat'] = $bestResult['geometry']['viewport']['northeast']['lat'];
                        $viewPort['northeast']['lng'] = $bestResult['geometry']['viewport']['northeast']['lng'];
                        $viewPort['southwest']['lat'] = $bestResult['geometry']['viewport']['southwest']['lat'];
                        $viewPort['southwest']['lng'] = $bestResult['geometry']['viewport']['southwest']['lng'];
                    }
                }
            } else {
                $viewPort['northeast']['lat'] = 41.3423276;
                $viewPort['northeast']['lng'] = 9.55979339999999;
                $viewPort['southwest']['lat'] = 51.0891658;
                $viewPort['southwest']['lng'] = -5.14214190000007;
            }
        }

        $this->set('period', $period);

        $this->set('shareTypeCategory', $shareTypeCategory);
        $this->set('shareType', $shareType);

        $this->set('address', $address);
        //pr($viewPort);
        $this->set('viewPort', $viewPort);
    }
        
	public function add() {
        if ($this->request->is('POST')) {
            //Get user identifier
            $userExternalId = $this->getUserExternalId($this->request);
            $userId = $this->getUserId($userExternalId);

            //Get POST data
            $data = $this->request->data;
            //pr($data);

            try {
                //Intern add
                $response = $this->internAdd($userId, $data['Share']['latitude'], $data['Share']['longitude'], NULL, NULL,
                    $data['Share']['share_type_id'], $data['Share']['event_date'], $data['Share']['event_time'], $data['Share']['title'],
                    $data['Share']['price'], $data['Share']['places'], $data['Share']['waiting_time'],
                    $data['Share']['meet_place'], $data['Share']['limitations'],
                    $data['Share']['message'],
                    NULL, NULL);

                $shareId = $response['share_id'];
                $this->redirect('/share/details/'.$shareId);
            } catch (ShareException $e) {
                $this->Share->validationErrors = $e->getValidationErrors();
            }
        }
	}
    
    public function details($shareId = NULL) {
        $response = $this->internDetails($shareId);
        //$this->set('share', $response);
        $this->set('shareId', $response['share_id']);
        $this->set('shareUserExternalId', $response['user']['external_id']);

        //Get user identifier
        $userExternalId = $this->getUserExternalId($this->request);

        $share['Share'] = $response;

        //Reformat share
        $share['Share']['id'] = $share['Share']['share_id'];
        unset($share['Share']['share_id']);

        $share['User'] = $share['Share']['user'];
        unset($share['Share']['user']);

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
