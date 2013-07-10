<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) exit('No direct script access allowed');
/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package			Event Espresso
 * @ author			Seth Shoultes
 * @ copyright		(c) 2008-2011 Event Espresso  All Rights Reserved.
 * @ license			http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link					http://www.eventespresso.com
 * @ version		 	4.0
 *
 * ------------------------------------------------------------------------
 *
 * EE_Config
 *
 * @package			Event Espresso
 * @subpackage	core/
 * @author				Brent Christensen 
 *
 * ------------------------------------------------------------------------
 */
final class EE_Config {


	/**
	 * 	instance of the EE_Config object
	 *	@var 	$_instance
	 * 	@access 	private
	 */
	private static $_instance = NULL;

	/**
	 * 	_module_route_map
	 *	@var 	array	$_module_route_map
	 * 	@access 	private
	 */
	private static $_module_route_map = array();

	/**
	 * 	_module_forward_map
	 *	@var 	array	$_module_forward_map
	 * 	@access 	private
	 */
	private static $_module_forward_map = array();

	/**
	 * 	_module_view_map
	 *	@var 	array	$_module_view_map
	 * 	@access 	private
	 */
	private static $_module_view_map = array();

	/**
	 * 	EE_Registry Object
	 *	@var 	EE_Registry	$EE	
	 * 	@access 	protected
	 */
	protected $EE = NULL;






	/**
	 *		@singleton method used to instantiate class object
	 *		@access public
	 *		@return class instance
	 */
	public static function instance() {
		// check if class object is instantiated, and instantiated properly
		if ( self::$_instance === NULL  or ! is_object( self::$_instance ) or ! is_a( self::$_instance, __CLASS__ )) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}



	/**
	 * 	class constructor
	 *
	 *  @access 	private
	 *  @return 	void
	 */
	private function __construct() {
		$this->EE = EE_Registry::instance();
		// get EE site settings
		$this->_load_config();
		$this->_register_shortcodes_and_modules();
		//add_action( 'init', array( $this, 'init' ), 5 );
		add_action( 'wp_loaded', array( $this, 'plugins_loaded' ), 3 );
		
	}

 


	/**
	 * 	plugins_loaded
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function plugins_loaded() {
//		echo '<h3>'. __CLASS__ . '->' . __FUNCTION__ . ' <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h3>';
//		printr( $this->EE->CFG->post_shortcodes, '$this->EE->CFG->post_shortcodes  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
//		printr( $this->EE->shortcodes, '$this->EE->shortcodes  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
//		printr( $this->EE->modules, '$this->EE->modules  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );		
//		printr( EE_Config::$_module_route_map, 'EE_Config::$_module_route_map  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
//		printr( EE_Config::$_module_forward_map, 'EE_Config::$_module_forward_map  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
//		printr( EE_Config::$_module_view_map, 'EE_Config::$_module_view_map  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
//		printr( $this->EE->REQ, '$this->EE->REQ  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );

	}




	/**
	 * 		load EE organization options and begin EE logging
	 *
	 * 		@access private
	 * 		@return void
	 */
	private function _load_config() {

		$current_user_id = get_current_user_id();
		$current_user_id = $current_user_id ? $current_user_id : 1;
		// grab org options based on current admin user
		$this->EE->CFG = get_user_meta( $current_user_id, 'events_organization_settings', TRUE );
		// force $this->EE->CFG into an object
		if ( is_array( $this->EE->CFG )) {
			$CFG = new stdClass();
			foreach ( $this->EE->CFG as $k => $v ) {
				$CFG->$k = $v;
			}
			$this->EE->CFG = $CFG;
		}
		// do settings for this user exist ?
		if ( empty( $this->EE->CFG )) {
			require_once( EE_HELPERS . 'EEH_Activation.helper.php' );
			$this->EE->CFG = EEH_Activation::org_option_initialization();		
		} else {
			// list of critical settings
			$critical_settings = array( 
				'contact_email',
				'currency_sign'
			);
			// cycle thru critical org_options
			foreach ( $critical_settings as $critical_setting ) {
				// make sure each one actually exists 
				if ( ! isset( $this->EE->CFG->$critical_setting )) {
					// reinitialize the org options
					require_once( EE_HELPERS . 'EEH_Activation.helper.php' );
					$this->EE->CFG = EEH_Activation::org_option_initialization();		
					break;	
				}
			}
		}
		// add current_user_id
		$this->EE->CFG->wp_user = $current_user_id;	

//		printr( $this->EE->CFG, '$this->EE->CFG  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
		
		$this->EE->CFG->post_shortcodes = isset( $this->EE->CFG->post_shortcodes ) ? $this->EE->CFG->post_shortcodes : array();
		// set _module_route_map
		EE_Config::$_module_route_map = isset( $this->EE->CFG->_module_route_map ) ? $this->EE->CFG->_module_route_map : array();
		// set _module_forward_map
		EE_Config::$_module_forward_map = isset( $this->EE->CFG->_module_forward_map ) ? $this->EE->CFG->_module_forward_map : array();
		// set _module_view_map
		EE_Config::$_module_view_map = isset( $this->EE->CFG->_module_view_map ) ? $this->EE->CFG->_module_view_map : array();
		
		$remove_from_config = array(
			'_module_route_map',
			'_module_forward_map',
			'_module_view_map'
		);
		
		foreach ( $remove_from_config as $setting ) {
			if ( isset( $this->EE->CFG->$setting )) {
				unset( $this->EE->CFG->$setting );
			}
		}
		
		do_action('AHEE_debug_file');
	}



