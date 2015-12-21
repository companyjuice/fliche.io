<?php
/**
 * Admin settings controller
 **/
class wpl_exe_wp_admin_settings_controller extends wpl_exe_wp_core_controller {
	
	function __construct() {
		
		if( is_admin() ) {
			// rebuild thumbnail
			add_action( 'wp_ajax_wproto_flush_rewrite_rules', array( $this, 'flush_rewrite_rules' ) );
			// reset all settings
			add_action( 'wp_ajax_wproto_reset_settings', array( $this, 'reset_settings' ) );
			
			// grab google fonts
			add_action( 'wp_ajax_wproto_grab_google_fonts_list', array( $this, 'ajax_grab_google_fonts_list' ) );
			
			// get customize LESS file content
			add_action( 'wp_ajax_wproto_check_readability', array( $this, 'check_readability' ) );
			add_action( 'wp_ajax_wproto_get_customizer_stylesheet', array( $this, 'ajax_get_less_file' ) );
			add_action( 'wp_ajax_wproto_save_customizer_stylesheet', array( $this, 'ajax_save_less' ) );
			
			// install demo data
			add_action( 'wp_ajax_wproto_install_demo_data', array( $this, 'ajax_install_demo_data' ) );
			
			// import / export theme settings
			add_action( 'wp_ajax_wproto_export_settings', array( $this, 'ajax_export_settings' ) );
			add_action( 'wp_ajax_wproto_import_settings', array( $this, 'ajax_import_settings' ) );
			
			// reset tweets cache
			add_action( 'wp_ajax_wproto_reset_tweets_cache', array( $this, 'ajax_reset_tweets_cache' ) );
		}
		
	}
	
