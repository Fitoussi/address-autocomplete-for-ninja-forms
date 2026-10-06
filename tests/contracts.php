<?php
/** Fixture contracts; these do not replace installed WordPress acceptance. */
define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['hooks'] = [];
function add_action( $hook, $callback, $priority = 10, $args = 1 ) { $GLOBALS['hooks'][$hook][] = $callback; }
function add_filter( $hook, $callback, $priority = 10, $args = 1 ) { add_action( $hook, $callback, $priority, $args ); }
function __( $text, $domain = '' ) { return $text; }
function sanitize_text_field( $value ) { return strip_tags( $value ); }
function plugin_basename( $file ) { return basename( dirname( $file ) ) . '/' . basename( $file ); }
function plugin_dir_path( $file ) { return dirname( $file ) . '/'; }
function plugin_dir_url( $file ) { return 'https://fixture.test/plugins/' . basename( dirname( $file ) ) . '/'; }
function apply_filters( $hook, $value ) { return $value; }
function wp_json_encode( $value, $flags = 0 ) { return json_encode( $value, $flags ); }
function wp_enqueue_script() { $GLOBALS['scripts'][] = func_get_args(); }
function wp_enqueue_style() {}
function wp_add_inline_script( $handle, $value, $where ) { $GLOBALS['inline'] = $value; }
function get_option( $key, $default = false ) { return $GLOBALS['options'][$key] ?? $default; }
function Ninja_Forms() { return new class { public function get_setting( $key ) { return 'fixture-value'; } }; }
class Ninja_Forms { const VERSION = '3.15.3'; }
class NF_Fields_Textbox { protected $_settings = []; protected $_nicename; public function __construct() {} }
class FrmAppHelper { public static function plugin_version() { return '6.35'; } }
class FrmFieldText {}

function expect( $truth, $message ) {
	if ( ! $truth ) { throw new RuntimeException( $message ); }
}
require dirname( __DIR__ ) . '/address-autocomplete-for-ninja-forms.php';
expect( NFGEOAC_PACKAGE_TYPE === 'free', 'Package identity' );
expect( ! defined( 'NFGEO_VERSION' ), 'Free must not define premium bootstrap constants' );
NinjaGeolocationAutocomplete\Loader::load();
expect( isset( $GLOBALS['hooks']['ninja_forms_register_fields'] ), 'Host field wiring' );
$defaults = NinjaGeolocationAutocomplete\Features\Form\Fields\Address\Settings::defaults();
expect( count( $defaults ) === 13, 'Explicit autocomplete whitelist' );
expect( ! isset( $defaults['nfgeo_geocoder_id'] ), 'No premium options' );
$schema = NinjaGeolocationAutocomplete\Features\Form\Fields\Address\Settings::get_settings();
expect( count( $schema ) === 13 && count( $schema['nfgeo_address_autocomplete_country']['options'] ) > 200, 'Native country controls' );
$features = NinjaGeolocationAutocomplete\Admin\DashboardFeatures::get_features();
expect( count( array_filter( $features, static function( $item ) { return $item['included']; } ) ) === 1, 'Only autocomplete included' );
expect( array_keys( NinjaGeolocationAutocomplete\Admin\DashboardFeatures::get_packages() ) === [ 'free', 'starter', 'pro', 'agency' ], 'Host package lineup' );
$processor = new NinjaGeolocationAutocomplete\Features\Form\Runtime\FormGeoProcessor();
$input = [
	[ 'id' => 11, 'type' => 'nfgeo_address', 'nfgeo_address_autocomplete' => 1, 'nfgeo_geocoder_id' => 9, 'nfgeo_server_api_key' => 'must-not-export' ],
	[ 'id' => 12, 'type' => 'address' ],
	[ 'id' => 13, 'type' => 'nfgeo_map' ],
	[ 'id' => 14, 'settings' => [ 'type' => 'repeater', 'fields' => [
		[ 'id' => 15, 'settings' => [ 'type' => 'nfgeo_address', 'nfgeo_address_autocomplete' => 1.0 ] ],
	] ] ],
];
$args = 7;
expect( $processor->collect( $input, $args ) === $input, 'Render must not modify forms' );
expect( strpos( $GLOBALS['inline'], 'must-not-export' ) === false, 'No hidden credentials in payload' );
expect( strpos( $GLOBALS['inline'], 'geocoder_id' ) === false, 'No geocoder runtime' );
expect( strpos( $GLOBALS['inline'], '"id":"15"' ) !== false, 'Repeater child and numeric host normalization' );

$GLOBALS['hooks'] = [];
define( 'NFGEO_VERSION', 'premium-fixture' );
NinjaGeolocationAutocomplete\Loader::load();
expect( isset( $GLOBALS['hooks']['admin_notices'] ), 'Persistent conflict notice' );
expect( ! isset( $GLOBALS['hooks']['ninja_forms_register_fields'] ), 'Free yields to premium' );
echo "PHP contracts passed\n";
