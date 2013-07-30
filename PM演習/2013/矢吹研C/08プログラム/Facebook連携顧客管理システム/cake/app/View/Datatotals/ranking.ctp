<div class="datatotals index">
	<h2><?php echo __('製品別売上ランキング');?></h2>
	<?php 
		if($twitter){
			echo $this->element('Twitter/tweet');
		}
	?>
	<br>
	<br>
	<table cellpadding="0" cellspacing="0" class="table">
	<tr>
		<th><?php echo __('順位'); ?></th>
		<th><?php echo __('製品名');  ?></th>
		<th><?php echo __('単価'); ?></th>
		<th><?php echo __('売上個数'); ?></th>
		<th><?php echo __('売上合計金額'); ?></th>
	</tr>
	<?php
		/** 順位表示用変数 */
		$rank = 1;
		
		/** 取得したデータ分ループする */
		foreach ($sales as $sale):
	?>
	<tr>
		<td>
			<?php echo h($rank); ?>
		</td>
		<td>
			<?php
				echo h($sale['Product']['product_name']);
			?>
		</td>
		<td>
			<?php
				/** 金額フォーマットに整形 */
				echo $this->Number->format(
					h($sale['Product']['unit_price']),
					array(
					    'places' => 0,
					    'before' => '￥',
					    'thousands' => ','
					)
				);
			?>
		</td>
		<td><?php echo h($sale['Sale']['unit_amount']); ?></td>
		<td>
			<?php
				/** 金額フォーマットに整形 */
				echo $this->Number->format(
					h($sale['Sale']['money']),
					array(
					    'places' => 0,
					    'before' => '￥',
					    'thousands' => ','
					)
				);
			?>
		</td>
	</tr>
<?php
	/** 順位をインクリメント */
	$rank++;
	
	endforeach;
?>
	</table>
</div>
