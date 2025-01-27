<?php
/**
 * Header functions.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */
 

/* ---------------------------------------------------------------------------
 * Title
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_title' ) )
{
	function mfn_title()
	{
		$title = false;
		if( mfn_opts_get('mfn-seo') && mfn_ID() ){
			if( get_post_meta( mfn_ID(), 'mfn-meta-seo-title', true ) ){
				$title = stripslashes( get_post_meta( mfn_ID(), 'mfn-meta-seo-title', true ) );
			}
		}
		
		return $title;
	}
}


/* ---------------------------------------------------------------------------
 * Meta and Desctiption
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_seo' ) )
{
	function mfn_seo() 
	{
		if( mfn_opts_get('mfn-seo') && mfn_ID() ){
	
			// description
			if( get_post_meta( mfn_ID(), 'mfn-meta-seo-description', true ) ){
				echo '<meta name="description" content="'. stripslashes( get_post_meta( mfn_ID(), 'mfn-meta-seo-description', true ) ) .'" />'."\n";
			} elseif( mfn_opts_get('meta-description') ){
				echo '<meta name="description" content="'. stripslashes( mfn_opts_get('meta-description') ) .'" />'."\n";
			}
			
			// keywords
			if( get_post_meta( mfn_ID(), 'mfn-meta-seo-keywords', true ) ){
				echo '<meta name="keywords" content="'. stripslashes( get_post_meta( mfn_ID(), 'mfn-meta-seo-keywords', true ) ) .'" />'."\n";
			} elseif( mfn_opts_get('meta-keywords') ){
				echo '<meta name="keywords" content="'. stripslashes( mfn_opts_get('meta-keywords') ) .'" />'."\n";
			}
			
		}
	
		// google analytics
		if( mfn_opts_get( 'google-analytics' ) ){
			mfn_opts_show( 'google-analytics' );
		}
	}
}
add_action('wp_seo', 'mfn_seo');


/* ---------------------------------------------------------------------------
 * Google Remarketing Code
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_google_remarketing' ) )
{
	function mfn_google_remarketing() 
	{
		// google remarketing
		if( mfn_opts_get( 'google-remarketing' ) ){
			mfn_opts_show( 'google-remarketing' );
		}
	}
}
add_action('wp_footer', 'mfn_google_remarketing', 100);


/* ---------------------------------------------------------------------------
 * Fonts | Selected in Theme Options
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_fonts_selected' ) )
{
	function mfn_fonts_selected(){
		$fonts = array();
		
		$fonts['content'] 		= mfn_opts_get( 'font-content', 		'Roboto' );
		$fonts['menu'] 			= mfn_opts_get( 'font-menu', 			'Roboto' );
		$fonts['title'] 		= mfn_opts_get( 'font-title', 			'Patua One' );
		$fonts['headings'] 		= mfn_opts_get( 'font-headings', 		'Patua One' );
		$fonts['headingsSmall'] = mfn_opts_get( 'font-headings-small', 	'Roboto' );
		$fonts['blockquote'] 	= mfn_opts_get( 'font-blockquote', 		'Patua One' );
		
		return $fonts;
	}
}


/* ---------------------------------------------------------------------------
 * 960px grid
* --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_is_960' ) )
{
	function mfn_is_960(){
		$grid960 = false;
	
		if( $_GET && key_exists('mfn-960', $_GET) ){		
			$grid960 = true;		
		} elseif( $layoutID = mfn_layout_ID() ){		
			if( get_post_meta( $layoutID, 'mfn-post-grid960', true ) ) $grid960 = true; // separate IF; do NOT connect		
		} elseif( mfn_opts_get('grid960') ){		
			$grid960 = true;		
		}
		
		return $grid960;
	}
}


/* ---------------------------------------------------------------------------
 * Styles
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_styles' ) )
{
	function mfn_styles() 
	{
		$theme_disable = mfn_opts_get( 'theme-disable' );
		
		// wp_enqueue_style ------------------------------------------------------
		wp_enqueue_style( 'style',				get_stylesheet_uri(), false, THEME_VERSION, 'all' );
		
		wp_enqueue_style( 'mfn-base',			THEME_URI .'/css/base.css', false, THEME_VERSION, 'all' );
		wp_enqueue_style( 'mfn-btn',			THEME_URI .'/css/buttons.css', false, THEME_VERSION, 'all' );
		wp_enqueue_style( 'mfn-icons',			THEME_URI .'/fonts/mfn-icons.css', false, THEME_VERSION, 'all' );
		
		wp_enqueue_style( 'mfn-grid',			THEME_URI .'/css/grid.css', false, THEME_VERSION, 'all' );
		wp_enqueue_style( 'mfn-layout',			THEME_URI .'/css/layout.css', false, THEME_VERSION, 'all' );
		wp_enqueue_style( 'mfn-shortcodes',		THEME_URI .'/css/shortcodes.css', false, THEME_VERSION, 'all' );
		wp_enqueue_style( 'mfn-variables',		THEME_URI .'/css/variables.css', false, THEME_VERSION, 'all' );
		
		wp_enqueue_style( 'mfn-style-simple',	THEME_URI .'/css/style-simple.css', false, THEME_VERSION, 'all' );
		
		
		// plugins
		if( ! isset( $theme_disable['entrance-animations'] ) ){
			wp_enqueue_style( 'mfn-animations',	THEME_URI .'/js/animations/animations.min.css', false, THEME_VERSION, 'all' );
		}
			
		wp_enqueue_style( 'mfn-jquery-ui', 		THEME_URI .'/css/ui/jquery.ui.all.css', false, THEME_VERSION, 'all' );
		wp_enqueue_style( 'mfn-jplayer',		THEME_URI .'/css/jplayer/jplayer.blue.monday.css', false, THEME_VERSION, 'all' );
		wp_enqueue_style( 'mfn-prettyPhoto', 	THEME_URI .'/css/prettyPhoto.css', false, THEME_VERSION, 'all' );
		
		
		// rtl | demo -----
		if( $_GET && key_exists('mfn-rtl',$_GET) ) wp_enqueue_style( 'mfn-rtl', THEME_URI .'/rtl.css', false, THEME_VERSION, 'all' );
		
		// Responsive -------------------------------------------------------------
		if( mfn_opts_get('responsive') ){
			if( mfn_is_960() ){
				wp_enqueue_style( 'mfn-responsive-960', THEME_URI .'/css/responsive-960.css', false, THEME_VERSION, 'all' );
			} else {
				wp_enqueue_style( 'mfn-responsive-1240', THEME_URI .'/css/responsive-1240.css', false, THEME_VERSION, 'all' );
			}
			wp_enqueue_style( 'mfn-responsive', THEME_URI .'/css/responsive.css', false, THEME_VERSION, 'all' );
		} else {
			wp_enqueue_style( 'mfn-responsive-off', THEME_URI .'/css/responsive-off.css', false, THEME_VERSION, 'all' );
		}
	
		// Custom Theme Options styles --------------------------------------------
		if( mfn_opts_get( 'static-css' ) && ( ! ( $_GET && key_exists('mfn-c',$_GET) ) ) ){	
			
			// Static | style-static.css
			if( defined( 'STATIC_IN_CHILD' ) && STATIC_IN_CHILD ){
				wp_enqueue_style( 'mfn-style-static', CHILD_THEME_URI .'/style-static.css', false, THEME_VERSION, 'all' );
			} else {
				wp_enqueue_style( 'mfn-style-static', THEME_URI .'/style-static.css', false, THEME_VERSION, 'all' );
			}
			
		} else {
			
			// Predefined Skins		
			if( $_GET && key_exists('mfn-c',$_GET) ){
				$skin = $_GET['mfn-c']; // demo
			} elseif( $layoutID = mfn_layout_ID() ) {
				$skin = get_post_meta( $layoutID, 'mfn-post-skin', true );
			} else {
				$skin = mfn_opts_get('skin','custom');
			}
			
			if( $skin != 'custom' && $skin != 'one' ){
		
				// Predefined Skins
				wp_enqueue_style( 'mfn-skin-'. $skin, THEME_URI .'/css/skins/'. $skin .'/style.css', false, THEME_VERSION, 'all' );
				
			}
		}
		
		// Google Fonts ----------------------------------------------------------
		$google_fonts 	= mfn_fonts( 'all' );
		
		// subset
		$subset 		= mfn_opts_get('font-subset');
		if( $subset ) $subset = '&amp;subset='. str_replace(' ', '', $subset);
		
		// style & weight
		if( $weight = mfn_opts_get('font-weight') ){
			$weight = ':'. implode( ',', $weight );	
		}
	
		$fonts = mfn_fonts_selected();
		foreach( $fonts as $font ){
			
			if( in_array( $font, $google_fonts ) ){
				
				// Google Fonts
				$font_slug = str_replace(' ', '+', $font);
				wp_enqueue_style( $font_slug, 'http'. mfn_ssl() .'://fonts.googleapis.com/css?family='. $font_slug . $weight . $subset );	
			
			}
		}
		
		// Google Font for counters etc.
		wp_enqueue_style( 'Patua+One', 'http'. mfn_ssl() .'://fonts.googleapis.com/css?family=Patua+One' );
		
		// Custom CSS
		wp_enqueue_style( 'mfn-custom', THEME_URI .'/css/custom.css', false, THEME_VERSION, 'all' );
	}
}
add_action( 'wp_enqueue_scripts', 'mfn_styles' );


/* ---------------------------------------------------------------------------
 * Styles | Custom Font
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_styles_custom_font' ) )
{
	function mfn_styles_custom_font()
	{
		$font_custom = mfn_opts_get( 'font-custom' );
		$font_custom2 = mfn_opts_get( 'font-custom2' );
	
		if( $font_custom ){
			echo '<style>'."\n";
				echo '@font-face {';
					echo 'font-family: "'. $font_custom .'";';
					echo 'src: url("'. mfn_opts_get('font-custom-eot') .'");';
					echo 'src: url("'. mfn_opts_get('font-custom-eot') .'#iefix") format("embedded-opentype"),';
						echo 'url("'. mfn_opts_get('font-custom-woff') .'") format("woff"),';
						echo 'url("'. mfn_opts_get('font-custom-ttf') .'") format("truetype"),';
						echo 'url("'. mfn_opts_get('font-custom-svg') .'#'. $font_custom .'") format("svg");';
					echo 'font-weight: normal;';
					echo 'font-style: normal;';
				echo '}'."\n";
			echo '</style>'."\n";
		}
		
		if( $font_custom2 ){
			echo '<style>'."\n";
				echo '@font-face {';
					echo 'font-family: "'. $font_custom2 .'";';
					echo 'src: url("'. mfn_opts_get('font-custom2-eot') .'");';
					echo 'src: url("'. mfn_opts_get('font-custom2-eot') .'#iefix") format("embedded-opentype"),';
						echo 'url("'. mfn_opts_get('font-custom2-woff') .'") format("woff"),';
						echo 'url("'. mfn_opts_get('font-custom2-ttf') .'") format("truetype"),';
						echo 'url("'. mfn_opts_get('font-custom2-svg') .'#'. $font_custom2 .'") format("svg");';
					echo 'font-weight: normal;';
					echo 'font-style: normal;';
				echo '}'."\n";
			echo '</style>'."\n";
		}
	}
}
add_action('wp_head', 'mfn_styles_custom_font');


/* ---------------------------------------------------------------------------
 * Styles | Background
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_styles_background' ) )
{
	function mfn_styles_background()
	{
		$output = '';
		
		// HTML ---------------------------
		if( $layoutID = mfn_layout_ID() ){
			$bg_img = get_post_meta( $layoutID, 'mfn-post-bg', true );
			$bg_pos = get_post_meta( $layoutID, 'mfn-post-bg-pos', true );
		} else {
			$bg_img = mfn_opts_get( 'img-page-bg' );
			$bg_pos = mfn_opts_get( 'position-page-bg' );
		}
	
		if( $bg_img ){
	
			$aBg 	= array();
			$aBg[] 	= 'background-image:url('. $bg_img .')';
	
			if( $bg_pos ){
				$background_attr = explode( ';', $bg_pos );
				$aBg[] 	= 'background-repeat:'. $background_attr[0];
				$aBg[] 	= 'background-position:'. $background_attr[1];
				$aBg[] 	= 'background-attachment:'. $background_attr[2];
				$aBg[] 	= '-webkit-background-size:'. $background_attr[3];
				$aBg[] 	= 'background-size:'. $background_attr[3];
			}
	
			$background = implode('; ', $aBg );
	
			$output .= 'html {'. $background. ';}'."\n";
		}
		
		// Subheader ----------------------
		if( get_post_meta( mfn_ID(), 'mfn-post-subheader-image', true ) ){
			$output .= '#Subheader { background-image: url("'. get_post_meta( mfn_ID(), 'mfn-post-subheader-image', true ) .'");}'."\n";
		} elseif( mfn_opts_get( 'subheader-image' ) ){
			$output .= '#Subheader { background-image: url("'. mfn_opts_get( 'subheader-image' ) .'");}'."\n";
		}
		
		// Echo ---------------------------
		if( $output ){
			echo "\n".'<style>'."\n";
				echo $output;
			echo '</style>'."\n";
		}
		
	}
}
add_action('wp_head', 'mfn_styles_background');


/* ---------------------------------------------------------------------------
 * Styles | Minify
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_styles_minify' ) )
{
	function mfn_styles_minify( $css ){
		
		// remove comments
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );	
		
		// remove whitespace
		$css = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css );
		
		return $css;
	}
}


/* ---------------------------------------------------------------------------
 * Styles | Dynamic
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_styles_dynamic' ) )
{
	function mfn_styles_dynamic()
	{
		echo '<style>'."\n";

			ob_start();

			if( ! mfn_opts_get( 'static-css' ) ){
					
				// Dynamic | style.php & ( style-colors.php || style-one.php || css/skins/.. )
					
				if( $_GET && key_exists('mfn-c',$_GET) ){
					$skin = $_GET['mfn-c']; // demo
				} elseif( $layoutID = mfn_layout_ID() ) {
					$skin = get_post_meta( $layoutID, 'mfn-post-skin', true );
				} else {
					$skin = mfn_opts_get('skin','custom');
				}
					
				if( $skin == 'custom' ){
			
					// Custom Skin
					include_once THEME_DIR . '/style-colors.php';
			
				} elseif( $skin == 'one' ){
			
					// One Click Skin Generator				
					include_once THEME_DIR . '/style-one.php';
					
				}

				include_once THEME_DIR . '/style.php';
			}

			$css = ob_get_contents();
			
			ob_get_clean();
			
			echo mfn_styles_minify( $css ) ."\n";
	
		echo '</style>'."\n";
	}
}
add_action( 'wp_head', 'mfn_styles_dynamic' );


/* ---------------------------------------------------------------------------
 * Styles | Custom Styles
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_styles_custom' ) )
{
	function mfn_styles_custom()
	{	
		// Theme Options > Custom CSS
		if( $custom_css = mfn_opts_get( 'custom-css' ) ){
			echo '<style>'."\n";
				echo $custom_css ."\n";
			echo '</style>'."\n";
		}
		
		// Page Options > Custom CSS
		if( $custom_css = get_post_meta( mfn_ID(), 'mfn-post-css', true ) ){
			echo '<style>'."\n";
				echo $custom_css."\n";
			echo '</style>'."\n";
		}
		
		// Layouts > Custom Colors
		if( $layoutID = mfn_layout_ID() ){
			
			$layout_styles = '';
			
			if( get_post_meta( $layoutID, 'mfn-post-background-subheader', true ) ){
				$layout_styles .= '#Subheader {background-color: '. get_post_meta( $layoutID, 'mfn-post-background-subheader', true ) .';} ';
			}
			if( get_post_meta( $layoutID, 'mfn-post-color-subheader', true ) ){
				$layout_styles .= '#Subheader .title {color: '. get_post_meta( $layoutID, 'mfn-post-color-subheader', true ) .';} ';
				$layout_styles .= '#Subheader ul.breadcrumbs li, #Subheader ul.breadcrumbs li a {color: '. hex2rgba(get_post_meta( $layoutID, 'mfn-post-color-subheader', true ),.6) .';} ';
			}
			
			if( $layout_styles ){
				echo '<style>'."\n";
					echo $layout_styles."\n";
				echo '</style>'."\n";
			}
			
		}
		
		// Demo - Custom Google Fonts for Homepages
		if( $_GET && key_exists('mfn-f', $_GET) ){
			
			$font_slug = str_replace('+', ' ', $_GET['mfn-f']);
			$font_family = str_replace('+', ' ', $font_slug);
			
			wp_enqueue_style( $font_slug, 'http'. mfn_ssl() .'://fonts.googleapis.com/css?family='. $font_slug .':300,400' );
			
			echo '<style>';
				echo 'h1, h2, h3, h4 { font-family: '. $font_family .' !important;}';
			echo '</style>'."\n";
		}
	}
}
add_action('wp_head', 'mfn_styles_custom');


/* ---------------------------------------------------------------------------
 * IE fix
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_ie_fix' ) )
{
	function mfn_ie_fix() 
	{
		if( ! is_admin() )
		{
			echo "\n".'<!--[if lt IE 9]>'."\n";
			echo '<script src="http'. mfn_ssl() .'://html5shiv.googlecode.com/svn/trunk/html5.js"></script>'."\n";
			echo '<![endif]-->'."\n";
		}	
	}
}
add_action('wp_head', 'mfn_ie_fix');


/* ---------------------------------------------------------------------------
 * Scripts
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_scripts' ) )
{
	function mfn_scripts() 
	{
		if( ! is_admin() ){
			
			wp_enqueue_script( 'jquery-ui-core', 		THEME_URI .'/js/ui/jquery.ui.core.js', false, THEME_VERSION, true );
			wp_enqueue_script( 'jquery-ui-widget', 		THEME_URI .'/js/ui/jquery.ui.widget.js', false, THEME_VERSION, true );
			wp_enqueue_script( 'jquery-ui-tabs', 		THEME_URI .'/js/ui/jquery.ui.tabs.js', false, THEME_VERSION, true );
			wp_enqueue_script( 'jquery-ui-accordion',	THEME_URI .'/js/ui/jquery.ui.accordion.js', false, THEME_VERSION, true );
	
			
			wp_enqueue_script( 'jquery-plugins', 		THEME_URI. '/js/jquery.plugins.js', false, THEME_VERSION, true );

			wp_enqueue_script( 'jquery-animations',		THEME_URI. '/js/animations/animations.min.js', false, THEME_VERSION, true );
			wp_enqueue_script( 'jquery-jplayer', 		THEME_URI. '/js/jquery.jplayer.min.js', false, THEME_VERSION, true );
	
			wp_enqueue_script( 'jquery-mfn-menu', 		THEME_URI. '/js/mfn.menu.js', false, THEME_VERSION, true );
			
			
			// scripts config -----------------------------
			mfn_scripts_config();
			
			wp_enqueue_script( 'jquery-scripts', 		THEME_URI. '/js/scripts.js', false, THEME_VERSION, true );
	
			// singular | comment reply
			if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); 
		}
	}
}
add_action('wp_enqueue_scripts', 'mfn_scripts');


/* ---------------------------------------------------------------------------
 * Scripts | Custom JS
* --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_scripts_custom' ) )
{
	function mfn_scripts_custom()
	{
		if( $custom_js = mfn_opts_get( 'custom-js' ) ){
			echo '<script>'."\n";
				echo '//<![CDATA['."\n";
					echo $custom_js ."\n";
				echo '//]]>'."\n";
			echo '</script>'."\n";
		}
	}
}
add_action('wp_footer', 'mfn_scripts_custom', 100);


/* ---------------------------------------------------------------------------
 * Retina logo
* --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_retina_logo' ) )
{
	function mfn_retina_logo()
	{
		// logo - source -------------------------
		if( $layoutID = mfn_layout_ID() ){
				
			$logo_src 		= get_post_meta( $layoutID, 'mfn-post-retina-logo-img', true );
			$logo_sticky 	= get_post_meta( $layoutID, 'mfn-post-sticky-retina-logo-img', true ) ? get_post_meta( $layoutID, 'mfn-post-sticky-retina-logo-img', true ) : $logo_src;
				
		} else {
				
			$logo_src 		= mfn_opts_get( 'retina-logo-img' );
			$logo_sticky 	= mfn_opts_get( 'sticky-retina-logo-img' ) ? mfn_opts_get( 'sticky-retina-logo-img' ) : $logo_src;
			$logo_mobile 	= mfn_opts_get( 'responsive-retina-logo-img' ) ? mfn_opts_get( 'responsive-retina-logo-img' ) : $logo_src;
				
		}
		
		if( $logo_src || $logo_sticky ){
			echo '<script>'."\n";
				echo '//<![CDATA['."\n";
					echo 'jQuery(window).load(function(){'."\n";
						echo 'var retina = window.devicePixelRatio > 1 ? true : false;';
						echo 'if( retina ){';
	
							if( $logo_src ){
								echo 'var retinaEl = jQuery("#logo img.logo-main");';
								echo 'var retinaLogoW = retinaEl.width();';
								echo 'var retinaLogoH = retinaEl.height();';
								echo 'retinaEl';
									echo '.attr( "src", "'. $logo_src .'" )';
									echo '.width( retinaLogoW )';
									echo '.height( retinaLogoH );';
							}
							
							if( $logo_sticky ){
								echo 'var stickyEl = jQuery("#logo img.logo-sticky");';
								echo 'var stickyLogoW = stickyEl.width();';
								echo 'var stickyLogoH = stickyEl.height();';
								echo 'stickyEl';
									echo '.attr( "src", "'. $logo_sticky .'" )';
									echo '.width( stickyLogoW )';
									echo '.height( stickyLogoH );';
							}
							
							if( $logo_mobile ){
								echo 'var mobileEl = jQuery("#logo img.logo-mobile");';
								echo 'var mobileLogoW = mobileEl.width();';
								echo 'var mobileLogoH = mobileEl.height();';
								echo 'mobileEl';
									echo '.attr( "src", "'. $logo_mobile .'" )';
									echo '.width( mobileLogoW )';
									echo '.height( mobileLogoH );';
							}
								
						echo '}';
					echo '});'."\n";
				echo '//]]>'."\n";
			echo '</script>'."\n";
		}
	
	}
}
add_action('wp_head', 'mfn_retina_logo');


/* ---------------------------------------------------------------------------
 * Scripts config
* --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_scripts_config' ) )
{
	function mfn_scripts_config()
	{
		echo '<script>'."\n";
			echo '//<![CDATA['."\n";
			
				// ajax
				echo 'window.mfn_ajax = "'. admin_url('admin-ajax.php') .'";'."\n";
				
				// nice scroll
				echo 'window.mfn_nicescroll = '. mfn_opts_get('nice-scroll-speed',40) .';'."\n";
				
				// prettyphoto
				$aPrettyOptions = mfn_opts_get('prettyphoto-options');
				
				echo 'window.mfn_prettyphoto = {';
					if( is_array( $aPrettyOptions ) && isset( $aPrettyOptions['disable'] ) ){
						echo 'disable:true, ';
					}
					if( is_array( $aPrettyOptions ) && isset( $aPrettyOptions['disable-mobile'] ) ){
						echo 'disableMobile:true, ';
					}
					if( is_array( $aPrettyOptions ) && isset( $aPrettyOptions['title'] ) ){
						echo 'title:true, ';
					}
					echo 'style:"'. mfn_opts_get('prettyphoto','pp_default').'", ';
					echo 'width:'. intval( mfn_opts_get('prettyphoto-width',0) ).', ';
					echo 'height:'. intval( mfn_opts_get('prettyphoto-height',0) );
				echo '};'."\n";
				
				// sliders
				echo 'window.mfn_sliders = {';
					echo 'blog:'. intval( mfn_opts_get('slider-blog-timeout',0) ) .', ';
					echo 'clients:'. intval( mfn_opts_get('slider-clients-timeout',0) ) .', ';
					echo 'offer:'. intval( mfn_opts_get('slider-offer-timeout',0) ) .', ';
					echo 'portfolio:'. intval( mfn_opts_get('slider-portfolio-timeout',0) ) .', ';
					echo 'shop:'. intval( mfn_opts_get('slider-shop-timeout',0) ) .', ';
					echo 'slider:'. intval( mfn_opts_get('slider-slider-timeout',0) ) .', ';
					echo 'testimonials:'. intval( mfn_opts_get('slider-testimonials-timeout',0) );
				echo '};'."\n";
				
				// retina.js
				echo 'window.mfn_retina_disable = '. intval( mfn_opts_get('retina-js-disable',0) ) .';'."\n";
				
			echo '//]]>'."\n";
		echo '</script>'."\n";
	}
}


/* ---------------------------------------------------------------------------
 * Adds classes to the array of body classes.
 * --------------------------------------------------------------------------- */

