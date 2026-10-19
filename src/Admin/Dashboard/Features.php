<?php
/**
 * Local feature catalog for the current Starter and complete Pro products.
 *
 * @package NinjaGeolocationAutocomplete\Admin\Dashboard
 *
 * @since 1.0.0
 */

namespace NinjaGeolocationAutocomplete\Admin\Dashboard;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Presentation only, not licenses, remote feeds or runtime entitlement gates.
 *
 * @since 1.0.0
 */
final class Features {

	/**
	 * Prioritize major capabilities without changing comparison-table order.
	 *
	 * @since 1.0.0
	 *
	 * @param array<string,array> $features Existing feature definitions.
	 * @return array<string,array> Overview cards in feature-first order.
	 */
	public static function order_for_overview( $features ) {
		$order = [
			'autocomplete',
			'location-map',
			'geocoder',
			'dynamic-fields',
			'distance',
			'pricing',
			'directions',
			'drawing',
			'nearby',
			'validation',
			'entry-maps',
			'search',
			'entry-details',
			'current-location',
			'coordinates',
			'location-controls',
			'quick-setups',
			'templates',
			'gmw-integration',
		];
		$ordered = [];
		foreach ( $order as $id ) {
			if ( isset( $features[ $id ] ) ) {
				$ordered[ $id ] = $features[ $id ];
			}
		}
		return $ordered + $features;
	}

