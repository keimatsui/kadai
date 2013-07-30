<?php
App::uses('AppModel', 'Model');
/**
 * Sale Model
 *
 * @property Customer $Customer
 * @property Product $Product
 * @property Company $Company
 */
class Sale extends AppModel {
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
		'Customer' => array(
			'className' => 'Customer',
			'foreignKey' => 'customer_id',
			'order' => '',
			'conditions' => ''
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
			'order' => '',
			'conditions' => ''
		),
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => '',
			'order' => '',
			'conditions' => 'Customer.company_id = Company.id',
			'fields' => 'Company.company_name'
		)
	);
}
