<div class="users form">
<?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>
	<fieldset>
	<legend><?php echo __('ログイン'); ?></legend>
		<div class="control-group">
		<?php
			/** ユーザ名 */
			echo $this->Form->label(
				'username',
				__('ユーザ名'),
				array('class' => 'control-label', 'for' => 'username')
			);
			echo $this->Form->input(
				'username',
				array('label' => false, 'class' => 'input-xlarge', 'div' => array('class' => 'controls'))
			);
		?>
		</div>
		<div class="control-group">
		<?php
			/** パスワード */
			echo $this->Form->label(
				'password',
				__('パスワード'),
				array('class' => 'control-label', 'for' => 'password')
			);
			echo $this->Form->input(
				'password',
				array('label' => false, 'class' => 'input-xlarge', 'div' => array('class' => 'controls'))
			);
		?>
		</div>
		<div class="form-actions">
		<?php
			echo $this->Form->button(__('ログイン'), array('class' => 'btn btn-primary'));
			echo $this->Form->end();
		?>
		</div>
	</fieldset>
</div>