	/**
	 * Save settings handler
	 **/
	function save() {
		global $wpl_exe_wp;
		
		$_POST = wp_unslash( $_POST );
		
		$allowed_tags = wp_kses_allowed_html( 'post' );
		
		if( isset( $_POST['wproto_setting_env'] ) && $_POST['wproto_setting_env'] == 'general' ) {
			
			delete_transient('wproto_font_icons');
			
			$_POST['general']['icomoon_enabled'] = isset( $_POST['general']['icomoon_enabled'] ) ? $_POST['general']['icomoon_enabled'] : false;
			$_POST['general']['captcha_difficult'] = isset( $_POST['general']['captcha_difficult'] ) && is_array( $_POST['general']['captcha_difficult'] ) ? $_POST['general']['captcha_difficult'] : array( 'minus' );
			
			if( $_POST['general']['portfolio_slug'] == '' ) {
				$_POST['general']['portfolio_slug'] = $wpl_exe_wp->system_config['general']['portfolio_slug'];
			}
			
			$_POST['general']['additional_subsets'] = isset( $_POST['general']['additional_subsets'] ) && is_array( $_POST['general']['additional_subsets'] ) && count( $_POST['general']['additional_subsets'] ) > 0 ? $_POST['general']['additional_subsets'] : array();

		}
		
		if( isset( $_POST['wproto_setting_env'] ) && $_POST['wproto_setting_env'] == 'branding' ) {
			
			update_option( 'blogname', isset( $_POST['branding']['site_title'] ) ? wp_kses( $_POST['branding']['site_title'], $allowed_tags ) : '' );
			update_option( 'blogdescription', isset( $_POST['branding']['site_tagline'] ) ? wp_kses( $_POST['branding']['site_tagline'], $allowed_tags ) : '' );
			
		}
		
		if( isset( $_POST['wproto_setting_env'] ) && $_POST['wproto_setting_env'] == 'api' ) {
			
			$_POST['api']['like_facebook'] = isset( $_POST['api']['like_facebook'] ) ? $_POST['api']['like_facebook'] : false;
			$_POST['api']['like_twitter'] = isset( $_POST['api']['like_twitter'] ) ? $_POST['api']['like_twitter'] : false;
			$_POST['api']['like_google_plus'] = isset( $_POST['api']['like_google_plus'] ) ? $_POST['api']['like_google_plus'] : false;
			$_POST['api']['like_pinterest'] = isset( $_POST['api']['like_pinterest'] ) ? $_POST['api']['like_pinterest'] : false;
			$_POST['api']['like_vk'] = isset( $_POST['api']['like_vk'] ) ? $_POST['api']['like_vk'] : false;
			
			if( (isset( $_POST['api']['enable_google_oauth'] ) && $_POST['api']['enable_google_oauth']) || (isset( $_POST['api']['enable_facebook_oauth'] ) && $_POST['api']['enable_facebook_oauth']) ) {
				update_option( 'users_can_register', true );
			}
			
		}
		
		if( isset( $_POST['wproto_setting_env'] ) && $_POST['wproto_setting_env'] == 'appearance' ) {
			$_POST['appearance']['top_bar_address_on_desktop'] = isset( $_POST['appearance']['top_bar_address_on_desktop'] );
			$_POST['appearance']['top_bar_address_on_tablets'] = isset( $_POST['appearance']['top_bar_address_on_tablets'] );
			$_POST['appearance']['top_bar_address_on_phones'] = isset( $_POST['appearance']['top_bar_address_on_phones'] );

			$_POST['appearance']['top_bar_phone_on_desktop'] = isset( $_POST['appearance']['top_bar_phone_on_desktop'] );
			$_POST['appearance']['top_bar_phone_on_tablets'] = isset( $_POST['appearance']['top_bar_phone_on_tablets'] );
			$_POST['appearance']['top_bar_phone_on_phones'] = isset( $_POST['appearance']['top_bar_phone_on_phones'] );
			
			$_POST['appearance']['top_bar_email_on_desktop'] = isset( $_POST['appearance']['top_bar_email_on_desktop'] );
			$_POST['appearance']['top_bar_email_on_tablets'] = isset( $_POST['appearance']['top_bar_email_on_tablets'] );
			$_POST['appearance']['top_bar_email_on_phones'] = isset( $_POST['appearance']['top_bar_email_on_phones'] );
			
			$_POST['appearance']['top_bar_skype_on_desktop'] = isset( $_POST['appearance']['top_bar_skype_on_desktop'] );
			$_POST['appearance']['top_bar_skype_on_tablets'] = isset( $_POST['appearance']['top_bar_skype_on_tablets'] );
			$_POST['appearance']['top_bar_skype_on_phones'] = isset( $_POST['appearance']['top_bar_skype_on_phones'] );
			
			$_POST['appearance']['top_bar_wh_on_desktop'] = isset( $_POST['appearance']['top_bar_wh_on_desktop'] );
			$_POST['appearance']['top_bar_wh_on_tablets'] = isset( $_POST['appearance']['top_bar_wh_on_tablets'] );
			$_POST['appearance']['top_bar_wh_on_phones'] = isset( $_POST['appearance']['top_bar_wh_on_phones'] );
			
			$_POST['appearance']['top_bar_ft_on_deskop'] = isset( $_POST['appearance']['top_bar_ft_on_deskop'] );
			$_POST['appearance']['top_bar_ft_on_tablets'] = isset( $_POST['appearance']['top_bar_ft_on_tablets'] );
			$_POST['appearance']['top_bar_ft_on_phones'] = isset( $_POST['appearance']['top_bar_ft_on_phones'] );
			
			$_POST['appearance']['display_social_icons_top_bar_desktop'] = isset( $_POST['appearance']['display_social_icons_top_bar_desktop'] );
			$_POST['appearance']['display_social_icons_top_bar_tablets'] = isset( $_POST['appearance']['display_social_icons_top_bar_tablets'] );
			$_POST['appearance']['display_social_icons_top_bar_phones'] = isset( $_POST['appearance']['display_social_icons_top_bar_phones'] );
			
			$_POST['appearance']['display_login_top_bar_desktop'] = isset( $_POST['appearance']['display_login_top_bar_desktop'] );
			$_POST['appearance']['display_login_top_bar_tablets'] = isset( $_POST['appearance']['display_login_top_bar_tablets'] );
			$_POST['appearance']['display_login_top_bar_phones'] = isset( $_POST['appearance']['display_login_top_bar_phones'] );
			
			$_POST['appearance']['display_wishlist_top_bar_desktop'] = isset( $_POST['appearance']['display_wishlist_top_bar_desktop'] );
			$_POST['appearance']['display_wishlist_top_bar_tablets'] = isset( $_POST['appearance']['display_wishlist_top_bar_tablets'] );
			$_POST['appearance']['display_wishlist_top_bar_phones'] = isset( $_POST['appearance']['display_wishlist_top_bar_phones'] );
		}
		
		if( is_array( $_POST ) && count( $_POST ) > 0 ) {
			foreach( $_POST as $env=>$v ) {
				
				if( is_array( $v ) && count( $v ) > 0 ) {
					foreach( $v as $option_name=>$option_value ) {
						$wpl_exe_wp->set_option( $option_name, $option_value, $env );
					}
					
				}
				
			}
			
			$wpl_exe_wp->write_all_settings();
			
		}
		
		/** reset settings to defaults **/
		if( isset( $_POST['wproto_reset_to_defaults'] ) && isset( $_POST['wproto_setting_env'] ) ) {
			
			foreach( $wpl_exe_wp->system_config[ $_POST['wproto_setting_env'] ] as $option_name=>$option_value ) {
				$wpl_exe_wp->set_option( $option_name, $option_value, $_POST['wproto_setting_env'] );
			}
			
			if( $_POST['wproto_setting_env'] == 'customizer' ) {
				unset( $wpl_exe_wp->settings[ 'customizer' ] );
			}
			
			$wpl_exe_wp->write_all_settings();
			
		}
		
		header( 'Location: ' . add_query_arg( array( 'updated' => 'true' ) ) );
		exit;
	}
	
