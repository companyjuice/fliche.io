<?php

class wpl_exe_wp_installation extends wpl_exe_wp_database {
	/**
	 * Install Tables
	 **/
	function install() {
		global $wpl_exe_wp;
		$fonts_file = WPROTO_ENGINE_DIR . '/fonts.txt';

		update_option( 'wproto_show_demo_data_message', true );
		if( file_exists( $fonts_file ) ) {
			update_option( 'wproto_google_fonts_list', file_get_contents( $fonts_file ) );
		}
		
		update_option( 'wpb_js_content_types', array('post', 'page', 'wproto_mega_menu') );
		
		if( !wp_next_scheduled( 'wproto_weekly_cron' ) )
			wp_schedule_event( time(), 'weekly', 'wproto_weekly_cron');
		
		$default_settings = array(
			'general' => array(

			)
		);
		
		// set default settings
		foreach( $default_settings as $env=>$v ) {
				
			if( is_array( $v ) && count( $v ) > 0 ) {
				foreach( $v as $option_name=>$option_value )
				$wpl_exe_wp->set_option( $option_name, $option_value, $env );
			}
				
		}
			
		$wpl_exe_wp->write_all_settings();

	}

	/**
	 * Uninstall DB tables & remove options
	 **/
	function uninstall() {

		wp_clear_scheduled_hook( 'wproto_weekly_cron' );

		delete_option( 'wproto_show_demo_data_message' );
		delete_option( 'wproto_google_fonts_list' );
		delete_option( 'wproto_settings_' . WPROTO_THEME_NAME );
		
		delete_option( 'wproto_custom_css' );
		
		flush_rewrite_rules( true );

	}
	
}