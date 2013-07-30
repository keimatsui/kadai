<?php

App::uses('AppHelper', 'View/Helper');

/**
 * Csv Helper
 *
 * @package       app.View.Helper
 */
class CsvHelper extends AppHelper {
	/** 1行分のデータ */
	public $line = array(); 
	/** CSV形式データのバッファ */
	public $buffer;
	
	/** 区切り文字 */
	public $delimiter = ',';

	/** 値の囲い文字 */
	public $enclosure = '"';

	/** 出力ファイル名 */
	public $filename = 'Export.csv';
	
/**
 * constructor method
 *
 * @return void
 */
	public function __construct() {
		/** 初期化 */
		$this->clear(); 
	} 

/**
 * clear method
 *
 * @return void
 */
	public function clear() {
		/** クリア */
		$this->line = array(); 
		$this->buffer = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+'); 
	} 

/**
 * addField method
 *
 * @param string $value
 * @return void
 */
	function addField($value) {
		/** フィールド追加 */
		$this->line[] = $value; 
	} 

/**
 * endRow method
 *
 * @return void
 */
	function endRow() { 
		$this->addRow($this->line); 
		$this->line = array(); 
	} 

/**
 * addRow method
 *
 * @param string $row
 * @return void
 */
	function addRow($row) {
		/** CSVとしてフォーマットしファイルへ書き込む */
		fputcsv($this->buffer, $row, $this->delimiter, $this->enclosure); 
	} 

/**
 * setFilename method
 *
 * @param string $filename
 * @return void
 */
	function setFilename($filename) { 
		$this->filename = $filename; 
		if (strtolower(substr($this->filename, -4)) != '.csv') { 
			/** 拡張子の追加 */
			$this->filename .= '.csv'; 
		} 
	} 

/**
 * renderHeaders method
 *
 * @return void
 */
	function renderHeaders() { 
		/** 出力データのタイプ設定 */
		header("Content-type:text/csv"); 
		header("Content-disposition:attachment;filename=".$this->filename); 
	} 

/**
 * render method
 *
 * @param string $to_encoding, $from_encoding
 * @return $this->output($output)
 */
	function render($to_encoding = null, $from_encoding = "auto") { 
		/** 出力データのタイプ設定 */
		$this->renderHeaders(); 
		/** ファイルポインタの位置を元に戻す */
		rewind($this->buffer); 
		/** 残ストリームを文字列に読み込む */
		$output = stream_get_contents($this->buffer); 

		if ($to_encoding) { 
			/** 文字エンコーディングを変換する */
			$output = mb_convert_encoding($output, $to_encoding, $from_encoding); 
		} 
		return $this->output($output); 
	} 
} 
