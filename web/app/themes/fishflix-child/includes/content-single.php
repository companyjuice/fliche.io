<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

// prev & next post -------------------
$in_same_term = ( mfn_opts_get( 'prev-next-nav' ) == 'same-category' ) ? true : false;
$post_prev = get_adjacent_post( $in_same_term, '', true );
$post_next = get_adjacent_post( $in_same_term, '', false );
$blog_page_id = get_option('page_for_posts');

// post classes -----------------------
$classes = array();
if( ! mfn_post_thumbnail( get_the_ID() ) ) $classes[] = 'no-img';
if( get_post_meta(get_the_ID(), 'mfn-post-hide-image', true) ) $classes[] = 'no-img';
if( post_password_required() ) $classes[] = 'no-img';
if( ! mfn_opts_get( 'blog-title' ) ) $classes[] = 'no-title';
if( ! mfn_opts_get( 'share' ) ) $classes[] = 'no-share';

$translate['published'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-published','Published by') : __('Published by','betheme');
$translate['at'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-at','at') : __('at','betheme');
$translate['tags'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-tags','Tags') : __('Tags','betheme');
$translate['categories'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-categories','Categories') : __('Categories','betheme');
$translate['all'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-all','Show all') : __('Show all','betheme');
$translate['related'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-related','Related posts') : __('Related posts','betheme');
$translate['readmore'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-readmore','Read more') : __('Read more','betheme');
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<div class="section section-post-header">
		<div class="section_wrapper clearfix">

<?php if ( get_post_type( $post ) != 'videogallery' ) { ?>
			
			<?php if( mfn_opts_get('prev-next-nav') ): ?>
			<div class="column one post-nav">
				
				<?php 
					// prev & next post navigation
					echo mfn_post_navigation( $post_prev, 'prev', 'icon-left-open-big' ); 
					echo mfn_post_navigation( $post_next, 'next', 'icon-right-open-big' ); 
				?>
				
				<ul class="next-prev-nav">
					<?php if( $post_prev ): ?>
						<li class="prev"><a class="button button_js" href="<?php echo get_permalink( $post_prev ); ?>"><span class="button_icon"><i class="icon-left-open"></i></span></a></li>
					<?php endif; ?>
					<?php if( $post_next ): ?>
						<li class="next"><a class="button button_js" href="<?php echo get_permalink( $post_next ); ?>"><span class="button_icon"><i class="icon-right-open"></i></span></a></li>
					<?php endif; ?>
				</ul>
				
				<?php if( $blog_page_id ): ?>
					<a class="list-nav" href="<?php echo get_permalink( $blog_page_id ); ?>"><i class="icon-layout"></i><?php echo $translate['all']; ?></a>
				<?php endif; ?>
				
			</div>
			<?php endif; ?>

			<div class="column one post-header">
			
				<div class="button-love"><?php echo mfn_love() ?></div>
				
				<div class="title_wrapper">
				
					<?php
						if( mfn_opts_get( 'blog-title' ) ){ 
							if( get_post_format() == 'quote'){
								echo '<blockquote>'. get_the_title() .'</blockquote>';
							} else {
								$h = mfn_opts_get( 'title-heading', 1 );
								echo '<h'. $h .' class="entry-title" itemprop="headline">'. get_the_title() .'</h'. $h .'>';
							}
						}
					?>
					
					<?php 
						if( get_post_format() == 'link'){
							$link = get_post_meta(get_the_ID(), 'mfn-post-link', true);
							echo '<a href="'. $link .'" target="_blank">'. $link .'</a>';
						}
					?>
					
					<?php if( mfn_opts_get( 'blog-meta' ) ): ?>
						<div class="post-meta clearfix">
						
							<div class="author-date">
								<span class="vcard author post-author">
									<?php echo $translate['published']; ?> <i class="icon-user"></i>
									<span class="fn"><a href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>"><?php the_author_meta( 'display_name' ); ?></a></span>
								</span> 
								<span class="date">
									<?php echo $translate['at']; ?> <i class="icon-clock"></i>
									<time class="entry-date" datetime="<?php the_date('c'); ?>" itemprop="datePublished" pubdate><?php echo get_the_date(); ?></time>
								</span>	
							</div>
							
							<div class="category meta-categories">
								<span class="cat-btn"><?php echo $translate['categories']; ?> <i class="icon-down-dir"></i></span>
								<div class="cat-wrapper"><?php echo get_the_category_list(); ?></div>
							</div>
							
							<div class="category mata-tags">
								<span class="cat-btn"><?php echo $translate['tags']; ?> <i class="icon-down-dir"></i></span>
								<div class="cat-wrapper">
									<ul>
										<?php
											if( $terms = get_the_terms( false, 'post_tag' ) ){
												foreach( $terms as $term ){
													$link = get_term_link( $term, 'post_tag' );
													echo '<li><a href="' . esc_url( $link ) . '">' . $term->name .'</a></li>';
												}
											}
										?>
									</ul>
								</div>
							</div>

						</div>
					<?php endif; ?>
					
				</div>
				
			</div>
			
			<div class="column one single-photo-wrapper">
				
				<?php if( mfn_opts_get( 'share' ) ): ?>
					<div class="share_wrapper">
						<span class='st_facebook_vcount' displayText='Facebook'></span>
						<span class='st_twitter_vcount' displayText='Tweet'></span>
						<span class='st_pinterest_vcount' displayText='Pinterest'></span>						
						
						<script src="http<?php mfn_ssl(1); ?>://w<?php mfn_ssl(1); ?>.sharethis.com/button/buttons.js"></script>
						<script>stLight.options({publisher: "1390eb48-c3c3-409a-903a-ca202d50de91", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
					</div>
				<?php endif; ?>
				
				<?php if( ! post_password_required() ): ?>
					<div class="image_frame scale-with-grid <?php if( ! mfn_opts_get('blog-single-zoom') ) echo 'disabled'; ?>">
						<div class="image_wrapper">
							<?php echo mfn_post_thumbnail( get_the_ID() ); ?>
						</div>
					</div>
				<?php endif; ?>
				
			</div>

<?php } ?>
			
		</div>
	</div>

	<div class="post-wrapper-content">

		<?php
			// Content Builder & WordPress Editor Content
			mfn_builder_print( $post->ID );	
		?>

		<div class="section section-post-footer">
			<div class="section_wrapper clearfix">
			
				<div class="column one post-pager">
					<?php
						// List of pages
						wp_link_pages(array(
							'before'			=> '<div class="pager-single">',
							'after'				=> '</div>',
							'link_before'		=> '<span>',
							'link_after'		=> '</span>',
							'next_or_number'	=> 'number'
						));
					?>
				</div>
				
			</div>
		</div>
		
		<div class="section section-post-about">
			<div class="section_wrapper clearfix">
			
				<?php if( mfn_opts_get( 'blog-author' ) ): ?>
				<div class="column one author-box">
					<div class="author-box-wrapper">
						<div class="avatar-wrapper">
							<?php 
								global $user;
								echo get_avatar( get_the_author_meta('email'), '64', false, get_the_author_meta('display_name', $user['ID']) );
							?>
						</div>
						<div class="desc-wrapper">
							<h5><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author_meta( 'display_name' ); ?></a></h5>
							<div class="desc"><?php the_author_meta('description'); ?></div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>	
		</div>
		
	</div>
			
	<div class="section section-post-related">
		<div class="section_wrapper clearfix">

			<?php
				if( mfn_opts_get( 'blog-related' ) && $aCategories = wp_get_post_categories( get_the_ID() ) ){

					$args = array(
						'category__in'			=> $aCategories,
						'ignore_sticky_posts'	=> true,
						'no_found_rows'			=> true,
						'post__not_in'			=> array( get_the_ID() ),
						'posts_per_page'		=> 3,
						'post_status'			=> 'publish',
					);

					$query_related_posts = new WP_Query( $args );
					if( $query_related_posts->have_posts() ){

						echo '<div class="section-related-adjustment">';
							echo '<h4>'. $translate['related'] .'</h4>';
							
							while ( $query_related_posts->have_posts() ){
								$query_related_posts->the_post();
								
								$classes_rel = '';
								if( ! mfn_post_thumbnail( get_the_ID() ) ) $classes_rel .= 'no-img';
								
								echo '<div class="column one-third post-related '. implode( ' ', get_post_class( $classes_rel ) ).'">';	
									
									if( get_post_format() == 'quote'){

										echo '<blockquote>';
											echo '<a href="'. get_permalink() .'">';
												the_title();
											echo '</a>';
										echo '</blockquote>';
										
									} else {

										echo '<div class="image_frame scale-with-grid">';
											echo '<div class="image_wrapper">';
												echo mfn_post_thumbnail( get_the_ID() );
											echo '</div>';
										echo '</div>';
										
									}
									
									echo '<div class="date_label">'. get_the_date() .'</div>';
								
									echo '<div class="desc">';
										if( get_post_format() != 'quote') echo '<h4><a href="'. get_permalink() .'">'. get_the_title() .'</a></h4>';
										echo '<hr class="hr_color" />';
										echo '<a href="'. get_permalink() .'" class="button button_left button_js"><span class="button_icon"><i class="icon-layout"></i></span><span class="button_label">'. $translate['readmore'] .'</span></a>';
									echo '</div>';
									
								echo '</div>';
							}
							
						echo '</div>';
					}
					wp_reset_postdata();
				}	
			?>
			
		</div>
	</div>
	
	<?php if( mfn_opts_get( 'blog-comments' ) ): ?>
		<div class="section section-post-comments">
			<div class="section_wrapper clearfix">
			
				<div class="column one comments">
					<?php comments_template( '', true ); ?>
				</div>
				
			</div>
		</div>
	<?php endif; ?>

</div>