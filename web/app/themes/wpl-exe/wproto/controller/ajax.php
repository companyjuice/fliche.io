<?php
/**
 * AJAX Controller (backend and frontend)
 **/
class wpl_exe_wp_ajax_controller extends wpl_exe_wp_core_controller {
	
	function __construct() {
		
		if( is_admin() ) {
			// Dismiss demo notice
			add_action( 'wp_ajax_wproto_dismiss_demo_data_notice', array( $this, 'dismiss_demo_data_notice' ) );
			// Icon picker dialog
			add_action( 'wp_ajax_wproto_ajax_show_icon_picker_form', array( $this, 'ajax_show_icon_picker_form' ) );
			// widgets editor
			add_action( 'wp_ajax_wproto_edit_widget_settings', array( $this, 'edit_widget_settings' ) );
			// Change post status
			add_action( 'wp_ajax_wproto_ajax_change_post_status', array( $this, 'ajax_change_post_status' ) );
			// Attach images to the post
			add_action( 'wp_ajax_wproto_ajax_get_html_for_attached_images', array( $this, 'get_html_for_attached_images' ) );
		}
		
		// Auth user via AJAX
		add_action( 'wp_ajax_wproto_register_login_form', array( $this, 'ajax_auth' ) );
		add_action( 'wp_ajax_nopriv_wproto_register_login_form', array( $this, 'ajax_auth' ) );
		
		// WooCommerce
		add_action( 'wp_ajax_wproto_get_woocommerce_totals', array( $this, 'ajax_get_woocommerce_totals' ));
		add_action( 'wp_ajax_nopriv_wproto_get_woocommerce_totals', array( $this, 'ajax_get_woocommerce_totals' ));
		
		add_action( 'wp_ajax_wproto_refresh_wishlist_num', array( $this, 'ajax_refresh_wishlist_num' ));
		add_action( 'wp_ajax_nopriv_wproto_refresh_wishlist_num', array( $this, 'ajax_refresh_wishlist_num' ));
		
		// Get latest tweets
		add_action( 'wp_ajax_wproto_get_latest_tweets', array( $this, 'ajax_get_latest_tweets' ) );
		add_action( 'wp_ajax_nopriv_wproto_get_latest_tweets', array( $this, 'ajax_get_latest_tweets' ) );
		
		// AJAX Pagination
		add_action( 'wp_ajax_wproto_ajax_pagination', array( $this, 'ajax_pagination' ) );
		add_action( 'wp_ajax_nopriv_wproto_ajax_pagination', array( $this, 'ajax_pagination' ) );
		
		// Load more posts
		add_action( 'wp_ajax_wproto_load_more_posts', array( $this, 'load_more_posts' ) );
		add_action( 'wp_ajax_nopriv_wproto_load_more_posts', array( $this, 'load_more_posts' ) );
		
	}
	
	/**
	 * Remove demo data info box
	 **/
	function dismiss_demo_data_notice() {
		delete_option('wproto_show_demo_data_message');
		die;
	}
	
	/**
	 * AJAX - Show Wproto Icon Picker Dialog
	 **/
	function ajax_show_icon_picker_form() {
		global $wpl_exe_wp;
		$response = array();
		$icons = array();
		$icons = wpl_exe_wp_admin_utils::get_icons();
		
		ob_start();
			$wpl_exe_wp->view->load_partial( 'dialog/icon_picker', array('icons' => $icons ) );
		$response['html'] = ob_get_clean();
		die( json_encode( $response ) );
	}
	
	
	/**
	 * Show forms for buttons
	 **/
	
