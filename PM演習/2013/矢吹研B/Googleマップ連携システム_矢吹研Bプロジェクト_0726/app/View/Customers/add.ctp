<div class="customers form">
<?php echo $this->Form->create('Customer'); ?>
	<fieldset>
		<legend><?php echo __('Add Customer'); ?></legend>
	<?php
		echo $this->Form->input('customer_cd');
		echo $this->Form->input('name');
		echo $this->Form->input('kana');
		echo $this->Form->input('zip');
		echo $this->Form->input('prefecture_id');
		echo $this->Form->input('address1');
		echo $this->Form->input('address2');
		echo $this->Form->input('phone');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Customers'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Prefectures'), array('controller' => 'prefectures', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prefecture'), array('controller' => 'prefectures', 'action' => 'add')); ?> </li>
	</ul>
</div>
