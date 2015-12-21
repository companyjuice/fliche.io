<?php
	global $wp_query, $wpl_exe_wp;
	get_header();
	get_template_part('part', 'breadcrumbs');
	
	$style = $wpl_exe_wp->get_option('blog_archive_layout', 'system_layouts');
	
	$read_more_text = $wpl_exe_wp->get_option('blog_read_more_text', 'system_layouts');
	
	if( is_author() ) {
		$style = $wpl_exe_wp->get_option('author_archive_layout', 'system_layouts');
		$read_more_text = $wpl_exe_wp->get_option('author_read_more_text', 'system_layouts');
	}
	if( is_search() ) {
		$read_more_text = $wpl_exe_wp->get_option('search_read_more_text', 'system_layouts');
		if( get_query_var('post_type') == 'product' ) {
			
		} else {
			$style = $wpl_exe_wp->get_option('search_layout', 'system_layouts');
		}
	}
	if( is_post_type_archive('wproto_portfolio') || is_tax( 'wproto_portfolio_category' ) ) {
		$style = $wpl_exe_wp->get_option('portfolio_archive_layout', 'system_layouts');
		$read_more_text = $wpl_exe_wp->get_option('portfolio_read_more_text', 'system_layouts');
	}
?>

	<section class="container" id="content">
		<div class="row">
			<div class="<?php echo wpl_exe_wp_front::content_classes( true ) . ' wproto-primary-content-area'; ?>">
			
				<?php if( is_author() && $wpl_exe_wp->get_option('author_page_display_info', 'system_layouts') ): ?>
					<!--
						ABOUT AUTHOR INFO
					-->
					<?php wpl_exe_wp_front::about_author(); ?>
				<?php endif; ?>
			
				<?php if( is_post_type_archive('wproto_portfolio') || is_tax( 'wproto_portfolio_category' ) ): ?>
					<!--
						PORTFOLIO POSTS
					-->
					<?php echo do_shortcode('[wproto_portfolio read_more_text="' . esc_attr( $read_more_text ) . '" use_wp_query="1" posts_per_page="' . get_option('posts_per_page') . '" order_by="date" sort_by="DESC" style="' . esc_attr( $style ) . '" display_pagination="1" pagination_style="numeric_with_prev_next"]'); ?>
					
				<?php else: ?>
					<!--
						POSTS
					-->
					
					<?php
						$term_description = term_description();
						if( $term_description <> '' ) {
							echo wp_kses_post( $term_description );
						} 
					?>
					
					<?php echo do_shortcode('[wproto_blog read_more_text="' . esc_attr( $read_more_text ) . '" use_wp_query="1" posts_per_page="' . get_option('posts_per_page') . '" order_by="date" sort_by="DESC" style="' . esc_attr( $style ) . '" display_pagination="1" pagination_style="numeric_with_prev_next"]'); ?>
					
				<?php endif; ?>
				<div class="clearfix"></div>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</section>

<?php get_footer();