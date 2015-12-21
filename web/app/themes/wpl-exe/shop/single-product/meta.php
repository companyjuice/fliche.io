<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$cat_count = get_the_terms( get_the_ID(), 'product_cat' );
$tag_count = get_the_terms( get_the_ID(), 'product_tag' );

?>
<div class="wproto-single-product-meta product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<h5><?php _e( 'SKU:', 'woocommerce' ); ?></h5>
		<div class="links"><span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span>.</div>

	<?php endif; ?>

	<?php if( $cat_count != false ): ?>
		<?php echo '<h5>' . _n( 'Category:', 'Categories:', $cat_count, 'wproto' ) . '</h5><div class="links">' . $product->get_categories() . '</div>'; ?>
	<?php endif; ?>
	
	<?php if( $tag_count != false ): ?>
		<?php echo '<h5>' . _n( 'Tag:', 'Tags:', $tag_count, 'wproto' ) . '</h5><div class="links">' . $product->get_tags() . '</div>'; ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