	function edit_widget_settings() {
		global $wpl_exe_wp;
		$response = array();
		$data = array();
		$_POST = wp_unslash( $_POST );
		$data = isset( $_POST ) ? $_POST : array();
		
		$template = $_POST['template'];
		
		ob_start();
		
		$wpl_exe_wp->view->load_partial( 'widgets/' . $template, $data );
		
		$response['html'] = ob_get_clean();
		
		die( json_encode( $response ) );
	}
	
	
	/**
	 * AJAX - Change post status
	 **/
	function ajax_change_post_status() {
		
		$response = array();
		
		if ( current_user_can( 'edit_pages' ) ):
		
			$post_id = absint( $_POST['post_id'] );
		
			if( $_POST['post_status'] == 'sticky' ) {
			
				wpl_exe_wp_admin_utils::make_post_sticky( $_POST['post_id'], is_sticky( $post_id ) ? false : true );
			
			}
		
			if( $_POST['post_status'] == 'featured' ) {
			
				if( get_post_meta( $post_id, 'featured', true ) ) {
					// make post default
					update_post_meta( $post_id, 'featured', 0 );
				} else {
					// make post featured
					update_post_meta( $post_id, 'featured', 1 );
				}
			
			}
		
		endif;
		
		die( json_encode( $response ) );
		
	}
	
	
	/**
	 * Get HTML for attached images
	 **/
	function get_html_for_attached_images() {
		global $wpl_exe_wp;
		$response = array();

		ob_start();

		if( is_array( $_POST['images'] ) ) {
			foreach( $_POST['images'] as $image ) {
				
				if( (isset( $_POST['already_attached'] ) && is_array( $_POST['already_attached'] )) && in_array( $image['id'] , $_POST['already_attached'] ) ) {
					continue;
				}
				
				$wpl_exe_wp->view->load_partial( 'metaboxes/attached_images_item', array( 'id' => $image['id'] ) );
			}
		}
		
		$response['html'] = ob_get_clean();
		
		die( json_encode( $response ) );
	}
	
	/**
	 * Login / register / lost password actions via AJAX
	 */
	public function ajax_auth() {
		global $wpl_exe_wp;
		
		$type = $_POST['type'];
		$login = $_POST['login'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
			
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
			
		$answer = array(
			'status' => 'error'
		);
			
		switch( $type ) {
			case('login'):
				
				$login = sanitize_text_field( $login );
				
				$auth = wp_authenticate( $login, $password );
					
				if ( is_wp_error( $auth ) ) {
					$answer['error_text'] = __('Wrong login or password', 'wproto');
				} else {
						
    			$user = get_user_by( 'login', $login );
     			$user_id = $user->ID;
      		wp_set_current_user( $user_id, $login );
      		wp_set_auth_cookie( $user_id );
      		do_action( 'wp_login', $login );
        		
					$answer['status'] = 'ok';
					$answer['result_text'] = sprintf( __('Hello, %s! You have successfully logged in. A page will be refreshed automatically in 5 seconds.', 'wproto'), $login );
						
				}
				
			break;
			case('register'):
				
				if( trim( $login ) == '' ) {
					$login = $email;
				}
				
				if( get_option('users_can_register') == false ) {
						
					$answer['error_text'] = __('Registration was disabled by administrator', 'wproto');
						
				} else {
						
					// validate input
					if( !validate_username( $login ) ) {
						$answer['error_text'] = __('We have found wrong characters in your username. Letters, digits and "@._ -" chars are allowed.', 'wproto');
					} else if( username_exists( $login ) ) {
						$answer['error_text'] = __('User name already taken by another user', 'wproto');
					} else if( !is_email( $email ) ) {
						$answer['error_text'] = __('Wrong email address', 'wproto');
					} else if( email_exists( $email ) ) {
						$answer['error_text'] = __('Email address already taken by another user', 'wproto');
					} else if( $password != $password2 ) {
						$answer['error_text'] = __('The passwords do not match', 'wproto');
					}
						
					// all is great, register new user
					if( !isset( $answer['error_text'] ) ) {
							
						$user_id = wp_create_user( $login, $password2, $email );
						
						$notify_type = $wpl_exe_wp->get_option( 'signup_notifications', 'general' );
						wp_new_user_notification( $user_id, null, $notify_type );
							
						wp_update_user( array(
							'ID' => $user_id,
							'first_name' => $first_name,
							'last_name' => $last_name
						));
							
    				wp_set_current_user( $user_id, $login );
     				wp_set_auth_cookie( $user_id );
      			do_action( 'wp_login', $login );
							
						$answer['status'] = 'ok';
						$answer['result_text'] = sprintf( __('Hello, %s! You have successfully registered and logged in.', 'wproto'), $login );
							
					}
						
				}
				
			break;
			case('lostpass'):
				
				$login = sanitize_text_field( $login );
				
				if( is_email( $login ) ) {
						
					if( !email_exists( $login ) ) {
						$answer['error_text'] = __('This email is not registered on the website', 'wproto');
					} else {
						$user_data = get_user_by( 'email', $login );
					}
						
				} else {
						
					if( !username_exists( $login ) ) {
						$answer['error_text'] = __('This user name is not registered on the website', 'wproto');
					} else {
						$user_data = get_user_by( 'login', $login );
					}
						
				}
					
				// send verification email
				if( !isset( $answer['error_text'] ) ) {
						
					global $wpdb, $wp_hasher;
						
					do_action( 'lostpassword_post');
						
					$user_login = $user_data->user_login;
	 				$user_email = $user_data->user_email;
 				
 					do_action( 'retrieve_password', $user_login );
 					do_action( 'retrieve_password', $user_login );
 					
 					$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );
 					
 					if ( ! $allow ) {
						$answer['status'] = 'error';
						$answer['result_text'] = __('Password reset is not allowed for this user', 'wproto');
					} else {
						
	  				$key = wp_generate_password( 20, false );
	  				do_action( 'retrieve_password_key', $user_login, $key );

						if ( empty( $wp_hasher ) ) {
							require_once ABSPATH . WPINC . '/class-phpass.php';
							$wp_hasher = new PasswordHash( 8, true );
						}
						
						$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
						$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
	    				
	   				$message = __('Someone requested that the password be reset for the following account:', 'wproto') . "\r\n\r\n";
	   				$message .= network_home_url( '/' ) . "\r\n\r\n";
	   				$message .= sprintf(__('Username: %s', 'wproto'), $user_login) . "\r\n\r\n";
	   				$message .= __('If this was a mistake, just ignore this email and nothing will happen.', 'wproto') . "\r\n\r\n";
	   				$message .= __('To reset your password, visit the following address:', 'wproto') . "\r\n\r\n";
	   				$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";
	
						if ( is_multisite() )
	  					$blogname = $GLOBALS['current_site']->site_name;
	   				else
	    				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	
						$title = sprintf( __('[%s] Password Reset', 'wproto'), $blogname );
	
						$title = apply_filters('retrieve_password_title', $title);
	 					$message = apply_filters('retrieve_password_message', $message, $key);
	
						wp_mail( $user_email, $title, $message );
	
						$answer['status'] = 'ok';
						$answer['result_text'] = __('Link for password reset has been emailed to you. Please check your email.', 'wproto');
						
					}
						
				}
				
			break;
		}
			
		die( json_encode( $answer ) );
	}
	
