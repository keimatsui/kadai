/**  
 * Customers
 * 
 * @action index
 */
var CUSTOMERS={};
var target = null;
var senddata = null;
var clearflg = null;
CUSTOMERS.page = 1;

CUSTOMERS.init = function() {
	/** ID:customer_tableにtablesorterを適用 */
	$('#customer_table').tablesorter({
		// ボタン郡のTHのソートを無効にする
		headers: {
			7: {sorter: false}
		}
	});
	
	/** フォーム内でクリックした要素を格納 */
	$('#CustomerIndexForm :input').click(function() {
		target = this;
	});
	
	/** ダイナミックなデータの絞り込み */
	$('#CustomerIndexForm').find(':input').each(function() {
		$(this).bind('keydown keyup keypress change',function(){
			CUSTOMERS.search(false);
		});
	});
    
	/** ID:CustomerIndexFormのsubmit要素に検索関数を適用 */
	$('#CustomerIndexForm').submit(
		function(e) {
			if (target.name != null && target.name == 'clear') {
				clearflg = true;
				/** フォーム内の入力値削除 */
				$(this).find(':input').each(function() {
					if (this.type != 'submit') {
						$(this).val('');
					}
				});
			} else{
				clearflg = false;
			}
			CUSTOMERS.search(clearflg);
		}
	);
	
	/** テーブル、ページの初期化 */
	CUSTOMERS.search(false);
};

CUSTOMERS.search = function(n) {
	var senddata = null;
	/** 取引日の作成 */
	var tradeStart = {
		'year': $('#CustomerLasttradeStartYear').val(),
		'month': $('#CustomerLasttradeStartMonth').val(),
		'day': $('#CustomerLasttradeStartDay').val()
	};
	var tradeEnd = {
		'year': $('#CustomerLasttradeEndYear').val(),
		'month': $('#CustomerLasttradeEndMonth').val(),
		'day': $('#CustomerLasttradeEndDay').val()
	};

	/** 入力フィールドから値の取得 */
	if (n != true) {
		senddata = {
			page: CUSTOMERS.page,
			customer_cd: $('#CustomerCustomerCd').val(),
			company_name: $('#CustomerCompanyName').val(),
			name: $('#CustomerName').val(),
			kana: $('#CustomerKana').val(),
			phone: $('#CustomerPhone').val(),
			zip: $('#CustomerZip').val(),
			prefecture_id: $('#CustomerPrefectureId').val(),
			email: $('#CustomerEmail').val(),
		};
	}
	
	$.ajax({
		type: 'POST',
		url: '/cake/customers/ajaxList/list8-2',
		dataType: 'json',
		data: senddata,
		success: function(data, dataType) {
			try {
				/** 初期化 */
				var datahtml = '';
				
				/** データを結合 */
				$.each(data.list, function(k, v) {
					datahtml += v;
				});

				/** テーブルを画面に出力 */
				$('#customer_table tbody').html(datahtml);
				$('table').trigger('update');

				/** ページングを画面に出力 */
				$('#pagination').html(data.page);
				$('#pagination a').click(function() {
					var href = $(this).attr('href');
					/** ページを取得 */
					var pageno = href.split('page:');
					CUSTOMERS.page = pageno[1];
					CUSTOMERS.search();
					return false;
				});
			} catch(e) {
				/** エラー時なにも出力しない */
			}
		}
	});
};

/** 
 * ウインドウ読み込み完了イベント関数
 * 
 * @return void
 */
$(window).load(function() {
	CUSTOMERS.init();
});

