<?php
	global $wpl_exe_wp;
	$page_settings = $wpl_exe_wp->model('post')->get_post_custom( get_the_ID() );
	$style = isset( $page_settings->breadcrumbs_bar_style ) ? $page_settings->breadcrumbs_bar_style : '';
	
	if( $style == '' ) {
		$style = $wpl_exe_wp->get_option('breadcrumbs_bar_style', 'appearance');
	}
	
	if( $style != 'hidden' ):
?>
<div id="breadcrumbs" class="style-<?php echo esc_attr( $style ); ?>">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-xs-12 page-title">
				<?php if( is_page() ): ?>
					<?php the_title(); ?>
				<?php elseif( is_singular('post') || is_home() ): ?>
					<?php echo esc_html( $wpl_exe_wp->get_option('blog_page_title', 'system_layouts') ); ?>
				<?php elseif( is_singular('product') || function_exists('is_woocommerce') && is_woocommerce() ): ?>
					<?php _e('Shop', 'wproto'); ?>
				<?php elseif( is_singular('wproto_portfolio') ): ?>
					<?php echo esc_html( $wpl_exe_wp->get_option('portfolio_title', 'general') ); ?>
				<?php elseif( is_tax('wproto_portfolio_category') || is_post_type_archive('wproto_portfolio') ): ?>
					<?php echo sprintf( $wpl_exe_wp->get_option('portfolio_cats_page_title', 'system_layouts'), single_term_title('', false) ); ?>
				<?php elseif( is_author() ): ?>
					<?php echo sprintf( $wpl_exe_wp->get_option('author_page_title', 'system_layouts'), get_query_var('author_name')); ?>
				<?php elseif( is_search() ): ?>
					<?php echo sprintf( $wpl_exe_wp->get_option('search_results_page_title', 'system_layouts'), get_query_var('s') ); ?>
				<?php elseif( is_category() ): ?>
					<?php echo sprintf( $wpl_exe_wp->get_option('blog_cats_page_title', 'system_layouts'), single_cat_title('', false) ); ?>
				<?php elseif( is_tag() ): ?>
					<?php echo sprintf( $wpl_exe_wp->get_option('blog_tags_page_title', 'system_layouts'), single_tag_title('', false) ); ?>
				<?php elseif( is_archive() ): ?>
					<?php echo esc_html( $wpl_exe_wp->get_option('blog_archive_page_title', 'system_layouts') ); ?>
				<?php elseif( is_404() ): ?>
					<?php _e('Page not found', 'wproto'); ?>
				<?php elseif( isset( $_GET['wproto_action'] ) && $_GET['wproto_action'] == 'front-reset_password' ): ?>
					<?php _e('Password restore', 'wproto'); ?>
				<?php endif; ?>
			</div>
			<div class="col-md-8 col-xs-12 breadcrumbs-links">
				<span class="breadcrumbs-title"><?php _e('You are here', 'wproto'); ?>:</span> <?php wpl_exe_wp_front::breadcrumbs(); ?>
			</div>
		</div>
	</div>
</div>
<?php
	endif;