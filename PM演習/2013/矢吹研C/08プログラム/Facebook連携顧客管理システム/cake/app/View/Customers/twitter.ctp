<?php
	/** cake.generic.CSSの読込 */
	echo $this->Html->css(
		array('bootstrap.min'),
		null
	);
?>
<div class='customers twitter'>
	<h2><?php echo __('顧客のツイート');?></h2>
<?php
	foreach ($twitter_users as $twitter_user):
?>
    <div class="container-fluid">
		<div class="row-fluid">
			<div class="span2">
				<?php
					/** ツイッターアイコン表示 */
					echo $this->Html->image($twitter_user->{'user'}->{'profile_image_url'});
				?>
			</div>
			<div class="span10">
				<div class="well">
					<div class="date" style="color:#aaa;float:right">
						<?php
							$now=mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")) - 
									strtotime((string) $twitter_user->{'created_at'});

							$day=(int)($now/86400);
							$jikan=(int)($now/3600);
							$hun=(int)($now/60);
							$byou=(int)($now);

							if($byou<60){
								echo "たった今";
							}elseif($hun<60){
								echo "約" . $hun . "分前";
							}elseif($jikan<24){
								echo "約" . $jikan . "時間前";
							}elseif (10<$day) {
								echo date("Y/m/d");
							}elseif ($day>=1) {
								echo $day."日前";
							}else{
								echo date("Y/m/d");
							}
						?>
					</div>
					<strong>
						<?php echo $twitter_user->{'user'}->{'name'}; ?>
					</strong>
					<?php echo __("@".$twitter_user->{'user'}->{'screen_name'}); ?>
					<br>
					<?php echo __($twitter_user->{'text'}); ?>
				</div>
				<div class="clear"></div>
		    </div>
	    </div>
    </div>
<?php
	endforeach;
?>
	</div>
</div>
