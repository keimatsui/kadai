<div class="customers index">
	<h2><?php echo __('Customers'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('customer_cd'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('kana'); ?></th>
			<th><?php echo $this->Paginator->sort('zip'); ?></th>
			<th><?php echo $this->Paginator->sort('prefecture_id'); ?></th>
			<th><?php echo $this->Paginator->sort('address1'); ?></th>
			<th><?php echo $this->Paginator->sort('address2'); ?></th>
			<th><?php echo $this->Paginator->sort('phone'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($customers as $customer): ?>
	<tr>
		<td><?php echo h($customer['Customer']['id']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['customer_cd']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['name']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['kana']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['zip']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($customer['Prefecture']['id'], array('controller' => 'prefectures', 'action' => 'view', $customer['Prefecture']['id'])); ?>
		</td>
		<td><?php echo h($customer['Customer']['address1']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['address2']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['phone']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['created']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $customer['Customer']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $customer['Customer']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $customer['Customer']['id']), null, __('Are you sure you want to delete # %s?', $customer['Customer']['id']));?>
			 <?php echo $this->Form->create(
				'Customer',
				array('action'=>'googlemapSearch','target'=>'blank')
				);
			echo $this->Form->input(
				'Prefecture.pref_name',
				array('value'=>$customer['Prefecture']['pref_name'],
					'type'=>'hidden')
				);
			echo $this->Form->input(
				'Customer.address1',
				array('value'=>$customer['Customer']['address1'],
					'type'=>'hidden')
				);
			echo $this->Form->input(
				'Customer.address2',
				array('value'=>$customer['Customer']['address2'],
					'type'=>'hidden')
				);?>
			<?php echo $this->Form->button('Map',array('class'=>
			'btn btn-small'));
			echo $this->Form->end();?>
			

		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Customer'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Prefectures'), array('controller' => 'prefectures', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Prefecture'), array('controller' => 'prefectures', 'action' => 'add')); ?> </li>
	</ul>
</div>
