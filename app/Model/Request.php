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
    
    public $hasOne = array(
        'ParticipantEvaluation' => array(
			'className' => 'Evaluation',
            'foreignKey'    => false,
            'conditions' => array(
                'Request.participant_evaluation_id = ParticipantEvaluation.id'
        )),
        'CreatorEvaluation' => array(
			'className' => 'Evaluation',
            'foreignKey'    => false,
            'conditions' => array(
                'Request.creator_evaluation_id = CreatorEvaluation.id'
        ))
    );
}