	/**
	 * 	'update_config'
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function update_config() {
//		printr( $this->EE->CFG, '$this->EE->CFG  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
		$this->EE->CFG->_module_route_map = EE_Config::$_module_route_map;
		$this->EE->CFG->_module_forward_map = EE_Config::$_module_forward_map;
		$this->EE->CFG->_module_view_map = EE_Config::$_module_view_map;
		// grab org options based on current admin user		
		if ( ! update_usermeta( $this->EE->CFG->wp_user, 'events_organization_settings', $this->EE->CFG )) {
			$msg = __( 'An error has occured. The Event Espresso Configuration Settings could not be updated.', 'event_espresso' );
			EE_Error::add_error( $msg, __FILE__, __FUNCTION__, __LINE__ );
		}
	}


	/**
	 * 	update_post_shortcodes
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function update_post_shortcodes() {
		// cycle thru post_shortcodes
		foreach( $this->EE->CFG->post_shortcodes as $post_name => $shortcodes ){
			// skip the posts page, because we want all shortcodes registered for it
			if ( $post_name != 'posts' ) {
				foreach( $shortcodes as $shortcode => $post_id ){
					if ( $post = get_post( $post_id )) {
						if ( $post->post_name == $post_name ) {
							break;
						}
					}
					unset( $this->EE->CFG->post_shortcodes[ $post_name ] );
				}
			}
		}
		$this->update_config();
//		add_action( 'shutdown', array( $this, 'update_config' ));
	}





	/**
	 * 	_register_shortcodes_and_modules
	 *
	 *  @access 	private
	 *  @return 	void
	 */
	private function _register_shortcodes_and_modules() {
		// allow shortcodes to register with WP and to set hooks for the rest of the system
		$this->_register_shortcodes();
		// allow modules to set hooks for the rest of the system
		$this->_register_modules();
	}



	/**
	 * 		_register_shortcodes
	 *
	 * 		@access private
	 * 		@return void
	 */
	private function _register_shortcodes() {
		// load base class
		require_once( EE_SHORTCODES . 'EES_Shortcode.shortcode.php' );
		// grab list of installed shortcodes
		$shortcodes_to_register = glob( EE_SHORTCODES . '*', GLOB_ONLYDIR );
		// filter list of modules to register
		$shortcodes_to_register = apply_filters( 'AHEE__Front_Controller__register_shortcodes__shortcodes_to_register', $shortcodes_to_register );
		// cycle thru shortcode folders
		foreach ( $shortcodes_to_register as $shortcode_path ) {
			// add to list of installed shortcode modules
			EE_Config::register_shortcode( $shortcode_path );
		}
		// filter list of installed modules
		$this->EE->shortcodes = apply_filters( 'AHEE__Front_Controller__register_shortcodes__installed_shortcodes', $this->EE->shortcodes );
	}



