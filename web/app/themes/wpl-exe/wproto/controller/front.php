<?php
/**
 * Front-end controller
 **/
class wpl_exe_wp_front_controller extends wpl_exe_wp_core_controller {
	
	function __construct() {
		
		// add scripts and styles
		add_action( 'wp_head', array( $this, 'add_scripts_head' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'fix_js_composer' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ), 50 );
		
		add_action( 'wp_enqueue_scripts', array( $this, 'remove_styles' ), 99 );
		add_action( 'wp_print_scripts', array( $this, 'remove_scripts' ), 100 );
		
		// Custom CSS styles
		add_action( 'parse_request', array( $this, 'handle_custom_css_output' ) );
		
		// make valid HTML output
		add_filter( 'get_search_form', array( $this, 'custom_search' ) );
		add_filter( 'get_archives_link', array( $this, 'theme_get_archives_link' ) );
		add_filter( 'wp_tag_cloud', array( $this, 'highlight_tags' ) );
		add_filter( 'embed_oembed_html', array( $this, 'custom_embed_html' ), 10, 3 );
		
		// add images to RSS feed
		add_filter( 'the_excerpt_rss', array( $this, 'add_featured_image_to_feed' ), 1000, 1);
		add_filter( 'the_content_feed', array( $this, 'add_featured_image_to_feed' ), 1000, 1);
		
		// Hide admin bar from non-admins if this required by theme settings
		add_action( 'after_setup_theme',  array( $this, 'remove_admin_bar' ) );
		
		// search custom posts
		add_filter( 'pre_get_posts', array( $this, 'filter_search' ));
		add_filter( 'body_class', array( $this, 'filter_body_classes' ));
		
		// check for maintenance mode
		add_action( 'template_redirect', array( $this, 'template_redirect' ), 10, 1);
		
		// filter wp_head with settings
		add_action( 'init', array( $this, 'setup_wp' ) );
		// print tracking code
		add_action( 'wp_footer', array( $this, 'print_tracking_code' ));
		
		// change list categories output
		//add_filter( 'wp_list_categories', array( $this, 'filter_cat_count' ));
		add_filter( 'get_archives_link', array( $this, 'filter_archive_count' ));
		
		remove_action( 'ninja_forms_display_css', 'ninja_forms_display_css', 10, 2 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		
		// customize password protected post form
		add_filter( 'the_password_form', array( $this, 'customize_password_protect' ));
		
		// change gallery output from gallery post format
		add_filter( 'the_content', array( $this, 'filter_content' ), 6); 
		
		add_filter( 'wp_title',  array( $this, 'wp_title' ), 10, 2 );	
		
		add_filter( 'img_caption_shortcode', array( $this, 'fix_captions'), 10, 3);			
			
	}
	
	/**
	 * Add scripts into header
	 **/
	function add_scripts_head() {
		global $wpl_exe_wp, $post;
		
		if ( !is_admin() ):
			?>
			<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
			<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie.css">
			<![endif]-->
			<?php
		endif;
		
		if( ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'wproto_google_map')) ):
		?>
		<script src="//maps.googleapis.com/maps/api/js"></script>
		<?php
		endif;
		
