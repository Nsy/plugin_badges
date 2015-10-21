<h3>Welcome !</h3>

<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h2><?php esc_attr_e( 'The official WP TreeHouse Badges', 'wp_admin_style' ); ?></h2>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-2">

			<!-- main content -->
			<div id="post-body-content">

				<div class="meta-box-sortables ui-sortable">

<?php if (!isset($wptreehouse_username) || $wptreehouse_username == '') : ?>
<!-- PART 1 -->
					<div class="postbox">

						<!-- Toggle -->

						<h3 class="hndle"><span>Let's get started</span>
						</h3>

						<div class="inside">
							<p>
								<form name="wptreehouse_username_form" method="post" action=""> <!-- add name -->
									<input type="hidden" name="wptreehouse_form_submitted" value="Y"> <!-- add this hidden input -->
									<table class="form-table">

										<tr valign="top">
											<td>
												<label for="wptreehouse_username">Treehouse username</label>
											</td>
											<td><input name="wptreehouse_username" id="wptreehouse_username" type="text" value="" class="regular-text" /><br></td>
										</tr>
									</table>

									<p>
										<input name="wptreehouse_username_submit" class="button-primary" type="submit" name="Example" value="Save" />
									</p>

								</form>


							</p>
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->
<!-- PART 1 -->
<?php else: ?>

<!-- PART 2 -->



					<div class="postbox">


						<h3 class="hndle"><span>Most recent badges</span>
						</h3>

						<div class="inside">
							<p>Below are your 20 most recent badges</p>
							
							<!-- dumb data with placeholder image for now -->

							<ul class="wptreehouse-badges">
								<?php
									$total_badges = count($wptreehouse_profile->{'badges'});
									for ($i = $total_badges -1; $i >= $total_badges - 20; $i-- ):
								?>
								<li>
									<ul>
										<li>
											<img class="wptreehouse-gravatar" width="120px" src="<?php echo $wptreehouse_profile->{'badges'}[$i]->{'icon_url'}; ?>">
										</li>

										<?php if ( $wptreehouse_profile->{'badges'}[$i]->{'url'} != $wptreehouse_profile->{'profile_url'} ) { ?>
										<li class="badge-name">
											<a href="<?php echo $wptreehouse_profile->{'badges'}[$i]->{'url'}; ?>">
												<?php echo $wptreehouse_profile->{'badges'}[$i]->{'name'}; ?>
											</a>
										</li>
										<li class="project-name">
											<a href="<?php echo $wptreehouse_profile->{'badges'}[$i]->{'courses'}[0]->{'url'}; ?>">
												<?php echo $wptreehouse_profile->{'badges'}[$i]->{'courses'}[1]->{'title'}; ?>
											</a>
										</li>
										<?php } else { ?>
										<li class="badge-name"><a><?php echo $wptreehouse_profile->{'badges'}[$i]->{'name'}; ?></a></li>
										<?php } ?>

									</ul>
								</li>
								<?php endfor; ?>
							</ul>

						</div>
						<div class="clear"></div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->


<!-- PART 4 -->
				<?php if ($display_json == true): ?>

					<div class="postbox">
						<h3>JSON FEED</h3>
						<div class="inside">

						<p>
							<?php echo $wptreehouse_profile->{'name'}; ?> <!--  get the name -->
						</p>
						<p>
							<?php echo $wptreehouse_profile->{'profile_url'}; ?> <!--  get the url -->
						</p>
						<p>
						<?php echo $wptreehouse_profile->{'badges'}[0]->{'name'}; ?> <!--  get the 1st badge name -->
						</p>
						<p>
						<?php echo $wptreehouse_profile->{'badges'}[1]->{'courses'}[1]->{'url'}; ?> <!-- get the second badge courses url -->
						</p>
						<p>
							<?php var_dump($wptreehouse_profile); ?> <!-- dump all for debug -->
						</p>
						</div>
					</div>
				<?php endif; ?>

<!-- PART 4 -->



<!-- PART 2 -->
<?php endif; ?>



				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->

			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

				<div class="meta-box-sortables">

<?php if (isset($wptreehouse_username) && $wptreehouse_username != '') : ?>
<!-- PART 3 -->

					<div class="postbox">


						<h3 class="hndle"><span><?php echo $wptreehouse_username; ?>'s Profile</span></h3>

							<div class="inside">
								<p>
									<img width="100%" class="wptreehouse-gravatar" src="<?php echo $wptreehouse_profile->{'gravatar_url'}; ?>" alt="Mike the Frog"></p>
									<ul class="wptreehouse-badges-and-points">
										<li>Badges: <strong><?php echo count($wptreehouse_profile->{'badges'}); /* count the number of badges in array */?></strong></li>
										<li>Points: <strong><?php echo $wptreehouse_profile->{'points'}->{'total'}; ?></strong></li>
									</ul>
								</p>




								<form name="wptreehouse_username_form" method="post" action=""> <!-- add name -->
									<input type="hidden" name="wptreehouse_form_submitted" value="Y"> <!-- add this hidden input -->
									<p>
											<label for="wptreehouse_username">Username</label>
									</p>
									<p>
										<input name="wptreehouse_username" id="wptreehouse_username" type="text" value="<?php echo $wptreehouse_username; ?>" />
										<input name="wptreehouse_username_submit" class="button-primary" type="submit" name="Example" value="Update" />
									</p>

								</form>





							</div>
							<!-- .inside -->

						</div>
						<!-- .postbox -->
<!-- PART 3 -->
<?php endif; ?>

					</div>
					<!-- .meta-box-sortables -->

				</div>
				<!-- #postbox-container-1 .postbox-container -->

			</div>
			<!-- #post-body .metabox-holder .columns-2 -->

			<br class="clear">
		</div>
		<!-- #poststuff -->

</div> <!-- .wrap -->