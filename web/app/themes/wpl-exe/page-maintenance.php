<?php
	get_header('simple');
	global $wpl_exe_wp;
?>

<div class="container">
	<div class="row">
		<div class="col-md-12 primary-text">
			<?php echo wp_kses_post( $wpl_exe_wp->get_option('maintenance_page_text', 'custom_modes') ); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 footer-text">
			<?php echo wp_kses_post( $wpl_exe_wp->get_option('maintenance_footer_text', 'custom_modes') ); ?>
			
			<div class="icons">
				<?php wpl_exe_wp_front::social_icons('global'); ?>
			</div>
			
		</div>
	</div>
</div>

<?php
	get_footer('simple');