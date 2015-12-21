<?php
	global $wpl_exe_wp, $wpl_exe_wp_g_width, $wpl_exe_wp_g_height;
	$matches = array();
	/** get post gallery shortcode **/
	$post_format = get_post_format();
	$post_gallery = do_shortcode( wpl_exe_wp_front::get_gallery() );
	/** post format blockquote bg **/
	$custom_style = '';
	if( $post_format == 'quote' && has_post_thumbnail() ) {
		$custom_style = 'background-image: url(' . wp_get_attachment_url( get_post_thumbnail_id( get_the_ID()) ) . ');';
	}
	/** get post media **/
	$header_media = wpl_exe_wp_front::get_media( $post_format );
	$shortcode_params = get_query_var('wproto_shortcode_params');
	
	/** post format blockquote bg **/
	$custom_style = '';
	if( $post_format == 'quote' && has_post_thumbnail() ) {
		$custom_style = 'background-image: url(' . wp_get_attachment_url( get_post_thumbnail_id( get_the_ID()) ) . ');';
	}
	
	$read_more = $shortcode_params['read_more_text'];
?>

<article <?php post_class( 'wow fadeIn'); ?> data-wow-delay="0.1s" id="post-<?php the_ID(); ?>">

	<?php if( !in_array( $post_format, array('link', 'quote') ) ): ?>
	<div class="post-content-inner">
	<?php endif; ?>
	
		<?php if( $shortcode_params['style'] == 'cols_1_centered_no_bg' ): ?>
		
			<?php if( !in_array( $post_format, array('link', 'quote') ) ): ?>
			<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php endif; ?>
		
			<?php if( !in_array( $post_format, array('link', 'quote') ) ): ?>
			<header class="post-grid-meta">
				<div class="post-meta-data">
					<span class="meta-item by"><?php _e('By', 'wproto'); ?> <?php the_author(); ?></span>
					<span class="meta-item cats"><?php _e('In', 'wproto'); ?> <?php echo wpl_exe_wp_front::get_categories(); ?></span>
					<span class="meta-item"><time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time( get_option('date_format')); ?></time></span>
					<span class="meta-item"><?php comments_number( __('no comments', 'wproto'), __('one comment', 'wproto'), __('% comments', 'wproto') ); ?></span>
				</div>
			</header>
			<?php endif; ?>
		<?php endif; ?>
	
		<!--
		
			POST MEDIA HEADER
			
		-->
		
		<?php if( !in_array( $post_format, array('link', 'quote') ) ): ?>
		<header class="post-grid-media-header">
			<?php if( !$post_format && has_post_thumbnail() || ( $post_format == 'gallery' && $post_gallery == '' ) ): ?>
				<div class="post-format-icon"></div>
				<div class="post-thumbnail wproto-post-image-area">				
				
					<?php $thumb = ''; ?>
				
					<?php if( in_array($shortcode_params['style'], array('cols_1_default', 'cols_1_centered', 'cols_1_centered_no_bg', 'cols_1_alt')) ): ?>
						<?php
							$wpl_exe_wp_g_width = 1170;
							$wpl_exe_wp_g_height = 565;
							$thumb = wpl_exe_image( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $wpl_exe_wp_g_width, $wpl_exe_wp_g_height );
							if( $thumb == '' ) {
								echo '<a href="' . get_permalink() . '">';
								the_post_thumbnail();
								echo '</a>';
							} else {
								echo $thumb;
							}
						?>
					<?php elseif( in_array( $shortcode_params['style'], array( 'cols_2', 'cols_2_alt', 'cols_2_masonry', 'cols_2_masonry_alt' ) ) ): ?>
						<?php
							$wpl_exe_wp_g_width = 570;
							$wpl_exe_wp_g_height = 340;
							$thumb = wpl_exe_image( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $wpl_exe_wp_g_width, $wpl_exe_wp_g_height );
							echo $thumb;
						?>
					<?php elseif( in_array( $shortcode_params['style'], array( 'cols_3', 'cols_3_masonry' ) ) ): ?>
						<?php
							$wpl_exe_wp_g_width = 370;
							$wpl_exe_wp_g_height = 237;
							$thumb = wpl_exe_image( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $wpl_exe_wp_g_width, $wpl_exe_wp_g_height );
							echo $thumb;
						?>
					<?php elseif( in_array( $shortcode_params['style'], array( 'cols_4', 'cols_4_masonry' ) ) ): ?>
						<?php
							$wpl_exe_wp_g_width = 270;
							$wpl_exe_wp_g_height = 180;
							$thumb = wpl_exe_image( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $wpl_exe_wp_g_width, $wpl_exe_wp_g_height );
							echo $thumb;
						?>
					<?php endif; ?>
					
					<?php if( $thumb <> '' ): ?>
					<div class="overlay">
						<div class="buttons">
							<a class="lightbox zoom" href="<?php echo esc_attr( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>"><span class="hover_pulse_ray"></span></a>
							<a class="post-link" href="<?php the_permalink(); ?>"><span class="hover_pulse_ray"></span></a>
						</div>
					</div>
					<?php endif; ?>
					
				</div>
		
			<?php endif; ?>
			
			<?php if( $post_format == 'gallery' && $post_gallery <> '' ): ?>
				<div class="post-format-icon"></div>
				<?php echo $post_gallery; ?>
			<?php endif; ?>
			
			<?php if( in_array( $post_format, array( 'video', 'audio' ) ) ): ?>
				<div class="post-format-icon"></div>
				<?php echo $header_media; ?>
			<?php endif; ?>
		</header>
		<?php endif; ?>
		
		<?php if( $shortcode_params['style'] != 'cols_1_centered_no_bg' ): ?>
			<!--
			
				POST META HEADER
				
			-->
			<?php if( !in_array( $post_format, array('link', 'quote') ) ): ?>
			<header class="post-grid-meta">
				<div class="post-meta-data">
					<span class="meta-item by"><?php _e('By', 'wproto'); ?> <?php the_author(); ?></span>
					<span class="meta-item cats"><?php _e('In', 'wproto'); ?> <?php echo wpl_exe_wp_front::get_categories(); ?></span>
					<span class="meta-item"><time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time( get_option('date_format')); ?></time></span>
					<span class="meta-item"><?php comments_number( __('no comments', 'wproto'), __('one comment', 'wproto'), __('% comments', 'wproto') ); ?></span>
				</div>
			</header>
			<?php endif; ?>
			
			<!--
			
				POST TITLE AND CONTENT / EXCERPT
				
			-->
			<?php if( !in_array( $post_format, array('link', 'quote') ) ): ?>
			<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php endif; ?>
		<?php endif; ?>
		
		<div class="post-text-area" style="<?php echo esc_attr( $custom_style ); ?>">
			<?php if( $post_format == 'quote' ): ?>
			<div class="inner">
				<div class="post-format-icon"></div>
			<?php endif; ?>
			
			<?php if( $post_format == 'link' ): ?>
				<div class="post-format-icon"></div>
				<h2><?php the_title(); ?></h2>
			<?php endif; ?>
			
				<?php if( in_array( $post_format, array('link', 'quote', 'image') ) ): ?>
					<?php the_content(); ?>
				<?php else: ?>
					<div class="excerpt">
					
						<?php if( in_array( $shortcode_params['style'], array( 'cols_2', 'cols_2_alt', 'cols_2_masonry', 'cols_2_masonry_alt' ) ) ): ?>
							<?php echo wp_trim_words( get_the_excerpt(), 30, '...'); ?>
						<?php elseif( in_array( $shortcode_params['style'], array( 'cols_3', 'cols_4', 'cols_3_masonry', 'cols_4_masonry' ) ) ): ?>
							<?php echo wp_trim_words( get_the_excerpt(), 13, '...'); ?>
						<?php else: ?>
							<?php the_excerpt(); ?>
						<?php endif; ?>
					
					</div>
				<?php endif; ?>
			
			<?php if( $post_format == 'quote' ): ?>
			</div>
			<?php endif; ?>
			<div class="clearfix"></div>
		</div>
		
		<?php if( $read_more <> '' && !in_array( $post_format, array('link', 'quote') ) ): ?>
		<div class="read-more">
			<?php if( in_array($shortcode_params['style'], array('cols_1_default', 'cols_1_centered', 'cols_1_centered_no_bg', 'cols_1_alt', 'cols_2', 'cols_2_alt', 'cols_2_masonry', 'cols_2_masonry_alt')) ): ?>
			<a href="<?php the_permalink(); ?>" class="button button-style-black"><?php echo esc_html( $read_more ); ?></a>
			<?php else: ?>
			<a href="<?php the_permalink(); ?>" class="more"><?php echo esc_html( $read_more ); ?></a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	
	<?php if( !in_array( $post_format, array('link', 'quote') ) ): ?>
	</div>
	<?php endif; ?>
	
</article>