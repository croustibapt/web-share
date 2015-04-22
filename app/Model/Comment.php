<?php
class Comment extends AppModel {
	public $name = 'Comment';
    
    public $belongsTo = array(
		'Share' => array(
			'className' => 'Share',
			'foreignKey' => 'share_id',
            'counterCache' => true),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
            'counterCache' => true
    ));

    public $validate = array(
        'message' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => 'create',
                'allowEmpty' => false,
                'message' => 'FieldRequired'
            ),
            'minLength' => array(
                'rule'    => array('minLength', SHARE_COMMENT_MESSAGE_MIN_LENGTH),
                'message' => 'FieldLengthNotValid'
    )));
}