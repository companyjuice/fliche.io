<?php
	get_header();
	get_template_part('part', 'breadcrumbs');
?>

<?php if( have_posts() ): while ( have_posts() ) : the_post(); ?>
	
	<section class="container" id="content">
		<div class="row">
			<article <?php post_class( wpl_exe_wp_front::content_classes( true ) . ' wproto-primary-content-area' ); ?> id="post-<?php the_ID(); ?>">
					
				<!--
					
					PAGE CONTENT
						
				-->
				<?php the_content(); ?>
				
				<div class="clearfix"></div>
				
				<?php wp_link_pages('before=<div class="pagination post-pagination">&after=</div>&next_or_number=next'); ?>
				
				<div class="clearfix"></div>
				
			</article>
	
			<?php get_sidebar(); ?>
		</div>
	
	</section>
	
<?php endwhile; endif; ?>

<?php get_footer(); 