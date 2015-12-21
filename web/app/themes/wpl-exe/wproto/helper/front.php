<?php

class wpl_exe_wp_front {
	
	/**
	 * Top bar
	 **/
	public static function top_bar() {
		global $wpl_exe_wp;
		
		$display_top_bar = $wpl_exe_wp->get_option('show_top_bar', 'appearance');
		
		$header_style = $wpl_exe_wp->get_option('header_style', 'appearance');
		
		if( is_singular() || is_page() ) {
			$_header_style = $wpl_exe_wp->get_post_option('wproto_header_style');
			if( $_header_style <> '' ) $header_style = $_header_style;
		}
		
		if( $header_style == 'slider' ) $display_top_bar = false;
		
		if( $display_top_bar ):
		
			$top_bar_style = $wpl_exe_wp->get_option('top_bar_style', 'appearance');
			
			if( is_singular() || is_page() ) {
				$_top_bar_style = $wpl_exe_wp->get_post_option('wproto_force_top_bar_style');
				if( $_top_bar_style <> '' ) $top_bar_style = $_top_bar_style;
			}
			
			$top_bar_address = $wpl_exe_wp->get_option('top_bar_address', 'appearance');
			$top_bar_address_on_desktop = $wpl_exe_wp->get_option('top_bar_address_on_desktop', 'appearance');
			$top_bar_address_on_tablets = $wpl_exe_wp->get_option('top_bar_address_on_tablets', 'appearance');
			$top_bar_address_on_phones = $wpl_exe_wp->get_option('top_bar_address_on_phones', 'appearance');
			
			$top_bar_phone = $wpl_exe_wp->get_option('top_bar_phone', 'appearance');
			$top_bar_phone_on_desktop = $wpl_exe_wp->get_option('top_bar_phone_on_desktop', 'appearance');
			$top_bar_phone_on_tablets = $wpl_exe_wp->get_option('top_bar_phone_on_tablets', 'appearance');
			$top_bar_phone_on_phones = $wpl_exe_wp->get_option('top_bar_phone_on_phones', 'appearance');
			
			$top_bar_email = $wpl_exe_wp->get_option('top_bar_email', 'appearance');
			$top_bar_email_on_desktop = $wpl_exe_wp->get_option('top_bar_email_on_desktop', 'appearance');
			$top_bar_email_on_tablets = $wpl_exe_wp->get_option('top_bar_email_on_tablets', 'appearance');
			$top_bar_email_on_phones = $wpl_exe_wp->get_option('top_bar_email_on_phones', 'appearance');
			
			$top_bar_skype = $wpl_exe_wp->get_option('top_bar_skype', 'appearance');
			$top_bar_skype_on_desktop = $wpl_exe_wp->get_option('top_bar_skype_on_desktop', 'appearance');
			$top_bar_skype_on_tablets = $wpl_exe_wp->get_option('top_bar_skype_on_tablets', 'appearance');
			$top_bar_skype_on_phones = $wpl_exe_wp->get_option('top_bar_skype_on_phones', 'appearance');
			
			$top_bar_working_hours = $wpl_exe_wp->get_option('top_bar_working_hours', 'appearance');
			$top_bar_wh_on_desktop = $wpl_exe_wp->get_option('top_bar_wh_on_desktop', 'appearance');
			$top_bar_wh_on_tablets = $wpl_exe_wp->get_option('top_bar_wh_on_tablets', 'appearance');
			$top_bar_wh_on_phones = $wpl_exe_wp->get_option('top_bar_wh_on_phones', 'appearance');
			
			$top_bar_free_text = $wpl_exe_wp->get_option('top_bar_free_text', 'appearance');
			$top_bar_ft_on_deskop = $wpl_exe_wp->get_option('top_bar_ft_on_deskop', 'appearance');
			$top_bar_ft_on_tablets = $wpl_exe_wp->get_option('top_bar_ft_on_tablets', 'appearance');
			$top_bar_ft_on_phones = $wpl_exe_wp->get_option('top_bar_ft_on_phones', 'appearance');
			
			$display_social_icons_top_bar_desktop = $wpl_exe_wp->get_option('display_social_icons_top_bar_desktop', 'appearance');
			$display_social_icons_top_bar_tablets = $wpl_exe_wp->get_option('display_social_icons_top_bar_tablets', 'appearance');
			$display_social_icons_top_bar_phones = $wpl_exe_wp->get_option('display_social_icons_top_bar_phones', 'appearance');
			
			$display_login_top_bar_desktop = $wpl_exe_wp->get_option('display_login_top_bar_desktop', 'appearance');
			$display_login_top_bar_tablets = $wpl_exe_wp->get_option('display_login_top_bar_tablets', 'appearance');
			//$display_login_top_bar_phones = $wpl_exe_wp->get_option('display_login_top_bar_phones', 'appearance');
			$display_login_top_bar_phones = false;
			
			$display_wishlist_top_bar_desktop = $wpl_exe_wp->get_option('display_wishlist_top_bar_desktop', 'appearance');
			$display_wishlist_top_bar_tablets = $wpl_exe_wp->get_option('display_wishlist_top_bar_tablets', 'appearance');
			//$display_wishlist_top_bar_phones = $wpl_exe_wp->get_option('display_wishlist_top_bar_phones', 'appearance');
			$display_wishlist_top_bar_phones = false;
		
		?>
		<header id="top-bar" class="style-<?php echo esc_attr( $top_bar_style ); ?>">
			<div class="container">
				<div class="row">
					<div class="col col-md-12">
						
						<div class="top-bar-item social-icons <?php echo $display_social_icons_top_bar_desktop ? 'display-on-desktop' : 'hide-on-desktop'; ?> <?php echo $display_social_icons_top_bar_tablets ? 'display-on-tablets' : 'hide-on-tablets'; ?> <?php echo $display_social_icons_top_bar_phones ? 'display-on-phones' : 'hide-on-phones'; ?>">
							<?php wpl_exe_wp_front::social_icons('global', 'n'); ?>
						</div>
						
						<?php if( wpl_exe_wp_utils::isset_woocommerce() ): ?>
						<div class="top-bar-item item-wishlist <?php echo $display_wishlist_top_bar_desktop ? 'display-on-desktop' : 'hide-on-desktop'; ?> <?php echo $display_wishlist_top_bar_tablets ? 'display-on-tablets' : 'hide-on-tablets'; ?> <?php echo $display_wishlist_top_bar_phones ? 'display-on-phones' : 'hide-on-phones'; ?>">
							<i class="fa fa-heart top-bar-icon"></i> <a href="javascript:;" id="wproto-top-wishlist"><?php _e('Wishlist', 'wproto'); ?>: <span id="wproto-top-wishlist-num"><?php echo wpl_exe_wp_front::get_wishlist_num(); ?></span> <i class="fa caret fa-caret-down"></i></a>
							
							<div id="wproto-top-bar-wishlist-widget">
								<?php
									wpl_exe_wp_front::wishlist();
								?>
							</div>
							
						</div>
						<?php endif; ?>
						
						<div class="top-bar-item item-login <?php echo $display_login_top_bar_desktop ? 'display-on-desktop' : 'hide-on-desktop'; ?> <?php echo $display_wishlist_top_bar_tablets ? 'display-on-tablets' : 'hide-on-tablets'; ?> <?php echo $display_login_top_bar_phones ? 'display-on-phones' : 'hide-on-phones'; ?>">
							<?php if( ! is_user_logged_in() ): ?>
							<i class="fa fa-sign-in top-bar-icon"></i>
							<a href="javascript:;" id="wproto-top-signin"><?php _e('Sign In <span>/</span> <span class="reg">Register</span>', 'wproto'); ?> <i class="fa caret fa-caret-down"></i></a>
							<div id="wproto-top-bar-login-register-widget">
							
								<?php
									$w_style = $wpl_exe_wp->get_option('login_register_top_bar_style', 'appearance');
								?>
							
								<?php echo do_shortcode('[wproto_login_signup allow_register="false" style="' . $w_style . '"]'); ?>
							
							</div>
							<?php else: ?>
							<i class="fa fa-sign-out top-bar-icon"></i> <a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e('Log Out', 'wproto'); ?></a>
							<?php endif; ?> 
						</div>
						
						<?php if( $top_bar_address <> '' ): ?>
						<div class="top-bar-item address <?php echo $top_bar_address_on_desktop ? 'display-on-desktop' : 'hide-on-desktop'; ?> <?php echo $top_bar_address_on_tablets ? 'display-on-tablets' : 'hide-on-tablets'; ?> <?php echo $top_bar_address_on_phones ? 'display-on-phones' : 'hide-on-phones'; ?>">
							<i class="fa fa-map-marker top-bar-icon"></i> <?php echo wp_kses_post( $top_bar_address ); ?>
						</div>
						<?php endif; ?>
						
						<?php if( $top_bar_phone <> '' ): ?>
						<div class="top-bar-item phone <?php echo $top_bar_phone_on_desktop ? 'display-on-desktop' : 'hide-on-desktop'; ?> <?php echo $top_bar_phone_on_tablets ? 'display-on-tablets' : 'hide-on-tablets'; ?> <?php echo $top_bar_phone_on_phones ? 'display-on-phones' : 'hide-on-phones'; ?>">
							<i class="fa fa-mobile-phone top-bar-icon"></i> <?php echo wp_kses_post( $top_bar_phone ); ?>
						</div>
						<?php endif; ?>
						
						<?php if( $top_bar_email <> '' ): ?>
						<div class="top-bar-item email <?php echo $top_bar_email_on_desktop ? 'display-on-desktop' : 'hide-on-desktop'; ?> <?php echo $top_bar_email_on_tablets ? 'display-on-tablets' : 'hide-on-tablets'; ?> <?php echo $top_bar_email_on_phones ? 'display-on-phones' : 'hide-on-phones'; ?>">
							<i class="fa fa-paper-plane top-bar-icon"></i> <?php echo wpl_exe_wp_front::emailize( esc_html( $top_bar_email ) ); ?>
						</div>
						<?php endif; ?>
						
						<?php if( $top_bar_skype <> '' ): ?>
						<div class="top-bar-item skype <?php echo $top_bar_skype_on_desktop ? 'display-on-desktop' : 'hide-on-desktop'; ?> <?php echo $top_bar_skype_on_tablets ? 'display-on-tablets' : 'hide-on-tablets'; ?> <?php echo $top_bar_skype_on_phones ? 'display-on-phones' : 'hide-on-phones'; ?>">
							<i class="fa fa-skype top-bar-icon"></i> <?php echo wp_kses_post( $top_bar_skype ); ?>
						</div>
						<?php endif; ?>
						
						<?php if( $top_bar_working_hours <> '' ): ?>
						<div class="top-bar-item wh <?php echo $top_bar_wh_on_desktop ? 'display-on-desktop' : 'hide-on-desktop'; ?> <?php echo $top_bar_wh_on_tablets ? 'display-on-tablets' : 'hide-on-tablets'; ?> <?php echo $top_bar_wh_on_phones ? 'display-on-phones' : 'hide-on-phones'; ?>">
							<i class="fa fa-clock-o top-bar-icon"></i> <?php echo wp_kses_post( $top_bar_working_hours ); ?>
						</div>
						<?php endif; ?>
						
						<?php if( $top_bar_free_text <> '' ): ?>
						<div class="top-bar-item free-text <?php echo $top_bar_ft_on_deskop ? 'display-on-desktop' : 'hide-on-desktop'; ?> <?php echo $top_bar_ft_on_tablets ? 'display-on-tablets' : 'hide-on-tablets'; ?> <?php echo $top_bar_ft_on_phones ? 'display-on-phones' : 'hide-on-phones'; ?>">
							<?php echo wp_kses_post( $top_bar_free_text ); ?>
						</div>
						<?php endif; ?>
						
						<div class="clearfix"></div>
						
					</div>
				</div>
			</div>
		</header>
		<?php
		endif;
	}
	
