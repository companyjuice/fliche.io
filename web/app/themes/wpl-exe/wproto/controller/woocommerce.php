<?php
/**
 * WooCommerce controller
 **/
class wpl_exe_wp_woocommerce_controller extends wpl_exe_wp_core_controller {
	
	function __construct() {
		
		if( !is_admin() ) {
			// Override search form
			add_filter( 'get_product_search_form', array( $this, 'custom_woo_search' ) );
			// Remove WooCommerce styles and scripts
			add_filter( 'woocommerce_enqueue_styles', '__return_false' );
			
			// Wrap WooCommerce pages
			remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
			add_action( 'woocommerce_before_main_content', array( $this, 'woocommerce_wrapper_start' ), 10);
			add_action( 'woocommerce_after_main_content', array( $this, 'woocommerce_wrapper_end' ), 10);
	
			// override breadcrumbs
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
			add_filter( 'woocommerce_show_page_title', '__return_false' );
			
			// custom posts per page
			add_filter( 'loop_shop_per_page', array( $this, 'woocommerce_custom_products_per_page' ) );
			
			// custom woocommerce images
			add_action( 'init', array( $this, 'setup_woocommerce' ), 1 );
	
			// wishlist
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'after_add_to_cart' ));
	
			// add like buttons
			add_action( 'woocommerce_product_meta_end', array( $this, 'woocommerce_add_like_buttons' ));
				
