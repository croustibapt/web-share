<?php
class Request extends AppModel {
	public $name = 'Request';
    
    public $useTable = 'requests';
        
    public $belongsTo = array(
		'Share' => array(
			'className' => 'Share',
			'foreignKey' => 'share_id',
            'counterCache' => true),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
            'counterCache' => true)
    );
}