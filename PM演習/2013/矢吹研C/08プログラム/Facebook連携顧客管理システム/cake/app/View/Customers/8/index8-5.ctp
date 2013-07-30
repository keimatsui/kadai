<?php
	/** tablesorterの読込 */
	echo $this->Html->script(
		array('jquery.tablesorter.min','Customers/8/index8-5'),
		array('inline'=>false)
	);
	
	/** tablesorter.CSSの読込 */
	echo $this->Html->css(
		array('tablesorter'),
		null,
		array('inline'=>false)
	);
?>

<div class="customers index">
	<h2><?php echo __('顧客一覧');?></h2>
	<div class="well">
		<?php
			/** 顧客検索 */
			echo $this->Form->create('Customer', array('action' => 'index', 'default' => false, 'class' => 'form-horizontal'));
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
			echo $this->Form->submit('クリア', array('name' => 'clear', 'class' => 'btn  btn-primary btn-small'));
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
		<?php
			echo $this->Html->link(
				__('インポート'),
				array(
					'action' => 'csvImport'
				),
				array('class' => 'btn btn-primary btn-small')
			);
		?>
		<?php
			echo $this->Html->link(
				__('エクスポート'),
				array(
					'action' => 'csvExport'
				),
				array('class' => 'btn btn-primary btn-small')
			);
		?>
	</div>
	<br>
	<br>
	<table cellpadding="0" cellspacing="0" id="customer_table" class="tablesorter table">
		<thead> 
			<tr>
				<th><?php echo __('顧客コード'); ?></th>
				<th><?php echo __('顧客名'); ?></th>
				<th><?php echo __('顧客名(カナ)'); ?></th>
				<th><?php echo __('会社名'); ?></th>
				<th><?php echo __('都道府県'); ?></th>
				<th><?php echo __('電話番号'); ?></th>
				<th><?php echo __('Email'); ?></th>
				<th><?php echo __('サービス'); ?></th>
				<th><?php echo __('更新削除'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php /** AJAXでテーブルを作成するため空要素 */ ?>
		</tbody>
	</table>
	
	<div id="pagination" class="paging">
		<?php /** AJAXでページングを作成するため空要素 */ ?>
	</div>
</div>
