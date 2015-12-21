<?php
/**
 * Users controller
 **/
class wpl_exe_wp_user_controller extends wpl_exe_wp_core_controller {
	
	function __construct() {
		
		if( is_admin() ) {
			// add user custom fields for oAUTH
			add_filter( 'show_user_profile', array( $this, 'add_user_custom_fields' ));
			add_filter( 'edit_user_profile', array( $this, 'add_user_custom_fields' ));
			
			add_action( 'personal_options_update', array( $this, 'save_user_custom_fields' ));
			add_action( 'edit_user_profile_update', array( $this, 'save_user_custom_fields' ));
		}
		
	}
	
	/**
	 * Add user custom fields
	 **/
	function add_user_custom_fields( $user ) {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'custom_fields/user_custom_fields', array( 'user' => $user ) );
	}
	
	/**
	 * Save user custom fields
	 **/
	function save_user_custom_fields( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;
		
		global $wpl_exe_wp;
		
		$allowed_tags = wp_kses_allowed_html( 'post' );
		
		update_user_meta( $user_id, 'wproto_profession', wp_kses( $_POST[ 'wproto_profession' ], $allowed_tags ) );
		
		foreach( $wpl_exe_wp->system_config['social_icons'] as $icon ) {
			update_user_meta( $user_id, $icon['name'], wp_kses( $_POST[ $icon['name'] ], $allowed_tags ) );
		}

	}
	
}