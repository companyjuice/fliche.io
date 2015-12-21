<?php
/**
 * Base controller
 **/
class wpl_exe_wp_base_controller extends wpl_exe_wp_core_controller {
	
	/**
	 * Below ALL add_action() and add_filter() hooks that
	 * get served by the methods of this controller
	 * @param array
	 **/
	function __construct() {
	
		// Load libraries
		if( is_admin() ) {
			require_once WPROTO_THEME_DIR . '/library/tgm-plugin-activation/class-tgm-plugin-activation.php';	
		}
	
		// Translation support
		load_theme_textdomain( 'wproto', WPROTO_THEME_DIR . '/languages' );
		
		// activate plugins
		add_action( 'tgmpa_register', array( $this, 'register_plugins') );
		
		// Register custom post types and taxonomies
		add_action( 'init', array( $this, 'register_custom_post_types'), 5);
		add_action( 'init', array( $this, 'deregister_custom_post_types'), 20);
		add_action( 'init', array( $this, 'register_taxonomies'), 5);
		
		// Add theme support
		add_action( 'init', array( $this, 'add_theme_support'));
		add_action( 'admin_init', array( $this, 'fix_vc'));
		
		add_filter( 'use_default_gallery_style', '__return_false' );
		
		register_nav_menus( array(
			'header_menu' => __('Header Menu', 'wproto')
		) );
		
		register_nav_menus( array(
			'bottom_bar_menu' => __('Bottom Bar Menu', 'wproto')
		) );
		
		// Register sidebars
		add_action( 'init', array( $this, 'register_sidebars'));
		add_action( 'widgets_init', array( $this, 'create_sidebars'));
		
	}
	
