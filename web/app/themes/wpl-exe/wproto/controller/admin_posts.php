<?php
/**
 * Admin posts controller
 **/
class wpl_exe_wp_admin_posts_controller extends wpl_exe_wp_core_controller {
	
	function __construct() {

		if( is_admin() ) {
			
			// remove unused metaboxes
			add_action( 'add_meta_boxes', array( $this, 'add_remove_metaboxes' ) );
			
			add_action( 'save_post', array( $this, 'save_custom_meta' ) );
			
			// Posts screen filters
			add_filter( 'manage_edit-post_columns', array( $this, 'posts_manage_admin_columns' ) );
			add_action( 'manage_post_posts_custom_column', array( $this, 'posts_get_admin_columns' ), 10, 2);
			
			// Pages screen
			add_filter( 'manage_edit-page_columns', array( $this, 'manage_pages_admin_columns' ) );
			add_action( 'manage_page_posts_custom_column', array( $this, 'get_pages_admin_columns' ), 10, 2);
			
			// Benefits post type admin screen settings
			add_filter( 'manage_edit-wproto_benefits_columns', array( $this, 'benefits_manage_admin_columns' ) );
			add_action( 'manage_wproto_benefits_posts_custom_column', array( $this, 'benefits_get_admin_columns' ), 10, 2);
			
			// Partners / clients post type admin screen settings
			add_filter( 'manage_edit-wproto_partners_columns', array( $this, 'partners_manage_admin_columns' ) );
			add_action( 'manage_wproto_partners_posts_custom_column', array( $this, 'partners_get_admin_columns' ), 10, 2);
			
			// Portfolio post type admin screen settings
			add_filter( 'manage_edit-wproto_portfolio_columns', array( $this, 'portfolio_manage_admin_columns' ) );
			add_action( 'manage_wproto_portfolio_posts_custom_column', array( $this, 'portfolio_get_admin_columns' ), 10, 2);
			
			// Team post type admin screen settings
			add_filter( 'manage_edit-wproto_team_columns', array( $this, 'team_manage_admin_columns' ) );
			add_action( 'manage_wproto_team_posts_custom_column', array( $this, 'team_get_admin_columns' ), 10, 2);
			
			// Testimonials post type admin screen settings
			add_filter( 'manage_edit-wproto_testimonials_columns', array( $this, 'testimonials_manage_admin_columns' ) );
			add_action( 'manage_wproto_testimonials_posts_custom_column', array( $this, 'testimonials_get_admin_columns' ), 10, 2);
			
			// Admin screen filters
			add_action( 'restrict_manage_posts', array( $this, 'admin_posts_filter' ) );
			add_filter( 'parse_query', array( $this, 'admin_posts_query_filter' ) );
			
			// Custom JS for a custom post types
			add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
			
		} 
		
		
	}
	
