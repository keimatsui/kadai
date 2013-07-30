<?php
App::uses('AppController', 'Controller');

/** HelperをControllerで使用する */
App::import('Helper', 'Csv');

/**
 * Customers Controller
 *
 * @property Customer $Customer
 */
class CustomersController extends AppController {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Csv');

/**
 * Module name
 *
 * @var string
 */
	public $name = 'Customers';

/**
 * Use Model
 *
 * @var array
 */
	public $uses = array(
		'Customer',
		'Company',
		'Prefecture',
	);

/**
 * Paginate setting
 *
 * @var array
 */
	public $paginate = array(
		'order' => 'Customer.customer_cd ASC',
		'limit' => 5,
	);

/**
 * index method
 *
 * @return void
 */
	public function index() {
		/** Ajaxの場合、処理を行わない */
		if (!$this->RequestHandler->isAjax()) {
			/** POST値がある場合は検索処理を行う */
			$option = array();
			$searchword = array();
			$lasttrade_start = null;
			$lasttrade_end = null;

			/** リクエストデータがある場合、ページ以外のパラメータがある場合は検索処理 */
			if (!empty($this->data) || count($this->passedArgs) > 1) {
				if (!isset($this->request->data['clear'])) {
					/** 検索ボタンを押下した場合 */
					if (isset($this->request->data['search']) || isset($this->request->data['freeword'])) {
						$searchword = $this->request->data['Customer'];
					} else {
						/** パラメータが検索ワード */
						foreach ($this->passedArgs as $argkey => $argvalue) {
							if ($argkey != 'sort' && $argkey != 'direction' && $argkey != 'page') {
								if ($argkey != 'lasttrade_start' && $argkey != 'lasttrade_end') {
									$searchword[$argkey] = urlencode($argvalue);
								} else {
									$searchword[$argkey]['year'] = urlencode($argvalue['year']);
									$searchword[$argkey]['month'] = urlencode($argvalue['month']);
									$searchword[$argkey]['day'] = urlencode($argvalue['day']);
								}
							}
						}
						/** フォームに値を格納するため、リクエストに代入 */
						$this->request->data['Customer'] = $searchword;
					}

					if (isset($searchword['freeword'])) {
						/** 全文検索処理 */
						if (!empty($searchword['freeword'])) {
							$keywords = trim($searchword['freeword']);
							$option = array(
								'OR' => array(
									"MATCH(Customer.customer_cd) AGAINST('".$keywords."')",
									"MATCH(Customer.name) AGAINST('".$keywords."')",
									"MATCH(Customer.kana) AGAINST('".$keywords."')",
									"MATCH(Customer.zip) AGAINST('".$keywords."')",
									"MATCH(Customer.phone) AGAINST('".$keywords."')",
									"MATCH(Customer.email) AGAINST('".$keywords."')",
									"MATCH(Company.company_name) AGAINST('".$keywords."')",
									"MATCH(Prefecture.pref_name) AGAINST('".$keywords."')",
								)
							);
						}
					} else {
						/** 顧客情報の検索処理 */
						foreach ($searchword as $search_key => $search_value) {
							if ($search_key != 'sort' && $search_key != 'direction' && $search_key != 'page') {
								if (isset($search_value) && $search_value != '') {
									if (strstr($search_key, '_id')) {
										/** _idを含む場合、完全一致にする */
										$option[$search_key] = $search_value;
									} elseif(strstr($search_key, 'lasttrade')) {
										/** 日付の検索 */
										$year = strval($search_value['year']);
										$month = strval($search_value['month']);
										$day = strval($search_value['day']);
										
										if (!empty($year) && !empty($month) && !empty($day)) {
											/** 日付が存在するか確認 */
											if (checkdate($month, $day, $year)) {
												if(strstr($search_key, '_start')) {
													$lasttrade_start = $year.'-'.$month.'-'.$day;
												} elseif(strstr($search_key, '_end')) {
													$lasttrade_end = $year.'-'.$month.'-'.$day;
												}
											}
										}
									} else {
										/** その他の項目は部分一致 */
										$option[$search_key.' LIKE'] = "%{$search_value}%";
									}
								}
							}
						}

						/** 開始日と終了日を検索する */
						if ($lasttrade_start || $lasttrade_end) {
							if ($lasttrade_start == null) {
								$lasttrade_start =  date('Y')-100 .'-'. date('m') .'-'. date('d');
							}
							if ($lasttrade_end == null) {
								$lasttrade_end =  date('Y')+100 .'-'. date('m') .'-'. date('d');
							}
							$option['lasttrade BETWEEN ? AND ?'] = array($lasttrade_start, $lasttrade_end);
						}
					}
				} else {
					/** 検索内容のクリア */
					$this->redirect(array('action' => 'index'));
				}
			}

			/** 都道府県情報を取得する */
			$prefectures = $this->Prefecture->find('all');
			/** プルダウン用にデータを整える */
			$prefectures = set::Combine(
				$prefectures,
				'{n}.Prefecture.id',
				'{n}.Prefecture.pref_name'
			);

			/** テンプレートに出力 */
			$this->set('searchword', $searchword);
			$this->set('prefectures', $prefectures);
			$this->set('customers', $this->paginate($option));
		}
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Customer->create();
			if ($this->Customer->save($this->request->data)) {
				$this->Session->setFlash(__('顧客の登録に成功しました。'), 'Flash/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('顧客の登録に失敗しました。'));
			}
		}

		/** 会社情報を取得する */
		$companies = $this->Company->find('all');
		/** プルダウン用にデータを整える */
		$companies = set::Combine($companies, '{n}.Company.id', '{n}.Company.company_name');

		/** 都道府県情報を取得する */
		$prefectures = $this->Prefecture->find('all');
		/** プルダウン用にデータを整える */
		$prefectures = set::Combine($prefectures, '{n}.Prefecture.id', '{n}.Prefecture.pref_name');

		/** テンプレートに出力 */
		$this->set('company', $companies);
		$this->set('prefectures', $prefectures);
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Customer->id = $id;
		if (!$this->Customer->exists()) {
			throw new NotFoundException(__('Invalid customer'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Customer->save($this->request->data)) {
				$this->Session->setFlash(__('顧客の登録に成功しました。'), 'Flash/success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('顧客の登録に失敗しました。'));
			}
		} else {
			$this->request->data = $this->Customer->read(null, $id);
		}

		/** 会社情報を取得する */
		$companies = $this->Company->find('all');
		/** プルダウン用にデータを整える */
		$companies = set::Combine($companies, '{n}.Company.id', '{n}.Company.company_name');

		/** 都道府県情報を取得する */
		$prefectures = $this->Prefecture->find('all');
		/** プルダウン用にデータを整える */
		$prefectures = set::Combine($prefectures, '{n}.Prefecture.id', '{n}.Prefecture.pref_name');

		/** Viewに値を渡す */
		$this->set('company', $companies);
		$this->set('prefectures', $prefectures);
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Customer->id = $id;
		if (!$this->Customer->exists()) {
			throw new NotFoundException(__('Invalid customer'));
		}
		if ($this->Customer->delete()) {
			$this->Session->setFlash(__('顧客を削除しました。'), 'Flash/success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('顧客の削除に失敗しました。'));
		$this->redirect(array('action' => 'index'));
	}

/**
 * csvImport method
 *
 * @return void
 */
	public function csvImport(){
		if (!empty($this->data)) {
			/** アップロードファイル情報格納 */
			$up_file = $this->data['Customer']['file_name']['tmp_name']; 
			$fileName = TMP.'csv/'.$this->data['Customer']['file_name']['name'];
			
			/** アップロードされたファイルかどうか確認を行う */
			if (is_uploaded_file($up_file)){
				/** アップロードされたテンポラリファイルを、出力ファイル名で指定されたパスにコピーする */
				move_uploaded_file($up_file, $fileName);
				
				try {
					
					/** CSVファイルを読み込みデータをインポートする */
					$csvData = file($fileName, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
					
					/** 読み込んだデータのインポートが成功した数のカウント用 */
					$csvSuccessCnt = 0;
					
					foreach($csvData as $key => $line){
						/** 一行目は飛ばす */
						if ($key != 0) {
							/** 区切り文字で分割する */
							$record = split(',', $line);
							mb_language('Japanese');
							/** インポートするデータをコンバートする */
							$data = array( 
								'customer_cd' => mb_convert_encoding(html_entity_decode($record[0]), 'UTF-8', 'auto'),
								'name' => mb_convert_encoding(html_entity_decode($record[1]), 'UTF-8', 'auto'), 
								'kana' => mb_convert_encoding(html_entity_decode($record[2]), 'UTF-8', 'auto'), 
								'gender' => mb_convert_encoding(html_entity_decode($record[3]), 'UTF-8', 'auto'), 
								'company_id' => mb_convert_encoding(html_entity_decode($record[4]), 'UTF-8', 'auto'), 
								'zip' => mb_convert_encoding(html_entity_decode($record[5]), 'UTF-8', 'auto'), 
								'prefecture_id' => mb_convert_encoding(html_entity_decode($record[6]), 'UTF-8', 'auto'), 
								'address1' => mb_convert_encoding(html_entity_decode($record[7]), 'UTF-8', 'auto'), 
								'address2' => mb_convert_encoding(html_entity_decode($record[8]), 'UTF-8', 'auto'), 
								'phone' => mb_convert_encoding(html_entity_decode($record[9]), 'UTF-8', 'auto'), 
								'fax' => mb_convert_encoding(html_entity_decode($record[10]), 'UTF-8', 'auto'), 
								'email' => mb_convert_encoding(html_entity_decode($record[11]), 'UTF-8', 'auto'), 
								'lasttrade' => mb_convert_encoding(html_entity_decode($record[12]), 'UTF-8', 'auto'), 
								'twitter_id' => mb_convert_encoding(html_entity_decode($record[13]), 'UTF-8', 'auto'), 
								'facebook_id' => mb_convert_encoding(html_entity_decode($record[14]), 'UTF-8', 'auto'), 
							);
							/** データを登録する */
							$this->Customer->create(); 
							if ($this->Customer->save($data)){
								$csvSuccessCnt++;
							}
						} 
					}
				} catch(Exception $e) {
				}
			}
			$this->Session->setFlash((count($csvData) - 1).'件中'.$csvSuccessCnt.'件のCSVデータのインポートが完了しました。', 'Flash/success');
		}
	}

/**
 * csvCustomer method
 *
 * @return string
 */
	private function csvCustomer() {
		/** ファイル名を指定する */
		$filename = date('YmdHis');
		
		/** 顧客情報を取得する */
		$th = array('customer_cd', 'name', 'kana', 'gender', 'company_id', 'zip', 'prefecture_id', 'address1', 'address2', 'phone', 'fax', 'email', 'lasttrade','twitter_id', 'facebook_id');
		$td = $this->Customer->find('all', array('fields' => $th));

		/** 情報を結合する */
		$table = compact('th', 'td');
		
		/** CsvHelperを使用してCsv出力する */
		$this->Csv = new CsvHelper();
		$this->Csv->addRow($th);
		foreach ($table['td'] as $t) {
			$this->Csv->addField(h($t['Customer']['customer_cd']));
			$this->Csv->addField(h($t['Customer']['name']));
			$this->Csv->addField(h($t['Customer']['kana']));
			$this->Csv->addField(h($t['Customer']['gender']));
			$this->Csv->addField(h($t['Customer']['company_id']));
			$this->Csv->addField(h($t['Customer']['zip']));
			$this->Csv->addField(h($t['Customer']['prefecture_id']));
			$this->Csv->addField(h($t['Customer']['address1']));
			$this->Csv->addField(h($t['Customer']['address2']));
			$this->Csv->addField(h($t['Customer']['phone']));
			$this->Csv->addField(h($t['Customer']['fax']));
			$this->Csv->addField(h($t['Customer']['email']));
			$this->Csv->addField(h($t['Customer']['lasttrade']));
			$this->Csv->addField(h($t['Customer']['twitter_id']));
			$this->Csv->addField(h($t['Customer']['facebook_id']));
			$this->Csv->endRow();
		}
		$this->Csv->setFilename($filename);
		echo $this->Csv->render('utf-8');
	}
	
/**
 * csvExport method
 *
 * @return boolean
 */
	public function csvExport() {
		/** レイアウトを使用しない */
		$this->autoRender = false;
		$this->layout = false;

		$this->csvCustomer();
		return ;
	}

/**
 * ajaxList method
 *
 * @return void
 */
	public function ajaxList($ctp = null) {
		/** デバッグ情報出力を抑制 */
		Configure::write('debug', 0);
		
		/** レイアウトを使用しない */
		$this->autoRender = false;
		$this->layout = false;

		/** テンプレートの指定 */
		if (empty($ctp)) {
			$renderCtp = 'list';
		} else {
			/** ディレクトリを取得 */
			$listDirname = explode('-', $ctp);
			$ctpNumber = str_replace('list', '', $listDirname[0]);
			if (!empty($ctpNumber)) {
				$renderCtp = $ctpNumber."/".$ctp;
			} else {
				$renderCtp = $ctp;
			}
		}
		$option = array();
		$datalist = array();
		$page = null;
		$lasttrade_start = null;
		$lasttrade_end = null;
		$twitter = false;
		$facebook = false;

		/** Ajaxによる呼び出しかどうかの確認 */
		if ($this->RequestHandler->isAjax()) {
			foreach ($this->request->data as $search_key => $search_value) {
				if (isset($search_value) && $search_value != '') {
					if (strstr($search_key, '_id')) {
						/** _idを含む場合、完全一致にする */
						$option[$search_key] = $search_value;
					} elseif(strstr($search_key, 'page')) {
						$this->paginate['page'] = $search_value;
					} elseif(strstr($search_key, 'lasttrade')) {
						/** 日付の検索 */
						$year = strval($search_value['year']);
						$month = strval($search_value['month']);
						$day = strval($search_value['day']);
						
						if (!empty($year) && !empty($month) && !empty($day)) {
							/** 日付が存在するか確認 */
							if (checkdate($month, $day, $year)) {
								if(strstr($search_key, '_start')) {
									$lasttrade_start = $year.'-'.$month.'-'.$day;
								} elseif(strstr($search_key, '_end')) {
									$lasttrade_end = $year.'-'.$month.'-'.$day;
								}
							}
						}
					} else {
						/** その他の項目は部分一致 */
						$option[$search_key.' LIKE'] = "%{$search_value}%";
					}
				}
			}

			/** 開始日と終了日を検索する */
			if ($lasttrade_start || $lasttrade_end) {
				if ($lasttrade_start == null) {
					$lasttrade_start =  date('Y')-100 .'-'. date('m') .'-'. date('d');
				}
				if ($lasttrade_end == null) {
					$lasttrade_end =  date('Y')+100 .'-'. date('m') .'-'. date('d');
				}
				$option['lasttrade BETWEEN ? AND ?'] = array($lasttrade_start, $lasttrade_end);
			}
			
			/** データを取得 */
			$ajax_views = $this->paginate($option);

			/** TwitterIDが無い場合、Twitterを使用しない */
			if (!empty($this->Twitter)) {
				$twitter = true;
			}

			/** FacebookIDが無い場合、Facebookを使用しない */
			if (!empty($this->Facebook)) {
				$facebook = true;
			}

			/** テーブルのViewを文字列として取得 */
			foreach ($ajax_views as $ajax_view) {
				$this->set('customer', $ajax_view);
				$this->set('twitter', $twitter);
				$this->set('facebook', $facebook);
				$datalist[] = strval($this->render($renderCtp));
			}
			
			/** ページのViewを文字列として取得 */
			$this->set('customers', $ajax_views);
			$page = strval($this->render('ajax_paginate'));
			
			/** JSONで返す */
			$this->response->type('json');
			$this->response->body(
				json_encode(
					array(
						'result' => true,
						'list' => $datalist,
						'page' => $page
					)
				)
			);
			return $this->response;
		}
	}

/**
 * twitter method
 *
 * @return void
 */
	public function twitter() {
		/** レイアウトを使用しない */
		$this->layout = false;
		$twitter_users = array();
		
		if (!empty($this->request->data['Customer']['twitter_id'])) {
			/** レイアウトを使用しない */
			$twitter_id = $this->request->data['Customer']['twitter_id'];

			$twitter_users = $this->Twitter->get(
				$this->accessToken->key,
				$this->accessToken->secret,
				'https://api.twitter.com/1.1/statuses/user_timeline.json',
				array(
					'screen_name' => $twitter_id,
					'include_rts' => true,
					'count' => 10
				)
			);
			$twitter_users = json_decode($twitter_users);
			if (isset($twitter_users->{'errors'})) {
				$this->autoRender = false;
				echo __('Twitter情報の取得に失敗しました。');
			} else {
				/** テンプレートに出力 */
				$this->set('twitter_users', $twitter_users);
			}
		} else {
			$this->autoRender = false;
			echo __('Twitter情報の取得に失敗しました。');
		}
	}

/**
 * facebook method
 *
 * @return void
 */
	public function facebook() {
		/** レイアウトを使用しない */
		$this->layout = false;
		$facebook_users = array();
		
		if (!empty($this->request->data['Customer']['facebook_id'])) {
			/** レイアウトを使用しない */
			$facebook_id = $this->request->data['Customer']['facebook_id'];
			try {
				$facebook_users = $this->Facebook->api('/'.$facebook_id.'/');
				$this->set('facebook_users', $facebook_users);
			} catch(Exception $e) {
				/** FaceBookAPIのエラーはFacebookApiException */
				$this->autoRender = false;
				echo __('FaceBook情報の取得に失敗しました。');
			}
		} else {
			$this->autoRender = false;
			echo __('FaceBook情報の取得に失敗しました。');
		}
	}

/**
 * googlemapSearch method
 *
 * @return void
 */
	public function googlemapSearch() {
		/** レイアウトを使用しない */
		$this->layout = false;
		
		/** Google検索を行い、座標を取得する */
		$search_word = null;
		if (!empty($this->request->data)) {
			$search_word = $this->request->data['Prefecture']['pref_name'];
			$search_word .= " ";
			$search_word .= $this->request->data['Customer']['address1'];
			$search_word .= " ";
			$search_word .= $this->request->data['Customer']['address2'];
		}
		/** テンプレートに出力 */
		$this->set('googlemap_searchs_word', h($search_word));
		
	}
}
