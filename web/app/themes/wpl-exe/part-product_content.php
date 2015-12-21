<?php
	global $wpl_exe_wp;
	$shortcode_params = get_query_var('wproto_shortcode_params');
	
	$style = $shortcode_params['style'];	
	$product = wc_get_product( get_the_ID() );
	$product_thumb_id = get_post_thumbnail_id( get_the_ID() );
	$product_thumb_url = wp_get_attachment_url( $product_thumb_id );
	$product_images = array( $product_thumb_id );
	$product_images_ids = $product->get_gallery_attachment_ids();
	if( is_array( $product_images_ids ) ) $product_images = array_merge( $product_images, $product_images_ids );
	
	$with_thumb = count( $product_images ) > 0;
	
	$post_classes = '';
	if( $with_thumb ) $post_classes .= ' with-thumb';
?>

<article <?php post_class( $post_classes ); ?> id="post-<?php the_ID(); ?>">
	<div class="inside">
		<div class="thumb <?php echo count( $product_images ) == 1 ? 'hide-controls' : ''; ?>">
		
			<?php if( $product->is_on_sale() ): ?>
			<div class="onsale"><?php _e('Sale', 'wproto'); ?></div>
			<?php endif; ?>
			
			<a href="javascript:;" data-id="<?php the_ID(); ?>" title="<?php echo ! wpl_exe_wp_front::in_wishlist( get_the_ID() ) ? __('Add to my Wishlist', 'wproto') : ''; ?>" class="small-icon-wishlist wproto-add-to-wishlist iconic-wishlist <?php echo wpl_exe_wp_front::in_wishlist( get_the_ID() ) ? 'active' : ''; ?>"></a>
			
			<div class="g-items">
				<?php if( $with_thumb ): ?>
				
					<?php foreach( $product_images as $img_id ): $thumb = wp_get_attachment_url( $img_id ); ?>
					
						<a href="<?php echo esc_attr( $thumb ); ?>" data-lightbox-gallery="products-gallery-<?php the_ID(); ?>" class="lightbox">
							<?php if( in_array( $style, array('col_1_style_1', 'col_1_style_3', 'cols_2_style_1', 'cols_2_style_3', 'cols_3_style_1', 'cols_3_style_3', 'cols_4_style_1', 'cols_4_style_3') ) ): ?>							
								<?php echo wpl_exe_image( $thumb, 570, 560, true, true, 'medium', $img_id ); ?>
							<?php elseif( in_array( $style, array('col_1_style_2', 'cols_2_style_2', 'cols_3_style_2', 'cols_4_style_2') ) ): ?>
								<?php echo wpl_exe_image( $thumb, 270, 350, true, true, 'medium', $img_id ); ?>
							<?php endif; ?>
						</a>
					
					<?php endforeach; ?>
				<?php else: ?>
					
					<img src="<?php echo wc_placeholder_img_src(); ?>" alt="" />
					
				<?php endif; ?>
			</div>
		
		</div>
	
		<div class="product-content">
			
			<?php if( in_array( $style, array( 'col_1_style_1', 'cols_2_style_1', 'cols_3_style_1', 'cols_4_style_1' ) ) ): ?>
			
				<div class="product-rating">
					<?php echo $product->get_rating_html(); ?>
				</div>
				
				<a href="<?php the_permalink(); ?>" class="product-title"><?php the_title(); ?></a>
				
				<div class="clearfix"></div>
				
				<div class="product-categories">
					<?php echo get_the_term_list( get_the_ID(), 'product_cat', '', ', ', '' ); ?>
				</div>
				
				<div class="product-price">
					<?php echo $product->get_price_html(); ?>
				</div>
				
				<div class="product-desc">
					<?php echo wp_trim_words( get_the_excerpt(), 55 ); ?>
				</div>
				
				<div class="hidden product-desc price-visible-cols-2">
					<?php echo wp_trim_words( get_the_excerpt(), 21 ); ?>
				</div>
				
				<div class="hidden product-price price-visible-cols-2-style-1">
					<?php echo $product->get_price_html(); ?>
				</div>
				
				<div class="product-cart-buttons">
					<a class="button button-style-black" href="<?php echo esc_attr( $product->add_to_cart_url() ); ?>"><?php echo $product->add_to_cart_text(); ?></a>
					<a href="javascript:;" data-id="<?php the_ID(); ?>" class="wproto-add-to-wishlist iconic-wishlist <?php echo wpl_exe_wp_front::in_wishlist( get_the_ID() ) ? 'active' : ''; ?>"></a>
				</div>
				
				<div class="clearfix"></div>
			
			<?php elseif( in_array( $style, array( 'col_1_style_2', 'cols_2_style_2', 'cols_3_style_2', 'cols_4_style_2' ) ) ): ?>
			
				<div class="product-categories">
					<?php echo get_the_term_list( get_the_ID(), 'product_cat', '', ', ', '' ); ?>
				</div>
			
				<a href="<?php the_permalink(); ?>" class="product-title"><?php the_title(); ?></a>
				
				<div class="product-rating">
					<?php echo $product->get_rating_html(); ?>
				</div>
				
				<div class="product-desc">
					<?php echo wp_trim_words( get_the_excerpt(), 55 ); ?>
				</div>
				
				<div class="hidden product-desc price-visible-cols-2">
					<?php echo wp_trim_words( get_the_excerpt(), 21 ); ?>
				</div>
				
				<footer>
				
					<div class="product-price">
						<?php echo $product->get_price_html(); ?>
					</div>
					
					<div class="product-cart-buttons">
						<a class="button button-style-black" href="<?php echo esc_attr( $product->add_to_cart_url() ); ?>"><?php echo $product->add_to_cart_text(); ?></a>
						<a href="javascript:;" data-id="<?php the_ID(); ?>" class="wproto-add-to-wishlist iconic-wishlist with-text <?php echo wpl_exe_wp_front::in_wishlist( get_the_ID() ) ? 'active' : ''; ?>"><?php echo wpl_exe_wp_front::in_wishlist( get_the_ID() ) ? '' : __('Add to Wishlist', 'wproto'); ?></a>
					</div>
				
				</footer>
			
			<?php elseif( in_array( $style, array( 'col_1_style_3', 'cols_2_style_3', 'cols_3_style_3', 'cols_4_style_3' ) ) ): ?>
			
				<div class="product-rating">
					<?php echo $product->get_rating_html(); ?>
				</div>
				
				<a href="<?php the_permalink(); ?>" class="product-title"><?php the_title(); ?></a>
				
				<div class="product-price">
					<?php echo $product->get_price_html(); ?>
				</div>
				
				<div class="product-desc">
					<?php echo wp_trim_words( get_the_excerpt(), 55 ); ?>
				</div>
				
				<div class="hidden product-desc price-visible-cols-2">
					<?php echo wp_trim_words( get_the_excerpt(), 21 ); ?>
				</div>
				
				<div class="product-cart-buttons">
					<a class="button button-style-black" href="<?php echo esc_attr( $product->add_to_cart_url() ); ?>"><?php echo $product->add_to_cart_text(); ?></a>
					<a href="javascript:;" data-id="<?php the_ID(); ?>" class="wproto-add-to-wishlist iconic-wishlist with-text <?php echo wpl_exe_wp_front::in_wishlist( get_the_ID() ) ? 'active' : ''; ?>"><?php echo wpl_exe_wp_front::in_wishlist( get_the_ID() ) ? '' : __('Add to Wishlist', 'wproto'); ?></a>
				</div>
			
			<?php endif; ?>
			
		</div>
		
	</div>
	<div class="clearfix"></div>
</article>