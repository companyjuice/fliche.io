<?php
	get_header('simple');
	global $wpl_exe_wp;
?>

<div class="container">
	<div class="row">
		<div class="col-md-12 primary-text">
			<?php echo wp_kses_post( $wpl_exe_wp->get_option('coming_soon_page_text', 'custom_modes') ); ?>
		</div>
	</div>
	<div class="row" id="countdown">
		<div class="col-md-3 col-xs-6 col-sm-3 part part-days">
			<div class="inner">
				<span class="days">00</span>
				<p class="timeRefDays"><?php _e('days', 'wproto'); ?></p>
			</div>
		</div>
		<div class="col-md-3 col-xs-6 col-sm-3 part part-hours">
			<div class="inner">
				<span class="hours">00</span>
				<p class="timeRefHours"><?php _e('hours', 'wproto'); ?></p>
			</div>
		</div>
		<div class="col-md-3 col-xs-6 col-sm-3 part part-minutes">
			<div class="inner">
				<span class="minutes">00</span>
				<p class="timeRefMinutes"><?php _e('mins', 'wproto'); ?></p>
			</div>
		</div>
		<div class="col-md-3 col-xs-6 col-sm-3 part part-seconds">
			<div class="inner">
				<span class="seconds">00</span>
				<p class="timeRefSeconds"><?php _e('secs', 'wproto'); ?></p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 footer-text">
			<?php echo wp_kses_post( $wpl_exe_wp->get_option('coming_soon_footer_text', 'custom_modes') ); ?>
			
			<div class="icons">
				<?php wpl_exe_wp_front::social_icons('global'); ?>
			</div>
			
		</div>
	</div>
</div>

<script>
jQuery.noConflict()( function(){
	"use strict";
	
	// Countdown timer
	<?php $start_timer = $wpl_exe_wp->get_option( 'site_opening_date', 'custom_modes' ); ?>
	jQuery("#countdown").countdown({
			date: "<?php echo esc_js( $start_timer ); ?> 00:00:00",
			format: "on"
		},
		function() {
			//location.reload();
		}
	);
	
});
</script>

<?php
	get_footer('simple');