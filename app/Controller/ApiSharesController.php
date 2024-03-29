<?php
App::uses('AppController', 'Controller');
App::uses('ShareException', 'Lib');

define('SHARE_PUSH_NOTIFICATION_SHARE_CANCELLED', 'SHARE_PUSH_NOTIFICATION_SHARE_CANCELLED');

class ApiSharesController extends AppController {
	public $uses = array('Share', 'ShareType', 'ShareTypeCategory', 'User', 'Tag', 'Comment', 'Request');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('search', 'details', 'add', 'cancel');
    }
    
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

    protected function internSearch($types = NULL, $startDate = NULL, $endDate = NULL, $region = NULL, $page = 1, $limit = SHARE_SHARES_SEARCH_LIMIT) {
        //Main query
        $sqlPrefix = "SELECT *, X(Share.location) as latitude, Y(Share.location) as longitude, ShareTypeCategory.label, (SELECT COUNT(Request.id) FROM requests Request WHERE Request.share_id = Share.id AND Request.status = 1) AS participation_count";
        $sql = " FROM shares Share, users User, share_types ShareType, share_type_categories ShareTypeCategory WHERE Share.user_id = User.id AND Share.status = ".SHARE_STATUS_OPENED." AND Share.share_type_id = ShareType.id AND ShareType.share_type_category_id = ShareTypeCategory.id";

        //Types
        if (($types != NULL) && (count($types) > 0)) {
            $shareTypesIds = "(";

            $i = 0;
            foreach ($types as $type) {
                if ($i > 0) {
                    $shareTypesIds .= ", ";
                }

                $shareTypesIds .= $type;
                $i++;
            }

            $shareTypesIds .= ")";

            $sql .= ' AND Share.share_type_id IN '.$shareTypesIds;
        }

        //Start date
        if ($startDate != NULL) {
            $sqlStartDate = $startDate->format('Y-m-d');
            //$sqlStartTime = $startDate->format('H:i:s');

            $sql .= ' AND Share.start_date >= \''.$sqlStartDate.'\'';
        }

        //End date
        if ($endDate != NULL) {
            $sqlEndDate = $endDate->format('Y-m-d');
            //$sqlEndTime = $endDate->format('H:i:s');

            $sql .= ' AND Share.start_date <= \''.$sqlEndDate.'\'';
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
        $sqlLimit = " LIMIT ".$limit;
        
        //Offset
        $offset = ($page - 1) * $limit;
        $sqlOffset = " OFFSET ".$offset;

        $query = $sqlPrefix.$sql." GROUP BY Share.id ORDER BY Share.start_date ASC, Share.start_time ASC".$sqlLimit.$sqlOffset.";";

        //pr($query);

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
        $response['total_pages'] = ceil($totalResults / $limit);

        //Return limit
        $response['limit'] = $limit;

        return $response;
    }

    public function search() {
        if ($this->request->is('post')) {
            //Decode data
            $data = $this->request->input('json_decode', true);

            /*echo json_encode($data);
            exit();*/

            //Get Share type
            $types = NULL;
            if (isset($data['types']) && is_array($data['types']) && !(empty($data['types']))) {
                $types = $data['types'];
            }

            //Get start date
            $startDate = NULL;
            if (isset($data['start']) && is_numeric($data['start'])) {
                $startTimestamp = $data['start'];

                $startDate = new DateTime();
                $startDate->setTimestamp($startTimestamp);
            }

            //Get end date
            $endDate = NULL;
            if (isset($data['end']) && is_numeric($data['end'])) {
                $endTimestamp = $data['end'];

                $endDate = new DateTime();
                $endDate->setTimestamp($endTimestamp);
            }

            //Get region
            $region = NULL;
            if (isset($data['region']) && is_array($data['region']) && !(empty($data['region']))) {
                $region = $data['region'];
            }

            //Page
            $page = 1;
            if (isset($data['page']) && is_numeric($data['page'])) {
                $page = $data['page'];
            }

            //Limit
            $limit = SHARE_SHARES_SEARCH_LIMIT;
            if (isset($data['limit']) && is_numeric($data['limit'])) {
                $limit = $data['limit'];
            }

            $response = $this->internSearch($types, $startDate, $endDate, $region, $page, $limit);

            //Send JSON response
            $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
        }
    }
    
    protected function internAdd($userId = NULL, $latitude = NULL, $longitude = NULL, $city = NULL, $zipCode = NULL, $shareTypeId = NULL, $startDate = NULL, $startTime = NULL, $title = NULL, $price = NULL, $places = NULL, $waitingTime = NULL, $meetPlace = NULL, $limitations = NULL, $imageUrl = NULL, $link = NULL, $message = NULL, $accuracy = NULL, $radius = NULL) {
        $response = NULL;

        $cityGeocodingUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&result_type=locality|postal_code&key=".SHARE_GOOGLE_MAPS_API_KEY;

        //pr($cityGeocodingUrl);

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
        $dataShare['Share']['start_date'] = $startDate;
        $dataShare['Share']['start_time'] = $startTime;
        $dataShare['Share']['title'] = $title;
        $dataShare['Share']['price'] = $price;
        $dataShare['Share']['places'] = $places;
        $dataShare['Share']['status'] = SHARE_STATUS_OPENED;
        $dataShare['Share']['waiting_time'] = $waitingTime;
        $dataShare['Share']['meet_place'] = $meetPlace;
        $dataShare['Share']['limitations'] = $limitations;
        $dataShare['Share']['image_url'] = $imageUrl;
        $dataShare['Share']['link'] = $link;
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

            //Created, modified
            $this->formatISODate($response['created'], $share['Share']['created']);
            $this->formatISODate($response['modified'], $share['Share']['modified']);

            $response['city'] = $this->checkEmptyString($share['Share']['city']);
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
        
        return $response;
    }

	public function add() {
        if ($this->request->is('put')) {
            //Check credentials
            if ($this->checkCredentials($this->request)) {
                //Get user external identifier
                $userExternalId = $this->getUserExternalId($this->request);
                $userId = $this->getUserId($userExternalId);

                /*echo json_encode($userExternalId);
                exit();*/

                //Get data
                $data = $this->request->input('json_decode', true);

                //Check empty fields
                $this->checkField($data, 'city');
                $this->checkField($data, 'zip_code');
                $this->checkField($data, 'limitations');
                $this->checkField($data, 'image_url');
                $this->checkField($data, 'link');
                $this->checkField($data, 'meet_place');
                $this->checkField($data, 'message');

                try {
                    //Intern add
                    $response = $this->internAdd($userId, $data['latitude'], $data['longitude'], NULL, NULL, $data['share_type_id'], $data['start_date'], $data['start_time'], $data['title'], $data['price'], $data['places'], $data['waiting_time'], $data['meet_place'], $data['limitations'], $data['image_url'], $data['link'], $data['message'], $data['accuracy'], $data['radius']);

                    //Send JSON respsonse
                    $this->sendResponse(SHARE_STATUS_CODE_CREATED, $response);
                } catch (ShareException $e) {
                    $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
                }
            } else {
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS);
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
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
                $response = $this->formatShare($share, true);
            } else {
                throw new ShareException(SHARE_STATUS_CODE_NOT_FOUND, SHARE_ERROR_CODE_RESOURCE_NOT_FOUND, "Share not found");
            }
        } else {
            throw new ShareException(SHARE_STATUS_CODE_BAD_REQUEST, SHARE_ERROR_CODE_BAD_PARAMETERS, "Bad Share identifier");
        }
        
        return $response;
    }
    
    public function details($shareId = NULL) {
        if ($this->request->is('get')) {
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

    protected function internCancel($shareId = NULL, $reason = NULL, $message = NULL, $userExternalId = NULL) {
        $response = NULL;

        //Check share id parameter
        if ($shareId != NULL) {
            //Check reason parameter
            if ($reason != NULL) {
                //Find share
                $share = $this->Share->find('first', array(
                    'conditions' => array(
                        'Share.id' => $shareId
                    )
                ));

                //If it's well formatted
                if ($share != NULL) {
                    //Check if the Share is opened
                    if ($this->canCancel($share, $userExternalId)) {
                        //Save changes
                        $this->Share->id = $shareId;

                        //If it succeeded
                        $updatedShare = $this->Share->saveField('status', SHARE_STATUS_CLOSED);
                        if ($updatedShare) {
                            $response['share_id'] = $shareId;
                            $response['status'] = $updatedShare['Share']['status'];
                            $response['modified'] = $updatedShare['Share']['modified'];
                            
                            //Send push notif to each participant
                            foreach ($share['Request'] as $request) {
                                if ($request['status'] == SHARE_REQUEST_STATUS_ACCEPTED) {
                                    $this->sendPushNotif($request['user_id'], 'Le partage auquel vous participiez vient d\'être annulé.', SHARE_PUSH_NOTIFICATION_SHARE_CANCELLED, array("share_id" => $shareId));
                                } else if ($request['status'] == SHARE_REQUEST_STATUS_PENDING) {
                                    $this->sendPushNotif($request['user_id'], 'Le partage auquel vous vouliez participer vient d\'être annulé.', SHARE_PUSH_NOTIFICATION_SHARE_CANCELLED, array("share_id" => $shareId));
                                }
                            }
                        } else {
                            throw new ShareException(SHARE_STATUS_CODE_INTERNAL_SERVER_ERROR, SHARE_ERROR_CODE_SAVE_FAILED, "Share cancel failed");
                        }
                    } else {
                        throw new ShareException(SHARE_STATUS_CODE_METHOD_NOT_ALLOWED, SHARE_ERROR_CODE_RESOURCE_DISABLED, "You cannot cancel this Share");
                    }
                } else {
                    throw new ShareException(SHARE_STATUS_CODE_NOT_FOUND, SHARE_ERROR_CODE_RESOURCE_NOT_FOUND, "Share not found");
                }
            } else {
                throw new ShareException(SHARE_STATUS_CODE_BAD_REQUEST, SHARE_ERROR_CODE_BAD_PARAMETERS, "Bad reason");
            }
        } else {
            throw new ShareException(SHARE_STATUS_CODE_BAD_REQUEST, SHARE_ERROR_CODE_BAD_PARAMETERS, "Bad Share identifier");
        }

        return $response;
    }

    public function cancel($shareId = NULL) {
        if ($this->request->is('post')) {
            //Check credentials
            if ($this->checkCredentials($this->request)) {
                try {
                    //Get data
                    $data = $this->request->input('json_decode', true);

                    //Intern Details
                    $response = $this->internCancel($shareId, $data['reason'], $data['message']);

                    //Send JSON respsonse
                    $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
                } catch (ShareException $e) {
                    $this->sendErrorResponse($e->getStatusCode(), $e->getCode(), $e->getMessage(), $e->getValidationErrors());
                }
            } else {
                $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_ERROR_CODE_BAD_CREDENTIALS);
            }
        } else {
            $this->sendErrorResponse(SHARE_STATUS_CODE_UNAUTHORIZED, SHARE_STATUS_CODE_METHOD_NOT_ALLOWED);
        }
    }
}
