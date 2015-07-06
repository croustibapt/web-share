<?php
App::uses('AppController', 'Controller');
App::uses('ShareException', 'Lib');

class ApiShareTypeCategoriesController extends AppController {
    public $name = 'ApiShareTypeCategories';

	public $uses = array('ShareTypeCategory', 'ShareType');
    
    public function apiGet() {
        if ($this->request->is('GET')) {
            $shareTypeCategories = $this->ShareTypeCategory->find('all');
            
            $response['results'] = array();
            
            $shareTypeCategoryIndex = 0;
            foreach($shareTypeCategories as $shareTypeCategory) {
                $response['results'][$shareTypeCategoryIndex]['share_type_category_id'] = $shareTypeCategory['ShareTypeCategory']['id'];
                $response['results'][$shareTypeCategoryIndex]['label'] = $this->checkEmptyString($shareTypeCategory['ShareTypeCategory']['label']);
                
                $shareTypeIndex = 0;
                foreach ($shareTypeCategory['ShareType'] as $shareType) {
                    $response['results'][$shareTypeCategoryIndex]['share_types'][$shareTypeIndex]['share_type_id'] = $shareType['id'];
                    $response['results'][$shareTypeCategoryIndex]['share_types'][$shareTypeIndex]['label'] = $this->checkEmptyString($shareType['label']);
                    $response['results'][$shareTypeCategoryIndex]['share_types'][$shareTypeIndex]['share_type_category_id'] = $shareType['share_type_category_id'];
                    $shareTypeIndex++;
                }
                
                $shareTypeCategoryIndex++;
            }
            
            $this->sendResponse(SHARE_STATUS_CODE_OK, $response);
        }
    }
}
