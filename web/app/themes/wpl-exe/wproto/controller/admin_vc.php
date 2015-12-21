<?php
/**
 * Visual Composer shortcodes controller
 **/
class wpl_exe_wp_admin_vc_controller extends wpl_exe_wp_core_controller {
	
	public $animations_list = array();
	
	function __construct() {
		global $wpl_exe_wp;
		$this->animations_list = $wpl_exe_wp->system_config['animations'];
		$this->blog_styles = $wpl_exe_wp->system_config['blog_styles'];
		$this->portfolio_styles = $wpl_exe_wp->system_config['portfolio_styles'];
		$this->pagination_styles = $wpl_exe_wp->system_config['pagination_styles'];
		$this->shop_styles = $wpl_exe_wp->system_config['shop_styles'];
	
		/**
	 		Add custom Visual Composer widgets
	 	**/
 		if( function_exists( 'vc_map' ) ) {
			// init VisualComposer
			add_action( 'init', array( $this, 'init_vc' ) );
 			add_action( 'vc_before_init', array( $this, 'add_remove_vc_params' ));
 			add_action( 'vc_before_init', array( $this, 'add_vc_items' ));
 			add_action( 'init', array( $this, 'setup_vc' ) );
		}
		
		/**
		 * Add VC custom params
		 **/
	 	if( function_exists( 'vc_add_shortcode_param' ) ) {
	 		vc_add_shortcode_param( 'wproto_icon_picker', array( $this, 'add_vc_icon_picker' ), get_template_directory_uri() . '/js/admin/vc.js');
	 		vc_add_shortcode_param( 'wproto_taxonomy_picker', array( $this, 'add_vc_taxonomy_picker' ), get_template_directory_uri() . '/js/admin/vc.js');
	 		vc_add_shortcode_param( 'wproto_pricing_tables_picker', array( $this, 'add_vc_pricing_table_picker' ), get_template_directory_uri() . '/js/admin/vc.js');
	 		vc_add_shortcode_param( 'wproto_ninja_forms_picker', array( $this, 'add_vc_ninja_forms_picker' ), get_template_directory_uri() . '/js/admin/vc.js');
 		}
		
	}
	
	/**
	 * Icon picker for Visual Composer
	 **/
	function add_vc_icon_picker( $settings, $value ) {
		$dependency = vc_generate_dependencies_attributes( $settings );
 		return '<div class="wpl-vc-iconpicker">'
           .'<input type="hidden" name="'.$settings['param_name']
           .'" class="wpb_vc_param_value wproto-icon-holder-input wpb-textinput '
           . $settings['param_name'].' '.$settings['type'].'_field" value="'
           . $value.'" ' . $dependency . '/>'
           
           . '<i data-name="" class="wproto-icon-holder ' . $value . ' fa-4x"></i> <a href="javascript:;" class="wproto-icon-chooser">' . __('Select icon', 'wproto') . '</a>'
           
       .'</div>';
	}
	
	/**
	 * Taxonomy picker
	 **/
	function add_vc_taxonomy_picker( $settings, $value ) {
		global $wpl_exe_wp;
		
		$dependency = vc_generate_dependencies_attributes( $settings );

		$taxonomies = array(
			'category' => __('Blog Categories', 'wproto'),
			'wproto_portfolio_category' => __('Portfolio Categories', 'wproto'),
			'product_cat'	=> __('Product category', 'wproto')
		);

		$return = '<div class="wpl-vc-taxonomies-picker">
			<select ' . $dependency . ' class="wpb_vc_param_value wpb-input wpb-select '. $settings['param_name'].' '.$settings['type'].'_field" name="'.$settings['param_name'].'">';
		
		foreach( $taxonomies as $k=>$v ) {
			$selected = $k == $value ? 'selected="selected"' : '';
			$return .= '<option ' . $selected . ' value="' . $k . '">' . $v . '</option>';
		}
		
		$return .= '</select></div>';
					
		return $return;
	}
	
	/**
	 * Pricing tables picker for Visual Composer
	 **/
	function add_vc_pricing_table_picker( $settings, $value ) {
		global $wpl_exe_wp;
		
		$dependency = vc_generate_dependencies_attributes( $settings );

		$pricing_tables = $wpl_exe_wp->model('post')->get_all_pricing_tables();

		$return = '<div class="wpl-vc-pricint-tabler-picker">
			<select ' . $dependency . ' class="wpb_vc_param_value wpb-input wpb-select '. $settings['param_name'].' '.$settings['type'].'_field" name="'.$settings['param_name'].'">';
		
		if( $pricing_tables->have_posts() ) {
			
			while( $pricing_tables->have_posts() ): $pricing_tables->the_post();
				$id = get_the_ID();
				$selected = $id == $value ? 'selected="selected"' : '';
				$return .= '<option ' . $selected . ' value="' . $id . '">' . get_the_title( $id ) . '</option>';
			endwhile; wp_reset_postdata();
			
		}
		
		$return .= '</select></div>';
					
		return $return;
	}
	
	/**
	 * Ninja Forms picker
	 **/
	function add_vc_ninja_forms_picker( $settings, $v ) {
		
		$dependency = vc_generate_dependencies_attributes( $settings );

		$return = '<div class="wpl-vc-pricint-tabler-picker">
			<select ' . $dependency . ' class="wpb_vc_param_value wpb-input wpb-select '. $settings['param_name'].' '.$settings['type'].'_field" name="'.$settings['param_name'].'">';
		
			if ( ! function_exists( 'ninja_forms_get_all_forms' ) ) {
				// experimental, maybe not needed
				require_once( NINJA_FORMS_DIR . "/includes/database.php" );
			}
			$ninja_forms_data = ninja_forms_get_all_forms();
			$ninja_forms = array();
			if ( ! empty( $ninja_forms_data ) ) {
				// Fill array with Name=>Value(ID)
				foreach ( $ninja_forms_data as $key => $value ) {
					if ( is_array( $value ) ) {
						$selected = $v == $value['id'] ? 'selected="selected"' : '';
						$return .= '<option ' . $selected . ' value="' . $value['id'] . '">' . $value['name'] . '</option>';
					}
				}
			}
		
		$return .= '</select></div>';
					
		return $return;
	}
	
	/**
	 * Init VC
	 **/
	function init_vc() {
		global $wpl_exe_wp;
		
		if( $wpl_exe_wp->get_option( 'disable_bundled_vc', 'plugins' ) == false && function_exists('vc_set_as_theme') ) vc_set_as_theme( true );
		//if( $wpl_exe_wp->get_option( 'use_stand_alone_vc', 'plugins' ) == false && function_exists('vc_disable_frontend') ) vc_disable_frontend();

	}
	
	/**
	 * Setup VC
	 **/
	function setup_vc() {
		global $typenow, $wpl_exe_wp;
		
		if( $wpl_exe_wp->get_option( 'use_stand_alone_vc', 'plugins' ) == false ) {
			vc_remove_element( 'ninja_forms_display_form' );
			vc_remove_element( 'vc_gallery' );
			vc_remove_element( 'vc_images_carousel' );
			vc_remove_element( 'vc_posts_slider' );
			vc_remove_element( 'vc_basic_grid' );
			vc_remove_element( 'vc_media_grid' );
			vc_remove_element( 'vc_masonry_grid' );
			vc_remove_element( 'vc_masonry_media_grid' );
			vc_remove_element( 'vc_button' );
			vc_remove_element( 'vc_button2' );
			vc_remove_element( 'vc_cta_button' );
			vc_remove_element( 'vc_cta_button2' );
			vc_remove_element( 'vc_tta_pageable' );		
			
			/*
			vc_remove_element( 'woocommerce_order_tracking' );
			vc_remove_element( 'recent_products' );
			vc_remove_element( 'featured_products' );
			vc_remove_element( 'product' );
			vc_remove_element( 'products' );
			vc_remove_element( 'add_to_cart' );
			vc_remove_element( 'product_category' );
			vc_remove_element( 'product_categories' );
			vc_remove_element( 'sale_products' );
			vc_remove_element( 'best_selling_products' );
			vc_remove_element( 'top_rated_products' );
			
			vc_remove_element( 'vc_wp_search' );
			vc_remove_element( 'vc_wp_meta' );
			vc_remove_element( 'vc_wp_recentcomments' );
			vc_remove_element( 'vc_wp_calendar' );
			vc_remove_element( 'vc_wp_pages' );
			vc_remove_element( 'vc_wp_tagcloud' );
			vc_remove_element( 'vc_wp_custommenu' );
			vc_remove_element( 'vc_wp_text' );
			vc_remove_element( 'vc_wp_posts' );
			vc_remove_element( 'vc_wp_categories' );
			vc_remove_element( 'vc_wp_archives' );
			vc_remove_element( 'vc_wp_rss' );
			*/
			
		}

	}
	