			// custom WooCommerce templates
			add_filter( 'template_include', array( $this, 'custom_woo_pages' ) );
			add_filter( 'woocommerce_locate_template', array( $this, 'custom_woo_templates' ), 10, 3 );
			
		}
		
		// remove PrettyPhoto, add nivoLightbox
		add_filter( 'woocommerce_single_product_image_html', array( $this, 'woocommerce_single_product_image_html'), 99, 1 ); // single image
		add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'woocommerce_single_product_image_html'), 99, 1); // thumbnails
		
		// add Brands
		add_action( 'init', array( $this, 'register_taxonomies'), 5);
		
		if( is_admin() ) {
			add_action( 'product_brand_add_form_fields', array( $this, 'add_brand_fields' ) );
			add_action( 'product_brand_edit_form_fields', array( $this, 'edit_brand_fields' ), 10 );
			add_action( 'created_term', array( $this, 'save_brand_fields'), 10, 3 );
			add_action( 'edit_term', array( $this, 'save_brand_fields'), 10, 3 );
			// Add columns
			add_filter( 'manage_edit-product_brand_columns', array( $this, 'product_brand_columns' ) );
			add_filter( 'manage_product_brand_custom_column', array( $this, 'product_brand_column' ), 10, 3 );
		}
		
	}
	
	/**
	 * Override WooSearch Form
	 **/
	function custom_woo_search() {
		ob_start();
		include WPROTO_THEME_DIR . '/search-form-shop.php';
		return ob_get_clean();
	}
	
	/**
	 * Woocommerce page Wrapper start
	 **/
	function woocommerce_wrapper_start() {
		global $wpl_exe_wp;
		if( $wpl_exe_wp->get_option( 'woo_display_sidebar', 'plugins' ) ) {
			echo '<article class="col-md-9 wproto-primary-content-area">';
		} else {
			echo '<article class="col-md-12 wproto-primary-content-area">';
		}

	}
	
	/**
	 * Woocommerce page Wrapper end
	 **/
	function woocommerce_wrapper_end() {
		echo '</article>';
	}
	
	/**
	 * Woocommerce Custom posts per page
	 **/
	function woocommerce_custom_products_per_page() {
		global $wpl_exe_wp;
		return absint( $wpl_exe_wp->get_option( 'woo_posts_per_page', 'plugins' ) );
	}
	
	/**
	 * Custom image sizes and other settings
	 **/
	function setup_woocommerce() {
		global $pagenow, $wpl_exe_wp;
		
		if( $wpl_exe_wp->get_option( 'woo_disable_ordering', 'plugins' ) ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
			remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
		}
		
	}
	
	/**
	 * Add wishlist button & likes
	 **/
	function after_add_to_cart() {
		global $wpl_exe_wp;
		if( $wpl_exe_wp->get_option( 'woo_enable_wishlist', 'plugins' ) ) {
			
			if( ! wpl_exe_wp_front::in_wishlist( get_the_ID() ) ) {
				
				echo '<a href="javascript:;" data-id="' . get_the_ID() . '" class="button wproto-add-to-wishlist">' . __('Add to wishlist', 'wproto') . '</a>';
				
			} 
			
		}

	}
	
	/**
	 * Add like buttons
	 **/
	function woocommerce_add_like_buttons() {
		wpl_exe_wp_front::share_links();
	}
	
	/**
	 * Change prettyPhoto to a nivoLightbox
	 **/
	function woocommerce_single_product_image_html( $html ) {
		$html = str_replace('data-rel="prettyPhoto[product-gallery]', 'data-lightbox-gallery="product-gallery', $html);
		return $html;
	}
	
	function register_taxonomies() {
		register_taxonomy( 'product_brand',
			'product',
			array(
				'hierarchical' 				=> FALSE,
				'show_ui' 						=> TRUE,
				'query_var' 					=> TRUE,
				'rewrite' 						=> TRUE,
				'show_admin_column'   => TRUE,
				'show_in_nav_menus' 	=> TRUE,
				'labels'              => array(
					'name'                => _x( 'Brands', 'taxonomy general name', 'wproto' ),
					'singular_name'       => _x( 'Brand', 'taxonomy singular name', 'wproto' ),
					'search_items'        => __( 'Search Brands', 'wproto' ),
					'all_items'           => __( 'All Brands', 'wproto' ),
					'edit_item'           => __( 'Edit Brand', 'wproto' ), 
					'update_item'         => __( 'Update Brand', 'wproto' ),
					'add_new_item'        => __( 'Add New Brand', 'wproto' ),
					'new_item_name'       => __( 'New Brand', 'wproto' ),
					'menu_name'           => __( 'Brands', 'wproto' )
				)
			)
		);
		
	}
	
	/**
	 * Brand thumbnail fields.
	 */
	public function add_brand_fields() {
		?>
		<div class="form-field">
			<label><?php _e( 'Thumbnail', 'woocommerce' ); ?></label>
			<div id="product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo wc_placeholder_img_src(); ?>" width="60px" height="60px" /></div>
			<div style="line-height:60px;">
				<input type="hidden" id="product_cat_thumbnail_id" name="product_cat_thumbnail_id" />
				<button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
				<button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
			</div>
			<script type="text/javascript">

				// Only show the "remove image" button when needed
				if ( ! jQuery('#product_cat_thumbnail_id').val() ) {
					jQuery('.remove_image_button').hide();
				}

				// Uploading files
				var file_frame;

				jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( file_frame ) {
						file_frame.open();
						return;
					}

					// Create the media frame.
					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php _e( 'Choose an image', 'woocommerce' ); ?>',
						button: {
							text: '<?php _e( 'Use image', 'woocommerce' ); ?>',
						},
						multiple: false
					});

					// When an image is selected, run a callback.
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get('selection').first().toJSON();

						jQuery('#product_cat_thumbnail_id').val( attachment.id );
						jQuery('#product_cat_thumbnail img').attr('src', attachment.sizes.thumbnail.url );
						jQuery('.remove_image_button').show();
					});

					// Finally, open the modal.
					file_frame.open();
				});

				jQuery( document ).on( 'click', '.remove_image_button', function( event ) {
					jQuery('#product_cat_thumbnail img').attr('src', '<?php echo wc_placeholder_img_src(); ?>');
					jQuery('#product_cat_thumbnail_id').val('');
					jQuery('.remove_image_button').hide();
					return false;
				});

			</script>
			<div class="clear"></div>
		</div>
		<?php
	}

	/**
	 * Edit brand thumbnail field.
	 *
	 * @param mixed $term Term (brand) being edited
	 */
	public function edit_brand_fields( $term ) {

		$thumbnail_id = absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		} else {
			$image = wc_placeholder_img_src();
		}
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php _e( 'Thumbnail', 'woocommerce' ); ?></label></th>
			<td>
				<div id="product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo $image; ?>" width="60px" height="60px" /></div>
				<div style="line-height:60px;">
					<input type="hidden" id="product_cat_thumbnail_id" name="product_cat_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
					<button type="submit" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
					<button type="submit" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
				</div>
				<script type="text/javascript">

					// Uploading files
					var file_frame;

					jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

						event.preventDefault();

						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}

						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php _e( 'Choose an image', 'woocommerce' ); ?>',
							button: {
								text: '<?php _e( 'Use image', 'woocommerce' ); ?>',
							},
							multiple: false
						});

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							attachment = file_frame.state().get('selection').first().toJSON();

							jQuery('#product_cat_thumbnail_id').val( attachment.id );
							jQuery('#product_cat_thumbnail img').attr('src', attachment.sizes.thumbnail.url );
							jQuery('.remove_image_button').show();
						});

						// Finally, open the modal.
						file_frame.open();
					});

					jQuery( document ).on( 'click', '.remove_image_button', function( event ) {
						jQuery('#product_cat_thumbnail img').attr('src', '<?php echo wc_placeholder_img_src(); ?>');
						jQuery('#product_cat_thumbnail_id').val('');
						jQuery('.remove_image_button').hide();
						return false;
					});

				</script>
				<div class="clear"></div>
			</td>
		</tr>
		<?php
	}
	
	public function save_brand_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( isset( $_POST['display_type'] ) && 'product_brand' === $taxonomy ) {
			update_woocommerce_term_meta( $term_id, 'display_type', esc_attr( $_POST['display_type'] ) );
		}
		if ( isset( $_POST['product_cat_thumbnail_id'] ) && 'product_brand' === $taxonomy ) {
			update_woocommerce_term_meta( $term_id, 'thumbnail_id', absint( $_POST['product_cat_thumbnail_id'] ) );
		}
	}
	
	/**
	 * Thumbnail column added to category admin.
	 *
	 * @param mixed $columns
	 * @return array
	 */
	public function product_brand_columns( $columns ) {
		$new_columns          = array();
		$new_columns['cb']    = $columns['cb'];
		$new_columns['thumb'] = __( 'Image', 'woocommerce' );

		unset( $columns['cb'] );

		return array_merge( $new_columns, $columns );
	}
	
	/**
	 * Thumbnail column value added to category admin.
	 *
	 * @param mixed $columns
	 * @param mixed $column
	 * @param mixed $id
	 * @return array
	 */
	public function product_brand_column( $columns, $column, $id ) {

		if ( 'thumb' == $column ) {

			$thumbnail_id = get_woocommerce_term_meta( $id, 'thumbnail_id', true );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = wc_placeholder_img_src();
			}

			// Prevent esc_url from breaking spaces in urls for image embeds
			// Ref: http://core.trac.wordpress.org/ticket/23605
			$image = str_replace( ' ', '%20', $image );

			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . __( 'Thumbnail', 'woocommerce' ) . '" class="wp-post-image" height="48" width="48" />';

		}

		return $columns;
	}
	
	/**
	 * Custom pages for a WooCommerce
	 **/
	function custom_woo_pages( $template ) {
		global $wpl_exe_wp;
		
		if( wpl_exe_wp_utils::isset_woocommerce() && $wpl_exe_wp->get_option( 'woo_custom_templates', 'plugins' ) ) {
			
			if( is_shop() || is_product_category() || is_product_tag() || is_product_brand() || ( is_search() && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'product' ) ) {
				return get_template_directory() . '/shop/shop.php';
			}
			
		}
		
		return $template;
	}
	
	/**
	 * Relocate some of WooCommerce templates
	 **/
	function custom_woo_templates( $template, $template_name, $template_path ) {
		global $woocommerce, $wpl_exe_wp;
		
		if( wpl_exe_wp_utils::isset_woocommerce() && $wpl_exe_wp->get_option( 'woo_custom_templates', 'plugins' ) ) {
		
			$_template = $template;
			
			if ( ! $template_path ) $template_path = $woocommerce->template_url;
			
			$theme_woo_path = get_template_directory() . '/shop/';
			
		  $template = locate_template( array(
	   		$template_path . $template_name,
	     	$template_name
	   	));
	   	
	 	  if ( ! $template && file_exists( $theme_woo_path . $template_name ) )
	    $template = $theme_woo_path . $template_name;
	    
	  	if ( ! $template ) $template = $_template;
	  	
  	}
  	
  	return $template;
		
	}
	
}

if( !function_exists('is_product_brand') ) {
	function is_product_brand() {
		return is_tax('product_brand');
	}
}