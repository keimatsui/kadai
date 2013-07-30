<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
/**
 * User Model
 *
 */
class User extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'username' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'usernameを入力してください',
			)
		),
		'password' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'passwordを入力してください',
			)
		),
	);

/**
 * beforeSave method
 *
 * @var string
 */
	public function beforeSave($options = Array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] =
				AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}

}

