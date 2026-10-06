<?php
/**
 * Single-line Address field retaining the premium field identity.
 *
 * @package NinjaGeolocationAutocomplete\Features\Form\Fields\Address
 * @since 1.0.0
 */

namespace NinjaGeolocationAutocomplete\Features\Form\Fields\Address;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Native Ninja Forms parent properties require these exact names.
// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

/**
 * Use the host textbox lifecycle without geocoders or locator controls.
 */
class Field extends \NF_Fields_Textbox {
	/**
	 * Saved field identity.
	 *
	 * @var string Saved field identity.
	 */
	protected $_name = 'nfgeo_address';
	/**
	 * Native field type.
	 *
	 * @var string Native field type.
	 */
	protected $_type = 'nfgeo_address';
	/**
	 * Host textbox template without premium markup.
	 *
	 * @var string Host textbox template without premium markup.
	 */
	protected $_templates = 'textbox';
	/**
	 * Builder palette group.
	 *
	 * @var string Builder palette group.
	 */
	protected $_section = 'nfgeo_geolocation';
	/**
	 * Native icon.
	 *
	 * @var string Native icon.
	 */
	protected $_icon = 'map-marker';

	/**
	 * Register the real Address field and its minimal native controls.
	 */
	public function __construct() {
		parent::__construct();
		$this->_nicename = __( 'Address', 'address-autocomplete-for-ninja-forms' );
		$this->_settings = array_merge( $this->_settings, Settings::get_settings() );
		add_filter( 'ninja_forms_register_fields', array( $this, 'register' ) );
	}
	/**
	 * Register only the functional Address type.
	 *
	 * @param array $fields Host field classes.
	 * @return array
	 */
	public function register( $fields ) {
		$fields['nfgeo_address'] = $this;
		return $fields;
	}
}