	/**
	 * Remove unused metaboxes and add our own
	 **/
	function add_remove_metaboxes() {
		global $wpl_exe_wp;
		
		/** Remove unused metaboxes **/
		remove_meta_box( 'vc_teaser', 'page', 'side');
		remove_meta_box( 'vc_teaser', 'post', 'side');
		remove_meta_box( 'vc_teaser', 'wproto_mega_menu', 'side');
		remove_meta_box( 'mymetabox_revslider_0', 'post', 'normal');
		remove_meta_box( 'mymetabox_revslider_0', 'page', 'normal');
		remove_meta_box( 'mymetabox_revslider_0', 'wproto_portfolio', 'normal');
		remove_meta_box( 'mymetabox_revslider_0', 'wproto_team', 'normal');
		remove_meta_box( 'mymetabox_revslider_0', 'wproto_benefits', 'normal');
		remove_meta_box( 'mymetabox_revslider_0', 'wproto_partners', 'normal');
		remove_meta_box( 'mymetabox_revslider_0', 'wproto_testimonials', 'normal');
		remove_meta_box( 'mymetabox_revslider_0', 'wproto_mega_menu', 'normal');
		remove_meta_box( 'mymetabox_revslider_0', 'wproto_custom_font', 'normal');
		remove_meta_box( 'mymetabox_revslider_0', 'wproto_pricing_table', 'normal');
		
		/** Featured post metabox **/
		foreach( array( 'post', 'page', 'wproto_portfolio' ) as $custom_post_type ) {
			
			// Page redirect metabox
			add_meta_box(
				'wproto_redirect'
				, __( 'Redirect Options', 'wproto' )
				, array( $this, 'render_redirect_metabox' )
				, $custom_post_type
				, 'side'
				, 'low'
			);
			
			add_meta_box(
				'wproto_header_settings'
				, __( 'Header options', 'wproto' )
				, array( $this, 'render_metabox_header_settings' )
				, $custom_post_type
				, 'normal'
				, 'default'
			);			
			
			if( $custom_post_type != 'page' ) {
			
				add_meta_box(
					'wproto_meta_featured'
					, __( 'Featured post', 'wproto' )
					, array( $this, 'render_meta_box_post_featured' )
					, $custom_post_type
					, 'side'
					, 'high'
				);
				
			}
			
			if( !in_array( $custom_post_type, array('page') ) ){
				add_meta_box(
					'wproto_appearance_settings'
					, __( 'Post / Page appearance options', 'wproto' )
					, array( $this, 'render_metabox_appearance_settings' )
					, $custom_post_type
					, 'normal'
					, 'default'
				);	
			}	
			
			// Sidebar chooser
			if( !in_array( $custom_post_type, array('wproto_portfolio') ) ) {
				add_meta_box(
					'wproto_sidebar_settings'
					, __( 'Sidebar options', 'wproto' )
					, array( $this, 'render_metabox_sidebar_options' )
					, $custom_post_type
					, 'normal'
					, 'default'
				);
			}
			
			add_meta_box(
				'wproto_footer_settings'
				, __( 'Footer options', 'wproto' )
				, array( $this, 'render_metabox_footer_settings' )
				, $custom_post_type
				, 'normal'
				, 'default'
			);	
			
		}
		
		/** Custom fonts metaboxes **/
		add_meta_box(
			'wproto_form_settings'
			, __( 'Font properties', 'wproto' )
			, array( $this, 'render_meta_box_custom_fonts_settings' )
			, 'wproto_custom_font'
			, 'normal'
			, 'default'
		);
		
		/** Benefits custom metaboxes **/
		add_meta_box(
			'wproto_meta_benefit_type'
			, __( 'Style settings', 'wproto' )
			, array( $this, 'render_meta_box_benefit_type' )
			, 'wproto_benefits'
			, 'side'
			, 'default'
		);
		
		/** Portfolio **/
		foreach( array( 'wproto_portfolio') as $custom_post_type ) {
			
			add_meta_box(
				'wproto_meta_portfolio_info'
				, __( 'Additional information', 'wproto' )
				, array( $this, 'render_meta_box_portfolio_info' )
				, $custom_post_type
				, 'normal'
				, 'default'
			);
			
			add_meta_box(
				'wproto_meta_attached_images'
				, __( 'Attached Images', 'wproto' )
				, array( $this, 'render_meta_box_post_attached_images' )
				, $custom_post_type
				, 'normal'
				, 'default'
			);
			
		}
		
		/** Team members **/
		
		add_meta_box(
			'wproto_team_connections'
			, __( 'Connections', 'wproto' )
			, array( $this, 'team_render_meta_box_connections' )
			, 'wproto_team'
			, 'advanced'
			, 'default'
		);
		
		add_meta_box(
			'wproto_team_public_info'
			, __( 'Public information', 'wproto' )
			, array( $this, 'team_render_meta_box_public_info' )
			, 'wproto_team'
			, 'advanced'
			, 'default'
		);
		
		/** Testimonials **/
		
		add_meta_box(
			'wproto_meta_additional'
			, __( 'Additional info', 'wproto' )
			, array( $this, 'testimonials_render_meta_box_additional_info' )
			, 'wproto_testimonials'
			, 'side'
			, 'default'
		);
		
		/** Partners / Clients **/
		
		add_meta_box(
			'wproto_additional_info'
			, __( 'Additional info', 'wproto' )
			, array( $this, 'partners_clients_render_metabox_info' )
			, 'wproto_partners'
			, 'side'
			, 'default'
		);
				
		/** Menus **/		
		remove_meta_box( 'postcustom', 'wproto_mega_menu', 'normal');
		remove_meta_box( 'vc_teaser', 'wproto_mega_menu', 'side');
		
		/** Pricing tables **/
		add_meta_box(
			'wproto_pricing_table_editor'
			,__( 'Pricing Table Editor', 'wproto' )
			,array( $this, 'render_meta_box_pricing_table_editor' )
			,'wproto_pricing_table'
			,'normal'
			,'high'
		);
		
	}
	
	
	/**
	 * Save meta for custom metaboxes
	 **/
	function save_custom_meta( $post_id ) {
		
		$post_type = get_post_type( $post_id );
		$allowed_tags = wp_kses_allowed_html( 'post' );
		
		$allowed_post_types = array('post', 'page', 'wproto_benefits', 'wproto_portfolio', 'wproto_custom_font', 'wproto_partners', 'wproto_team', 'wproto_testimonials', 'wproto_pricing_table');
		$allowed_changes = in_array( $post_type, $allowed_post_types );
		
		// Stop WP from clearing custom fields on autosave
		if ( $allowed_changes && defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE )
			return;

		// Prevent quick edit from clearing custom fields
		if ( $allowed_changes && defined( 'DOING_AJAX') && DOING_AJAX )
			return;
			
		// Do nothing during a bulk edit
		if( isset( $_REQUEST['bulk_edit'] ) ) {
			return;
		}

		if( $allowed_changes && empty( $_POST) )
			return;
		
		if( $allowed_changes && isset( $_POST['wproto_settings'] ) && is_array( $_POST['wproto_settings'] ) && count( $_POST['wproto_settings'] ) > 0 ) {
			
			foreach( $_POST['wproto_settings'] as $k=>$v ) {
				update_post_meta( $post_id, wp_kses( $k, $allowed_tags ), is_string( $v ) ? wp_kses( str_replace( "'", '&#39;', stripslashes( $v ) ), $allowed_tags ) : $v );
			}
			
		}
		
		if( $post_type == 'post' ) {
			update_post_meta( $post_id, "featured", isset( $_POST["featured"] ) ? $_POST["featured"] : false );
		}
		
		if( $post_type == 'wproto_benefits' ) {
			
			update_post_meta( $post_id, "wproto_benefit_link", isset( $_POST["wproto_benefit_link"] ) ? wp_kses( $_POST["wproto_benefit_link"], $allowed_tags ) : '' );	
			update_post_meta( $post_id, "wproto_benefit_style", isset( $_POST["wproto_benefit_style"] ) ? wp_kses( $_POST["wproto_benefit_style"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "wproto_benefit_icon_name", isset( $_POST["wproto_benefit_icon_name"] ) ? wp_kses( $_POST["wproto_benefit_icon_name"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "wproto_benefit_animation", isset( $_POST["wproto_benefit_animation"] ) ? wp_kses( $_POST["wproto_benefit_animation"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "wproto_benefit_animation_delay", isset( $_POST["wproto_benefit_animation_delay"] ) ? wp_kses( $_POST["wproto_benefit_animation_delay"], $allowed_tags ) : '' );
			
			update_post_meta( $post_id, "wproto_benefit_svg_image", isset( $_POST["wproto_benefit_svg_image"] ) ? wp_kses( $_POST["wproto_benefit_svg_image"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "wproto_benefit_svg_image_width", isset( $_POST["wproto_benefit_svg_image_width"] ) ? wp_kses( $_POST["wproto_benefit_svg_image_width"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "wproto_benefit_svg_image_height", isset( $_POST["wproto_benefit_svg_image_height"] ) ? wp_kses( $_POST["wproto_benefit_svg_image_height"], $allowed_tags ) : '' );
			
		}
		
		if( in_array( $post_type, array( 'wproto_portfolio', 'page', 'post') ) ) {
				
			if( isset( $_POST["wproto_attached_images"] ) && is_array( $_POST["wproto_attached_images"] ) ) {
				update_post_meta( $post_id, "wproto_attached_images", $_POST["wproto_attached_images"] );
			} else {
				update_post_meta( $post_id, "wproto_attached_images", '' );
			}
			
		}
		
		if( $post_type == 'wproto_custom_font' ) {
			
			update_post_meta( $post_id, "font_family", isset( $_POST["font_family"] ) ? wp_kses( $_POST["font_family"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "file_eot", isset( $_POST["file_eot"] ) ? wp_kses( $_POST["file_eot"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "file_woff2", isset( $_POST["file_woff2"] ) ? wp_kses( $_POST["file_woff2"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "file_woff", isset( $_POST["file_woff"] ) ? wp_kses( $_POST["file_woff"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "file_truetype", isset( $_POST["file_truetype"] ) ? wp_kses( $_POST["file_truetype"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "file_svg", isset( $_POST["file_svg"] ) ? wp_kses( $_POST["file_svg"], $allowed_tags ) : '' );
			
		}
		
		if( $post_type == 'wproto_portfolio' ) {
			update_post_meta( $post_id, "featured", isset( $_POST["featured"] ) ? wp_kses( $_POST["featured"], $allowed_tags ) : false );
		}
		
		if( $post_type == 'wproto_partners' ) {
			update_post_meta( $post_id, "url", isset( $_POST["url"] ) ? wp_kses( $_POST["url"], $allowed_tags ) : false );
		}
		
		if( $post_type == 'wproto_team' ) {
			update_post_meta( $post_id, "custom_url", isset( $_POST["custom_url"] ) ? wp_kses( $_POST["custom_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "position", isset( $_POST["position"] ) ? wp_kses( $_POST["position"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "twitter_url", isset( $_POST["twitter_url"] ) ? wp_kses( $_POST["twitter_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "facebook_url", isset( $_POST["facebook_url"] ) ? wp_kses( $_POST["facebook_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "linkedin_url", isset( $_POST["linkedin_url"] ) ? wp_kses( $_POST["linkedin_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "youtube_url", isset( $_POST["youtube_url"] ) ? wp_kses( $_POST["youtube_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "pinterest_url", isset( $_POST["pinterest_url"] ) ? wp_kses( $_POST["pinterest_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "dribbble_url", isset( $_POST["dribbble_url"] ) ? wp_kses( $_POST["dribbble_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "google_plus_url", isset( $_POST["google_plus_url"] ) ? wp_kses( $_POST["google_plus_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "flickr_url", isset( $_POST["flickr_url"] ) ? wp_kses( $_POST["flickr_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "tumblr_url", isset( $_POST["tumblr_url"] ) ? wp_kses( $_POST["tumblr_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "bitbucket_url", isset( $_POST["bitbucket_url"] ) ? wp_kses( $_POST["bitbucket_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "github_url", isset( $_POST["github_url"] ) ? wp_kses( $_POST["github_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "instagram_url", isset( $_POST["instagram_url"] ) ? wp_kses( $_POST["instagram_url"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "skype_login", isset( $_POST["skype_login"] ) ? wp_kses( $_POST["skype_login"], $allowed_tags ) : '' );
			
			update_post_meta( $post_id, "author_id", isset( $_POST["author_id"] ) ? wp_kses( $_POST["author_id"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "connect_with_wp_author", isset( $_POST["connect_with_wp_author"] ) ? (bool)$_POST["connect_with_wp_author"] : false );
			
		}
		
		if( $post_type == 'wproto_testimonials' ) {
			update_post_meta( $post_id, "position", isset( $_POST["position"] ) ? wp_kses( $_POST["position"], $allowed_tags ) : '' );
			update_post_meta( $post_id, "url", isset( $_POST["url"] ) ? wp_kses( $_POST["url"], $allowed_tags ) : '' );
		}
		
		if( $post_type == 'wproto_pricing_table' ) {
			update_post_meta( $post_id, "pricing_table", isset( $_POST["pt"] ) ? $_POST["pt"] : '' );
		}
		
	}
	
	/**
	 * Manage admin columns for blog posts
	 **/
	function posts_manage_admin_columns( $columns ) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['image'] = __( 'Photo', 'wproto');
		$new_columns['title'] = __( 'Name', 'wproto' );
		$new_columns['comments'] = '<span class="vers"><div class="comment-grey-bubble"></div></span>';
		
		if ( current_user_can( 'edit_pages' ) ):
		
			$new_columns['is_sticky'] = __( 'Sticky', 'wproto');
			$new_columns['is_featured'] = __( 'Featured', 'wproto');
			
		endif;
			
		$new_columns['tags'] = __( 'Tags', 'wproto');
		$new_columns['categories'] = __( 'Categories', 'wproto');
		$new_columns['author'] = __( 'Author', 'wproto' );
		$new_columns['date'] = __( 'Date', 'wproto' );
		
		return $new_columns;
	}
	
	/**
	 * Get admin columns for blog posts
	 **/
	function posts_get_admin_columns( $column_name, $id ) {
		global $wpl_exe_wp;
		switch ( $column_name ) {
			case 'image':
				echo '<a href="' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . '">';
				echo '<div class="wproto-admin-thumb">';
				if ( has_post_thumbnail() ):
				
					$url_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'thumb' );
					
					echo '<img width="100" src="' . $url_arr[0] . '" alt="" />';
				else: 
					echo '<img width="100" src="' . WPROTO_THEME_URL . '/images/admin/noimage-2x.gif" alt="" />';
				endif;
				echo '</div>';
				echo '</a>';
			break;
			case 'is_sticky': 
				$wpl_exe_wp->view->load_partial( 'admin_filters/is_sticky', array( 'post_id' => $id ) );
			break;
			case 'is_featured': 
				$wpl_exe_wp->view->load_partial( 'admin_filters/is_featured', array( 'post_id' => $id, 'is_featured' => get_post_meta( $id, 'featured', true ) ) );
			break;
		}
	}
	
	/**
	 * Manage admin columns
	 **/
	function manage_pages_admin_columns( $columns ) {
	
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = __( 'Title', 'wproto' );
		$new_columns['page_template'] = __( 'Page template', 'wproto');
		$new_columns['author'] = __( 'Author', 'wproto');
		$new_columns['date'] = __( 'Date', 'wproto' );
		
		return $new_columns;
	}
	
	/**
	 * Get the data for admin columns
	 **/
	function get_pages_admin_columns( $column_name, $id ) {
		switch ( $column_name ) {
			case 'page_template':
				echo ucwords( str_replace( '_', ' ', str_replace( '-', ' ', str_replace( 'page-tpl', '',  str_replace( '.php', '', basename( get_page_template() ) ) ) ) ) );
			break;
		}
	}
	
	/**
	 * Manage admin columns for a benefits post type
	 **/
	function benefits_manage_admin_columns( $columns ) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['image'] = __( 'Icon / Image', 'wproto');
		$new_columns['title'] = __( 'Title', 'wproto' );
		$new_columns['text'] = __( 'Text', 'wproto' );
		$new_columns['category'] = __( 'Categories', 'wproto');
		
		$new_columns['date'] = __( 'Date', 'wproto');
		
		return $new_columns;
	}
	
	/**
	 * Get the data for admin columns for a benefits post type
	 **/
	function benefits_get_admin_columns( $column_name, $id ) {
		global $wpl_exe_wp;
		$style = get_post_meta( $id, 'wproto_benefit_style', true );
		
		switch ( $column_name ) {
			case 'image':
				echo '<a href="' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . '">';
				echo '<div class="wproto-admin-thumb">';
				
				if( $style == '' || $style == 'image' ) {

					if ( has_post_thumbnail() ):
						$url_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'wproto-thumb-small' );
					
						echo '<img width="100" src="' . $url_arr[0] . '" alt="" />';
					else: 
						echo '<img width="100" src="' . WPROTO_THEME_URL . '/images/admin/noimage-2x.gif" alt="" />';
					endif;
					
				} elseif( $style == 'svg' ) {
					
					$icon = get_post_meta( $id, 'wproto_benefit_svg_image', true );
					$w = get_post_meta( $id, 'wproto_benefit_svg_image_width', true );
					$h = get_post_meta( $id, 'wproto_benefit_svg_image_height', true );
					
					echo '<img alt="" width="' . esc_attr( $w ) . '" height="' . esc_attr( $h ) . '" src="' . esc_attr( $icon ) . '" />';
					
				} else {
					
					$icon = get_post_meta( $id, 'wproto_benefit_icon_name', true );
					
					if ( $icon <> '' ) {
						echo '<i class="fa-4x ' . esc_attr( $icon ) . '"></i>';
					} else {
						
						echo '<img width="100" src="' . WPROTO_THEME_URL . '/images/admin/noimage-2x.gif" alt="" />';
					}
					
				}
				

				echo '</div>';
				echo '</a>';
			break;
			case 'text':
				the_excerpt();
			break;
			case 'category':
				$terms = get_the_terms( $id, 'wproto_benefits_category' );
				$wpl_exe_wp->view->load_partial( 'admin_filters/list_categories', array( 'terms' => $terms ) );
			break;
		}
	}
	
	/**
	 * Manage admin columns for a partners post type
	 **/
	function partners_manage_admin_columns( $columns ) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['image'] = __( 'Logo', 'wproto');
		$new_columns['title'] = __( 'Title', 'wproto' );
		$new_columns['category'] = __( 'Categories', 'wproto');
		
		$new_columns['date'] = __( 'Date', 'wproto');
		
		return $new_columns;
	}
	
	/**
	 * Get the data for admin columns for a partners / clients post type
	 **/
	function partners_get_admin_columns( $column_name, $id ) {
		global $wpl_exe_wp;
		
		switch ( $column_name ) {
			case 'image':
				echo '<a href="' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . '">';
				echo '<div class="wproto-admin-thumb">';
				
				if ( has_post_thumbnail() ):
					$url_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'wproto-thumb-small' );
					echo '<img width="100" src="' . $url_arr[0] . '" alt="" />';
				else: 
					echo '<img width="100" src="' . WPROTO_THEME_URL . '/images/admin/noimage-2x.gif" alt="" />';
				endif;
				
				echo '</div>';
				echo '</a>';
			break;
			case 'category':
				$terms = get_the_terms( $id, 'wproto_partners_category' );
				$wpl_exe_wp->view->load_partial( 'admin_filters/list_categories', array( 'terms' => $terms ) );
			break;
		}
	}
	
	/**
	 * Portfolio manage admin columns
	 **/
	function portfolio_manage_admin_columns( $columns ) {
	
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['image'] = __( 'Image', 'wproto');
		$new_columns['title'] = __( 'Title', 'wproto' );
		$new_columns['is_featured'] = __( 'Featured', 'wproto');
		$new_columns['category'] = __( 'Categories', 'wproto');
		$new_columns['date'] = __( 'Date', 'wproto' );
		
		return $new_columns;
	}
	
	/**
	 * Portfolio - get the data for admin columns
	 **/
	function portfolio_get_admin_columns( $column_name, $id ) {
		global $wpl_exe_wp;
		switch ( $column_name ) {
			case 'image':
				echo '<a href="' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . '">';
				echo '<div class="wproto-admin-thumb">';
				if ( has_post_thumbnail() ):
					$url_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'wproto-thumb-small' );
					echo '<img width="100" src="' . $url_arr[0] . '" alt="" />';
				else: 
					echo '<img width="100" src="' . WPROTO_THEME_URL . '/images/admin/noimage-2x.gif" alt="" />';
				endif;
				echo '</div>';
				echo '</a>';
			break;
			case 'is_featured': 
				$wpl_exe_wp->view->load_partial( 'admin_filters/is_featured', array( 'post_id' => $id, 'is_featured' => get_post_meta( $id, 'featured', true ) ) );
			break;
			case 'category':
				$terms = get_the_terms( $id, 'wproto_portfolio_category' );
				$wpl_exe_wp->view->load_partial( 'admin_filters/list_categories', array( 'terms' => $terms ) );
			break;
		}
	}
	
	/**
	 * Team - manage admin columns
	 **/
	function team_manage_admin_columns( $columns ) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['image'] = __( 'Photo', 'wproto');
		$new_columns['title'] = __( 'Name', 'wproto' );
		//$new_columns['text'] = __( 'Text', 'wproto' );
		$new_columns['category'] = __( 'Categories', 'wproto');
		
		$new_columns['date'] = __( 'Date', 'wproto' );
		
		return $new_columns;
	}
	
	/**
	 * Team - get the data for admin columns
	 **/
	function team_get_admin_columns( $column_name, $id ) {
		global $wpl_exe_wp;
		switch ( $column_name ) {
			case 'image':
				echo '<a href="' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . '">';
				echo '<div class="wproto-admin-thumb">';
				if ( has_post_thumbnail() ):
				
					$url_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'wproto-thumb-small' );
					
					echo '<img width="100" src="' . $url_arr[0] . '" alt="" />';
				else: 
					echo '<img width="100" src="' . WPROTO_THEME_URL . '/images/admin/noimage-2x.gif" alt="" />';
				endif;
				echo '</div>';
				echo '</a>';
			break;
			case 'text':
				the_excerpt();
			break;
			case 'category':
				$terms = get_the_terms( $id, 'wproto_team_category' );
				$wpl_exe_wp->view->load_partial( 'admin_filters/list_categories', array( 'terms' => $terms ) );
			break;
		}
	}
	
	/**
	 * Testimonials - manage admin columns
	 **/
	function testimonials_manage_admin_columns( $columns ) {
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['image'] = __( 'Photo', 'wproto');
		$new_columns['title'] = __( 'Name', 'wproto' );
		$new_columns['text'] = __( 'Text', 'wproto' );
		$new_columns['category'] = __( 'Categories', 'wproto');
		
		$new_columns['date'] = __( 'Date', 'wproto' );
		
		return $new_columns;
	}
	
	/**
	 * Testionials - get the data for admin columns
	 **/
	function testimonials_get_admin_columns( $column_name, $id ) {
		global $wpl_exe_wp;
		switch ( $column_name ) {
			case 'image':
				echo '<a href="' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . '">';
				echo '<div class="wproto-admin-thumb">';
				if ( has_post_thumbnail() ):
					$url_arr = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'wproto-thumb-small' );
					echo '<img width="100" src="' . $url_arr[0] . '" alt="" />';
				else: 
					echo '<img width="100" src="' . WPROTO_THEME_URL . '/images/admin/noimage-2x.gif" alt="" />';
				endif;
				echo '</div>';
				echo '</a>';
			break;
			case 'text':
				the_excerpt();
			break;
			case 'category':
				$terms = get_the_terms( $id, 'wproto_testimonials_category' );
				$wpl_exe_wp->view->load_partial( 'admin_filters/list_categories', array( 'terms' => $terms ) );
			break;
		}
	}
	
	/**
	 * Filter query by category at admin screen
	 **/
	function admin_posts_filter() {
		global $post, $wp_query, $wpl_exe_wp;
		$post_type = get_post_type( $post );		
		if ( $post_type == 'post' ) {
			
			$data = array( 'typenow' => 'post' );
			$wpl_exe_wp->view->load_partial( 'admin_filters/posts_filter', $data );
			$wpl_exe_wp->view->load_partial( 'admin_filters/ajax_loader' );
			
		} else if( $post_type == 'page' ) {
			
			$tpls = get_page_templates();
			ksort( $tpls );
			$data['templates'] = $tpls;
			$wpl_exe_wp->view->load_partial( 'admin_filters/pages_filter', $data );
			
		} else if ( $post_type == 'wproto_benefits' ) {
			
			$data = array();
			$data['wp_query'] = $wp_query;
			$data['taxonomy'] = 'wproto_benefits_category';
			$wpl_exe_wp->view->load_partial( 'admin_filters/category_filter', $data );
			
		} else if ( $post_type == 'wproto_portfolio' ) {
			
			$data = array( 'typenow' => 'wproto_portfolio' );
			$data['wp_query'] = $wp_query;
			$data['taxonomy'] = 'wproto_portfolio_category';
			$wpl_exe_wp->view->load_partial( 'admin_filters/category_filter', $data );
			$wpl_exe_wp->view->load_partial( 'admin_filters/posts_filter', $data );
			$wpl_exe_wp->view->load_partial( 'admin_filters/ajax_loader' );
			
		} else if ( $post_type == 'wproto_team' ) {
			
			$data = array();
			$data['wp_query'] = $wp_query;
			$data['taxonomy'] = 'wproto_team_category';
			$wpl_exe_wp->view->load_partial( 'admin_filters/category_filter', $data );
			
		} else if ( $post_type == 'wproto_testimonials' ) {
			
			$data = array();
			$data['wp_query'] = $wp_query;
			$data['taxonomy'] = 'wproto_testimonials_category';
			$wpl_exe_wp->view->load_partial( 'admin_filters/category_filter', $data );
			
		}
	}
	
	/**
	 * Query filter by category at admin screen
	 **/
	function admin_posts_query_filter( $query ) {
		global $typenow;
			
		if( isset( $_GET['featured'] ) && $_GET['featured'] ) {
			$query->set( 'meta_query', array(
				array(
					'key' => 'featured',
					'value' => true
				)
			));
		}
			
		if( isset( $_GET['post_format'] ) && $_GET['post_format'] <> '' ) {
			$query->set( 'tax_query', array(
				array(
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => array( $_GET['post_format'] )
				)
			));
		}
		
		if( $typenow == 'page' ) {
			
			if( isset( $_GET['page_template'] ) && trim( $_GET['page_template'] ) != '' ) {
				$query->set( 'meta_query', array(
					array(
						'key' => '_wp_page_template',
						'value' => $_GET['page_template']
					)
				));
			}
			
			if( isset( $_GET['page_redirect'] ) && trim( $_GET['page_redirect'] ) != '' ) {
				$query->set( 'meta_query', array(
					array(
						'key' => 'wproto_redirect_enabled',
						'value' => true
					)
				));
			}
			
		} else if ( $typenow == 'wproto_benefits' ) {
				
			if( isset( $_GET['filter_by_category'] ) && $_GET['filter_by_category'] > 0 ) {
				$query->set( 'tax_query', array(
					array(
						'taxonomy' => 'wproto_benefits_category',
						'terms' => $_GET['filter_by_category'] 
					)
				));
			}
				
		} else if ( $typenow == 'wproto_portfolio' ) {
			
			if( isset( $_GET['featured'] ) && $_GET['featured'] ) {
				$query->set( 'meta_query', array(
					array(
						'key' => 'featured',
						'value' => true
					)
				));
			}
			
			if( isset( $_GET['filter_by_category'] ) && $_GET['filter_by_category'] > 0 ) {
				$query->set( 'tax_query', array(
					array(
						'taxonomy' => 'wproto_portfolio_category',
						'terms' => $_GET['filter_by_category'] 
					)
				));
			}
				
		} else if ( $typenow == 'wproto_team' ) {
			if( isset( $_GET['filter_by_category'] ) && $_GET['filter_by_category'] > 0 ) {
				$query->set( 'tax_query', array(
					array(
						'taxonomy' => 'wproto_team_category',
						'terms' => $_GET['filter_by_category'] 
					)
				));
			}
		} else if ( $typenow == 'wproto_testimonials' ) {
			if( isset( $_GET['filter_by_category'] ) && $_GET['filter_by_category'] > 0 ) {
				$query->set( 'tax_query', array(
					array(
						'taxonomy' => 'wproto_testimonials_category',
						'terms' => $_GET['filter_by_category'] 
					)
				));
			}
		} 
		
	}
	
	/**
	 * Add JS Scripts
	 **/
	function add_scripts() {
		global $post, $wpl_exe_wp;
		
		$post_type = get_post_type( $post );
		
		if( $post_type == 'wproto_benefits' ) {
			wp_register_script( 'wproto-benefits', WPROTO_THEME_URL . '/js/admin/screen-benefits.js', false, $wpl_exe_wp->settings['res_cache_time'] );
			wp_enqueue_script( 'wproto-benefits', array( 'jquery' ) );
		}
		
		if( $post_type == 'wproto_pricing_table' ) {
			wp_register_script( 'wproto-pricing-tables-screen', WPROTO_THEME_URL . '/js/admin/screen-pricing-tables.js', false, $wpl_exe_wp->settings['res_cache_time'] );
			wp_enqueue_script( 'wproto-pricing-tables-screen', array( 'jquery' ) );
		}
		
		wp_register_script( 'wproto-template-editor', WPROTO_THEME_URL . '/js/admin/template-editor.js', false, $wpl_exe_wp->settings['res_cache_time'] );
		wp_enqueue_script( 'wproto-template-editor', array( 'jquery' ) );
		
	}
	
	/**
	 * Featured post metabox
	 **/
	function render_meta_box_post_featured() {
		global $post, $wpl_exe_wp;
		$data = array();
		$data['featured'] = get_post_meta( $post->ID, 'featured', true );
		
		$wpl_exe_wp->view->load_partial( 'metaboxes/post_featured', $data );
	}
	
	/**
	 * Page redirect metabox
	 **/
	function render_redirect_metabox( $post ) {
		global $wpl_exe_wp;
		$data = array();
		
		$data = wpl_exe_wp_utils::get_post_custom( $post->ID );

		$data['post_type'] = get_post_type( $post->ID ) == 'page' ? __('page', 'wproto') : __('post', 'wproto');
		$data['post_id'] = $post->ID;
		
		$wpl_exe_wp->view->load_partial( 'metaboxes/page_redirect', $data );
	}
	
	/**
	 * Header settings for post / page etc.
	 **/
	function render_metabox_header_settings( $post ) {
		global $wpl_exe_wp;
		$data = wpl_exe_wp_utils::get_post_custom( $post->ID );
		$data['post_id'] = $post->ID;
		$data['post_type'] = get_post_type( $post->ID );
		$wpl_exe_wp->view->load_partial( 'layout_editor/metabox_header', $data );
	}
	
	/**
	 * Appearance settings for post / page etc.
	 **/
	function render_metabox_appearance_settings( $post ) {
		global $wpl_exe_wp;
		$data = wpl_exe_wp_utils::get_post_custom( $post->ID );
		$data['post_id'] = $post->ID;
		$data['post_type'] = get_post_type( $post->ID );
		$wpl_exe_wp->view->load_partial( 'layout_editor/metabox_appearance', $data );
	}
	
	/**
	 * Sidebar settings metabox
	 **/
	function render_metabox_sidebar_options( $post ) {
		global $wp_registered_sidebars, $wpl_exe_wp;
		$data = array();
		
		$data = wpl_exe_wp_utils::get_post_custom( $post->ID );
		
		$data['registered_sidebars'] = $wp_registered_sidebars;
		$data['post_id'] = $post->ID;
		
		$wpl_exe_wp->view->load_partial( 'layout_editor/metabox_sidebar', $data );

	}
	
	/**
	 * Footer settings for post / page etc.
	 **/
	function render_metabox_footer_settings( $post ) {
		global $wp_registered_sidebars, $wpl_exe_wp;
		$data = wpl_exe_wp_utils::get_post_custom( $post->ID );
		$data['post_id'] = $post->ID;
		$data['registered_sidebars'] = $wp_registered_sidebars;
		$data['post_type'] = get_post_type( $post->ID );
		$wpl_exe_wp->view->load_partial( 'layout_editor/metabox_footer', $data );
	}
	
	/**
	 * "Attached images" metabox
	 **/
	function render_meta_box_post_attached_images() {
		global $post, $wpl_exe_wp;
		$data = array();
		$data['images'] = get_post_meta( $post->ID, 'wproto_attached_images', true );
		$wpl_exe_wp->view->load_partial( 'metaboxes/attached_images', $data );
	}
	
	/**
	 * Portfolio additional information
	 **/
	function render_meta_box_portfolio_info() {
		global $post, $wpl_exe_wp;
		$data = array();
		$data = wpl_exe_wp_utils::get_post_custom( $post->ID );
		$wpl_exe_wp->view->load_partial( 'metaboxes/portfolio_information', $data );
	}
	
	/**
	 * Custom fonts settings metabox
	 **/
	function render_meta_box_custom_fonts_settings() {
		global $post, $wpl_exe_wp;
		$data = array();
		$data['font_family'] = get_post_meta( $post->ID, 'font_family', true );
		$data['file_eot'] = get_post_meta( $post->ID, 'file_eot', true );
		$data['file_woff2'] = get_post_meta( $post->ID, 'file_woff2', true );
		$data['file_woff'] = get_post_meta( $post->ID, 'file_woff', true );
		$data['file_truetype'] = get_post_meta( $post->ID, 'file_truetype', true );
		$data['file_svg'] = get_post_meta( $post->ID, 'file_svg', true );
		
		$wpl_exe_wp->view->load_partial( 'metaboxes/custom_font_settings', $data );
	}
	
	/**
	 * Render "Benefit type" metabox
	 **/
	function render_meta_box_benefit_type() {
		global $post, $wpl_exe_wp;
		$data = array();
		$data['link'] = get_post_meta( $post->ID, 'wproto_benefit_link', true );
		$data['style'] = get_post_meta( $post->ID, 'wproto_benefit_style', true );
		$data['icon'] = get_post_meta( $post->ID, 'wproto_benefit_icon_name', true );
		$data['animation'] = get_post_meta( $post->ID, 'wproto_benefit_animation', true );
		$data['animation_delay'] = get_post_meta( $post->ID, 'wproto_benefit_animation_delay', true );
		
		$data['svg_image'] = get_post_meta( $post->ID, 'wproto_benefit_svg_image', true );
		$data['svg_image_width'] = get_post_meta( $post->ID, 'wproto_benefit_svg_image_width', true );
		$data['svg_image_height'] = get_post_meta( $post->ID, 'wproto_benefit_svg_image_height', true );
		
		$wpl_exe_wp->view->load_partial( 'metaboxes/benefits_style', $data );
	}
	
	/**
	 * Team - connections metabox
	 **/
	function team_render_meta_box_connections() {
		global $post, $wpl_exe_wp;
		$data = array();
		$data['blog_authors'] = get_users( 'orderby=nicename&who=authors' );
		$data['connect_with_wp_author'] = get_post_meta( $post->ID, 'connect_with_wp_author', true );
		$data['author_id'] = get_post_meta( $post->ID, 'author_id', true );
		
		$wpl_exe_wp->view->load_partial( 'metaboxes/team_member_connections', $data );
	}
	
	/**
	 * Team - public information metabox
	 **/
	function team_render_meta_box_public_info() {
		global $post, $wpl_exe_wp;
		$data = array();
		//$data['age'] = get_post_meta( $post->ID, 'age', true );
		$data['post_id'] = $post->ID;
		$data['icon'] = get_post_meta( $post->ID, 'icon', true );
		$data['custom_url'] = get_post_meta( $post->ID, 'custom_url', true );
		$data['position'] = get_post_meta( $post->ID, 'position', true );
		$data['experience'] = get_post_meta( $post->ID, 'experience', true );
		$data['connect_with_wp_author'] = get_post_meta( $post->ID, 'connect_with_wp_author', true );
		
		$wpl_exe_wp->view->load_partial( 'metaboxes/team_member', $data );
	}
	
	/**
	 * Testimonials - additional info metabox
	 **/
	function testimonials_render_meta_box_additional_info() {
		global $post, $wpl_exe_wp;
		$data = array();
		$data['position'] = get_post_meta( $post->ID, 'position', true );
		$data['url'] = get_post_meta( $post->ID, 'url', true );
		
		$wpl_exe_wp->view->load_partial( 'metaboxes/testimonials', $data );
	}
	
	/**
	 * Partners / Clients additional information
	 **/
	function partners_clients_render_metabox_info() {
		global $post, $wpl_exe_wp;
		$data = array();
		$data['url'] = get_post_meta( $post->ID, 'url', true );
		$wpl_exe_wp->view->load_partial( 'metaboxes/partners_clients', $data );
	}
	
	/**
	 * Pricing table editor
	 **/
	function render_meta_box_pricing_table_editor() {
		global $post, $wpl_exe_wp;
		$data = array();
		$data['pricing_table'] = get_post_meta( $post->ID, 'pricing_table', true );
		
		$wpl_exe_wp->view->load_partial( 'metaboxes/pricing_table_editor', $data );
	}
	
}