<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $wpl_exe_wp;	
	$post_style = $wpl_exe_wp->get_option('portfolio_single_style', 'posts');
	$post_style = $wpl_exe_wp->get_post_option('wproto_single_style') ? $wpl_exe_wp->get_post_option('wproto_single_style') : $post_style;
	$portfolio_images = get_post_meta( get_the_ID(), 'wproto_attached_images', true );
	
	// post options
	$display_buy_button = $wpl_exe_wp->get_post_option('display_buy_button');
	$buy_button_text = $wpl_exe_wp->get_post_option('buy_button_custom_text');
	$buy_button_url = $wpl_exe_wp->get_post_option('buy_button_url');
	
	$display_download_button = $wpl_exe_wp->get_post_option('display_download_button');
	$download_button_text = $wpl_exe_wp->get_post_option('download_button_custom_text');
	$download_button_url = $wpl_exe_wp->get_post_option('download_button_url');
	$download_button_url = $download_button_url <> '' ? $download_button_url : wp_get_attachment_url( get_post_thumbnail_id(), 'full' );
	
	$display_categories = $wpl_exe_wp->get_post_option('display_categories');
	$cats = wpl_exe_wp_front::get_categories();
	$display_client = $wpl_exe_wp->get_post_option('display_client');
	$client_name = $wpl_exe_wp->get_post_option('client_name');
	$display_project_link = $wpl_exe_wp->get_post_option('display_link');
	$project_url = $wpl_exe_wp->get_post_option('project_url');
	$display_date = $wpl_exe_wp->get_post_option('display_date');
	$display_share_icons = $wpl_exe_wp->get_post_option('display_share_icons');
?>

<?php if( is_array( $portfolio_images ) && count( $portfolio_images ) > 0 ): ?>

	<div class="single-portfolio-slider">

	<?php foreach( $portfolio_images as $image_id ): ?>
		<div class="bx-item">
			<img src="<?php echo esc_attr( wp_get_attachment_url( $image_id, 'full' ) ); ?>" alt="" />
		</div>
	<?php endforeach; ?>
	
	</div>
	
<?php endif; ?>
	
<div class="portfolio-content-area">

	<div class="container">
		<div class="row">
			<div class="col-md-12 wproto-col-flat">
			
				<h1 class="post-title"><?php the_title(); ?></h1>
				
				<div class="project-info">
					<?php if( $display_categories && $cats <> '' ): ?>
					<span class="item categories"><i class="fa fa-tags"></i> <?php echo $cats; ?></span>
					<?php endif; ?>
					
					<?php if( $display_client && $client_name <> '' ): ?>
					<span class="item client"><i class="fa fa-user"></i> <?php echo esc_html( $client_name ); ?></span>
					<?php endif; ?>
					
					<?php if( $display_project_link && $project_url <> '' ): ?>
					<span class="item project_link"><i class="fa fa-link"></i> <a target="_blank" rel="nofollow" href="<?php echo esc_attr( $project_url ); ?>"><?php echo esc_html( $project_url ); ?></a></span>
					<?php endif; ?>
					
					<?php if( $display_date ): ?>
					<span class="item date"><i class="fa fa-clock-o"></i> <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time( get_option('date_format')); ?></time></span>
					<?php endif; ?>
					
					<?php if( $display_share_icons ): ?>
					<span class="item share"><?php wpl_exe_wp_front::share_links(); ?></span>
					<?php endif; ?>
				</div>
				
				<?php the_content(); ?>
				
				<div class="buttons">
					<?php if( $display_buy_button && $buy_button_text <> '' && $buy_button_url <> '' ): ?><a target="_blank" rel="nofollow" class="button button-style-green" href="<?php echo esc_attr( $buy_button_url ); ?>"><?php echo esc_html( $buy_button_text ); ?></a><?php endif; ?>
				
					<?php if( $display_download_button && $download_button_text <> '' && $download_button_url <> '' ): ?><a target="_blank" class="button button-style-blue" href="<?php echo esc_attr( $download_button_url ); ?>"><?php echo esc_html( $download_button_text ); ?></a><?php endif; ?>
				</div>
			
			</div>
		</div>
	</div>

</div>

<?php if( $wpl_exe_wp->get_option('portfolio_comments', 'posts') && ( !post_password_required() && comments_open() ) ): ?>
<div class="portfolio-comments">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="comments-style-<?php echo esc_attr( $wpl_exe_wp->get_option('portfolio_comments_style', 'posts') ); ?>">
					<?php comments_template( '', true ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if( $wpl_exe_wp->get_option( 'display_related_posts_portfolio', 'posts' ) ): ?>
<div class="portfolio-related-posts">
	<!--
	
		RELATED POSTS
		
	-->
	<?php wpl_exe_wp_front::portfolio_related_posts( get_the_ID(), 'style_1' ); ?>
</div>
<?php endif; ?>

<?php if( $wpl_exe_wp->get_option('show_posts_links_portfolio', 'posts') ): ?>
	<!--
	
		PREV AND NEXT POST LINKS
		
	-->
<div class="wproto-prev-next-posts">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			<?php
				$prev_post = get_adjacent_post( false, '', true);
				if( !empty( $prev_post)) echo '<a class="prevpost" href="' . esc_attr( get_permalink( $prev_post->ID ) ) . '"><span class="desc">' . __('Previous project', 'wproto') . '</span> <span class="title">' . $prev_post->post_title . '</span></a>';
				$next_post = get_adjacent_post( false, '', false);
				if( !empty( $next_post)) echo '<a class="nextpost" href="' . esc_attr( get_permalink( $next_post->ID ) ) . '"><span class="desc">' . __('Next project', 'wproto') . '</span> <span class="title">' . $next_post->post_title . '</a>';
			?>
			<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
<?php endif;