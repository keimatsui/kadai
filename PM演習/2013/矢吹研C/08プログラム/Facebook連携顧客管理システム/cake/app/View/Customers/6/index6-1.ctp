<div class="customers index">
	<h2><?php echo __('顧客一覧');?></h2>
	<div class="well">
		<?php
			/** 顧客検索 */
			echo $this->Form->create('Customer', array('class' => 'form-horizontal'));
		?>
		<table>
			<tr>
				<td valign="top" style="padding-right:20px">
					<?php
						echo $this->Form->input('customer_cd', array('label'=>'顧客コード'));
						echo $this->Form->input('name', array('label'=>'顧客名'));
						echo $this->Form->input('kana', array('label'=>'顧客名(カナ)'));
						echo $this->Form->input('company_name', array('label'=>'会社名'));
					?>
				</td>
				<td valign="top">
					<?php
						echo $this->Form->input(
							'prefecture_id',
							array('label'=>'都道府県',
								'type'=>'select',
								'empty'=>'- 選択してください -',
								'options'=>$prefectures)
							);
						echo $this->Form->input('phone', array('label'=>'電話番号'));
						echo $this->Form->input('email', array('label'=>'メールアドレス'));
						echo $this->Form->label(
							'lasttrade',
							'最終取引日'
						);
						echo $this->Form->input(
							'lasttrade_start',
							array(
								'label' => false,
								'type' => 'date',
								'dateFormat' => 'YMD',
								'minYear' => 2000,
								'maxYear' => date('Y'),
								'monthNames' => false,
								'separator' => '/',
								'empty' => '-',
								'class' => 'input-mini ',
								'div' => false,
							)
						);
						echo ' ～ ';
						echo $this->Form->input(
							'lasttrade_end',
							array(
								'label' => false,
								'type' => 'date',
								'dateFormat' => 'YMD',
								'minYear' => 2000,
								'maxYear' => date('Y'),
								'monthNames' => false,
								'separator' => '/',
								'empty' => '-',
								'class' => 'input-mini',
								'div' => false
							)
						);
					?>
				</td>
			</tr>
		</table>
		<?php
			echo $this->Form->button('検索', array('name' => 'search', 'type' => 'submit', 'class' => 'btn  btn-primary btn-small'));
			echo '&nbsp;';
			echo $this->Form->button('クリア', array('name' => 'clear', 'type' => 'submit', 'class' => 'btn  btn-primary btn-small'));
			echo $this->Form->end();
		?>
	</div>
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
