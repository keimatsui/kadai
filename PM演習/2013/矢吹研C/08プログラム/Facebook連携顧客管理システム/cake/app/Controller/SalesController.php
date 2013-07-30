<?php
App::uses('AppController', 'Controller');

/** HelperをControllerで使用する */
App::import('Helper', 'Tcpdf');

/**
 * Sales Controller
 *
 * @property Sale $Sale
 */
class SalesController extends AppController {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Tcpdf');

/**
 * Module name
 *
 * @var string
 */
	public $name = 'Sales';

/**
 * Use Model
 *
 * @var array
 */
	public $uses = array(
		'Sale',
		'Customer',
		'Company',
		'Product'
	);

/**
 * index method
 *
 * @return void
 */
	public function index() {
		/** バーチャルフィールドを使用し、個数*単価=金額を取得させる */
		$this->Sale->virtualFields = array('money' => '(Sale.amount * Product.unit_price)');
		
		/** 取得順序を設定 */
		$this->paginate = array('order' => 'Sale.money DESC');
		
		/** テンプレートに出力 */
		$this->set('sales', $this->paginate());
	}
	
/**
 * pdf method
 *
 * @return void
 */
	public function pdf() {
		/** PDF出力ヘッダー */
		$this->response->type('pdf');
		$this->layout = false;
		
		/** バーチャルフィールドを使用し、個数*単価=金額を取得 */
		$this->Sale->virtualFields = array('money' => '(Sale.amount * Product.unit_price)');
		
		/** 取得順序を設定 */
		$this->paginate = array('order' => 'Sale.money DESC');
		
		/** テンプレートに出力 */
		$this->set('sales', $this->paginate());
	}
}
