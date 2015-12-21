<?php
/**
 * Do stuff common to ALL controllers
 **/
class wpl_exe_wp_core_controller {
	
	/**
	 * Class vars. Make controllers public, so
	 * that they can be called from templates too.
	 **/
	protected $_model = null;
	protected $_controller = null;
	public $view = null;
	public $is_mega_menu = false;
	
	public $settings = array();
	public $system_config = array();
	
	public $post_settings = array();
	
	function __construct( $settings = array() ) {
		
		// Use sessions
		if( ! session_id() ) {
			@session_start();
		}
		
		// Load settings
		$this->settings = $settings['settings'];
		$this->load_settings();
		
		$this->system_config = $settings['system_config'];
		
		// Route $_GET/$_POST['wproto_action'] custom requests
		add_action( 'parse_request', array( $this, 'delegate_to_controller_action' ), 1 );
		add_action( 'admin_init', array( $this, 'delegate_to_controller_action' ), 1 );
		add_action( 'wp', array( $this, 'load_post_settings' ) );
		
	}
	
	
	/**
	 * Load settings
	 **/
	function load_settings() {		
		// Engine settings
		$settings = get_option( 'wproto_settings_' . WPROTO_THEME_NAME );
		$settings['date_format'] = get_option('date_format');
		$this->settings = $settings + $this->settings;
	}
	
	function load_post_settings() {
		global $wpl_exe_wp;
		// Post settings
		if( is_single() || is_page() ) {
			global $post;
			$this->post_settings = get_post_custom( $post->ID );
		}
	}
	
	
	/**
	 * Get settings value
	 * @param option name string
	 * @param environment string
	 * @param post_id int (optional)
	 **/
	function get_option( $option_name, $env = 'general' ) {
		
		$settings_env = isset( $this->settings[ $env ] ) ? $this->settings[ $env ] : array();
		
		if( isset( $settings_env[ $option_name ] ) ) {
			return $settings_env[ $option_name ];
		} else if( isset( $this->system_config[ $env ][ $option_name ] ) ) {
			return $this->system_config[ $env ][ $option_name ];
		} else {
			return NULL;
		}
		
	}
	
	/**
	 * Get a post option value
	 **/
	function get_post_option( $option_name ) {
		return isset( $this->post_settings[ $option_name ][0] ) ? $this->post_settings[ $option_name ][0] : NULL;
	}
	
	function get_post_settings_obj() {
		$return = new stdClass;
		
		foreach( $this->post_settings as $k=>$v ) {
			$return->$k = $v[0];
		}
		
		return $return;
		
	}
	
	/**
	 * Set a new option value and refresh the settings
	 * @param option name string
	 * @param environment string
	 * @param post_id int (optional)
	 **/
	function write_option( $option_name, $option_value, $env ) {
		
		$storage = get_option( 'wproto_settings_' . WPROTO_THEME_NAME );
		$storage[ $env ][ $option_name ] = $option_value;

		update_option( 'wproto_settings_' . WPROTO_THEME_NAME, $storage );
		
		// Refresh settings
		$this->load_settings();
	}
	
	/**
	 * Set option into loaded config
	 **/
	function set_option( $option_name, $option_value, $env ) {
		$this->settings[ $env ][ $option_name ] = $option_value;
	}
	
	/**
	 * Write loaded settings intoDB
	 **/
	function write_all_settings() {
		
		update_option( 'wproto_settings_' . WPROTO_THEME_NAME, $this->settings );
		
	}
	
	/**
	 * Load a controller
	 **/
	function controller( $name ) {

		$class = 'wpl_exe_wp_' . $name . '_controller';
		
		if( !isset( $this->_controller->$class ) ) {
			$directory = WPROTO_ENGINE_DIR . '/controller/';
			
			require_once $directory . $name . '.php';
			
			@$this->_controller->$name = new $class();
			
			return $this->_controller->$name;
		} else {			
			return $this->_controller->$name;
		}
		
	}
	
	/**
	 * Load a model
	 **/
	function model( $name ) {
		
		$class = 'wpl_exe_wp_' . $name;
		
		if( !isset( $this->_model->$class ) ) {
			$directory = WPROTO_ENGINE_DIR . '/model/';
			require_once $directory . $name . '.php';
			
			@$this->_model->$name = new $class();
			
			return $this->_model->$name;
		} else {
			return $this->_model->$name;
		}
		
	}
	
	/**
	 * Parse custom request using our own routing,
	 * i.e. $_GET['wproto_action'] or $_POST['wproto_action'],
	 * and then delegate to appropriate controller
	 * action.
	 *
	 * Example 1: '/?wproto_action=front_controller-view'
	 * Example 2: '/wp-admin/index.php?wproto_action=admin_settings-save'
	 **/
	function delegate_to_controller_action() {
		if ( isset( $_POST['wproto_action'] ) ) {
			$action = $_POST['wproto_action'];
		} elseif ( isset( $_GET['wproto_action'] ) ) {
			$action = $_GET['wproto_action'];
		}

		if ( isset( $action ) ) {
			$controller_and_action = explode( '-', $action );

			if ( count( $controller_and_action ) == 2 ) {
				
				$this->controller( $controller_and_action[0] )->$controller_and_action[1]();

			}
		}
	}
	
