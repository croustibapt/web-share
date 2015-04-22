<?php
class Share extends AppModel {
	public $name = 'Share';
    
    public $displayField = 'title';
    
    public $validate = array(
        'user_id' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'allowEmpty' => false,
                'message' => 'FieldRequired'
            ),
            'numeric' => array(
                'rule'     => 'numeric',
                'message'  => 'FieldNotValid'
            )
        ),
        'share_type_id' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'allowEmpty' => false,
                'message' => 'FieldRequired'
            ),
            'numeric' => array(
                'rule'     => 'numeric',
                'message'  => 'FieldNotValid'
            )
        ),
        'event_date' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'allowEmpty' => false,
                'message' => 'FieldRequired'
            ),
            'datetime' => array(
                'rule'     => 'datetime',
                'message'  => 'FieldNotValid'
            )
        ),
        'title' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'allowEmpty' => false,
                'message' => 'FieldRequired'
            ),
            'minLength' => array(
                'rule'    => array('minLength', SHARE_SHARE_TITLE_MIN_LENGTH),
                'message' => 'FieldLengthNotValid'
            )
        ),
        'price' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'allowEmpty' => false,
                'message' => 'FieldRequired'
            ),
            'decimal' => array(
                'rule'     => 'decimal',
                'message'  => 'FieldNotValid'
            )
        ),
        'places' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'allowEmpty' => false,
                'message' => 'FieldRequired'
            ),
            'naturalNumber' => array(
                'rule'     => 'naturalNumber',
                'message'  => 'FieldNotValid'
            ),
            'greater' => array(
                'rule'    => array('comparison', 'greater or equal', 1),
                'message' => 'FieldNotValid'
            )
        ),
        'latitude' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'allowEmpty' => false,
                'message' => 'FieldRequired'
            ),
            'decimal' => array(
                'rule'     => 'decimal',
                'message'  => 'FieldNotValid'
            )
        ),
        'longitude' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'allowEmpty' => false,
                'message' => 'FieldRequired'
            ),
            'decimal' => array(
                'rule'     => 'decimal',
                'message'  => 'FieldNotValid'
            )
        )
    );
    
    public $belongsTo = array(
		'ShareType' => array(
			'className' => 'ShareType',
			'foreignKey' => 'share_type_id',
            'counterCache' => true),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
            'counterCache' => true
    ));
    
    public $hasMany = array(
        'Comment' => array(
			'className' => 'Comment'),
        'Request' => array(
			'className' => 'Request')
    );

    public function beforeSave($options = array()) {
        if (isset($this->data['Share']['latitude']) && isset($this->data['Share']['longitude'])) {
            $db = ConnectionManager::getDataSource('default');
            $this->data['Share']['location'] = $db->expression("GeomFromText('POINT(" . $this->data['Share']['latitude'] . " " . $this->data['Share']['longitude'] . ")')");
        }
        
        unset($this->data['Share']['latitude']);
        unset($this->data['Share']['longitude']);
        
        return true;
    }
}