	/**
	 * Theme head
	 **/
	public static function head() {
		global $wpl_exe_wp;

		$favicon = esc_attr( $wpl_exe_wp->get_option( 'favicon', 'branding' ) );
		
		$apple_touch_57 = esc_attr( $wpl_exe_wp->get_option( 'apple_touch_icon_57x57', 'branding' ) );
		$apple_touch_114 = esc_attr( $wpl_exe_wp->get_option( 'apple_touch_icon_114x114', 'branding' ) );
		$apple_touch_72 = esc_attr( $wpl_exe_wp->get_option( 'apple_touch_icon_72x72', 'branding' ) );
		$apple_touch_144 = esc_attr( $wpl_exe_wp->get_option( 'apple_touch_icon_144x144', 'branding' ) );
		?>
		
		<?php if( $favicon <> '' ): ?>
		<!-- Favicons -->
		<link rel="shortcut icon" href="<?php echo $favicon; ?>" type="image/x-icon">
		<link rel="icon" href="<?php echo $favicon; ?>" type="image/x-icon">
		<?php endif; ?>
		
		<?php if( $apple_touch_57 <> '' ): ?>
		<!-- Standard iPhone --> 
		<link rel="apple-touch-icon" sizes="57x57" href="<?php echo $apple_touch_57; ?>" />
		<?php endif; ?>
		
		<?php if( $apple_touch_114 <> '' ): ?>
		<!-- Retina iPhone --> 
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $apple_touch_114; ?>" />
		<?php endif; ?>
		
		<?php if( $apple_touch_72 <> '' ): ?>
		<!-- Standard iPad --> 
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $apple_touch_72; ?>" />
		<?php endif; ?>
		
		<?php if( $apple_touch_144 <> '' ): ?>
		<!-- Retina iPad --> 
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $apple_touch_144; ?>" />
		<?php endif; ?>
		
		<meta name="generator" content="Powered by <?php echo wp_get_theme(); ?> - premium theme by WPlab.pro" />

		<?php
		

	}
	
	/**
	 * Theme header
	 **/
	public static function header() {
		global $wpl_exe_wp, $woocommerce;
		
		$header_style = esc_attr( $wpl_exe_wp->get_option('header_style', 'appearance') );
		
		$display_menu_search = $wpl_exe_wp->get_option('display_search_bar', 'appearance');
		$display_menu_cart = $wpl_exe_wp->get_option('display_menu_cart', 'appearance');
		
		$enable_onepage = false;
		
		if( is_singular() || is_page() ) {
			$_header_style = $wpl_exe_wp->get_post_option('wproto_header_style');
			if( $_header_style <> '' ) $header_style = $_header_style;
			
			$enable_onepage = $wpl_exe_wp->get_post_option('wproto_display_onepage');
			
		}
		
		$_custom_menu = $wpl_exe_wp->get_post_option('custom_page_menu');
		$menu_id = $_custom_menu <> '' ? $_custom_menu : '';
		?>
		<div id="menu-header-container" class="<?php echo $enable_onepage ? 'enable-one-page-menu' : ''; ?>">
			<header id="header" class="style-<?php echo $header_style; ?>">
	
				<?php if( in_array( $header_style, array('default', 'classic_1', 'classic_2', 'classic_3', 'classic_4', 'slider', 'inverted') ) ): ?>
				
				<div class="container">
					<div class="row">
						<div class="col col-md-12">
				
							<?php wpl_exe_wp_front::logo(); ?>
							
							<nav id="primary-nav-menu">
							
								<div id="header-special">
									
									<?php if( wpl_exe_wp_utils::isset_woocommerce() && $wpl_exe_wp->get_option('display_menu_cart', 'appearance') ): ?>
									
									<div id="wproto-header-cart-widget">
										<?php the_widget( 'WC_Widget_Cart' ); ?>
									</div>
									
									<a href="javascript:;" id="wproto-header-cart-link"><span class="wow bounceIn"><?php echo $woocommerce->cart->get_cart_contents_count(); ?></span></a>
									<?php endif; ?>
									
									<?php if( $wpl_exe_wp->get_option('display_search_bar', 'appearance') ): ?>
									
									<div id="wproto-header-search">
										<?php get_search_form(); ?>
									</div>
									
									<a href="javascript:;" class="menu-displays" id="wproto-header-search-link"></a>
									
									<?php endif; ?>
									
									<a href="javascript:;" id="wproto-mobile-menu"></a>
								</div>
							
								<?php
									wp_nav_menu( array(
										'menu' => $menu_id,
										'theme_location' => 'header_menu',
										'walker' => new wpl_exe_wp_front_nav_menu_walker,
										'menu_id' => 'header-menu',
										'fallback_cb' => false,
										'menu_class' => 'sm sm-simple',
										'container' => false						
									));
								?>
							
							</nav>
							
							<div class="clearfix"></div>
				
						</div>
					</div>
				</div>
				
				<?php elseif( in_array( $header_style, array('centered_logo', 'centered_logo_alt') )  ): ?>
				
				<div class="container">
					<div class="row">
						<div class="col col-md-12 col-logo">
				
							<?php wpl_exe_wp_front::logo(); ?>
							
							<a href="javascript:;" id="wproto-mobile-menu"></a>
							
						</div>
					</div>
				</div>
				
				<div class="wproto-top-menu-container">
				
					<div class="container">
						<div class="row">
							<div class="col col-md-12 col-menu">
								<?php
									wp_nav_menu( array(
										'menu' => $menu_id,
										'theme_location' => 'header_menu',
										'walker' => new wpl_exe_wp_front_nav_menu_walker,
										'menu_id' => 'header-menu',
										'fallback_cb' => false,
										'menu_class' => 'sm sm-simple',
										'container' => false						
									));
								?>
							</div>
						</div>
					</div>
				
				</div>
				<?php endif; ?>
	
			</header>
		</div>
		<?php
	}
	
	/**
	 * Theme Footer
	 **/
	public static function footer() {
		global $wpl_exe_wp;
				
		if( $wpl_exe_wp->get_option('display_speed_info') ):
		?>

<!--
===============================================================================================
WPROTO v<?php echo WPROTO_ENGINE_VERSION; ?> engine by WPlab.Pro
Theme: <?php echo WPROTO_THEME_NAME; ?>

===============================================================================================
Generated with <?php echo get_num_queries(); ?> SQL queries in <?php timer_stop(1); ?> seconds.
===============================================================================================
-->


		<?php
		endif;
		
		echo $wpl_exe_wp->get_option( 'analytics_code', 'general' );
	}
	
	/**
	 * Page preloader
	 **/
	public static function preloader() {
		global $wpl_exe_wp;
		
		if( $wpl_exe_wp->get_option( 'use_preloader', 'general' ) ):
			$preloader_style = $wpl_exe_wp->get_option( 'preloader_style', 'general' );
			$preloader_style = $preloader_style <> '' ? $preloader_style : 'pacman';
			
			$custom_preloader = $wpl_exe_wp->get_option( 'preloader_custom_image', 'general' );
			if( $custom_preloader <> '' ):
				$i_width = absint( $wpl_exe_wp->get_option( 'preloader_custom_image_width', 'general' ) );
				$i_width == 0 ? 40 : $i_width;
				$i_height = absint( $wpl_exe_wp->get_option( 'preloader_custom_image_height', 'general' ) );
				$i_height == 0 ? 40 : $i_height;
		?>
		<div id="wproto-preloader">
			<img width="<?php echo $i_width; ?>" height="<?php echo $i_height; ?>" style="margin-top: -<?php echo $i_width / 2; ?>px; margin-left: -<?php echo $i_height / 2; ?>px; " class="custom-preloader-image" src="<?php echo esc_attr( $custom_preloader ); ?>" data-at2x="<?php echo esc_attr( $wpl_exe_wp->get_option( 'preloader_custom_image_2x', 'general' ) ); ?>" alt="" />
		</div>
		<?php
			else:
		?>
		<div id="wproto-preloader">
			<div id="wproto-preloader-inner" class="loader-inner <?php echo esc_attr( $preloader_style ); ?>"></div>
		</div>
		<?php endif; endif;
	}
	