	/**
	 * Reset password
	 **/
	function reset_password() {
		global $wpdb;
		
		if( isset( $_GET['key'] ) && isset( $_GET['login'] ) ) {
			
			$login = sanitize_user( $_GET['login'] );
			$key = sanitize_text_field( $_GET['key'] );
			
			if( $wpdb->get_var( $wpdb->prepare( "SELECT user_login FROM $wpdb->users WHERE user_activation_key = '%s'", $key ) ) == $login ) {
				
				$wpdb->update( $wpdb->users, array( 'user_activation_key' => '' ), array( 'user_login' => $login ) );
				
				$user = get_user_by( 'login', $login );
				
				$password = wp_generate_password( 7, false );
				wp_set_password( $password, $user->ID );
				
				if ( is_multisite() )
					$blogname = $GLOBALS['current_site']->site_name;
 				else
 					$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
				
				$title = sprintf( __('[%s] Your new password', 'wproto'), $blogname );
				
				$message = __('Your new password is:', 'wproto') . "\r\n\r\n";
				$message .= $password . "\r\n\r\n";
				
				wp_mail( $user->user_email, $title, $message );
				
				wp_redirect( home_url('/?wproto_message=reset_password&text=' . urlencode( __('Your password was successfully changed and sent to your email.', 'wproto') ) ) );
				
			} else {
				wp_redirect( home_url('/?wproto_error=reset_password&text=' . urlencode( __('Wrong user login or activation key.', 'wproto') ) ) );
			}
			
		} else {
			wp_redirect( home_url('/?wproto_error=reset_password&text=' . urlencode( __('Wrong user login or activation key.', 'wproto') ) ) );
		}
		
		die;
		
	}
	
	/**
	 * AJAX get woocommerce cart totals
	 **/
	
	function ajax_get_woocommerce_totals() {
		global $woocommerce;
		echo strip_tags( $woocommerce->cart->get_cart_contents_count() );
		die;
	}
	
