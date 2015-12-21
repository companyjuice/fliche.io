<!doctype html>
<html class="no-js" <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<meta charset="<?php bloginfo( 'charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php
		global $woocommerce, $wpl_exe_wp;
		wpl_exe_wp_front::head();
		wp_head();
	?>
</head>
<?php
	// Body styles for ComingSoon & Maintenance modes
	$is_coming_soon = $wpl_exe_wp->get_option('coming_soon_enabled', 'custom_modes');
	$is_maintenance = $wpl_exe_wp->get_option('maintenance_enabled', 'custom_modes');
	
	$body_style = '';
	
	// Custom styles for Maintenance Mode
	if( $is_coming_soon ) {
		$bg_color = $wpl_exe_wp->get_option('coming_soon_page_bg_color', 'custom_modes');
		if( $bg_color <> '' ) {
			$body_style .= 'background-color: ' . $bg_color . ';';
		}
		$bg_img = $wpl_exe_wp->get_option('coming_soon_background', 'custom_modes');
		if( $bg_img <> '' ) {
			$body_style .= 'background-image: url(' . $bg_img . ');';
		}
		$body_style .= 'background-repeat: ' . $wpl_exe_wp->get_option('coming_soon_background_repeat', 'custom_modes') . '; ';
		$body_style .= 'background-position: ' . $wpl_exe_wp->get_option('coming_soon_background_h_pos', 'custom_modes') . ' ' . $wpl_exe_wp->get_option('coming_soon_background_v_pos', 'custom_modes') . '; ';
		$fixed_bg = $wpl_exe_wp->get_option('coming_soon_background_fixed', 'custom_modes');
		if( $fixed_bg ) {
			$body_style .= 'background-attachment: fixed;';
		}
	}
	
	if( $is_maintenance ) {
		$bg_color = $wpl_exe_wp->get_option('maintenance_page_bg_color', 'custom_modes');
		if( $bg_color <> '' ) {
			$body_style .= 'background-color: ' . $bg_color . ';';
		}
		$bg_img = $wpl_exe_wp->get_option('maintenance_background', 'custom_modes');
		if( $bg_img <> '' ) {
			$body_style .= 'background-image: url(' . $bg_img . ');';
		}
		$body_style .= 'background-repeat: ' . $wpl_exe_wp->get_option('maintenance_background_repeat', 'custom_modes') . '; ';
		$body_style .= 'background-position: ' . $wpl_exe_wp->get_option('maintenance_background_h_pos', 'custom_modes') . ' ' . $wpl_exe_wp->get_option('coming_soon_background_v_pos', 'custom_modes') . '; ';
		$fixed_bg = $wpl_exe_wp->get_option('maintenance_background_fixed', 'custom_modes');
		if( $fixed_bg ) {
			$body_style .= 'background-attachment: fixed;';
		}
	}
?>
<body <?php body_class(); ?> style="<?php echo esc_attr( $body_style ); ?>">