	/**
	 * Page unloader
	 **/
	public static function unloader() {
		global $wpl_exe_wp;
		
		$use_unloader = $wpl_exe_wp->get_option( 'use_unloader', 'general' );
		$unloader_style = $wpl_exe_wp->get_option('unloader_style', 'general');
		
		$unloaders = array(
			'style_1' => '<!-- STYLE 1 --><div id="unloader" data-unload-speed="100" class="pageunload-overlay" data-opening="M20,15 50,30 50,30 30,30 Z;M0,0 80,0 50,30 20,45 Z;M0,0 80,0 60,45 0,60 Z;M0,0 80,0 80,60 0,60 Z" data-closing="M0,0 80,0 60,45 0,60 Z;M0,0 80,0 50,30 20,45 Z;M20,15 50,30 50,30 30,30 Z;M30,30 50,30 50,30 30,30 Z"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none"><path d="M30,30 50,30 50,30 30,30 Z"/></svg></div>',
			'style_2' => '<!-- STYLE 2 --><div id="unloader" data-unload-speed="300" class="pageunload-overlay" data-opening="M 40 -21.875 C 11.356078 -21.875 -11.875 1.3560784 -11.875 30 C -11.875 58.643922 11.356078 81.875 40 81.875 C 68.643922 81.875 91.875 58.643922 91.875 30 C 91.875 1.3560784 68.643922 -21.875 40 -21.875 Z"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="xMidYMid slice"><path d="M40,30 c 0,0 0,0 0,0 0,0 0,0 0,0 0,0 0,0 0,0 0,0 0,0 0,0 Z"/></svg></div>',
			'style_3' => '<!-- STYLE 3 --><div id="unloader" data-unload-speed="400" class="pageunload-overlay" data-opening="M 0,0 c 0,0 63.5,-16.5 80,0 16.5,16.5 0,60 0,60 L 0,60 Z"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none"><path d="M 0,0 c 0,0 -16.5,43.5 0,60 16.5,16.5 80,0 80,0 L 0,60 Z"/></svg></div>',
			'style_4' => '<!-- STYLE 4 --><div id="unloader" data-unload-speed="300" class="pageunload-overlay" data-opening="M 0,0 0,60 80,60 80,0 Z M 40,30 40,30 40,30 40,30 Z"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none"><path d="M 0,0 0,60 80,60 80,0 Z M 80,0 80,60 0,60 0,0 Z"/></svg></div>',
			'style_5' => '<!-- STYLE 5 --><div id="unloader" data-unload-speed="300" class="pageunload-overlay" data-opening="M -18 -26.90625 L -18 86.90625 L 98 86.90625 L 98 -26.90625 L -18 -26.90625 Z M 40 29.96875 C 40.01804 29.96875 40.03125 29.98196 40.03125 30 C 40.03125 30.01804 40.01804 30.03125 40 30.03125 C 39.98196 30.03125 39.96875 30.01804 39.96875 30 C 39.96875 29.98196 39.98196 29.96875 40 29.96875 Z"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="xMidYMid slice"><path d="M -18 -26.90625 L -18 86.90625 L 98 86.90625 L 98 -26.90625 L -18 -26.90625 Z M 40 -25.6875 C 70.750092 -25.6875 95.6875 -0.7500919 95.6875 30 C 95.6875 60.750092 70.750092 85.6875 40 85.6875 C 9.2499078 85.6875 -15.6875 60.750092 -15.6875 30 C -15.6875 -0.7500919 9.2499078 -25.6875 40 -25.6875 Z"/></svg></div>',
			'style_6' => '<!-- STYLE 6 --><div id="unloader" data-unload-speed="400" class="pageunload-overlay" data-opening="M 40,100 150,0 -65,0 z"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none"><path d="M 40,100 150,0 l 0,0 z"/></svg></div>',
			'style_7' => '<!-- STYLE 7 --><div id="unloader" data-unload-speed="200" class="pageunload-overlay" data-opening="M 0,60 80,60 80,50 0,40 0,60;M 0,60 80,60 80,25 0,40 0,60;M 0,60 80,60 80,25 0,10 0,60;M 0,60 80,60 80,0 0,0 0,60" data-closing="M 0,60 80,60 80,20 0,0 0,60;M 0,60 80,60 80,20 0,40 0,60;m 0,60 80,0 0,0 -80,0"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none"><path d="m 0,60 80,0 0,0 -80,0"/></svg></div>',
			'style_8' => '<!-- STYLE 8 --><div id="unloader" data-unload-speed="300" class="pageunload-overlay" data-opening="M 0,0 0,60 80,60 80,0 z M 80,0 40,30 0,60 40,30 z"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none"><path d="M 0,0 0,60 80,60 80,0 Z M 80,0 80,60 0,60 0,0 Z"/></svg></div>',
			'style_9' => '<!-- STYLE 9 --><div id="unloader" data-unload-speed="400" class="pageunload-overlay" data-opening="M 0,0 80,-10 80,60 0,70 0,0" data-closing="M 0,-10 80,-20 80,-10 0,0 0,-10"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none"><path d="M 0,70 80,60 80,80 0,80 0,70"/></svg></div>',
			'style_10' => '<!-- STYLE 10 --><div id="unloader" data-unload-speed="500" class="pageunload-overlay" data-opening="M 40,-65 145,80 -65,80 40,-65" data-closing="m 40,-65 0,0 L -65,80 40,-65"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none"><path d="M 40,-65 145,80 40,-65"/></svg></div>',
			'style_11' => '<!-- STYLE 11 --><div id="unloader" data-unload-speed="400" class="pageunload-overlay" data-opening="m -5,-5 0,70 90,0 0,-70 z m 5,35 c 0,0 15,20 40,0 25,-20 40,0 40,0 l 0,0 C 80,30 65,10 40,30 15,50 0,30 0,30 z"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none" ><path d="m -5,-5 0,70 90,0 0,-70 z m 5,5 c 0,0 7.9843788,0 40,0 35,0 40,0 40,0 l 0,60 c 0,0 -3.944487,0 -40,0 -30,0 -40,0 -40,0 z"/></svg></div>',
			'style_12' => '<!-- STYLE 12 --><div id="unloader" data-unload-speed="400" class="pageunload-overlay" data-opening="m -10,-10 0,80 100,0 0,-80 z m 50,-30.5 0,70.5 0,70 0,-70 z"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none" ><path d="m -10,-10 0,80 100,0 0,-80 z M 40,-40.5 120,30 40,100 -40,30 z"/></svg></div>',
			'style_13' => '<!-- STYLE 13 --><div id="unloader" data-unload-speed="700" class="pageunload-overlay" data-opening="m 40,-80 190,0 -305,290 C -100,140 0,0 40,-80 z"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none" ><path d="m 75,-80 155,0 0,225 C 90,85 100,30 75,-80 z"/></svg></div>',
		);
		
		$style = $unloader_style == 'random' || $unloader_style == NULL ? $unloaders[ array_rand($unloaders) ] : $unloaders[ $unloader_style ];
		
		if( $use_unloader ):
			echo $style;
		endif;
	}
	
	/**
	 * Social icons
	 **/
	public static function social_icons( $type, $gravity='s', $author_id = 0, $post_id = 0 ) {
		global $wpl_exe_wp;

		$target = ! $wpl_exe_wp->get_option( 'social_icons_at_new_tab', 'api' ) ? '' : ' target="_blank" ';

		$social_html = '';
		
		if( $type == 'user' ) {
			$author_metadata = get_metadata( 'user', $author_id );
		}
		
		if( $type == 'post' ) {
			$post_metadata = get_post_custom();
		}

		foreach( $wpl_exe_wp->system_config['social_icons'] as $icon ) {
			
			$url = '';
			
			if( $type == 'global' ) {
				$url = $wpl_exe_wp->get_option( $icon['name'], 'api' );
			} elseif( $type == 'user' ) {
				$url = isset( $author_metadata[ $icon['name'] ][0] ) && $author_metadata[ $icon['name'] ][0] <> '' ? $author_metadata[ $icon['name'] ][0] : '';
			} elseif( $type == 'post' ) {
				$url = isset( $post_metadata[ $icon['name'] ][0] ) && $post_metadata[ $icon['name'] ][0] <> '' ? $post_metadata[ $icon['name'] ][0] : '';
			}
			
			$social_html .= $url <> '' ? '<a ' . $target . ' rel="nofollow" data-gravity="' . esc_attr( $gravity ) . '" href="' . esc_attr( $url ) . '" title="' . esc_attr( $icon['title'] ) . '"><i class="' . esc_attr( $icon['icon'] ) . '"></i></a>' : '';
			
		}

		echo $social_html;

	}
	
