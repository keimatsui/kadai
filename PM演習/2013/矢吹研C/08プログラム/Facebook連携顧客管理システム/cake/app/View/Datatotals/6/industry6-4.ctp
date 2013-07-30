<div class="datatotal form">
	<h2><?php echo __('業種ごとの顧客数');?></h2>
	<br>
	<br>
	<table cellpadding="0" cellspacing="0" class="table">
	<tr>
		<th><?php echo "業種";?></th>
		<th><?php echo "顧客数";?></th>
	</tr>
	<?php
		foreach ($businesscategories as $business) :
	?>
	<tr>
		<td><?php echo h($business['BusinessCategory']['business_category_name']); ?></td>
		<td>
			<?php 
				echo h($business['BusinessCategory']['customer_count']);
			?>
			&nbsp;
		</td>
	</tr>
	<?php endforeach; ?>
	</table>
</div>
