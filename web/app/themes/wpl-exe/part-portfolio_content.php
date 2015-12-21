<?php
	global $wpl_exe_wp;
	$shortcode_params = get_query_var('wproto_shortcode_params');
	$read_more = $shortcode_params['read_more_text'];
	
	$img_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
	$add_class = ! $shortcode_params['display_filters'] ? 'wow fadeIn' : '';
?>

<article <?php post_class( $add_class ); ?> data-wow-delay="0.1s" id="post-<?php the_ID(); ?>">

	<div class="post-content-inner">
	
		<header class="post-grid-media-header">
			<div class="post-thumbnail wproto-post-image-area">
			
				<?php if( in_array( $shortcode_params['style'], array( 'cols_2_masonry_no_gap', 'cols_3_masonry_no_gap', 'cols_4_masonry_no_gap' ) ) ): ?>
				
					<?php echo wpl_exe_image( $img_url, 585, 380 ); ?>
				
				<?php else: ?>
				
					<?php echo wpl_exe_image( $img_url, 580, 999, false ); ?>
				
				<?php endif; ?>
			
				<div class="overlay">
				
					<div class="overlay-info">
						<h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="cats"><?php echo wpl_exe_wp_front::get_categories(); ?></div>
					</div>
				
					<div class="buttons">
						<a class="lightbox zoom" href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>"><span class="hover_pulse_ray"></span></a>
						<a class="post-link" href="<?php the_permalink(); ?>"><span class="hover_pulse_ray"></span></a>
					</div>
				</div>
			</div>
		</header>
		
		<?php if( in_array( $shortcode_params['style'], array('cols_2_masonry_with_desc', 'cols_3_masonry_with_desc', 'cols_4_masonry_with_desc')) ): ?>
		<div class="item-content">
			<h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
			<div class="cats"><?php echo wpl_exe_wp_front::get_categories(); ?></div>
		</div>
		<?php endif; ?>
	
	</div>
	
</article>