<?php
/**
 * Shortcodes
 **/
class wpl_exe_wp_front_shortcodes_controller extends wpl_exe_wp_core_controller {
	
	function __construct() {
		
		// replace default gallery shortcode
		remove_shortcode( 'gallery' );
		add_shortcode( 'gallery', array( $this, 'gallery') );
		add_shortcode( 'wproto_heading', array( $this, 'wproto_heading') );
		add_shortcode( 'wproto_dropcap', array( $this, 'wproto_dropcap') );
		add_shortcode( 'wproto_quote', array( $this, 'wproto_quote') );
		add_shortcode( 'wproto_image', array( $this, 'wproto_image') );
		add_shortcode( 'wproto_button', array( $this, 'wproto_button') );
		add_shortcode( 'wproto_iconic_list_container', array( $this, 'wproto_iconic_list_container') );
		add_shortcode( 'wproto_iconic_list_elem', array( $this, 'wproto_iconic_list_elem') );
		add_shortcode( 'wproto_message_box', array( $this, 'wproto_message_box') );
		add_shortcode( 'wproto_call_to_action', array( $this, 'wproto_call_to_action') );
		add_shortcode( 'wproto_progress', array( $this, 'wproto_progress') );
		add_shortcode( 'wproto_testimonials', array( $this, 'wproto_testimonials') );
		add_shortcode( 'wproto_benefits', array( $this, 'wproto_benefits') );
		add_shortcode( 'wproto_partners_clients', array( $this, 'wproto_partners_clients') );
		add_shortcode( 'wproto_brands', array( $this, 'wproto_brands') );
		add_shortcode( 'wproto_pricing_tables', array( $this, 'wproto_pricing_tables') );
		add_shortcode( 'wproto_contact_information', array( $this, 'wproto_contact_information') );
		add_shortcode( 'wproto_tweets', array( $this, 'wproto_tweets') );
		add_shortcode( 'wproto_login_signup', array( $this, 'wproto_login_signup') );
		add_shortcode( 'wproto_facts_in_digits', array( $this, 'wproto_facts_in_digits') );
		add_shortcode( 'wproto_staff', array( $this, 'wproto_staff') );
		add_shortcode( 'wproto_google_map', array( $this, 'wproto_google_map') );
		add_shortcode( 'wproto_ninja_form', array( $this, 'wproto_ninja_form') );		
		add_shortcode( 'wproto_posts_carousel', array( $this, 'wproto_posts_carousel') );
		add_shortcode( 'wproto_blog', array( $this, 'wproto_blog') );
		add_shortcode( 'wproto_portfolio', array( $this, 'wproto_portfolio') );
		add_shortcode( 'wproto_banner', array( $this, 'wproto_banner') );
		add_shortcode( 'wproto_product_carousel', array( $this, 'wproto_product_carousel') );
		add_shortcode( 'wproto_shop_product', array( $this, 'wproto_shop_product') );
		add_shortcode( 'wproto_shop_product_list', array( $this, 'wproto_shop_product_list') );
		add_shortcode( 'wproto_intro_section', array( $this, 'wproto_intro_section') );		
	}
	
	/**
	 * Gallery shortcode
	 **/
	function gallery( $params ) {
		global $post, $wpl_exe_wp;
		
		$ids_str = isset( $params['ids'] ) && $params['ids'] <> '' ? $params['ids'] : '';
		
		$ids = array();
		if( $ids_str <> '' ) $ids = explode( ',', $ids_str );
		
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null
		); 
			
		if( is_array( $ids ) && count( $ids ) > 0 ) {
			$args['include'] = $ids;
		} else {
			$args['post_parent'] = $post->ID;
		}
		
		$data['items'] = get_posts( $args );
		
