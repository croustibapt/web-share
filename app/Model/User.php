<?php
class User extends AppModel {
	public $name = 'User';
    
    public $displayField = 'username';
    
    public $validate = array(
        'username' => array(
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
        ),
        'external_id' => array(
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
		'Share' => array(
			'className' => 'Share'),
        'Comment' => array(
			'className' => 'Comment'),
        'Request' => array(
			'className' => 'Request')
    );
    
    public function beforeDelete($cascade = true) {
        $continue = parent::beforeDelete($cascade);

        if ($continue) {
            //Delete all Shares where the user is the "creator"
            $continue = $this->Share->deleteAll(array('Share.user_id' => $this->id), $cascade);
        }

        return $continue;
    }
}