	/**
	 * 	register_shortcode - makes core aware of this shortcode
	 *
	 *  @access 	public
	 *  @param 	string 		$shortcode_path - full path up to and including shortcode folder
	 *  @return 	void
	 */
	public static function register_shortcode( $shortcode_path = NULL ) {
		$shortcode_ext = '.shortcode.php';
		// make all separators match
		$shortcode_path = rtrim( str_replace( '/\\', DS, $shortcode_path ), DS );
		// grab and sanitize shortcode directory name
		$shortcode_dir = sanitize_key( basename( $shortcode_path ));
		// create classname from shortcode directory name
		$shortcode = str_replace( ' ', '_', ucwords( str_replace( '_', ' ', $shortcode_dir )));
		// add class prefix
		$shortcode_class = 'EES_' . $shortcode;
		// does the shortcode exist ?
		if ( ! is_readable( $shortcode_path . DS . $shortcode_class . $shortcode_ext )) {
			$msg = sprintf( __( 'An error has occured. The requested %s shortcode file could not be found or is not readable due to file permissions.', 'event_espresso' ), $shortcode_class );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		// load the shortcode class file
		require_once( $shortcode_path . DS . $shortcode_class . $shortcode_ext );
		// verfiy that class exists
		if ( ! class_exists( $shortcode_class )) {
			$msg = sprintf( __( 'An error has occured. The requested %s shortcode class does not exist.', 'event_espresso' ), $shortcode_class );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		// which set hooks ?
		$hook_point = is_admin() ? 'set_hooks_admin' : 'set_hooks';
		// fire the shortcode class's set_hooks method during the wp_loaded hook, in case it needs to hook into other parts of the system
		add_action( 'wp_loaded', array( $shortcode_class, $hook_point ), 1 );
		// add to array of registered shortcodes
		EE_Registry::instance()->shortcodes[ strtoupper( $shortcode ) ] = $shortcode_path . DS . $shortcode_class . $shortcode_ext;
		return TRUE;
	}	




	/**
	 * 		_register_modules
	 *
	 * 		@access private
	 * 		@return void
	 */
	private function _register_modules() {
		// load base class
		require_once( EE_MODULES . 'EED_Module.module.php' );
		// grab list of installed modules
		$modules_to_register = glob( EE_MODULES . '*', GLOB_ONLYDIR );
		// filter list of modules to register
		$modules_to_register = apply_filters( 'AHEE__Front_Controller__register_modules__modules_to_register', $modules_to_register );
		// loop through folders
		foreach ( $modules_to_register as $module_path ) {
			if ( $module_path != EE_MODULES . 'zzz-copy-this-module-template' ) {
				// add to list of installed modules
				EE_Config::register_module( $module_path );
			}
		}
		// filter list of installed modules
		$this->EE->modules = apply_filters( 'AHEE__Front_Controller__register_modules__installed_modules', $this->EE->modules );
	}



	/**
	 * 	register_module - makes core aware of this module
	 *
	 *  @access 	public
	 *  @param 	string 		$module_path - full path up to and including module folder
	 *  @return 	void
	 */
	public static function register_module( $module_path = NULL ) {
		$module_ext = '.module.php';
		// make all separators match
		$module_path = rtrim( str_replace( '/\\', DS, $module_path ), DS );
		// grab and sanitize module name
		$module_dir = basename( $module_path );
		// create classname from module directory name
		$module = str_replace( ' ', '_', ucwords( str_replace( '_', ' ', $module_dir )));
		// add class prefix
		$module_class = 'EED_' . $module;
		// does the module exist ?
		if ( ! is_readable( $module_path . DS . $module_class . $module_ext )) {
			$msg = sprintf( __( 'An error has occured. The requested %s module file could not be found or is not readable due to file permissions.', 'event_espresso' ), $module );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		// load the module class file
		require_once( $module_path . DS . $module_class . $module_ext );
		// verfiy that class exists
		if ( ! class_exists( $module_class )) {
			$msg = sprintf( __( 'An error has occured. The requested %s module class does not exist.', 'event_espresso' ), $module_class );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		// which set hooks ?
		$hook_point = is_admin() ? 'set_hooks_admin' : 'set_hooks';
		// fire the module class's set_hooks method during the  wp_loaded hook
		add_action( 'wp_loaded', array( $module_class, $hook_point ), 1 );
		// add to array of registered modules
		EE_Registry::instance()->modules[ $module ] = $module_path . DS . $module_class . $module_ext;
		return TRUE;
	}



	/**
	 * 	register_route - adds module method routes to route_map
	 *
	 *  @access 	public
	 *  @param 	string 		$route - "pretty" public alias for module method
	 *  @param 	string 		$module - module name (classname without EED_ prefix)
	 *  @param 	string 		$method_name - the actual module method to be routed to
	 *  @return 	void
	 */
	public static function register_route( $route = NULL, $module = NULL, $method_name = NULL ) {
		$module = str_replace( 'EED_', '', $module );
		if ( ! isset( EE_Registry::instance()->modules[ $module ] )) {
			$msg = sprintf( __( 'An error has occured. The module %s has not been registered.', 'event_espresso' ), $module );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		if ( empty( $route )) {
			$msg = sprintf( __( 'An error has occured. No route has been supplied.', 'event_espresso' ), $route );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		if ( ! method_exists ( 'EED_' . $module, $method_name )) {
			$msg = sprintf( __( 'An error has occured. A valid class method for the %s route has not been supplied.', 'event_espresso' ), $route );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		EE_Config::$_module_route_map[ $route ] = array( 'EED_' . $module, $method_name );
//		echo '<br /><br /><h4>$route : ' . $route . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//		printr( EE_Config::$_module_route_map[ $route ], 'EE_Config::$_module_route_map[ $route ]  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
	}



	/**
	 * 	get_route - get module method route
	 *
	 *  @access 	public
	 *  @param 	string 		$route - "pretty" public alias for module method
	 *  @return 	void
	 */
	public static function get_route( $route = NULL ) {
		if ( isset( EE_Config::$_module_route_map[ $route ] )) {
			return EE_Config::$_module_route_map[ $route ];
		}
		return NULL;
	}



	/**
	 * 	register_forward - allows modules to forward request to another module for further processing
	 *
	 *  @access 	public
	 *  @param 	string 		$route - "pretty" public alias for module method
	 *  @param 	integer	$status - integer value corresponding  to status constant strings set in module parent class, allows different forwards to be served based on status
	 *  @param 	mixed		string|array 	$forward - function name or array( class, method )
	 *  @return 	void
	 */
	public static function register_forward( $route = NULL, $status = 0, $forward = NULL ) {
		if ( ! isset( EE_Config::$_module_route_map[ $route ] ) ||  empty( $route )) {
			$msg = sprintf( __( 'An error has occured. The module route %s for this forward has not been registered.', 'event_espresso' ), $route );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		if ( empty( $forward )) {
			$msg = sprintf( __( 'An error has occured. No forwarding route has been supplied.', 'event_espresso' ), $route );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		if ( is_array( $forward )) {
			if ( ! isset( $forward[1] )) {
				$msg = sprintf( __( 'An error has occured. A class method for the %s forwarding route has not been supplied.', 'event_espresso' ), $route );
				EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
				return FALSE;
			}
			if ( ! method_exists( $forward[0], $forward[1] )) {
				$msg = sprintf( __( 'An error has occured. The class method %s for the %s forwarding route is in invalid.', 'event_espresso' ), $forward[1], $route );
				EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
				return FALSE;
			}
		} else if ( ! function_exists( $forward )) {
			$msg = sprintf( __( 'An error has occured. The function %s for the %s forwarding route is in invalid.', 'event_espresso' ), $forward, $route );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		EE_Config::$_module_forward_map[ $route ][ absint( $status ) ] = $forward;
	}



	/**
	 * 	get_forward - get forwarding route 
	 *
	 *  @access 	public
	 *  @param 	string 		$route - "pretty" public alias for module method
	 *  @param 	integer	$status - integer value corresponding  to status constant strings set in module parent class, allows different forwards to be served based on status
	 *  @return 	void
	 */
	public static function get_forward( $route = NULL, $status = 0 ) {
		if ( isset( EE_Config::$_module_route_map[ $route ][ $status ] )) {
			return EE_Config::$_module_route_map[ $route ][ $status ];
		}
		return NULL;
	}



	/**
	 * 	register_forward - allows modules to specify different view templates for different method routes and status results
	 *
	 *  @access 	public
	 *  @param 	string 		$route - "pretty" public alias for module method
	 *  @param 	integer	$status - integer value corresponding  to status constant strings set in module parent class, allows different views to be served based on status
	 *  @param 	mixed		string|array 	$forward - function name or array( class, method )
	 *  @return 	void
	 */
	public static function register_view( $route = NULL, $status = 0, $view = NULL ) {
		if ( ! isset( EE_Config::$_module_route_map[ $route ] ) ||  empty( $route )) {
			$msg = sprintf( __( 'An error has occured. The module route %s for this view has not been registered.', 'event_espresso' ), $route );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		if ( ! is_readable( $view )) {
			$msg = sprintf( __( 'An error has occured. The %s view file could not be found or is not readable due to file permissions.', 'event_espresso' ), $view );
			EE_Error::add_error( $msg . '||' . $msg, __FILE__, __FUNCTION__, __LINE__ );
			return FALSE;
		}
		EE_Config::$_module_view_map[ $route ][ absint( $status ) ] = $view;
	}



	/**
	 * 	get_view - get view for route and status
	 *
	 *  @access 	public
	 *  @param 	string 		$route - "pretty" public alias for module method
	 *  @param 	integer	$status - integer value corresponding  to status constant strings set in module parent class, allows different views to be served based on status
	 *  @return 	void
	 */
	public static function get_view( $route = NULL, $status = 0 ) {
		if ( isset( EE_Config::$_module_view_map[ $route ][ $status ] )) {
			return EE_Config::$_module_view_map[ $route ][ $status ];
		}
		return NULL;
	}





}
// End of file EE_Config.core.php
// Location: /core/EE_Config.core.php