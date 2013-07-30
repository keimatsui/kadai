<div class="sales index">
	<h2><?php echo __('売上一覧');?></h2>
	<br><br>
	<table cellpadding="0" cellspacing="0" class="table">
	<tr>
		<th><?php echo __('顧客名'); ?></th>
		<th><?php echo __('会社名'); ?></th>
		<th><?php echo __('住所'); ?></th>
		<th><?php echo __('製品名'); ?></th>
		<th><?php echo __('購入日'); ?></th>
		<th><?php echo __('個数'); ?></th>
		<th><?php echo __('単価'); ?></th>
		<th><?php echo __('金額'); ?></th>
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
			<?php echo h($sale['Company']['company_name']); ?>
		</td>
		<td>
			<?php echo h($sale['Customer']['address1']); ?>
		</td>
		<td>
			<?php echo h($sale['Product']['product_name']); ?>
		</td>
		<td><?php echo h($sale['Sale']['purchase_date']); ?></td>
		<td><?php echo h($sale['Sale']['amount']); ?></td>
		<td>
			<?php
				/** 金額フォーマットに整形 */
				echo h(
					$this->Number->format(
						h($sale['Product']['unit_price']),
						array(
						    'places' => 0,
						    'before' => '￥',
						    'thousands' => ','
						)
					)
				);
			?>
		</td>
		<td>
			<?php
				/** 金額フォーマットに整形 */
				echo h(
					$this->Number->format(
						h($sale['Sale']['money']),
						array(
						    'places' => 0,
						    'before' => '￥',
						    'thousands' => ','
						)
					)
				);
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
