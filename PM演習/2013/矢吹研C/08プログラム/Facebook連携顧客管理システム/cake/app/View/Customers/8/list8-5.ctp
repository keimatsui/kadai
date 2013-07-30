	<tr>
		<td><?php echo h($customer['Customer']['customer_cd']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['name']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['kana']); ?>&nbsp</td>
		<td><?php echo h($customer['Company']['company_name']); ?>&nbsp</td>
		<td><?php echo h($customer['Prefecture']['pref_name']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['phone']); ?>&nbsp;</td>
		<td><?php echo h($customer['Customer']['email']); ?>&nbsp;</td>
		<td>
			<?php
				if (!empty($customer['Customer']['twitter_id']) && $twitter == true) {
					/** Twitterフォーム */
					echo $this->Form->create(
						'Customer',
						array('action' => 'twitter','target' => 'blank')
					);
					echo $this->Form->input(
						'Customer.twitter_id', 
						array('value' => $customer['Customer']['twitter_id'], 'type'=>'hidden')
					);
					/** Twitterボタン */
					echo $this->Form->button('Twitter', array('class' => 'btn btn-small'));
					echo $this->Form->end();
				}

				if (!empty($customer['Customer']['facebook_id']) && $facebook == true) {
					/** Facebookフォーム */
					echo $this->Form->create(
						'Customer',
						array('action' => 'facebook','target' => 'blank')
					);
					echo $this->Form->input(
						'Customer.facebook_id',
						array('value' => $customer['Customer']['facebook_id'], 'type'=>'hidden')
					);
					/** Facebookボタン */
					echo $this->Form->button('Facebook', array('class' => 'btn btn-small'));
					echo $this->Form->end();
				}

				/** GoogleMapフォーム */
				echo $this->Form->create(
					'Customer',
					array('action' => 'googlemapSearch','target' => 'blank')
				);
				echo $this->Form->input(
					'Prefecture.pref_name',
					array('value' => $customer['Prefecture']['pref_name'], 'type'=>'hidden')
				);
				echo $this->Form->input(
					'Customer.address1',
					array('value' => $customer['Customer']['address1'], 'type'=>'hidden')
				);
				echo $this->Form->input(
					'Customer.address2',
					array('value' => $customer['Customer']['address2'], 'type'=>'hidden')
				);
				/** GoogleMapボタン */
				echo $this->Form->button('Map', array('class' => 'btn btn-small'));
				echo $this->Form->end();
			?>
		</td>
		<td>
			<?php
				/** 更新ボタン */
				echo $this->Html->link(
					__('更新'),
					array('action' => 'edit', $customer['Customer']['id']),
					array('class' => 'btn btn-primary btn-small')
				);
			?>
			<?php
				/** 削除ボタン */
				echo $this->Form->postLink(
					__('削除'),
					array('action' => 'delete', $customer['Customer']['id']),
					array('class' => 'btn btn-primary btn-small'),
					__('削除してもよろしいですか？ # %s?', $customer['Customer']['id'])
				);
			?>
		</td>
	</tr>
