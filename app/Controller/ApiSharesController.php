<?php
App::uses('AppController', 'Controller');
App::uses('ShareException', 'Lib');

class ApiSharesController extends AppController {
	public $uses = array('Share', 'ShareType', 'ShareTypeCategory', 'User', 'Tag', 'Comment', 'Request');
    
    protected function getShareTypeId($shareTypeLabel = NULL) {
        $shareTypeId = NULL;
        
        if ($shareTypeLabel != NULL) {
            $shareType = $this->ShareType->find('first', array(
                'conditions' => array(
                    'ShareType.label' => $shareTypeLabel
                )
            ));
            
            $shareTypeId = $shareType['ShareType']['id'];
        }
        
        return $shareTypeId;
    }

    protected function internSearch($types = NULL, $expiryDate = NULL, $region = NULL, $page = 1) {
        //Main query
        $sqlPrefix = "SELECT *, X(Share.location) as latitude, Y(Share.location) as longitude, ShareTypeCategory.label, (SELECT COUNT(Request.id) FROM requests Request WHERE Request.share_id = Share.id AND Request.status = 1) AS participation_count";
        $sql = " FROM shares Share, users User, share_types ShareType, share_type_categories ShareTypeCategory WHERE Share.user_id = User.id AND Share.share_type_id = ShareType.id AND ShareType.share_type_category_id = ShareTypeCategory.id";

        //Types
        if (($types != NULL) && (count($types) > 0)) {
            $shareTypesIds = "(";
            $nbTypes = count($types);

            for ($i = 0; $i < $nbTypes; $i++) {
                if ($i > 0) {
                    $shareTypesIds .= ", ";
                }

                $shareTypesIds .= $types[$i];
            }

            $shareTypesIds .= ")";

            $sql .= ' AND Share.share_type_id IN '.$shareTypesIds;
        }

        //Expiry date
        if ($expiryDate != NULL) {
            $sql .= ' AND Share.event_date >= '.$expiryDate->format('Y-m-d H:i:s');
        }

        //Region
        if ($region != NULL) {
            $sql .= " AND Contains(GeometryFromText('POLYGON((";

            //Loop on coordinates
            $nbCoordinates = count($region);

            for ($i = 0; $i < $nbCoordinates; $i++) {
                $coordinate = $region[$i];
                $sql .= $coordinate['latitude']." ".$coordinate['longitude'].', ';
            }

            //Re-add first point
            if ($nbCoordinates > 0) {
                $firstCoordinate = $region[0];
                $sql .= $firstCoordinate['latitude']." ".$firstCoordinate['longitude'];
            }

            //End
            $sql .= "))', 4326), Share.location)";
        }

        //Limit
        $sqlLimit = " LIMIT ".SHARE_SEARCH_LIMIT;
        
        //Offset
        $offset = ceil(($page - 1) / SHARE_SEARCH_LIMIT);
        $sqlOffset = " OFFSET ".$offset;

        $query = $sqlPrefix.$sql." GROUP BY Share.id".$sqlLimit.$sqlOffset.";";

        /*echo json_encode($query);
        exit();*/

        //Execute request
        $shares = $this->Share->query($query);

        $response['page'] = $page;
        $response['results'] = array();
        $shareIndex = 0;
        foreach ($shares as $share) {
            $response['results'][$shareIndex++] = $this->formatShare($share);
        }

        //Total results count
        $totalResults = 0;
        $totalShares = $this->Share->query("SELECT COUNT(Share.id) as total_results".$sql.";");
        if (count($totalShares) > 0) {
            $totalResults = $totalShares[0][0]['total_results'];
        }

        $response['total_results'] = $totalResults;
        $response['total_pages'] = ceil($totalResults / SHARE_SEARCH_LIMIT);

        return $response;
    }

    public function apiSearch() {
        if ($this->request->is('POST')) {
            //Decode data
            $data = $this->request->input('json_decode', true);

            //Get Share type
            $types = NULL;
            if (isset($data['types']) && is_array($data['types'])) {
                $types = $data['types'];
            }

            //Get Expiry
            $expiryDate = NULL;
            if (isset($this->params['url']['expiry'])) {
                $expiryTimestamp = $this->params['url']['expiry'];

                $expiryDate = new DateTime();
                $expiryDate->setTimestamp($expiryTimestamp);
            }

            //Get region
            $region = NULL;
            if (isset($data['region']) && is_array($data['region'])) {
                $region = $data['region'];
            }

            //Page
            $page = 1;
            if (isset($this->params['url']['page']) && is_numeric($this->params['url']['page'])) {
                $page = $this->params['url']['page'];
            }

            $response = $this->internSearch($types, $expiryDate, $region, $page);

            //Send JSON response
            $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
        }
    }
    
