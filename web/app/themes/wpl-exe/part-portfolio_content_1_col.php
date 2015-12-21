<?php
	global $wpl_exe_wp;
	$shortcode_params = get_query_var('wproto_shortcode_params');
	$read_more = $shortcode_params['read_more_text'];
	
	$img_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
?>

<div class="row fadeIn wow" data-wow-delay="0.1s">
	<div class="col-md-12">
		<article <?php post_class(''); ?> id="post-<?php the_ID(); ?>">
			<div class="row">
				<div class="col-thumb col-md-6 col-sm-6 col-xs-6">
					<header class="post-grid-media-header">
						<div class="post-thumbnail wproto-post-image-area">
							<?php echo wpl_exe_image( $img_url, 640, 360 ); ?>
							<div class="overlay">				
								<div class="buttons">
									<a class="lightbox zoom" href="<?php echo esc_attr( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>"><span class="hover_pulse_ray"></span></a>
									<a class="post-link" href="<?php the_permalink(); ?>"><span class="hover_pulse_ray"></span></a>
								</div>
							</div>
						</div>
					</header>
				</div>
				<div class="col-desc col-md-6 col-sm-6 col-xs-6">
				
					<div class="desc-text">
						<div class="categories">
							<?php echo wpl_exe_wp_front::get_categories(); ?>
						</div>
						<h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="excerpt">
							<?php echo wp_trim_words( get_the_excerpt(), 30, '...'); ?>
						</div>
						<?php if( $read_more <> '' ): ?>
						<div class="read-more">
							<a href="<?php the_permalink(); ?>" class="more"><?php echo esc_html( $read_more ); ?></a>
						</div>
						<?php endif; ?>
					</div>
				
				</div>
			</div>
		</article>
	</div>
</div>