// header style ---------------------------------
if( ! function_exists( 'mfn_header_style' ) )
{
	function mfn_header_style( $firstPartOnly = false ){
		$header_layout = false;
	
		if( $_GET && key_exists('mfn-h', $_GET) ){
			$header_layout = $_GET['mfn-h']; // demo
		} elseif( $layoutID = mfn_layout_ID() ){
			$header_layout = get_post_meta( $layoutID, 'mfn-post-header-style', true );
		} elseif( mfn_opts_get('header-style') ){
			$header_layout =  mfn_opts_get('header-style');
		}
	
		if( strpos( $header_layout, ',' ) ){
			
			// multiple header parameters
			$a_header_layout = explode( ',', $header_layout );
			
			// return only First Parameter
			if( $firstPartOnly ) return 'header-'.$a_header_layout[0];
			
			foreach( (array)$a_header_layout as $key => $val ){
				$a_header_layout[$key] = 'header-'. $val;
			}
			$header = implode(' ', $a_header_layout);
			
		} else {
			
			// one parameter
			$header = 'header-'. $header_layout;
			
		}
		
		return $header;
	}
}

// sidebar classes ------------------------------
if( ! function_exists( 'mfn_sidebar_classes' ) )
{
	function mfn_sidebar_classes( $has_both = false )
	{
		$classes 	= false;
		$both 		= false;
		
		if( mfn_ID() ){
			
			if( get_post_type() == 'page' && mfn_opts_get('single-page-layout') ){
				
				// Theme Options | Single - Page
				$layout = mfn_opts_get('single-page-layout');
										
			} elseif( get_post_type() == 'post' && is_single() && mfn_opts_get('single-layout') ){
				
				// Theme Options | Single - Post
				$layout = mfn_opts_get('single-layout');
				
			} elseif( get_post_type() == 'portfolio' && is_single() && mfn_opts_get('single-portfolio-layout') ){
				
				// Theme Options | Single - Portfolio
				$layout = mfn_opts_get('single-portfolio-layout');
									
			} else {
				
				// Post Meta
				$layout = get_post_meta( mfn_ID(), 'mfn-post-layout', true);
					
			}
	
			switch ( $layout ) {
				
				case 'left-sidebar':
					$classes = ' with_aside aside_left';
					break;
					
				case 'right-sidebar':
					$classes = ' with_aside aside_right';
					break;
					
				case 'both-sidebars':
					$classes = ' with_aside aside_both';
					$both = true;
					break;
					
			}
			
			// demo
			if( $_GET && key_exists('mfn-s', $_GET) ){
				if( $_GET['mfn-s'] ){
					$classes = ' with_aside aside_right';
				} else {
					$classes = false;
				}
			}
		}
		
		// WooCommerce
		if( function_exists('is_woocommerce') ){
			
			if( is_woocommerce() ){
			
				if( $layout == 'both-sidebars' ){
					// Only one sidebar for shop
					$classes = ' with_aside aside_right';				
				} elseif( ! $layout ){
					// BeTheme version < 6.4 | DO NOT DELETE
					if( is_active_sidebar( 'shop' ) ) $classes = ' with_aside aside_right';
				}
	
			}
			
			if( is_product() && mfn_opts_get('shop-sidebar') == 'shop' ){
				$classes = false;
			}
			
		}
		
		// bbPress
		if( function_exists('is_bbpress') && is_bbpress() && is_active_sidebar( 'forum' ) ){
			$classes = ' with_aside aside_right';
		}
		
		// BuddyPress
		if( function_exists('is_buddypress') && is_buddypress() && is_active_sidebar( 'buddy' ) ){
			$classes = ' with_aside aside_right';
		}
		
		// Events Calendar
		if( function_exists('tribe_is_month') && is_active_sidebar( 'events' ) ){
			if( tribe_is_month() || tribe_is_day() || tribe_is_event() || tribe_is_event_query() || tribe_is_venue() ){
				$classes = ' with_aside aside_right';
			}
		}
	
		// check if has both sidebars
		if( $has_both ) return $both;
	
		// Page Template: Blank Page, Under Construction
		if( is_page_template( 'template-blank.php' ) || is_page_template( 'under-construction.php' ) ){
			$classes = false;
		}
		
		return $classes;
	}
}

