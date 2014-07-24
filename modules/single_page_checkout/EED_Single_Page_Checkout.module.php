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
 * Single Page Checkout (SPCO)
 *
 * @package			Event Espresso
 * @subpackage	/modules/single_page_checkout/
 * @author				Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class EED_Single_Page_Checkout  extends EED_Module {

	/**
	 * 	$checkout - EE_Checkout object for handling the properties of the current checkout process
	 * 	@access public
	 *	@var EE_Checkout $checkout
	 */
	public $checkout = NULL;




	/**
	 * @return EED_Single_Page_Checkout
	 */
	public static function instance() {
		return parent::get_instance( __CLASS__ );
	}





	/**
	 * 	set_hooks - for hooking into EE Core, other modules, etc
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public static function set_hooks() {
		EED_Single_Page_Checkout::set_definitions();
		// set routing
		EE_Config::register_route( '_register', 'EED_Single_Page_Checkout', 'run' );
//		EE_Config::register_route( 'process_reg_step', 'EED_Single_Page_Checkout', 'process_reg_step' );
//		EE_Config::register_route( 'finalize_registration', 'EED_Single_Page_Checkout', 'finalize_registration' );
		// add powered by EE msg
		add_action( 'AHEE__SPCO__reg_form_footer', array( 'EED_Single_Page_Checkout', 'display_registration_footer' ));
	}



	/**
	 * 	set_hooks_admin - for hooking into EE Admin Core, other modules, etc
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public static function set_hooks_admin() {
		EED_Single_Page_Checkout::set_definitions();
		EE_Config::register_route( '_register', 'EED_Single_Page_Checkout', 'run' );
//		EE_Config::register_route( 'process_reg_step', 'EED_Single_Page_Checkout', 'process_reg_step' );
//		EE_Config::register_route( 'finalize_registration', 'EED_Single_Page_Checkout', 'finalize_registration' );
		// set ajax hooks
		add_action( 'wp_ajax_espresso_process_reg_step', array( 'EED_Single_Page_Checkout', 'process_reg_step' ));
		add_action( 'wp_ajax_nopriv_espresso_process_reg_step', array( 'EED_Single_Page_Checkout', 'process_reg_step' ));
		add_action( 'wp_ajax_espresso_finalize_registration', array( 'EED_Single_Page_Checkout', 'finalize_registration' ));
		add_action( 'wp_ajax_nopriv_espresso_finalize_registration', array( 'EED_Single_Page_Checkout', 'finalize_registration' ));
	}



	/**
	 * 	set_definitions
	 *
	 *  @access 	public
	 *  @return 	void
	 */
	public static function set_definitions() {
		define( 'SPCO_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' . DS );
		define( 'SPCO_BASE_PATH', rtrim( str_replace( array( '\\', '/' ), DS, plugin_dir_path( __FILE__ )), DS ) . DS );
		define( 'SPCO_REG_STEPS_PATH', SPCO_BASE_PATH . 'reg_steps' . DS );
		define( 'SPCO_TEMPLATES_PATH', SPCO_BASE_PATH . 'templates' . DS );
	}



	/**
	 * 	ajax_process_registration_steps
	 */
	public static function process_reg_step() {
		EED_Single_Page_Checkout::instance()->_initialize();
	}



	/**
	 *    run
	 *
	 * @access    public
	 * @param WP $WP
	 * @return    void
	 */
	public function run( $WP ) {
		$this->_initialize();
	}



	/**
	 *    _initialize - initial module setup
	 *
	 * @access    private
	 * @throws EE_Error
	 * @return    void
	 */
	private function _initialize() {
		// load classes
		if ( ! isset( EE_Registry::instance()->REQ )) {
			EE_Registry::instance()->load_core( 'Request_Handler' );
		}
		$this->checkout->continue_reg = apply_filters( 'FHEE__EED_Single_Page_Checkout__init___continue_reg', TRUE );
		// load the reg steps array
		$this->load_reg_steps();
		// set the current step
		$this->checkout->set_current_step( EE_Registry::instance()->REQ->get( 'step' ));
		// and the next step
		$this->checkout->set_next_step();
		// and what we're doing on the current step
		$this->checkout->action = EE_Registry::instance()->REQ->get( 'action', 'display_spco_reg_form' );
		// returning from the thank you page ?
		$this->checkout->reg_url_link = EE_Registry::instance()->REQ->get( 'e_reg_url_link', FALSE );
		// get transaction from db or session
		$this->checkout->transaction = $this->checkout->reg_url_link && ! is_admin() ? $this->_get_transaction_and_cart_for_previous_visit() : $this->_get_transaction_and_cart_for_current_session();
		// and the registrations for the transaction
		$this->_get_registrations( $this->checkout->transaction );
		// initialize each reg step, which gives them the chance to potentially alter the process
		$this->_initialize_reg_steps();
		// get reg form
		$this->_check_form_submission();
		// checkout the action!!!
		$this->_process_form_action();
		// add some style and make it dance
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_and_scripts' ), 10 );
	}



	/**
	 *    load_reg_steps
	 * loads and instantiates each reg step based on the EE_Registry::instance()->CFG->registration->reg_steps array
	 *
	 * @access    private
	 * @throws EE_Error
	 * @return    array
	 */
	private function load_reg_steps() {
		// instantiate EE_Checkout object for handling the properties of the current checkout process
		$this->checkout = EE_Registry::instance()->load_file( SPCO_BASE_PATH, 'EE_Checkout', 'class', array(), FALSE  );
		if ( ! $this->checkout instanceof EE_Checkout ) {
			throw new EE_Error( __( 'The EE_Checkout class could not be loaded.', 'event_espresso' ) );
		}
		// load EE_SPCO_Reg_Step base class
		EE_Registry::instance()->load_file( SPCO_REG_STEPS_PATH, 'EE_SPCO_Reg_Step', 'class'  );
		// filter list of reg_steps
		$reg_steps_to_load = apply_filters( 'AHEE__SPCO__load_reg_steps__reg_steps_to_load', $this->get_reg_steps() );
		// sort by key (order)
		ksort( $reg_steps_to_load );
		// loop through folders
		foreach ( $reg_steps_to_load as $order => $reg_step ) {
			// we need a
			if ( isset( $reg_step['file_path'] ) && isset( $reg_step['class_name'] ) && isset( $reg_step['slug'] )) {
				// instantiate step class using file path and class name
				$reg_step_obj = EE_Registry::instance()->load_file( $reg_step['file_path'], $reg_step['class_name'], 'class', $this->checkout, FALSE  );
				// did we gets the goods ?
				if ( $reg_step_obj instanceof EE_SPCO_Reg_Step ) {
					// set reg step order based on config
					$reg_step_obj->set_order( $order );
					// add instantiated reg step object to the master reg steps array
					$this->checkout->add_reg_step( $reg_step_obj );
				}
			}
		}
		EE_Registry::instance()->CFG->registration->skip_reg_confirmation = TRUE;
		EE_Registry::instance()->CFG->registration->reg_confirmation_last = TRUE;
		// skip the registration_confirmation page ?
		if ( EE_Registry::instance()->CFG->registration->skip_reg_confirmation ) {
			// just remove it from the reg steps array
			$this->checkout->remove_reg_step( 'registration_confirmation' );
		} else if ( EE_Registry::instance()->CFG->registration->reg_confirmation_last && isset( 	$this->checkout->reg_steps['registration_confirmation'] )) {
			// set the order to something big like 100
			$this->checkout->set_reg_step_order( 'registration_confirmation', 100 );
		}
		// filter the array for good luck
		$this->checkout->reg_steps = apply_filters( 'FHEE__Single_Page_Checkout__load_reg_steps__reg_steps', $this->checkout->reg_steps );
		// finally re-sort based on the reg step class order properties
		$this->checkout->sort_reg_steps();
	}



	/**
	 *    get_reg_steps
	 *
	 * @access 	public
	 * @return 	array
	 */
	public function get_reg_steps() {
		$reg_steps = EE_Registry::instance()->CFG->registration->reg_steps;
		if ( empty( $reg_steps )) {
			$reg_steps = array(
				10 => array(
					'file_path' => EE_MODULES . DS . 'single_page_checkout' . DS . 'reg_steps' . DS,
					'class_name' => 'EE_SPCO_Reg_Step_Attendee_Information',
					'slug' => 'attendee_information'
				),
				20 => array(
					'file_path' => EE_MODULES . DS . 'single_page_checkout' . DS . 'reg_steps' . DS,
					'class_name' => 'EE_SPCO_Reg_Step_Registration_Confirmation',
					'slug' => 'registration_confirmation'
				),
				30 => array(
					'file_path' => EE_MODULES . DS . 'single_page_checkout' . DS . 'reg_steps' . DS,
					'class_name' => 'EE_SPCO_Reg_Step_Payment_Options',
					'slug' => 'payment_options'
				),
				999 => array(
					'file_path' => EE_MODULES . DS . 'single_page_checkout' . DS . 'reg_steps' . DS,
					'class_name' => 'EE_SPCO_Reg_Step_Finalize_Registration',
					'slug' => 'finalize_registration'
				)
			);
		}
		return $reg_steps;
	}



	/**
	 * 	_initialize_reg_steps
	 * simply loops thru all of the active reg steps and calls the initialize_reg_step() method
	 *
	 *  @access 	private
	 *  @return 	void
	 */
	private function _initialize_reg_steps() {
		foreach ( $this->checkout->reg_steps as $reg_step ) {
			$reg_step->initialize_reg_step();
		}
	}



	/**
	 * _check_form_submission
	 *
	 * @access private
	 * 	@return void
	 */
	private function _check_form_submission() {
		// ever heard that song by Blue Rodeo ?
		try {
			$this->checkout->current_step->generate_reg_form();
			// check for form submission
			if ( $this->checkout->current_step->reg_form->was_submitted() ) {
				// capture form data
				$this->checkout->current_step->reg_form->receive_form_submission();
				// validate form data
				if ( $this->checkout->current_step->reg_form->is_valid() && $this->checkout->continue_reg ) {
					// good registrant, you get to proceed
					EE_Error::add_success( $this->checkout->current_step->success_message() );
				} else {
					// bad, bad, bad registrant
					EE_Error::add_error( $this->checkout->current_step->reg_form->submission_error_message(), __FILE__, __FUNCTION__, __LINE__ );
					$this->checkout->action = 'display_spco_reg_form';
				}
			}
		} catch( EE_Error $e ) {
			$e->get_error();
		}
	}



	/**
	 * _process_action
	 *
	 * @access private
	 * 	@return void
	 */
	private function _process_form_action() {
		// what cha wanna do?
		switch( $this->checkout->action ) {
			// initial form view before anything has happened
			case 'display_spco_reg_form' :
				$this->_display_spco_reg_form();
				break;

			default :
				// meh... do one of those other steps first
				if ( ! empty( $this->checkout->action ) && method_exists( $this->checkout->current_step, $this->checkout->action )) {
					do_action( "AHEE__Single_Page_Checkout__before_{$this->checkout->current_step->slug()}_{$this->checkout->action}", $this->checkout->current_step );
					// call action on current step
					if ( call_user_func( array( $this->checkout->current_step, $this->checkout->action )) ) {
						// store our progress so far
						$this->checkout->stash_transaction();
						// advance to the next step! If you pass GO, collect $200
						$this->go_to_next_step();
					} else {
//						d( EE_Error::get_notices( FALSE ));
						$this->checkout->action = 'display_spco_reg_form';
						$this->_display_spco_reg_form();
					}
					do_action( "AHEE__Single_Page_Checkout__after_{$this->checkout->current_step->slug()}_{$this->checkout->action}", $this->checkout->current_step );
				} else {
					EE_Error::add_error(
						sprintf(
							__( 'The requested form action "%s" does not exist for the current "%s" registration step.', 'event_espresso' ),
							$this->checkout->action,
							$this->checkout->current_step->name()
						),
						__FILE__, __FUNCTION__, __LINE__
					);
				}
			// end default
		}
	}



	/**
	 * _get_transaction_and_cart_for_previous_visit
	 *
	 * @access private
	 * 	@return mixed EE_Transaction|NULL
	 */
	private function _get_transaction_and_cart_for_previous_visit() {
		// because the reg_url_link is present in the request, this is a return visit to SPCO, so we'll get the transaction data from the db
		$transaction = EE_Registry::instance()->load_model( 'Transaction' )->get_transaction_from_reg_url_link( $this->checkout->reg_url_link );
		// verify transaction
		if ( $transaction instanceof EE_Transaction ) {
			// and get the cart that was used for that transaction
			$this->checkout->cart = $this->_get_cart_for_transaction( $transaction );
			return $transaction;
		} else {
			EE_Error::add_error( __( 'Your Registration and Transaction information could not be retrieved from the db.', 'event_espresso' ), __FILE__, __FUNCTION__, __LINE__);
			return NULL;
		}
	}



	/**
	 * _get_cart_for_transaction
	 *
	 * @access private
	 * @param EE_Transaction $transaction
	 * @param bool           $from_session
	 * @return EE_Cart
	 */
	private function _get_cart_for_transaction( EE_Transaction $transaction, $from_session = FALSE ) {
		$cart = $from_session ? $transaction->get_cart_session() : EE_Cart::get_cart_from_txn( $transaction );
		// verify cart
		if ( ! $cart instanceof EE_Cart ) {
			$cart = EE_Registry::instance()->load_core( 'Cart' );
		}
		return $cart;
	}



	/**
	 * _get_transaction_and_cart_for_current_session
	 * 	generates a new EE_Transaction object and adds it to the $_transaction property.
	 *
	 * 	@access private
	 * 	@return EE_Transaction
	 */
	private function _get_transaction_and_cart_for_current_session() {
		// first check in the session
		$transaction = EE_Registry::instance()->SSN->get_session_data( 'transaction' );
		// verify transaction
		if ( $transaction instanceof EE_Transaction ) {
			// check if the TXN has an ID, which means it has already been saved to the db
			$TXN_ID = $transaction->ID();
			if ( $TXN_ID ) {
				// so this transaction has already been saved to the db earlier in the same session (ie: it's not a revisit)... so let's pull that
				$transaction = EEM_Transaction::instance()->get_one_by_ID( $TXN_ID );
				// verify transaction
				if ( ! $transaction instanceof EE_Transaction ) {
					EE_Error::add_error( __( 'The Transaction could not be retrieved from the db when attempting to process your registration information', 'event_espresso' ), __FILE__, __FUNCTION__, __LINE__);
				}
			}
			// and get the cart that was used for that transaction
			$this->checkout->cart = $this->_get_cart_for_transaction( $transaction, TRUE );
		} else {
			//  if there's no transaction, then this is the FIRST visit to SPCO
			// so load up the cart
			$this->checkout->cart = EE_Registry::instance()->load_core( 'Cart' );
			// and then create a new transaction
			$transaction = $this->_initialize_transaction();
		}
		return $transaction;
	}



	/**
	 * 	generates a new EE_Transaction object and adds it to the $_transaction property.
	 *
	 * 	@access private
	 * 	@return mixed EE_Transaction|NULL
	 */
	private function _initialize_transaction() {
		try {
			// create new TXN
			return EE_Transaction::new_instance( array(
				'TXN_timestamp' => current_time( 'timestamp' ),
				'TXN_total' => $this->checkout->cart->get_cart_grand_total(),
				'TXN_paid' => 0,
				'STS_ID' => EEM_Transaction::failed_status_code,
			));
		} catch( Exception $e ) {
			EE_Error::add_error( $e->getMessage(), __FILE__, __FUNCTION__, __LINE__);
		}
		return NULL;
	}



	/**
	 * _get_registrations
	 *
	 * @access private
	 * @param EE_Transaction $transaction
	 * @return EE_Cart
	 */
	private function _get_registrations( EE_Transaction $transaction ) {
		// first step: grab the registrants  { : o
		$registrations = $transaction->registrations( array(), TRUE );
		// verify registrations have been set
		if ( $registrations == NULL ) {
			// if no cached registrations, then check the db
			if ( $transaction->registrations() == NULL ) {
				// still nothing? then start from scratch
				$this->_initialize_registrations( $transaction );
			}
		}
		// sort by their original registration order
		usort( $registrations, array( 'EED_Single_Page_Checkout', 'sort_registrations_by_REG_count' ));
		// then loop thru the array
		foreach ( $registrations as $registration ) {
			// verify each registration
			if ( $registration instanceof EE_Registration ) {
				// we display all attendee info for the primary registrant
				if ( $this->checkout->reg_url_link == $registration->reg_url_link() && $registration->is_primary_registrant() ) {
					$this->checkout->primary_revisit = TRUE;
					break;
				} else if ( $this->checkout->reg_url_link != $registration->reg_url_link() ) {
					// but hide info if it doesn't belong to you
					$transaction->clear_cache( 'Registration', $registration->ID() );
				}
			}
		}
	}



	/**
	 *    adds related EE_Registration objects for each ticket in the cart to the current EE_Transaction object
	 *
	 * @access private
	 * @param EE_Transaction $transaction
	 * @return    void
	 */
	private function _initialize_registrations( EE_Transaction $transaction ) {
		//d($this->checkout->cart->all_ticket_quantity_count());
		if ( $transaction instanceof EE_Transaction ) {
			$att_nmbr = 0;
			$total_items = $this->checkout->cart->all_ticket_quantity_count();
			// now let's add the cart items to the $transaction
			foreach ( $this->checkout->cart->get_tickets() as $item ) {
				// grab the related ticket object for this line_item
				$ticket = $item->ticket();
				if ( ! $ticket instanceof EE_Ticket ){
					EE_Error::add_error(sprintf(__("Line item %s did not contain a valid ticket", "event_espresso"),$item->ID()), __FILE__, __FUNCTION__, __LINE__);
					break;
				}
				$first_datetime = $ticket->get_first_related( 'Datetime' );
				if ( ! $first_datetime instanceof EE_Datetime ){
					EE_Error::add_error(sprintf(__("The ticket (%s) is not associated with any valid datetimes.", "event_espresso"),$ticket->name()), __FILE__, __FUNCTION__, __LINE__ );
					continue;
				}
				$event = $first_datetime->get_first_related( 'Event' );
				if ( ! $event instanceof EE_Event ){
					EE_Error::add_error(sprintf(__("The ticket (%s) is not associated with a valid event.", "event_espresso"),$ticket->name()),__FILE__,__FUNCTION__,__LINE__);
					continue;
				}
				//do the following for each ticket of this type they selected
				for ( $x = 1; $x <= $item->quantity(); $x++ ) {
					$att_nmbr++;
					$reg_url_link = $att_nmbr . '-' . $item->code();

					$event_default_registration_status = $event->default_registration_status();
					$STS_ID = ! empty( $event_default_registration_status ) ? $event_default_registration_status : EE_Registry::instance()->CFG->registration->default_STS_ID;
					// now create a new registration for the ticket
					$registration = EE_Registration::new_instance( array(
						'EVT_ID' => $event->ID(),
						'TXN_ID' => $transaction->ID(),
						'TKT_ID' => $ticket->ID(),
						'STS_ID' => $STS_ID,
						'REG_date' => $transaction->datetime(),
						'REG_final_price' => $ticket->price(),
						'REG_session' => EE_Registry::instance()->SSN->id(),
						'REG_count' => $att_nmbr,
						'REG_group_size' => $total_items,
						'REG_url_link'	=> $reg_url_link
					));
					$registration->_add_relation_to( $event, 'Event', array(), $event->ID() );
					$registration->_add_relation_to( $item->ticket(), 'Ticket', array(), $item->ticket()->ID() );
					$transaction->_add_relation_to( $registration, 'Registration', array(), $reg_url_link );

				}
			}
			EE_Registry::instance()->SSN->set_session_data( array( 'transaction' => $transaction ));
			EE_Registry::instance()->SSN->update();

			//			echo '<h3>'. __CLASS__ . '->' . __FUNCTION__ . ' <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h3>';
		}
	}



	/**
	 * sorts registrations by REG_count
	 *
	 * @access public
	 * @param EE_Registration $reg_A
	 * @param EE_Registration $reg_B
	 * @return array()
	 */
	public static function sort_registrations_by_REG_count( EE_Registration $reg_A, EE_Registration $reg_B ) {
		// this shouldn't ever happen within the same TXN, but oh well
		if ( $reg_A->count() == $reg_B->count() ) {
			return 0;
		}
		return ( $reg_A->count() > $reg_B->count() ) ? 1 : -1;
	}



	/**
	 * 		translate_js_strings
	 *
	 * 		@access 		public
	 * 		@return 		void
	 */
	public function translate_js_strings() {
		EE_Registry::$i18n_js_strings['invalid_coupon'] = __('We\'re sorry but that coupon code does not appear to be valid. If this is incorrect, please contact the site administrator.', 'event_espresso');
		EE_Registry::$i18n_js_strings['required_field'] = __(' is a required question.', 'event_espresso');
		EE_Registry::$i18n_js_strings['required_multi_field'] = __(' is a required question. Please enter a value for at least one of the options.', 'event_espresso');
		EE_Registry::$i18n_js_strings['reg_step_error'] = __('This registration step could not be completed. Please refresh the page and try again.', 'event_espresso');
		EE_Registry::$i18n_js_strings['answer_required_questions'] = __('Please answer all required questions before proceeding.', 'event_espresso');
		EE_Registry::$i18n_js_strings['attendee_info_copied'] = __('The attendee information was successfully copied.<br/>Please ensure the rest of the registration form is completed before proceeding.', 'event_espresso');
		EE_Registry::$i18n_js_strings['enter_valid_email'] = __('You must enter a valid email address.', 'event_espresso');
		EE_Registry::$i18n_js_strings['valid_email_and_questions'] = __('You must enter a valid email address and answer all other required questions before you can proceed.', 'event_espresso');
		EE_Registry::$i18n_js_strings['no_payment_method'] = __( 'Please select a method of payment in order to continue.', 'event_espresso' );
		EE_Registry::$i18n_js_strings['process_registration'] = __( 'Please wait while we process your registration.<br />Do not refresh the page or navigate away while this is happening.<br/> Thank you for your patience.', 'event_espresso' );
		EE_Registry::$i18n_js_strings['language'] = get_bloginfo( 'language' );
		EE_Registry::$i18n_js_strings['EESID'] = EE_Registry::instance()->SSN->id();
	}



	/**
	 * 	enqueue_styles_and_scripts
	 *
	 * 	@access 		public
	 * 	@return 		void
	 */
	public function enqueue_styles_and_scripts() {
		// load css
		wp_register_style( 'single_page_checkout', SPCO_ASSETS_URL . 'single_page_checkout.css', array(), EVENT_ESPRESSO_VERSION );
		wp_enqueue_style( 'single_page_checkout' );
		// i18n
		$this->translate_js_strings();
		$this->checkout->current_step->translate_js_strings();
		// load JS
//		wp_enqueue_script( 'underscore' );
//		wp_register_script( 'single_page_checkout', SPCO_ASSETS_URL . 'single_page_checkout.js', array('espresso_core', 'underscore'), EVENT_ESPRESSO_VERSION, TRUE );
//		wp_enqueue_script( 'single_page_checkout' );
//		wp_localize_script( 'single_page_checkout', 'eei18n', EE_Registry::$i18n_js_strings );
		// add css and JS for current step
		$this->checkout->current_step->enqueue_styles_and_scripts();
	}



	/**
	 * 	display the Registration Single Page Checkout Form
	 *
	 * @access 	private
	 * @return 	string
	 */
	private function _display_spco_reg_form() {
		// form details
		$form_args = array(
			'name' 	=> 'single-page-checkout',
			'html_id' 	=> 'ee-single-page-checkout-dv',
			//template files
			'layout_strategy' => is_admin() ?
				new EE_Div_Per_Section_Layout() :
				new EE_Template_Layout( array(
					'layout_template_file' 			=> SPCO_TEMPLATES_PATH . 'registration_page_wrapper.template.php',
					'begin_template_file' 			=> NULL,
					'input_template_file' 				=> NULL,
					'subsection_template_file' 	=> NULL,
					'end_template_file' 				=> NULL,
					'template_args' => array(
							'empty_cart' 		=> count( $this->checkout->transaction->registrations() ) < 1 ? TRUE : FALSE,
							'revisit' 				=> $this->checkout->revisit,
							'reg_steps' 			=> $this->checkout->reg_steps,
							'next_step' 			=>  $this->checkout->next_step instanceof EE_SPCO_Reg_Step ? $this->checkout->next_step->slug() : '',
							'empty_msg' 		=> apply_filters(
								'FHEE__Single_Page_Checkout__display_spco_reg_form__empty_msg',
								sprintf(
									__( 'You need to %sselect at least one event%s before you can proceed with the registration process.', 'event_espresso' ),
									'<a href="'. add_query_arg( array( 'post_type' => 'espresso_events' ), site_url() ) . '" title="' . __( 'Return to Events list', 'event_espresso' ) . '">',
									'</a>'
								)
							)
						)
				)
			)
		);
//		d( $this->checkout );
		$this->checkout->registration_form = new EE_Form_Section_Proper( $form_args );
		// load template and add to output sent that gets filtered into the_content()
		EE_Registry::instance()->REQ->add_output( $this->checkout->registration_form->get_html_and_js() );
	}



	/**
	 *    add_extra_finalize_registration_inputs
	 *
	 * @access    public
	 * @param $next_step
	 * @internal  param string $label
	 * @return        string
	 */
	public function add_extra_finalize_registration_inputs( $next_step ) {
		if ( $next_step == 'finalize_registration' ) {
			echo '<div id="spco-extra-finalize_registration-inputs-dv"></div>';
		}
	}



	/**
	 * 	display_registration_footer
	 *
	 *  @access 	public
	 *  @return 	string
	 */
	public static function display_registration_footer() {
		if ( apply_filters( 'FHEE__EE_Front__Controller__show_reg_footer', EE_Registry::instance()->CFG->admin->show_reg_footer ) ) {
			if ( ! empty( EE_Registry::instance()->CFG->admin->affiliate_id )) {
				$url = add_query_arg( array( 'ap_id' => EE_Registry::instance()->CFG->admin->affiliate_id ), 'http://eventespresso.com/' );
				$url = apply_filters( 'FHEE__EE_Front_Controller__registration_footer__url', $url );
				echo apply_filters( 'FHEE__EE_Front_Controller__display_registration_footer','<div id="espresso-registration-footer-dv"><a href="' . $url . '" title="Event Registration Powered by Event Espresso" target="_blank">Event Registration and Ticketing</a> Powered by <a href="' . $url . '" title="Event Espresso - Event Registration and Management System for WordPress" target="_blank">Event Espresso</a></div>' );
			}
		}
	}



	/**
	 *        _process_return_to_reg_step_query_args
	 *
	 * @access 	private
	 * @param 	$args
	 * @return 	array
	 */
	private function _process_return_to_reg_step_query_args( $args ) {
		$ignore = array( 'ajax_action', 'espresso_ajax', 'noheader', 'spco-go-to-next-step-sbmt-btn', 'step', 'next_step' );
		foreach ( $_POST as $key => $value ) {
			if ( ! in_array( $key, $ignore )) {
				$args[ $key ] = isset( $value ) ? $value : '';
			}
		}
		return $args;
	}



	/**
	 *   handle ajax message responses and redirects
	 *
	 * @access public
	 * @param bool   $callback
	 * @param bool   $callback_param
	 * @return void
	 */
	public function go_to_next_step( $callback = FALSE, $callback_param = FALSE ) {
//		echo '<br/><h5 style="color:#2EA2CC;">' . __CLASS__ . '<span style="font-weight:normal;color:#0074A2"> -> </span>' . __FUNCTION__ . '() <br/><span style="font-size:9px;font-weight:normal;color:#666">' . __FILE__ . '</span>    <b style="font-size:10px;color:#333">  ' . __LINE__ . ' </b></h5>';

//		// advance to the next step! If you pass GO, collect $200
//		$this->_set_current_step( $this->checkout->next_step->slug() );
//		// and advance the next step as well
//		$this->_set_next_step();
		$this->checkout->json_response = array();

		$no_errors = TRUE;
		// check for valid callback function
		$valid_callback = $callback !== FALSE && $callback != '' && method_exists( $this, $callback ) ? TRUE : FALSE;
		// check for recursion
		if ( $this->checkout->action == $callback ) {
			EE_Error::add_error( __('A recursive loop was detected and the registration process was halted.', 'event_espresso'), __FILE__, __FUNCTION__, __LINE__ );
			$valid_callback = FALSE;
		}
		// setup URL for redirect
		if ( $this->checkout->redirect_to_thank_you_page ) {
			// setup the thank you page properly
			$this->checkout->thank_you_page_url = add_query_arg(
				array( 'e_reg_url_link' => $this->checkout->transaction->primary_registration()->reg_url_link() ),
				$this->checkout->thank_you_page_url
			);
			$this->checkout->json_response['return_data'] = array( 'redirect-to-thank-you-page' => $this->checkout->thank_you_page_url );
		}
		// grab notices
		$notices = EE_Error::get_notices( FALSE );
		// set JSON response and merge in notices
		$this->checkout->json_response = array_merge( $this->checkout->json_response, $notices );

//		if ( $this->checkout->current_step != 'attendee_information' ) {
//		if ( $this->checkout->action != 'process_reg_step' ) {
//			d( $this->checkout->transaction );
//			d( $this->checkout->current_step );
//			d( $this->checkout->next_step );
//			echo '<h4>$this->checkout->action : ' . $this->checkout->action . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//			echo '<h4>$callback : ' . $callback . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//			echo '<h4>$valid_callback : ' . $valid_callback . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//			echo '<h4>$success_msg : ' . $success_msg . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//			echo '<h4>$error_msg : ' . $error_msg . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//			echo '<h4>$this->checkout->revisit : ' . $this->checkout->revisit . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//			echo '<h4>REQ->ajax : ' . EE_Registry::instance()->REQ->ajax . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//			printr( $this->checkout->json_response, '$this->checkout->json_response  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
//		}

		$success_msg = isset( $notices['success'] ) ? $notices['success'] : FALSE;
		$error_msg = isset( $notices['errors'] ) ? $notices['errors'] : FALSE;
		$attention_msg = isset( $notices['attention'] ) ? $notices['attention'] : FALSE;
		// all good ?
		if ( $success_msg && ! ( $error_msg || $attention_msg )) {
			unset( $this->checkout->json_response['error'] );
			unset( $this->checkout->json_response['attention'] );
			// if not ajax, then return TRUE to advance to next step
			$no_errors = TRUE;
		// DERP!!!
		} elseif ( $error_msg || $attention_msg ) {
			unset( $this->checkout->json_response['success'] );
			// if not ajax, then return FALSE to repeat the current step while displaying the error notice
			$no_errors = FALSE;
		}
		// if this is an ajax request AND a callback function exists
		if ( EE_Registry::instance()->REQ->ajax  && $valid_callback ) {
			// send data through to the callback function
			$this->$callback( $callback_param, $success_msg );
		} else if ( EE_Registry::instance()->REQ->ajax ) {
			// just send the ajax
			$this->checkout->json_response = apply_filters( 'FHEE__EE_Single_Page_Checkout__JSON_response', $this->checkout->json_response );
			echo json_encode( $this->checkout->json_response );
			die();
		}

		// store notices in a transient
		EE_Error::get_notices( FALSE, TRUE, TRUE );
		// no errors, means progress to next step, but if next step is empty, then redirect to thank you page. errors means return to page we came from
//		$next_step = $no_errors ? $this->checkout->next_step : str_replace( 'process_', '', $this->checkout->current_step );
		if ( $no_errors && $this->checkout->next_step instanceof EE_SPCO_Reg_Step ) {
			$args = $this->_process_return_to_reg_step_query_args( array( 'ee' => '_register', 'step' => $this->checkout->next_step->slug() ));
//			d( $args );
			$redirect = add_query_arg( $args, $this->checkout->reg_page_base_url );
		} else {
			$redirect = $this->checkout->thank_you_page_url;
		}
//		echo '<h4>$no_errors : ' . $no_errors . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//		echo '<h4>$next_step : ' . $this->checkout->next_step->slug() . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//		echo '<h4>$redirect : ' . $redirect . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//		printr( EE_Registry::instance()->REQ, 'EE_Registry::instance()->REQ  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
		wp_safe_redirect( $redirect );
		exit();
	}

}
// End of file EED_Single_Page_Checkout.module.php
// Location: /modules/single_page_checkout/EED_Single_Page_Checkout.module.php