		if( count( $data['items'] ) > 0 && is_array( $data['items'] ) ) {
			
			ob_start();
			$wpl_exe_wp->view->load_partial( 'shortcodes/gallery', $data );
			return ob_get_clean();
			
			
		}
		
	}
	
	/**
	 * Heading shortcode
	 **/
	function wproto_heading( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'text'								=> '',
			'subtext'							=> '',
			'tag'									=> 'h1',
			'style'								=> 'default',
			'color' 							=> '',
			'subtext_color' 			=> '',
			'align'								=> '',
			'transform'						=> '',
			'font_style'					=> '',
			'weight'							=> '',
			'header_line'					=> false,
			'header_line_accent'	=> false
 		), $params );
 		
 		if( !isset( $data['tag'] ) || $data['tag'] == '' ) {
 			$data['tag'] = 'h2';
 		}
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/heading', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Dropcap shortcode
	 **/
	function wproto_dropcap( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'text'							=> '',
			'style'							=> 'style_1',
 		), $params );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/dropcap', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Image shortcode
	 **/
	function wproto_image( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'image'						=> '',
			'image_size'			=> '',
			'link_type'				=> '',
			'custom_link'			=> '',
			'link_target'			=> '_self',
			'image_align'			=> 'alignnone',
			'animation'				=> '',
			'animation_delay'	=> ''
 		), $params );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/image', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Quote shortcode
	 **/
	function wproto_quote( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'text'												=> '',
			'author'											=> '',
			'add_text'										=> '',
			'style'												=> 'style_1',
			'text_color'									=> '',
			'author_color'								=> '',
			'additional_text_color'				=> ''
 		), $params );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/quote', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Button shortcode
	 **/
	function wproto_button( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'link'								=> '',
			'title'								=> __('My button title', 'wproto' ),
			'icon'								=> '',
			'size'								=> 'medium',
			'style'								=> 'red',
			'border_radius'				=> '',
			'icon_radius'					=> '',
 		), $params );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/button', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Iconic list container shortcode
	 **/
	function wproto_iconic_list_container( $params, $content ) {
		global $_wpl_iconic_list_num;
		
		$_wpl_iconic_list_num = 0;
		
		$data = shortcode_atts( array(
			'el_class'					=> '',
 		), $params );	
 		
 		$output = '<ul class="wproto-iconic-list-shortcode ' . $data['el_class'] . '">';
 		
 		$output .= wpb_js_remove_wpautop( $content ) . '</ul>';
 		
 		return $output;
	}
	
	/**
	 * Iconic list element shortcode
	 **/
	function wproto_iconic_list_elem( $params ) {
		global $wpl_exe_wp, $_wpl_iconic_list_num;
		
		$_wpl_iconic_list_num++;
		
		$data = shortcode_atts( array(
			'el_class'							=> '',
			'title'									=> '',
			'icon_type'							=> '',
			'icon'									=> '',
			'text_color'						=> '',
			'icon_color' 						=> '',
			'icon_background_color' => '',
			'icon_border_color' 		=> '',
			'border_radius' 				=> '',
			'num'										=> $_wpl_iconic_list_num
 		), $params );	
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/iconic_list_element', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Message box
	 **/
	function wproto_message_box( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'text'						=> '',
			'type'						=> 'error',
			'style'						=> 'default',
			'icon'						=> '',
			'animation'				=> '',
			'animation_delay'	=> '',
			'text_color' 			=> '',
			'icon_color' 			=> '',
			'border_color' 		=> '',
			'bg_color' 				=> '',
			'icon_bg_color' 	=> ''
 		), $params );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/message_box', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Call to action
	 **/
	function wproto_call_to_action( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'header_text'				=> '',
			'text'							=> '',
			'display_triangle'	=> false,
			'style'							=> 'no-buttons',
			'button_text'				=> '',
			'button_link'				=> '',
			'button2_text' 			=> '',
			'button2_link' 			=> '',
			'animation' 				=> '',
			'animation_delay' 	=> '',
			'text_color' 				=> '',
			'bg_color' 					=> ''
 		), $params );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/call_to_action', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Progress bars shortcode
	 **/
	function wproto_progress( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'values'				=> "90|Development\r\n80|Design\r\n70|Marketing",
			'unit'					=> '%',
			'style'					=> 'rounded_filled',
 		), $params );
 		
		$progress_elements = preg_split("/\\r\\n|\\r|\\n/", $data['values'] );
		
		$data['progress_items'] = array();
		
		if( is_array( $progress_elements ) && count( $progress_elements ) > 0 ) {
			$i=0; foreach( $progress_elements as $k=>$v ) {
				
				$item = explode('|', $v );
				
				if( isset( $item[1] ) && $item[1] <> '' ) {
				
					$data['progress_items'][ $i ]['value'] = isset( $item[0] ) ? absint( $item[0] ) : 0;
					$data['progress_items'][ $i ]['title'] = isset( $item[1] ) ? $item[1] : '';
					
					$i++;
					
				}

			}
		}
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/progress_bars', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Testimonials shortcode
	 **/
	function wproto_testimonials( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'title'									=> '',
			'count'									=> 5,
			'category'							=> '',
			'order_by'							=> 'ID',
			'sort'									=> 'ASC',
			'autoplay'							=> false,
			'autoplay_speed' 				=> 5000,
			'style'									=> 'boxed',
 		), $params );
 		
 		$type = $data['category'] <> '' ? 'category' : '';
 		$data['posts'] = $this->model('post')->get( $type, $data['count'], $data['category'], $data['order_by'], $data['sort'], 'wproto_testimonials', 'wproto_testimonials_category', false, false, false, 0, 'slug' );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/testimonials', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Benefits shortcode
	 **/
	function wproto_benefits( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'count'				=> 4,
			'category'		=> '',
			'order_by'		=> 'ID',
			'sort'				=> 'ASC',
			'style'				=> 'rounded',
			'columns'			=> 'col-md-3',
			'target'			=> '_self',
			'min_height'	=> ''
 		), $params );
 		
 		$type = $data['category'] <> '' ? 'category' : '';
 		$data['posts'] = $wpl_exe_wp->model('post')->get( $type, $data['count'], $data['category'], $data['order_by'], $data['sort'], 'wproto_benefits', 'wproto_benefits_category', false, false, false, 1, 'slug' );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/benefits', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Partners / Clients logos carousel
	 **/
	function wproto_partners_clients( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
    	'count' 					=> 5,
    	'items'						=> 1,
    	'categories' 			=> '',
    	'order_by' 				=> 'ID', 
    	'sort'						=> 'ASC',
    	'nav'							=> true,
    	'autoplay'				=> false,
    	'autoplay_speed'	=> 5000,
    	'target'					=> '_blank',
    	'display_title'		=> false
 		), $params );
 		
 		$type = $data['categories'] <> '' ? 'category' : '';
 		$data['posts'] = $wpl_exe_wp->model('post')->get( $type, $data['count'], $data['categories'], $data['order_by'], $data['sort'], 'wproto_partners', 'wproto_partners_category', false, false, false, 1, 'slug' );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/partners_clients', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Brands carousel
	 **/
	function wproto_brands( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
    	'items'						=> 5,
    	'hide_empty' 			=> true,
    	'order_by' 				=> 'id', 
    	'sort'						=> 'ASC',
    	'nav'							=> true,
    	'autoplay'				=> false,
    	'autoplay_speed'	=> 5000
 		), $params );
 		
		$data['terms'] = get_terms( 'product_brand', array(
			'orderby' => $data['order_by'],
			'order' => $data['sort'],
			'hide_empty' => $data['hide_empty']
		));
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/brands', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Pricing tables shortcode
	 **/
	function wproto_pricing_tables( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'table_id'	=> 0,
			'style'			=> 'style_1',
			'columns'		=> 'col-md-3',
 		), $params );
 		
 		$data['table_id'] = absint( $data['table_id'] );
 		
 		if( $data['table_id'] > 0 ) {

			$data['pricing_table'] = get_post_meta( $data['table_id'], 'pricing_table', true );
			
	 		// shortcode output
	 		ob_start();
			
			$wpl_exe_wp->view->load_partial( 'shortcodes/pricing_tables', $data );
	 			
	 		return ob_get_clean();

		}
 		
	}
	
	/**
	 * Contact information shortcode
	 **/
	function wproto_contact_information( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'title'									=> '',
			'subtext'								=> '',
			'address'								=> '',
			'phone'									=> '',
			'email'									=> '',
			'display_social_icons'	=> false,
			'style'									=> ''
 		), $params );
 		
 		// shortcode output
 		ob_start();
		
		if( $data['style'] == 'modern' ) {
			$wpl_exe_wp->view->load_partial( 'shortcodes/contact_information_modern', $data );
		} else {
			$wpl_exe_wp->view->load_partial( 'shortcodes/contact_information', $data );
		}
 			
 		return ob_get_clean();
	}
	
	/**
	 * Login register signup shortcode
	 **/
	function wproto_login_signup( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'style'	=> 'style_1',
			'allow_register' => false
 		), $params );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/login_signup', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Facts in digits shortcode
	 **/
	function wproto_facts_in_digits( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'title'						=> '',
			'count'						=> '',
			'unit'						=> '',
			'description' 		=> '',
			'icon' 						=> '',
			'style' 					=> 'style_1',
			'color'						=> '#e5493a',
			'grad_color'			=> '#8cc640',
			'grad_color_alt'	=> '#1cbbb2',
			'title_color'			=> '',
			'icon_color'			=> '',
			'text_color'			=> ''
 		), $params );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/facts_in_digits', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Stuff, our team shortcode
	 **/
	function wproto_staff( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'count'						=> 4,
			'category' 				=> '',
			'order_by' 				=> 'ID',
			'sort' 						=> 'ASC',
			'style' 					=> 'grid',
			'columns' 				=> 'col-md-3',
			'items' 					=> 4,
			'carousel_title' 	=> '',
			'autoplay_speed'	=> 5000,
			'autoplay'				=> false,
			'nav'							=> 'false',
 		), $params );
 		
 		$type = $data['category'] <> '' ? 'category' : '';
 		$data['posts'] = $wpl_exe_wp->model('post')->get( $type, $data['count'], $data['category'], $data['order_by'], $data['sort'], 'wproto_team', 'wproto_team_category', false, false, false, 1, 'slug' );
 		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/staff', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * GoogleMap shortcode
	 **/
	function wproto_google_map( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
    	'address'						=> '',
    	'height'						=> 35,
    	'min_height'				=> '',
    	'pointer_image'			=> '',
    	'map_zoom'					=> 1,
    	'draggable'					=> false,
    	'map_mode'					=> 'ROADMAP'
 		), $params );
		
 		// shortcode output
 		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'shortcodes/google_map', $data );
 			
 		return ob_get_clean();
	}
	
	/**
	 * Latest tweets
	 **/
	function wproto_tweets( $params ) {
		global $wpl_exe_wp;
 		
		$data = shortcode_atts( array(
			'count'						=> 5,
			'autoplay'				=> false,
			'autoplay_speed'	=> 1800,
			'style'						=> 'default',
 		), $params );
 		
		// shortcode output
		ob_start();
	
		$wpl_exe_wp->view->load_partial( 'shortcodes/tweets', $data );
		
		return ob_get_clean();
 		
	}
	
	/**
	 * NinjaForms picker
	 **/
	function wproto_ninja_form( $params ) {
		
		$data = shortcode_atts( array(
			'id'						=> '',
			'form_style'		=> 'style_1',
 		), $params );
 		
 		return '<div class="wproto-ninja-form style-' . $data['form_style'] . '">' . do_shortcode('[ninja_form id="' . $data['id'] . '"]') . '</div>';
 		
	}
	
	/**
	 * Posts carousel shortcode
	 **/
	function wproto_posts_carousel( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'style' => 'style_1',
			'read_more_text' => __('Read more', 'wproto'),
			'block_title' => __('Breaking news', 'wproto'),
			'display_filters' => false,
			'display_nav' => false,
			'post_type' => 'post',
			'posts_limit' => 10,
			'author_id' => '',
			'order_by' => 'ID',
			'sort_by' => 'ASC',
			'featured' => false,
			'cat_query_type' => '',
			'categories' => ''
 		), $params );
 		
 		$post_type_categories = $data['post_type'] == 'wproto_portfolio' ? 'wproto_portfolio_category' : 'category';
 		
 		$data['posts'] = $this->model('post')->get( $data['cat_query_type'], $data['posts_limit'], $data['categories'], $data['order_by'], $data['sort_by'], $data['post_type'], $post_type_categories, $data['featured'], false, true, 0, 'slug' );
 		
		// shortcode output
		ob_start();
		
			$wpl_exe_wp->view->load_partial( 'shortcodes/posts_carousel', $data );
		
		wp_reset_postdata();
		
		return ob_get_clean();
 		
	}
	
	/**
	 * Blog posts shortcode
	 **/
	function wproto_blog( $params ) {
		global $wpl_exe_wp, $wp_query;
		
		$data = shortcode_atts( array(
			'use_wp_query' => false,
			'posts_per_page' => 10,
			'author_id' => '',
			'order_by' => 'ID',
			'sort_by' => 'ASC',
			'featured' => false,
			'cat_query_type' => '',
			'categories' => '',
			'style' => 'cols_1_default',
			'read_more_text' => __('Read more', 'wproto'),
			'display_pagination' => false,
			'pagination_style' => 'numeric',
			'ajax_load' => false,
			'paged' => false
 		), $params );
 		
 		set_query_var( 'wproto_shortcode_params', $data );
 		
 		if( $data['use_wp_query'] ) {
 			$data['posts'] = $wp_query;
 		} else {
 			$paged = ! $data['paged'] ? get_query_var('paged') : $data['paged'];
 			$data['posts'] = $this->model('post')->get( $data['cat_query_type'], absint( $data['posts_per_page'] ), $data['categories'], $data['order_by'], $data['sort_by'], 'post', 'category', $data['featured'], false, false, $paged, 'slug' );
 		}
 		
		// shortcode output
		ob_start();
	
		switch( $data['style'] ) {
			default:
			case 'cols_1_default':
			case 'cols_1_centered':
			case 'cols_1_centered_no_bg':
			case 'cols_1_alt':
				$wpl_exe_wp->view->load_partial( 'shortcodes/blog/1_col', $data );
			break;
			case 'cols_2':
			case 'cols_2_alt':
				$wpl_exe_wp->view->load_partial( 'shortcodes/blog/2_cols', $data );
			break;
			case 'cols_3':
				$wpl_exe_wp->view->load_partial( 'shortcodes/blog/3_cols', $data );
			break;
			case 'cols_4':
				$wpl_exe_wp->view->load_partial( 'shortcodes/blog/4_cols', $data );
			break;
			case 'cols_2_masonry':
			case 'cols_2_masonry_alt':
			case 'cols_3_masonry':
			case 'cols_4_masonry':
				$wpl_exe_wp->view->load_partial( 'shortcodes/blog/masonry', $data );
			break;
		}
		
		set_query_var( 'wproto_shortcode_params', null );
		wp_reset_postdata();
		
		return ob_get_clean();
 		
	}
	
	/**
	 * Portfolio posts shortcode
	 **/
	function wproto_portfolio( $params ) {
		global $wpl_exe_wp, $wp_query;
		
		$data = shortcode_atts( array(
			'use_wp_query' => false,
			'posts_per_page' => 10,
			'author_id' => '',
			'order_by' => 'ID',
			'sort_by' => 'ASC',
			'featured' => false,
			'cat_query_type' => '',
			'categories' => '',
			'style' => 'cols_1_default',
			'read_more_text' => __('Read more', 'wproto'),
			'display_pagination' => false,
			'pagination_style' => 'numeric',
			'ajax_load' => false,
			'paged' => false,
			'display_filters' => false,
			'display_sort_filters' => false,
			'display_view_switcher' => false,
			'block_title' => ''
 		), $params );
 		
 		set_query_var( 'wproto_shortcode_params', $data );
 		
 		if( $data['use_wp_query'] ) {
 			$data['posts'] = $wp_query;
 		} else {
 			$paged = ! $data['paged'] ? get_query_var('paged') : $data['paged'];
 			$data['posts'] = $this->model('post')->get( $data['cat_query_type'], absint( $data['posts_per_page'] ), $data['categories'], $data['order_by'], $data['sort_by'], 'wproto_portfolio', 'wproto_portfolio_category', $data['featured'], false, false, $paged, 'slug' );
 		}
 		
		// shortcode output
		ob_start();
	
		switch( $data['style'] ) {
			default:
			case 'cols_1_default':
			case 'cols_1_alt':
				$wpl_exe_wp->view->load_partial( 'shortcodes/portfolio/1_col', $data );
			break;
			case 'cols_2_masonry':
			case 'cols_2_masonry_no_gap':
			case 'cols_2_masonry_with_desc':
			case 'cols_3_masonry':
			case 'cols_3_masonry_no_gap':
			case 'cols_3_masonry_with_desc':
			case 'cols_4_masonry':
			case 'cols_4_masonry_no_gap':
			case 'cols_4_masonry_with_desc':
				$wpl_exe_wp->view->load_partial( 'shortcodes/portfolio/masonry', $data );
			break;
			case 'full_width':
			case 'full_width_alt':
			case 'full_width_third':
				$wpl_exe_wp->view->load_partial( 'shortcodes/portfolio/full_width', $data );
			break;
		}
		
		set_query_var( 'wproto_shortcode_params', null );
		wp_reset_postdata();
		
		return ob_get_clean();
	}
	
	/**
	 * Intro section
	 **/
	function wproto_intro_section( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'post_type' => 'post',
			'author_id' => '',
			'order_by' => 'ID',
			'sort_by' => 'ASC',
			'featured' => false,
			'cat_query_type' => '',
			'categories' => '',
			'title' => __('Latest Blog Posts', 'wproto'),
			'read_more_text' => __('Read more &raquo;', 'wproto'),
			'display_read_all' => false,
			'read_all_text' => ''
 		), $params );
 		
 		$post_type_categories = $data['post_type'] == 'wproto_portfolio' ? 'wproto_portfolio_category' : 'category';
 		
 		$data['posts'] = $this->model('post')->get( $data['cat_query_type'], 3, $data['categories'], $data['order_by'], $data['sort_by'], $data['post_type'], $post_type_categories, $data['featured'], false, true, 0, 'slug' );
 		
		// shortcode output
		ob_start();
	
		$wpl_exe_wp->view->load_partial( 'shortcodes/intro_section', $data );
		
		wp_reset_postdata();
		
		return ob_get_clean();
 		
	}
	
	/**
	 * Banner
	 **/
	function wproto_banner( $params ) {
		global $wpl_exe_wp;
		
		$data = shortcode_atts( array(
			'link' => '',
			'type' => 'text',
			'image' => '',
			'header_text' => '',
			'description_text' => '',
			'read_more_text' => '',
			'border' => false,
			'text_align' => 'left',
			'header_color' => '',
			'description_color' => '',
			'read_more_color' => '',
			'border_color' => '',
			'accent_background_color' => '',
			'accent_text_color' => '',
			'padding_top' => '',
			'padding_right' => '',
			'padding_bottom' => '',
			'padding_left' => '',
			'animation' => '',
			'animation_delay' => ''
 		), $params );
 		
		// shortcode output
		ob_start();
	
		$wpl_exe_wp->view->load_partial( 'shortcodes/banner', $data );
		
		return ob_get_clean();
 		
	}
	
	/**
	 * Products carousel
	 **/
	function wproto_product_carousel( $params ) {
		global $wpl_exe_wp;
		
		if( wpl_exe_wp_utils::isset_woocommerce() ) {
			
			$data = shortcode_atts( array(
				'type' => 'featured',
				'style' => 'style_1',
				'display_nav_arrows' => false,
				'display_nav_bullets' => false,
				'count' => 8,
				'visible_desktop' => 4,
				'visible_small_desktop' => 3,
				'visible_phone_landscape' => 2,
				'visible_phone' => 1,
	 		), $params );
	 		
	 		switch( $data['type'] ) {
	 			default:
	 			case('featured'):
	 				$data['posts'] = $this->model('post')->get_featured_products( $data['count'] );
	 			break;
	 			case('onsale'):
	 				$data['posts'] = $this->model('post')->get_onsale_products( $data['count'], true );
	 			break;
	 			case('latest'):
	 				$data['posts'] = $this->model('post')->get_recent_posts( 'product', $data['count'] );
	 			break;
	 			case('best_sellers'):
	 				$data['posts'] = $this->model('post')->get_best_sellers( $data['count'] );
	 			break;
	 			case('top_rated'):
	 				$data['posts'] = $this->model('post')->get_top_rated_products( $data['count'], true );
	 			break;
	 			case('random'):
	 				$data['posts'] = $this->model('post')->get_random_posts( 'product', $data['count'], true );
	 			break;
	 		}
	 		
			// shortcode output
			ob_start();
		
			$wpl_exe_wp->view->load_partial( 'shortcodes/product_carousel', $data );
			
			wp_reset_postdata();
			
			return ob_get_clean();
			
		}
		
	}
	
	/**
	 * Shop products
	 **/
	function wproto_shop_product( $params ) {
		global $wpl_exe_wp, $wp_query;
		
		if( wpl_exe_wp_utils::isset_woocommerce() ) {
			$data = shortcode_atts( array(
				'use_wp_query' => false,
				'posts_per_page' => 9,
				'order_by' => 'ID',
				'sort_by' => 'ASC',
				'cat_query_type' => '',
				'categories' => '',
				'style' => 'col_1_style_1',
				'display_pagination' => false,
				'pagination_style' => 'numeric',
				'view_switcher' => false,
				'display_filters' => false,
				'ajax_load' => false,
				'paged' => false,
				'author_id' => '',
				'featured' => false,
				'read_more_text' => ''
	 		), $params );
 		
 		$data['uniqid'] = uniqid();
 		
 		set_query_var( 'wproto_shortcode_params', $data );
 		
 		if( $data['use_wp_query'] ) {
 			$data['posts'] = $wp_query;
 		} else {
 			$paged = ! $data['paged'] ? get_query_var('paged') : $data['paged'];
 			$data['posts'] = $this->model('post')->get( $data['cat_query_type'], absint( $data['posts_per_page'] ), $data['categories'], $data['order_by'], $data['sort_by'], 'product', 'product_cat', false, false, false, $paged, 'slug' );
 		}
 		
		// shortcode output
		ob_start();
	
		$wpl_exe_wp->view->load_partial( 'shortcodes/shop/subcats', $data );
	
		switch( $data['style'] ) {
			default:
			case 'col_1_style_1':
			case 'col_1_style_2':
			case 'col_1_style_3':
				$wpl_exe_wp->view->load_partial( 'shortcodes/shop/1_col', $data );
			break;
			case 'cols_2_style_1':
			case 'cols_2_style_2':
			case 'cols_2_style_3':
				$wpl_exe_wp->view->load_partial( 'shortcodes/shop/2_cols', $data );
			break;
			case 'cols_3_style_1':
			case 'cols_3_style_2':
			case 'cols_3_style_3':
				$wpl_exe_wp->view->load_partial( 'shortcodes/shop/3_cols', $data );
			break;
			case 'cols_4_style_1':
			case 'cols_4_style_2':
			case 'cols_4_style_3':
				$wpl_exe_wp->view->load_partial( 'shortcodes/shop/4_cols', $data );
			break;
		}
		
		set_query_var( 'wproto_shortcode_params', null );
		wp_reset_postdata();
		
		return ob_get_clean();
	 		
		}
		
	}
	
	/**
	 * Shop products list
	 **/
	function wproto_shop_product_list( $params ) {
		global $wpl_exe_wp;
		
		if( wpl_exe_wp_utils::isset_woocommerce() ) {
			$data = shortcode_atts( array(
				'type' => 'latest',
				'count' => 3,
				'title' => __('New Incomes', 'wproto'),
	 		), $params );
	 		
	 		switch( $data['type'] ) {
	 			default:
	 			case('latest'):
	 				$data['posts'] = $this->model('post')->get_recent_posts( 'product', $data['count'] );
	 			break;
	 			case('sales'):
	 				$data['posts'] = $this->model('post')->get_onsale_products( $data['count'], true );
	 			break;
	 			case('top_rated'):
	 				$data['posts'] = $this->model('post')->get_top_rated_products( $data['count'], true );
	 			break;
	 			case('random'):
	 				$data['posts'] = $this->model('post')->get_random_posts( 'product', $data['count'], true );
	 			break;
	 		}
	 		
			// shortcode output
			ob_start();
		
			$wpl_exe_wp->view->load_partial( 'shortcodes/product_list', $data );
			
			wp_reset_postdata();
			
			return ob_get_clean();
		}
		
	}
		
}