// body classes ---------------------------------
if( ! function_exists( 'mfn_body_classes' ) )
{
	function mfn_body_classes( $classes )
	{
		// Layout | Custom ------------------------------------
		$layoutID = mfn_layout_ID();
		
		// Slider ---------------------------------------------
		if( mfn_slider() ) $classes[] = 'template-slider';
		
		// Sidebar --------------------------------------------
		$classes[] = mfn_sidebar_classes();
	
		// Skin -----------------------------------------------
		if( $_GET && key_exists('mfn-c', $_GET) ){
			$classes[] = 'color-'. $_GET['mfn-c']; // demo
		} elseif( $layoutID ){
			$classes[] = 'color-'. get_post_meta( $layoutID, 'mfn-post-skin', true );
		} else {
			$classes[] = 'color-'. mfn_opts_get('skin','custom');
		}
		
		// Style | Default & Simple ---------------------------
		if( $_GET && key_exists('mfn-style', $_GET) ){
			$classes[] = 'style-'. $_GET['mfn-style']; // demo
		} else {
			$classes[] = 'style-'. mfn_opts_get( 'style', 'default' );
		}
		
		// Layout | Full Width & Boxed ------------------------
		if( $_GET && key_exists('mfn-box', $_GET) ){
			$classes[] = 'layout-boxed'; // demo
		} elseif( $layoutID ){
			$classes[] = 'layout-'. get_post_meta( $layoutID, 'mfn-post-layout', true );
		} else {
			$classes[] = 'layout-'. mfn_opts_get('layout','full-width');
		}
		
		// One Page -------------------------------------------
		if( get_post_meta( mfn_ID(), 'mfn-post-one-page', true ) ){
			$classes[] = 'one-page';
		}
		
		// Grid 960px -----------------------------------------
		if( mfn_is_960() ) $classes[] = 'grid960';
	
		// Nice Scroll ----------------------------------------
		if( mfn_opts_get('nice-scroll') ) $classes[] = 'nice-scroll-on';
		
		// Responsive -----------------------------------------
		if( mfn_opts_get('no-hover') ) $classes[] = 'no-hover-'. mfn_opts_get('no-hover');
		if( mfn_opts_get('no-section-bg') ) $classes[] = 'no-section-bg-'. mfn_opts_get('no-section-bg');
		if( mfn_opts_get('responsive-top-bar') ) $classes[] = 'mobile-tb-'. mfn_opts_get('responsive-top-bar');
		
		$responsive_options = mfn_opts_get( 'responsive-options' );
		if( is_array( $responsive_options ) && isset( $responsive_options['mobile-wide'] ) ){
			$classes[] = 'mobile-wide';
		}
		
		// Button | Style -------------------------------------
		if( $_GET && key_exists('mfn-btn', $_GET) ){
			$classes[] = 'button-'. $_GET['mfn-btn']; // demo
		} elseif( mfn_opts_get('button-style') ){
			$classes[] = 'button-'. mfn_opts_get('button-style');
		}
		
		// Image Frame | Style --------------------------------
		if( $_GET && key_exists('mfn-if', $_GET) ){
			$classes[] = 'if-'. $_GET['mfn-if']; // demo
		} elseif( mfn_opts_get('image-frame-style') ){
			$classes[] = 'if-'. mfn_opts_get('image-frame-style');
		}
		
		// Content Padding ------------------------------------
		if( mfn_opts_get('content-remove-padding') ){
			$classes[] = 'no-content-padding';
		} elseif( get_post_meta( mfn_ID(), 'mfn-post-remove-padding', true ) ){
			$classes[] = 'no-content-padding';
		}
		
		// RTL | demo only ------------------------------------
		if( $_GET && key_exists('mfn-rtl', $_GET) ) $classes[] = 'rtl';
		
		// Love -----------------------------------------------
		if( ! mfn_opts_get('love') ) $classes[] = 'hide-love';
		
		// Table Hover ----------------------------------------
		if( mfn_opts_get('table-hover-disable') ) $classes[] = 'no-table-hover';
		
		
		// Header =============================================
		
		$header_options = mfn_opts_get('header-fw') ? mfn_opts_get('header-fw') : false;
		
		// Header | Layout --------------------------
		$classes[] = mfn_header_style();
		
		// Header | Full Width ----------------------
		if( $_GET && key_exists('mfn-hfw', $_GET) ){
			$classes[] = 'header-fw'; // demo
		} elseif( isset( $header_options['full-width'] )  ){
			$classes[] = 'header-fw';
		}
		
		// Header | Boxed ---------------------------
		if( is_array( $header_options ) && isset( $header_options['header-boxed'] ) ){
			$classes[] = 'header-boxed';
		}
		
		// Header | Minimalist ----------------------
		if( $_GET && key_exists('mfn-min', $_GET) ){
			$classes[] = 'minimalist-header'; // demo
		} elseif( $layoutID ){
			if( get_post_meta( $layoutID, 'mfn-post-minimalist-header', true ) ) $classes[] = 'minimalist-header';
		} elseif( mfn_opts_get('minimalist-header') ) {
			$classes[] = 'minimalist-header';
		}
		
		// Header | Sticky --------------------------
		if( mfn_opts_get('sticky-header') && ( mfn_header_style( true ) != 'header-creative' ) ){
			$classes[] = 'sticky-header';
		}
		
		// Header Sticky Style ----------------------
		if( $_GET && key_exists('mfn-ss', $_GET) ){
			$classes[] = 'sticky-'. $_GET['mfn-ss']; // demo
		} elseif( $layoutID ){
			$classes[] = 'sticky-'. get_post_meta( $layoutID, 'mfn-post-sticky-header-style', true );
		} else {
			$classes[] = 'sticky-'. mfn_opts_get('sticky-header-style','white');
		}
		
		// Action Bar -------------------------------
		if( mfn_opts_get('action-bar') ){
			$classes[] = 'ab-show';
		} else {
			$classes[] = 'ab-hide';
		}
		
		// Subheader | Transparent ------------------
		$skin = mfn_opts_get( 'skin', 'custom' );
		if( $_GET && key_exists('mfn-subtr', $_GET) ){
			$classes[] = 'subheader-transparent'; // demo
		} elseif( ! in_array( $skin, array('custom','one') ) ){
			if( mfn_opts_get( 'subheader-transparent' ) != 100 ){
				$classes[] = 'subheader-transparent';
			}
		}
		
		// Subheader | Style ------------------------
		if( $_GET && key_exists('mfn-sh', $_GET) ){
			$classes[] = 'subheader-'. $_GET['mfn-sh']; // demo
		} else {
			$classes[] = 'subheader-'. mfn_opts_get('subheader-style','title-left');
		}
	
		// Menu | Style -----------------------------
		if( $_GET && key_exists('mfn-m', $_GET) ){
			$classes[] = 'menu-'. $_GET['mfn-m']; // demo
		} elseif( mfn_opts_get('menu-style') ) {
			$classes[] = 'menu-'. mfn_opts_get('menu-style');
		}
		
		// Menu | Options ---------------------------
		$menu_options = mfn_opts_get( 'menu-options' );
		if( is_array( $menu_options ) && isset( $menu_options['align-right'] ) ){
			$classes[] = 'menuo-right';
		}
		if( is_array( $menu_options ) && isset( $menu_options['menu-arrows'] ) ){
			$classes[] = 'menuo-arrows';
		}
		if( is_array( $menu_options ) && isset( $menu_options['hide-borders'] ) ){
			$classes[] = 'menuo-no-borders';
		}
		
		
		// Footer =============================================
		
		// Footer | Style ---------------------------
		if( $_GET && key_exists('mfn-ftr', $_GET) ){
			$classes[] = 'footer-'. $_GET['mfn-ftr']; // demo
		} elseif( mfn_opts_get('footer-style') ) {
			$classes[] = 'footer-'. mfn_opts_get('footer-style');
		}
		
		// Footer | Copy & Social -------------------
		if( mfn_opts_get( 'footer-hide' ) == 'center' ) {
			$classes[] = 'footer-copy-center';
		}
	
		
		return $classes;
	}
}
add_filter( 'body_class', 'mfn_body_classes' );


/* ---------------------------------------------------------------------------
 * Annoying styles remover
 * --------------------------------------------------------------------------- */
if( ! function_exists( 'mfn_remove_recent_comments_style' ) )
{
	function mfn_remove_recent_comments_style() {  
	    global $wp_widget_factory;  
	    remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );  
	} 
} 
add_action( 'widgets_init', 'mfn_remove_recent_comments_style' ); 

?>