<?php
App::uses('AppModel', 'Model');
/**
 * Customer Model
 *
 * @property Prefecture $Prefecture
 */
class Customer extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Prefecture' => array(
			'className' => 'Prefecture',
			'foreignKey' => 'prefecture_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