	/**
	 * Theme logo
	 **/
	public static function logo() {
		global $wpl_exe_wp;

		$logo_type = $wpl_exe_wp->get_option( 'header_logo', 'branding' );
		
		$blog_name = esc_html( get_bloginfo('name') );
		$description = esc_html( get_bloginfo('description') );
		
		$logo_margin_top = $wpl_exe_wp->get_option( 'custom_logo_margin_top', 'branding' );
		$logo_margin_right = $wpl_exe_wp->get_option( 'custom_logo_margin_right', 'branding' );
		$logo_margin_bottom = $wpl_exe_wp->get_option( 'custom_logo_margin_bottom', 'branding' );
		$logo_margin_left = $wpl_exe_wp->get_option( 'custom_logo_margin_left', 'branding' );
		
		$logo_padding_top = $wpl_exe_wp->get_option( 'custom_logo_padding_top', 'branding' );
		$logo_padding_right = $wpl_exe_wp->get_option( 'custom_logo_padding_right', 'branding' );
		$logo_padding_bottom = $wpl_exe_wp->get_option( 'custom_logo_padding_bottom', 'branding' );
		$logo_padding_left = $wpl_exe_wp->get_option( 'custom_logo_padding_left', 'branding' );
		
		$custom_style_string = '';
		
		if( $logo_margin_top <> '' ) {
			$custom_style_string .= 'margin-top: ' . $logo_margin_top . 'px; ';
		}
		
		if( $logo_margin_right <> '' ) {
			$custom_style_string .= 'margin-right: ' . $logo_margin_right . 'px; ';
		}
		
		if( $logo_margin_bottom <> '' ) {
			$custom_style_string .= 'margin-bottom: ' . $logo_margin_bottom . 'px; ';
		}
		
		if( $logo_margin_left <> '' ) {
			$custom_style_string .= 'margin-left: ' . $logo_margin_left . 'px; ';
		}
		
		if( $logo_padding_top <> '' ) {
			$custom_style_string .= 'padding-top: ' . $logo_padding_top . 'px; ';
		}
		
		if( $logo_padding_right <> '' ) {
			$custom_style_string .= 'padding-right: ' . $logo_padding_right . 'px; ';
		}
		
		if( $logo_padding_bottom <> '' ) {
			$custom_style_string .= 'padding-bottom: ' . $logo_padding_bottom . 'px; ';
		}
		
		if( $logo_padding_left <> '' ) {
			$custom_style_string .= 'padding-left: ' . $logo_padding_left . 'px; ';
		}
		
		if( $logo_type == 'default' ):
			?>
			<a <?php if( !is_front_page() ): ?>href="<?php echo site_url(); ?>"<?php endif; ?> class="text-logo with-desc" style="<?php echo esc_attr( $custom_style_string ); ?>"><span class="logo-text"><?php echo $blog_name; ?></span> <?php if( $description <> '' ): ?><span class="tagline"><?php echo $description; endif; ?></span></a>
			<?php
		elseif( $logo_type == 'text' ):
			?>
			<a <?php if( !is_front_page() ): ?>href="<?php echo site_url(); ?>"<?php endif; ?> class="text-logo without-desc" style="<?php echo esc_attr( $custom_style_string ); ?>"><span class="logo-text"><?php echo $blog_name; ?></span></a>
			<?php
		elseif( $logo_type == 'image' ):
			$custom_logo_url = $wpl_exe_wp->get_option( 'custom_logo_url', 'branding' );
			$custom_logo_url_2x = $wpl_exe_wp->get_option( 'custom_logo_url_2x', 'branding' );
			
			$custom_logo_image = wpl_exe_wp_utils::is_retina() && $custom_logo_url_2x <> '' ? $custom_logo_url_2x : $custom_logo_url;
			
			$size = array();
			
			$custom_logo_width = $wpl_exe_wp->get_option( 'custom_logo_width', 'branding' );
			$custom_logo_height = $wpl_exe_wp->get_option( 'custom_logo_height', 'branding' );

			?>
			<a <?php if( !is_front_page() ): ?>href="<?php echo site_url(); ?>"<?php endif; ?> class="img-logo" style="<?php echo esc_attr( $custom_style_string ); ?>"><img width="<?php echo $custom_logo_width <> '' ? esc_attr( $custom_logo_width ) : ''; ?>" height="<?php echo esc_attr( @$custom_logo_height ); ?>" src="<?php echo esc_attr( @$custom_logo_image ); ?>" alt="" data-no-retina /></a>
			<?php
		endif;

	}
	
	/**
	 * Show breadcrumbs
	 **/
	public static function breadcrumbs( $showOnHome = true, $delimiter = '<span class="part">&bull;</span>', $showCurrent = 1, $before = '<span class="current">', $after = '</span>' ) {
  	global $post, $wp_query, $wpl_exe_wp;
  	
  	$breadcrumbs_enabled = $wpl_exe_wp->get_option( 'display_breadcrumbs', 'general' );
		 
  	if( ! $breadcrumbs_enabled ) {
  		return false;
  	}
  	
		$home = __( 'Home', 'wproto' );
  	$blog = __( 'Blog', 'wproto' );
  	$shop = __( 'Shop', 'wproto' ); 
  	
  	$page_for_posts = get_option( 'page_for_posts' );
		$page_for_posts = $page_for_posts > 0 ? get_permalink( $page_for_posts ) : site_url(); 
  
  	$breadcrumbs_blog_page = $wpl_exe_wp->get_option( 'breadcrumbs_blog_page', 'general' );
  	
  	if( absint( $breadcrumbs_blog_page ) > 0 ) {
  		$page_for_posts = get_permalink( $breadcrumbs_blog_page );
  	}
  
  	$homeLink = home_url() . '/';

		if ( is_front_page() ) {
			
			if ( $showOnHome ) {
				echo '<span class="current">' . $home . '</span>';
			} 
			
		} elseif( is_home() ) {
			
			if ( $showOnHome ) {
				echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ' . $blog;
			} 
			
		} else {

			echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

		}
		
		if( isset( $_GET['wproto_action'] ) && $_GET['wproto_action'] == 'front-reset_password' ) {
			echo $before . __( 'Password restore', 'wproto' ) . '' . $after;
		} elseif ( is_category() ) {
  		
			echo '<a href="' . $page_for_posts . '">' . $blog . '</a>' . $delimiter;
		    
			$thisCat = get_category( get_query_var( 'cat' ), false );
			if ( $thisCat->parent != 0 ) echo get_category_parents( $thisCat->parent, TRUE, ' ' . $delimiter . ' ' );
			echo $before . __( 'Category', 'wproto') . ' "' . single_cat_title( '', false ) . '"' . $after;
  
		} elseif ( is_search() ) {
    		
			echo '<a href="' . $page_for_posts . '">' . $blog . '</a>' . $delimiter;
			echo $before . __( 'Search results for','wproto' ) . ' "' . esc_html( get_search_query() ) . '"' . $after;

		} elseif ( is_day() ) {

			echo '<a href="' . $page_for_posts . '">' . $blog . '</a>' . $delimiter;
			echo '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
			echo '<a href="' . get_month_link( get_the_time( 'Y' ),get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('d') . $after;
  
		} elseif ( is_month() ) {

			echo '<a href="' . $page_for_posts . '">' . $blog . '</a>' . $delimiter;
			echo '<a href="' . get_year_link( get_the_time( 'Y' )) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time( 'F' ) . $after;
  
		} elseif ( is_year() ) {

			echo '<a href="' . $page_for_posts . '">' . $blog . '</a>' . $delimiter;
			echo $before . get_the_time( 'Y' ) . $after;

		} elseif ( is_single() && !is_attachment() ) {
  			
			if ( get_post_type() != 'post' && get_post_type() != 'product' ) {
				
				$post_type = get_post_type_object(get_post_type());
				
				$slug = $homeLink;
				
				$label_name_var = $post_type->labels->name;
				
				switch( $post_type->name ) {
					
					default:
						$slug = $post_type->rewrite['slug'] . '/';
					break;
					case( 'wproto_portfolio' ):
					
						$_slug = $wpl_exe_wp->get_option( 'breadcrumbs_portfolio_page', 'general' );
						$slug = absint( $_slug ) > 0 ? get_permalink( $_slug ) : $homeLink . $post_type->rewrite['slug'] . '/';
						
						$_label = $wpl_exe_wp->get_option( 'portfolio_title', 'general' );
						if( $_label != null && $_label <> '' ) {
							$label_name_var = $_label;
						}
					
					break;
					
				}
				
				echo '<a href="' . $slug . '">' . $label_name_var . '</a>';
				if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
			} elseif( get_post_type() == 'product' && wpl_exe_wp_utils::isset_woocommerce() ) {

				echo '<a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) )  . '">' . $shop .  '</a>' . $delimiter . '<span class="current">' . get_the_title() . '</span>';

			} else {

				echo '<a href="' . $page_for_posts . '">' . $blog . '</a>' . $delimiter;

				$cat = get_the_category();
				$cat = $cat[0];
				$cats = get_category_parents( $cat, TRUE, ' ' . $delimiter . ' ');
				
				if ( !is_wp_error( $cats ) ) {
					if ( $showCurrent == 0 ) $cats = preg_replace( "#^(.+)\s$delimiter\s$#", "$1", $cats );
					echo $cats;
				}
				
				if ($showCurrent == 1) echo $before . get_the_title() . $after;

			}

		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
			global $wp_query;
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );
			
			if( $post_type == '' && is_object( $wp_query ) && isset( $wp_query->query_vars['taxonomy'] ) ) {
				$post_type = get_taxonomy( $wp_query->query_vars['taxonomy'] );
			}
			
			$label_name = isset( $post_type->labels->name ) ? $post_type->labels->name : '';
			
			if( function_exists('is_shop') && is_shop() ) {
				echo '<a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) )  . '">' . $shop . '</a>';
			} else if( (get_query_var( 'post_type' ) == 'product') || (wpl_exe_wp_utils::isset_woocommerce() && is_product_category()) ) {
				echo '<a href="' . get_permalink( woocommerce_get_page_id( 'shop' ) )  . '">' . $shop . '</a>' . $delimiter;
			} else {
				echo $before . $label_name . $after . $delimiter;
			}

		} elseif ( is_attachment() ) {
			
			$parent = get_post( $post->post_parent );
			$cat = get_the_category( $parent->ID );
			$cat = isset( $cat[0] ) ? $cat[0] : 0;
			if( $cat > 0 ) {
				echo get_category_parents( $cat, TRUE, ' ' . $delimiter . ' ' );
				echo '<a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a>';
			} else {
				_e( 'Attachment', 'wproto' );
			}

			if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
  
		} elseif ( is_page() && !$post->post_parent ) {
			if ( $showCurrent == 1 ) echo $before . get_the_title() . $after;
		} elseif ( is_page() && $post->post_parent ) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ( $parent_id ) {
				$page = get_page( $parent_id );
				$breadcrumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse( $breadcrumbs );
			for ($i = 0; $i < count( $breadcrumbs ); $i++) {
				echo $breadcrumbs[$i];
				if ($i != count( $breadcrumbs ) -1 ) echo ' ' . $delimiter . ' ';
			}
			if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
  
		} elseif ( is_tag() ) {
			echo $before . __( 'Posts tagged', 'wproto' ) . ' "' . single_tag_title( '', false ) . '"' . $after;
  
		} elseif ( is_author() ) {
			global $author;
			$userdata = get_userdata( $author );
			echo $before . __( 'Articles posted by', 'wproto' ) . ' ' . $userdata->display_name . $after;
  
		} elseif ( is_404() ) {
			echo $before . __( 'Error 404', 'wproto' ) . '' . $after;
		} 
  
  	$tax = isset( $wp_query->query_vars['taxonomy'] ) ? $wp_query->query_vars['taxonomy'] : NULL;
  
		if( $tax != NULL ) {
			
			echo $before . get_queried_object()->name . $after;
			
		}

	}
	
