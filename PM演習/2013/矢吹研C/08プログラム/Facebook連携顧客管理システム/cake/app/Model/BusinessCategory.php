<?php
App::uses('AppModel', 'Model');
/**
 * BusinessCategory Model
 *
 * @property Company $Company
 */
class BusinessCategory extends AppModel {
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
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'business_category_id',
			'dependent' => false
		)
	);
	
}