    protected function internAdd($userId = NULL, $latitude = NULL, $longitude = NULL, $city = NULL, $zipCode = NULL, $shareTypeId = NULL, $eventDate = NULL, $title = NULL, $price = NULL, $places = NULL, $waitingTime = NULL, $meetPlace = NULL, $limitations = NULL, $supplement = NULL, $message = NULL, $accuracy = NULL, $radius = NULL) {
        $response = NULL;
        
        //Check credentials
        if ($this->checkCredentials($this->request)) {
            $cityGeocodingUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&result_type=locality|postal_code&key=".SHARE_GOOGLE_MAPS_API_KEY;

            /*echo json_encode($cityGeocodingUrl);
            exit();*/

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $cityGeocodingUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $geocodingCityResponse = json_decode(curl_exec($ch), true);

            /*echo json_encode($geocodingCityResponse);
            exit();*/
            
            foreach ($geocodingCityResponse['results'] as $result) {
                if (count($result['address_components']) > 0) {
                    $firstAddressComponent = $result['address_components'][0];
                    $adressTypes = $firstAddressComponent['types'];

                    if (in_array('postal_code', $adressTypes)) {
                        $zipCode = $firstAddressComponent['long_name'];
                    } else if (in_array('locality', $adressTypes)) {
                        $city = $firstAddressComponent['long_name'];
                    }
                }
            }

            //User
            $dataShare['Share']['user_id'] = $userId;

            //Transform data
            if ($shareTypeId != -1) {
                $dataShare['Share']['share_type_id'] = $shareTypeId;
            }
            $dataShare['Share']['event_date'] = $eventDate;
            $dataShare['Share']['title'] = $title;
            $dataShare['Share']['price'] = $price;
            $dataShare['Share']['places'] = $places;
            $dataShare['Share']['status'] = SHARE_STATUS_OPENED;
            $dataShare['Share']['waiting_time'] = $waitingTime;
            $dataShare['Share']['meet_place'] = $meetPlace;
            $dataShare['Share']['limitations'] = $limitations;
            $dataShare['Share']['supplement'] = $supplement;
            $dataShare['Share']['message'] = $message;
            $dataShare['Share']['latitude'] = $latitude;
            $dataShare['Share']['longitude'] = $longitude;
            $dataShare['Share']['city'] = $city;
            $dataShare['Share']['zip_code'] = $zipCode;
            $dataShare['Share']['accuracy'] = $accuracy;
            $dataShare['Share']['radius'] = $radius;

            /*echo json_encode($dataShare);
            exit();*/

            //If Share was successfuly saved
            if ($this->Share->saveAssociated($dataShare)) {
                //Get it back
                $shareId = $this->Share->id;
                $share = $this->Share->find('first', array(
                    'conditions' => array(
                        'Share.id' => $shareId
                    )
                ));

                //Prepare response
                $response['share_id'] = $shareId;
                $response['created'] = $share['Share']['created'];
                $response['modified'] = $share['Share']['modified'];
                $response['city'] = $share['Share']['city'];
                $response['zip_code'] = $share['Share']['zip_code'];
            } else {
                $validationErrors = $this->Share->validationErrors;

                //Check validation errors
                if ($validationErrors !== NULL) {
                    throw new ShareException(SHARE_STATUS_CODE_PRECONDITION_FAILED, SHARE_ERROR_CODE_VALIDATION_FAILED, "Share validation failed", $validationErrors);
                } else {
                    throw new ShareException(SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR, SHARE_ERROR_CODE_SAVE_FAILED, "Share save failed");
                }
            }
        } else {
            throw new ShareException(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS, "Bad credentials");
        }
        
        return $response;
    }

	public function apiAdd() {
        if ($this->request->is('PUT')) {
            //Get user external identifier
            $userExternalId = $this->getUserExternalId($this->request);
            $userId = $this->getUserId($userExternalId);
            
            /*echo json_encode($userExternalId);
            exit();*/
            
            //Get data
            $data = $this->request->input('json_decode', true);
            
            //Check empty fields
            $this->checkField($data, 'supplement');
            $this->checkField($data, 'message');
            $this->checkField($data, 'city');
            $this->checkField($data, 'zip_code');
            $this->checkField($data, 'limitations');
            $this->checkField($data, 'meet_place');
            $this->checkField($data, 'image_url');
            
            try {
                //Intern add
                $response = $this->internAdd($userId, $data['latitude'], $data['longitude'], NULL, NULL, $data['share_type_id'], $data['event_date'], $data['title'], $data['price'], $data['places'], $data['waiting_time'], $data['meet_place'], $data['limitations'], $data['supplement'], $data['message'], $data['accuracy'], $data['radius']);

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_CREATED, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        }
	}
    
    protected function internDetails($shareId = NULL) {
        $response = NULL;
        
        //Check parameter
        if ($shareId != NULL) {
            //Find share
            /*$share = $this->Share->find('first', array(
                'conditions' => array(
                    'Share.id' => $shareId
                )
            ));*/
            $sql = "SELECT *, X(Share.location) as latitude, Y(Share.location) as longitude, (SELECT COUNT(Request.id) FROM requests Request WHERE Request.share_id = Share.id AND Request.status = 1) AS participation_count FROM shares AS Share, users AS User, share_types AS ShareType, share_type_categories ShareTypeCategory WHERE Share.user_id = User.id AND Share.share_type_id = ShareType.id AND ShareType.share_type_category_id = ShareTypeCategory.id AND Share.id = ".$shareId." LIMIT 1;";
            
            $shares = $this->Share->query($sql);
            $share = $shares[0];
            
            /*echo json_encode($share);
            exit();*/
            
            //If it's well formatted
            if ($share != NULL) {
                //Format response
                $response = $this->formatShare($share, false, true);
            } else {
                throw new ShareException(SHARE_STATUS_CODE_NOT_FOUND, SHARE_ERROR_CODE_RESOURCE_NOT_FOUND, "Share not found");
            }
        } else {
            throw new ShareException(SHARE_STATUS_CODE_BAD_REQUEST, SHARE_ERROR_CODE_BAD_PARAMETERS, "Bad Share identifier");
        }
        
        return $response;
    }
    
    public function apiDetails($shareId = NULL) {
        if ($this->request->is('GET')) {
            try {
                //Intern Details
                $response = $this->internDetails($shareId);

                //Send JSON respsonse
                $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
            } catch (ShareException $e) {
                $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
            }
        }
    }
}