 	/**
 	 * Add custom params to VC elements / remove unused elements
 	 **/
 	function add_remove_vc_params() {

		vc_remove_param( 'vc_tabs', 'interval' );
		vc_remove_param( 'vc_tour', 'interval' );
		vc_remove_param( 'vc_tabs', 'title' );
		vc_remove_param( 'vc_tour', 'title' );
		vc_remove_param( 'vc_accordion', 'title' );
		vc_remove_param( 'vc_accordion', 'collapsible' );
		vc_remove_param( 'vc_accordion', 'collapsible' );
		vc_remove_param( 'vc_accordion', 'disable_keyboard' );
		vc_remove_param( 'vc_accordion', 'active_tab' );
		vc_remove_param( 'vc_tab', 'tab_id');
		
		vc_add_param( 'vc_tab', array(
			'type' => 'wproto_icon_picker',
			'heading' => __('Tab icon', 'wproto'),
			'param_name' => 'tab_icon',
			'value' => '',
		));
		
		vc_add_param( 'vc_accordion', array(
			'type' => 'dropdown',
			'heading' => __('Style', 'wproto'),
			'admin_label' => true,
			'param_name' => 'accordion_style',
			'value' => array(
				__('Style', 'wproto') . ' 1' => 'style_1',
				__('Style', 'wproto') . ' 2' => 'style_2',
				__('Style', 'wproto') . ' 3' => 'style_3',
				__('Style', 'wproto') . ' 4' => 'style_4',
				__('Style', 'wproto') . ' 5' => 'style_5',
				__('Style', 'wproto') . ' 6' => 'style_6',
				__('Style', 'wproto') . ' 7' => 'style_7',
				__('Style', 'wproto') . ' 8' => 'style_8',
				__('Style', 'wproto') . ' 9' => 'style_9',
				__('Style', 'wproto') . ' 10' => 'style_10',
				__('Style (for dark backgrounds)', 'wproto') . ' 1' => 'style_11',
				__('Style (for dark backgrounds)', 'wproto') . ' 2' => 'style_12',
				__('Style (for dark backgrounds)', 'wproto') . ' 3' => 'style_13',
				__('Style (for dark backgrounds)', 'wproto') . ' 4' => 'style_14',
				__('Style (for dark backgrounds)', 'wproto') . ' 5' => 'style_15',
				__('Style (for dark backgrounds)', 'wproto') . ' 6' => 'style_16',
			),
		));
		
		vc_add_params( 'vc_tour', array( 
			array(
				'type' => 'textfield',
				'heading' => __('Responsive screen width breakpoint (in pixels)', 'wproto'),
				'param_name' => 'break'
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Style', 'wproto'),
				'admin_label' => true,
				'param_name' => 'tabs_style',
				'value' => array(
					__('Style', 'wproto') . ' 1' => 'style_1',
					__('Style', 'wproto') . ' 2' => 'style_2',
					__('Style', 'wproto') . ' 3' => 'style_3',
				),
			),
		));
		
		vc_add_params( 'vc_tabs', array( 
			array(
				'type' => 'textfield',
				'heading' => __('Responsive screen width breakpoint (in pixels)', 'wproto'),
				'param_name' => 'break'
			),
			array(
				'type' => 'dropdown',
				'heading' => __('Style', 'wproto'),
				'admin_label' => true,
				'param_name' => 'tabs_style',
				'value' => array(
					__('Style', 'wproto') . ' 1' => 'style_1',
					__('Style', 'wproto') . ' 2' => 'style_2',
					__('Style', 'wproto') . ' 3' => 'style_3',
					__('Style', 'wproto') . ' 4' => 'style_4',
					__('Style', 'wproto') . ' 5' => 'style_5',
					__('Style', 'wproto') . ' 6' => 'style_6',
					__('Style', 'wproto') . ' 7' => 'style_7',
					__('Style', 'wproto') . ' 8' => 'style_8',
				),
			),
		));

		
		vc_remove_param( 'vc_tta_tabs', 'title' );
		vc_remove_param( 'vc_tta_tabs', 'style' );
		vc_remove_param( 'vc_tta_tabs', 'shape' );
		vc_remove_param( 'vc_tta_tabs', 'color' );
		vc_remove_param( 'vc_tta_tabs', 'no_fill_content_area' );
		vc_remove_param( 'vc_tta_tabs', 'spacing' );
		vc_remove_param( 'vc_tta_tabs', 'gap' );
		vc_remove_param( 'vc_tta_tabs', 'alignment' );
		vc_remove_param( 'vc_tta_tabs', 'pagination_style' );
		vc_remove_param( 'vc_tta_tabs', 'pagination_color' );
		vc_remove_param( 'vc_tta_tabs', 'tab_position' );
		vc_remove_param( 'vc_tta_tabs', 'autoplay' );
		
		vc_add_params( 'vc_tta_tabs', array( 
			array(
				'type' => 'dropdown',
				'heading' => __('Style', 'wproto'),
				'admin_label' => true,
				'param_name' => 'wproto_style',
				'value' => array(
					__('Style', 'wproto') . ' 1' => 'style_1',
					__('Style', 'wproto') . ' 2' => 'style_2',
					__('Style', 'wproto') . ' 3' => 'style_3',
					__('Style', 'wproto') . ' 4' => 'style_4',
					__('Style', 'wproto') . ' 5' => 'style_5',
					__('Style', 'wproto') . ' 6' => 'style_6',
					__('Style', 'wproto') . ' 7' => 'style_7',
					__('Style', 'wproto') . ' 8' => 'style_8',
				),
			),
		));
		
		vc_remove_param( 'vc_tta_tour', 'title' );
		vc_remove_param( 'vc_tta_tour', 'style' );
		vc_remove_param( 'vc_tta_tour', 'shape' );
		vc_remove_param( 'vc_tta_tour', 'color' );
		vc_remove_param( 'vc_tta_tour', 'no_fill_content_area' );
		vc_remove_param( 'vc_tta_tour', 'spacing' );
		vc_remove_param( 'vc_tta_tour', 'gap' );
		vc_remove_param( 'vc_tta_tour', 'tab_position' );
		vc_remove_param( 'vc_tta_tour', 'alignment' );
		vc_remove_param( 'vc_tta_tour', 'autoplay' );
		vc_remove_param( 'vc_tta_tour', 'pagination_style' );
		vc_remove_param( 'vc_tta_tour', 'pagination_color' );
		vc_remove_param( 'vc_tta_tour', 'controls_size' );
		
		vc_add_params( 'vc_tta_tour', array( 
			array(
				'type' => 'dropdown',
				'heading' => __('Style', 'wproto'),
				'admin_label' => true,
				'param_name' => 'wproto_style',
				'value' => array(
					__('Style', 'wproto') . ' 1' => 'style_1',
					__('Style', 'wproto') . ' 2' => 'style_2',
					__('Style', 'wproto') . ' 3' => 'style_3',
				),
			),
		));
		
		vc_remove_param( 'vc_tta_accordion', 'title' );
		vc_remove_param( 'vc_tta_accordion', 'style' );
		vc_remove_param( 'vc_tta_accordion', 'shape' );
		vc_remove_param( 'vc_tta_accordion', 'color' );
		vc_remove_param( 'vc_tta_accordion', 'no_fill' );
		vc_remove_param( 'vc_tta_accordion', 'spacing' );
		vc_remove_param( 'vc_tta_accordion', 'gap' );
		vc_remove_param( 'vc_tta_accordion', 'c_align' );
		vc_remove_param( 'vc_tta_accordion', 'c_position' );
		vc_remove_param( 'vc_tta_accordion', 'c_icon' );
		vc_remove_param( 'vc_tta_accordion', 'autoplay' );
		
		vc_add_param( 'vc_tta_accordion', array(
			'type' => 'dropdown',
			'heading' => __('Style', 'wproto'),
			'admin_label' => true,
			'param_name' => 'wproto_style',
			'value' => array(
				__('Style', 'wproto') . ' 1' => 'style_1',
				__('Style', 'wproto') . ' 2' => 'style_2',
				__('Style', 'wproto') . ' 3' => 'style_3',
				__('Style', 'wproto') . ' 4' => 'style_4',
				__('Style', 'wproto') . ' 5' => 'style_5',
				__('Style', 'wproto') . ' 6' => 'style_6',
				__('Style', 'wproto') . ' 7' => 'style_7',
				__('Style', 'wproto') . ' 8' => 'style_8',
				__('Style', 'wproto') . ' 9' => 'style_9',
				__('Style', 'wproto') . ' 10' => 'style_10',
				__('Style (for dark backgrounds)', 'wproto') . ' 1' => 'style_11',
				__('Style (for dark backgrounds)', 'wproto') . ' 2' => 'style_12',
				__('Style (for dark backgrounds)', 'wproto') . ' 3' => 'style_13',
				__('Style (for dark backgrounds)', 'wproto') . ' 4' => 'style_14',
				__('Style (for dark backgrounds)', 'wproto') . ' 5' => 'style_15',
				__('Style (for dark backgrounds)', 'wproto') . ' 6' => 'style_16',
			),
		));


		vc_add_params( 'vc_row', array( 
			array(
				'type' => 'dropdown',
				'heading' => __('Animation', 'wproto'),
				'admin_label' => true,
				'param_name' => 'animation',
				'value' => $this->animations_list,
				'group' => __('Style', 'wproto'),
			),
			array(
				'type' => 'textfield',
				'heading' => __('Animation delay', 'wproto'),
				'admin_label' => true,
				'param_name' => 'animation_delay',
				'value' => '',
				'description' => __('For example: 0.1s', 'wproto'),
				'group' => __('Style', 'wproto'),
				'dependency' => array(
					'element' => 'animation',
					'not_empty' => true
				),
			),
			array(
				'type' => 'textfield',
				'heading' => __('Parallax speed', 'wproto'),
				'param_name' => 'parallax_speed',
				'value' => '',
				'description' => __('For example: 2.5', 'wproto'),
				'group' => __('Style', 'wproto'),
			),
		));
			
		// Adding animation to columns
		vc_add_params('vc_column', array(
			array(
				'type' => 'dropdown',
				'heading' => __('Animation', 'wproto'),
				'admin_label' => true,
				'param_name' => 'animation',
				'value' => $this->animations_list,
				'group' => __('Style', 'wproto'),
			),
			array(
				'type' => 'textfield',
				'heading' => __('Animation delay (e.g. 0.2s)', 'wproto'),
				'admin_label' => true,
				'param_name' => 'animation_delay',
				'value' => '',
				'group' => __('Style', 'wproto'),
				'dependency' => array(
					'element' => 'animation',
					'not_empty' => true
				),
			),
		));
 		
	}
	
