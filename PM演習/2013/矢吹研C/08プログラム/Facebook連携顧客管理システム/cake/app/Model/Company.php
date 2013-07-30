<?php
App::uses('AppModel', 'Model');
/**
 * Company Model
 *
 * @property BusinessCategory $BusinessCategory
 * @property Customer $Customer
 */
class Company extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';
	
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'BusinessCategory' => array(
			'className' => 'BusinessCategory',
			'foreignKey' => 'business_category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Customer' => array(
			'className' => 'Customer',
			'foreignKey' => 'company_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
}
