<?php
/**
 * Single Product Up-Sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpl_exe_wp, $product, $woocommerce_loop;

$_sidebar_displayed = $wpl_exe_wp->get_option('woo_display_sidebar', 'plugins');

$upsells = $product->get_upsells();

if ( sizeof( $upsells ) == 0 ) {
	return;
}

$meta_query = WC()->query->get_meta_query();

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => $posts_per_page,
	'orderby'             => $orderby,
	'post__in'            => $upsells,
	'post__not_in'        => array( $product->id ),
	'meta_query'          => $meta_query
);

$products = new WP_Query( $args );

$woocommerce_loop['columns'] = $columns;

if ( $products->have_posts() ) : ?>
<div class="wproto-shop-upsellers">

	<h2 class="wproto-shop-title"><?php _e( 'You may also like&hellip;', 'woocommerce' ) ?></h2>

	<div class="wproto-products-carousel style-style_1">
		<div class="items" data-display-nav-bullets="false" data-display-nav-arrows="false" data-v-desktop="<?php echo $_sidebar_displayed ? '3' : '4'; ?>" data-v-small-desktop="<?php echo $_sidebar_displayed ? '2' : '3'; ?>" data-v-phone-land="<?php echo $_sidebar_displayed ? '1' : '2'; ?>" data-v-phone="1">
			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php
					$product = wc_get_product( get_the_ID() );
					$product_images = array( get_post_thumbnail_id( get_the_ID() ) );
					$product_images_ids = $product->get_gallery_attachment_ids();
					if( is_array( $product_images_ids ) ) $product_images = array_merge( $product_images, $product_images_ids );
				?>
				
				<div class="item">
					<div class="inside">
					
						<?php if( !empty( $product_images ) ): ?>
						<div class="thumb <?php echo count( $product_images ) == 1 ? 'hide-controls' : ''; ?>">
							<?php if( $product->is_on_sale() ): ?>
							<div class="onsale"><?php _e('Sale', 'wproto'); ?></div>
							<?php endif; ?>
							
							<a href="javascript:;" data-id="<?php the_ID(); ?>" title="<?php echo ! wpl_exe_wp_front::in_wishlist( get_the_ID() ) ? __('Add to my Wishlist', 'wproto') : ''; ?>" class="wproto-add-to-wishlist iconic-wishlist <?php echo wpl_exe_wp_front::in_wishlist( get_the_ID() ) ? 'active' : ''; ?>"></a>
							
							<div class="g-items">
								<?php foreach( $product_images as $img_id ): ?>
								
									<?php echo wpl_exe_image( wp_get_attachment_url( $img_id ), 290, 350, true, true, 'thumbnail', $img_id ); ?>
								
								<?php endforeach; ?>
							</div>
						</div>
						<?php endif; ?>
						
						<div class="text">
							<div class="categories">
								<?php echo get_the_term_list( get_the_ID(), 'product_cat', '', ', ', '' ); ?>
							</div>
							<a href="<?php the_permalink(); ?>" class="product-title"><?php the_title(); ?></a>
							<div class="product-price">
								<?php echo $product->get_price_html(); ?>
							</div>
							<div class="product-add-button">
								<a class="button" href="<?php echo esc_attr( $product->add_to_cart_url() ); ?>"><?php echo $product->add_to_cart_text(); ?></a>
							</div>
						</div>

					</div>
				</div>

			<?php endwhile; // end of the loop. ?>
		</div>
	</div>
</div>
<?php endif;

wp_reset_postdata();
