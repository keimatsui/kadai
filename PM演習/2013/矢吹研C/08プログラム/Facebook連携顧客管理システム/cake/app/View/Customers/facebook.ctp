<?php
	/** cake.generic.CSSの読込 */
	echo $this->Html->css(
		array('bootstrap.min'),
		null
	);
?>
<div class="customers facebook">
	<h2><?php echo __('Facebook情報');?></h2>
	<table class="table">
		<tr>
			<td>
				Facebook ID
			</td>
			<td>
				<?php echo __($facebook_users['id']); ?>
			</td>
		</tr>
		<tr>
			<td>
				ユーザーネーム
			</td>
			<td>
				<?php echo __($facebook_users['username']); ?>
			</td>
		</tr>
		<tr>
			<td>
				名前
			</td>
			<td>
				<?php echo __($facebook_users['name']); ?>
			</td>
		</tr>
		<tr>
			<td>
				性別
			</td>
			<td>
				<?php 
					if ($facebook_users['gender'] == 'male') {
						echo __("男");
					} 
					if ($facebook_users['gender'] == 'female') {
						echo __("女");
					}
				?>
			</td>
		</tr>
		
	</table>
</div>

