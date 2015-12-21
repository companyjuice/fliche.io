<?php
/**
 * Back-end main controller
 **/
class wpl_exe_wp_admin_controller extends wpl_exe_wp_core_controller {
	
	function __construct() {
		
		if( is_admin() ) {
			
			// Add admin scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_styles' ) );
			
			// Theme activation & deactivation
			add_action( 'init', array( $this, 'activation_hook'));
			add_action( 'switch_theme', array( $this, 'deactivation_hook' ));	
			
			// Cron jobs
			add_filter( 'cron_schedules', array( $this, 'modify_cron'));
			add_action( 'wproto_weekly_cron', array( $this, 'weekly_cron'));
			
			// Footer notice
			add_filter( 'admin_footer_text', array( $this, 'add_footer_wproto_info'));
			
			// Demo notice
			add_action( 'admin_notices', array( $this, 'add_notices'));
			
			// Setup admin menu
			add_action( 'admin_menu', array( $this, 'setup_admin_menu' ) );
			add_action( 'admin_menu', array( $this, 'remove_admin_menu_elements' ), 99 );
			add_action( 'admin_enqueue_scripts', array( $this, 'highlight_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'fix_admin_icons' ) );
			
			// disable wp admin for non-admins optionally
			add_action( 'admin_init', array( $this, 'disable_wp_admin_access' ) );
			
			// allow mime types
			add_filter( 'upload_mimes', array( $this, 'add_upload_types' ) );
				
			// generate @2x images for thumbnails
			if( wpl_exe_wp_utils::is_retina_enabled() ) {
				add_filter( 'wp_generate_attachment_metadata', array( $this, 'retina_support_attachment_meta' ), 10, 2 );
				add_filter( 'delete_attachment', array( $this, 'delete_generated_images' ) );
			}
			
		}
		
	}
	
	/**
	 * Add admin scripts
	 **/
	function add_scripts() {
		global $post, $typenow, $wpl_exe_wp;
		
		if( !in_array( $typenow, array('product') ) ) {

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-accordion' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
	
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_script( 'editor' );
			wp_enqueue_script( 'quicktags' );
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_script( 'wp-color-picker' );
	
			wp_enqueue_media();
			
			wp_enqueue_script( 'tipsy', WPROTO_THEME_URL . '/js/libs/jquery.tipsy.js', false, $wpl_exe_wp->settings['res_cache_time'], true );
			wp_enqueue_script( 'less', WPROTO_THEME_URL . '/js/libs/less.min.js', false, $wpl_exe_wp->settings['res_cache_time'], true );
			wp_enqueue_script( 'serialize-object', WPROTO_THEME_URL . '/js/libs/jquery.serialize-object.min.js', false, $wpl_exe_wp->settings['res_cache_time'], true );
			
			$js_vars = array(
				'ajaxNonce' => wp_create_nonce( 'wproto-engine-ajax-nonce' ),
				'siteDomain' => $_SERVER['SERVER_NAME'],
				'adminURL' => admin_url(),
				'postID' => isset( $post->ID ) ? $post->ID : 0,
				'adminBigLoaderImage' => '<img id="wproto-big-loader" width="48" height="48" src="' . WPROTO_THEME_URL . '/images/admin/ajax-loader-big@2x.gif" alt="" />',
				'mceTransImg' => '/wp-includes/js/tinymce/plugins/wordpress/img/trans.gif',
				'adminBigLoaderImageTransp' => '<img id="wproto-big-loader-transp" width="48" height="48" src="' . WPROTO_THEME_URL . '/images/admin/ajax-loader-big-transp@2x.gif" alt="" />',
				'adminIconTrue' => WPROTO_THEME_URL . '/images/admin/true@2x.png',
				'adminIconFalse' => WPROTO_THEME_URL . '/images/admin/false@2x.png',
				'buttonImagePath' => WPROTO_THEME_URL . '/images/admin/buttons',
				'themeMixinsPath' => WPROTO_THEME_URL . '/css/mixins.less?t=' . time(),
				'themeStylePath' => WPROTO_THEME_URL . '/css/front/style.less?t=' . time(),
				'moveImgSrc' => WPROTO_THEME_URL . '/images/admin/move@2x.png',
				'strSelectIcon' => __('Select icon', 'wproto'),
				'strError' => __( 'Error', 'wproto' ),
				'strRemove' => __( 'Remove', 'wproto' ),
				'strRename' => __( 'Rename', 'wproto' ),
				'strDelete' => __( 'Delete', 'wproto' ),
				'strChange' => __( 'Change', 'wproto' ),
				'strSticky' => __( 'Sticky', 'wproto' ),
				'strSelect' => __( 'Select', 'wproto' ),
				'strSuccess' => __( 'Successfully', 'wproto' ),
				'strGrabbing' => __( 'Grabbing', 'wproto' ),
				'strCantConnectToGoogle' => __( 'Cannot connect to Google', 'wproto' ),
				'strPleaseWait' => __( 'Please, wait...', 'wproto' ),
				'strNoAttachmentsFound' => __( 'No images was found.', 'wproto' ),
				'strAllDone' => __( 'All done', 'wproto' ),
				'strRebuilding' => __( 'Rebuilding', 'wproto' ),
				'strOf' => __( 'of', 'wproto' ),
				'strIconPicker' => __( 'Icon Picker', 'wproto' ),
				'strRemoveIcon' => __( 'Remove Icon', 'wproto' ),
				'strSelectIcon' => __( 'Select Icon', 'wproto' ),
				'strSelectImage' => __( 'Select an image', 'wproto' ),
				'strNoImagesSelected' => __( 'No images selected', 'wproto' ),
				'strOneImagesSelected' => __( 'One image selected', 'wproto' ),
				'strImagesSelected' => __( 'images selected', 'wproto' ),
				'strAttachImages' => __( 'Attach Images', 'wproto' ),
				'strInsertAttachedImages' => __( 'Select', 'wproto' ),
				'strAJAXError' => __( 'An AJAX error occurred when performing a query. Please contact Customer Support if the problem persists.', 'wproto' ),
				'strServerResponseError' => __( 'The script have received an invalid response from the server. Please contact Customer Support if the problem persists.', 'wproto' ),
				'strConfirm' => __( 'Confirm action', 'wproto' ),
				'strConfirmDelete' => __( 'Are you sure you want to delete? This action cannot be undone.', 'wproto' ),
				'strValue' => __( 'Value', 'wproto' ),
				'strYourFeature' => __( 'Feature name', 'wproto' ),
				'strPackageName' => __( 'Package name', 'wproto' ),
				'widgetTogglesTitle' => __( 'Edit Toggles content', 'wproto' ),
				'widgetProgressTitle' => __( 'Edit Progress bars', 'wproto' ),
				'strLoading' => __( 'Loading... Please wait...', 'wproto' ),
				'strCompilingLess' => __( sprintf( 'Please wait until we\'re applying your custom styles into theme.<br/><br/>%s', '<img id="wproto-big-loader-transp" width="24" height="24" src="' . WPROTO_THEME_URL . '/images/admin/ajax-loader-big-transp@2x.gif" alt="" /><br/><br/>' ),'wproto'),
				'strLoadingLessFile' => __('Loading LESS files...', 'wproto'),
				'strLoadingLessFileSuccess' => __('File loaded successfully.', 'wproto'),
				'strCompilationLess' => __('Compilation LESS variables into CSS...', 'wproto'),
				'strLessParseError' => __('Cannot parse LESS. That\'s why: ', 'wproto'),
				'strCompilationLessSuccess' => __('LESS code was successfully compiled.', 'wproto'),
				'strSavingLessIntoDB' => __('Saving stylesheet...', 'wproto'),
				'strRefreshing' => __('Submitting the form...', 'wproto'),
				'strInstallingDemoData' => sprintf( __('%s Installing demo data, please wait...', 'wproto') , '<img id="wproto-big-loader" width="14" height="14" src="' . WPROTO_THEME_URL . '/images/admin/ajax-loader-big@2x.gif" alt="" />'),
				'strWrongServerAnswer' => __('Seems something going wrong... We have received a wrong answer from the server.', 'wproto'),
				'strCheckingReadability' => __('Checking correct work of <code>file_get_content()</code> function...', 'wproto'),
				'strReadError' => __('Theme can not read stylesheet file. For first, check that <code>customizer.less</code> file exists on your server in <code>/css/</code> directory and it readable. If this does not help, <code>file_get_content()</code> function does not work properly on your hosting. This is not a theme bug, and You should contact your hosting provider to resolve that error.', 'wproto'),
			);
	
			wp_register_script( 'wproto-engine-functions', WPROTO_THEME_URL . '/js/admin/functions.js', false, $wpl_exe_wp->settings['res_cache_time'], true );
			wp_enqueue_script( 'wproto-engine-functions', array( 'jquery' ) );
	
			wp_register_script( 'wproto-engine-backend', WPROTO_THEME_URL . '/js/admin/backend.js', false, $wpl_exe_wp->settings['res_cache_time'], true );
			wp_enqueue_script( 'wproto-engine-backend', array( 'wproto-engine-functions' ) );
			wp_localize_script( 'wproto-engine-backend', 'wprotoVars', $js_vars );
			
			if( isset( $_GET['wproto_admin_noheader'] ) ) {
				wp_register_script( 'wproto-remove-header', WPROTO_THEME_URL . '/js/admin/admin-noheader.js', false, $wpl_exe_wp->settings['res_cache_time'], true );
				wp_enqueue_script( 'wproto-remove-header', array( 'jquery' ) );
			}	
			
			$screen = get_current_screen();
							
			if( isset( $screen->base ) && $screen->base == 'widgets' ) {
				wp_register_script( 'wproto-widgets-js', WPROTO_THEME_URL . '/js/admin/screen-widgets.js', false, $wpl_exe_wp->settings['res_cache_time'], true );
				wp_enqueue_script( 'wproto-widgets-js', array( 'jquery' ) );
			}	
			
			$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
			
			if( in_array( $page, array( 'wproto_theme_settings', 'wproto_display_branding_settings', 'wproto_display_skins_settings', 'wproto_display_customizer_settings', 'wproto_display_appearance_settings', 'wproto_system_layouts_settings', 'wproto_custom_posts_settings', 'wproto_custom_modes_settings', 'wproto_display_plugins_settings', 'wproto_display_woo_settings', 'wproto_display_api_social_settings', 'wproto_display_tools', 'wproto_display_demo_data_installer', 'wproto_display_updater' ) ) ) {
				wp_register_script( 'wproto-settings-screen', WPROTO_THEME_URL . '/js/admin/screen-settings.js', false, $wpl_exe_wp->settings['res_cache_time'] );
				wp_enqueue_script( 'wproto-settings-screen', array( 'wproto-engine-functions' ) );	
			}
			
		} else {
			wp_enqueue_media();
		}

		
	}
	
	/**
	 * Add admin styles
	 **/
	function add_styles() {
		global $wp_styles, $wpl_exe_wp;
		
		$screen = get_current_screen();
								
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( 'editor' );
		wp_enqueue_style( 'wp-color-picker' );
		
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		
		wp_enqueue_style( 'jquery-ui', WPROTO_THEME_URL . '/css/libs/ui/jquery-ui.min.css', false, $wpl_exe_wp->settings['res_cache_time'] );
		
		/*
		if( !wpl_exe_wp_utils::isset_eg() ) {
			wp_enqueue_style( 'jquery-ui', WPROTO_THEME_URL . '/css/libs/ui/jquery-ui.min.css', false, $wpl_exe_wp->settings['res_cache_time'] );
		} else {
			wp_enqueue_style( 'jquery-ui-slider', WPROTO_THEME_URL . '/css/libs/ui/jquery-ui-slider.css', false, $wpl_exe_wp->settings['res_cache_time'] );
		}*/
		
		wp_enqueue_style( 'wproto-backend', WPROTO_THEME_URL . '/css/admin/backend.css', false, $wpl_exe_wp->settings['res_cache_time'] );		
		wp_enqueue_style( 'wproto-fontawesome', WPROTO_THEME_URL . '/css/libs/font-awesome/css/font-awesome.min.css', false, $wpl_exe_wp->settings['res_cache_time'] );
		
		if( $wpl_exe_wp->get_option( 'icomoon_enabled' ) ) {
			wp_enqueue_style( 'wproto-icomoon', WPROTO_THEME_URL . '/css/libs/icomoon/style.css', false, $wpl_exe_wp->settings['res_cache_time'] );
		}

		if( isset( $_GET['wproto_admin_noheader'] ) ) {
			wp_enqueue_style( 'wproto-backend-noheader', WPROTO_THEME_URL . '/css/admin/backend-noheader.css', false, $wpl_exe_wp->settings['res_cache_time'] );
		}
		
		// Custom fonts
		$custom_google_fonts = wpl_exe_wp_utils::get_all_custom_theme_fonts();
		$custom_google_fonts_string = '';
		
		if( is_array( $custom_google_fonts ) && count( $custom_google_fonts ) ) {
			
			foreach( $custom_google_fonts as $k=>$font ) {
				$custom_google_fonts_string .= $font . ':300,400,600,700,800,300italic,400italic|';
			}
			
		}
		
		$additional_subsets = $wpl_exe_wp->get_option( 'additional_subsets', 'general' );
		
		if( is_array( $additional_subsets ) && count( $additional_subsets ) > 0 ) {
			$subsets_string = implode( ',', $additional_subsets );
			$custom_google_fonts_string .= '&subset=' . $subsets_string;
		} else {
			$custom_google_fonts_string .= '&subset=latin';
		}
		
		wp_enqueue_style( 'wproto-front-fonts', '//fonts.googleapis.com/css?family=' . esc_attr( $custom_google_fonts_string ) );
		
	}
	
	/**
	 * Check for first activation
	 **/
	function activation_hook() {
		global $pagenow, $wp_version, $wpl_exe_wp;

		if ( isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
			if( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
				wp_die( sprintf( __( 'Cannot install the theme. PHP version > 5.3.0 is required. Your PHP version: %s', 'wproto' ), PHP_VERSION ) );
			}
		
			if( version_compare( $wp_version, '3.8', '<' ) ) {
				wp_die( sprintf( __( 'Cannot activate the theme. WordPress version > 3.8 is required. Your WordPress version: %s', 'wproto' ), $wp_version ) );
			}
		
			$wpl_exe_wp->model( 'installation' )->install();
		
			flush_rewrite_rules( true );
			wp_cache_flush();
		}
		
	}
	
	/**
	 * deactivation hook
	 **/
	function deactivation_hook() {
		flush_rewrite_rules( true );		
	}
	
	/**
	 * Add cron jobs
	 **/
	function modify_cron( $schedules ) {
		if( !is_array( $schedules ) ) $schedules = array();
		// add a 'weekly' schedule to the existing set
		$schedules['weekly'] = array(
			'interval' => 604800,
			'display' => __( 'Once Weekly', 'wproto')
		);        
		return $schedules;
	}
	
	/**
	 * Wproto weekly cron
	 **/
	function weekly_cron() {
		wpl_exe_wp_admin_utils::purge_transients();
	}
	
	/**
	 * Add footer info
	 **/
	function add_footer_wproto_info( $text ) {
		return $text . ' ' . __( sprintf( 'Theme created by <a href="%s" target="_blank">WPlab</a>', 'http://www.wplab.pro/' ), 'wproto' );
	}
	
	/**
	 * Add admin notices
	 **/
	function add_notices() {
		global $wpl_exe_wp;
		// demo data notice
		if( get_option('wproto_show_demo_data_message') ):
			$wpl_exe_wp->view->load_partial( 'dialog/demo_data_notice' );
		endif;
		
		if( $wpl_exe_wp->get_option('maintenance_enabled', 'custom_modes') ) {
			printf( '<div class="updated notify-maintenance"><p>%s <a href="%s">%s</a></p></div>', __( 'Maintenance mode is enabled. All pages of your website will be not availabled for visitors (<strong>users, with Administrator permissions are excluded</strong>) and search engines, while maintenance mode is enabled.', 'wproto' ), admin_url('admin.php?page=wproto_custom_modes_settings'), __( 'Settings page', 'wproto' ) );
		}
		
		if( $wpl_exe_wp->get_option('coming_soon_enabled', 'custom_modes') ) {
			printf( '<div class="updated notify-maintenance"><p>%s <a href="%s">%s</a></p></div>', __( 'Coming soon mode is enabled. All pages of your website will be not availabled for visitors (<strong>users, with Administrator permissions are excluded</strong>) and search engines, while "coming soon" mode is enabled.', 'wproto' ), admin_url('admin.php?page=wproto_custom_modes_settings'), __( 'Settings page', 'wproto' ) );
		}
	}
	
	/**
	 * Setup admin menu
	 **/	 
	function setup_admin_menu() {
		global $menu, $submenu, $wp_registered_sidebars;
		
		if ( current_user_can( 'edit_theme_options' ) ) {
			
			add_menu_page( __( 'Theme Options', 'wproto' ),
													__( 'Theme Options', 'wproto' ),
													'administrator',
													'wproto_theme_settings',
													array( $this->controller('admin_settings'), 'display_general_settings' ) );
														 
			add_submenu_page( 'wproto_theme_settings',
														 __( 'Branding', 'wproto' ),
														 __( 'Branding', 'wproto' ),
														 'administrator',
														 'wproto_display_branding_settings',
														 array( $this->controller('admin_settings'), 'display_branding_settings' ) );
														 
			add_submenu_page( 'wproto_theme_settings',
														 __( 'Customizer', 'wproto' ),
														 __( 'Customizer', 'wproto' ),
														 'administrator',
														 'wproto_display_customizer_settings',
														 array( $this->controller('admin_settings'), 'display_customizer_settings' ) );
														 
			add_submenu_page( 'wproto_theme_settings',
														 __( 'Custom fonts', 'wproto' ),
														 __( 'Custom fonts', 'wproto' ),
														 'administrator',
														 'edit.php?post_type=wproto_custom_font' );

			add_submenu_page( 'wproto_theme_settings',
														 __( 'Widget areas', 'wproto' ),
														 __( 'Widget areas', 'wproto' ),
														 'administrator',
														 'edit-tags.php?taxonomy=wproto_sidebars' );
														 
			add_submenu_page( 'wproto_theme_settings',
														 __( 'Appearance', 'wproto' ),
														 __( 'Appearance', 'wproto' ),
														 'administrator',
														 'wproto_display_appearance_settings',
														 array( $this->controller('admin_settings'), 'display_appearance_settings' ) );

			add_submenu_page( 'wproto_theme_settings',
														 __( 'System layouts', 'wproto' ),
														 __( 'System layouts', 'wproto' ),
														 'administrator',
														 'wproto_system_layouts_settings',
														 array( $this->controller('admin_settings'), 'display_system_layouts_settings' ) );
														 
			add_submenu_page( 'wproto_theme_settings',
														 __( 'Single layouts', 'wproto' ),
														 __( 'Single layouts', 'wproto' ),
														 'administrator',
														 'wproto_custom_posts_settings',
														 array( $this->controller('admin_settings'), 'display_custom_posts_settings' ) );

			add_submenu_page( 'wproto_theme_settings',
														 __( 'Plugins', 'wproto' ),
														 __( 'Plugins', 'wproto' ),
														 'administrator',
														 'wproto_display_plugins_settings',
														 array( $this->controller('admin_settings'), 'wproto_display_plugins_settings' ) );
														 
			add_submenu_page( 'wproto_theme_settings',
														 __( 'WooCommerce', 'wproto' ),
														 __( 'WooCommerce', 'wproto' ),
														 'administrator',
														 'wproto_display_woo_settings',
														 array( $this->controller('admin_settings'), 'wproto_display_woo_settings' ) );
		 												
			add_submenu_page( 'wproto_theme_settings',
														 __( 'API & Social icons', 'wproto' ),
														 __( 'API & Social icons', 'wproto' ),
														 'administrator',
														 'wproto_display_api_social_settings',
														 array( $this->controller('admin_settings'), 'display_api_social_settings' ) );
														 
			add_submenu_page( 'wproto_theme_settings',
														 __( 'Coming soon & Maintenance modes', 'wproto' ),
														 __( 'Coming soon & Maintenance modes', 'wproto' ),
														 'administrator',
														 'wproto_custom_modes_settings',
														 array( $this->controller('admin_settings'), 'display_custom_modes_settings' ) );
														 
			add_submenu_page( 'wproto_theme_settings',
														 __( 'Tools', 'wproto' ),
														 __( 'Tools', 'wproto' ),
														 'administrator',
														 'wproto_display_tools',
														 array( $this->controller('admin_settings'), 'display_tools' ) );
														 
			add_submenu_page( 'wproto_theme_settings',
														 __( 'Demo data', 'wproto' ),
														 __( 'Demo data', 'wproto' ),
														 'administrator',
														 'wproto_display_demo_data_installer',
														 array( $this->controller('admin_settings'), 'display_demo_data_installer' ) );

		}																							
		
	}
	
	function remove_admin_menu_elements() {
		global $wpl_exe_wp;
		if( $wpl_exe_wp->get_option( 'disable_bundled_vc', 'plugins' ) == false ) {
			remove_submenu_page( 'vc-general', 'edit.php?post_type=vc_grid_item' );
		}
	}
	
	/**
	 * Highlight menu
	 **/
	function highlight_menu() {
		global $parent_file, $submenu_file;

		if( $submenu_file == 'edit-tags.php?taxonomy=wproto_sidebars' ) {
			$parent_file = 'wproto_theme_settings';
			$submenu_file = 'edit-tags.php?taxonomy=wproto_sidebars';
		}

		if( $parent_file == 'edit.php?post_type=wproto_custom_font' ) {
			$parent_file = 'wproto_theme_settings';
			$submenu_file = 'edit.php?post_type=wproto_custom_font';
		}
		
	}
	
	/**
	 * Fix admin icons
	 **/
	function fix_admin_icons() {
		?>
    <style type="text/css">
			#icon-wproto_theme_settings { background: url(images/icons32.png?ver=20121105) -11px -5px no-repeat; }
			@media only screen and (-webkit-min-device-pixel-ratio:1.5) {
				#icon-wproto_theme_settings { background-image: url(images/icons32-2x.png?ver=20121105); background-size: 756px 45px; }
			}
		</style>
		<?php
	}
	
	/**
	 * Disable access to WP admin for non-admins optionally
	 **/
	function disable_wp_admin_access() {
		global $wpl_exe_wp;
		
		$disable_access = $wpl_exe_wp->get_option( 'disable_wp_admin_for_non_admins', 'general' );
		
		if ( $disable_access && ! current_user_can( 'manage_options' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
  		wp_redirect( home_url() );
			exit;
		}
		
	}
	
	/**
	 * Allow mime types
	 **/
	function add_upload_types( $existing_mimes ) {
		$existing_mimes['ico'] = 'image/vnd.microsoft.icon';
		$existing_mimes['eot'] = 'application/vnd.ms-fontobject';
		$existing_mimes['woff2'] = 'application/x-woff';
		$existing_mimes['woff'] = 'application/x-woff';
		$existing_mimes['ttf'] = 'application/octet-stream';
		$existing_mimes['svg'] = 'image/svg+xml';
		$existing_mimes['mp4'] = 'video/mp4';
		$existing_mimes['ogv'] = 'video/ogg';
		$existing_mimes['webm'] = 'video/webm';
		$existing_mimes['svg'] = 'image/svg+xml';
		return $existing_mimes;
	}

	/**
	 * Add retina image sizes for another plugins compatibility
	 **/
	function retina_support_attachment_meta( $metadata, $attachment_id ) {
		foreach ( $metadata as $key => $value ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $image => $attr ) {
					if ( is_array( $attr ) )
						$this->retina_support_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
				}
			}
		}
		return $metadata;
	}

	function retina_support_create_images( $file, $width, $height, $crop = false ) {
		if ( $width || $height ) {
			$resized_file = wp_get_image_editor( $file );
			if ( ! is_wp_error( $resized_file ) ) {
				$filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );
				$resized_file->resize( $width * 2, $height * 2, $crop );
				$resized_file->save( $filename );
				$info = $resized_file->get_size();
				return array(
					'file' => wp_basename( $filename ),
					'width' => $info['width'],
					'height' => $info['height'],
				);
			}
		}
		return false;
	}
	
	function delete_generated_images( $attachment_id ) {
		$meta = wp_get_attachment_metadata( $attachment_id );
		$upload_dir = wp_upload_dir();
		$path = pathinfo( $meta['file'] );
		
		$attach_file = $upload_dir['basedir'] . '/' . $meta['file'];
		
		$mask = substr_replace( $attach_file, '*.', strrpos( $attach_file, '.' ), strlen( '.' ) );
		
		foreach ( glob( $mask ) as $filename) {
			@unlink( $filename );
		}
		
	}
	
}