	/**
	 * Register plugins
	 **/
	function register_plugins() {
		
		$plugins = array(
			array(
				'name'     						=> 'JS Composer',
				'slug'     						=> 'js_composer',
				'source'   						=> WPROTO_THEME_DIR . '/library/tgm-plugin-activation/plugins/js_composer.zip',
				'required' 						=> false,
				'version' 						=> '4.5.1',
				'force_activation' 		=> false,
				'force_deactivation' 	=> false,
				'external_url' 				=> 'http://codecanyon.net/item/visual-composer-for-wordpress/242431'
			),
			array(
				'name'     						=> 'Slider Revolution',
				'slug'     						=> 'revslider',
				'source'   						=> WPROTO_THEME_DIR . '/library/tgm-plugin-activation/plugins/revslider.zip',
				'required' 						=> false,
				'version' 						=> '4.6.9',
				'force_activation' 		=> false,
				'force_deactivation' 	=> false,
				'external_url' 				=> 'http://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380'
			),
			array(
				'name' 		=> 'Ninja Forms',
				'slug' 		=> 'ninja-forms',
				'version' => '2.9.7',
				'required' 	=> false,
				'force_activation' => false
			),
			array(
				'name' 		=> 'Ninja Forms Layout',
				'slug' 		=> 'ninja-forms-layout',
				'version' => '1.2',
				'required' 	=> false,
				'force_activation' => false
			),
			array(
				'name' 		=> 'MailChimp for WordPress',
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false,
				'version' => '2.2.8',
				'force_activation' => false
			),
			array(
				'name' 		=> 'WooCommerce',
				'slug' 		=> 'woocommerce',
				'version' => '2.3.7',
				'required' 	=> false,
				'force_activation' => false
			),
			array(
				'name'     						=> 'Envato WordPress Toolkit',
				'slug'     						=> 'envato-wordpress-toolkit',
				'source'   						=> WPROTO_THEME_DIR . '/library/tgm-plugin-activation/plugins/envato-wordpress-toolkit.zip',
				'required' 						=> false,
				'version' 						=> '1.7.3',
				'force_activation' 		=> false,
				'force_deactivation' 	=> false,
			),
		);

		tgmpa( $plugins, array(
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
            'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
            'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    ) );
		
	}
	
	/**
	 * Add theme support
	 **/
	function add_theme_support() {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );		
		add_theme_support( 'menus' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-formats', array( 'gallery', 'quote', 'video', 'audio', 'link' ) );
		add_theme_support( 'post-thumbnails' );
		
		remove_post_type_support( 'page', 'comments' );
		remove_post_type_support( 'page', 'thumbnail' );
		remove_post_type_support( 'wproto_pricing_table', 'thumbnail' );
	}
	
	function fix_vc() {
		global $typenow;
		
		// when editing pages, $typenow isn't set until later!
    if (empty($typenow)) {
        // try to pick it up from the query string
        if (!empty($_GET['post'])) {
            $post = get_post($_GET['post']);
            $typenow = $post->post_type;
        }
        // try to pick it up from the quick edit AJAX post
        elseif (!empty($_POST['post_ID'])) {
            $post = get_post($_POST['post_ID']);
            $typenow = $post->post_type;
        }
    }

	}	
	
	/**
	 * Deregister unused custom post types
	 **/
	function deregister_custom_post_types() {
		global $wp_post_types, $wpl_exe_wp;
		if( $wpl_exe_wp->get_option( 'use_stand_alone_vc', 'plugins' ) == false ) {
			if ( isset( $wp_post_types[ 'vc_grid_item' ] ) ) unset( $wp_post_types[ 'vc_grid_item' ] );
		}
	}
	
	/**
	 * Register custom post types
	 **/
	function register_custom_post_types() {
		global $wpl_exe_wp;
		
		if( $wpl_exe_wp->get_option( 'pricing_tables_enabled', 'general' ) ) {
		
			register_post_type( 'wproto_pricing_table',
				array(
					'label' 					=> __( 'Pricing Tables', 'wproto'),
					'description' 		=> '',
					'public' 					=> FALSE,
					'show_ui' 				=> TRUE,
					'show_in_menu' 		=> TRUE,
					'show_in_nav_menus' => FALSE,
					'capability_type' => 'post',
					'hierarchical' 		=> FALSE,
					'supports' 				=> array( 'title', 'custom-fields' ),
					'rewrite' 				=> FALSE,
					'has_archive' 		=> FALSE,
					'query_var' 			=> TRUE,
					'capabilities' => array(
	        	'publish_posts' => 'edit_pages',
	        	'edit_posts' => 'edit_pages',
	        	'edit_others_posts' => 'edit_pages',
	        	'delete_posts' => 'edit_pages',
	        	'delete_others_posts' => 'edit_pages',
	        	'read_private_posts' => 'edit_pages',
	        	'edit_post' => 'edit_pages',
	        	'delete_post' => 'edit_pages',
	        	'read_post' => 'edit_pages',
	    		),
					'labels'					=> array(
						'name' 									=> __( 'Pricing Tables', 'wproto'),
						'singular_name' 				=> __( 'Pricing Table', 'wproto'),
						'menu_name' 						=> __( 'Pricing Tables', 'wproto'),
						'add_new' 							=> __( 'Add Pricing Table', 'wproto'),
						'add_new_item' 					=> __( 'Add New Pricing Table', 'wproto'),
						'all_items' 						=> __( 'All Pricing Tables', 'wproto'),
						'edit_item' 						=> __( 'Edit Pricing Table', 'wproto'),
						'new_item' 							=> __( 'New Pricing Table', 'wproto'),
						'view_item' 						=> __( 'View Pricing Table', 'wproto'),
						'search_items' 					=> __( 'Search Pricing Tables', 'wproto'),
						'not_found' 						=> __( 'No Pricing Tables Found', 'wproto'),
						'not_found_in_trash'		=> __( 'No Pricing Tables Found in Trash', 'wproto'),
						'parent_item_colon' 		=> __( 'Parent Pricing Table:', 'wproto') )
				)
			);
			
		}
		
		$portfolio_slug = $wpl_exe_wp->get_option( 'portfolio_slug', 'general' );
		
		$portfolio_support = array( 'title', 'custom-fields', 'thumbnail', 'editor', 'excerpt' );
		
		if( $wpl_exe_wp->get_option( 'portfolio_comments', 'posts' ) ) {
			$portfolio_support[] = 'comments';
		}
		
		if( $wpl_exe_wp->get_option( 'portfolio_enabled', 'general' ) ) {
		
			register_post_type( 'wproto_portfolio',
				array(
					'label' 					=> __( 'Portfolio', 'wproto'),
					'description'			=> '',
					'public' 					=> TRUE,
					'show_ui' 				=> TRUE,
					'show_in_menu'		=> TRUE,
					'show_in_nav_menus' => TRUE,
					'capability_type' => 'post',
					'hierarchical' 		=> FALSE,
					'supports' 				=> $portfolio_support,
					'rewrite' 				=> array( 'slug' => $portfolio_slug ),
					'has_archive' 		=> FALSE,
					'query_var' 			=> TRUE,
					'capabilities' => array(
	        	'publish_posts' => 'edit_pages',
	        	'edit_posts' => 'edit_pages',
	        	'edit_others_posts' => 'edit_pages',
	        	'delete_posts' => 'edit_pages',
	        	'delete_others_posts' => 'edit_pages',
	        	'read_private_posts' => 'edit_pages',
	        	'edit_post' => 'edit_pages',
	        	'delete_post' => 'edit_pages',
	        	'read_post' => 'edit_pages',
	    		),
					'labels' 					=> array(
						'name' 									=> __( 'Portfolio', 'wproto'),
						'singular_name' 				=> __( 'Portfolio item', 'wproto'),
						'menu_name' 						=> __( 'Portfolio', 'wproto'),
						'add_new' 							=> __( 'Add Portfolio item', 'wproto'),
						'add_new_item' 					=> __( 'Add New Portfolio item', 'wproto'),
						'all_items' 						=> __( 'All Portfolio items', 'wproto'),
						'edit_item' 						=> __( 'Edit Portfolio item', 'wproto'),
						'new_item' 							=> __( 'New Portfolio item', 'wproto'),
						'view_item' 						=> __( 'View Portfolio item', 'wproto'),
						'search_items'					=> __( 'Search Portfolio items', 'wproto'),
						'not_found' 						=> __( 'No Portfolio items Found', 'wproto'),
						'not_found_in_trash' 		=> __( 'No Portfolio items Found in Trash', 'wproto'),
						'parent_item_colon' 		=> __( 'Parent Portfolio item:', 'wproto') )
				)
			);
			
		}
		
		if( $wpl_exe_wp->get_option( 'team_members_enabled', 'general' ) ) {
		
			register_post_type( 'wproto_team',
				array(
					'label' 					=> __( 'Team', 'wproto'),
					'description' 		=> '',
					'public' 					=> FALSE,
					'show_ui' 				=> TRUE,
					'show_in_menu' 		=> TRUE,
					'exclude_from_search' => TRUE,
					'show_in_nav_menus' => FALSE,
					'capability_type' => 'post',
					'hierarchical' 		=> FALSE,
					'supports' 				=> array( 'title', /*'editor',*/ 'custom-fields', 'thumbnail' ),
					'rewrite' 				=> FALSE,
					'has_archive' 		=> FALSE,
					'query_var' 			=> FALSE,
					'capabilities' => array(
	        	'publish_posts' => 'edit_pages',
	        	'edit_posts' => 'edit_pages',
	        	'edit_others_posts' => 'edit_pages',
	        	'delete_posts' => 'edit_pages',
	        	'delete_others_posts' => 'edit_pages',
	        	'read_private_posts' => 'edit_pages',
	        	'edit_post' => 'edit_pages',
	        	'delete_post' => 'edit_pages',
	        	'read_post' => 'edit_pages',
	    		),
					'labels' 					=> array(
						'name' 									=> __( 'Team', 'wproto'),
						'singular_name' 				=> __( 'Member', 'wproto'),
						'menu_name' 						=> __( 'Team members', 'wproto'),
						'add_new' 							=> __( 'Add team member', 'wproto'),
						'add_new_item' 					=> __( 'Add New team member', 'wproto'),
						'all_items' 						=> __( 'All team members', 'wproto'),
						'edit_item' 						=> __( 'Edit team member', 'wproto'),
						'new_item' 							=> __( 'New team member', 'wproto'),
						'view_item' 						=> __( 'View team member', 'wproto'),
						'search_items' 					=> __( 'Search team members', 'wproto'),
						'not_found' 						=> __( 'No team members Found', 'wproto'),
						'not_found_in_trash' 		=> __( 'No team members Found in Trash', 'wproto'),
						'parent_item_colon' 		=> __( 'Parent team member:', 'wproto') )
				)
			);
			
		}
		
		if( $wpl_exe_wp->get_option( 'benefits_enabled', 'general' ) ) {
			
			register_post_type( 'wproto_benefits',
				array(
					'label' 					=> __( 'Benefits', 'wproto'),
					'description' 		=> '',
					'public' 					=> FALSE,
					'show_ui' 				=> TRUE,
					'show_in_menu' 		=> TRUE,
					'exclude_from_search' => TRUE,
					'show_in_nav_menus' => FALSE,
					'capability_type' => 'post',
					'hierarchical' 		=> FALSE,
					'supports' 				=> array( 'title', 'editor', 'custom-fields', 'thumbnail' ),
					'rewrite' 				=> FALSE,
					'has_archive' 		=> FALSE,
					'query_var' 			=> FALSE,
					'capabilities' => array(
	        	'publish_posts' => 'edit_pages',
	        	'edit_posts' => 'edit_pages',
	        	'edit_others_posts' => 'edit_pages',
	        	'delete_posts' => 'edit_pages',
	        	'delete_others_posts' => 'edit_pages',
	        	'read_private_posts' => 'edit_pages',
	        	'edit_post' => 'edit_pages',
	        	'delete_post' => 'edit_pages',
	        	'read_post' => 'edit_pages',
	    		),
					'labels' 					=> array(
						'name' 									=> __( 'Benefits', 'wproto'),
						'singular_name'					=> __( 'Benefit', 'wproto'),
						'menu_name' 						=> __( 'Benefits', 'wproto'),
						'add_new' 							=> __( 'Add Benefit', 'wproto'),
						'add_new_item' 					=> __( 'Add New Benefit', 'wproto'),
						'all_items' 						=> __( 'All Benefits', 'wproto'),
						'edit_item' 						=> __( 'Edit Benefit', 'wproto'),
						'new_item' 							=> __( 'New Benefit', 'wproto'),
						'view_item' 						=> __( 'View Benefit', 'wproto'),
						'search_items' 					=> __( 'Search Benefits', 'wproto'),
						'not_found' 						=> __( 'No Benefits Found', 'wproto'),
						'not_found_in_trash' 		=> __( 'No Benefits Found in Trash', 'wproto'),
						'parent_item_colon' 		=> __( 'Parent Benefit:', 'wproto') )
				)
			);
			
		}
		
		if( $wpl_exe_wp->get_option( 'partners_clients_enabled', 'general' ) ) {
			
			register_post_type( 'wproto_partners',
				array(
					'label' 					=> __( 'Partners / Clients', 'wproto'),
					'description' 		=> '',
					'public' 					=> FALSE,
					'show_ui' 				=> TRUE,
					'show_in_menu' 		=> TRUE,
					'show_in_nav_menus' => FALSE,
					'exclude_from_search' => TRUE,
					'capability_type' => 'post',
					'hierarchical' 		=> FALSE,
					'supports' 				=> array( 'title', 'custom-fields', 'thumbnail' ),
					'rewrite' 				=> FALSE,
					'has_archive' 		=> FALSE,
					'query_var' 			=> FALSE,
					'capabilities' => array(
	        	'publish_posts' => 'edit_pages',
	        	'edit_posts' => 'edit_pages',
	        	'edit_others_posts' => 'edit_pages',
	        	'delete_posts' => 'edit_pages',
	        	'delete_others_posts' => 'edit_pages',
	        	'read_private_posts' => 'edit_pages',
	        	'edit_post' => 'edit_pages',
	        	'delete_post' => 'edit_pages',
	        	'read_post' => 'edit_pages',
	    		),
					'labels' 					=> array(
						'name' 									=> __( 'Partners / Clients', 'wproto'),
						'singular_name' 				=> __( 'Partner / Client', 'wproto'),
						'menu_name' 						=> __( 'Partners / Clients', 'wproto'),
						'add_new' 							=> __( 'Add Partner / Client', 'wproto'),
						'add_new_item' 					=> __( 'Add New Partner / Client', 'wproto'),
						'all_items' 						=> __( 'All Partners / Clients', 'wproto'),
						'edit_item' 						=> __( 'Edit Partner / Client', 'wproto'),
						'new_item' 							=> __( 'New Partner / Client', 'wproto'),
						'view_item' 						=> __( 'View Partner / Client', 'wproto'),
						'search_items' 					=> __( 'Search Partners / Clients', 'wproto'),
						'not_found' 						=> __( 'No Partners / Clients Found', 'wproto'),
						'not_found_in_trash' 		=> __( 'No Partners / Clients Found in Trash', 'wproto'),
						'parent_item_colon' 		=> __( 'Parent Partner / Client:', 'wproto') )
				)
			);
			
		}
		
		if( $wpl_exe_wp->get_option( 'testimonials_enabled', 'general' ) ) {
		
			register_post_type( 'wproto_testimonials',
				array(
					'label' 					=> __( 'Testimonials', 'wproto'),
					'description' 		=> '',
					'public' 					=> FALSE,
					'show_ui' 				=> TRUE,
					'show_in_menu' 		=> TRUE,
					'show_in_nav_menus' => FALSE,
					'exclude_from_search' => TRUE,
					'capability_type' => 'post',
					'hierarchical' 		=> FALSE,
					'supports' 				=> array( 'title', 'editor', 'custom-fields', 'thumbnail' ),
					'rewrite' 				=> FALSE,
					'has_archive' 		=> FALSE,
					'query_var' 			=> FALSE,
					'capabilities' => array(
	        	'publish_posts' => 'edit_pages',
	        	'edit_posts' => 'edit_pages',
	        	'edit_others_posts' => 'edit_pages',
	        	'delete_posts' => 'edit_pages',
	        	'delete_others_posts' => 'edit_pages',
	        	'read_private_posts' => 'edit_pages',
	        	'edit_post' => 'edit_pages',
	        	'delete_post' => 'edit_pages',
	        	'read_post' => 'edit_pages',
	    		),
					'labels' 					=> array(
						'name' 									=> __( 'Testimonials', 'wproto'),
						'singular_name' 				=> __( 'Testimonial', 'wproto'),
						'menu_name' 						=> __( 'Testimonials', 'wproto'),
						'add_new' 							=> __( 'Add Testimonial', 'wproto'),
						'add_new_item' 					=> __( 'Add New Testimonial', 'wproto'),
						'all_items' 						=> __( 'All Testimonials', 'wproto'),
						'edit_item' 						=> __( 'Edit Testimonial', 'wproto'),
						'new_item' 							=> __( 'New Testimonial', 'wproto'),
						'view_item' 						=> __( 'View Testimonial', 'wproto'),
						'search_items'					=> __( 'Search Testimonials', 'wproto'),
						'not_found' 						=> __( 'No Testimonials Found', 'wproto'),
						'not_found_in_trash' 		=> __( 'No Testimonials Found in Trash', 'wproto'),
						'parent_item_colon' 		=> __( 'Parent Testimonial:', 'wproto') )
				)
			);
			
		}
		
		register_post_type( 'wproto_custom_font',
			array(
				'label' 					=> __( 'Custom fonts', 'wproto'),
				'description' 		=> '',
				'public' 					=> FALSE,
				'show_ui' 				=> TRUE,
				'show_in_menu' 		=> FALSE,
				'show_in_nav_menus' => FALSE,
				'capability_type' => 'post',
				'hierarchical' 		=> FALSE,
				'supports' 				=> array( 'title', 'custom-fields' ),
				'rewrite' 				=> FALSE,
				'has_archive' 		=> FALSE,
				'query_var' 			=> FALSE,
				'capabilities' => array(
        	'publish_posts' => 'edit_pages',
        	'edit_posts' => 'edit_pages',
        	'edit_others_posts' => 'edit_pages',
        	'delete_posts' => 'edit_pages',
        	'delete_others_posts' => 'edit_pages',
        	'read_private_posts' => 'edit_pages',
        	'edit_post' => 'edit_pages',
        	'delete_post' => 'edit_pages',
        	'read_post' => 'edit_pages',
    		),
				'labels'					=> array(
					'name' 									=> __( 'Custom fonts', 'wproto'),
					'singular_name' 				=> __( 'Custom font', 'wproto'),
					'menu_name' 						=> __( 'Custom fonts', 'wproto'),
					'add_new' 							=> __( 'Add Custom font', 'wproto'),
					'add_new_item' 					=> __( 'Add New Custom font', 'wproto'),
					'all_items' 						=> __( 'All Custom fonts', 'wproto'),
					'edit_item' 						=> __( 'Edit Custom font', 'wproto'),
					'new_item' 							=> __( 'New Custom font', 'wproto'),
					'view_item' 						=> __( 'View Custom font', 'wproto'),
					'search_items' 					=> __( 'Search Custom fonts', 'wproto'),
					'not_found' 						=> __( 'No Custom fonts Found', 'wproto'),
					'not_found_in_trash'		=> __( 'No Custom fonts Found in Trash', 'wproto'),
					'parent_item_colon' 		=> __( 'Parent Custom font:', 'wproto') )
			)
		);
		
	}
	
	/**
	 * Register custom taxonomies
	 **/
	function register_taxonomies() {
		
		register_taxonomy( 'wproto_sidebars',
			NULL,
			array(
				'hierarchical' 				=> FALSE,
				'show_ui' 						=> TRUE,
				'query_var' 					=> FALSE,
				'rewrite' 						=> FALSE,
				'show_admin_column'   => FALSE,
				'show_in_nav_menus' => FALSE,
				'labels'              => array(
					'name'                => _x( 'Widget Areas', 'taxonomy general name', 'wproto' ),
					'singular_name'       => _x( 'Widget Area', 'taxonomy singular name', 'wproto' ),
					'search_items'        => __( 'Search Areas', 'wproto' ),
					'all_items'           => __( 'All Widget Areas', 'wproto' ),
					'edit_item'           => __( 'Edit Widget Area', 'wproto' ), 
					'update_item'         => __( 'Update Widget Area', 'wproto' ),
					'add_new_item'        => __( 'Add New Widget Area', 'wproto' ),
					'new_item_name'       => __( 'New Widget Area', 'wproto' ),
					'menu_name'           => __( 'Widget Area', 'wproto' )
				)
			)
		);
		
		register_taxonomy( 'wproto_benefits_category',
			'wproto_benefits',
			array(
				'hierarchical' 				=> TRUE,
				'show_ui' 						=> TRUE,
				'query_var' 					=> FALSE,
				'show_in_nav_menus' => FALSE,
				'rewrite' 						=> FALSE,
				'show_admin_column'   => TRUE,
				'labels'              => array(
					'name'                => _x( 'Benefits Categories', 'taxonomy general name', 'wproto' ),
					'singular_name'       => _x( 'Benefits Category', 'taxonomy singular name', 'wproto' ),
					'search_items'        => __( 'Search in categories', 'wproto' ),
					'all_items'           => __( 'All Categories', 'wproto' ),
					'edit_item'           => __( 'Edit Category', 'wproto' ), 
					'update_item'         => __( 'Update Category', 'wproto' ),
					'add_new_item'        => __( 'Add New Category', 'wproto' ),
					'new_item_name'       => __( 'New Category', 'wproto' ),
					'menu_name'           => __( 'Benefits Categories', 'wproto' )
				)
			)
		);
		
		register_taxonomy( 'wproto_partners_category',
			'wproto_partners',
			array(
				'hierarchical' 				=> TRUE,
				'show_ui' 						=> TRUE,
				'query_var' 					=> FALSE,
				'rewrite' 						=> FALSE,
				'show_in_nav_menus' => FALSE,
				'show_admin_column'   => TRUE,
				'labels'              => array(
					'name'                => _x( 'Categories', 'taxonomy general name', 'wproto' ),
					'singular_name'       => _x( 'Category', 'taxonomy singular name', 'wproto' ),
					'search_items'        => __( 'Search in categories', 'wproto' ),
					'all_items'           => __( 'All Categories', 'wproto' ),
					'edit_item'           => __( 'Edit Category', 'wproto' ), 
					'update_item'         => __( 'Update Category', 'wproto' ),
					'add_new_item'        => __( 'Add New Category', 'wproto' ),
					'new_item_name'       => __( 'New Category', 'wproto' ),
					'menu_name'           => __( 'Categories', 'wproto' )
				)
			)
		);
		
		register_taxonomy( 'wproto_portfolio_category',
			'wproto_portfolio',
			array(
				'hierarchical' 				=> TRUE,
				'show_ui' 						=> TRUE,
				'show_in_nav_menus' => TRUE,
				'query_var' 					=> TRUE,
				'rewrite' 						=> array( 'slug' => 'portfolio-category' ),
				'show_admin_column'   => TRUE,
				'labels'              => array(
					'name'                => _x( 'Portfolio Categories', 'taxonomy general name', 'wproto' ),
					'singular_name'       => _x( 'Portfolio Category', 'taxonomy singular name', 'wproto' ),
					'search_items'        => __( 'Search in categories', 'wproto' ),
					'all_items'           => __( 'All Categories', 'wproto' ),
					'edit_item'           => __( 'Edit Category', 'wproto' ), 
					'update_item'         => __( 'Update Category', 'wproto' ),
					'add_new_item'        => __( 'Add New Category', 'wproto' ),
					'new_item_name'       => __( 'New Category', 'wproto' ),
					'menu_name'           => __( 'Portfolio Categories', 'wproto' )
				)
			)
		);
		
		register_taxonomy( 'wproto_team_category',
			'wproto_team',
			array(
				'hierarchical' 				=> TRUE,
				'show_ui' 						=> TRUE,
				'query_var' 					=> FALSE,
				'show_in_nav_menus' => FALSE,
				'rewrite' 						=> FALSE,
				'show_admin_column'   => TRUE,
				'labels'              => array(
					'name'                => _x( 'Team Categories', 'taxonomy general name', 'wproto' ),
					'singular_name'       => _x( 'Team Category', 'taxonomy singular name', 'wproto' ),
					'search_items'        => __( 'Search in categories', 'wproto' ),
					'all_items'           => __( 'All Categories', 'wproto' ),
					'edit_item'           => __( 'Edit Category', 'wproto' ), 
					'update_item'         => __( 'Update Category', 'wproto' ),
					'add_new_item'        => __( 'Add New Category', 'wproto' ),
					'new_item_name'       => __( 'New Category', 'wproto' ),
					'menu_name'           => __( 'Team Categories', 'wproto' )
				)
			)
		);
		
		register_taxonomy( 'wproto_testimonials_category',
			'wproto_testimonials',
			array(
				'hierarchical' 				=> TRUE,
				'show_ui' 						=> TRUE,
				'query_var' 					=> FALSE,
				'show_in_nav_menus' => FALSE,
				'rewrite' 						=> FALSE,
				'show_admin_column'   => TRUE,
				'labels'              => array(
					'name'                => _x( 'Testimonials Categories', 'taxonomy general name', 'wproto' ),
					'singular_name'       => _x( 'Testimonials Category', 'taxonomy singular name', 'wproto' ),
					'search_items'        => __( 'Search in categories', 'wproto' ),
					'all_items'           => __( 'All Categories', 'wproto' ),
					'edit_item'           => __( 'Edit Category', 'wproto' ), 
					'update_item'         => __( 'Update Category', 'wproto' ),
					'add_new_item'        => __( 'Add New Category', 'wproto' ),
					'new_item_name'       => __( 'New Category', 'wproto' ),
					'menu_name'           => __( 'Testimonials Categories', 'wproto' )
				)
			)
		);
		
	}
	
	/**
	 * Register sidebars
	 **/
	function register_sidebars() {
		global $pagenow;
		
		if( $pagenow == 'themes.php' && isset( $_GET['activated'] ) ) {
			
			$side_left = term_exists( 'sidebar-left', 'wproto_sidebars' );

			if( $side_left === 0 || $side_left === NULL ) {
				
				wp_insert_term( __( 'Left sidebar', 'wproto' ), 'wproto_sidebars', array(
					'description' => '',
					'slug' => 'sidebar-left'
				));
				
			}
		
			$side_right = term_exists( 'sidebar-right', 'wproto_sidebars' );
			
			if( $side_right === 0 || $side_right === NULL ) {
		
				wp_insert_term( __( 'Right sidebar', 'wproto' ), 'wproto_sidebars', array(
					'description' => '',
					'slug' => 'sidebar-right'
				));
		
			}
			
			$side_footer_4 = term_exists( 'sidebar-footer', 'wproto_sidebars' );
			
			if( $side_footer_4 === 0 || $side_footer_4 === NULL ) {
			
				wp_insert_term( __( 'Footer 4 Columns Widget Area', 'wproto' ), 'wproto_sidebars', array(
					'description' => '',
					'slug' => 'sidebar-footer'
				));
				
			}
			
			$side_footer_3 = term_exists( 'sidebar-footer-3-cols', 'wproto_sidebars' );
			
			if( $side_footer_3 === 0 || $side_footer_3 === NULL ) {
			
				wp_insert_term( __( 'Footer 3 Columns Widget Area', 'wproto' ), 'wproto_sidebars', array(
					'description' => '',
					'slug' => 'sidebar-footer-3-cols'
				));
				
			}
			
			$side_footer_2 = term_exists( 'sidebar-footer-2-cols', 'wproto_sidebars' );
			
			if( $side_footer_2 === 0 || $side_footer_2 === NULL ) {
			
				wp_insert_term( __( 'Footer 2 Columns Widget Area', 'wproto' ), 'wproto_sidebars', array(
					'description' => '',
					'slug' => 'sidebar-footer-2-cols'
				));
				
			}
			
			$side_footer_1 = term_exists( 'sidebar-footer-1-col', 'wproto_sidebars' );
			
			if( $side_footer_1 === 0 || $side_footer_1 === NULL ) {
			
				wp_insert_term( __( 'Footer 1 Column Widget Area', 'wproto' ), 'wproto_sidebars', array(
					'description' => '',
					'slug' => 'sidebar-footer-1-col'
				));
				
			}
			
			$side_shop = term_exists( 'shop', 'wproto_sidebars' );
			
			if( $side_shop === 0 || $side_shop === NULL ) {
			
				wp_insert_term( __( 'Shop Widget Area', 'wproto' ), 'wproto_sidebars', array(
					'description' => '',
					'slug' => 'shop'
				));
				
			}
			
			$side_megamenu = term_exists( 'mega-menu', 'wproto_sidebars' );
			
			if( $side_shop === 0 || $side_shop === NULL ) {
			
				wp_insert_term( __( 'Mega Menu Widget Area', 'wproto' ), 'wproto_sidebars', array(
					'description' => '',
					'slug' => 'mega-menu'
				));
				
			}
			
		}
		
		$sidebars = get_terms( 'wproto_sidebars', array( 'hide_empty' => false ) );
		
		// here we created user-defined sidebars
		
		if( count( $sidebars ) > 0 ) {
			foreach( $sidebars as $sidebar ) {
				
				register_sidebar( array(
					'name'          => $sidebar->name,
					'id'            => $sidebar->slug,
					'description'   => $sidebar->description,
					'class'         => $sidebar->slug,
					'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inside">',
					'after_widget'  => '<div class="clearfix"></div></div></div>',
					'before_title'  => '<h4 class="widget-title">',
					'after_title'   => '</h4>'
				));
				
			}
		}
		
	}
	
	/**
	 * Create sidebars
	 **/
	function create_sidebars() {

	}
		
}