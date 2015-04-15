<?php
class ShareType extends AppModel {
	public $name = 'ShareType';
    
    public $displayField = 'label';
    
    public $validate = array(
        'label' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'allowEmpty' => false,
                'message' => 'FieldRequired'
            )
        )
    );

	public $hasMany = array(
		'Share' => array(
			'className' => 'Share'
    ));
    
    public $belongsTo = array(
		'ShareTypeCategory' => array(
			'className' => 'ShareTypeCategory'
    ));
    
    public function beforeDelete($cascade = true) {
        $continue = parent::beforeDelete($cascade);

        if ($continue) {
            //Delete all typed Shares
            $continue = $this->Share->deleteAll(array('Share.share_type_id' => $this->id), $cascade);
        }

        return $continue;
    }
}