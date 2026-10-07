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
 *
 * @since 1.0.0
 */
final class Settings {

	/**
	 * Ninja Forms owns nonces, permissions and native settings persistence.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'ninja_forms_plugin_settings_groups', [ $this, 'groups' ] );
		add_filter( 'ninja_forms_plugin_settings', [ $this, 'fields' ] );
	}

	/**
	 * Register the settings tab.
	 *
	 * @param array $groups Native settings groups.
	 * @return array
	 * @since 1.0.0
	 */
	public function groups( $groups ) {
		$groups['nfgeo_geolocation'] = [
			'id'    => 'nfgeo_geolocation',
			'label' => __( 'Address Autocomplete', 'address-autocomplete-for-ninja-forms' ),
		];
		return $groups;
	}

	/**
	 * Expose only the three browser settings.
	 *
	 * @param array $settings Native definitions.
	 * @return array
	 * @since 1.0.0
	 */
	public function fields( $settings ) {
		$settings['nfgeo_geolocation'] = [
			'nfgeoac_overview'                  => [
				'id'    => 'nfgeoac_overview',
				'type'  => 'html',
				'label' => '',
				'html'  => '<div class="nfgeoac-settings-notice"><h3>' . esc_html__( 'Need more than address autocomplete?', 'address-autocomplete-for-ninja-forms' ) . '</h3><p>' . esc_html__( 'Explore connected maps, current-location detection, dynamic fields, directions, distance and more with Ninja Geolocation.', 'address-autocomplete-for-ninja-forms' ) . '</p><div class="nfgeoac-settings-notice__actions"><a class="button button-primary" href="' . esc_url( Dashboard::get_url() ) . '">' . esc_html__( 'Explore features', 'address-autocomplete-for-ninja-forms' ) . '</a><a class="button" href="' . esc_url( NFGEOAC_SITE_URL . '/pricing/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'View packages', 'address-autocomplete-for-ninja-forms' ) . '</a></div></div>',
			],
			'nfgeo_google_maps_browser_api_key' => [
				'id'    => 'nfgeo_google_maps_browser_api_key',
				'type'  => 'textbox',
				'label' => __( 'Google Browser API Key', 'address-autocomplete-for-ninja-forms' ),
				'desc'  => esc_html__( 'For this free plugin, enable Maps JavaScript API and Places API (New) for your browser key. No server key is required.', 'address-autocomplete-for-ninja-forms' ) . '<br>' . esc_html__( 'Need help?', 'address-autocomplete-for-ninja-forms' ) . ' <a href="' . esc_url( NFGEOAC_SITE_URL . '/docs/create-google-maps-api-keys-ninja-forms/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'View the Google API setup guide', 'address-autocomplete-for-ninja-forms' ) . '</a>',
			],
			'nfgeo_google_maps_country'         => [
				'id'    => 'nfgeo_google_maps_country',
				'type'  => 'textbox',
				'label' => __( 'Region Code', 'address-autocomplete-for-ninja-forms' ),
				'desc'  => __( 'A two-letter region code such as US. Use field settings to restrict suggestions to specific countries.', 'address-autocomplete-for-ninja-forms' ),
			],
			'nfgeo_google_maps_language'        => [
				'id'    => 'nfgeo_google_maps_language',
				'type'  => 'textbox',
				'label' => __( 'Language', 'address-autocomplete-for-ninja-forms' ),
				'desc'  => __( 'A language code such as en. Individual Address fields can override it.', 'address-autocomplete-for-ninja-forms' ),
			],
		];
		return $settings;
	}

	/**
	 * Sanitized scalar value.
	 *
	 * @param string $key Native setting name.
	 * @return string Sanitized scalar value.
	 * @since 1.0.0
	 */
	private static function read_string( $key ) {
		$value = \Ninja_Forms()->get_setting( $key );
		return is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';
	}

	/**
	 * Read browser-safe configuration, never server credentials or account tokens.
	 *
	 * @return array<string,mixed>
	 * @since 1.0.0
	 */
	public static function get_config() {
		$region   = self::read_string( 'nfgeo_google_maps_country' );
		$language = self::read_string( 'nfgeo_google_maps_language' );
		return [
			'googleMapsBrowserApiKey' => self::read_string( 'nfgeo_google_maps_browser_api_key' ),
			'regionCode'              => $region ? $region : 'US',
			'languageCode'            => $language ? $language : 'en',
			// Retain the premium-compatible public loading filter.
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'disableGoogleApi'        => (bool) apply_filters( 'nfgeo_disable_google_maps_api', ! empty( \Ninja_Forms()->get_setting( 'nfgeo_disable_google_maps_api' ) ) ),
		];
	}
}
