	<div class="well">
		<?php
			/** フォームを出力 */
			echo $this->Form->create(
				'Datatotal',
				array(
					'action' => 'tweet',
					'class' => 'form-horizontal'
				)
			);
			echo $this->Form->textarea('tweet');
		?>
		<br>
		<?php
			echo $this->Form->submit(__('ツイート'), array('class' => 'btn'));
			echo $this->Form->end();
		?>
	</div>
