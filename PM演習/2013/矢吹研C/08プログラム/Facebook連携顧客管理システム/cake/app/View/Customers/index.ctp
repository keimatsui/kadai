<?php
	/** tablesorterの読込 */
	echo $this->Html->script(
		array('jquery.tablesorter.min','Customers/index'),
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
	<table cellpadding="0" cellspacing="0" id="customer_table" class="tablesorter table">
		<thead> 
			<tr>
				<th><?php echo __('顧客コード'); ?></th>
				<th><?php echo __('顧客名'); ?></th>
				<th><?php echo __('顧客名(カナ)'); ?></th>
			
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
