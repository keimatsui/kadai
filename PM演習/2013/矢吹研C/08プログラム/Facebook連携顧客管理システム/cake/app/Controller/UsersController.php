<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

/**
 * Module name
 *
 * @var string
 */
	public $name = 'Users';

/**
 * Use Model
 *
 * @var array
 */
	public $uses = array('User');

/**
 * beforeFilter method
 *
 * @return void
 */
	public function beforeFilter() {
		/** ログインしていない時に許可するアクション */
		$this->Auth->autoRedirect = false;
		$this->Auth->allow('login', 'add');
		$this->layout = 'login';
		parent::beforeFilter();
	}

/**
 * login method
 *
 * @return void
 */
	public function login() {
		/** POST 送信時にログイン認証処理を行う */
		if ($this->request->is('post')) {
			/** ログイン認証処理 */
			if ($this->Auth->login()) {
				/** 取得順序を設定 */
				$options = array(
					'conditions' => "User.username = '".$this->request->data['User']['username']."'",
					'fields' => 'User.twitter_consumer_key, User.twitter_consumer_secret, User.facebook_appid, User.facebook_appsecret'
				);
				$input_user = $this->User->find('first', $options);
				parent::setTwitterid(
					$input_user['User']['twitter_consumer_key'],
					$input_user['User']['twitter_consumer_secret']
				);
				parent::setFacebookid(
					$input_user['User']['facebook_appid'],
					$input_user['User']['facebook_appsecret']
				);

				/** 認証後のリダイレクト処理 */
				$this->redirectlogin('Twitter');
				
			} else {
				/** 認証エラーメッセージ出力 */
				$this->Session->setFlash(__('ログインに失敗しました。'));
			}
		}else{
			/** ログイン認証済の場合、顧客画面へ遷移 */
			if ($this->Auth->login()) {
				$this->redirectlogin();
			}
		}
	}

/**
 * redirectlogin method
 *
 * @return boolean
 */
	public function redirectlogin($type = null) {
		try {
			/** 二度呼ぶと値が書き変わる為、変数に格納する */
			$redirectUrl = $this->Auth->redirect();
			switch ($type) {
				case 'Twitter':
					if ($this->Twitter) {
						/** Twitter認証 */
						$requestToken = $this->Twitter->getRequestToken(
							'https://api.twitter.com/oauth/request_token',
							'http://' . $_SERVER['HTTP_HOST'] . '/cake/users/twitter/'
						);
						
						if ($requestToken) {
							$this->Session->write('twitter_request_token', $requestToken);
							$this->redirect(
								'https://api.twitter.com/oauth/authorize?oauth_token=' . $requestToken->key
							);
						} else {
							throw new Exception();
						}
					} else {
						$this->redirectlogin('Facebook');
					}
					break;
				case 'Facebook':
					if ($this->Facebook) {
						if ($this->Facebook->getUser()) {
							$this->redirect($redirectUrl);
						} else {
							/** Facebookログイン */
							$login_url = $this->Facebook->getLoginUrl(array(
								'canvas' => 1,
								'fbconnect' => 0,
								'redirect_uri'=> 'http://'.$_SERVER['HTTP_HOST'].'/cake/users/facebook/',
							));
							$this->redirect($login_url);
						}
					} else {
						$this->redirect($redirectUrl);
					}
					break;
				default:
					$this->redirect($redirectUrl);
					break;
			}
		} catch (Exception $e) {
			/** エラー（ログアウト） */
			$this->Auth->logout();
			/** 認証エラーメッセージ出力 */
			$this->Session->setFlash(__('ログインに失敗しました。'));
		}
	}

/**
 * logout method
 *
 * @return void
 */
	public function logout() {
		$this->Twitter = null;
		$this->Facebook = null;
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}

/**
 * twitter method
 *
 * @return void
 */
	public function twitter() {
		/** レイアウトを使用しない */
		$this->autoRender = false;
		$this->layout = false;
		
		/** アクセストークンの取得 */
		$requestToken = $this->Session->read('twitter_request_token');
		$accessToken = $this->Twitter->getAccessToken(
			'https://api.twitter.com/oauth/access_token',
			$requestToken
		);

		if ($accessToken) {
			/** インスタンスを格納 */
			$this->Session->write('twitter', $this->Twitter);
			$this->Session->write('twitter_accessToken', $accessToken);
			/** トークンを削除 */
			$this->Session->delete('twitter_request_token');
			/** リダイレクト */
			$this->redirectlogin('Facebook');

		} else {
			/** 取得できていない場合、エラー（ログアウト） */
			$this->Session->setFlash(__('ログインに失敗しました。'));
			$this->redirect($this->Auth->logout());
		}
	}

/**
 * facebook method
 *
 * @return void
 */
	public function facebook() {
		/** レイアウトを使用しない */
		$this->autoRender = false;
		$this->layout = false;
		/** アクセストークンの取得 */
		$access_token = $this->Facebook->getAccessToken();
		if ($access_token) {
			/** インスタンスを格納 */
			$this->Session->write('facebook', $this->Facebook);
			/** リダイレクト */
			$this->redirectlogin();
		}else{
			/** 取得できていない場合、エラー（ログアウト） */
			$this->Session->setFlash(__('ログインに失敗しました。'));
			$this->redirect($this->Auth->logout());
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}
	
/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

