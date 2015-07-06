<?php
App::uses('AppController', 'Controller');
App::uses('ShareException', 'Lib');

class ApiShareTypesController extends AppController {
    public $name = 'ApiShareTypes';
    
	public $uses = array('ShareType', 'ShareTypeCategory');
    
    public function apiGet() {
        if ($this->request->is('GET')) {
            $shareTypes = $this->ShareType->find('all');
            
            $response['results'] = array();
            
            $shareTypeIndex = 0;
            foreach($shareTypes as $shareType) {
                $response['results'][$shareTypeIndex]['share_type_id'] = $shareType['ShareType']['id'];
                $response['results'][$shareTypeIndex]['label'] = $this->checkEmptyString($shareType['ShareType']['label']);
                $shareTypeIndex++;
            }
            
            $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
        }
    }
}
