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
		<th class="actions"><?php echo __('更新削除');?></th>
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
		<td>
			<?php
				/** 更新ボタン */
				echo $this->Html->link(
					__('更新'),
					array('action' => 'edit', $customer['Customer']['id']),
					array('class' => 'btn btn-primary btn-small')
				);
			?>
			<?php
				/** 削除ボタン */
				echo $this->Form->postLink(
					__('削除'),
					array('action' => 'delete', $customer['Customer']['id']),
					array('class' => 'btn btn-primary btn-small'),
					__('削除してもよろしいですか？ # %s?', $customer['Customer']['id'])
				);
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
</div>
