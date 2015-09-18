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
            'foreignKey' => false,
            'conditions' => array(
                'Request.participant_evaluation_id = ParticipantEvaluation.id'
        )),
        'CreatorEvaluation' => array(
			'className' => 'Evaluation',
            'foreignKey' => false,
            'conditions' => array(
                'Request.creator_evaluation_id = CreatorEvaluation.id'
        ))
    );

    public function afterFind($results, $primary = false) {
        if (isset($results['ParticipantEvaluation']) && $results['ParticipantEvaluation']['id'] === null) {
            unset($results['ParticipantEvaluation']);
        }

        if (isset($results['CreatorEvaluation']) && $results['CreatorEvaluation']['id'] === null) {
            unset($results['CreatorEvaluation']);
        }

        if (isset($results[0])) {
            foreach ($results as $key => $value) {
                if (isset($value['ParticipantEvaluation']) && $value['ParticipantEvaluation']['id'] === null) {
                    unset($results[$key]['ParticipantEvaluation']);
                }

                if (isset($value['CreatorEvaluation']) && $value['CreatorEvaluation']['id'] === null) {
                    unset($results[$key]['CreatorEvaluation']);
                }
            }
        }

        return $results;
    }
}