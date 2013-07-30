<div class="datatotals index">
	<h2><?php echo __('平均客単価');?></h2>
	<br>
	<table cellpadding="0" cellspacing="0" class="table">
	<tr>
		<th><?php echo __('顧客名'); ?></th>
		<th><?php echo __('平均客単価');  ?></th>
	</tr>
	<?php
		/** 取得したデータ分ループする */
		foreach ($sales as $sale):
	?>
	<tr>
		<td>
			<?php echo h($sale['Customer']['name']); ?>
		</td>
		<td>
			<?php
				/** 金額フォーマットに整形 */
				echo $this->Number->format(
					h($sale['Sale']['avg']),
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
		endforeach;
	?>
	</table>
</div>