	/**
	 * Page Sidebar
	 **/
	public static function get_sidebar() {
		global $wpl_exe_wp, $post;
		
		$sidebar_enabled = $wpl_exe_wp->get_option( 'display_sidebar', 'appearance' ); 

		$sidebar_type = $wpl_exe_wp->get_option( 'default_sidebar', 'appearance' ); 
		
		$sidebar_name = $wpl_exe_wp->get_option( 'default_sidebar_id', 'appearance' ); 
		
		if( is_404() ) {
			$sidebar_enabled = $wpl_exe_wp->get_option( 'e404_page_sidebar', 'system_layouts' ); 
			$sidebar_type = $wpl_exe_wp->get_option( 'e404_page_sidebar_position', 'system_layouts' ); 
			$sidebar_name = $wpl_exe_wp->get_option( 'e404_page_widget_area', 'system_layouts' );
		}
		
		if( is_search() ) {
			$sidebar_enabled = $wpl_exe_wp->get_option( 'search_page_sidebar', 'system_layouts' ); 
			$sidebar_type = $wpl_exe_wp->get_option( 'search_page_sidebar_position', 'system_layouts' ); 
			$sidebar_name = $wpl_exe_wp->get_option( 'search_page_widget_area', 'system_layouts' );
		}
		
		if( is_category() || is_archive() || is_tag() || is_home() ) {
			$sidebar_enabled = $wpl_exe_wp->get_option( 'blog_archive_page_sidebar', 'system_layouts' ); 
			$sidebar_type = $wpl_exe_wp->get_option( 'blog_archive_sidebar_position', 'system_layouts' ); 
			$sidebar_name = $wpl_exe_wp->get_option( 'blog_archive_widget_area', 'system_layouts' );
		}
		
		if( is_author() ) {
			$sidebar_enabled = $wpl_exe_wp->get_option( 'author_archive_page_sidebar', 'system_layouts' ); 
			$sidebar_type = $wpl_exe_wp->get_option( 'author_archive_sidebar_position', 'system_layouts' ); 
			$sidebar_name = $wpl_exe_wp->get_option( 'author_archive_widget_area', 'system_layouts' );
		}
		
		if( is_tax('wproto_portfolio_category') || is_post_type_archive('wproto_portfolio') ) {
			$sidebar_enabled = $wpl_exe_wp->get_option( 'portfolio_archive_page_sidebar', 'system_layouts' ); 
			$sidebar_type = $wpl_exe_wp->get_option( 'portfolio_archive_sidebar_position', 'system_layouts' ); 
			$sidebar_name = $wpl_exe_wp->get_option( 'portfolio_archive_widget_area', 'system_layouts' );
		}
		
		if( function_exists('is_woocommerce') && is_woocommerce() ) {
			$sidebar_enabled = $wpl_exe_wp->get_option( 'woo_display_sidebar', 'plugins' ); 
			$sidebar_type = $wpl_exe_wp->get_option( 'woo_default_sidebar', 'plugins' ); 
			$sidebar_name = $wpl_exe_wp->get_option( 'woo_default_sidebar_id', 'plugins' );
		}
		
		if( is_single() || is_page() ) {
			$page_settings = $wpl_exe_wp->get_post_settings_obj();
		}
		
		if( function_exists('is_shop') && is_shop() ) {
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( get_option( 'woocommerce_shop_page_id' ) );
		}
		
		if( function_exists('is_cart') && is_cart() ) {
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( get_option( 'woocommerce_cart_page_id' ) );	
		} 
		if( function_exists('is_checkout') && is_checkout() ) {
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( get_option( 'woocommerce_checkout_page_id' ) );
		} 
		if( function_exists('is_account_page') && is_account_page() ) {
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( get_option( 'woocommerce_myaccount_page_id' ) );
		}
		
		if( is_single() || is_page() || ( function_exists('is_shop') && is_shop() ) || (function_exists('is_cart') && is_cart()) || (function_exists('is_checkout') && is_checkout()) || (function_exists('is_account_page') && is_account_page()) ) {			

			if( isset( $page_settings->customize_sidebar ) && $page_settings->customize_sidebar ) {
				$_sidebar_type = $wpl_exe_wp->system_config['appearance']['default_sidebar'];
				$_sidebar_name = $wpl_exe_wp->system_config['appearance']['default_sidebar_id'];
				
				$sidebar_enabled = isset( $page_settings->wproto_page_sidebar ) && $page_settings->wproto_page_sidebar != 'none';
				$sidebar_type = isset( $page_settings->wproto_page_sidebar ) ? $page_settings->wproto_page_sidebar : $_sidebar_type;
				$sidebar_name = isset( $page_settings->wproto_page_sidebar_id ) ? $page_settings->wproto_page_sidebar_id : $_sidebar_name;
				
			}

		}
		
		if( $sidebar_enabled ) {
			echo '<aside class="col-md-3 sidebar sidebar-' . esc_attr( $sidebar_type ) . '">';
			if( wpl_exe_wp_utils::isset_woocommerce() ) {
				do_action('woocommerce_sidebar');
			} 
			if( is_active_sidebar( $sidebar_name ) ) {
				dynamic_sidebar( $sidebar_name );
			}
			echo '</aside>';
		}   
	}
	
	/**
	 * Print content section classes
	 **/
	public static function content_classes( $return = false) {
		global $wpl_exe_wp, $post;
		
		$display_sidebar = $wpl_exe_wp->get_option( 'display_sidebar', 'appearance' ); 

		if( is_single() || is_page() ) {
			$page_settings = $wpl_exe_wp->get_post_settings_obj();
		}
		
		if( function_exists('is_shop') && is_shop() ) {
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( get_option( 'woocommerce_shop_page_id' ) );
		}
		
		if( function_exists('is_cart') && is_cart() ) {
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( get_option( 'woocommerce_cart_page_id' ) );	
		} 
		if( function_exists('is_checkout') && is_checkout() ) {
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( get_option( 'woocommerce_checkout_page_id' ) );
		} 
		if( function_exists('is_account_page') && is_account_page() ) {
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( get_option( 'woocommerce_myaccount_page_id' ) );
		}

		if( is_post_type_archive('product') || (function_exists('is_product_category') && is_product_category()) || (function_exists('is_product') && is_product()) || (function_exists('is_product_tag') && is_product_tag()) ) {
			$display_sidebar = $wpl_exe_wp->get_option( 'woo_display_sidebar', 'plugins' );
		}

		if( is_single() || is_page() || ( function_exists('is_shop') && is_shop() ) || (function_exists('is_cart') && is_cart()) || (function_exists('is_checkout') && is_checkout()) || (function_exists('is_account_page') && is_account_page()) ) {
			if( isset( $page_settings->customize_sidebar ) && $page_settings->customize_sidebar ) {
				$display_sidebar = isset( $page_settings->wproto_page_sidebar ) && $page_settings->wproto_page_sidebar == 'none' ? false : true;
			}
		}
		
		if( is_404() ) {
			$display_sidebar = $wpl_exe_wp->get_option( 'e404_page_sidebar', 'system_layouts' );
		}
		
		if( is_search() ) {
			$display_sidebar = $wpl_exe_wp->get_option( 'search_page_sidebar', 'system_layouts' );			
		}
		
		if( is_category() || is_archive() || is_tag() || is_home() ) {
			$display_sidebar = $wpl_exe_wp->get_option( 'blog_archive_page_sidebar', 'system_layouts' );
		}
		
		if( is_author() ) {
			$display_sidebar = $wpl_exe_wp->get_option( 'authors_page_page_sidebar', 'system_layouts' );			
		}
		
		if( is_tax('wproto_portfolio_category') || is_post_type_archive('wproto_portfolio') ) {
			$display_sidebar = $wpl_exe_wp->get_option( 'portfolio_archive_page_sidebar', 'system_layouts' );
		}
		
		if( is_singular('wproto_portfolio') ) {
			$display_sidebar = false;
		}
		
		if( $display_sidebar ) {
			$classes_string = 'col-md-9';
		} else {
			$classes_string = 'col-md-12';
		}
		
		if( $return ) {
			return $classes_string;
		} else {
			echo $classes_string;
		}
	}
	
	/**
	 * Widgetized footer
	 **/
	public static function widgetized_footer() {
		global $wpl_exe_wp, $post;
		
		$footer_style = $wpl_exe_wp->get_option( 'footer_style', 'appearance' );
		$display_widgetized_footer = $footer_style != 'no_widgets';
		$footer_sidebar = $wpl_exe_wp->get_option( 'widgetized_footer_sidebar_id', 'appearance' );
		
		if( is_single() || is_page() ) {
			$page_settings = $wpl_exe_wp->get_post_settings_obj();
		}
		
		if( function_exists('is_shop') && is_shop() ) {
			$p_id = get_option( 'woocommerce_shop_page_id' );
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( $p_id );
		}
		
		if( function_exists('is_cart') && is_cart() ) {
			$p_id = get_option( 'woocommerce_cart_page_id' );
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( $p_id );	
		} 
		if( function_exists('is_checkout') && is_checkout() ) {
			$p_id = get_option( 'woocommerce_checkout_page_id' );
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( $p_id );
		} 
		if( function_exists('is_account_page') && is_account_page() ) {
			$p_id = get_option( 'woocommerce_myaccount_page_id' );
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( $p_id );
		}
		
		if( is_single() || is_page() || ( function_exists('is_shop') && is_shop() ) || (function_exists('is_cart') && is_cart()) || (function_exists('is_checkout') && is_checkout()) || (function_exists('is_account_page') && is_account_page()) ) {
			
			if( isset( $page_settings->wproto_footer_style ) && $page_settings->wproto_footer_style <> '' ) {
				$footer_style = $page_settings->wproto_footer_style;
				$display_widgetized_footer = $footer_style != 'no_widgets';
				if( $display_widgetized_footer ) $footer_sidebar = isset( $page_settings->wproto_page_footer_sidebar_id ) && $page_settings->wproto_page_footer_sidebar_id <> '' ? $page_settings->wproto_page_footer_sidebar_id : 'sidebar-footer';
			}
			
		}
		
		if( is_404() ) {
			$_fs = $wpl_exe_wp->get_option( 'e404_page_widgetized_footer', 'system_layouts' );
			if( $_fs <> '' ) {
				$footer_style = $_fs;
				$display_widgetized_footer = $footer_style != 'no_widgets';
				if( $display_widgetized_footer ) $footer_sidebar = $wpl_exe_wp->get_option( 'e404_page_footer_widget_area', 'system_layouts' );	
			}			
		}
		
		if( is_search() ) {
			$_fs = $wpl_exe_wp->get_option( 'search_page_widgetized_footer', 'system_layouts' );
			if( $_fs <> '' ) {
				$footer_style = $_fs;
				$display_widgetized_footer = $footer_style != 'no_widgets';
				if( $display_widgetized_footer ) $footer_sidebar = $wpl_exe_wp->get_option( 'search_page_footer_widget_area', 'system_layouts' );	
			}			
		}
		
		if( is_author() ) {
			$_fs = $wpl_exe_wp->get_option( 'authors_page_page_widgetized_footer', 'system_layouts' );
			if( $_fs <> '' ) {
				$footer_style = $_fs;
				$display_widgetized_footer = $footer_style != 'no_widgets';
				if( $display_widgetized_footer ) $footer_sidebar = $wpl_exe_wp->get_option( 'authors_page_page_footer_widget_area', 'system_layouts' );
			}			
		}
		
		if( $display_widgetized_footer ):
		
		?>
		<!-- Widgetized footer -->
		<footer id="footer-widgets">
			<div class="footer footer-style-<?php echo esc_attr( $footer_style ); ?>">
				<div class="container">
					<div class="row">
						<?php dynamic_sidebar( $footer_sidebar ); ?>
					</div>
				</div>
			</div>
		</footer>
		<?php
		endif;
		
	}
	
