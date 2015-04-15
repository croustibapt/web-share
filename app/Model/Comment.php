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
}