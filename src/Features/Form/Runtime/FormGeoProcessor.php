<?php
/**
 * Collect only custom Address settings for the standalone frontend.
 *
 * @package NinjaGeolocationAutocomplete\Features\Form\Runtime
 * @since 1.0.0
 */

namespace NinjaGeolocationAutocomplete\Features\Form\Runtime;
use NinjaGeolocationAutocomplete\Admin\Settings;
use NinjaGeolocationAutocomplete\Features\Form\Fields\Address\Settings as FieldSettings;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prepare immutable browser-safe payloads at the render boundary.
 */
final class FormGeoProcessor {
	/**
	 * Rendered form configurations.
	 *
	 * @var array Rendered form configurations.
	 */
	private static $forms = array();
	/**
	 * Register normal and preview render hooks.
	 */
	public function __construct() {
		add_filter( 'ninja_forms_display_fields', array( $this, 'collect' ), 20, 2 );
		add_action( 'ninja_forms_before_container_preview', array( $this, 'preview' ), 20, 3 );
	}
	/**
	 * Configure preview.
	 *
	 * @param int   $form_id Form ID.
	 * @param array $settings Host settings.
	 * @param array $fields Fields.
	 */
	public function preview( $form_id, $settings, $fields ) {
		$this->collect( $fields, $form_id );
	}
	/**
	 * Leave native Address and component fields outside this adapter.
	 *
	 * @param array      $fields Native field payload.
	 * @param int|string $form_id Native form instance ID.
	 * @return array Unmodified host fields.
	 */
	public function collect( $fields, $form_id ) {
		$addresses = array();
		$queue     = array_values( (array) $fields );
		while ( $queue ) {
			$field = array_shift( $queue );
			if ( ! is_array( $field ) ) {
				continue;
			}
			$settings = isset( $field['settings'] ) && is_array( $field['settings'] ) ? array_replace( $field['settings'], $field ) : $field;
			// Repeatable Fieldsets carry child settings inside their own payload.
			if ( 'repeater' === ( $settings['type'] ?? '' ) && isset( $settings['fields'] ) && is_array( $settings['fields'] ) ) {
				$queue = array_merge( $queue, array_values( $settings['fields'] ) );
			}
			if ( 'nfgeo_address' !== ( $settings['type'] ?? '' ) || ! in_array( $settings['nfgeo_address_autocomplete'] ?? 1, array( true, 1, 1.0, '1', 'true' ), true ) ) {
				continue;
			}
			$options                               = array_intersect_key( $settings, FieldSettings::defaults() );
			$options['id']                         = (string) ( $field['id'] ?? '' );
			$options['type']                       = 'nfgeo_address';
			$options['nfgeo_address_autocomplete'] = 1;
			$addresses[]                           = $options;
		}
		if ( ! $addresses ) {
			return $fields;
		}
		self::$forms[ (string) $form_id ] = array(
			'formId' => (string) $form_id,
			'fields' => $addresses,
			'config' => Settings::get_config(),
		);
		wp_enqueue_script( 'nfgeoac-autocomplete', NFGEOAC_URL . 'build/js/frontend/address-autocomplete.min.js', array( 'jquery', 'nf-front-end' ), NFGEOAC_VERSION, true );
		wp_enqueue_style( 'nfgeoac-autocomplete', NFGEOAC_URL . 'assets/css/address-autocomplete.css', array(), NFGEOAC_VERSION );
		wp_add_inline_script( 'nfgeoac-autocomplete', 'window.nfgeoAutocompleteForms = Object.assign(window.nfgeoAutocompleteForms || {}, ' . wp_json_encode( self::$forms, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . ');', 'before' );
		return $fields;
	}
}
