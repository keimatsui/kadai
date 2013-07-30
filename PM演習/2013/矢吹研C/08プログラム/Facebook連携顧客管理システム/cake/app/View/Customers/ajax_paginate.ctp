<p>
	<?php
		echo $this->Paginator->counter(array(
		'format' => __('{:count} 件中 {:page} ページ目 ({:start} ～ {:end} 件表示)')
		));
	?>
</p>

<?php
	echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
	echo $this->Paginator->numbers(array('separator' => ''));
	echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
?>
