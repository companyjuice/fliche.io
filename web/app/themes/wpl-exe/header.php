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
<body <?php body_class(); ?>>

	<?php	
		wpl_exe_wp_front::preloader();
		wpl_exe_wp_front::unloader();
	?>

	<div id="wrap">
	
		<?php
			wpl_exe_wp_front::top_bar();
			wpl_exe_wp_front::header();
		?>