	/**
	 * Display general settings
	 **/
	function display_general_settings() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/general', array() );
	}
	
	/**
	 * Display branding settings
	 **/
	function display_branding_settings() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/branding', array() );
	}
	
	/**
	 * Display skins settings
	 **/
	function display_skins_settings() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/skins', array() );
	}
	
	/**
	 * Display customization settings
	 **/
	function display_customizer_settings() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/customizer', array() );
	}
	
	/**
	 * Display appearance settings
	 **/
	function display_appearance_settings() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/appearance', array() );
	}
	
	/**
	 * Display system layout settings
	 **/
	function display_system_layouts_settings() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/system_layouts', array() );
	}
	
	/**
	 * Display custom post settings
	 **/
	function display_custom_posts_settings() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/custom_posts', array() );
	}
	
	/**
	 * Display custom modes settings
	 **/
	function display_custom_modes_settings() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/custom_modes', array() );
	}
	
	/**
	 * Display plugins settings
	 **/
	function wproto_display_plugins_settings() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/plugins', array() );
	}
	
	/**
	 * Display WooCommerce settings
	 **/
	function wproto_display_woo_settings() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/woo', array() );
	}
	
	/**
	 * Display social and search settings
	 **/
	function display_api_social_settings() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/api_social', array() );
	}
	
	/**
	 * Display tools settings
	 **/
	function display_tools() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/tools', array() );
	}
	
	/**
	 * Display demo data installer
	 **/
	function display_demo_data_installer() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/demo_data', array() );
	}
	
	/**
	 * Display theme updater settings
	 **/
	function display_updater() {
		global $wpl_exe_wp;
		$wpl_exe_wp->view->load_partial( 'settings/updater', array() );
	}
	
	/*************************************************************************************************************
																					AJAX IN SETTINGS
	*************************************************************************************************************/
	
	/**
	 * Flush rewrite rules
	 **/
	function flush_rewrite_rules() {
		update_option( 'rewrite_rules', '' );
		flush_rewrite_rules( true );
		die;
	}
	
	/**
	 * Reset all settings
	 **/
	function reset_settings() {
		update_option( 'wproto_settings_' . WPROTO_THEME_NAME, '' );
		update_option( 'wproto_custom_css_' . WPROTO_THEME_NAME, '' );
		
		$fonts_file = WPROTO_ENGINE_DIR . '/fonts.txt';
		if( file_exists( $fonts_file ) ) {
			update_option( 'wproto_google_fonts_list', serialize( json_decode( file_get_contents( $fonts_file ) ) ) );
		}
		
		die;
	}
	
	/**
	 * Grab google fonts list
	 **/
	function ajax_grab_google_fonts_list() {
		
		$fonts = wpl_exe_wp_utils::grab_google_fonts( true );
		
		if( count( @$fonts->items ) > 0 ) echo 'ok';
		
		die;
	}
	
	/**
	 * Check correct work of file_get_contents
	 **/
	function check_readability() {
		
		echo false === @file_get_contents( get_template_directory() . '/css/front/style.less' ) ? 'error' : 'ok';
		
		die;
	}
	
	/**
	 * Get content of less file
	 **/
	function ajax_get_less_file() {
		global $wpl_exe_wp;
		
		$current_skin = $wpl_exe_wp->get_option('skin', 'skins');
		
		$file_content = file_get_contents( get_template_directory() . '/css/front/style.less' );
		
		echo $file_content;
		die;
	}
	
	/**
	 * Save compiled LESS into DB
	 **/
	function ajax_save_less() {
		global $wp_filesystem;
		
		if (empty($wp_filesystem)) {
	    require_once (ABSPATH . '/wp-admin/includes/file.php');
	    WP_Filesystem();
		}
		
		$_POST = stripslashes_deep( $_POST );
		$stylesheet = $_POST['css'];

		$upload_dir = wp_upload_dir();
		
		$style_filename = $upload_dir['basedir'] . '/wplab_exe_theme_custom_css.css';
		
		// write to a file
		$wp_filesystem->put_contents( $style_filename, $stylesheet, FS_CHMOD_FILE );
		
		// clean old styles
		update_option('wproto_custom_css_' . WPROTO_THEME_NAME, '');
		
		die;
	}
	
	/**
	 * Install demo data
	 **/
	function ajax_install_demo_data() {
		global $wpl_exe_wp;
		
		require_once WPROTO_ENGINE_DIR . '/model/demo-data/installer.phtml';
		$demo_data_model = new wpl_exe_wp_demo_data;
		
		$_POST = stripslashes_deep( $_POST );
		
		$subaction = $_POST['subaction'];
		
		$data = isset( $_POST['data'] ) ? unserialize( base64_decode( $_POST['data'] ) ) : array();
		$answer = array();
		
		switch( $subaction ) {
			
			case( 'start' ):
			
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_images';
				$answer['message'] = __('Uploading demo images into WordPress gallery...', 'wproto');
				$answer['data'] = '';
			
				die( json_encode( $answer ) );
			
			break;
			// upload demo images
			case( 'install_demo_images' ):
			
				$demo_images_array = array(
					get_template_directory() . '/images/exe_demo_light.jpg',
					get_template_directory() . '/images/exe_demo_dark.jpg',
				);

				$wp_upload_dir = wp_upload_dir();

				foreach( $demo_images_array as $k=>$image ) {
					
					$moved_file = $wp_upload_dir['path'] . "/demo_image_" . uniqid() . '.jpg';
					
					if( copy( $image, $moved_file ) ) {
				
						$wp_filetype = wp_check_filetype(basename( $moved_file ), null );
				
						$attach_id = wp_insert_attachment( array(
     					'guid' => $wp_upload_dir['url'] . '/' . basename( $moved_file ), 
     					'post_mime_type' => $wp_filetype['type'],
     					'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $moved_file ) ),
     					'post_content' => '',
     					'post_status' => 'inherit'
						), $moved_file );
				
						require_once( ABSPATH . 'wp-admin/includes/image.php' );
						$attach_data = wp_generate_attachment_metadata( $attach_id, $moved_file );
  					wp_update_attachment_metadata( $attach_id, $attach_data );
  					
  					$data['demo_images'][] = $attach_id;
					}

				}
				
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_posts';
				$answer['message'] = __('All images were uploaded successfully. Inserting blog posts...', 'wproto');
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert blog posts
			case( 'install_demo_posts' ):
			
				$demo_data_model->install_demo_blog_posts( $data['demo_images'] );
				
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_pricing_tables';
				$answer['message'] = __('All blog posts were inserted successfully. Inserting pricing tables...', 'wproto');
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert pricing tables
			case( 'install_demo_pricing_tables' ):
			
				$data['pricing_tables_ids'] = $demo_data_model->install_demo_pricing_tables( $data['demo_images'] );
				
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_partners_clients';
				$answer['message'] = __('All pricing tables were inserted successfully. Inserting partners / clients posts...', 'wproto');
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert partners / clients
			case( 'install_demo_partners_clients' ):
			
				$demo_data_model->install_demo_partners_clients( $data['demo_images'] );
				
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_portfolio';
				$answer['message'] = __('All partners / clients posts were inserted successfully. Inserting portfolio posts...', 'wproto');
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert portfolio posts
			case( 'install_demo_portfolio' ):
			
				$demo_data_model->install_demo_portfolio( $data['demo_images'] );
				
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_team_members';
				$answer['message'] = __('All portfolio posts were inserted successfully. Inserting team members...', 'wproto');
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert team members
			case( 'install_demo_team_members' ):
			
				$demo_data_model->install_demo_team_members( $data['demo_images'] );
				
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_benefits';
				$answer['message'] = __('All team members were inserted successfully. Inserting benefits...', 'wproto');
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert benefits
			case( 'install_demo_benefits' ):
			
				$demo_data_model->install_demo_benefits( $data['demo_images'] );
				
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_testimonials';
				$answer['message'] = __('All benefits were inserted successfully. Inserting testimonials...', 'wproto');
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert testimonials
			case( 'install_demo_testimonials' ):
			
				$demo_data_model->install_demo_testimonials( $data['demo_images'] );
				
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_contact_forms';
				$answer['message'] = __('All testimonials were inserted successfully. Inserting contact forms...', 'wproto');
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert contact forms
			case( 'install_demo_contact_forms' ):
			
				if( function_exists('ninja_forms_import_form') ) {
					$demo_data_model->install_demo_forms();
					$answer['message'] = __('All forms were inserted successfully. Inserting demo slideshows...', 'wproto');
				} else {
					$answer['message'] = __('NinjaForms plugin is NOT activated. Installing demo forms failed. Inserting demo slideshows...', 'wproto');
				}

				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_slideshow';
				
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert demo slider
			case( 'install_demo_slideshow' ):
			
				if( shortcode_exists( 'rev_slider' ) ) {
					
					$data['slideshow_ids'] = $demo_data_model->install_demo_slideshows( $data['demo_images'] );
					$answer['message'] = __('All slideshows were inserted successfully. Inserting pages...', 'wproto');
					
				} else {
					
					$answer['message'] = __('Revolution slider plugin is not activated. Inserting demo slides failed. Inserting pages...', 'wproto');
					
				}
			
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_pages';
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert pages
			case( 'install_demo_pages' ):
			
				$data['pages_ids'] = $demo_data_model->install_demo_pages( $data );
				
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_menus';
				$answer['message'] = __('All pages were inserted successfully. Inserting menus...', 'wproto');
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert menus
			case( 'install_demo_menus' ):
			
				$demo_data_model->install_demo_menus( $data['pages_ids'] );
				
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_demo_widgets';
				$answer['message'] = __('All menus were inserted successfully. Inserting widgets...', 'wproto');
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert widgets
			case( 'install_demo_widgets' ):
			
				$demo_data_model->install_demo_widgets( $data );
				
				$answer['answer'] = 'ok';
				$answer['next_subaction'] = 'install_settings';
				$answer['message'] = __('All widgets were inserted successfully. Writing settings...', 'wproto');
				$answer['data'] = base64_encode( serialize( $data ) );
				
				die( json_encode( $answer ) );
			
			break;
			
			// insert widgets
			case( 'install_settings' ):
			
				$demo_data_model->install_demo_settings( $data );
				
				$answer['answer'] = 'finished';
				
				die( json_encode( $answer ) );
			
			break;
			
		}
		
		
		die;
	}
	
	/**
	 * Export theme settings
	 **/
	function ajax_export_settings() {
		
		$settings = array();
		
		$settings[ 'wproto_google_fonts_list' ] = get_option( 'wproto_google_fonts_list' );
		$settings[ 'wproto_settings_' . WPROTO_THEME_NAME ] = get_option( 'wproto_settings_' . WPROTO_THEME_NAME );
		$settings[ 'wproto_custom_css_' . WPROTO_THEME_NAME ] = get_option( 'wproto_custom_css_' . WPROTO_THEME_NAME );
		
		echo '<textarea onclick="this.select()" readonly="readonly" style="height: 300px;">' . base64_encode( serialize( $settings ) ) . '</textarea>';
		
		exit;
		
	}
	
	/**
	 * Import theme settings
	 **/
	function ajax_import_settings() {
		
		$settings = isset( $_POST['settings'] ) ? @unserialize( base64_decode( trim( $_POST['settings'] ) ) ) : '';
		
		if(
					!is_array( $settings )
			|| 	!isset( $settings[ 'wproto_google_fonts_list' ] )
			|| 	!isset( $settings[ 'wproto_settings_' . WPROTO_THEME_NAME ] )
			|| 	!isset( $settings[ 'wproto_custom_css_' . WPROTO_THEME_NAME ] ) 
		) {
			
			_e('Wrong export string. Theme settings were NOT imported.', 'wproto');
			
		} else {
			
			update_option( 'wproto_google_fonts_list', $settings[ 'wproto_google_fonts_list' ] );
			update_option( 'wproto_settings_' . WPROTO_THEME_NAME, $settings[ 'wproto_settings_' . WPROTO_THEME_NAME ] );
			update_option( 'wproto_custom_css_' . WPROTO_THEME_NAME, $settings[ 'wproto_custom_css_' . WPROTO_THEME_NAME ] );
			
			_e('Theme settings were imported successfully', 'wproto');
			
		}
		
		exit;
		
	}
	
	/**
	 * Reset cache of tweets
	 **/
	function ajax_reset_tweets_cache() {
		
		wp_cache_delete('wproto_latest_tweets');
		
		exit;
	}
	
}