	/**
	 * Add custom VC items
	 **/	
 	function add_vc_items( $hook ) {	 		
 		global $wpl_exe_wp;
 		
 		$order = array(
			'ID' => 'ID',
			'Date' => 'date',
			'Modified date' => 'modified',
			'Title' => 'title',
			'Random' => 'rand',
			'Menu order' => 'menu'
	 	);
	 	
 		$sort = array(
			__('Ascending', 'wproto') => 'ASC',
			__('Descending', 'wproto') => 'DESC'
	 	);
	 	
		/**
	 	 * Heading
	   **/
		vc_map( array(
			'name' => __( 'Header', 'wproto' ),
			'description' => __('Displays a header and sub-header text', 'wproto'),
			'base' => 'wproto_heading',
			'icon' => 'wproto_heading',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Header text', 'wproto'),
    			'param_name' => 'text',
    			'value' => '',
    			'description' => __('That text will be appeared as &lt;h> tag', 'wproto')
   			),
				array(
 					'type' => 'textarea',
    			'heading' => __('Sub-header text (optional)', 'wproto'),
    			'param_name' => 'subtext',
    			'value' => '',
    			'description' => __('That text will be appeared after header tag', 'wproto')
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Header size', 'wproto'),
    			'param_name' => 'tag',
    			'value' => array(
    				__('Header 1 (H1)', 'wproto') => 'h1',
    				__('Header 2 (H2)', 'wproto') => 'h2',
						__('Header 3 (H3)', 'wproto') => 'h3',
						__('Header 4 (H4)', 'wproto') => 'h4',
						__('Header 5 (H5)', 'wproto') => 'h5',
						__('Header 6 (H6)', 'wproto') => 'h6',
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Text align', 'wproto'),
    			'param_name' => 'align',
    			'value' => array(
    				__('- Default -', 'wproto') => '',
    				__('Left', 'wproto') => 'left',
						__('Center', 'wproto') => 'center',
						__('Right', 'wproto') => 'right',
						__('Justify', 'wproto') => 'justify'
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Text transform', 'wproto'),
    			'param_name' => 'transform',
    			'value' => array(
    				__('- Default -', 'wproto') => '',
    				__('None', 'wproto') => 'none',
						__('Uppercase', 'wproto') => 'uppercase',
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Font style', 'wproto'),
    			'param_name' => 'font_style',
    			'value' => array(
    				__('- Default -', 'wproto') => '',
    				__('Normal', 'wproto') => 'normal',
						__('Italic', 'wproto') => 'italic',
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Font weight', 'wproto'),
    			'param_name' => 'weight',
    			'value' => array(
    				__('- Default -', 'wproto') => '',
    				__('Normal', 'wproto') => '400',
    				__('Light', 'wproto') => '300',
						__('Bold', 'wproto') => '700',
						__('Bolder', 'wproto') => '800',
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Header text color (optional)', 'wproto'),
    			'param_name' => 'color',
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Subheader text color (optional)', 'wproto'),
    			'param_name' => 'subtext_color',
 					'group' => __('Style', 'wproto'),
   			),
				array(
					'type' => 'checkbox',
					'heading' => __('Draw a line after header', 'wproto'),
					'param_name' => 'header_line',
					'value' => array(
						'' => 'false'
					),
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Use accent color for a line', 'wproto'),
					'param_name' => 'header_line_accent',
					'value' => array(
						'' => 'false'
					),
					'group' => __('Style', 'wproto'),
				),
			)
		));
		
		
		/**
	 	 * Dropcap
	   **/
		vc_map( array(
			'name' => __( 'Dropcap text', 'wproto' ),
			'description' => __('Displays a text with a DropCap', 'wproto'),
			'base' => 'wproto_dropcap',
			'icon' => 'wproto_dropcap',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
   			array(
   				'type' => 'textarea',
    			'heading' => __('Text', 'wproto'),
    			'param_name' => 'text',
    			'value' => '',
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style preset', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Style', 'wproto') . ' 1' => 'style_1',
						__('Style', 'wproto') . ' 2' => 'style_2',
						__('Style', 'wproto') . ' 3' => 'style_3',
						__('Style', 'wproto') . ' 4' => 'style_4',				
						__('Style', 'wproto') . ' 5' => 'style_5',	
						__('Style', 'wproto') . ' 6' => 'style_6',		
					),
   			),
			)
		));
		
		/**
	 	 * Quote
	   **/
		vc_map( array(
			'name' => __( 'Quote', 'wproto' ),
			'description' => __('Displays a styled Quote', 'wproto'),
			'base' => 'wproto_quote',
			'icon' => 'wproto_quote',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
   			array(
   				'type' => 'textarea',
    			'heading' => __('Quote Text', 'wproto'),
    			'param_name' => 'text',
    			'value' => '',
   			),
   			array(
   				'type' => 'textfield',
    			'heading' => __('Author', 'wproto'),
    			'param_name' => 'author',
    			'value' => '',
   			),
   			array(
   				'type' => 'textfield',
    			'heading' => __('Additional text', 'wproto'),
    			'param_name' => 'add_text',
    			'value' => '',
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style preset', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Style', 'wproto') . ' 1' => 'style_1',
						__('Style', 'wproto') . ' 2' => 'style_2',
						__('Style', 'wproto') . ' 3' => 'style_3',
						__('Style', 'wproto') . ' 4' => 'style_4',				
						__('Style', 'wproto') . ' 5' => 'style_5',	
						__('Style', 'wproto') . ' 6' => 'style_6',		
						__('Style', 'wproto') . ' 7' => 'style_7',
						__('Style', 'wproto') . ' 8' => 'style_8',
						__('Style', 'wproto') . ' 9' => 'style_9',
						__('Style', 'wproto') . ' 10' => 'style_10',	
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Text custom color', 'wproto'),
    			'param_name' => 'text_color',
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Author text custom color', 'wproto'),
    			'param_name' => 'author_color',
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Additional text custom color', 'wproto'),
    			'param_name' => 'additional_text_color',
 					'group' => __('Style', 'wproto'),
   			),
			)
		));
		
		/**
	 	 * Image
	   **/
		vc_map( array(
			'name' => __( 'Image', 'wproto' ),
			'description' => __('Image with support HDPI devices', 'wproto'),
			'base' => 'wproto_image',
			'icon' => 'wproto_image',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'attach_image',
    			'heading' => __('Image', 'wproto'),
    			'param_name' => 'image',
    			'value' => '',
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Image size', 'wproto'),
    			'param_name' => 'image_size',
    			'value' => '',
    			'description' => __('Enter image size in pixels, e.g.: 200x100 (Width x Height). Leave empty to use full size.', 'wproto')
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Link this image', 'wproto'),
    			'param_name' => 'link_type',
    			'value' => array(
    				__('Do not link', 'wproto') => '',
    				__('Direct link to image', 'wproto') => 'direct',
						__('Open full size of image in lightbox', 'wproto') => 'lightbox',
						__('Custom link (type it in the next field)', 'wproto') => 'custom'
					),
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Custom link', 'wproto'),
    			'param_name' => 'custom_link',
    			'value' => '',
					'dependency' => array(
						'element' => 'link_type',
						'value' => array('custom')
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Link target', 'wproto'),
    			'param_name' => 'link_target',
    			'value' => array(
    				__('_self', 'wproto') => '_self',
						__('_blank', 'wproto') => '_blank'
					),
					'dependency' => array(
						'element' => 'link_type',
						'value' => array('custom', 'direct')
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Image alignment', 'wproto'),
    			'param_name' => 'image_align',
    			'value' => array(
    				__('None', 'wproto') => 'alignnone',
						__('Align Left', 'wproto') => 'alignleft',
						__('Align Right', 'wproto') => 'alignright',
						__('Align Center', 'wproto') => 'aligncenter',
					),
 					'group' => __('Style', 'wproto'),
   			),
				array(
					'type' => 'dropdown',
					'heading' => __('Animation', 'wproto'),
					'admin_label' => true,
					'param_name' => 'animation',
					'value' => $this->animations_list,
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Animation delay', 'wproto'),
					'admin_label' => true,
					'param_name' => 'animation_delay',
					'value' => '',
					'description' => __('For example: 0.1s', 'wproto'),
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'animation',
						'not_empty' => true
					),
				),
			)
		));
		
		/**
	 	 * Banner
	   **/
		vc_map( array(
			'name' => __( 'Banner', 'wproto' ),
			'description' => __('Animated banner', 'wproto'),
			'base' => 'wproto_banner',
			'icon' => 'wproto_banner',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'vc_link',
    			'heading' => __('Link', 'wproto'),
    			'param_name' => 'link',
    			'value' => '',
    			'group' => __('Settings', 'wproto')
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Banner type', 'wproto'),
    			'param_name' => 'type',
    			'value' => array(
    				__('Text', 'wproto') => 'text',
						__('Image with text', 'wproto') => 'image',
					),
 					'group' => __('Settings', 'wproto')
   			),
				array(
 					'type' => 'attach_image',
    			'heading' => __('Background Image', 'wproto'),
    			'param_name' => 'image',
    			'value' => '',
					'dependency' => array(
						'element' => 'type',
						'value' => array('image')
					),
    			'group' => __('Settings', 'wproto')
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Header text', 'wproto'),
    			'param_name' => 'header_text',
    			'value' => '',
    			'group' => __('Settings', 'wproto')
   			),
				array(
 					'type' => 'textarea',
    			'heading' => __('Description text', 'wproto'),
    			'param_name' => 'description_text',
    			'value' => '',
    			'group' => __('Settings', 'wproto')
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('"Read more" link / button text', 'wproto'),
    			'param_name' => 'read_more_text',
    			'value' => '',
    			'description' => __('Leave it blank to hide this line / button', 'wproto'),
					'group' => __('Settings', 'wproto'),
   			),
				array(
					'type' => 'checkbox',
					'heading' => __('Draw a border', 'wproto'),
					'param_name' => 'border',
					'value' => array(
						'' => 'false'
					),
					'dependency' => array(
						'element' => 'type',
						'value' => array('text')
					),
					'group' => __('Settings', 'wproto'),
				),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Text align inside', 'wproto'),
    			'param_name' => 'text_align',
    			'value' => array(
    				__('Left', 'wproto') => 'left',
						__('Center', 'wproto') => 'center',
						__('Right', 'wproto') => 'right',
						__('Justify', 'wproto') => 'justify',
					),
 					'group' => __('Style', 'wproto')
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Header text color', 'wproto'),
    			'param_name' => 'header_color',
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Description text color', 'wproto'),
    			'param_name' => 'description_color',
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('"Read more" text color', 'wproto'),
    			'param_name' => 'read_more_color',
					'dependency' => array(
						'element' => 'type',
						'value' => array('image')
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Border color', 'wproto'),
    			'param_name' => 'border_color',
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Accent background color', 'wproto'),
    			'param_name' => 'accent_background_color',
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Accent text color', 'wproto'),
    			'param_name' => 'accent_text_color',
 					'group' => __('Style', 'wproto'),
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Custom top padding (in pixels)', 'wproto'),
    			'param_name' => 'padding_top',
    			'value' => '',
    			'group' => __('Style', 'wproto')
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Custom right padding (in pixels)', 'wproto'),
    			'param_name' => 'padding_right',
    			'value' => '',
    			'group' => __('Style', 'wproto')
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Custom bottom padding (in pixels)', 'wproto'),
    			'param_name' => 'padding_bottom',
    			'value' => '',
    			'group' => __('Style', 'wproto')
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Custom left padding (in pixels)', 'wproto'),
    			'param_name' => 'padding_left',
    			'value' => '',
    			'group' => __('Style', 'wproto')
   			),
				array(
					'type' => 'dropdown',
					'heading' => __('Animation', 'wproto'),
					'admin_label' => true,
					'param_name' => 'animation',
					'value' => $this->animations_list,
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Animation delay', 'wproto'),
					'admin_label' => true,
					'param_name' => 'animation_delay',
					'value' => '',
					'description' => __('For example: 0.1s', 'wproto'),
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'animation',
						'not_empty' => true
					),
				),
			)
		));
		
		/**
	   * Button
	   **/
		vc_map( array(
			'name' => __( 'Button', 'wproto' ),
			'description' => __('Theme styled button', 'wproto'),
			'base' => 'wproto_button',
			'icon' => 'wproto_button',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'vc_link',
    			'heading' => __('Button link', 'wproto'),
    			'param_name' => 'link',
    			'value' => '',
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Text on the button', 'wproto'),
    			'param_name' => 'title',
    			'value' => __('My button title', 'wproto' ),
   			),
				array(
 					'type' => 'wproto_icon_picker',
    			'heading' => __('Button icon', 'wproto'),
    			'param_name' => 'icon',
    			'value' => '',
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Button size', 'wproto'),
    			'param_name' => 'size',
    			'value' => array(
    				__('Medium', 'wproto') => 'medium',
						__('Mini', 'wproto') => 'mini',
						__('Small', 'wproto') => 'small',
						__('Large', 'wproto') => 'large',
						__('Extra large', 'wproto') => 'extra-large',
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Button style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Red', 'wproto') => 'red',
						__('Black', 'wproto') => 'black',
						__('Green', 'wproto') => 'green',
						__('Blue', 'wproto') => 'blue',
						__('Red stroke', 'wproto') => 'red-stroke',
						__('Black stroke', 'wproto') => 'black-stroke',
						__('Green stroke', 'wproto') => 'green-stroke',
						__('Blue stroke', 'wproto') => 'blue-stroke',
						__('Orange gradient', 'wproto') => 'orange-gradient',
						__('Purple gradient', 'wproto') => 'purple-gradient',
						__('Green gradient', 'wproto') => 'green-gradient',
						__('Blue gradient', 'wproto') => 'blue-gradient',
						__('With shadow', 'wproto') => 'shadow',
						__('Iconic', 'wproto') => 'iconic',
						__('Iconic Gradient', 'wproto') => 'iconic-gradient',
						__('Iconic Alt', 'wproto') => 'iconic-alt',
						__('Iconic Stroke', 'wproto') => 'iconic-stroke',
					),
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Border radius', 'wproto'),
    			'param_name' => 'border_radius',
    			'value' => '',
    			'description' => __('Use % or px, e.g.: 50%, 10px etc.', 'wproto'),
					'group' => __('Customize style', 'wproto')
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Icon holder radius', 'wproto'),
    			'param_name' => 'icon_radius',
    			'value' => '',
					'group' => __('Customize style', 'wproto')
   			),
			)
		));
		
		/**
		 * Iconic list
		 **/
		vc_map( array(
			'name' => __( 'Iconic list', 'wproto' ),
			'description' => __('Lists with custom bullets', 'wproto'),
			'base' => 'wproto_iconic_list_container',
			'icon' => 'wproto_iconic_list_container',
			'show_settings_on_create' => false,
			'content_element' => true,
			'as_parent' => array('only' => 'wproto_iconic_list_elem'),
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
  		'params' => array(
      	array(
					'type' => 'textfield',
					'heading' => __('Extra class name', 'wproto'),
          'param_name' => 'el_class',
          'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'wproto')
				),
			),
			'js_view' => 'VcColumnView'
		));
		
		vc_map( array(
			'name' => __( 'Iconic element', 'wproto' ),
			'base' => 'wproto_iconic_list_elem',
			'icon' => 'wproto_iconic_list_container',
			'as_child' => array( 'only' => 'wproto_iconic_list_container' ),
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
  		'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Text', 'wproto'),
    			'param_name' => 'title',
    			'value' => '',
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Element icon', 'wproto'),
    			'param_name' => 'icon_type',
    			'value' => array(
    				__('Select an icon from library', 'wproto') => '',
    				__('Numeric list', 'wproto') => 'numeric',
					),
   			),
				array(
 					'type' => 'wproto_icon_picker',
    			'heading' => __('Choose element icon', 'wproto'),
    			'param_name' => 'icon',
    			'value' => '',
					'dependency' => array(
						'element' => 'icon_type',
						'value' => array('')
					),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Custom text color', 'wproto'),
    			'param_name' => 'text_color',
 					'group' => __('Customize style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Custom icon / number color', 'wproto'),
    			'param_name' => 'icon_color',
 					'group' => __('Customize style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Number background color', 'wproto'),
    			'param_name' => 'icon_background_color',
					'dependency' => array(
						'element' => 'icon_type',
						'value' => array('numeric')
					),
 					'group' => __('Customize style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Number border color', 'wproto'),
    			'param_name' => 'icon_border_color',
					'dependency' => array(
						'element' => 'icon_type',
						'value' => array('numeric')
					),
 					'group' => __('Customize style', 'wproto'),
   			),
      	array(
					'type' => 'textfield',
					'heading' => __('Border radius', 'wproto'),
          'param_name' => 'border_radius',
					'dependency' => array(
						'element' => 'icon_type',
						'value' => array('numeric')
					),
          'group' => __('Customize style', 'wproto'),
				),
      	array(
					'type' => 'textfield',
					'heading' => __('Extra class name', 'wproto'),
          'param_name' => 'el_class',
          'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'wproto'),
          'group' => __('Customize style', 'wproto'),
				),
			)
		));  
		
		/**
	 	 * Message box
	   **/
		vc_map( array(
			'name' => __( 'Message Box', 'wproto' ),
			'description' => __('Displays alerts', 'wproto'),
			'base' => 'wproto_message_box',
			'icon' => 'wproto_message_box',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Message text', 'wproto'),
    			'param_name' => 'text',
    			'value' => '',
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Message Type', 'wproto'),
    			'param_name' => 'type',
    			'value' => array(
						__('Error', 'wproto') => 'error',
						__('Help', 'wproto') => 'help',
						__('Notice', 'wproto') => 'notice',
						__('Success', 'wproto') => 'success',
						__('Information', 'wproto') => 'information',
						__('General', 'wproto') => 'general',					
					),
   			),
				array(
 					'type' => 'wproto_icon_picker',
    			'heading' => __('Custom icon', 'wproto'),
    			'param_name' => 'icon',
    			'value' => '',
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Default', 'wproto') => 'default',
						__('Stroke', 'wproto') => 'stroke',
						__('Gradient', 'wproto') => 'gradient',
						__('Flat', 'wproto') => 'flat',
						__('Solid icon', 'wproto') => 'solid_icon',			
					),
 					'group' => __('Style', 'wproto'),
   			),
				array(
					'type' => 'dropdown',
					'heading' => __('Animation', 'wproto'),
					'admin_label' => true,
					'param_name' => 'animation',
					'value' => $this->animations_list,
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Animation delay', 'wproto'),
					'admin_label' => true,
					'param_name' => 'animation_delay',
					'value' => '',
					'description' => __('For example: 0.1s', 'wproto'),
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'animation',
						'not_empty' => true
					),
				),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Custom text color', 'wproto'),
    			'param_name' => 'text_color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('default', 'stroke', 'flat', 'solid_icon')
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Custom icon color', 'wproto'),
    			'param_name' => 'icon_color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('default', 'stroke', 'flat', 'solid_icon')
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Custom border color', 'wproto'),
    			'param_name' => 'border_color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('default', 'stroke', 'flat', 'solid_icon')
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Custom background color', 'wproto'),
    			'param_name' => 'bg_color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('default', 'stroke', 'flat', 'solid_icon')
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Custom icon background color', 'wproto'),
    			'param_name' => 'icon_bg_color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('default', 'stroke', 'flat', 'solid_icon')
					),
 					'group' => __('Style', 'wproto'),
   			),
			)
		));
		
		/**
	 	 * Call to action
	   **/
		vc_map( array(
			'name' => __( 'Call to action', 'wproto' ),
			'description' => __('Text and button', 'wproto'),
			'base' => 'wproto_call_to_action',
			'icon' => 'wproto_call_to_action',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Header text', 'wproto'),
    			'param_name' => 'header_text',
    			'value' => '',
   			),
				array(
 					'type' => 'textarea',
    			'heading' => __('Call to action text', 'wproto'),
    			'param_name' => 'text',
    			'value' => "",
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Block style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Centered, without buttons', 'wproto') => 'no-buttons',
						__('Centered, 2 buttons', 'wproto') => 'centered-2',
						__('Centered, 1 button', 'wproto') => 'centered-1',
						__('Left Button', 'wproto') => 'button-left',
						__('Right Button', 'wproto') => 'button-right',					
					),
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Button text', 'wproto'),
    			'param_name' => 'button_text',
    			'value' => '',
					'dependency' => array(
						'element' => 'style',
						'value' => array('centered-2', 'centered-1', 'button-left', 'button-right')
					),
   			),
				array(
 					'type' => 'vc_link',
    			'heading' => __('Button link', 'wproto'),
    			'param_name' => 'button_link',
    			'value' => '',
					'dependency' => array(
						'element' => 'style',
						'value' => array('centered-2', 'centered-1', 'button-left', 'button-right')
					),
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Second button text', 'wproto'),
    			'param_name' => 'button2_text',
    			'value' => '',
					'dependency' => array(
						'element' => 'style',
						'value' => array('centered-2')
					),
   			),
				array(
 					'type' => 'vc_link',
    			'heading' => __('Second button link', 'wproto'),
    			'param_name' => 'button2_link',
    			'value' => '',
					'dependency' => array(
						'element' => 'style',
						'value' => array('centered-2')
					),
   			),
				array(
					'type' => 'checkbox',
					'heading' => __('Display triangle at bottom', 'wproto'),
					'param_name' => 'display_triangle',
					'value' => array(
						'' => 'false'
					),
					'group' => __('Customize style', 'wproto'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Animation', 'wproto'),
					'admin_label' => true,
					'param_name' => 'animation',
					'value' => $this->animations_list,
					'group' => __('Customize style', 'wproto'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Animation delay', 'wproto'),
					'admin_label' => true,
					'param_name' => 'animation_delay',
					'value' => '',
					'description' => __('For example: 0.1s', 'wproto'),
					'group' => __('Customize style', 'wproto'),
					'dependency' => array(
						'element' => 'animation',
						'not_empty' => true
					),
				),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Custom text color', 'wproto'),
    			'param_name' => 'text_color',
 					'group' => __('Customize style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Custom background color', 'wproto'),
    			'param_name' => 'bg_color',
 					'group' => __('Customize style', 'wproto'),
   			),
			)
		));
		
		/**
	 	 * Progress bars
	   **/
		vc_map( array(
			'name' => __( 'Custom progress bars', 'wproto' ),
			'description' => __('Styled progress bars', 'wproto'),
			'base' => 'wproto_progress',
			'icon' => 'wproto_progress',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'textarea',
    			'heading' => __('Graphic values', 'wproto'),
    			'param_name' => 'values',
    			'value' => "90|Development\r\n80|Design\r\n70|Marketing",
    			'description' => __('Input graph values here. Divide values with linebreaks (Enter). Example: 90|Development', 'wproto')
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Units', 'wproto'),
    			'param_name' => 'unit',
    			'value' => '%',
    			'description' => __('Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.', 'wproto')
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Progress bar style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
    				__('Rounded, Filled', 'wproto') => 'rounded_filled',
    				__('Rounded Gradient', 'wproto') => 'rounded_gradient',
    				__('Rounded Stripes Thin', 'wproto') => 'rounded_stripes_thin',
    				__('Rounded Stripes Thick', 'wproto') => 'rounded_stripes_thick',
    				__('Flat', 'wproto') => 'flat',
    				__('Flat Gradient', 'wproto') => 'flat_gradient',
    				__('Thermometer', 'wproto') => 'thermometer',
    				__('Colored Bar', 'wproto') => 'colored_bar',
    				__('Minimal white', 'wproto') => 'minimal_white',
    				__('Minimal dark', 'wproto') => 'minimal_dark',
					),
 					'group' => __('Style', 'wproto')
   			),
			)
		));
		
		/**
	 	 * Testimonials shortcode
	 	 **/
		vc_map( array(
			'name' => __( 'Testimonials', 'wproto' ),
			'description' => __('From Happy Clients', 'wproto'),
			'base' => 'wproto_testimonials',
			'icon' => 'wproto_testimonials',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
   			array(
   				'type' => 'textfield',
    			'heading' => __('Posts count', 'wproto'),
    			'param_name' => 'count',
    			'value' => 5,
   			),
   			array(
   				'type' => 'exploded_textarea',
    			'heading' => __('Display testimonials from category', 'wproto'),
    			'param_name' => 'category',
    			'value' => '',
 					'description' => __('Type here category slugs and separate multiple slugs by comma, e.g.: my-category,fresh-portfolio. Leave it blank to get posts from any category.', 'wproto')
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Order by', 'wproto'),
    			'param_name' => 'order_by',
    			'value' => $order,
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Sort by', 'wproto'),
    			'param_name' => 'sort',
    			'value' => $sort,
			
   			),
				array(
					'type' => 'checkbox',
					'heading' => __('Autoplay (only for carousel type)', 'wproto'),
					'param_name' => 'autoplay',
					'value' => array(
						'' => 'false'
					),
				),
				array(
				'type' => 'textfield',
				'heading' => __('Autoplay speed', 'wproto'),
				'param_name' => 'autoplay_speed',
				'value' => '5000',
				'description' => __('In milliseconds, e.g.: 5000', 'wproto'),
				'dependency' => array(
					'element' => 'autoplay',
					'value' => array( 1 ),
					'not_empty' => true
				)),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Boxed', 'wproto') => 'boxed',
						__('Boxed Alt', 'wproto') => 'boxed_alt',
						__('Minimal', 'wproto') => 'minimal',
						__('Dark', 'wproto') => 'dark',
						__('Dark Alt', 'wproto') => 'dark_alt',
						__('Single Carousel (for white backgrounds)', 'wproto') => 'single_carousel_white',
						__('Single Dark Carousel (for dark backgrounds)', 'wproto') => 'single_carousel_dark',
						__('Widget (style 1)', 'wproto') => 'widget_1',
						__('Widget (style 2)', 'wproto') => 'widget_2',
						__('Widget (style 3)', 'wproto') => 'widget_3',
						__('Widget (style 4)', 'wproto') => 'widget_4',
						__('Widget (style 5)', 'wproto') => 'widget_5',
						__('Widget (style 6)', 'wproto') => 'widget_6',
						__('Widget (style 7)', 'wproto') => 'widget_7',
						__('Widget (style 8)', 'wproto') => 'widget_8',
					),
   			),
			)
		));
		
		/**
	 	 * Benefits
	   **/
		vc_map( array(
			'name' => __( 'Benefits', 'wproto' ),
			'description' => __('Why we better than', 'wproto'),
			'base' => 'wproto_benefits',
			'icon' => 'wproto_benefits',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
   			array(
   				'type' => 'textfield',
    			'heading' => __('Posts count', 'wproto'),
    			'param_name' => 'count',
    			'value' => 4,
   			),
   			array(
   				'type' => 'exploded_textarea',
    			'heading' => __('Display testimonials from category', 'wproto'),
    			'param_name' => 'category',
    			'value' => '',
 					'description' => __('Type here category slugs and separate multiple slugs by comma, e.g.: my-category,fresh-portfolio. Leave it blank to get posts from any category.', 'wproto')
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Order by', 'wproto'),
    			'param_name' => 'order_by',
    			'value' => $order,
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Sort by', 'wproto'),
    			'param_name' => 'sort',
    			'value' => $sort,
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Rounded', 'wproto') => 'rounded',
						__('Rounded inverted', 'wproto') => 'rounded_inverted',
						__('Rounded medium left', 'wproto') => 'rounded_medium_left',
						__('Rounded small left', 'wproto') => 'rounded_small_left',
						__('For dark backgrounds (style 1)', 'wproto') => 'dark_style_1',
						__('For dark backgrounds (style 2)', 'wproto') => 'dark_style_2',
						__('For dark backgrounds (style 3)', 'wproto') => 'dark_style_3',
						__('Boxed', 'wproto') => 'boxed',				
						__('Gradient', 'wproto') => 'gradient',
						__('Rounded small left light', 'wproto') => 'rounded_small_left_light',
						__('Rounded small right light', 'wproto') => 'rounded_small_right_light',		
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Columns', 'wproto'),
    			'param_name' => 'columns',
    			'value' => array(
    				__('Four', 'wproto') => 'col-md-3',		
    				__('Three', 'wproto') => 'col-md-4',
    				__('Two', 'wproto') => 'col-md-6',
						__('One', 'wproto') => 'col-md-12',			
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Links target', 'wproto'),
    			'param_name' => 'target',
    			'value' => array(
    				__('Same page', 'wproto') => '_self',		
    				__('New tag', 'wproto') => '_blank',			
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'textfield',
    			'heading' => __('Minimum height of elements', 'wproto'),
    			'param_name' => 'min_height',
    			'value' => '',
    			'group' => __('Style', 'wproto'),
   			),
			)
		));
		
		/**
	 	 * Pricing tables
	   **/
		vc_map( array(
			'name' => __( 'Pricing tables', 'wproto' ),
			'description' => __('Responsive table', 'wproto'),
			'base' => 'wproto_pricing_tables',
			'icon' => 'wproto_pricing_tables',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'wproto_pricing_tables_picker',
    			'heading' => __('Choose a table', 'wproto'),
    			'param_name' => 'table_id',
    			'value' => '',
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Style 1', 'wproto') => 'style_1',
						__('Style 2', 'wproto') => 'style_2',
						__('Style 3', 'wproto') => 'style_3',
						__('Style 4', 'wproto') => 'style_4',
						__('Style 5', 'wproto') => 'style_5',
						__('Style 6', 'wproto') => 'style_6',
						__('Style 7', 'wproto') => 'style_7',
						__('Style 8', 'wproto') => 'style_8',				
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Columns', 'wproto'),
    			'param_name' => 'columns',
    			'value' => array(
    				__('Four', 'wproto') => 'col-md-3',
						__('Three', 'wproto') => 'col-md-4',
						__('Two', 'wproto') => 'col-md-6',	
						__('One', 'wproto') => 'col-md-12',					
					),
   			),
			)
		));
		
		/**
	 	 * Contact information
	   **/
		vc_map( array(
			'name' => __( 'Contact information', 'wproto' ),
			'description' => __('Address, phones and email', 'wproto'),
			'base' => 'wproto_contact_information',
			'icon' => 'wproto_contact_information',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Header text (optional)', 'wproto'),
    			'param_name' => 'title',
    			'value' => '',
   			),
				array(
 					'type' => 'textarea',
    			'heading' => __('Text after header (optional)', 'wproto'),
    			'param_name' => 'subtext',
    			'value' => '',
   			),
				array(
 					'type' => 'textarea',
    			'heading' => __('Address', 'wproto'),
    			'param_name' => 'address',
    			'value' => '',
   			),
				array(
 					'type' => 'textarea',
    			'heading' => __('Phones', 'wproto'),
    			'param_name' => 'phone',
    			'value' => '',
   			),
				array(
 					'type' => 'textarea',
    			'heading' => __('Emails', 'wproto'),
    			'param_name' => 'email',
    			'value' => '',
   			),
				array(
 					'type' => 'checkbox',
    			'heading' => __('Display social icons', 'wproto'),
    			'param_name' => 'display_social_icons',
    			'value' => array( __('Yes', 'wproto') => true ),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Block style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
    				__('Simple', 'wproto') => '',
						__('For backgrounds', 'wproto') => 'modern',			
					),
 					'group' => __('Style', 'wproto')
   			),
 			)
		));
		
		/**
	 	 * Latest tweets
	   **/
		vc_map( array(
			'name' => __( 'Latest Tweets', 'wproto' ),
			'description' => __('Displays few record from Twitter', 'wproto'),
			'base' => 'wproto_tweets',
			'icon' => 'wproto_tweets',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Tweets count', 'wproto'),
    			'param_name' => 'count',
    			'value' => 5,
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Default', 'wproto') => 'default',
						__('Widget', 'wproto') => 'widget',			
					),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Custom color (text, controls)', 'wproto'),
    			'param_name' => 'custom_color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('default')
					),
   			),
			)
		));
		
		/**
	 	 * Login / SignUp form
	   **/
		vc_map( array(
			'name' => __( 'Login / Signup Form', 'wproto' ),
			'description' => __('Allows to login / register', 'wproto'),
			'base' => 'wproto_login_signup',
			'icon' => 'wproto_login_signup',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
					'type' => 'checkbox',
					'heading' => __('Allow user to register?', 'wproto'),
					'description' => __('Important! User registration should be enabled in your WordPress settings, see General Settings screen', 'wproto'),
					'param_name' => 'allow_register',
					'value' => array(
						'' => 'false'
					),
				),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Widget Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Style', 'wproto') . ' 1' => 'style_1',
						__('Style', 'wproto') . ' 2' => 'style_2',
						__('Style', 'wproto') . ' 3' => 'style_3',
						__('Style', 'wproto') . ' 4' => 'style_4',
						__('Style', 'wproto') . ' 5' => 'style_5',
						__('Style', 'wproto') . ' 6' => 'style_6',
						__('Style', 'wproto') . ' 7' => 'style_7',
						__('Style', 'wproto') . ' 8' => 'style_8',		
					),
   			),
			)
		));
		
		/**
	 	 * Facts in digits
	   **/
		vc_map( array(
			'name' => __( 'Fact in digits', 'wproto' ),
			'description' => __('Displays some of your statistic', 'wproto'),
			'base' => 'wproto_facts_in_digits',
			'icon' => 'wproto_facts_in_digits',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Title', 'wproto'),
    			'param_name' => 'title',
    			'value' => '',
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Number', 'wproto'),
    			'param_name' => 'count',
    			'value' => '',
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Units', 'wproto'),
    			'param_name' => 'unit',
    			'value' => '',
					'dependency' => array(
						'element' => 'style',
						'value' => array('style_1','style_2','style_3','style_4')
					),
    			'description' => __('Enter units if needed, eg. %', 'wproto')
   			),
				array(
 					'type' => 'textarea',
    			'heading' => __('Short description', 'wproto'),
    			'param_name' => 'description',
    			'value' => '',
   			),
				array(
 					'type' => 'wproto_icon_picker',
    			'heading' => __('Icon', 'wproto'),
    			'param_name' => 'icon',
    			'value' => '',
					'dependency' => array(
						'element' => 'style',
						'value' => array('style_5', 'style_6')
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Circle, for white backgrounds', 'wproto') => 'style_1',
						__('Circle gradient', 'wproto') => 'style_2',
						__('Circle for colorful backgrounds', 'wproto') => 'style_3',
						__('Circle for images', 'wproto') => 'style_4',
						__('Facts in digits, iconic, style 1', 'wproto') => 'style_5',
						__('Facts in digits, iconic, style 2', 'wproto') => 'style_6',
					),
 					'group' => __('Style', 'wproto')
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Progress fill color', 'wproto'),
    			'value' => '#e5493a',
    			'param_name' => 'color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('style_1', 'style_3')
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Gradient color #1', 'wproto'),
    			'value' => '#8cc640',
    			'param_name' => 'grad_color',
					'dependency' => array(
						'element' => 'style',
						'value' => array('style_2')
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Gradient color #2', 'wproto'),
    			'value' => '#1cbbb2',
    			'param_name' => 'grad_color_alt',
					'dependency' => array(
						'element' => 'style',
						'value' => array('style_2')
					),
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Title color', 'wproto'),
    			'value' => '',
    			'param_name' => 'title_color',
 					'group' => __('Style', 'wproto'),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Icon color', 'wproto'),
    			'value' => '',
    			'param_name' => 'icon_color',
 					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('style_5', 'style_6')
					),
   			),
   			array(
   				'type' => 'colorpicker',
    			'heading' => __('Description color', 'wproto'),
    			'value' => '',
    			'param_name' => 'text_color',
 					'group' => __('Style', 'wproto'),
   			),
			)
		));
		
		/**
	 	 * Our team
	   **/
		vc_map( array(
			'name' => __( 'Staff', 'wproto' ),
			'description' => __('Displays information about your team / stuff', 'wproto'),
			'base' => 'wproto_staff',
			'icon' => 'wproto_staff',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
   			array(
   				'type' => 'textfield',
    			'heading' => __('Posts count', 'wproto'),
    			'param_name' => 'count',
    			'value' => 4,
   			),
   			array(
   				'type' => 'exploded_textarea',
    			'heading' => __('Display testimonials from category', 'wproto'),
    			'param_name' => 'category',
    			'value' => '',
 					'description' => __('Type here category slugs and separate multiple slugs by comma, e.g.: my-category,featured-staff. Leave it blank to get posts from any category.', 'wproto')
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Order by', 'wproto'),
    			'param_name' => 'order_by',
    			'value' => $order,
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Sort by', 'wproto'),
    			'param_name' => 'sort',
    			'value' => $sort,
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Grid', 'wproto') => 'grid',
						__('Carousel', 'wproto') => 'carousel',					
					),
 					'group' => __('Style', 'wproto')
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Carousel title', 'wproto'),
    			'param_name' => 'carousel_title',
    			'value' => '',
    			'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'carousel' )
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Columns', 'wproto'),
    			'param_name' => 'columns',
    			'value' => array(
    				__('Four', 'wproto') => 'col-md-3',
    				__('Three', 'wproto') => 'col-md-4',
    				__('Two', 'wproto') => 'col-md-6',
						__('One', 'wproto') => 'col-md-12',						
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'grid' )
					),
 					'group' => __('Style', 'wproto')
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Visible elements', 'wproto'),
    			'param_name' => 'items',
    			'value' => array(
    				__('Four', 'wproto') => 4,
    				__('Three', 'wproto') => 3,
    				__('Two', 'wproto') => 2,
						__('One', 'wproto') => 1,						
					),
 					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'carousel' )
					),
   			),
				array(
					'type' => 'checkbox',
					'heading' => __('Navigation links', 'wproto'),
					'param_name' => 'nav',
					'value' => array(
						'' => 'false'
					),
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'carousel' )
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Autoplay', 'wproto'),
					'param_name' => 'autoplay',
					'value' => array(
						'' => 'false'
					),
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'carousel' )
					),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Autoplay speed', 'wproto'),
					'param_name' => 'autoplay_speed',
					'value' => '5000',
					'description' => __('In milliseconds, e.g.: 5000', 'wproto'),
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'autoplay',
						'value' => array( 1 ),
						'not_empty' => true
					),
				)
			)
		));

		/**
	 	 * Google Map
	   **/
		vc_map( array(
			'name' => __( 'Google Map', 'wproto' ),
			'description' => __('Customized style', 'wproto'),
			'base' => 'wproto_google_map',
			'icon' => 'wproto_google_map',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Address', 'wproto'),
    			'param_name' => 'address',
    			'value' => '',
    			'description' => __('If you want to use multiple pointers at Google Map, divide your addresses with "|" character, e.g.: Address 1|Address 2|Address 3 etc.', 'wproto')
   			),
				array(
 					'type' => 'dropdown',
    			'heading' => __('Map mode', 'wproto'),
    			'param_name' => 'map_mode',
    			'value' => array(
						__('Road Map', 'wproto') => 'ROADMAP',
						__('Satellite', 'wproto') => 'SATELLITE',
						__('Custom colors', 'wproto') => 'custom_colors'
					),
   			),
				array(
 					'type' => 'dropdown',
    			'heading' => __('Map zoom', 'wproto'),
    			'param_name' => 'map_zoom',
    			'value' => array(
						__('One', 'wproto') => 1,
						__('Two', 'wproto') => 2,
						__('Three', 'wproto') => 3,
						__('Four', 'wproto') => 4,
						__('Five', 'wproto') => 5,
						__('Six', 'wproto') => 6,
						__('Seven', 'wproto') => 7,
						__('Eight', 'wproto') => 8,
						__('Nine', 'wproto') => 9,
						__('Ten', 'wproto') => 10,
						__('Eleven', 'wproto') => 11,
						__('Twelve', 'wproto') => 12,
						__('Thirteen', 'wproto') => 13,
						__('Fourteen', 'wproto') => 14,
						__('Fifteen', 'wproto') => 15
					),
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Map height', 'wproto'),
    			'param_name' => 'height',
    			'value' => '35',
    			'description' => __('in percents %', 'wproto'),
    			'group' => __('Style', 'wproto'),
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Minimum height', 'wproto'),
    			'param_name' => 'min_height',
    			'value' => '',
    			'description' => __('in pixels (px)', 'wproto'),
    			'group' => __('Style', 'wproto'),
   			),
				array(
 					'type' => 'attach_image',
    			'heading' => __('Custom pointer on the map', 'wproto'),
    			'param_name' => 'pointer_image',
    			'value' => '',
    			'group' => __('Style', 'wproto'),
   			),
				array(
 					'type' => 'checkbox',
    			'heading' => __('Map draggable', 'wproto'),
    			'param_name' => 'draggable',
    			'value' => array( __('Yes', 'wproto') => true ),
    			'group' => __('Style', 'wproto'),
   			)
			)
		));
		
		/**
	 	 * Latest tweets
	   **/
		vc_map( array(
			'name' => __( 'Latest tweets', 'wproto' ),
			'description' => __('Displays a carousel of your latest tweets', 'wproto'),
			'base' => 'wproto_tweets',
			'icon' => 'wproto_tweets',
			'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Tweets count', 'wproto'),
    			'param_name' => 'count',
    			'value' => '5',
   			),
				array(
					'type' => 'checkbox',
					'heading' => __('Autoplay', 'wproto'),
					'param_name' => 'autoplay',
					'group' => __('Style', 'wproto'),
					'value' => array(
						'' => 'false'
					),
				),
				array(
				'type' => 'textfield',
				'heading' => __('Autoplay speed', 'wproto'),
				'param_name' => 'autoplay_speed',
				'value' => '',
				'group' => __('Style', 'wproto'),
				'description' => __('In milliseconds, e.g.: 1800', 'wproto'),
				'dependency' => array(
					'element' => 'autoplay',
					'value' => array( 1 ),
					'not_empty' => true
				)),
				array(
 					'type' => 'dropdown',
   				'holder' => 'div',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Default', 'wproto') => 'default',
						__('Alternate', 'wproto') => 'alt',
						__('Third', 'wproto') => 'third',
						__('Fourth', 'wproto') => 'fourth',
					),
    			'group' => __('Style', 'wproto')
   			),
 			),
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' )
		));
		
		/**
	 	 * Logos carousel
	   **/
		vc_map( array(
			'name' => __( 'Partners / Clients', 'wproto' ),
			'description' => __('Logos carousel', 'wproto'),
			'base' => 'wproto_partners_clients',
			'icon' => 'wproto_partners_clients',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
   			array(
   				'type' => 'textfield',
    			'heading' => __('Posts count', 'wproto'),
    			'param_name' => 'count',
    			'value' => 5,
   			),
   			array(
   				'type' => 'textfield',
    			'heading' => __('Carousel visible items', 'wproto'),
    			'param_name' => 'items',
    			'value' => 1,
   			),
				array(
 					'type' => 'exploded_textarea',
    			'heading' => __('Display from categories', 'wproto'),
    			'param_name' => 'categories',
    			'value' => '',
    			'description' => __('Type here category slugs to include or exclude posts. Explode multiple categories slugs by comma', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Order by', 'wproto'),
    			'param_name' => 'order_by',
    			'value' => $order,
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Sort by', 'wproto'),
    			'param_name' => 'sort',
    			'value' => $sort,
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Display navigation', 'wproto'),
    			'param_name' => 'nav',
    			'value' => array(
						__('Yes', 'wproto') => 1,
						__('No', 'wproto') => 0				
					),
   			),
				array(
					'type' => 'checkbox',
					'heading' => __('Carousel Autoplay', 'wproto'),
					'param_name' => 'autoplay',
					'value' => array(
						'' => 'false'
					),
				),
				array(
				'type' => 'textfield',
				'heading' => __('Autoplay speed', 'wproto'),
				'param_name' => 'autoplay_speed',
				'value' => '5000',
				'description' => __('In milliseconds, e.g.: 5000', 'wproto'),
				'dependency' => array(
					'element' => 'autoplay',
					'value' => array( 1 ),
					'not_empty' => true
				)),
				array(
 					'type' => 'dropdown',
   				'holder' => 'div',
    			'heading' => __('Open custom link at', 'wproto'),
    			'param_name' => 'target',
    			'value' => array(
						__('New tab (target = _blank)', 'wproto') => '_blank',
						__('Same window (target = _self)', 'wproto') => '_self',
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Display title', 'wproto'),
    			'param_name' => 'display_title',
    			'value' => array(
						__('No', 'wproto') => 0,
						__('Yes', 'wproto') => 1				
					),
   			),
			)
		));
		
		/**
	 	 * Posts carousel
	   **/
		vc_map( array(
			'name' => __( 'Posts Carousel', 'wproto' ),
			'description' => __('Displays Blog posts or Portfolio works', 'wproto'),
			'base' => 'wproto_posts_carousel',
			'icon' => 'wproto_posts_carousel',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Squares, Infinite', 'wproto') => 'style_1',
						__('Rectangles, Infinite', 'wproto') => 'style_2',
						__('Rectangles Alternate, Infinite', 'wproto') => 'style_3',
						__('Blocks style 1', 'wproto') => 'style_4',
						__('Blocks style 2', 'wproto') => 'style_5',
						__('Blocks style 3', 'wproto') => 'style_6',
						__('Blocks style 4', 'wproto') => 'style_7',
					),
 					'group' => __('Style', 'wproto'),
   			),
				array(
					'type' => 'textfield',
					'heading' => __('"Read more" link text', 'wproto'),
					'admin_label' => true,
					'param_name' => 'read_more_text',
					'value' => __('Read more', 'wproto'),
					'description' => __('Leave it blank to hide "Read more" link', 'wproto'),
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('style_4', 'style_5', 'style_7')
					),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Block title', 'wproto'),
					'admin_label' => true,
					'param_name' => 'block_title',
					'value' => __('Breaking news', 'wproto'),
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('style_6', 'style_7')
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Display Filters', 'wproto'),
					'param_name' => 'display_filters',
					'value' => array(
						'' => 'true'
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array('style_1', 'style_2', 'style_3', 'style_4')
					),
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Display Navigation links', 'wproto'),
					'param_name' => 'display_nav',
					'value' => array(
						'' => 'true'
					),
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Post type', 'wproto'),
					'param_name' => 'post_type',
					'value' => array(
						__('Blog posts', 'wproto') => 'post',
						__('Portfolio posts', 'wproto') => 'wproto_portfolio',
					),
					'group' => __('Query', 'wproto'),
				),
				array(
 					'type' => 'textfield',
    			'heading' => __('Posts count', 'wproto'),
    			'param_name' => 'posts_limit',
    			'value' => '10',
    			'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'exploded_textarea',
    			'heading' => __('Author ID', 'wproto'),
    			'param_name' => 'author_id',
    			'value' => '',
    			'description' => __('Explode multiple authors by comma', 'wproto'),
    			'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Order by', 'wproto'),
    			'param_name' => 'order_by',
    			'value' => $order,
 					'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Sort by', 'wproto'),
    			'param_name' => 'sort_by',
    			'value' => $sort,
 					'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'checkbox',
    			'heading' => __('Features only?', 'wproto'),
    			'param_name' => 'featured',
    			'value' => array( __('Yes', 'wproto') => 1 ),
    			'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Query from category', 'wproto'),
    			'param_name' => 'cat_query_type',
    			'value' => array(
    				__('All', 'wproto') => '',
    				__('Only', 'wproto') => 'only',
    				__('Except', 'wproto') => 'except',
					),
 					'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'exploded_textarea',
    			'heading' => __('Categories', 'wproto'),
    			'param_name' => 'categories',
    			'value' => '',
    			'description' => __('Type here category slugs to include or exclude, based on previous parameter. Explode multiple categories slugs by comma', 'wproto'),
    			'group' => __('Query', 'wproto'),
					'dependency' => array(
						'element' => 'cat_query_type',
						'not_empty' => true
					),
   			),
			)
		));

		/**
	 	 * Products carousel
	   **/
		vc_map( array(
			'name' => __( 'Products Carousel', 'wproto' ),
			'description' => __('WooCommerce products carousel', 'wproto'),
			'base' => 'wproto_product_carousel',
			'icon' => 'wproto_product_carousel',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Query type', 'wproto'),
    			'param_name' => 'type',
    			'value' => array(
						__('Featured Products', 'wproto') => 'featured',
						__('On Sale', 'wproto') => 'sales',
						__('New Arrivals', 'wproto') => 'latest',
						__('Best Sellers', 'wproto') => 'best_sellers',
						__('Top Rated', 'wproto') => 'top_rated',
						__('Random Products', 'wproto') => 'random',
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => array(
						__('Style 1', 'wproto') => 'style_1',
						__('Style 2', 'wproto') => 'style_2',
						__('Style 3', 'wproto') => 'style_3',
						__('Style 4', 'wproto') => 'style_4',
						__('Style 5 (recommend for 3 items)', 'wproto') => 'style_5',
					),
   			),
				array(
					'type' => 'checkbox',
					'heading' => __('Display Navigation arrows', 'wproto'),
					'param_name' => 'display_nav_arrows',
					'value' => array(
						'' => 'true'
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Display Navigation bullets', 'wproto'),
					'param_name' => 'display_nav_bullets',
					'value' => array(
						'' => 'true'
					),
				),
				array(
 					'type' => 'textfield',
    			'heading' => __('Products count', 'wproto'),
    			'param_name' => 'count',
    			'value' => '8',
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Visible items (desktop)', 'wproto'),
    			'param_name' => 'visible_desktop',
    			'value' => '4',
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Visible items (tablets)', 'wproto'),
    			'param_name' => 'visible_small_desktop',
    			'value' => '3',
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Visible items (phone landscape)', 'wproto'),
    			'param_name' => 'visible_phone_landscape',
    			'value' => '2',
   			),
				array(
 					'type' => 'textfield',
    			'heading' => __('Visible items (phone)', 'wproto'),
    			'param_name' => 'visible_phone',
    			'value' => '1',
   			),
			)
		));

		/**
	 	 * Intro section
	   **/
		vc_map( array(
			'name' => __( 'Intro Section', 'wproto' ),
			'description' => __('Latest or featured posts', 'wproto'),
			'base' => 'wproto_intro_section',
			'icon' => 'wproto_intro_section',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => __('Post type', 'wproto'),
					'param_name' => 'post_type',
					'value' => array(
						__('Blog posts', 'wproto') => 'post',
						__('Portfolio posts', 'wproto') => 'wproto_portfolio',
					),
					'group' => __('Query', 'wproto'),
				),
				array(
 					'type' => 'exploded_textarea',
    			'heading' => __('Author ID', 'wproto'),
    			'param_name' => 'author_id',
    			'value' => '',
    			'description' => __('Explode multiple authors by comma', 'wproto'),
    			'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Order by', 'wproto'),
    			'param_name' => 'order_by',
    			'value' => $order,
 					'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Sort by', 'wproto'),
    			'param_name' => 'sort_by',
    			'value' => $sort,
 					'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'checkbox',
    			'heading' => __('Features only?', 'wproto'),
    			'param_name' => 'featured',
    			'value' => array( __('Yes', 'wproto') => 1 ),
    			'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Query from category', 'wproto'),
    			'param_name' => 'cat_query_type',
    			'value' => array(
    				__('All', 'wproto') => '',
    				__('Only', 'wproto') => 'only',
    				__('Except', 'wproto') => 'except',
					),
 					'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'exploded_textarea',
    			'heading' => __('Categories', 'wproto'),
    			'param_name' => 'categories',
    			'value' => '',
    			'description' => __('Type here category slugs to include or exclude, based on previous parameter. Explode multiple categories slugs by comma', 'wproto'),
    			'group' => __('Query', 'wproto'),
					'dependency' => array(
						'element' => 'cat_query_type',
						'not_empty' => true
					),
   			),
				array(
					'type' => 'textfield',
					'heading' => __('Block title', 'wproto'),
					'param_name' => 'title',
					'value' => __('Latest Blog Posts', 'wproto'),
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('"Read more" link title', 'wproto'),
					'param_name' => 'read_more_text',
					'value' => __('Read more &raquo;', 'wproto'),
					'group' => __('Style', 'wproto'),
				),
				array(
 					'type' => 'checkbox',
    			'heading' => __('Display "Read all posts" link?', 'wproto'),
    			'param_name' => 'display_read_all',
    			'value' => array( __('Yes', 'wproto') => 1 ),
    			'group' => __('Style', 'wproto'),
   			),
				array(
					'type' => 'textfield',
					'heading' => __('"Read all posts" link title', 'wproto'),
					'param_name' => 'read_all_text',
					'value' => '',
					'dependency' => array(
						'element' => 'display_read_all',
						'value' => array( 1 ),
						'not_empty' => true
					),
					'group' => __('Style', 'wproto'),
				),
			)
		));

		/**
	 	 * Blog posts
	   **/
		vc_map( array(
			'name' => __( 'Blog Posts', 'wproto' ),
			'description' => __('Grid of blog posts', 'wproto'),
			'base' => 'wproto_blog',
			'icon' => 'wproto_blog',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Posts per page', 'wproto'),
    			'param_name' => 'posts_per_page',
    			'value' => '10',
    			'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'exploded_textarea',
    			'heading' => __('Author ID', 'wproto'),
    			'param_name' => 'author_id',
    			'value' => '',
    			'description' => __('Explode multiple authors by comma', 'wproto'),
    			'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Order by', 'wproto'),
    			'param_name' => 'order_by',
    			'value' => $order,
 					'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Sort by', 'wproto'),
    			'param_name' => 'sort_by',
    			'value' => $sort,
 					'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'checkbox',
    			'heading' => __('Features only?', 'wproto'),
    			'param_name' => 'featured',
    			'value' => array( __('Yes', 'wproto') => 1 ),
    			'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Query from category', 'wproto'),
    			'param_name' => 'cat_query_type',
    			'value' => array(
    				__('All', 'wproto') => '',
    				__('Only', 'wproto') => 'only',
    				__('Except', 'wproto') => 'except',
					),
 					'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'exploded_textarea',
    			'heading' => __('Categories', 'wproto'),
    			'param_name' => 'categories',
    			'value' => '',
    			'description' => __('Type here category slugs to include or exclude, based on previous parameter. Explode multiple categories slugs by comma', 'wproto'),
    			'group' => __('Query', 'wproto'),
					'dependency' => array(
						'element' => 'cat_query_type',
						'not_empty' => true
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => $this->blog_styles,
 					'group' => __('Style', 'wproto'),
   			),
				array(
					'type' => 'textfield',
					'heading' => __('"Read more" text', 'wproto'),
					'admin_label' => true,
					'param_name' => 'read_more_text',
					'value' => __('Read more', 'wproto'),
					'description' => __('Leave it blank to hide "Read more" button', 'wproto'),
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Display Pagination', 'wproto'),
					'param_name' => 'display_pagination',
					'value' => array(
						'' => 'true'
					),
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Pagination style', 'wproto'),
					'param_name' => 'pagination_style',
					'value' => $this->pagination_styles,
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'display_pagination',
						'value' => array( 'true' ),
						'not_empty' => true
					),
				),
			)
		));
		
		/**
	 	 * Portfolio posts
	   **/
		vc_map( array(
			'name' => __( 'Portfolio Posts', 'wproto' ),
			'description' => __('Grid of portfolio posts', 'wproto'),
			'base' => 'wproto_portfolio',
			'icon' => 'wproto_portfolio',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Posts per page', 'wproto'),
    			'param_name' => 'posts_per_page',
    			'value' => '10',
    			'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'exploded_textarea',
    			'heading' => __('Author ID', 'wproto'),
    			'param_name' => 'author_id',
    			'value' => '',
    			'description' => __('Explode multiple authors by comma', 'wproto'),
    			'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Order by', 'wproto'),
    			'param_name' => 'order_by',
    			'value' => $order,
 					'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Sort by', 'wproto'),
    			'param_name' => 'sort_by',
    			'value' => $sort,
 					'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'checkbox',
    			'heading' => __('Features only?', 'wproto'),
    			'param_name' => 'featured',
    			'value' => array( __('Yes', 'wproto') => 1 ),
    			'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Query from category', 'wproto'),
    			'param_name' => 'cat_query_type',
    			'value' => array(
    				__('All', 'wproto') => '',
    				__('Only', 'wproto') => 'only',
    				__('Except', 'wproto') => 'except',
					),
 					'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'exploded_textarea',
    			'heading' => __('Categories', 'wproto'),
    			'param_name' => 'categories',
    			'value' => '',
    			'description' => __('Type here category slugs to include or exclude, based on previous parameter. Explode multiple categories slugs by comma', 'wproto'),
    			'group' => __('Query', 'wproto'),
					'dependency' => array(
						'element' => 'cat_query_type',
						'not_empty' => true
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => $this->portfolio_styles,
 					'group' => __('Style', 'wproto'),
   			),
				array(
					'type' => 'textfield',
					'heading' => __('"Read more" text', 'wproto'),
					'admin_label' => true,
					'param_name' => 'read_more_text',
					'value' => __('Read more', 'wproto'),
					'description' => __('Leave it blank to hide "Read more" button', 'wproto'),
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'cols_1_default', 'cols_1_alt' )
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Display Pagination', 'wproto'),
					'param_name' => 'display_pagination',
					'value' => array(
						'' => 'true'
					),
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Pagination style', 'wproto'),
					'param_name' => 'pagination_style',
					'value' => $this->pagination_styles,
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'display_pagination',
						'value' => array( 'true' ),
						'not_empty' => true
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Display Filters', 'wproto'),
					'param_name' => 'display_filters',
					'value' => array(
						'' => 'true'
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'full_width', 'full_width_alt', 'full_width_third', 'cols_2_masonry', 'cols_2_masonry_no_gap', 'cols_2_masonry_with_desc', 'cols_3_masonry', 'cols_3_masonry_no_gap', 'cols_3_masonry_with_desc', 'cols_4_masonry', 'cols_4_masonry_no_gap', 'cols_4_masonry_with_desc' )
					),
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Display Sort Filters', 'wproto'),
					'param_name' => 'display_sort_filters',
					'value' => array(
						'' => 'true'
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'cols_2_masonry', 'cols_2_masonry_no_gap', 'cols_2_masonry_with_desc', 'cols_3_masonry', 'cols_3_masonry_no_gap', 'cols_3_masonry_with_desc', 'cols_4_masonry', 'cols_4_masonry_no_gap', 'cols_4_masonry_with_desc' )
					),
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'checkbox',
					'heading' => __('Allow to switch between columns', 'wproto'),
					'param_name' => 'display_view_switcher',
					'value' => array(
						'' => 'true'
					),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'cols_2_masonry', 'cols_2_masonry_no_gap', 'cols_2_masonry_with_desc', 'cols_3_masonry', 'cols_3_masonry_no_gap', 'cols_3_masonry_with_desc', 'cols_4_masonry', 'cols_4_masonry_no_gap', 'cols_4_masonry_with_desc' )
					),
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Block title', 'wproto'),
					'admin_label' => true,
					'param_name' => 'block_title',
					'value' => '',
					'description' => __('See our creative projects', 'wproto'),
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'style',
						'value' => array( 'full_width', 'full_width_alt', 'full_width_third' )
					),
				),
			)
		));
		
		/**
	 	 * Products
	   **/
		vc_map( array(
			'name' => __( 'Shop Products', 'wproto' ),
			'description' => __('Grid of WooCommerce products', 'wproto'),
			'base' => 'wproto_shop_product',
			'icon' => 'wproto_shop_product',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
 					'type' => 'textfield',
    			'heading' => __('Posts per page', 'wproto'),
    			'param_name' => 'posts_per_page',
    			'value' => '9',
    			'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Order by', 'wproto'),
    			'param_name' => 'order_by',
    			'value' => $order,
 					'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Sort by', 'wproto'),
    			'param_name' => 'sort_by',
    			'value' => $sort,
 					'group' => __('Query', 'wproto'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Query from category', 'wproto'),
    			'param_name' => 'cat_query_type',
    			'value' => array(
    				__('All', 'wproto') => '',
    				__('Only', 'wproto') => 'only',
    				__('Except', 'wproto') => 'except',
					),
 					'group' => __('Query', 'wproto'),
   			),
				array(
 					'type' => 'exploded_textarea',
    			'heading' => __('Categories', 'wproto'),
    			'param_name' => 'categories',
    			'value' => '',
    			'description' => __('Type here category slugs to include or exclude, based on previous parameter. Explode multiple categories slugs by comma', 'wproto'),
    			'group' => __('Query', 'wproto'),
					'dependency' => array(
						'element' => 'cat_query_type',
						'not_empty' => true
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Style', 'wproto'),
    			'param_name' => 'style',
    			'value' => $this->shop_styles,
 					'group' => __('Style', 'wproto'),
   			),
				array(
					'type' => 'checkbox',
					'heading' => __('Display Pagination', 'wproto'),
					'param_name' => 'display_pagination',
					'value' => array(
						'' => 'true'
					),
					'group' => __('Style', 'wproto'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Pagination style', 'wproto'),
					'param_name' => 'pagination_style',
					'value' => $this->pagination_styles,
					'group' => __('Style', 'wproto'),
					'dependency' => array(
						'element' => 'display_pagination',
						'value' => array( 'true' ),
						'not_empty' => true
					),
				),
			)
		));
		
		/**
	 	 * Products list
	   **/
		vc_map( array(
			'name' => __( 'Products List', 'wproto' ),
			'description' => __('List of products', 'wproto'),
			'base' => 'wproto_shop_product_list',
			'icon' => 'wproto_shop_product_list',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => __('Query Type', 'wproto'),
					'param_name' => 'type',
					'value' => array(
						__('New products', 'wproto') => 'latest',
						__('On sale', 'wproto') => 'sales',
						__('Top rated', 'wproto') => 'top_rated',
						__('Random products', 'wproto') => 'random',
					),
				),
   			array(
   				'type' => 'textfield',
    			'heading' => __('Posts count', 'wproto'),
    			'param_name' => 'count',
    			'value' => 3,
   			),
   			array(
   				'type' => 'textfield',
    			'heading' => __('Block title', 'wproto'),
    			'param_name' => 'title',
    			'value' => __('New Incomes', 'wproto'),
   			),
			)
		));
		
		/**
	 	 * Brands carousel
	   **/
		vc_map( array(
			'name' => __( 'Brands Carousel', 'wproto' ),
			'description' => __('WooCommerce Brands', 'wproto'),
			'base' => 'wproto_brands',
			'icon' => 'wproto_brands',
			'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
			'params' => array(
   			array(
   				'type' => 'textfield',
    			'heading' => __('Carousel visible items', 'wproto'),
    			'param_name' => 'items',
    			'value' => 5,
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Hide empty elements', 'wproto'),
    			'param_name' => 'hide_empty',
    			'value' => array(
						__('Yes', 'wproto') => 1,
						__('No', 'wproto') => 0				
					),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Order by', 'wproto'),
    			'param_name' => 'order_by',
    			'value' => array( 'ID' => 'id', __('Count', 'wproto') => 'count', __('Name', 'wproto') => 'name', __('Slug', 'wproto') => 'slug'),
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Sort by', 'wproto'),
    			'param_name' => 'sort',
    			'value' => $sort,
   			),
   			array(
   				'type' => 'dropdown',
    			'heading' => __('Display navigation', 'wproto'),
    			'param_name' => 'nav',
    			'value' => array(
						__('Yes', 'wproto') => 1,
						__('No', 'wproto') => 0				
					),
   			),
				array(
					'type' => 'checkbox',
					'heading' => __('Carousel Autoplay', 'wproto'),
					'param_name' => 'autoplay',
					'value' => array(
						'' => 'false'
					),
				),
				array(
				'type' => 'textfield',
				'heading' => __('Autoplay speed', 'wproto'),
				'param_name' => 'autoplay_speed',
				'value' => '5000',
				'description' => __('In milliseconds, e.g.: 5000', 'wproto'),
				'dependency' => array(
					'element' => 'autoplay',
					'value' => array( 1 ),
					'not_empty' => true
				)),
			)
		));
		
		if( function_exists('ninja_forms_get_all_forms') ) {
			/**
		 	 * NinjaForms
		   **/
			vc_map( array(
				'name' => __( 'Ninja Forms', 'wproto' ),
				'description' => __('Place Ninja Form', 'wproto'),
				'base' => 'wproto_ninja_form',
				'icon' => 'icon-wpb-ninjaforms',
				'category' => __( 'Content (by WPlab.Pro)', 'wproto' ),
				'params' => array(
					array(
						'type' => 'wproto_ninja_forms_picker',
						'heading' => __( 'Select ninja form', 'wproto' ),
						'param_name' => 'id',
						'description' => __( 'Choose previously created ninja form from the drop down list.', 'wproto' ),
					),
					array(
					'type' => 'dropdown',
					'heading' => __('Form Style', 'wproto'),
					'param_name' => 'form_style',
					'value' => array(
							__('Style', 'wproto') . ' 1' => 'style_1',
							__('Style', 'wproto') . ' 2' => 'style_2',
							__('Style', 'wproto') . ' 3' => 'style_3',
							__('Style', 'wproto') . ' 4' => 'style_4',
							__('Style', 'wproto') . ' 5' => 'style_5',
							__('Style', 'wproto') . ' 6' => 'style_6',
							__('Style (for dark backgrounds)', 'wproto') . ' 7' => 'style_7',
							__('Style (for dark backgrounds)', 'wproto') . ' 8' => 'style_8',
							__('Style (for dark backgrounds)', 'wproto') . ' 9' => 'style_9',
						),
					)
			)));
		}	
 		
	}
	
}

if( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_wproto_gitem_zoom_post_links extends WPBakeryShortCode {
	}
	class WPBakeryShortCode_wproto_gitem_categories extends WPBakeryShortCode {
	}
	class WPBakeryShortCode_wproto_banner extends WPBakeryShortCode {
	}
}

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Wproto_Iconic_List_Container extends WPBakeryShortCodesContainer {
	}
	class WPBakeryShortCode_Wproto_Facts_In_Digits_Container extends WPBakeryShortCodesContainer {
	}
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Wproto_Iconic_List_Elem extends WPBakeryShortCode {
	}
	class WPBakeryShortCode_Wproto_Facts_In_Digits_List_Elem extends WPBakeryShortCode {
	}
}