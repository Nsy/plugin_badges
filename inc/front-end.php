<?php

	echo $before_widget;
	echo $before_title . $title . $after_title;

?>
<!-- END BEFORE -->

<?php
	$total_badges = count($wptreehouse_profile->{'badges'});
	for ($i = $total_badges -1; $i >= $total_badges - $num_badges; $i-- ):
?>

<?php if ($display_tooltip == '1') : ?>

	<p><img class="big_img" alt="badge <?php echo $wptreehouse_profile->{'badges'}[$i]->{'name'}; ?>" src="<?php echo $wptreehouse_profile->{'badges'}[$i]->{'icon_url'}; ?>"><br />
	<?php echo $wptreehouse_profile->{'badges'}[$i]->{'name'}; ?></p>

<?php else : ?>

	<img class="small_img" alt="badge <?php echo $wptreehouse_profile->{'badges'}[$i]->{'name'}; ?>" src="<?php echo $wptreehouse_profile->{'badges'}[$i]->{'icon_url'}; ?>">

<?php endif; ?>

<?php endfor; ?>
<br />
<!-- START AFTER -->
<?php

	echo $after_widget;
 