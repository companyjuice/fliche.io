<?php
	/**
   * Post model
   **/
	class wpl_exe_wp_post extends wpl_exe_wp_database {                     
		/**
		 * Get items
		 * @return object
		 **/
		function get( $type, $limit = 3, $category = 0, $order = 'date', $sort = 'DESC', $post_type = 'post', $tax_name = 'category', $featured_only = false, $sticky_only = false, $with_thumbnail_only = false, $paged = 1, $term_field = 'id', $exclude_current_post = false ) {
			global $post;
			
			$category = !is_numeric( $category ) ? explode( ',', $category ) : $category;
			
			$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => $limit,
				'order' => $sort,
				'orderby' => $order,
				'paged' => $paged
			);
			
			if( $exclude_current_post ) {
				$args['post__not_in'] = isset( $post->ID ) ? array( $post->ID ) : array();
			}
			
			if( ! $sticky_only ) {
				$args['ignore_sticky_posts'] = 1;
			}
			
			if( $type == 'category' || $type == 'categories' || $type == 'only' ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $tax_name,
						'field' => $term_field,
						'terms' => $category
					)
				);
			}
			
			if( $type == 'category_except' || $type == 'all_except' || $type == 'except' ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $tax_name,
						'field' => $term_field,
						'terms' => $category,
						'operator' => 'NOT IN'
					)
				);
			}
			
			if( $featured_only ) {
				$args['meta_query'][] = array(
					'key' => 'featured',
					'value' => true
				);
			}
			
			if( $with_thumbnail_only ) {
				$args['meta_query'][] = array(
					'key' => '_thumbnail_id'
				);
			}
			
			if( $sticky_only ) {
				$args['post__in'] = get_option( 'sticky_posts' );
			}
			
			return new WP_Query( $args );
			
		}
		
		/**
		 * Get all pricing tables
		 **/
		function get_all_pricing_tables() {
			$args = array(
				'post_type' => 'wproto_pricing_table',
				'post_status' => 'publish',
				'posts_per_page' => -1
			);
			
			return new WP_Query( $args );
		}
		
		/**
		 * Get all posts
		 **/
		function get_all_posts( $post_type ) {
			global $post;
			
			$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'nopaging' => true
			);
			
			return new WP_Query( $args );
		}
		
		/**
		 * Get popular posts
		 **/
 		function get_popular_posts( $post_type, $popularity, $limit ) {
			$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => $limit,
				'order' => 'DESC',
				'ignore_sticky_posts' => true
			);
			
			switch( $popularity ) {
				case 'likes':
					$args['meta_key'] = 'wproto_likes';
					$args['orderby'] = 'meta_value_num';
				break;
				case 'views':
					$args['meta_key'] = 'wproto_views';
					$args['orderby'] = 'meta_value_num';
				break;
				case 'comments':
					$args['orderby'] = 'comment_count';
				break;
			}
			
			return new WP_Query( $args );
 		}
 		
 		/**
 		 * Get recent posts
 		 **/
		function get_recent_posts( $post_type, $limit ) {
			$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => $limit,
				'order' => 'DESC',
				'ignore_sticky_posts' => true
			);
			
			return new WP_Query( $args );
		}
		
		/**
		 * Get featured posts
		 **/
		function get_featured_posts( $post_type, $limit ) {
			$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => $limit,
				'order' => 'DESC',
				'ignore_sticky_posts' => true,
				'meta_key' => 'featured',
				'meta_value' => true
			);
			
			return new WP_Query( $args );
		}
		
		/**
		 * Get related posts
		 **/
 		function get_related_posts( $primary_post_id, $limit, $taxonomy = 'category', $with_thumbnail_only = false ) {
 			
 			$terms = wp_get_post_terms( $primary_post_id, $taxonomy );
 			
 			$response = false;
 			
			if( count( $terms ) > 0 ) {
				
				$post_type = get_post_type( $primary_post_id );
				$post_terms_ids = array();
				
				foreach( $terms as $term ) {
					$post_terms_ids[] = $term->term_id;
				}
				
				$args = array(
					'post_type' => $post_type,
					'post_status' => 'publish',
					'posts_per_page' => $limit,
					'order' => 'DESC',
					'orderby' => 'rand',
					'ignore_sticky_posts' => true,
					'post__not_in' => array( $primary_post_id ),
					'tax_query' => array(
						'relation' => 'OR',
						array(
							'taxonomy' => $taxonomy,
							'field' => 'id',
							'terms' => $post_terms_ids
						)
					)
				);
				
				if( $with_thumbnail_only ) {
					$args['meta_query'][] = array(
						'key' => '_thumbnail_id'
					);
				}
				
				$response = new WP_Query( $args );
				
			}
 			
 			return $response;
 		}
		
		/**
		 * Get random posts
		 **/
		function get_random_posts( $post_type, $limit, $with_thumbnail_only = false ) {
			$args = array(
				'post_type' => $post_type,
				'post_status' => 'publish',
				'posts_per_page' => $limit,
				'ignore_sticky_posts' => true,
				'orderby' => 'rand'
			);
			
			if( $with_thumbnail_only ) {
				$args['meta_query'][] = array(
					'key' => '_thumbnail_id'
				);
			}
			
			return new WP_Query( $args );
		}
		
		/**
		 * Search post
		 **/
		function search_post_by_title( $search, $post_type ) {

			$query = "SELECT ID, post_title FROM " . $this->wpdb->posts . "
        WHERE post_title LIKE '%$search%'
        AND post_type = '$post_type'
        AND post_status = 'publish'
        ORDER BY post_title ASC";
			
			return $this->wpdb->get_results( $query );
		}
		
		/**
		 * Return custom fields in a nice way
		 **/
		function get_post_custom( $post_id ) {
			$custom_fields = get_post_custom( $post_id );
			$return = array();
			if( is_array( $custom_fields ) && count( $custom_fields ) > 0 ) {
				foreach( $custom_fields as $k=>$v ) {
					if( $k[0] != '_' )
						$return[$k] = $v[0];
				}
			}
			return (object)$return;
		}
		
		/**
		 * Get on sale products
		 **/
		function get_onsale_products( $limit, $with_thumbnail_only = false ) {
			
			$products_ids_on_sale = wc_get_product_ids_on_sale();
			
			$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => $limit,
				'post__in' => $products_ids_on_sale
			);
			
			if( $with_thumbnail_only ) {
				$args['meta_query'][] = array(
					'key' => '_thumbnail_id'
				);
			}
			
			return new WP_Query( $args );
		}
		
		/**
		 * Get on sale products
		 **/
		function get_top_rated_products( $limit, $with_thumbnail_only = false ) {
			$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => $limit,
			);
			
			if( $with_thumbnail_only ) {
				$args['meta_query'][] = array(
					'key' => '_thumbnail_id'
				);
			}
			
			add_filter('posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses'));
			
			$products = new WP_Query( $args );
									
			remove_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );						
						
			return $products;
		}
		
		/**
		 * Get Best Sellers
		 **/
		function get_best_sellers( $limit ) {
			
			$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => $limit,
				'meta_key' => 'total_sales',
				'orderby' => 'meta_value_num',
			);
			
			return new WP_Query( $args );

		}
		
		/**
		 * Get featured products
		 **/
		function get_featured_products( $limit ) {

			$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => $limit,
				'meta_key' => '_featured',
				'meta_value' => 'yes',
			);
			
			return new WP_Query( $args );

		}
                
	}