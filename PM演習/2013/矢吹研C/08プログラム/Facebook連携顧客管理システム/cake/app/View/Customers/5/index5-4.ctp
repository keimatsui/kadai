<div class="customers index">
	<h2><?php echo __('顧客一覧');?></h2>
	<div class="pull-right">
		<?php
			echo $this->Html->link(
				__('新規登録'),
				array(
					'action' => 'add'
				),
				array('class' => 'btn btn-primary btn-small')
			);
		?>
	</div>
	<br>
	<br>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo __('顧客コード'); ?></th>
		<th><?php echo __('顧客名'); ?></th>
		<th><?php echo __('顧客名(カナ)'); ?></th>
		<th><?php echo __('会社名'); ?></th>
		<th><?php echo __('都道府県'); ?></th>
		<th><?php echo __('電話番号'); ?></th>
		<th><?php echo __('Email'); ?></th>
	</tr>
	<?php
		foreach ($customers as $customer):
	?>
	<tr>
		<td><?php echo h($customer['Customer']['customer_cd']); ?></td>
		<td><?php echo h($customer['Customer']['name']); ?></td>
		<td><?php echo h($customer['Customer']['kana']); ?></td>
		<td><?php echo h($customer['Company']['company_name']); ?></td>
		<td><?php echo h($customer['Prefecture']['pref_name']); ?></td>
		<td><?php echo h($customer['Customer']['phone']); ?></td>
		<td><?php echo h($customer['Customer']['email']); ?></td>
	</tr>
<?php endforeach; ?>
	</table>
</div>