		if( $wpl_exe_wp->get_option( 'use_preloader', 'general' ) ):
		?>
			<script src="<?php echo get_template_directory_uri(); ?>/js/libs/loaders.css.js"></script>
		<?php
		endif;
	}
	
	/**
	 * Add scripts to the front
	 **/
	function add_scripts() {
		global $wpl_exe_wp, $wp_query, $wp_scripts;
		
		wp_enqueue_script( 'jquery' );
		
		if ( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
			
		// include all libs together
		wp_register_script( 'wproto-all-libs', WPROTO_THEME_URL . '/js/libs/all_in_one_libs.min.js', array( 'jquery' ), $wpl_exe_wp->settings['res_cache_time'], true );
		wp_enqueue_script( 'wproto-all-libs' );
		
		wp_register_script( 'google-maps', '//maps.googleapis.com/maps/api/js?sensor=true', array( 'jquery' ), $wpl_exe_wp->settings['res_cache_time'], true );

		if( isset( $_GET['vc_editable'] ) && $_GET['vc_editable'] ) {
			wp_enqueue_script( 'google-maps' );
		}
		
		$is_coming_soon = $wpl_exe_wp->get_option( 'coming_soon_enabled', 'custom_modes' );
		if( $is_coming_soon ) {
			wp_enqueue_script( 'wproto-countdown', WPROTO_THEME_URL . '/js/libs/countdown.js', array( 'jquery' ), $wpl_exe_wp->settings['res_cache_time'], true );
		}
		
		$current_post_id = isset( $wp_query->post ) ? $wp_query->post->ID : 0;
		$template_name = get_post_meta( $current_post_id, '_wp_page_template', true );
		
		wp_register_script( 'wproto-front', WPROTO_THEME_URL . '/js/front.min.js', array( 'jquery' ), $wpl_exe_wp->settings['res_cache_time'], true );
		wp_enqueue_script( 'wproto-front' );
		
		$js_vars = array(
			'ajaxNonce' => wp_create_nonce( 'wproto-engine-ajax-nonce' ),
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'homeUrl' => home_url(),
			'searchVar' => get_query_var('s'),
			'post_id' => $current_post_id,
			'strSuccess' => __('Success', 'wproto'),
			'strError' => __('Error', 'wproto'),
			'strTermsError'	=> __('You should accept terms & conditions', 'wproto'),
			'strTypeCaptchaAnswer' => __('Please, enter captcha value', 'wproto'),
			'messageWasSent' => __('Your message has been sent. Thank you!', 'wproto'),
			'strAJAXError' => __( 'An AJAX error occurred when performing a query. Please contact support if the problem persists.', 'wproto' ),
			'strServerResponseError' => __( 'The script have received an invalid response from the server. Please contact support if the problem persists.', 'wproto' ),
			'strEnterYourName' => __('Please enter your name', 'wproto'),
			'strEnterEmail' => __('Please enter valid email address', 'wproto'),
			'strEnterMessage' => __('Please enter a message', 'wproto'),
			'strAddedToWishlist' => __('Added to wishlist', 'wproto'),
			'strEmptyWishlist' => __('Your wish list is empty', 'wproto'),
			'strWrongCaptcha' => __('Wrong Captcha answer', 'wproto'),
		);
		
		wp_localize_script( 'wproto-front', 'wprotoEngineVars', $js_vars );
		
		if( $wpl_exe_wp->get_option( 'smooth_scroll', 'general' ) ) {
			wp_enqueue_script( 'wproto-smoothscroll', WPROTO_THEME_URL . '/js/libs/SmoothScroll.js', array( 'jquery' ), $wpl_exe_wp->settings['res_cache_time'], true );
		}
		
	}
	
	function fix_js_composer() {
		global $wpl_exe_wp;
		wp_enqueue_script( 'wproto-vc-fix', WPROTO_THEME_URL . '/js/vc_fix.js', array( 'jquery', 'wpb_composer_front_js' ), $wpl_exe_wp->settings['res_cache_time'], true );

	}
	
	/**
	 * Add front CSS styles
	 **/
	function add_styles() {
		global $wpl_exe_wp, $post;
		
		wp_enqueue_style( 'wproto-font-awesome', WPROTO_THEME_URL . '/css/libs/font-awesome/css/font-awesome.min.css', false, $wpl_exe_wp->settings['res_cache_time'] );
		
		if( $wpl_exe_wp->get_option('icomoon_enabled', 'general') ) {
			wp_enqueue_style( 'wproto-icomoon', WPROTO_THEME_URL . '/css/libs/icomoon/style.css', false, $wpl_exe_wp->settings['res_cache_time'] );
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
		
		wp_enqueue_style( 'wproto-front-fonts', '//fonts.googleapis.com/css?family=' . $custom_google_fonts_string );

		// Additional skins
		$skin = $wpl_exe_wp->get_option( 'skin', 'skins' ); 
		
		// Handle skin for demo purposes
		if( defined( 'WPROTO_DEMO_STAND' ) && WPROTO_DEMO_STAND ) {
			if( is_object( $post ) && isset( $post->ID ) ) {
				$handle_skin = get_post_meta( $post->ID, 'wproto_force_skin', true );
				if( $handle_skin && $handle_skin <> '' ) {
					$skin = $handle_skin;
				}
			}
		}
		
		wp_enqueue_style( 'wproto-front-skin', WPROTO_THEME_URL . '/css/front/skin-' . $skin . '.css?' . $wpl_exe_wp->settings['res_cache_time'] );
		
		// add custom fonts style
		$custom_fonts = wpl_exe_wp_utils::get_all_custom_theme_fonts('custom');
		$inline_css = '';
		if( is_array( $custom_fonts ) && count( $custom_fonts ) > 0 ) {
			
			foreach( $custom_fonts as $font_id ) {
				
				if( $font_id == 0 ) continue;
				
				$_custom_fields = get_post_custom( $font_id );
				
				if( isset( $_custom_fields['font_family'][0] ) ) {
					
					$inline_css .= "@font-face { font-family: '" . $_custom_fields['font_family'][0] . "';";
					
					$file_eot = isset( $_custom_fields['file_eot'][0] ) ? $_custom_fields['file_eot'][0] : '';
					$file_woff2 = isset( $_custom_fields['file_woff2'][0] ) ? $_custom_fields['file_woff2'][0] : '';
					$file_woff = isset( $_custom_fields['file_woff'][0] ) ? $_custom_fields['file_woff'][0] : '';
					$file_truetype = isset( $_custom_fields['file_truetype'][0] ) ? $_custom_fields['file_truetype'][0] : '';
					$file_svg = isset( $_custom_fields['file_svg'][0] ) ? $_custom_fields['file_svg'][0] : '';
					
					$inline_css .= '
						src: url(\'' . $file_eot . '\');
						src: url(\'' . $file_eot . '?#iefix\') format(\'embedded-opentype\'),
         		url(\'' . $file_woff2 . '\') format(\'woff2\'),
         		url(\'' . $file_woff . '\') format(\'woff\'),
         		url(\'' . $file_truetype . '\') format(\'truetype\'),
         			url(\'' . $file_svg . '#' . $_custom_fields['font_family'][0] . '\') format(\'svg\');
					';
					
					$inline_css .= "}";
					
				}
				
			}
			
		}
		
		wp_enqueue_style('js_composer_front');
		wp_enqueue_style('js_composer_custom_css');
		
		// Custom CSS
		wp_add_inline_style( 'wproto-front-skin', $inline_css );
		
		if( $wpl_exe_wp->get_option( 'enabled', 'customizer' ) ) {
			$upload_dir = wp_upload_dir();
			$custom_css_file = $upload_dir['baseurl'] . '/wplab_exe_theme_custom_css.css';
			wp_enqueue_style( 'theme-front', $custom_css_file, false );
		} 
		
		wp_enqueue_style( 'wproto-front-custom', site_url( '/?wproto_print_custom_css' ) );

		
	}
	
	/**
	 * Remove some styles
	 **/
	function remove_styles() {
		wp_dequeue_style( 'woocommerce_frontend_styles' );
		wp_dequeue_style( 'woocommerce_fancybox_styles' );
		wp_dequeue_style( 'woocommerce_chosen_styles' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		wp_dequeue_style( 'woocommerce_frontend_styles_smallscreen' );
	}
	
	/**
	 * Remove WooScripts
	 **/
	function remove_scripts() {
		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_script( 'prettyPhoto-init' );
		wp_dequeue_script( 'wc-chosen' );
		wp_dequeue_script( 'select2' );
	}
	
	/**
	 * Custom CSS code output
	 **/
	function handle_custom_css_output() {
		global $wpl_exe_wp;
		
		header("Content-type: text/css", true);
		
		if( isset( $_GET['wproto_print_custom_css'] ) ) {
			
			if( $wpl_exe_wp->get_option( 'enabled', 'customizer' ) ) {
				echo get_option('wproto_custom_css_' . WPROTO_THEME_NAME);	
			}
		
			// custom CSS from textarea
			echo $wpl_exe_wp->get_option( 'custom_css', 'general' );
			die;
		}
		
	}
	
	/**
	 * Do a lot of job to make valid default HTML output
	 **/
	function custom_search() {
		ob_start();
		include WPROTO_THEME_DIR . '/search-form.php';
		return ob_get_clean();
	}
	
	function theme_get_archives_link( $link_html ) {
		global $wp_query;
			
		$current = '';
		if( isset( $wp_query->query['m'] ) ) {
			$current = $wp_query->query['m'];
		}
		if( isset( $wp_query->query['year'] ) && $wp_query->query['monthnum'] ) {
			$current = $wp_query->query['year'] . '\/+' . $wp_query->query['monthnum'];
		}
			
		if ( $current <> '' && preg_match( '/' . $current . '/i', $link_html ) )
			$link_html = preg_replace( '/<li>/i', '<li class="current">', $link_html );
			
		return $link_html;
	}
	
	function highlight_tags( $cloud ) {
		global $wpdb, $wp_query;
		$tags = single_tag_title('', false);
		$tags_array = explode(" + ", $tags);
		foreach ($tags_array as $tag_name) {
			$tag_id = isset( $wp_query->queried_object->term_id ) ? $wp_query->queried_object->term_id : 0;
				
			if( $tag_id ) {
				$cloud = str_replace( "tag-link-$tag_id", "current-term tag-link-$tag_id", $cloud);
			}
				
		}
		return $cloud;
	}
	
	function custom_embed_html( $html, $url, $attr ) {
		return str_replace( '</embed>', '', $html );
	}
	
	/**
	 * Add featured images to RSS feed
	 **/
	function add_featured_image_to_feed( $content ) {
		global $post, $wpl_exe_wp;
		
		$rss_display_thumbs = $wpl_exe_wp->get_option( 'rss_display_thumbs', 'general' );
		
		if( $rss_display_thumbs ) {
			
			if ( has_post_thumbnail( $post->ID ) ){
				$img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array( 800, 600 ) );
				$content = '<!-- POST THUMBNAIL --><img src="' . $img_src[0] . '" width="400" alt="" />' . $content;
			}
			
		}
		return $content;
	}
	
	/**
	 * Hide admin bar from non-admins
	 **/
	function remove_admin_bar() {
		global $wpl_exe_wp;
		$hide_bar_from_non_admins = $wpl_exe_wp->get_option('hide_adminbar_for_non_admins', 'general');
		
		if ( $hide_bar_from_non_admins && !current_user_can('delete_pages') && !is_admin()) {
			show_admin_bar( false );
		}
	}
	
	/**
	 * Filter search custom posts
	 **/
	function filter_search( $query ) {
		global $wpl_exe_wp;
		
		if ( $query->is_search ) {
			$query->set( 'post_type', array( 'post', 'page', 'wproto_portfolio', 'product' ) );
			//$query->set( 'post__not_in', get_option('sticky_posts'));
			
			$custom_posts_per_page = absint( $wpl_exe_wp->get_option( 'search_posts_per_page', 'system_layouts' ) );
			$query->set( 'posts_per_page', $custom_posts_per_page );
			
		};
    return $query;
	}
	
	/**
	 * Add custom body classes
	 **/
	function filter_body_classes( $classes ) {
		global $post, $wpl_exe_wp;
		
		$is_admin = current_user_can( 'delete_pages' );
		$post_type = get_post_type();
		
		if( defined( 'WPROTO_DEMO_STAND' ) && WPROTO_DEMO_STAND ) {
			$classes[] = 'wproto-demo';	
		}
		
		$classes[] = 'skin-' . $wpl_exe_wp->get_option('skin', 'skins');
		
		/**
		 * Indicate sidebar by classname
		 **/
		$sidebar_displayed = $wpl_exe_wp->get_option( 'display_sidebar', 'appearance' );
		$footer_style = $wpl_exe_wp->get_option( 'footer_style', 'appearance' ); 
		
		if( is_single() || is_page() ) {
			$page_settings = $wpl_exe_wp->get_post_settings_obj();
			if( isset( $page_settings->customize_sidebar ) && $page_settings->customize_sidebar && isset( $page_settings->wproto_page_sidebar ) && $page_settings->wproto_page_sidebar == 'none' ) {
				$sidebar_displayed = false;
			}
			
			if( isset( $page_settings->wproto_footer_style ) && $page_settings->wproto_footer_style <> '' ) {
				$footer_style = $page_settings->wproto_footer_style;
			}
			
			// one page 
			if( isset( $page_settings->menu_onepage ) && $page_settings->menu_onepage ) {
				$classes[] = 'wproto-one-page-menu';
			}
			
			if( isset( $page_settings->wproto_force_boxed ) && $page_settings->wproto_force_boxed ) {
				$classes[] = 'boxed-layout';
			}
			
			if( isset( $page_settings->wproto_force_framed ) && $page_settings->wproto_force_framed ) {
				$classes[] = 'framed-layout';
			}
				
		}
		
		if( is_404() ) {
			$sidebar_displayed = $wpl_exe_wp->get_option( 'e404_page_sidebar', 'system_layouts' );
			
			$_custom_footer = $wpl_exe_wp->get_option( 'e404_page_footer_style', 'system_layouts' );
			if( $_custom_footer <> '' ) {
				$footer_style = $_custom_footer;
			}
			
		}
		
		if( is_search() ) {
			$sidebar_displayed = $wpl_exe_wp->get_option( 'search_page_sidebar', 'system_layouts' );
			
			$_custom_footer = $wpl_exe_wp->get_option( 'search_page_footer_style', 'system_layouts' );
			if( $_custom_footer <> '' ) {
				$footer_style = $_custom_footer;
			}
		}
		
		if( is_category() || is_archive() || is_tag() || is_home() ) {
			$sidebar_displayed = $wpl_exe_wp->get_option( 'blog_archive_page_sidebar', 'system_layouts' );
		}
		
		if( is_author() ) {
			$sidebar_displayed = $wpl_exe_wp->get_option( 'authors_page_page_sidebar', 'system_layouts' );
			
			$_custom_footer = $wpl_exe_wp->get_option( 'author_archive_footer_style', 'system_layouts' );
			if( $_custom_footer <> '' ) {
				$footer_style = $_custom_footer;
			}
		}
		
		if( $wpl_exe_wp->get_option( 'disble_mobile_animations', 'general' ) ) {
			$classes[] = 'disable-animation-on-mobiles';
		}
		
		$classes[] = 'footer-' . $footer_style;
		
		if( function_exists('is_woocommerce') && is_woocommerce() ) {
			$sidebar_displayed = $wpl_exe_wp->get_option( 'woo_display_sidebar', 'plugins' );
		}
		
		if( is_singular('wproto_portfolio') ) {
			$sidebar_displayed = false;
		}
		
		if( $sidebar_displayed ) {
			$classes[] = 'page-with-sidebar';
			
			$side = $wpl_exe_wp->get_option( 'default_sidebar', 'appearance' ); 
			
			if( isset( $page_settings->wproto_page_sidebar ) ) {
				$side = $page_settings->wproto_page_sidebar;
			}
			
			if( is_404() ) {
				$side = $wpl_exe_wp->get_option( 'e404_page_sidebar_position', 'system_layouts' );
			}
			
			if( is_author() ) {
				$side = $wpl_exe_wp->get_option( 'authors_page_page_sidebar_position', 'system_layouts' );
			}
			
			if( is_search() ) {
				$side = $wpl_exe_wp->get_option( 'search_page_sidebar_position', 'system_layouts' );
			}
			
			if( is_category() || is_archive() || is_tag() || is_home() ) {
				$side = $wpl_exe_wp->get_option( 'blog_archive_sidebar_position', 'system_layouts' );
			}
			
			if( function_exists('is_woocommerce') && is_woocommerce() ) {
				$side = $wpl_exe_wp->get_option( 'woo_default_sidebar', 'plugins' );
			}
			
			$classes[] = 'sidebar-' . $side;
			
		} else {
			$classes[] = 'page-without-sidebar';
		}
		
		/**
		 * Page preloader & unloader
		 **/	
		
		if( $wpl_exe_wp->get_option( 'use_preloader', 'general' ) ) {
			$classes[] = 'preloader';
		}
		
		if( $wpl_exe_wp->get_option( 'use_unloader', 'general' ) ) {
			$classes[] = 'unloader';
		}
		
		/**
		 * Customization of form elements
		 **/
		if( $wpl_exe_wp->get_option( 'disable_form_style_plugins', 'plugins' ) ) {
			$classes[] = 'no-js-form-styling';
		}
		
		/**
		 * Do not scroll header menu
		 **/
		
		if( ! $wpl_exe_wp->get_option('menu_scrolling', 'appearance') ) {
			$classes[] = 'no-scrolling-menu';
		}
		
		if( $wpl_exe_wp->get_option('menu_scrolling', 'appearance') && $wpl_exe_wp->get_option('menu_mobile_scrolling', 'appearance') ) {
			$classes[] = 'menu-mobile-scroll';
		}
		
		/**
		 * Maintenance
		 **/
		
		if( $wpl_exe_wp->get_option('maintenance_enabled', 'custom_modes') && !$is_admin ) {
			$classes[] = 'template-maintenance';
			$classes[] = 'maintenance-' . $wpl_exe_wp->get_option('maintenance_page_style', 'custom_modes');
		}
		
		/**
		 * Coming soon
		 **/
		
		if( $wpl_exe_wp->get_option('coming_soon_enabled', 'custom_modes') && !$is_admin ) {
			$classes[] = 'template-coming-soon';
			$classes[] = 'coming-soon-' . $wpl_exe_wp->get_option('coming_soon_page_style', 'custom_modes');
		}
		
		/**
		 * Boxed layout
		 **/
		if( $wpl_exe_wp->get_option('enabled', 'customizer') ) {
			$layout_type = $wpl_exe_wp->get_option('layout_style', 'customizer');
			if( $layout_type == 'boxed' ) {
				$classes[] = 'boxed-layout';
			} else if( $layout_type == 'framed' ) {
				$classes[] = 'framed-layout';
			}
		}
		
		if( is_author() ) {
			$classes = array_diff( $classes, array('archive'));
		}
		
		if( is_search() ) {
			$classes = array_diff( $classes, array('archive', 'author'));
		}

	 	/**
	 	 * WooCommerce
	 	 **/
		if( is_post_type_archive('product') || (function_exists('is_product_category') && is_product_category()) ) {
			$classes = array_diff( $classes, array('archive'));
			$classes[] = 'post-type-archive-product';
		}
		
		if( $post_type == 'product' ) {
			$classes = array_diff( $classes, array('single'));
		}

		return $classes;
		
	}
	
	/**
	 * Template redirect
	 **/
	function template_redirect() {
		global $wpl_exe_wp, $post;
		
		$is_maintenance = $wpl_exe_wp->get_option('maintenance_enabled', 'custom_modes');
		
		$is_admin = current_user_can('delete_pages');
		
		if( $is_maintenance && !$is_admin ) {
			include( WPROTO_THEME_DIR . '/page-maintenance.php' );
			exit();
		}

		$is_coming_soon = $wpl_exe_wp->get_option('coming_soon_enabled', 'custom_modes');
		
		if( $is_coming_soon && !$is_admin ) {
			include( WPROTO_THEME_DIR . '/page-coming-soon.php' );
			exit();
		}
		
		/**
		 * Check for custom user redirect
		 **/
		if( gettype( $post ) == 'object' ) {

			$data = wpl_exe_wp_utils::get_post_custom( $post->ID );

			$data['wproto_redirect_url'] = isset( $data['wproto_redirect_url'] ) ? $data['wproto_redirect_url'] : '';
			$data['wproto_redirect_page_id'] = isset( $data['wproto_redirect_page_id'] ) ? $data['wproto_redirect_page_id'] : '';
			$data['wproto_redirect_type'] = isset( $data['wproto_redirect_type'] ) ? $data['wproto_redirect_type'] : '';
			$data['wproto_redirect_enabled'] = isset( $data['wproto_redirect_enabled'] ) ? $data['wproto_redirect_enabled'] : false;
			$data['wproto_redirect_code'] = isset( $data['wproto_redirect_code'] ) ? $data['wproto_redirect_code'] : '';
		
			if( $data['wproto_redirect_enabled'] ) {
				$to = $data['wproto_redirect_type'] == 'page' ? get_permalink( $data['wproto_redirect_page_id'] ) : $data['wproto_redirect_url'];
			}
		
			if( isset( $to ) && trim( $to ) <> '' ) {
				wp_redirect( $to, $data['wproto_redirect_code'] );
				exit;
			}	
		}
		
	}
	
	/**
	 * Setup WP with settings
	 **/
	function setup_wp() {
		global $wpl_exe_wp;
		$rss_enabled = $wpl_exe_wp->get_option( 'rss_enabled', 'general' );
		
		if( ! $rss_enabled ):
			remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
			remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
			remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
			
			add_action( 'do_feed', array( $this, 'disable_rss_feed' ), 1);
			add_action( 'do_feed_rdf', array( $this, 'disable_rss_feed' ), 1);
			add_action( 'do_feed_rss', array( $this, 'disable_rss_feed' ), 1);
			add_action( 'do_feed_rss2', array( $this, 'disable_rss_feed' ), 1);
			add_action( 'do_feed_atom', array( $this, 'disable_rss_feed' ), 1);
			add_action( 'do_feed_rss2_comments', array( $this, 'disable_rss_feed' ), 1);
			add_action( 'do_feed_atom_comments', array( $this, 'disable_rss_feed' ), 1);

			
		endif;
		
	}
	
	/**
	 * Disable RSS feed
	 **/
	function disable_rss_feed() {
		wp_die( __( 'Feed was disabled by administrator', 'wproto' ) );
	}
	
	/**
	 * Print tracking code at footer
	 **/
	function print_tracking_code() {
		global $wpl_exe_wp;
		$tracking_code = $wpl_exe_wp->get_option( 'tracking_code', 'general' );
		echo @$tracking_code;
	}	
	
	/**
	 * Filter cat count
	 **/
	function filter_cat_count( $links ) {		
		$links = str_replace('(', '<span>(', $links);
		$links = str_replace(')', ')</span>', $links);		
		return $links;
	}
	
	function filter_archive_count( $links ) {
		$links = str_replace('(', '<span>', $links);
		$links = str_replace(')', '</span>', $links);

		return $links;
	}
	
	/**
	 * Customize password form
	 **/
	function customize_password_protect() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<form class="post-password-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
    <p>' . __( "To view this protected post, enter the password below:", "wproto" ) . '</p>
    <input class="pass" name="post_password" id="' . $label . '" type="password" maxlength="20" /><input type="submit" name="Submit" value="' . esc_attr__( "Submit" ) . '" />
    </form>
    ';
    return $o;
	}
	
	/**
	 * Change post output for gallery post format
	 **/
	function filter_content( $content ) {
		global $post, $wpl_exe_wp;
		
		$post_style = $wpl_exe_wp->get_option('blog_single_style', 'posts');
		$post_style = $wpl_exe_wp->get_post_option('wproto_single_style') ? $wpl_exe_wp->get_post_option('wproto_single_style') : $post_style;
		
		$post_format = get_post_format();
		
		if( $post_format == 'gallery' ) {
			$content = wpl_exe_wp_front::strip_shortcode( 'gallery', $content );
		}
		
		if( $post_style == 'alt' && $post_format == 'video' ) {
			$content = wpl_exe_wp_front::strip_shortcode( 'vc_video', $content );
			$content = preg_replace("/(<iframe[^<]+<\/iframe>)/", '', $content);
		}
		
		if( $post_style == 'alt' && $post_format == 'audio' ) {
			$content = preg_replace("/(<iframe[^<]+<\/iframe>)/", '', $content);
		}
		
		return $content;
	}
	
	/**
	 * Title filter
	 **/
	function wp_title( $title, $sep ) {
		global $paged, $page;
	
		if ( is_feed() )
			return $title;

		if( is_home() || is_front_page() )
			return $title;

		return $title . ' ' . $sep . ' ' . get_bloginfo( 'description' );
			
	}	 	 	
	
	/**
	 * Fix captions
	 **/
	function fix_captions( $x=null, $attr, $content ) {
    extract( shortcode_atts( array(
      'id'    => '',
      'align'    => 'alignnone',
      'width'    => '',
      'caption' => ''
    ), $attr));

    if ( 1 > (int) $width || empty($caption) ) {
      return $content;
    }

    if ( $id ) $id = 'id="' . $id . '" ';

    return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . ((int) $width ) . 'px">' . $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
    
	}		
	
}