	/**
	 * Display share / like links
	 **/
	public static function share_links() {
		global $wpl_exe_wp;

		$title = urlencode( get_the_title( get_the_ID() ) );
		$permalink = urlencode( get_permalink( get_the_ID() ) );
		$post_thumb = has_post_thumbnail() ? urlencode( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ) ) : '';
		?>
		<div class="share">
			<h5><?php _e('Share', 'wproto'); ?>:</h5>
			<a rel="nofollow" title="<?php _e('Share on Facebook', 'wproto'); ?>" target="_blank" href="https://www.facebook.com/sharer/sharer.php?display=popup&u=<?php echo $permalink; ?>"><i class="fa fa-facebook-square"></i></a>
			<a rel="nofollow" title="<?php _e('Share on Pinterest', 'wproto'); ?>" target="_blank" href="http://pinterest.com/pin/create/button?description=<?php echo $title; ?>&media=<?php echo $post_thumb; ?>&url=<?php echo $permalink; ?>"><i class="fa fa-pinterest-square"></i></a>
			<a rel="nofollow" title="<?php _e('Share on Google Plus', 'wproto'); ?>" target="_blank" href="https://plus.google.com/share?url=<?php echo $permalink; ?>"><i class="fa fa-google-plus-square"></i></a>
			<a rel="nofollow" title="<?php _e('Share on Twitter', 'wproto'); ?>" target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&url=<?php echo $permalink; ?>"><i class="fa fa-twitter-square"></i></a>
		</div>
		<?php
	}
	
	/**
	 * Get post categories list
	 **/
	public static function get_categories( $separator = ', ' ) {
		
		$post_type = get_post_type();
		
		switch( $post_type ) {
			default:
			case 'post':
				return wpl_exe_wp_front::get_valid_category_list( $separator );
			break;
			case 'wproto_portfolio':
				return get_the_term_list( get_the_ID(), 'wproto_portfolio_category', '', $separator, '' );
			break;
		}
		
	}
	
	public static function get_valid_category_list( $separator = ', ' ) {
		$s = str_replace( ' rel="category"', '', get_the_category_list( $separator ) );
		$s = str_replace( ' rel="category tag"', '', $s );
		return $s;
	}
	
	public static function get_valid_tags_list( $separator = ', ' ) {
		$s = str_replace( ' rel="tag"', '', get_the_tag_list( '', $separator, '' ) );
		return $s;
	}
	
	/**
	 * About author
	 **/
	public static function about_author( $author_id = 0) {
		global $wp_query, $wpl_exe_wp;;
		
		$post_type = get_post_type();
		
		$_is_block_visible = false;
		
		if( $post_type == 'post' ) {
			$_is_block_visible = $wpl_exe_wp->get_option( 'show_author_info', 'posts' );
		}
		
		if( ! $_is_block_visible ) return false;
		
		if( $author_id == 0 ) {
			$current_author = $wp_query->get_queried_object();
			$author_metadata = get_metadata( 'user', $current_author->ID );			
		} else {
			$current_author = get_user_by( 'id', $author_id );
			$author_metadata = get_metadata( 'user', $current_author->ID );	
		}

		?>
		
		<div itemscope itemtype="http://schema.org/Person" class="about-author-info">
		
			<?php
				$thumb_size = wpl_exe_wp_utils::is_retina() ? 200 : 100;
			?>
			<div class="author_image">
				<div class="bg">
					<img src="<?php echo esc_attr( wpl_exe_wp_front::get_avatar_url( get_avatar( $current_author->ID, $thumb_size )) ); ?>" width="100" itemprop="image" />
					<span class="hover_pulse_ray"></span>
				</div>
			</div>
			
			<h4 itemprop="name" class="name"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo esc_html( $current_author->display_name ); ?></a></h4>
		
			<?php if( isset( $author_metadata['wproto_profession'][0] ) && $author_metadata['wproto_profession'][0] <> '' ): ?>
			<div class="profession-title" itemprop="jobTitle"><?php echo esc_html( $author_metadata['wproto_profession'][0] ); ?></div>
			<?php endif; ?>
			
			<div class="icons">
				<?php wpl_exe_wp_front::social_icons( 'user', 's', $current_author->ID ); ?>
			</div>
		
			<?php if( isset( $current_author->description ) && $current_author->description <> '' ): ?>
			<div itemprop="description"><?php echo nl2br( $current_author->description ); ?></div>
			<?php endif; ?>
			
			<div class="clear"></div>

		</div> 
		<?php
	}
	
	public static function get_avatar_url( $get_avatar ){
		preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
	}
	
	/**
	 * Post navigation links
	 **/
	public static function post_nav_links( $post ) {
		global $wpl_exe_wp;
		
		$post_type = get_post_type( $post );
		
		if( ! $wpl_exe_wp->get_option( 'show_posts_links_blog', 'posts' ) ) return false;
		
		?>
		<div class="blog-nav">
			<?php
				$prev_post = get_previous_post();
				if (!empty( $prev_post )):
					$t = wp_get_post_tags( $prev_post->ID );
			?>
			<div class="left">
				<h3><a href="<?php echo get_permalink( $prev_post->ID ); ?>"> <span class="arrow-left"></span><?php echo wp_kses_post( $prev_post->post_title ); ?></a></h3>
				<?php if( is_array( $t ) && count( $t ) > 0 ): ?>
				<span class="tags">
					<?php
						$tags_arr = array();
						foreach( $t as $tag ):
							$tags_arr[] = '<a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a>';
						endforeach;
						echo implode(', ', $tags_arr);
					?>
				</span>
				<?php endif; ?>
			</div>
			<?php
				endif;
				$next_post = get_next_post();
				if (!empty( $next_post )):
					$t = wp_get_post_tags( $next_post->ID );
			?>
			<div class="right">
				<h3><a href="<?php echo get_permalink( $next_post->ID ); ?>"><span class="arrow-right"></span> <?php echo wp_kses_post( $next_post->post_title ); ?></a></h3>
				<?php if( is_array( $t ) && count( $t ) > 0 ): ?>
				<span class="tags">
					<?php
						$tags_arr = array();
						foreach( $t as $tag ):
							$tags_arr[] = '<a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a>';
						endforeach;
						echo implode(', ', $tags_arr);
					?>
				</span>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
		<?php
		
	}
	
	/**
	 * Post thumbnail
	 **/
	public static function thumbnail( $id, $size, $type = 'with_post_link', $from_widget = false ) {
		global $wpl_exe_wp;
		$id = absint( $id );
		
		if( !has_post_thumbnail( $id ) ) return false;
		
		if( ( is_single() || is_page() ) && ! $from_widget ) {
			$page_settings = $wpl_exe_wp->model('post')->get_post_custom( $id );
			if( isset( $page_settings->wproto_post_hide_featured_image ) && $page_settings->wproto_post_hide_featured_image ) {
				return false;
			}
		}
		
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $size );
		
		switch( $type ) {
			
			default:
			case 'with_post_link':
				
				if( isset( $image[0] ) ) {
					echo '<div class="post-thumbnail"><a href="' . get_permalink( $id ) . '"><img class="thumb" src="' . $image[0] . '" alt="" /></a></div>';
				}
			
			break;
			case 'single_thumb':
				
				if( isset( $image[0] ) ) {
					echo '<div class="post-thumbnail single"><img class="thumb" src="' . $image[0] . '" alt="" /></div>';
				}
			
			break;
			case 'with_lightbox':
				
				if( isset( $image[0] ) ) {
					echo '<div class="post-thumbnail"><a title="' . get_the_title() . '" class="lightbox" href="' . $image[0] . '"><img class="thumb" src="' . $image[0] . '" alt="" /></a></div>';
				}
			
			break;
			
		}
		
	}
	
	/**
	 * Pagination
	 **/
	public static function pagination( $pagination_params = array(), $shortcode_params = array(), $load_more_text = '' ) {
		global $wp_query, $wpl_exe_wp;

		if( $load_more_text == '' ) {
			$load_more_text = __('Load more posts...', 'wproto');
		}

		$pagination_params = wp_parse_args( $pagination_params, array(
			'pagination_style' => 'numeric',
			'custom_query' => false,
			'ajax_target' => '',
			'append_type' => 'grid',
 		));

		$q_object = $wp_query;

		if( is_object( $pagination_params['custom_query'] ) ) {
			$q_object = $pagination_params['custom_query'];
		}

		if( $q_object->max_num_pages <= 1 ) return false;

		$permalinks_enabled = get_option('permalink_structure') != '';
		
		if( !isset( $q_object->query['paged'] ) || $q_object->query['paged'] <= 0 ) {
			$paged = 1;
		} else {
			$paged = $q_object->query['paged'];
		}
			
		switch( $pagination_params['pagination_style'] ) {
				
			default:
			case 'numeric':
			case 'numeric_with_prev_next':

				$format = $permalinks_enabled ? 'page/%#%/' : '&paged=%#%';
				$base = $permalinks_enabled && !is_search() ? get_pagenum_link(1) .'%_%' : str_replace( 9999999, '%#%', esc_url( get_pagenum_link( 9999999 ) ) );
				
				echo '<div class="wproto-pagination style-' . $pagination_params['pagination_style'] . '">';

				echo paginate_links( array(
					'format' => $format,
					'base' => $base,
					'current' => max( 1, get_query_var('paged') ),
					'total' => $q_object->max_num_pages,
					'prev_text' => __('Prev', 'wproto'),
					'next_text' => __('Next', 'wproto'),
					'mid_size' => 1
				));
					
				echo '</div>';
				
			break;	
			case 'ajax_load_more':
				
				if( $q_object->max_num_pages <= $paged ) return '';
				
				$q_obj = $q_object->get_queried_object();
				
				$tax = isset( $q_object->tax_query->queries[0]['taxonomy'] ) ? $q_object->tax_query->queries[0]['taxonomy'] : NULL;
				if( $tax != NULL ) $taxonomy = get_taxonomy( $tax );
					
				echo '<div class="wproto-pagination style-ajax-load-more">';
				?>
					
				<a href="javascript:;"
					data-shortcode="<?php echo esc_attr( $pagination_params['shortcode'] ); ?>"
					
					data-q-posts-per-page="<?php echo esc_attr( $shortcode_params['posts_per_page'] );?>" 
					data-q-author="<?php echo esc_attr( $shortcode_params['author_id'] );?>" 
					data-q-order-by="<?php echo esc_attr( $shortcode_params['order_by'] );?>" 
					data-q-sort-by="<?php echo esc_attr( $shortcode_params['sort_by'] );?>" 
					data-q-featured="<?php echo absint( $shortcode_params['featured'] );?>"
					data-q-type="<?php echo esc_attr( $shortcode_params['cat_query_type'] );?>" 
					data-q-categories="<?php echo esc_attr( $shortcode_params['categories'] );?>" 
					data-style="<?php echo esc_attr( $shortcode_params['style'] );?>" 
					data-read-more-text="<?php echo esc_attr( $shortcode_params['read_more_text'] );?>"  
					
					data-append-type="<?php echo esc_attr( $pagination_params['append_type'] ); ?>"
					data-loading-text="<?php _e( 'Loading...', 'wproto' ); ?>"
					data-normal-text="<?php echo esc_attr( $load_more_text ); ?>"
					data-ajax-target="<?php echo esc_attr( $pagination_params['ajax_target'] ); ?>"
					data-current-page="<?php echo esc_attr( $paged ); ?>"
					data-next-page="<?php echo esc_attr( $paged + 1 ); ?>"
					
					data-max-pages="<?php echo isset( $q_object->max_num_pages ) ? esc_attr( $q_object->max_num_pages ) : 1; ?>" 
					class="button wproto-load-more-posts-link"><?php echo esc_attr( $load_more_text ); ?></a>
				<?php
				echo '</div>';
				
			break;
		}
		
		echo '<div class="clearfix"></div>';
	}
	
	/**
	 * Custom length for excerpt
	 **/
	public static function custom_excerpt_length( $length ) {
		global $_wproto_custom_excerpt_len;
		
		if( isset( $_wproto_custom_excerpt_len ) && is_numeric( $_wproto_custom_excerpt_len ) ) {
			
			return absint( $_wproto_custom_excerpt_len );
		}
		
		return $length;
		
	}
	
	/**
	 * Plural form function
	 * @param int
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	**/
	public static function plural_form( $n, $form1, $form2, $form5 ) {
		$n = abs($n) % 100;
		$n1 = $n % 10;
		if ($n > 10 && $n < 20) return $form5;
		if ($n1 > 1 && $n1 < 5) return $form2;
		if ($n1 == 1) return $form1;
		return $form5;
	}
	
	/**
	 * Get wishlist number of products
	 **/
	public static function get_wishlist_num() {
		$num = 0;
		
		if( isset( $_COOKIE['wpl_wproto_wishlist'] ) ) {
			$num = count( json_decode( $_COOKIE['wpl_wproto_wishlist'] ) );
		}
		
		return $num . ' ' . self::plural_form( $num, __('item', 'wproto'), __('items', 'wproto'), __('items', 'wproto') );
	}
	
	/**
	 * Wishlist details
	 **/
	public static function wishlist() {
		
		$items = array();
		
		if( isset( $_COOKIE['wpl_wproto_wishlist'] ) ) {
			$items = json_decode( $_COOKIE['wpl_wproto_wishlist'] );
			$items = !is_array( $items ) ? array() : $items;
		}
		
		echo '<div class="wproto-wishlist-content">';
		
		if( count( $items ) > 0 ) {
			echo '<ul class="wproto_product_list">';
			foreach( $items as $id ):
				
				$product = wc_get_product( $id );
				?>
				<li>
					<a class="product-title" href="<?php echo get_permalink( $id ); ?>">
					
					<div class="thumb">
						<?php if( has_post_thumbnail( $id ) ): ?>
							<?php echo wpl_exe_image( wp_get_attachment_url( get_post_thumbnail_id( $id ) ), 70, 70 ); ?>
						<?php else: ?>
							<?php echo woocommerce_placeholder_img_src(); ?>
						<?php endif; ?>
					</div>
					
					<?php echo wp_kses_post( $product->post->post_title ); ?></a>
					<div class="price"><?php echo $product->get_price_html(); ?></div>
					<div class="stock-type <?php echo $product->is_in_stock() ? 'in-stock' : 'not-available'; ?>">
						<span><?php echo $product->is_in_stock() ? __('In stock', 'wproto') : __('Not available', 'wproto'); ?></span>
					</div>
					<a data-id="<?php echo esc_attr( $id ); ?>" title="<?php _e('Remove', 'wproto'); ?>" class="wproto-remove" href="javascript:;"></a>
					<div class="clearfix"></div>
				</li>
				<?php
			endforeach;
			echo '</ul>';
		} else {
			echo '<p class="empty">' . __('No products were added to the wishlist!', 'wproto') . '</p>';
		}
		
		echo '</div>';
		
		?>
		<div class="wishlist-bottom">
		
			<a href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>" class="button button-style-red continue-shopping button-size-medium"><?php _e('Go shopping', 'wproto'); ?></a>
		
			<?php if( count( $items ) > 0 ): ?>
			<a href="javascript:;" id="wproto-clear-wishlist" class="button btn-clear-all button-size-medium"><?php _e('Clear all', 'wproto'); ?></a>
			<?php endif; ?>
		
		</div>
		<?php
		
	}
	
	/**
	 * Sub-header, fancy header
	 **/
	public static function subheader() {
		global $wpl_exe_wp;
		
		$post_type = get_post_type();
		
		$title = '';

		if( is_single() || is_page() ) {
			$title = get_the_title();
		}
		
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
		if( is_home() || $post_type == 'post' ) {
			$title = $wpl_exe_wp->get_option( 'blog_page_title', 'system_layouts' );
		}
		
		if( is_archive() ) {
			$title = $wpl_exe_wp->get_option( 'blog_archive_page_title', 'system_layouts' );
		}
		
		if( is_single() && $post_type == 'wproto_portfolio' ) {
			$title = $wpl_exe_wp->get_option( 'portfolio_title', 'general' );
		}

		if( is_404() ) {
			$title = __('Error 404', 'wproto');
		} 
		if( is_search() ) {
			$title = __('Search results', 'wproto');
		} 
		if( is_author() ) {
			$title = sprintf( $wpl_exe_wp->get_option( 'author_page_title', 'system_layouts' ), get_query_var('author_name') );
		} 
		if( is_category() ) {
			$title = sprintf( $wpl_exe_wp->get_option( 'blog_cats_page_title', 'system_layouts' ), single_cat_title('', false) );
		} 
		if( is_tag() ) {
			$title = sprintf( $wpl_exe_wp->get_option( 'blog_tags_page_title', 'system_layouts' ), single_tag_title('', false) );		
		} 
		if( is_tax('wproto_portfolio_category') ) {
			$title = sprintf( $wpl_exe_wp->get_option( 'portfolio_cats_page_title', 'system_layouts' ), $term->name );
		} 
		if( is_archive('wproto_portfolio') ) {
			$title = $wpl_exe_wp->get_option( 'portfolio_archive_page_title', 'system_layouts' );
		}
		if( function_exists('is_woocommerce') && is_woocommerce() ) {
			$title = __('Shop', 'wproto');			
		} 
		if( function_exists('is_cart') && is_cart() ) {
			$title = __('Cart', 'wproto');
		} 
		if( function_exists('is_checkout') && is_checkout() ) {
			$title = __('Checkout', 'wproto');	
		} 
		if( function_exists('is_account_page') && is_account_page() ) {
			$title = __('Account', 'wproto');	
		}

		?>
		
		<!-- Page title -->
		<section>
			<div class="page-title">
				<div class="container">
					<div class="row">
						<div class="col-md-5 col-sm-5">
							<h3><?php echo wp_kses_post( $title ); ?></h3>
						</div>
						<div class="col-md-7 col-sm-7 hidden-xs">
							<div class="page-title-address">
							
								<?php wpl_exe_wp_front::breadcrumbs(); ?>
							
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- end Page title -->
		
		<?php
		
	}
	
	/**
	 * String email into link
	 **/
	public static function emailize($str) {
    //Detect and create email
    $mail_pattern = "/([A-z0-9_-]+\@[A-z0-9_-]+\.)([A-z0-9\_\-\.]{1,}[A-z])/";
    $str = preg_replace( $mail_pattern, '<a href="mailto:$1$2">$1$2</a>', $str );
    return $str;
	}
	
	/**
	 * Remove shortcode from string
	 **/
	public static function strip_shortcode( $code, $content ) {
    global $shortcode_tags;

    $stack = $shortcode_tags;
    $shortcode_tags = array($code => 1);

    $content = strip_shortcodes($content);

    $shortcode_tags = $stack;
    return $content;
	}
	
	/**
	 * Get post media from content
	 **/
	public static function get_media( $post_format ) {
		$header_media = '';
		if( in_array( $post_format, array( 'video', 'audio' ) )) {
			$post_content = get_post_field( 'post_content', get_the_ID() );
			
			$media = get_media_embedded_in_content( $post_content );
			if( isset( $media[0] ) && $media[0] <> '' ) {
				$header_media = $media[0];
			} else {
				$media_arr = preg_match('~\[vc_video\s+link\s*=\s*("|\')(?<url>.*?)\1\s*\]~i', $post_content, $matches );
				if( isset( $matches['url'] ) && $matches['url'] <> '' ) {
					$header_media = do_shortcode('[vc_video link="' . $matches['url'] . '"]');
				}
			}
	
		}
		return $header_media;
	}
	
	/**
	 * Is in wishlist
	 **/
	public static function in_wishlist( $product_id ) {
		
		if( isset( $_COOKIE['wordpress_test_cookie'] ) ) {
			$wishlist_elems = array();
			
			if( isset( $_COOKIE['wpl_wproto_wishlist'] ) ) {
				$wishlist_elems = json_decode( $_COOKIE['wpl_wproto_wishlist'] );
				if( !is_array( $wishlist_elems ) ) $wishlist_elems = array();
			}
			
			return in_array( $product_id, $wishlist_elems );
		} else {
			return false;
		}
		
	} 
	
	/**
	 * Get post gallery shortcode
	 **/
	public static function get_gallery() {
		$post_gallery = '';
		$content = get_post_field( 'post_content', get_the_ID() );
		if( has_shortcode( $content, 'gallery') ) {
			$post_gallery_arr = preg_match('/\[gallery ids=[^\]]+\]/', $content, $matches);
			$post_gallery = isset( $matches[0] ) ? $matches[0] : '';
			
		}
		return $post_gallery;
	}
	
	/**
	 * Related posts block for blog posts
	 **/
	public static function blog_related_posts( $post_id ) {
		global $wpl_exe_wp;
		$post_id = absint( $post_id );
		
		if( $post_id == 0 ) return false;

		$show_related_posts = $wpl_exe_wp->get_option( 'display_related_posts_blog', 'posts' );
		$block_title = $wpl_exe_wp->get_option( 'display_related_posts_blog_block_title', 'posts' );
		$posts_count = $wpl_exe_wp->get_option( 'display_related_posts_blog_count', 'posts' );
		$query_type = $wpl_exe_wp->get_option( 'display_related_posts_blog_query_type', 'posts' );
		$show_excerpt = $wpl_exe_wp->get_option( 'display_related_posts_blog_excerpts', 'posts' );
		$show_thumbs = $wpl_exe_wp->get_option( 'display_related_posts_blog_thumbnails', 'posts' );
		$read_more_text = $wpl_exe_wp->get_option( 'related_posts_blog_read_more_text', 'posts' );
				
		if( $show_related_posts ) {
			
			if( $query_type == 'any' ) {
				$related_posts = $wpl_exe_wp->model('post')->get_random_posts( 'post', $posts_count, $show_thumbs );
			} else {
				$related_posts = $wpl_exe_wp->model('post')->get_related_posts( $post_id, $posts_count, 'category', $show_thumbs );
			}
			
			/**
		 	 * Output related posts
		   **/
			if( $related_posts != false && $related_posts->have_posts() ) {
			?>
			<!-- related posts output -->
			<div class="wproto-posts-carousel">
				<?php if( $block_title <> '' ): ?>
				<h3><?php echo esc_html( $block_title ); ?></h3>
				<?php endif; ?>
			
				<div class="items">
				
					<?php while ( $related_posts->have_posts() ): $related_posts->the_post(); $post_format = get_post_format(); ?>
					<div class="item format-<?php echo esc_attr( $post_format ); ?>">
					
						<?php if( $post_format == 'gallery' ): ?>
							<div class="gallery-inner">
							<?php
								$post_gallery = wpl_exe_wp_front::get_gallery();
								global $wpl_exe_wp_related_posts_gallery;
								$wpl_exe_wp_related_posts_gallery = true;
								echo do_shortcode( $post_gallery );
							?>
							</div>
						
						<?php elseif( in_array( $post_format, array('audio', 'video') ) ): $header_media = wpl_exe_wp_front::get_media( $post_format ); ?>
						
							<?php echo $header_media; ?>
						
						<?php else: ?>
					
							<?php if( $show_thumbs && has_post_thumbnail() ): ?>
								<div class="thumb">
									<?php echo wpl_exe_image( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), 270, 150 ); ?>
									<div class="overlay">
									
										<div class="links">
											<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ), 'full' ); ?>" class="lightbox zoom"><span class="hover_pulse_ray"></span></a>
											<a href="<?php the_permalink(); ?>" class="post-link"><span class="hover_pulse_ray"></span></a>
										</div>
									
									</div>
									<div class="clearfix"></div>
								</div>
								
							<?php endif; ?>
					
						<?php endif; ?>
						<div class="inner">
							<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
						
							<div class="meta">
								<span class="meta-item"><?php _e('By', 'wproto'); ?> <?php the_author(); ?></span>
								<span class="meta-item"><?php _e('In', 'wproto'); ?> <?php echo wpl_exe_wp_front::get_categories(); ?></span>
							</div>
						
							<?php if( $show_excerpt ): ?>
								<p><?php echo wp_trim_words( get_the_excerpt(), 16 ); ?></p>
							<?php endif; ?>
							
							<?php if( $read_more_text <> '' ): ?>
								<a href="<?php the_permalink(); ?>" class="read-more"><?php echo esc_html( $read_more_text ); ?></a>
							<?php endif; ?>
							
						</div>
						
					</div>
					<?php endwhile; wp_reset_postdata(); ?>
				
				</div>
			</div>
						
			<?php
			}
			
		}
		
	}
	
	/**
	 * Portfolio related posts block
	 **/
	public static function portfolio_related_posts( $post_id, $style = 'style_1' ) {
		global $wpl_exe_wp;
		$post_id = absint( $post_id );
		
		if( $post_id == 0 ) return false;
		
		$style = esc_attr( $style );

		$show_related_posts = $wpl_exe_wp->get_option( 'display_related_posts_portfolio', 'posts' );
		$block_title = $wpl_exe_wp->get_option( 'display_related_posts_portfolio_block_title', 'posts' );
		$block_desc = $wpl_exe_wp->get_option( 'display_related_posts_portfolio_block_desc', 'posts' );
		$posts_count = $wpl_exe_wp->get_option( 'display_related_posts_portfolio_count', 'posts' );
		$query_type = $wpl_exe_wp->get_option( 'display_related_posts_portfolio_query_type', 'posts' );

		if( $show_related_posts ) {
			
			if( $query_type == 'any' ) {
				$related_posts = $wpl_exe_wp->model('post')->get_random_posts( 'wproto_portfolio', $posts_count, true );
			} else {
				$related_posts = $wpl_exe_wp->model('post')->get_related_posts( $post_id, $posts_count, 'wproto_portfolio_category', true );
			}
			
			/**
		 	 * Output related posts
		   **/
			if( $related_posts != false && $related_posts->have_posts() ) {
			?>

				<?php if( in_array( $style, array('style_1', 'style_3', 'style_4') ) && $block_title <> '' ): ?>
				<h2 class="header_with_line with_subtext centered">
					<?php echo esc_html( $block_title ); ?>
					<?php if( $block_desc <> ''): ?><span class="header-subtext"><?php echo nl2br( $block_desc ); ?></span><?php endif; ?>
				</h2>
				<?php elseif( $style == 'style_2' && $block_title <> '' ): ?>
				<h2 class="header_with_line">
					<?php echo esc_html( $block_title ); ?>
				</h2>
				<?php endif; ?>
			
				<?php if( $style == 'style_1' ): ?>
				<div class="wproto-portfolio-related-posts style_1">
					<?php while ( $related_posts->have_posts() ): $related_posts->the_post(); $img = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ); ?>
					<div class="item">
						<?php echo wpl_exe_image( $img, 384, 258 ); ?>
						<div class="overlay">
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="categories">
								<?php echo wpl_exe_wp_front::get_categories(); ?>
							</div>
							<div class="links">
								<a href="<?php echo esc_attr( $img ); ?>" class="zoom lightbox"><span class="hover_pulse_ray"></span></a>
								<a href="<?php the_permalink(); ?>" class="permalink"><span class="hover_pulse_ray"></span></a>
							</div>
						</div>
					</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
				<?php endif; ?>
				
				<?php if( in_array( $style, array( 'style_2' ) ) ): ?>
				<div class="wproto-portfolio-related-posts style-owl style-<?php echo $style; ?>">
					<?php while ( $related_posts->have_posts() ): $related_posts->the_post(); $img = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ); ?>
					<div class="item">
						<div class="inside">
							<div class="thumb">
								<?php echo wpl_exe_image( $img, 270, 185 ); ?>
								<div class="overlay">
									<div class="links">
										<a href="<?php echo $img; ?>" class="zoom lightbox"><span class="hover_pulse_ray"></span></a>
										<a href="<?php the_permalink(); ?>" class="permalink"><span class="hover_pulse_ray"></span></a>
									</div>
								</div>
							</div>
							<div class="desc">
								<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
								<div class="categories">
									<?php echo wpl_exe_wp_front::get_categories(); ?>
								</div>
							</div>
						</div>
					</div>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
				<?php endif; ?>
				
				<?php if( in_array( $style, array( 'style_3', 'style_4' ) ) ): ?>
				<div class="wproto-portfolio-related-posts style-blocks">
					<div class="container-fluid">
						<div class="row">
						<?php while ( $related_posts->have_posts() ): $related_posts->the_post(); $img = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ); ?>
						<div class="col-md-<?php echo $style == 'style_3' ? '6' : '4'; ?>">
						
							<div class="inside element">
								<?php if( $style == 'style_3' ) echo wpl_exe_image( $img, 570, 360 ); ?>
								<?php if( $style == 'style_4' ) echo wpl_exe_image( $img, 390, 260 ); ?>
								
								<div class="overlay">
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<div class="categories">
										<?php echo wpl_exe_wp_front::get_categories(); ?>
									</div>
									<div class="links">
										<a href="<?php echo $img; ?>" class="zoom lightbox"><span class="hover_pulse_ray"></span></a>
										<a href="<?php the_permalink(); ?>" class="permalink"><span class="hover_pulse_ray"></span></a>
									</div>
								</div>
								
							</div>
						
						</div>
						<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>
				</div>
				<?php endif; ?>
			
			<?php
			}
			
		}

	}
	
}