	/**
	 * Refresh wishlist number of elements
	 **/
	function ajax_refresh_wishlist_num() {
		
		$answer = array();
		
		$answer['num'] = wpl_exe_wp_front::get_wishlist_num();
		
		ob_start();
			wpl_exe_wp_front::wishlist();
		$answer['wishlist'] = ob_get_clean();
		
		die( json_encode( $answer ) );
	}
	
	/**
	 * Print latest tweets via AJAX
	 **/
	function ajax_get_latest_tweets() {
		global $wpl_exe_wp;
		
		$twitter_login = $wpl_exe_wp->get_option( 'twitter_login', 'api' );
		$twitter_oauth_token = $wpl_exe_wp->get_option( 'twitter_oauth_token', 'api' );
		$twitter_oauth_token_secret = $wpl_exe_wp->get_option( 'twitter_oauth_token_secret', 'api' );
		$twitter_cunsumer_key = $wpl_exe_wp->get_option( 'twitter_cunsumer_key', 'api' );
		$twitter_cunsumer_secret = $wpl_exe_wp->get_option( 'twitter_cunsumer_secret', 'api' );
		
		$tweets_count = isset( $_POST['count'] ) ? absint( $_POST['count'] ) : 5;
		$style = isset( $_POST['style'] ) ? $_POST['style'] : 'default';
		
		if( $tweets_count == 0 ) $tweets_count = 5;
		
		if( $twitter_login <> '' && $twitter_oauth_token <> '' && $twitter_oauth_token_secret <> '' && $twitter_cunsumer_key <> '' && $twitter_cunsumer_secret <> '' ) {
		
			$tweets = $wpl_exe_wp->controller('oauth')->get_latest_tweets( $tweets_count );
		
			if( is_array( $tweets ) && count( $tweets ) > 0 ) {
				?>
				
				<div class="<?php if( $style == 'default' ): ?>tweets-carousel<?php else: ?>tweets<?php endif; ?> style-<?php echo esc_attr( $style ); ?>">
				
					<?php foreach( $tweets as $tweet ): ?>
					<div>
						<p><?php echo str_replace( '<a', '<a target="_blank"', make_clickable( $tweet['tweet'] ) ); ?></p>
						<?php if( $style == 'widget' ): ?>
						<time><?php echo wp_kses_post( $tweet['time'], wp_kses_allowed_html( 'post' ) ); ?></time>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
				
				</div>
				
				<?php
			} else {
				?>
				
				<p><?php _e('We did not find any tweets, sorry.', 'wproto'); ?></p>
				
				<?php
			}
			
		} else {
			?>
			<p class="tweets-err"><?php _e('Cannot load tweets. Access is not configured in theme options.', 'wproto'); ?></p>
			<?php
		}
		
		die;
	}
	