	/**
	 * Load and instantiate all application
	 * classes neccessary for this plugin
	 **/
	function dispatch() {
		
		$this->view =					new stdClass();
		$this->_model =		  	new stdClass();
		$this->_controller =	new stdClass();
		
		require_once WPROTO_THEME_DIR . '/wproto/controller/base.php';
		$this->_controller->base = new wpl_exe_wp_base_controller();
		
		require_once WPROTO_ENGINE_DIR . '/controller/captcha.php';
		$this->_controller->captcha = new wpl_exe_wp_captcha_controller();
		
		require_once WPROTO_ENGINE_DIR . '/model/database.php';
		
		// Manually instantiate dependency classes first
		$this->_controller->base = $this;
		$this->_model->database = new wpl_exe_wp_database();
		
		// Load helpers
		require_once WPROTO_ENGINE_DIR . '/helper/utils.php';
		require_once WPROTO_ENGINE_DIR . '/helper/front.php';
		
		require_once WPROTO_ENGINE_DIR . '/view/view.php';
		$this->view = new wpl_exe_wp_view();
		
		if( wpl_exe_wp_utils::isset_woocommerce() ) {
			require_once WPROTO_ENGINE_DIR . '/controller/woocommerce.php';
			$this->_controller->woocommerce = new wpl_exe_wp_woocommerce_controller();
		}
		
		require_once WPROTO_ENGINE_DIR . '/controller/admin_vc.php';
		$this->_controller->admin_vc = new wpl_exe_wp_admin_vc_controller();
		
		if( is_admin() ) {
			
			require_once WPROTO_ENGINE_DIR . '/helper/admin_menu_walker.php';
			require_once WPROTO_ENGINE_DIR . '/helper/admin.php';
			
			require_once WPROTO_ENGINE_DIR . '/controller/admin.php';
			$this->_controller->admin = new wpl_exe_wp_admin_controller();
			
			require_once WPROTO_ENGINE_DIR . '/controller/admin_settings.php';
			$this->_controller->admin_settings = new wpl_exe_wp_admin_settings_controller();
			
			require_once WPROTO_ENGINE_DIR . '/controller/admin_posts.php';
			$this->_controller->admin_posts = new wpl_exe_wp_admin_posts_controller();
			
			require_once WPROTO_ENGINE_DIR . '/controller/admin_users.php';
			$this->_controller->user = new wpl_exe_wp_user_controller();
			
		} else {
			
			require_once WPROTO_ENGINE_DIR . '/helper/front_menu_walker.php';
			
			require_once WPROTO_ENGINE_DIR . '/controller/oauth.php';
			$this->_controller->oauth = new wpl_exe_wp_oauth_controller();
			
			require_once WPROTO_ENGINE_DIR . '/controller/front_shortcodes.php';
			$this->_controller->front_shortcodes = new wpl_exe_wp_front_shortcodes_controller();
			
			require_once WPROTO_ENGINE_DIR . '/controller/front.php';
			$this->_controller->front = new wpl_exe_wp_front_controller();
			
		}
		
		require_once WPROTO_ENGINE_DIR . '/controller/menus.php';
		$this->_controller->menus = new wpl_exe_wp_menus_controller();
		
		require_once WPROTO_ENGINE_DIR . '/controller/ajax.php';
		$this->_controller->ajax = new wpl_exe_wp_ajax_controller();
		
		$this->autoload_directory_classes('widget');

	}
	
	/**
	 * Autoload all classes in a directory
	 **/
	function autoload_directory_classes( $layer, $load_class = true ) {

		$directory = WPROTO_ENGINE_DIR . '/' . $layer . '/';
		$handle = opendir($directory);

		while (false !== ($file = readdir($handle))) {
			
			if( $file == '.htaccess' ) continue;
			
			if (is_file($directory . $file)) {
				// Figure out class name from file name
				$class = str_replace('.phtml', '', $file);
				$class = str_replace('.php', '', $class);
				
				$class = 'wpl_exe_wp_' . str_replace('-', '_', $class) . '';
				$shortClass = str_replace('wpl_exe_wp_', '', $class);
				$shortClass = str_replace('_' . $layer, '', $shortClass);

				if( $load_class ) {
					// Avoid recursion
					if ($class != get_class($this) ) {
						// Include and instantiate class
						require_once $directory . $file;
						if( $layer != 'helper' && $layer != 'widget' ) {
							$this->$layer->$shortClass = new $class();
						}
					}
				} else {
					require_once $directory . $file;
				}

			}
		}
	}
	
}