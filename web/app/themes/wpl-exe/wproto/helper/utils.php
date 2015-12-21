<?php

class wpl_exe_wp_utils {
	
	/**
	 * Check for Retina display
	 **/
	public static function is_retina() {
		global $wpl_exe_wp;
		$retina_support_enabled = $wpl_exe_wp->get_option( 'retina_support', 'general' );
		return WPROTO_IS_RETINA && $retina_support_enabled;
	}
	
	/**
	 * Check for Retina display
	 **/
	public static function is_retina_enabled() {
		global $wpl_exe_wp;
		$retina_support_enabled = $wpl_exe_wp->get_option( 'retina_support', 'general' );
		return $retina_support_enabled;
	}
	
	/**
	 * Get google fonts
	 **/
	public static function get_google_fonts() {
		
		$google_fonts = array();

		$fonts = self::grab_google_fonts();

		if( count( $fonts->items ) > 0 ) {
			
			foreach( $fonts->items as $item ) {
				$google_fonts[ $item->family ] = $item->family;
			}
			
		}
		
		ksort( $google_fonts );
		
		return $google_fonts;
		
	}
	
	/**
	 * Grab fonts from google
	 **/
	public static function grab_google_fonts( $force = false ) {
		global $wpl_exe_wp;
		
		$fonts = unserialize( get_option( 'wproto_google_fonts_list' ) );
		
		$google_api_key = $wpl_exe_wp->get_option( 'google_api_key', 'api' );
		
		if( $google_api_key == NULL && !$force ) {
			return $fonts;
		} else if( $google_api_key == NULL && $force ) {
			return array();
		}

		if( gettype( $fonts ) != 'object' || $force == true ) {
			$response = @file_get_contents( 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $google_api_key );
			
			try {
				$json = json_decode( $response );
			} catch ( Exception $ex ) {
				$json = NULL;
				return false;
			}
			
			if( $json != NULL ) {
				update_option( 'wproto_google_fonts_list', serialize( $json ));
			}
			
			return $json;
			
		} else {
			return gettype( $fonts ) == 'object' ? $fonts : '';
		}
		
	}
	
	/**
	 * Is WooCommerce Active
	 **/
	public static function isset_woocommerce() {
		return class_exists( 'woocommerce' );
	}
	
	/**
	 * Is Revolution Slider Active
	 **/
	public static function isset_revslider() {
		return class_exists( 'RevSlider' );
	}
	
	/**
	 * Is Essential Grid Active
	 **/
	public static function isset_eg() {
		return class_exists('Essential_Grid');
	}
	
	/**
	 * Get all post custom fields in nice array
	 **/
	public static function get_post_custom( $post_id ) {
		
		$return = array();
		
		$custom_fields = get_post_custom( $post_id );
		
		if( is_array( $custom_fields ) && count( $custom_fields ) > 0 ) {
			foreach( $custom_fields as $key => $value ) {
				$return[$key] = is_array( $value ) && count( $value ) > 1 ? $value : $value[0];
			}
		}
		
		return $return;
		
	}
	
	/**
	 * Get all custom font requests
	 **/
	public static function get_all_custom_theme_fonts( $provider = 'google' ) {
		global $wpl_exe_wp;
		
		$google_fonts_array = array();
		$custom_fonts_array = array();
		
		foreach( $wpl_exe_wp->system_config['css_vars_fonts'] as $vars_fonts ) {

			$google_fonts_array[] = $vars_fonts['value'];

		} 

		if( $wpl_exe_wp->get_option( 'enabled', 'customizer' ) ) {
		
			$sections = array(
				'primary_font',
				'secondary_font',
				'h1_font',
				'h2_font',
				'h3_font',
				'h4_font',
				'h5_font',
				'h6_font'
			);
			
			foreach( $sections as $k=>$v ) {
				
				$is_google_font = $wpl_exe_wp->get_option( $v . '_source', 'customizer' );
				$font_value = $wpl_exe_wp->get_option( $v, 'customizer' );
				
				if( $is_google_font == 'google' ) {
					$google_fonts_array[] = $font_value;
				} else {
					$custom_fonts_array[] = $wpl_exe_wp->get_option( $v . '_custom_id', 'customizer' );
				}
				
			}
			
		}
		
		return $provider == 'google' ? array_filter( array_unique( $google_fonts_array ) ) : array_filter( array_unique( $custom_fonts_array ) );
		
	}
	
	/**
	 * Check if skin isset
	 **/
	public static function isset_skin( $skin ) {
		global $wpl_exe_wp;
		return in_array( $skin, array_keys( $wpl_exe_wp->system_config['skins_params'] ));
	}
	
	/**
	 * Get post type in admin
	 **/	 		
	public static function get_current_post_type() {
	  global $post, $typenow, $current_screen;
		
	  //we have a post so we can just get the post type from that
	  if ( isset( $post->post_type ) )
	    return $post->post_type;
	    
	  //check the global $typenow - set in admin.php
	  elseif( isset( $typenow ) )
	    return $typenow;
	    
	  //check the global $current_screen object - set in sceen.php
	  elseif( isset( $current_screen->post_type ) )
	    return $current_screen->post_type;
	  
	  // check the post_type querystring
	  elseif( isset( $_REQUEST['post_type'] ) )
	    return sanitize_key( $_REQUEST['post_type'] );
		
		//lastly check the post type by post ID
		elseif( isset( $_REQUEST['post'] ) && is_numeric( $_REQUEST['post'] ) )
			return get_post_type( $_REQUEST['post'] );
	  //we do not know the post type!
	  return null;
	}		
	
}