<?php
	get_header();
	get_template_part('part', 'breadcrumbs');
?>
	
<section class="container" id="content">
	<div class="row">				
		<!--
			
			WOOCOMMERCE CONTENT
				
		-->
		
		<?php do_action( 'woocommerce_before_main_content' ); ?>
			
			<?php woocommerce_content(); ?>
				
		<?php do_action( 'woocommerce_after_main_content' ); ?>

		<!--
			
			WOOCOMMERCE SIDEBAR
				
		-->
		<?php do_action( 'woocommerce_sidebar' ); ?>
	</div>

</section>

<?php get_footer(); 