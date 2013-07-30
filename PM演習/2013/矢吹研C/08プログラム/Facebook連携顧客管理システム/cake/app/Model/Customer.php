<?php
App::uses('AppModel', 'Model');
/**
 * Customer Model
 *
 * @property Company $Company
 * @property Prefecture $Prefecture
 */
class Customer extends AppModel {
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
	public $validate = array(
		'customer_cd' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => '顧客コードを入力してください',
			),
			'maxlength' => array(
				'rule' => array('maxlength',10),
				'message' => '10文字以内にしてください',
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => '数値を入力してください',
			),
		),
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => '顧客名を入力してください',
			),
			'maxlength' => array(
				'rule' => array('maxlength',50),
				'message' => '50文字以内にしてください',
			),
		),
		'kana' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => '顧客名を入力してください',
			),
			'maxlength' => array(
				'rule' => array('maxlength',50),
				'message' => '50文字以内にしてください',
			),
		),
		'gender' => array(),
		'company_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => '会社名を選択してください',
			),
		),
		'zip' => array(
			'maxlength' => array(
				'rule' => array('maxlength', 10),
			),
		),
		'prefecture_id' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => '都道府県を選択してください',
			),
		),
		'address1' => array(
			'maxlength' => array(
				'rule' => array('maxlength', 200),
				'message' => '200文字以内にしてください',
			),
		),
		'address2' => array(
			'maxlength' => array(
				'rule' => array('maxlength', 200),
				'message' => '200文字以内にしてください',
			),
		),
		'phone' => array(
			'phone' => array(
				'rule'=>array('custom','/\d{2,4}-\d{2,4}-\d{4}/'),
				'message'=>'電話番号を正しく入力してください。'
			),
		),
		'fax' => array(
			'fax' => array(
				'rule'=>array('custom','/\d{2,4}-\d{2,4}-\d{4}/'),
				'allowEmpty' => true,
				'message'=>'FAX番号を正しく入力してください。',
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message'=>'メールアドレスを正しく入力してください。'
			),
		),
		'created' => array(
		),
		'modified' => array(
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => 'id, company_name',
			'order' => ''
		),
		'Prefecture' => array(
			'className' => 'Prefecture',
			'foreignKey' => 'prefecture_id',
			'conditions' => '',
			'fields' => 'id, pref_name',
			'order' => ''
		),
	);

}
