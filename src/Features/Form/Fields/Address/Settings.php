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
 *
 * @since 1.0.0
 */
final class Settings {

	/**
	 * New-field defaults and supported option names.
	 *
	 * @return array<string,mixed> New-field defaults and supported option names.
	 *
	 * @since 1.0.0
	 */
	public static function defaults() {
		return [
			'nfgeo_address_autocomplete'                 => 1,
			'nfgeo_force_autocomplete_selection'         => 0,
			'nfgeo_force_autocomplete_selection_message' => __( 'Please select an address from the suggested results.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_types'           => [],
			'nfgeo_address_autocomplete_country'         => [],
			'nfgeo_address_autocomplete_language'        => '',
			'nfgeo_autocomplete_restriction_usage'       => '',
			'nfgeo_autocomplete_proximity_lat'           => '',
			'nfgeo_autocomplete_proximity_lng'           => '',
			'nfgeo_autocomplete_proximity_radius'        => '',
			'nfgeo_autocomplete_bounds_sw_point'         => '',
			'nfgeo_autocomplete_bounds_ne_point'         => '',
			'nfgeo_address_autocomplete_strict_bounds'   => 0,
		];
	}

	/**
	 * Native field-setting definitions.
	 *
	 * @return array<string,array> Native field-setting definitions.
	 * @since 1.0.0
	 */
	public static function get_settings() {
		$labels  = [
			'nfgeo_address_autocomplete'                 => __( 'Enable autocomplete', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_force_autocomplete_selection'         => __( 'Require address selection from suggestions', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_force_autocomplete_selection_message' => __( 'Selection alert message', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_types'           => __( 'Autocomplete result types', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_country'         => __( 'Restrict to countries', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_language'        => __( 'Language', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_restriction_usage'       => __( 'Location bias', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_proximity_lat'           => __( 'Latitude', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_proximity_lng'           => __( 'Longitude', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_proximity_radius'        => __( 'Radius (meters, up to 50,000)', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_bounds_sw_point'         => __( 'Southwest point (latitude, longitude)', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_bounds_ne_point'         => __( 'Northeast point (latitude, longitude)', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_strict_bounds'   => __( 'Restrict results to the bounds', 'address-autocomplete-for-ninja-forms' ),
		];
		$help = [
			'nfgeo_address_autocomplete'                 => __( 'Show address suggestions powered by Google Places.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_force_autocomplete_selection'         => __( 'If the visitor leaves a newly typed address without selecting a suggestion, clear the input and show your configured alert.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_force_autocomplete_selection_message' => __( 'Show this alert when the visitor leaves a newly typed address without selecting an autocomplete suggestion.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_types'           => __( 'Choose up to 5 types of places to include in autocomplete suggestions. Leave empty to include all place types.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_country'         => __( 'Select up to 5 countries to limit address suggestions to those countries. Leave empty to allow all countries.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_language'        => __( 'Choose a language for suggestions and place details. Leave the default selected to use the global language setting.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_restriction_usage'       => __( 'Favor suggestions near a configured point or within a rectangular area. This does not detect the visitor’s location. Area bounds only exclude outside results when the bounds restriction is enabled.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_proximity_lat'           => __( 'Latitude of the center point used to favor nearby suggestions.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_proximity_lng'           => __( 'Longitude of the center point used to favor nearby suggestions.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_proximity_radius'        => __( 'Radius around the center point, in meters (up to 50,000). This favors nearby suggestions but does not exclude results outside the radius.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_bounds_sw_point'         => __( 'Enter the southwest corner as latitude, longitude, for example: 26.423277, -82.1371324.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_autocomplete_bounds_ne_point'         => __( 'Enter the northeast corner as latitude, longitude, for example: 26.4724595, -82.0217760.', 'address-autocomplete-for-ninja-forms' ),
			'nfgeo_address_autocomplete_strict_bounds'   => __( 'Limit suggestions to the configured rectangular area instead of only favoring results within it. Applies when Location bias is set to Area bounds.', 'address-autocomplete-for-ninja-forms' ),
		];
		$selects = [
			'nfgeo_address_autocomplete_language'  => ReferenceData::get_languages_as_options(),
			'nfgeo_address_autocomplete_types'     => ReferenceData::get_place_types_as_options(),
			'nfgeo_address_autocomplete_country'   => ReferenceData::get_countries_as_options(),
			'nfgeo_autocomplete_restriction_usage' => [
				[
					'label' => __( 'None', 'address-autocomplete-for-ninja-forms' ),
					'value' => '',
				],
				[
					'label' => __( 'Proximity', 'address-autocomplete-for-ninja-forms' ),
					'value' => 'proximity',
				],
				[
					'label' => __( 'Area bounds', 'address-autocomplete-for-ninja-forms' ),
					'value' => 'area_bounds',
				],
			],
		];
		$selects['nfgeo_address_autocomplete_language'] = array_merge(
			[
				[
					'label' => __( 'Default (global language)', 'address-autocomplete-for-ninja-forms' ),
					'value' => '',
				],
			],
			$selects['nfgeo_address_autocomplete_language']
		);
		$fields = [];
		foreach ( self::defaults() as $key => $value ) {
			$type = is_int( $value ) ? 'toggle' : 'textbox';
			if ( isset( $selects[ $key ] ) ) {
				$type = is_array( $value ) ? 'nfgeoac-select-multiple' : 'select';
			}
			$fields[ $key ] = [
				'name'  => $key,
				'type'  => $type,
				'group' => 'nfgeo_geolocation',
				'label' => $labels[ $key ],
				'help'  => $help[ $key ],
				'width' => 'full',
				'value' => $value,
			];
			if ( isset( $selects[ $key ] ) ) {
				$fields[ $key ]['options'] = $selects[ $key ];
			}
		}
		return $fields;
	}
}
