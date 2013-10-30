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
 * CPT_Strategy
 *
 * @package			Event Espresso
 * @subpackage	/core/
 * @author				Brent Christensen 
 *
 * ------------------------------------------------------------------------
 */
class EE_CPT_Strategy extends EE_BASE {

   /**
     * 	EE_CPT_Strategy Object
     * 	@private _instance
	 * 	@private 	protected
     */
	private static $_instance = NULL;

	/**
	 * 	EE_Registry Object
	 *	@var 	EE_Registry	$EE	
	 * 	@access 	protected
	 */
	protected $EE = NULL;

	/**
	 * $CPT - the current page, if it utilizes CPTs
	 *	@var 	array	
	 * 	@access 	protected
	 */
	protected $CPT = NULL;

	/**
	 * 	@var 	array 	$_CPTs
	 *  @access 	protected
	 */
	protected $_CPTs = array();

	/**
	 * 	@var 	array 	$_CPT_endpoints
	 *  @access 	protected
	 */
	protected $_CPT_endpoints = array();

	/**
	 * $CPT_model
	 *	@var 	object	
	 * 	@access 	protected
	 */
	protected $CPT_model = NULL;



	/**
	 *@ singleton method used to instantiate class object
	 *@ access public
	 *@ return EE_Registry instance
	 */	
	public static function instance() {
		// check if class object is instantiated
		if ( self::$_instance === NULL  or ! is_object( self::$_instance ) or ! ( self::$_instance instanceof EE_CPT_Strategy )) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	
	/**
	 * 	class constructor
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	private function __construct() {
		// EE registry
		$this->EE = EE_Registry::instance();
		// get CPT data
		$this->_CPTs = EE_Register_CPTs::get_CPTs();
		$this->_CPT_endpoints = $this->_set_CPT_endpoints();
		// load EE_Request_Handler
		add_action( 'parse_request', array( $this, 'apply_CPT_Strategy' ), 2 );
		
	}



	/**
	 * 	_set_CPT_endpoints - add CPT "slugs" to array of default espresso "pages"
	 *
	 * 	@access private
	 * 	@return array
	 */
	private function _set_CPT_endpoints() {
		$_CPT_endpoints = array();
		//printr( $CPTs, '$CPTs  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
		if ( is_array( $this->_CPTs )) {
			foreach ( $this->_CPTs as $CPT_type => $CPT ) {
				$_CPT_endpoints [ $CPT['singular_slug'] ] = $CPT_type;
				$_CPT_endpoints [ $CPT['plural_slug'] ] = $CPT_type;
			}
		}
		return $_CPT_endpoints;
	}


	/**
	 * 	_get_espresso_CPT_endpoints 
	 *
	 * 	@access public
	 * 	@return array
	 */
	public function get_CPT_endpoints() {
		return $this->_CPT_endpoints;
	}	


	/**
	 * 	apply_CPT_Strategy
	 *
	 * 	@access public
	 * 	@return array
	 */
	public function apply_CPT_Strategy( $WP_Object ) {
//		printr( $WP_Object, '$WP_Object  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
		// is current query for an EE CPT ?
		if ( isset( $WP_Object->query_vars['post_type'] ) && isset( $this->_CPTs[ $WP_Object->query_vars['post_type'] ] )) {
			// is EE on or off ?
			if ( EE_Maintenance_Mode::instance()->level() ) {
				// reroute CPT template view to maintenance_mode.template.php
				add_filter( 'template_include', array( 'EE_Maintenance_Mode', 'template_include' ), 99999 );
				return;
			}
			// grab details for the CPT the current query is for
			$this->CPT = $this->_CPTs[ $WP_Object->query_vars['post_type'] ];
			// set post type
			$this->CPT['post_type'] = $WP_Object->query_vars['post_type'];
			// the post or category or term that is triggering EE
			$this->CPT['espresso_page'] = $this->EE->REQ->is_espresso_page();
			// requested post name
			$this->CPT['post_name'] = $this->EE->REQ->get( 'post_name' );
			//printr( $this->CPT, '$this->CPT  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
			// make sure CPT name is set or things is gonna break
			if ( isset( $this->CPT['singular_name'] )) {
				// get CPT table data via CPT Model
				$this->CPT_model = $this->EE->load_model( $this->CPT['singular_name'] );
				$this->CPT['tables'] = $this->CPT_model->get_tables();
				// is there a Meta Table for this CPT?
				$this->CPT['meta_table'] = isset( $this->CPT['tables'][ $this->CPT['singular_name'] . '_Meta' ] ) ? $this->CPT['tables'][ $this->CPT['singular_name'] . '_Meta' ] : FALSE;
				// creates classname like:  EE_CPT_Event_Strategy
				$CPT_Strategy_class_name = 'CPT_' . $this->CPT['singular_name'] . '_Strategy';
				// load and instantiate
				 $CPT_Strategy = $this->EE->load_core ( $CPT_Strategy_class_name, array( 'EE' => $this->EE, 'CPT' =>$this->CPT ));	
//				printr( $CPT_Strategy, '$CPT_Strategy  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );

				add_filter( 'pre_get_posts', array( $this, 'pre_get_posts' ), 5 );
				add_filter( 'posts_fields', array( $this, 'posts_fields' ));
				add_filter( 'posts_join',	array( $this, 'posts_join' ));
				add_filter( 'get_' . $this->CPT['post_type'] . '_metadata', array( $CPT_Strategy, 'get_EE_post_type_metadata' ), 1, 4 );
				add_filter( 'the_posts',	array( $this, 'the_posts' ), 1, 2 );

			}				
		}
//		printr( $WP_Object, '$WP_Object  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
	}



	/**
	 * 	pre_get_posts
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function pre_get_posts(  $WP_Query  ) {

		if ( ! $WP_Query->is_main_query() ) {
			return;
		}
		// if no other module has already set a route
		if ( ! $this->EE->REQ->is_set( 'ee' )) {
			// check that route exists for CPT archive slug
			if ( is_archive() && EE_Config::get_route( $this->CPT['plural_slug'] )) {
				// ie: set "ee" to "events"
				$this->EE->REQ->set( 'ee', $this->CPT['plural_slug'] );
			// or does it match a single page CPT like /event/
			} else if ( is_single() && EE_Config::get_route( $this->CPT['singular_slug'] )) {
				// ie: set "ee" to "event"
				$this->EE->REQ->set( 'ee', $this->CPT['singular_slug'] );
			}
		}
		
		//d( $this->EE->REQ );

		return $WP_Query;
		
	}



	/**
	 * 	posts_fields
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	public function posts_fields( $SQL ) {
		// does this CPT have a meta table ?
		if ( isset( $this->CPT['meta_table'] )) {
			global $wpdb;
			// adds something like ", wp_esp_event_meta.* " to WP Query SELECT statement
			$SQL .= ', ' . $this->CPT['meta_table']->get_table_name() . '.* ' ;
		}
		//d( $SQL );
		return $SQL;
	}



	/**
	 * 	posts_join
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	public function posts_join( $SQL ) {
		// does this CPT have a meta table ?
		if ( isset( $this->CPT['meta_table'] )) {
			global $wpdb;
			// adds something like " LEFT JOIN wp_esp_event_meta ON ( wp_esp_event_meta.EVT_ID = wp_posts.ID ) " to WP Query JOIN statement
			$SQL .= ' LEFT JOIN ' . $this->CPT['meta_table']->get_table_name() . ' ON ( ' . $this->CPT['meta_table']->get_table_name() . '.' . $this->CPT['meta_table']->get_fk_on_table() . ' = ' . $wpdb->posts . '.ID ) ';
		}
		//d( $SQL );
		return $SQL;
	}



	/**
	 * 	the_posts
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function the_posts( $posts, WP_Query $wp_query ) {
//		d( $wp_query );
		$CPT_class = 'EE_' . $this->CPT['singular_name'];
		// loop thru posts
		if ( isset( $wp_query->posts )) {
			foreach( $wp_query->posts as $key => $post ) {
				if ( isset( $this->_CPTs[ $post->post_type ] )) {
					$post->$CPT_class = $this->CPT_model->instantiate_class_from_post_object( $post );
				}
			}
		}		
		return $wp_query->posts;
	}






}






/**
 * ------------------------------------------------------------------------
 *
 * EE_CPT_Default_Strategy
 *
 * @package			Event Espresso
 * @subpackage	/core/
 * @author				Brent Christensen 
 *
 * ------------------------------------------------------------------------
 */
class EE_CPT_Default_Strategy {

	/**
	 * 	EE_Registry Object
	 *	@var 	EE_Registry	$EE	
	 * 	@access 	protected
	 */
	protected $EE = NULL;

	/**
	 * $CPT - the current page, if it utilizes CPTs
	 *	@var 	object	
	 * 	@access 	protected
	 */
	protected $CPT = NULL;



	
	/**
	 * 	class constructor
	 *
	 *  @access 	private
	 *  @return 	void
	 */
	private function __construct( EE_Registry $EE, $CPT ) {
		$this->EE = $EE;
		$this->CPT = $CPT;
		//printr( $this->CPT, '$this->CPT  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
		add_filter( 'pre_get_posts', array( $this, 'pre_get_posts' ), 999 );
		add_filter( 'the_posts', array( $this, 'the_posts' ), 1, 2 );
	}






	/**
	 * 	pre_get_posts
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function pre_get_posts(  $WP_Query  ) {
		//printr( $WP_Query, '$WP_Query  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
		if ( ! $WP_Query->is_main_query() && ! $WP_Query->is_archive() ) {
			return;
		}
//		$WP_Query->set( 'post_type', array( $this->CPT['post_type'] ));
//		$WP_Query->set( 'fields', 'ids' );
		return $WP_Query;
	}





	/**
	 * 	wp
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function the_posts(  $posts, $WP_Query ) {
//		$EVT = $this->EE->load_model( 'Event' );
//		$EVT_IDs = array();
//		foreach( $WP_Query->posts as $WP_Post ) {
//			$EVT_IDs[] = $WP_Post->ID;
//		}
//		$events = $EVT->get_all( array( 0 =>array( 'EVT_ID' => array( 'IN', $EVT_IDs ), 'DTT_is_primary' => 1 ), 'force_join' =>array( 'Datetime' )));
//		printr( $WP_Query, '$WP_Query  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
//		printr( $EVT_IDs, '$EVT_IDs  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
//		printr( $events, '$events  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
		return $posts;
	}




	/**
	 * 	get_EE_post_type_metadata
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public function get_EE_post_type_metadata( $meta_value = NULL, $post_id, $meta_key, $single ) {

		return $meta_value;

	}


}






// End of file EE_CPT_Strategy.core.php
// Location: /core/EE_CPT_Strategy.core.php