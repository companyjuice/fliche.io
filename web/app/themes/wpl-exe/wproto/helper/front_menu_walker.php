<?php

class wpl_exe_wp_front_nav_menu_walker extends Walker_Nav_Menu {

  function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
		// check, whether there are children for the given ID and append it to the element with a (new) ID
		$element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);
		return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
  }
  
  function start_lvl(&$output, $depth = 0, $args = array()) {
    $indent = str_repeat("\t", $depth);
    
    $depth_str = '';
    
    if( $depth == 0 ) {
    	$depth_str = 'sub-menu';
    } else {
    	$depth_str = 'sub-menu sub-sub-l-menu';
    } 
    
    $output .= "\n$indent<ul class=\"$depth_str slideDown\">\n";
  }

	function start_el( &$output, $item, $depth = 0, $args = array(), $current_id = 0 ) {
		global $wp_query, $wpl_exe_wp;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$classes[] = 'level-' . $depth;
		
		if( $item->hasChildren ) {
			$classes[] = 'drop';
		}
		
		if( isset( $item->mega_menu_enabled ) && $item->mega_menu_enabled ) {
			$classes[] = 'item-mega-menu';
		}
		
		if( isset( $item->hide_desktop ) && $item->hide_desktop ) {
			$classes[] = 'hide-on-desktop';
		}
		
		if( isset( $item->hide_tablet ) && $item->hide_tablet ) {
			$classes[] = 'hide-on-tablet';
		}
		
		if( isset( $item->hide_phone ) && $item->hide_phone ) {
			$classes[] = 'hide-on-phone';
		}
		
		$posts_page_id = get_option('page_for_posts');
		
		if( $posts_page_id == $item->object_id ) {
			$classes[] = 'blog-menu-item';
			
			if( (isset( $wp_query->query_vars['post_type'] ) && $wp_query->query_vars['post_type'] != '') || is_404() ) {
				$classes = array_diff( $classes, array( 'current_page_parent', 'current_page_item', 'current-menu-item' ));
			}
			
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names .'>';

		$title_attr  	 = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes 	 = ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes 	.= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes 	.= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
            
		$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
		$item_url = esc_attr( $item->url ); 
		
		$href_class = 'menu-item-href';
		
		if( isset( $item->attr_title ) && $item->attr_title <> '' ) {
			$href_class .= ' show-tooltip';
		}
		
		if( isset( $item->one_page_link ) && $item->one_page_link ) {
		
			$href_class .= ' external';
			
		}
		
		$href_class = esc_attr( $href_class );
			
		if( isset( $item->dont_display_as_link ) && $item->dont_display_as_link ) {
			$item_output .= '<a data-toggle="tooltip" data-placement="left" class="' . $href_class . ' " ' . $title_attr . '>';
		} else {
			if ( $item_url != $current_url ) {
				$item_output .= '<a data-toggle="tooltip" data-placement="left" class="' . $href_class . '" '. $attributes .' ' . $title_attr . '>';
			} elseif( $depth == 1) {
				$item_output .= '<a data-toggle="tooltip" data-placement="left" class="' . $href_class . '" ' . $title_attr . '>';
			} else {
				$item_output .= '<a data-toggle="tooltip" data-placement="left" class="' . $href_class . '" ' . $title_attr . '>';
			}			
		}
		
		$item_output .= '<span class="inside-menu-item">';
		
		// menu icon
  	if( isset( $item->menu_icon ) && $item->menu_icon <> '' ) {
  		$item_output .= '<span class="menu-item-icon"><i class="' . esc_attr( $item->menu_icon ) . '"></i></span> ';
  	}

		$link_before = isset( $args->link_before ) ? $args->link_before : '';
		$link_after = isset( $args->link_after ) ? $args->link_after : '';

		$item_output .= $link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $link_after;
	
		if( $item->hasChildren || $item->mega_menu_enabled ) {
			
			if( $depth == 0 ) {
				$item_output .= '<span class="menu-item-icon-down"></span>';
			} 
			
		}
		
		$item_output .= '</span>';
	
		$item_output .= '</a>';
		
		/** Output Mega Menu **/
		if( isset( $item->mega_menu_enabled ) && $item->mega_menu_enabled ) {
			
			$mega_menu_columns = $item->mega_menu_cols;
			$mega_menu_widget_area = $item->mega_menu_sidebar;
			
			if( $mega_menu_widget_area <> '' ) {
				$item_output .= '<ul class="mega-menu sub-menu wproto-mega-menu-element cols-' . esc_attr( $mega_menu_columns ) . '"><li><div class="container"><div class="mega-menu-row">';
				
				ob_start();
				dynamic_sidebar( $mega_menu_widget_area );
				$item_output .= ob_get_clean();
				
				$item_output .= '<div class="clearfix"></div></div></div></li></ul>';	
			}
			
		}
		
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args ); 
	}
}
