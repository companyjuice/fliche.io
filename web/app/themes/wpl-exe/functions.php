<?php
	
	if ( ! isset( $content_width ) ) $content_width = 0;

	// Define constants
	define( 'WPROTO_ENGINE_VERSION', '5.0.0' );
	define( 'WPROTO_THEME_NAME', 'wpl_exe_wp' );
	define( 'WPROTO_THEME_DIR', get_template_directory() );
	define( 'WPROTO_ENGINE_DIR', WPROTO_THEME_DIR . '/wproto' );
	define( 'WPROTO_THEME_URL', get_template_directory_uri() );
	define( 'WPROTO_ENGINE_URL', WPROTO_THEME_URL . '/wproto' );
	
	define( 'WPROTO_IS_RETINA', isset( $_COOKIE["device_pixel_ratio"] ) && $_COOKIE["device_pixel_ratio"] >= 2 );
	
	if( !function_exists( 'wp_dump' ) ) {
		function wp_dump() {
			if ( func_num_args() > 0 ) {
				
				$args = func_get_args();
				
				foreach( $args as $arg ) {
					echo '<pre>';
					var_dump( $arg );
					echo '</pre>';
				}
				
			}
		}
	}
	
	/**
	 * Get an image with specific width and height and it's HD equivalent
	 * @param Image URL
	 * @param Image Width
	 * @param Image Height
	 * @param Image Crop
	 * @param Add HD image for retina.js
	 * @param Fallback image size
	 **/
	function wpl_exe_image( $url, $width, $height = null, $crop = true, $hd = true, $fallback_size = '', $thumb_id = 0 ) {
		require_once WPROTO_THEME_DIR . '/library/aq_resizer/aq_resizer.php';	
		
		$hd_image_url = '';
		$hd_atts = 'data-no-retina';
		$image_url = aq_resize( $url, $width, $height, $crop );
		
		if( $hd && wpl_exe_wp_utils::is_retina_enabled() ) {
			$hd_width = $width * 2;
			$hd_height = $height != null ? $height * 2 : null;
			$hd_image_url = aq_resize( $url, $hd_width, $hd_height, $crop );
			if( $hd_image_url ) {
				$hd_atts = 'data-at2x="' . esc_attr( $hd_image_url ) . '"';
			}
		}
		
		if( $image_url ) {
			return '<img class="wproto-wp-image" src="' . esc_attr( $image_url ) . '" ' . image_hwstring( $width, $height ) . ' ' . $hd_atts . ' />';
		} elseif( $fallback_size <> '' ) {		
			$_url = wp_get_attachment_url( $thumb_id );
			return '<img class="wproto-wp-image" src="' . esc_attr( $_url ) . '" />';
		} else {
			return '';
		}
	}
	
	// Instantiate base controller that will autoload
	// all application classes. Each controller must state
	// the add_action() and add_filter() hooks it executes
	// in its own constructor for a quick orientation of
	// which methods serve which exact browser requests
	require_once 'wproto/controller/core.php';
	global $wpl_exe_wp;
	
	/** theme settings can be changed **/
	$wpl_theme_defaults['settings'] = array(
		//'res_cache_time' => time()
		'res_cache_time' => '300820152142'
	);
	
	/** get theme defaults **/
	require_once WPROTO_THEME_DIR . '/wproto/config.cfg';
				
	$wpl_exe_wp = new wpl_exe_wp_core_controller( $wpl_theme_defaults );	
	$wpl_exe_wp->dispatch();