	/**
	 * Ajax pagination
	 **/
	function ajax_pagination() {
		global $wpl_exe_wp;
		
		$allowed_tags = wp_kses_allowed_html( 'post' );
		
		$response = array();

		if( ! isset( $_POST['data'] ) || ! is_array( $_POST['data'] ) ) die( __('Wrong AJAX Request', 'wproto'));

		$data = $_POST['data'];
		
		$shortcode = isset( $_POST['data']['shortcode'] ) ? wp_kses( $_POST['data']['shortcode'], $allowed_tags ) : 1;
		
		if( $shortcode == '' ) die( __('Wrong AJAX Request', 'wproto'));
		
		$next_page = isset( $_POST['data']['nextPage'] ) ? absint( $_POST['data']['nextPage'] ) : 1;
		$max_pages = isset( $_POST['data']['maxPages'] ) ? absint( $_POST['data']['maxPages'] ) : 0;
		$read_more_text = isset( $_POST['data']['readMoreText'] ) ? wp_kses( $_POST['data']['readMoreText'], $allowed_tags ) : '';
		$style = isset( $_POST['data']['style'] ) ? wp_kses( $_POST['data']['style'], $allowed_tags ) : '';
		$categories = isset( $_POST['data']['qCategories'] ) ? wp_kses( $_POST['data']['qCategories'], $allowed_tags ) : '';
		$type = isset( $_POST['data']['qType'] ) ? wp_kses( $_POST['data']['qType'], $allowed_tags ) : '';
		$featured = isset( $_POST['data']['qFeatured'] ) ? absint( $_POST['data']['qFeatured'] ) : 0;
		$sort_by = isset( $_POST['data']['qSortBy'] ) ? wp_kses( $_POST['data']['qSortBy'], $allowed_tags ) : 'ASC';
		$order_by = isset( $_POST['data']['qOrderBy'] ) ? wp_kses( $_POST['data']['qOrderBy'], $allowed_tags ) : 'date';
		$author = isset( $_POST['data']['qAuthor'] ) ? wp_kses( $_POST['data']['qAuthor'], $allowed_tags ) : '';
		$posts_per_page = isset( $_POST['data']['qPostsPerPage'] ) ? absint( $_POST['data']['qPostsPerPage'] ) : 0;

		$args = array(
			'ajax_load' => true,
			'paged' => $next_page,
			'read_more_text' => $read_more_text,
			'style' => $style,
			'categories' => $categories,
			'cat_query_type' => $type,
			'featured' => $featured,
			'sort_by' => $sort_by,
			'order_by' => $order_by,
			'author_id' => $author,
			'posts_per_page' => $posts_per_page
		);

		if( $shortcode == 'wproto_blog' ) {
			$response['html'] = $wpl_exe_wp->controller('front_shortcodes')->wproto_blog( $args );
		}
		
		if( $shortcode == 'wproto_portfolio' ) {
			$response['html'] = $wpl_exe_wp->controller('front_shortcodes')->wproto_portfolio( $args );
		}
		
		if( $shortcode == 'wproto_shop_product' ) {
			$response['html'] = $wpl_exe_wp->controller('front_shortcodes')->wproto_shop_product( $args );
		}

		$response['next_page'] = $next_page + 1;
		$response['current_page'] = $next_page;
		
		if( $response['next_page'] > $max_pages ) {
			$response['hide_link'] = true;
		}
		
		die( json_encode( $response ) );
	}
	
	/**
	 * Load more posts
	 **/
	function load_more_posts() {
		global $wpl_exe_wp;
		
		$allowed_tags = wp_kses_allowed_html( 'post' );
		
		$data = array();
		
		$max_pages = isset( $_POST['max_pages'] ) ? absint( $_POST['max_pages'] ) : 0;
		$current_page = isset( $_POST['current_page'] ) ? absint( $_POST['current_page'] ) : 0;
		$next_page = isset( $_POST['next_page'] ) ? absint( $_POST['next_page'] ) : 0;
		$data['posts_type'] = isset( $_POST['post_type'] ) ? wp_kses( $_POST['post_type'], $allowed_tags ) : '';
		$data['count'] = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : get_option('posts_per_page');
		$data['sort'] = isset( $_POST['sort'] ) ? wp_kses( $_POST['sort'], $allowed_tags ) : '';
		$data['order_by'] = isset( $_POST['orderby'] ) ? wp_kses( $_POST['orderby'], $allowed_tags ) : '';
		
		$data['display_links'] = isset( $_POST['display_links'] ) ? wp_kses( $_POST['display_links'], $allowed_tags ) : '';
		$data['display_title'] = isset( $_POST['display_title'] ) ? wp_kses( $_POST['display_title'], $allowed_tags ) : '';

 		$tax_name = '';
 		
 		if( $data['posts_type'] == 'wproto_portfolio' ) {
 			$tax_name = 'wproto_portfolio_category';
 		} 
 		
 		$data['tax_name'] = $tax_name;

		$response = array();
		
		$data['posts'] = $wpl_exe_wp->model('post')->get( '', $data['count'], '', $data['order_by'], $data['sort'], $data['posts_type'], $tax_name, false, false, false, $next_page );
		
		ob_start();
			while( $data['posts']->have_posts() ): $data['posts']->the_post();
				$wpl_exe_wp->view->load_partial( 'shortcodes/masonry_gallery_item', $data );
			endwhile;
		
		$response['html'] = ob_get_clean();
		wp_reset_postdata();
		
		$response['next_page'] = $next_page + 1;
		$response['current_page'] = $next_page;
		
		if( $response['next_page'] > $max_pages ) {
			$response['hide_link'] = true;
		}
		
		die( json_encode( $response ) );
	}
	
}