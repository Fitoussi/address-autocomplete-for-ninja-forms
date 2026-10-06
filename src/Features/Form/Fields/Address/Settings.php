<?php
/**
 * Autocomplete-only schema; saved nfgeo names match premium.
 *
 * @package NinjaGeolocationAutocomplete\Features\Form\Fields\Address
 * @since 1.0.0
 */

namespace NinjaGeolocationAutocomplete\Features\Form\Fields\Address;
use NinjaGeolocationAutocomplete\Helpers\ReferenceData;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Keep supported settings and defaults explicit.
 */
final class Settings {
	/**
	 * New-field defaults and supported option names.
	 *
	 * @return array<string,mixed> New-field defaults and supported option names.
	 */
	public static function defaults() {
		return array(
			'nfgeo_address_autocomplete'                 => 1,
			'nfgeo_force_autocomplete_selection'         => 0,
			'nfgeo_force_autocomplete_selection_message' => __( 'Please select an address from the suggested results.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_types'           => array(),
			'nfgeo_address_autocomplete_country'         => array(),
			'nfgeo_address_autocomplete_language'        => '',
			'nfgeo_autocomplete_restriction_usage'       => '',
			'nfgeo_autocomplete_proximity_lat'           => '',
			'nfgeo_autocomplete_proximity_lng'           => '',
			'nfgeo_autocomplete_proximity_radius'        => '',
			'nfgeo_autocomplete_bounds_sw_point'         => '',
			'nfgeo_autocomplete_bounds_ne_point'         => '',
			'nfgeo_address_autocomplete_strict_bounds'   => 0,
		);
	}

	/**
	 * Native field-setting definitions.
	 *
	 * @return array<string,array> Native field-setting definitions.
	 */
	public static function get_settings() {
		$labels  = array(
			'nfgeo_address_autocomplete'                 => __( 'Enable autocomplete', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_force_autocomplete_selection'         => __( 'Require address selection from suggestions', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_force_autocomplete_selection_message' => __( 'Selection alert message', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_types'           => __( 'Autocomplete Results Types', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_country'         => __( 'Restrict by Countries', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_language'        => __( 'Language', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_restriction_usage'       => __( 'Location Bias Type', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_proximity_lat'           => __( 'Latitude', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_proximity_lng'           => __( 'Longitude', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_proximity_radius'        => __( 'Radius in meters (maximum 50000)', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_bounds_sw_point'         => __( 'Southwest point (latitude,longitude)', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_bounds_ne_point'         => __( 'Northeast point (latitude,longitude)', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_strict_bounds'   => __( 'Restrict results to the bounds', 'address-autocomplete-for-ninja-forms' ),
		);
		$selects = array(
			'nfgeo_address_autocomplete_language'  => ReferenceData::get_languages_as_options(),
			'nfgeo_address_autocomplete_types'     => ReferenceData::get_place_types_as_options(),
			'nfgeo_address_autocomplete_country'   => ReferenceData::get_countries_as_options(),
			'nfgeo_autocomplete_restriction_usage' => array(
				array(
					'label' => __( 'None', 'address-autocomplete-for-ninja-forms' ),
					'value' => '',
				),
				array(
					'label' => __( 'Proximity', 'address-autocomplete-for-ninja-forms' ),
					'value' => 'proximity',
				),
				array(
					'label' => __( 'Area Bounds', 'address-autocomplete-for-ninja-forms' ),
					'value' => 'area_bounds',
				),
			),
		);
		$selects['nfgeo_address_autocomplete_language'] = array_merge(
			array(
				array(
					'label' => __( 'Default (global language)', 'address-autocomplete-for-ninja-forms' ),
					'value' => '',
				),
			),
			$selects['nfgeo_address_autocomplete_language']
		);
		$fields = array();
		foreach ( self::defaults() as $key => $value ) {
			$type = is_int( $value ) ? 'toggle' : 'textbox';
			if ( isset( $selects[ $key ] ) ) {
				$type = is_array( $value ) ? 'nfgeoac-select-multiple' : 'select';
			}
			$fields[ $key ] = array(
				'name'  => $key,
				'type'  => $type,
				'group' => 'nfgeo_geolocation',
				'label' => $labels[ $key ],
				'width' => 'full',
				'value' => $value,
			);
			if ( isset( $selects[ $key ] ) ) {
				$fields[ $key ]['options'] = $selects[ $key ];
			}
		}
		return $fields;
	}
}
