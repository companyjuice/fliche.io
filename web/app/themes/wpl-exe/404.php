<?php
	global $wpl_exe_wp;
	get_header();
	get_template_part('part', 'breadcrumbs');
	
	$display_sidebar = $wpl_exe_wp->get_option( 'e404_page_sidebar', 'system_layouts' );
?>

<div class="container">
	<div class="row">
		<div class="<?php if( $display_sidebar ): ?>col-md-9 <?php else: ?>col-md-12 <?php endif; ?> content-404">
			<?php echo apply_filters('wpautop', $wpl_exe_wp->get_option('e404_page_content', 'system_layouts')); ?>			
		</div>
		<?php if( $display_sidebar ) get_sidebar(); ?>
	</div>
</div>

<?php if( $wpl_exe_wp->get_option( 'e404_show_search_form', 'system_layouts' ) ): ?>
<div class="container-search">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php get_footer(); 