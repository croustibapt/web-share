<?php
class Evaluation extends AppModel {
	public $name = 'Evaluation';
    
    public $useTable = 'evaluations';
        
    public $belongsTo = array(
		'Request' => array(
			'className' => 'Request',
			'foreignKey' => 'request_id')
    );
}