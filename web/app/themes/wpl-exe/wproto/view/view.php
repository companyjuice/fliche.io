<?php
	/**
	 * Anything to do with templates
	 * and outputting client code
	 **/
  class wpl_exe_wp_view {
		/**
		 * Load view WITH header/footer
		 **/
		function load_page( $path = '', $data = array(), $header = 'header', $footer = 'footer' ) {
			// WARNING: Must not be optional for security reasons
			// $data = $this->esc_html( $data );

			//! TODO: Secure this, e.g. don't allow '..'
			$full_path = dirname( __FILE__ ) . '/' . $path . '.phtml';

			// Allow custom header path
			if ($header == 'header') {
				$header_path = get_stylesheet_directory() . '/header.php';
			} else {
				$header_path = $header . '.phtml';
			}

			// Allow custom footer path
			if ($footer == 'footer') {
				$footer_path = get_stylesheet_directory() . '/header.php';
			} else {
				$footer_path = $footer . '.phtml';
			}

			if ( file_exists( $full_path ) ) {
				if ( is_admin() ) {
					// WARNING: Link to an existing admin URL, such as /wp-admin/index.php?wproto_action=xxx, else trouble!
					
					// Load WP admin header, our file and footer, set empty menu to avoid errors
					global $menu;
					$menu = array();
					
					require_once ABSPATH . 'wp-admin/admin-header.php';
					require_once $full_path;
					require_once ABSPATH . 'wp-admin/admin-footer.php';
				} else {
					if( file_exists( $header_path ) ) {
						require_once $header_path;
					}
					
					require_once $full_path;
					
					if( file_exists( $footer_path ) ) {
						require_once $footer_path;
					}
				}
			} else {
				//! TODO: Introduce error emails throughout this plugin and a 404/500 page for the user
				throw new Exception( 'The view path ' . $full_path . ' can not be found.' );
			}

			exit;
		}

		/**
		 * Load view WITHOUT header/footer, in case you would like
		 * to nest templates, to loop through the same template, or
		 * to use a mixture of different templates in any other way.
		 **/
		function load_partial( $path = '', $data = array() ) {

			//! TODO: Secure this, e.g. don't allow '..'
			if( is_child_theme() ) {
				$full_path = get_stylesheet_directory() . '/wproto/view/' . $path . '.phtml';
				if( ! file_exists( $full_path ) ) {
					$full_path = WPROTO_ENGINE_DIR . '/view/' . $path . '.phtml';
				}
			} else {
				$full_path = WPROTO_ENGINE_DIR . '/view/' . $path . '.phtml';
			}
			

			if ( file_exists( $full_path ) ) {
				require $full_path;
			} else {
				throw new Exception( 'The view path ' . $full_path . ' can not be found.' );
			}
		}

		/**
		 * Load view WITHOUT header/footer for AJAX purposes. We will
		 * have to exit, or AJAX success code 1/0 will be outputted.
		 **/
		function load_ajax_partial( $path = '', $data = array() ) {
			$this->load_partial( $path, $data );
			exit;
		}

  }