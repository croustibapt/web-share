<?php
App::uses('ApiSharesController', 'Controller');

class SharesController extends ApiSharesController {
    //
    public function search($date = 'all', $shareTypeCategory = NULL, $shareType = NULL) {
        if ($this->request->is('GET')) {
            $region = NULL;
            $startDate = NULL;
            $endDate = NULL;
            $page = 1;

            //Date filter
            if ($date != 'all') {
                //Prepare date computation
                $now = new DateTime();
                $utcTimeZone = new DateTimeZone("UTC");

                //Current day
                if ($date == 'day') {
                    $currentDay = $now->format('Y-m-d');

                    $startDate = new DateTime($currentDay, $utcTimeZone);

                    $dayInterval = DateInterval::createfromdatestring('1 day - 1 second');

                    $endDate = new DateTime($currentDay, $utcTimeZone);;
                    $endDate->add($dayInterval);
                } else if ($date == 'week') {
                    //Current week
                    $currentDay = $now->format('Y-m-d');

                    $currentDayTimestamp = strtotime($currentDay);
                    $start = (date('w', $currentDayTimestamp) == 1) ? $currentDayTimestamp : strtotime('last monday', $currentDayTimestamp);

                    $startDateString = date('Y-m-d', $start);
                    $startDate = new DateTime($startDateString, $utcTimeZone);

                    $endDateString = date('Y-m-d', strtotime('next monday', $start));
                    $endDate = new DateTime($endDateString, $utcTimeZone);

                    $dayInterval = DateInterval::createfromdatestring('-1 second');
                    $endDate->add($dayInterval);
                } else if ($date == 'month') {
                    //Current month
                    $currentMonth = $now->format('Y-m');

                    $startDate = new DateTime($currentMonth, $utcTimeZone);

                    $monthInterval = DateInterval::createfromdatestring('1 month - 1 second');
                    $endDate = new DateTime($currentMonth, $utcTimeZone);;
                    $endDate->add($monthInterval);
                }
            }

            //Parse types
            if ($shareTypeCategory != NULL) {
                if ($shareType != NULL) {
                    $type = $this->getShareType($shareTypeCategory, $shareType);
                    $types = [$type['ShareType']['id']];
                } else {
                    $types = $this->getShareTypeCategoryTypes($shareTypeCategory);
                }
            } else {
                $types = NULL;
            }

            //Page
            if (isset($this->params['url']['page']) && is_numeric($this->params['url']['page'])) {
                $page = $this->params['url']['page'];
            }

            /*pr($startDate);
            pr($endDate);*/

            //
            $response = $this->internSearch($types, $startDate, $endDate, $region, $page);

            //
            $this->set('response', $response);

            $this->set('date', $date);
            $this->set('shareTypeCategory', $shareTypeCategory);
            $this->set('shareType', $shareType);
            $this->set('page', $page);
        }

        //Get share types
        $this->ShareType->unbindModel(
            array('hasMany' => array('Share'))
        );
        $shareCategoryTypes = $this->ShareType->find('all', array(
            'fields' => array('ShareType.id', 'ShareType.label', 'ShareType.share_type_category_id', 'ShareTypeCategory.label'),
        ));
        $shareCategoryTypes = Set::combine($shareCategoryTypes, '{n}.ShareType.id', '{n}.ShareType', '{n}.ShareTypeCategory.label');
        $this->set('shareCategoryTypes', $shareCategoryTypes);
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
