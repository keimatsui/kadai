<div class="customers form">
<?php echo $this->Form->create('Customer', array('class' => 'form-horizontal'));?>
	<fieldset>
		<legend><?php echo __('顧客登録'); ?></legend>
		<div class="control-group">
		<?php
			/** 顧客コード */
			echo $this->Form->label(
				'customer_cd',
				'<font color="red">*</font> 顧客コード',
				array('class' => 'control-label', 'for' => 'customer_cd')
			);
			echo $this->Form->input(
				'customer_cd',
				array('label' => false, 'class' => 'input-small', 'div' => array('class' => 'controls'))
			);
		?>
		</div>
		<div class="control-group">
		<?php
			/** 顧客名 */
			echo $this->Form->label(
				'name',
				'<font color="red">*</font> 顧客名',
				array('class' => 'control-label', 'for' => 'name')
			);
			echo $this->Form->input(
				'name',
				array('label' => false, 'class' => 'input-xlarge', 'div' => array('class' => 'controls'))
			);
		?>
		</div>
		<div class="control-group">
		<?php
			/** 顧客名(カナ) */
			echo $this->Form->label(
				'kana',
				'<font color="red">*</font> 顧客名(カナ)',
				array('class' => 'control-label', 'for' => 'kana')
			);
			echo $this->Form->input(
				'kana',
				array('label' => false, 'class' => 'input-xlarge', 'div' => array('class' => 'controls'))
			);
		?>
		</div>
		<div class="control-group">
		<?php
			/** 性別 */
			echo $this->Form->label(
				'gender',
				'性別',
				array('class' => 'control-label', 'for' => 'gender')
			);
			echo $this->Form->input(
				'gender',
				array(
					'type'=>'radio',
					'value' => 1,
					'legend' => false,
					'separator' => '',
					'options' => array('1' => '男性', '2' => '女性'),
					'div' => array('class' => 'controls radio')
				)
			);
		?>
		</div>
		
		<div class="control-group">
		<?php
			/** 郵便番号 */
			echo $this->Form->label(
				'zip',
				'郵便番号',
				array('class' => 'control-label', 'for' => 'zip')
			);
			echo $this->Form->input(
				'zip',
				array('label' => false, 'class' => 'input-small', 'div' => array('class' => 'controls'))
			);
		?>
		</div>
		<div class="control-group">
		<?php
			/** 都道府県 */
			echo $this->Form->label(
				'prefecture_id',
				'<font color="red">*</font> 都道府県',
				array('class' => 'control-label', 'for' => 'prefecture_id')
			);
			echo $this->Form->input(
				'prefecture_id',
				array(
					'label' => false,
					'type' => 'select',
					'options' => $prefectures,
					'empty' => '- 選択してください -',
					'class' => 'input-xlarge',
					'div' => array('class' => 'controls')
				)
			);
		?>
		</div>
		<div class="control-group">
		<?php
			/** 住所1 */
			echo $this->Form->label(
				'address1',
				'住所1',
				array('class' => 'control-label', 'for' => 'address1')
			);
			echo $this->Form->input(
				'address1',
				array('label' => false, 'class' => 'input-xxlarge', 'div' => array('class' => 'controls'))
			);
		?>
		</div>
		<div class="control-group">
		<?php
			/** 住所2 */
			echo $this->Form->label(
				'address2',
				'住所2',
				array('class' => 'control-label', 'for' => 'address2')
			);
			echo $this->Form->input(
				'address2',
				array('label' => false, 'class' => 'input-xxlarge', 'div' => array('class' => 'controls'))
			);
		?>
		</div>
		<div class="control-group">
		<?php
			/** 電話番号 */
			echo $this->Form->label(
				'phone',
				'<font color="red">*</font> 電話番号',
				array('class' => 'control-label', 'for' => 'phone')
			);
			echo $this->Form->input(
				'phone',
				array(
					'label' => false,
					'class' => 'input-large',
					'div' => array('class' => 'controls'),
					'after'=> '<span class="help-inline">「-」で区切って下さい</span>'
				)
			);
			echo $this->Form->label(
				'phone',
				'',
				array('class' => 'control-label', 'for' => 'phone')
			);
		?>
		</div>
		<div class="control-group">
		<?php
			/** FAX */
			echo $this->Form->label(
				'fax',
				'FAX',
				array('class' => 'control-label', 'for' => 'fax')
			);
			echo $this->Form->input(
				'fax',
				array(
					'label' => false,
					'class' => 'input-large',
					'div' => array('class' => 'controls'),
					'after'=> '<span class="help-inline">「-」で区切って下さい</span>'
				)
			);
		?>
		</div>
		<div class="control-group">
		<?php
			/** メールアドレス */
			echo $this->Form->label(
				'email',
				'<font color="red">*</font> メールアドレス',
				array('class' => 'control-label', 'for' => 'email')
			);
			echo $this->Form->input(
				'email',
				array('label' => false, 'class' => 'input-xlarge', 'div' => array('class' => 'controls'))
			);
		?>
		</div>
		<div class="control-group">
		<?php
			/** 最終取引日 */
			echo $this->Form->label(
				'lasttrade',
				'最終取引日',
				array('class' => 'control-label', 'for' => 'lasttrade')
			);
			echo $this->Form->input(
				'lasttrade',
				array(
					'label' => false,
					'type' => 'date',
					'dateFormat' => 'YMD',
					'minYear' => date('Y')-5,
					'maxYear' => date('Y'),
					'monthNames' => false,
					'separator' => '/',
					'class' => 'input-small',
					'div' => array('class' => 'controls')
				)
			);
		?>
		</div>
		
		<div class="control-group">
		<?php
			/** Facebook ID */
			echo $this->Form->label(
				'facebook_id',
				'Facebook ID',
				array('class' => 'control-label', 'for' => 'facebook_id')
			);
			echo $this->Form->input(
				'facebook_id',
				array(
					'label' => false,
					'type' => 'text',
					'class' => 'input-xlarge',
					'div' => array('class' => 'controls'),
					'after'=> '<span class="help-inline">「http://www.facebook.com/?id=********」もしくは、「http://www.facebook.com/********」の「*」部分</span>'
				)
			);
		?>
		</div>
	</fieldset>
	<font color="red">*</font> がついている項目はかならず入力してください。
	<div class="form-actions">
	<?php
		echo $this->Form->button('登録', array('class' => 'btn btn-primary'));
		echo $this->Html->link(
			__('キャンセル'),
			array('action' => 'index'),
			array('class' => 'btn')
		);
	?>
	</div>
	<?php
		echo $this->Form->end();
	?>
</div>
