<div class="customers">
	<h2><?php echo __('顧客インポート');?></h2>
	<div class="well">
		<?php
			/** 顧客検索 */
			echo $this->Form->create('Customer', array('type' => 'file'));
			echo $this->Form->file('file_name');
			echo $this->Form->submit('実行', array('class' => 'btn btn-primary'));
			echo $this->Form->end();
		?>
	</div>
</div>