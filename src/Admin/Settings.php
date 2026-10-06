<?php
/**
 * Minimal native settings using the existing premium option keys.
 *
 * @package NinjaGeolocationAutocomplete\Admin
 * @since 1.0.0
 */

namespace NinjaGeolocationAutocomplete\Admin;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add browser-key, region and language controls only.
 */
final class Settings {
	/**
	 * Ninja Forms owns nonces, permissions and native settings persistence.
	 */
	public function __construct() {
		add_filter( 'ninja_forms_plugin_settings_groups', array( $this, 'groups' ) );
		add_filter( 'ninja_forms_plugin_settings', array( $this, 'fields' ) );
	}
	/**
	 * Register the settings tab.
	 *
	 * @param array $groups Native settings groups.
	 * @return array
	 */
	public function groups( $groups ) {
		$groups['nfgeo_geolocation'] = array(
			'id'    => 'nfgeo_geolocation',
			'label' => __( 'Address Autocomplete', 'address-autocomplete-for-ninja-forms' ),
		);
		return $groups;
	}
	/**
	 * Expose only the three browser settings.
	 *
	 * @param array $settings Native definitions.
	 * @return array
	 */
	public function fields( $settings ) {
		$settings['nfgeo_geolocation'] = array(
			'nfgeoac_overview'                  => array(
				'id'    => 'nfgeoac_overview',
				'type'  => 'html',
				'label' => __( 'Need more than autocomplete?', 'address-autocomplete-for-ninja-forms' ),
				'html'  => '<p>' . esc_html__( 'Explore connected maps, directions, distance and more.', 'address-autocomplete-for-ninja-forms' ) . '</p><a class="button" href="' . esc_url( Dashboard::get_url() ) . '">' . esc_html__( 'Explore features', 'address-autocomplete-for-ninja-forms' ) . '</a> <a class="button" href="' . esc_url( NFGEOAC_SITE_URL . '/pricing/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'View packages', 'address-autocomplete-for-ninja-forms' ) . '</a>',
			),
			'nfgeo_google_maps_browser_api_key' => array(
				'id'    => 'nfgeo_google_maps_browser_api_key',
				'type'  => 'textbox',
				'label' => __( 'Google Browser API Key', 'address-autocomplete-for-ninja-forms' ),
				'desc'  => __( 'Enable Maps JavaScript API and Places API (New) for your own Google API key.', 'address-autocomplete-for-ninja-forms' ),
			),
			'nfgeo_google_maps_country'         => array(
				'id'    => 'nfgeo_google_maps_country',
				'type'  => 'textbox',
				'label' => __( 'Region Code', 'address-autocomplete-for-ninja-forms' ),
				'desc'  => __( 'A two-letter region code such as US. Use field settings to restrict suggestions to specific countries.', 'address-autocomplete-for-ninja-forms' ),
			),
			'nfgeo_google_maps_language'        => array(
				'id'    => 'nfgeo_google_maps_language',
				'type'  => 'textbox',
				'label' => __( 'Language', 'address-autocomplete-for-ninja-forms' ),
				'desc'  => __( 'A language code such as en. Individual Address fields can override it.', 'address-autocomplete-for-ninja-forms' ),
			),
		);
		return $settings;
	}
	/**
	 * Sanitized scalar value.
	 *
	 * @param string $key Native setting name.
	 * @return string Sanitized scalar value.
	 */
	private static function read_string( $key ) {
		$value = \Ninja_Forms()->get_setting( $key );
		return is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';
	}
	/**
	 * Read browser-safe configuration, never server credentials or account tokens.
	 *
	 * @return array<string,mixed>
	 */
	public static function get_config() {
		$region   = self::read_string( 'nfgeo_google_maps_country' );
		$language = self::read_string( 'nfgeo_google_maps_language' );
		return array(
			'googleMapsBrowserApiKey' => self::read_string( 'nfgeo_google_maps_browser_api_key' ),
			'regionCode'              => $region ? $region : 'US',
			'languageCode'            => $language ? $language : 'en',
			// Retain the premium-compatible public loading filter.
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'disableGoogleApi'        => (bool) apply_filters( 'nfgeo_disable_google_maps_api', ! empty( \Ninja_Forms()->get_setting( 'nfgeo_disable_google_maps_api' ) ) ),
		);
	}
}