	/**
	 * Return locally curated demos that actually illustrate the feature.
	 *
	 * Empty results deliberately omit links for setup helpers or features without
	 * a relevant demo. These are ordinary outbound links, never remote embeds.
	 *
	 * @since 1.0.0
	 *
	 * @param string $feature_id Public feature identifier.
	 * @return array<array{label:string,url:string}> Feature-specific demo links.
	 */
	public static function get_demo_links( $feature_id ) {
		$demos = [
			'location-map' => [
				[ 'label' => __( 'View demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/' ],
			],
			'geocoder' => [
				[ 'label' => __( 'View demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/' ],
			],
			'dynamic-fields' => [
				[ 'label' => __( 'View demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/' ],
			],
			'distance' => [
				[ 'label' => __( 'View demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/distance-duration-price-calculations/' ],
			],
			'pricing' => [
				[ 'label' => __( 'View demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/distance-duration-price-calculations/' ],
			],
			'directions' => [
				[ 'label' => __( 'View demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/routes-directions/' ],
			],
			'drawing' => [
				[ 'label' => __( 'View demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/drawing-tools/' ],
			],
			'nearby' => [
				[ 'label' => __( 'View demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/nearest-locations/' ],
			],
			'entry-maps' => [
				[ 'label' => __( 'View single-entry demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/single-entry-map/' ],
				[ 'label' => __( 'View mashup demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/mashup-map/' ],
			],
			'current-location' => [
				[ 'label' => __( 'View demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/' ],
			],
			'coordinates' => [
				[ 'label' => __( 'View demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/' ],
			],
			'validation' => [
				[ 'label' => __( 'View demo', 'wpgeo-address-autocomplete-for-ninja-forms' ), 'url' => 'https://demo.ninjageolocation.com/address-validation/' ],
			],
		];
		return $demos[ $feature_id ] ?? [];
	}
	/**
	 * Public package headings.
	 *
	 * @return array<string,string> Public package headings.
	 *
	 * @since 1.0.0
	 */
	public static function get_packages() {
		return [
			'free'    => __( 'Free', 'wpgeo-address-autocomplete-for-ninja-forms' ),
			'starter' => __( 'Starter', 'wpgeo-address-autocomplete-for-ninja-forms' ),
			'pro'     => __( 'Pro', 'wpgeo-address-autocomplete-for-ninja-forms' ),
		];
	}
	/**
	 * Local descriptions of free and premium capabilities.
	 *
	 * @return array Local descriptions of free and premium capabilities.
	 *
	 * @since 1.0.0
	 */
	public static function get_features() {
		return [
			'autocomplete'      => [
				'title'       => __( 'Address autocomplete', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Google Places API (New) in a dedicated single-line Address field.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Countries, place types, per-field language, location bias, global region and language, and required suggestion selection.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'location-alt',
				'doc'         => '',
				'packages'    => [ 'free', 'starter', 'pro' ],
				'included'    => true,
			],
			'location-map'      => [
				'title'       => __( 'Interactive form maps', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Show connected locations and let visitors refine them with a draggable marker.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Customize map appearance, zoom, default location and marker behavior.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'location',
				'doc'         => '',
				'packages'    => [ 'starter', 'pro' ],
				'included'    => false,
			],
			'geocoder'          => [
				'title'       => __( 'Geocoder & complete location data', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Store formatted addresses, coordinates and individual location components.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Connect addresses, maps and ordinary fields to shared geocoding results.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'database',
				'doc'         => '',
				'packages'    => [ 'starter', 'pro' ],
				'included'    => false,
			],
			'dynamic-fields'    => [
				'title'       => __( 'Dynamic location fields', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Populate city, region, postal code, coordinates and other location values.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Use connected values in native fields and your form workflows.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'randomize',
				'doc'         => '',
				'packages'    => [ 'starter', 'pro' ],
				'included'    => false,
			],
			'current-location'  => [
				'title'       => __( 'Current-location detection', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Offer a locator button or detect a location when the form loads.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Browser location requires visitor permission; optional IP location needs a provider.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'admin-site-alt3',
				'doc'         => '',
				'packages'    => [ 'starter', 'pro' ],
				'included'    => false,
			],
			'coordinates'       => [
				'title'       => __( 'Coordinate inputs', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Collect latitude and longitude directly.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'A dedicated input for workflows where an address is not the starting point.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'admin-generic',
				'doc'         => '',
				'packages'    => [ 'starter', 'pro' ],
				'included'    => false,
			],
			'location-controls' => [
				'title'       => __( 'Standalone Locator & Reset fields', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Place current-location and reset buttons where they fit your form.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Separate controls complement the locator inside premium Address fields.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'controls-repeat',
				'doc'         => '',
				'packages'    => [ 'starter', 'pro' ],
				'included'    => false,
			],
			'directions'        => [
				'title'       => __( 'Directions & route displays', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Connect an origin, destination and waypoints, and display route details.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Route maps, summaries and turn-by-turn directions with connected fields.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'leftright',
				'doc'         => '',
				'packages'    => [ 'pro' ],
				'included'    => false,
			],
			'distance'          => [
				'title'       => __( 'Distance & travel time', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Calculate travel distance and duration or straight-line distance.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Use readable results or numeric distance and duration in form calculations.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'performance',
				'doc'         => '',
				'packages'    => [ 'pro' ],
				'included'    => false,
			],
			'validation'        => [
				'title'       => __( 'Address validation', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Compare addresses with Google Address Validation recommendations.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Review corrections and validation results. Requires the Address Validation API.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'yes-alt',
				'doc'         => '',
				'packages'    => [ 'pro' ],
				'included'    => false,
			],
			'entry-maps'        => [
				'title'       => __( 'Saved-entry maps', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Display a saved location on its own map or multiple locations together.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Single-entry and mashup map shortcodes outside your form.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'admin-multisite',
				'doc'         => '',
				'packages'    => [ 'pro' ],
				'included'    => false,
			],
			'nearby'            => [
				'title'       => __( 'Nearby locations & result panels', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Find destinations near a visitor and display selectable results.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Configure destination sources and results with an optional map.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'search',
				'doc'         => '',
				'packages'    => [ 'pro' ],
				'included'    => false,
			],
			'drawing'           => [
				'title'       => __( 'Drawing Tools & Drawing Shape', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'description' => __( 'Draw markers, lines, polygons, rectangles, circles and freehand shapes.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'details'     => __( 'Collect shapes and geometry outputs on a connected map.', 'wpgeo-address-autocomplete-for-ninja-forms' ),
				'icon'        => 'edit',
				'doc'         => '',
				'packages'    => [ 'pro' ],
				'included'    => false,
			],
		];
	}
}
