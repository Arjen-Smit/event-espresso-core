<?php
/**
 * This file contains the EE_Invoice_message_type class.
 * @package      Event Espresso
 * @subpackage messages
 * @since           %VER%
 */
if ( ! defined('EVENT_ESPRESSO_VERSION')) exit('No direct script access allowed');

/**
 * The message type for invoices.
 *
 * This message type is used for generating invoice html (or pdf).  They are either triggered via the thank you page as a link, or the [INVOICE_URL] or  [INVOICE_LINK] shortcodes.
 *
 * @package        Event Espresso
 * @subpackage  messages
 * @since            %VER%
 * @author          Darren Ethier
 */
class EE_Invoice_message_type extends EE_message_type {

	public function __construct() {
		$this->name = 'invoice';
		$this->description = __('The invoice message type is triggered via a url on the thank you page and via at url generated by the [INVOICE_URL] or [INVOICE_LINK] shortcode.', 'event_espresso');
		$this->label = array(
			'singular' => __('invoice', 'event_espresso'),
			'pluarl' => __('invoices', 'event_espresso')
			);
		$this->_master_templates = array();
		parent::__construct();
	}


	protected function _set_admin_pages() {
		$this->admin_registered_pages = array(); //no admin pages to register this with.
	}



	protected function _set_data_handler() {
		$this->_data_handler = 'Gateways';
	}



	protected function _set_with_messengers() {
		$this->_with_messengers = array(
			'html' => array( 'pdf' )
			);
	}



	protected function _get_data_for_context( $context, EE_Registration $registration, $id ) {
		//receipt message type data handler is 'Gateways' and it expects a transaction object.
		$transaction = $registration->transaction();
		if ( $transaction instanceof EE_Transaction ) {
			return array( $transaction );
		}
		return array();
	}



	protected function _set_admin_settings_fields() {
		$this->_admin_settings_fields = array();
	}



	protected function _set_contexts() {
		$this->_context_label = array(
			'label' => __('recipient', 'event_espresso'),
			'plural' => __('recipients', 'event_espresso'),
			'description' => __('Recipient\'s are who will view the invoice.', 'event_espresso')
		);

		$this->_contexts = array(
			'purchaser' => array(
				'label' => __('Purchaser', 'event_espresso'),
				'description' => __('This template goes to the person who conducted the transaction.', 'event_espresso')
			)
		);
	}




	/**
	* used to set the valid shortcodes for the receipt message type
	*
	* @since   %VER%
	*
	* @return  void
	*/
	protected function _set_valid_shortcodes() {
		$this->_valid_shortcodes['purchaser'] = array(
			'attendee_list', 'attendee', 'datetime_list', 'datetime', 'event_list', 'event', 'event_meta', 'messenger', 'organization', 'primary_registration_list', 'primary_registration_details', 'ticket_list', 'ticket', 'transaction', 'venue', 'line_item_list', 'payment_list', 'line_item', 'payment', 'question_list', 'question'
		);
	}




	protected function _purchaser_addressees() {
		return parent::_primary_attendee_addressees();
	}
}
