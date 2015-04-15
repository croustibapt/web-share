<?php
class ShareTypeCategory extends AppModel {
	public $name = 'ShareTypeCategory';
    
    public $displayField = 'label';
    
    public $validate = array(
        'label' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'allowEmpty' => false,
                'message' => 'FieldRequired'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'FieldNotUnique'
            )
        )
    );

	public $hasMany = array(
		'ShareType' => array(
			'className' => 'ShareType'
    ));
    
    public function beforeDelete($cascade = true) {
        $continue = parent::beforeDelete($cascade);

        if ($continue) {
            //Delete all categorized Share types
            $continue = $this->ShareType->deleteAll(array('ShareType.share_type_category_id' => $this->id), $cascade);
        }

        return $continue;
    }
}