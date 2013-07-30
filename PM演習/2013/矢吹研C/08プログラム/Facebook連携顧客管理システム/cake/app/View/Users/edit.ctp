<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Edit User'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username');
		echo $this->Form->input('password', array(
			'value' => '',
			'disabled'=>'disabled',
			'after'=> '<span class="help-inline"><label class="checkbox">'.
				'<input type="checkbox" onclick="clickToPass()"> パスワードを変更する'.
				'</label></span>'
			)
		);
	
		echo $this->Form->input('facebook_appid');
		echo $this->Form->input('facebook_appsecret');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<script type="text/javascript">
<!--
	//活性、非活性を切り替える
	var userpass = document.getElementById('UserPassword');
	function clickToPass() {
		if (userpass.disabled == true) {
			userpass.disabled = false;
		} else {
			userpass.disabled = true;
		}
	}
// -->
</script>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('User.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('User.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index'));?></li>
	</ul>
</div>
