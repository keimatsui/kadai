<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/** OAuthプラグイン読込 */
App::import('Vendor', 'OAuth/OAuthClient');

/** Facebookプラグイン読込 */
App::import('Vendor', 'facebook/facebook');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array(
		'Html',
		'Form',
		'Number',
		'Session',
	);

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Session',
		'Auth' => array(
			'loginRedirect' => array(
				'controller'  => 'customers',
				'action' => 'index'
			),
			'logoutRedirect' => array(
				'controller' => 'users',
				'action' => 'login'
			),
			'loginAction' => array(
				'controller' => 'users',
				'action' => 'login'
			)
		),
		'RequestHandler',
	);

/**
 * Twitterインスタンス格納用
 *
 * @var object
 */
	public $Twitter = null;

/**
 * Twitterアクセストークン格納用
 *
 * @var string
 */
	public $accessToken = null;

/**
 * Facebookインスタンス格納用
 *
 * @var object
 */
	public $Facebook = null;

/**
 * setTwitterid method
 *
 * @return void
 */
	public function setTwitterid($appId, $secret) {
		if (!empty($appId) && !empty($secret)) {
			/** TwitterのOAuthClientインスタンスを格納 */
			$twitter = new OAuthClient(
				$appId,
				$secret
			);
			$this->Twitter = $twitter;
			/** セッションにTwitter情報を格納 */
			$this->Session->write('twitter', $twitter);
		} else{
			$this->Twitter = null;
		}
	}

/**
 * setFacebookid method
 *
 * @return void
 */
	public function setFacebookid($appId, $secret) {
		if (!empty($appId) && !empty($secret)) {
			/** Facebookインスタンスを格納 */
			$facebook = new Facebook(array(
				'appId' => $appId,
				'secret' => $secret,
				'cookie'=>true
			));
			$this->Facebook = $facebook;
			/** セッションにFacebook情報を格納 */
			$this->Session->write('facebook', $facebook);
		} else{
			$this->Facebook = null;
		}
	}

/**
 * beforeFilter method
 *
 * @return void
 */
	public function beforeFilter() {
		/** セッションを取得してメンバ変数に格納 */
		if ($this->Session->check('twitter')) {
			$this->Twitter = $this->Session->read('twitter');
		}
		if ($this->Session->check('twitter_accessToken')) {
			$this->accessToken = $this->Session->read('twitter_accessToken');
		}
		if ($this->Session->check('facebook')) {
			$this->Facebook = $this->Session->read('facebook');
		}

		/** 章ごとにViewを切り替える処理 */
		$this->viewFilter();
	}

/**
 * 章切り替え用
 *
 * @var string
 */
	public $viewPage = null;
	public $viewPassed = null;
	public $viewDirection = null;

/**
 * viewFilter method
 *
 * @return void
 */
	private function viewFilter() {
		/** 初期化 */
		$l = null;
		$r = null;

		/** 引数を取得(本システムでは引数が複数になることはないので0のみ取得) */
		if (isset($this->passedArgs[0])) {
			$this->viewPassed = $this->passedArgs[0];
		}

		/** ページ数取得 */
		if (isset($this->params->named['page'])) {
			$this->viewPage = $this->params->named['page'];
		}

		/** 順序の取得 */
		if (isset($this->params->named['direction'])) {
			$this->viewDirection = $this->params->named['direction'];
		}

		/** テンプレートの変更処理 */
		if ($this->name != 'Users') {
			if ($this->params->named != null) {
				if (!isset($this->params->named['t'])) {
					if (isset($this->params->named['l']) && isset($this->params->named['r'])) {

						/** 名前付きパラメータを格納（l:Layout r:Render） */
						$l = $this->params->named['l'];
						$r = $this->params->named['r'];

						/** 名前付きパラメータを削除 */
						unset($this->passedArgs['l']);
						unset($this->passedArgs['r']);
						
						/** レイアウトを変更 */
						$this->autoRender = false;

						/** 5-2はデフォルトテンプレートを使用する（固定） */
						if ($l == '5' && $r == '2') {
							$this->layout = $l.'/default';
						} else {
							$this->layout = $l.'/index';
						}

						/** データの送信がない場合、かつクエリがある場合 */
						if (empty($this->request->data) && isset($this->passedArgs)) {
							if (isset($this->passedArgs['page'])) {
								unset($this->passedArgs['page']);
							}
							/** モデルのデータとして格納する */
							$this->request->data[$this->modelClass] = $this->passedArgs;
						}
						
						/** リクエストの処理を行う */
						eval('$this->'.$this->action.'("'.$this->viewPassed.'");');
						
						/** テンプレート出力 */
						$this->render($l.DS.$this->action.$l.'-'.$r);
					} else {
						/** 名前付きパラメータが無い場合、リファラを確認する */
						$this->viewRedirect();
					}
				} else {
					/** レイアウトリセット処理 */
					$this->autoRender = true;
					$this->layout = 'default';
				}
			} elseif($this->referer() && $this->action != 'ajaxList' && $this->action != 'pdf'
				&& $this->action != 'industryGraph' && $this->action != 'cliCsvExport'
				&& $this->action != 'csvExport' && $this->action != 'csvImport' && $this->action != 'googlemapSearch'
				&& $this->action != 'tweet'
			 	&& $this->action != 'twitter' && $this->action != 'facebook'  && $this->action != 'googleSearch') {
				/** 名前付きパラメータが無い場合、リファラを確認する */
				$this->viewRedirect();
			}
		} else {
			if ($this->params->named != null) {
				/** レイアウトリセット処理 */
				$this->redirect(
					array(
						'action' => $this->action
					)
				);
			}
		}
	}

/**
 * viewRedirect method
 *
 * @return void
 */
	private function viewRedirect() {
		/** リファラに名前付きパラメータがあるか確認 */
		if (strstr($this->referer(), 'l:') && strstr($this->referer(), 'r:')) {
			/** ある場合、名前付きパラメータを取得 */
			$ref = explode('/', $this->referer());
			foreach ($ref as $re) {
				if (strstr($re, 'l:') && strlen($re) == 3) {
					$a = explode(':', $re);
					$l = $a[1];
				} elseif (strstr($re, 'r:') && strlen($re) == 3) {
					$a = explode(':', $re);
					$r = $a[1];
				}
			}

			/** クエリを保持したままリダイレクトする */
			$red = array(
				'action' => $this->action,
				$this->viewPassed,
				'l' => $l,
				'r' => $r,
				'page' => $this->viewPage,
				'direction' => $this->viewDirection
			);
			
			/** 章番号をつけてリダイレクト */
			$this->redirect(
				array_merge($red,$this->passedArgs)
			);
		}
	}

}

