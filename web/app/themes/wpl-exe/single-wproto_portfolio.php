<?php
	global $wpl_exe_wp;
	get_header();
	get_template_part('part', 'breadcrumbs');
	
	$post_style = $wpl_exe_wp->get_option('portfolio_single_style', 'posts');
	$post_style = $wpl_exe_wp->get_post_option('wproto_single_style') ? $wpl_exe_wp->get_post_option('wproto_single_style') : $post_style;
?>

<?php if( have_posts() ): while ( have_posts() ) : the_post(); ?>
	
	<section id="content" class="style-<?php echo esc_attr( $post_style ); ?>">		
		<?php get_template_part( 'portfolio/' . $post_style ); ?>
	</section>
	
<?php endwhile; endif; ?>

<?php get_footer(); 