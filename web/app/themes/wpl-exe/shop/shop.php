<?php
	get_header();
	get_template_part('part', 'breadcrumbs');
	
	global $wp_query, $wpl_exe_wp;
	$style = $wpl_exe_wp->get_option('woo_layout', 'plugins');
	$display_filters = $wpl_exe_wp->get_option('woo_ajax_filters', 'plugins');
	$display_view_switcher = $wpl_exe_wp->get_option('woo_view_switcher', 'plugins');
?>
	
<section class="container" id="content">
	<div class="row">				
		<!--
			
			WOOCOMMERCE CONTENT
				
		-->
		
		<?php do_action( 'woocommerce_before_main_content' ); ?>
		
			<?php if ( is_product_category() ): ?>
	
				<?php
					global $wp_query;
					$cat = $wp_query->get_queried_object();
					$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
					$image = wp_get_attachment_url( $thumbnail_id );
					if ( $image ) {
					  echo '<p><img src="' . $image . '" alt="" /></p>';
					}
				?>
			
			<?php endif; ?>
			
			<?php echo do_shortcode('[wproto_shop_product use_wp_query="1" posts_per_page="' . get_option('posts_per_page') . '"  view_switcher="' . esc_attr( $display_view_switcher ) . '" display_filters="' . esc_attr( $display_filters ) . '"  style="' . esc_attr( $style ) . '" order_by="date" sort_by="DESC" display_pagination="1" pagination_style="numeric_with_prev_next"]'); ?>
				
		<?php do_action( 'woocommerce_after_main_content' ); ?>

		<!--
			
			WOOCOMMERCE SIDEBAR
				
		-->
		<?php do_action( 'woocommerce_sidebar' ); ?>
	</div>

</section>

<?php get_footer(); 