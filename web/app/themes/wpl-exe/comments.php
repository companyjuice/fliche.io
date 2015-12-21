<?php
/**
 * The template for displaying Comments.
 *
 */
?>

<?php
	global $wpl_exe_wp;
	if ( ! defined( 'ABSPATH' ) ) { exit; }

	/*
	 * If the current post is protected by a password and
	 * the visitor has not yet entered the password we will
	 * return early without loading the comments.
	 */
	if ( post_password_required() || ( !comments_open() && 0 == get_comments_number() ) ) {
		return;
	}
?>

	<div id="comments" class="comments-area">

	<?php
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$required_text = sprintf( ' ' . __('Required fields are marked %s', 'wproto'), '<span class="required">*</span>' );

		$captcha_enabled = $wpl_exe_wp->get_option( 'comments_captcha_enabled', 'general' ); 
		$catcha_only_for_guests = $wpl_exe_wp->get_option( 'comments_captcha_only_for_guests', 'general' ); 
		
		$captcha_input = '';
		
		if( !is_user_logged_in() && $captcha_enabled ):
			ob_start();
			?>
			<div class="row">
				<div class="col-md-12">
					<?php $wpl_exe_wp->controller('captcha')->generate_captcha_phrase(); ?>
				</div>
			</div>
			<?php
			$captcha_input = ob_get_clean();
		endif;

		$comment_form_args = array(
			'fields'	=> apply_filters( 'comment_form_default_fields', array(

				'author' => '<div class="row"><div class="col-md-4"><input class="input-icon-user" id="author" name="author" type="text" placeholder="' . __( 'Full Name&#42;', 'wproto' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',

				'email' => '<div class="col-md-4"><input id="email" class="input-icon-email" name="email" type="text" placeholder="' . __( 'Email Address&#42;', 'wproto' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',

				'url' => '<div class="col-md-4"><input type="url" id="url" name="url" class="input-icon-web" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . __( 'Your Website', 'wproto' ) . '"></div></div>'

				)
			),

			'comment_field'	=> '<div class="row"><div class="col-md-12"><textarea class="input-icon-comment" id="comment" placeholder="' . __( 'Type Here Your Message&#42;', 'wproto' ) . '" name="comment" cols="45" rows="8" aria-required="true"></textarea></div></div>' . $captcha_input,

			'comment_notes_after' => '',

			'must_log_in' => '<p>' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'wproto' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',

			'logged_in_as' => '',

			'comment_notes_before' => '',

		);
	?>
	
	<?php comment_form( $comment_form_args ); ?>

	<?php if ( have_comments() ) : ?>

		<ol class="commentlist">
			<?php
				wp_list_comments( array( 'callback' => 'wproto_comments_callback' ) );
			?>
		</ol><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav role="navigation" id="comment-nav-below" class="site-navigation comment-navigation">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'wproto' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'wproto' ) ); ?></div>
			<div class="clearfix"></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	</div><!-- /comments-->

<?php
/**
	* Comments callback function
 **/
function wproto_comments_callback( $comment, $args, $depth) {
	global $wpl_exe_wp;
	$GLOBALS['comment'] = $comment;
    
	switch ( $comment->comment_type ) :
		case '':
	?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<div class="comment-inside" id="comment-content-<?php comment_ID(); ?>">
			
				<div class="comment-avatar">
					<?php $avatar_size = wpl_exe_wp_utils::is_retina() ? 140 : 70; echo get_avatar( $comment, $avatar_size ); ?>
				</div>
            
				<div class="comment-content">
				
					<div class="comment-data">
						<span class="time-and-reply">
							<span class="time"><?php _e('Posted on', 'wproto'); ?> <time datetime="<?php echo get_comment_date('Y-m-d'); ?>"><span><?php echo get_comment_date('d'); ?></span> <?php echo get_comment_date('F Y'); ?></time></span>  
							<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'comment-content', 'reply_text' => ' <span class="bull">&bull;</span> ' . __( 'Reply', 'wproto' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ), get_comment_ID(), get_the_ID() ); ?>
						</span>
						<h4 class="author">
							<?php $comment_author = get_userdata( $comment->user_id ); echo isset( $comment_author->display_name ) ? $comment_author->display_name : get_comment_author( get_comment_ID() ) ?>
						</h4>
						<div class="clearfix"></div>
					</div>
				
					<div class="comment-text">
						<?php comment_text(); ?>
					</div>
					
				</div>  
				
			</div>

	<?php
		break;
			case 'pingback'  :
			case 'trackback' :
	?>
		<li class="post pingback">
			<div class="comment-data">
				<p><?php _e( 'Pingback', 'wproto' ); ?>: <?php comment_author_link(); ?></p>
			</div>
	<?php
		break;
	endswitch;
}