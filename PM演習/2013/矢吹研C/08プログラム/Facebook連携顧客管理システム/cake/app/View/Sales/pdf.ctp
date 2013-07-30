<?php
	/** ドキュメント設定 */
	$this->Tcpdf->pdf->SetCreator(PDF_CREATOR);
	$this->Tcpdf->pdf->SetAuthor('');
	$this->Tcpdf->pdf->SetTitle('');
	$this->Tcpdf->pdf->SetSubject('');
	$this->Tcpdf->pdf->SetKeywords('');
	
	/** ヘッダ・フッタ設定 */
	$this->Tcpdf->pdf->setPrintHeader(true);
	$this->Tcpdf->pdf->setPrintFooter(false);
	$this->Tcpdf->pdf->setHeaderFont(array('kozgopromedium', '', PDF_FONT_SIZE_MAIN));
	$this->Tcpdf->pdf->SetHeaderData('', '', "売上帳票", '');
	
	/** 余白設定 */
	$this->Tcpdf->pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

	/** 改ページ設定 */
	$this->Tcpdf->pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	/** 画像単位設定 */
	$this->Tcpdf->pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	/** 初期化 */
	$this->Tcpdf->pdf->AliasNbPages();

	/** ページ追加 */
	$this->Tcpdf->pdf->AddPage();

	/** フォント設定 */
	$this->Tcpdf->pdf->SetFont('kozgopromedium', '', 10);

	/** セル作成 */
	$tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
	<tr style="background-color:#FFFF00;color:#0000FF;">
			<th>顧客名</th>
			<th>会社名</th>
			<th>住所</th>
			<th>製品名</th>
			<th>購入日</th>
			<th>個数</th>
			<th>単価</th>
			<th>金額</th>
	</tr>
EOD;
	foreach ($sales as $sale) {
		$tbl .= <<<EOD
	<tr>
		<td>
			{$sale['Customer']['name']}
		</td>
		<td>
			{$sale['Company']['company_name']}
		</td>
		<td>
			{$sale['Customer']['address1']}
		</td>
		<td>
			{$sale['Product']['product_name']}
		</td>
		<td>
			{$sale['Sale']['purchase_date']}
		</td>
		<td>
			{$sale['Sale']['amount']}
		</td>
		<td>
			{$this->Number->format($sale['Product']['unit_price'],
						array(
						    'places' => 0,
						    'before' => '￥',
						    'escape' => false,
						    'decimals' => '.',
						    'thousands' => ','
						)
					)}
		</td>
		<td>
			{$this->Number->format($sale['Sale']['money'],
						array(
						    'places' => 0,
						    'before' => '￥',
						    'escape' => false,
						    'decimals' => '.',
						    'thousands' => ','
						)
					)}
		</td>
	</tr>
EOD;
	}
	$tbl .= <<<EOD
</table>
EOD;


	/** 出力 */
	ob_end_clean();
	$this->Tcpdf->pdf->writeHTML($tbl, true, false, false, false, '');
	$this->Tcpdf->pdf->Output('sales.pdf', 'I');
?>


