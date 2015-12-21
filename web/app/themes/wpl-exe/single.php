<?php
	global $wpl_exe_wp;
	
	$matches = array();
	
	get_header();
	get_template_part('part', 'breadcrumbs');
	
	$post_style = $wpl_exe_wp->get_option('blog_single_style', 'posts');
	$post_style = $wpl_exe_wp->get_post_option('wproto_single_style') ? $wpl_exe_wp->get_post_option('wproto_single_style') : $post_style;
	
	/** get post gallery shortcode **/
	$post_format = get_post_format();
	$post_gallery = wpl_exe_wp_front::get_gallery();
	
	/** post format blockquote bg **/
	$custom_style = '';
	if( $post_format == 'quote' && has_post_thumbnail() ) {
		$custom_style = 'background-image: url(' . wp_get_attachment_url( get_post_thumbnail_id( get_the_ID()) ) . ');';
	}
	
	/** get post media **/
	$header_media = wpl_exe_wp_front::get_media( $post_format );
	
?>

<?php if( have_posts() ): while ( have_posts() ) : the_post(); ?>
	
	<section class="container post-single-blog-style-<?php echo esc_attr( $post_style ); ?>" id="content">
		<div class="row">
			<article <?php post_class( wpl_exe_wp_front::content_classes( true ) . ' wproto-primary-content-area' ); ?> id="post-<?php the_ID(); ?>">
			
				<div class="post-content-inner">
				
					<?php if( $post_style == 'alt' ): ?>
					<header class="alt">
						<?php if( !$post_format && has_post_thumbnail() ): ?>
							<div class="post-thumbnail">
								<?php echo the_post_thumbnail('full'); ?>
							</div>

						<?php endif; ?>
						
						<?php if( $post_format == 'gallery' ): ?>
							<?php echo do_shortcode( $post_gallery ); ?>
						<?php endif; ?>
						
						<?php if( in_array( $post_format, array( 'video', 'audio' ) ) ): ?>
							<?php echo $header_media; ?>
						<?php endif; ?>
					</header>
					<?php endif; ?>
				
					<!--
						
						POST HEADER
							
					-->
					<?php if( !in_array( $post_format, array('link', 'quote') ) ): ?>
					<header>
						<h1><?php the_title(); ?></h1>
						<div class="post-meta-data">
							<span class="meta-item"><?php _e('By', 'wproto'); ?> <?php the_author(); ?></span>
							<span class="meta-item"><?php _e('In', 'wproto'); ?> <?php echo wpl_exe_wp_front::get_categories(); ?></span>
							<span class="meta-item"><time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time( get_option('date_format')); ?></time></span>
							<span class="meta-item"><?php comments_number( __('no comments', 'wproto'), __('one comment', 'wproto'), __('% comments', 'wproto') ); ?></span>
						</div>
					</header>
					<?php endif; ?>
					
					<?php if( $post_style == '' || $post_style == 'default' ): ?>
					
						<?php if( !$post_format && has_post_thumbnail() ): ?>
							<p class="post-thumbnail">
								<?php echo the_post_thumbnail('full'); ?>
							</p>
						<?php endif; ?>
						
						<?php if( $post_format == 'gallery' ): ?>
							<?php echo do_shortcode( $post_gallery ); ?>
						<?php endif; ?>
					
					<?php endif; ?>
						
					<div class="post-text-area" style="<?php echo esc_attr( $custom_style ); ?>">
						<!--
							
							POST CONTENT
								
						-->
						<?php if( $post_format == 'quote' ): ?>
						<div class="inner">
						<?php endif; ?>
						
						<?php if( $post_format == 'link' ): ?>
							<h2><?php the_title(); ?></h2>
						<?php endif; ?>
						
						<?php the_content(); ?>
						
						<?php if( $post_format == 'quote' ): ?>
						</div>
						<?php endif; ?>
					</div>
					
					<div class="clearfix"></div>
					
					<?php wp_link_pages('before=<div class="pagination post-pagination">&after=</div>&next_or_number=next'); ?>
					
					<div class="clearfix"></div>
					
					<!--
					
						POST FOOTER
						
					-->
					<?php if( !post_password_required() ): ?>
					<footer>
					
						<div class="share-tags">
						<?php if( $wpl_exe_wp->get_option('show_tags_blog', 'posts') ): $tags_list = wpl_exe_wp_front::get_valid_tags_list(''); if( $tags_list <> '' ): ?>
							<!--
							
								TAGS
								
							-->
							<div class="post-tags">
								<h5><?php _e('Tags', 'wproto'); ?>:</h5>
								<?php echo $tags_list; ?>
							</div>
						<?php endif; endif; ?>
						
						<?php if( $wpl_exe_wp->get_option('show_share_links_blog', 'posts') ): ?>
							<!--
							
								SHARE LINKS
								
							-->
							<?php wpl_exe_wp_front::share_links(); ?>
						<?php endif; ?>
						</div>
	
					</footer>
					<?php endif; ?>
				</div>
				<?php if( !post_password_required() ): ?>
				<footer class="second">
				
					<?php if( $wpl_exe_wp->get_option('show_author_info', 'posts') && !in_array( $post_format, array('link', 'quote') ) ): ?>
						<!--
						
							ABOUT POST AUTHOR
							
						-->
						<?php wpl_exe_wp_front::about_author( $post->post_author ); ?>
					<?php endif; ?>
					
					<?php if( !in_array( $post_format, array('link', 'quote') ) ): ?>
						<!--
						
							RELATED POSTS
							
						-->
						<?php wpl_exe_wp_front::blog_related_posts( get_the_ID() ); ?>
					<?php endif; ?>
					
					<?php if( !post_password_required() && comments_open() ): ?>
						<!--
									
							COMMENTS
									
						-->
						<div class="comments-style-<?php echo esc_attr( $wpl_exe_wp->get_option('blog_comments_style', 'posts') ); ?>">
							<?php comments_template( '', true ); ?>
						</div>
					<?php endif; ?>
					
					
					<?php if( $wpl_exe_wp->get_option('show_posts_links_blog', 'posts') ): ?>
						<!--
						
							PREV AND NEXT POST LINKS
							
						-->
						<div class="wproto-prev-next-posts">
							<?php
								$prev_post = get_adjacent_post( false, '', true);
								if( !empty( $prev_post)) echo '<a class="prevpost" href="' . esc_attr( get_permalink( $prev_post->ID ) ) . '"><span class="desc">' . __('Previous post', 'wproto') . '</span> <span class="title">' . $prev_post->post_title . '</span></a>';
								$next_post = get_adjacent_post( false, '', false);
								if( !empty( $next_post)) echo '<a class="nextpost" href="' . esc_attr( get_permalink( $next_post->ID ) ) . '"><span class="desc">' . __('Next post', 'wproto') . '</span> <span class="title">' . $next_post->post_title . '</a>';
							?>
							<div class="clearfix"></div>
						</div>
					<?php endif; ?>
				</footer>
				<?php endif; ?>
				
				<div class="clearfix"></div>
				
			</article>
	
			<?php get_sidebar(); ?>
		</div>
	
	</section>
	
<?php endwhile; endif; ?>

<?php get_footer(); 