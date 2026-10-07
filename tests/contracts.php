<?php
/** Fixture contracts; these do not replace installed WordPress acceptance. */
define( 'ABSPATH', __DIR__ . '/' );
$GLOBALS['hooks'] = array();
function add_action( $hook, $callback, $priority = 10, $args = 1 ) {
	$GLOBALS['hooks'][ $hook ][] = $callback; }
function add_filter( $hook, $callback, $priority = 10, $args = 1 ) {
	add_action( $hook, $callback, $priority, $args ); }
function register_activation_hook( $file, $callback ) {
	$GLOBALS['hooks'][ 'activate_' . plugin_basename( $file ) ][] = $callback; }
function __( $text, $domain = '' ) {
	return $text; }
function esc_html__( $text, $domain = '' ) {
	return htmlspecialchars( $text, ENT_QUOTES ); }
function esc_url( $url ) {
	return $url; }
function admin_url( $path ) {
	return 'https://fixture.test/wp-admin/' . $path; }
function add_query_arg( $key, $value, $url ) {
	return $url . '&' . $key . '=' . $value; }
function sanitize_text_field( $value ) {
	return strip_tags( $value ); }
function plugin_basename( $file ) {
	return basename( dirname( $file ) ) . '/' . basename( $file ); }
function plugin_dir_path( $file ) {
	return dirname( $file ) . '/'; }
function plugin_dir_url( $file ) {
	return 'https://fixture.test/plugins/' . basename( dirname( $file ) ) . '/'; }
function apply_filters( $hook, $value ) {
	return $value; }
function wp_json_encode( $value, $flags = 0 ) {
	return json_encode( $value, $flags ); }
function wp_enqueue_script() {
	$GLOBALS['scripts'][] = func_get_args(); }
function wp_enqueue_style() {}
function wp_add_inline_script( $handle, $value, $where ) {
	$GLOBALS['inline'] = $value; }
function get_option( $key, $default = false ) {
	return $GLOBALS['options'][ $key ] ?? $default; }
function Ninja_Forms() {
	return new class() { public function get_setting( $key ) {
			return 'fixture-value';
	} }; }
class Ninja_Forms {
	const VERSION = '3.15.3';
}
class NF_Fields_Textbox {
	protected $_settings = array();
	protected $_nicename; public function __construct() {}
}
class FrmAppHelper {
	public static function plugin_version() {
		return '6.35'; }
}
class FrmFieldText {}

function expect( $truth, $message ) {
	if ( ! $truth ) {
		throw new RuntimeException( $message ); }
}
require dirname( __DIR__ ) . '/address-autocomplete-for-ninja-forms.php';
expect( NFGEOAC_PACKAGE_TYPE === 'free', 'Package identity' );
expect( ! defined( 'NFGEO_VERSION' ), 'Free must not define premium bootstrap constants' );
NinjaGeolocationAutocomplete\Loader::load();
NinjaGeolocationAutocomplete\Loader::register_integration();
expect( isset( $GLOBALS['hooks']['ninja_forms_register_fields'] ), 'Host field wiring' );
$global_settings = new NinjaGeolocationAutocomplete\Admin\Settings();
$global_fields   = $global_settings->fields( array() );
$api_help        = $global_fields['nfgeo_geolocation']['nfgeo_google_maps_browser_api_key']['desc'];
expect( strpos( $api_help, 'https://ninjageolocation.com/docs/create-google-maps-api-keys-ninja-forms/' ) !== false && strpos( $api_help, 'target="_blank" rel="noopener noreferrer"' ) !== false, 'Browser-key setup link uses the native description and safe new-tab attributes' );
expect( strpos( $api_help, 'No server key is required.' ) !== false, 'Setup help distinguishes free browser-only requirements' );
$editor          = new NinjaGeolocationAutocomplete\Features\Form\Admin\FormEditor();
$groups          = $editor->groups( array() );
expect( $groups['nfgeo_geolocation']['id'] === 'nfgeo_geolocation' && $groups['nfgeo_geolocation']['label'] === 'Address Autocomplete', 'Specific field-options label retains its existing group identity' );
$native_sections = array(
	'saved'    => array( 'id' => 'saved' ),
	'common'   => array( 'id' => 'common' ),
	'userinfo' => array( 'id' => 'userinfo' ),
	'misc'     => array( 'id' => 'misc' ),
);
$sections        = $editor->sections( $native_sections );
expect( array_keys( $sections ) === array( 'saved', 'common', 'nfgeo_geolocation', 'userinfo', 'misc' ), 'Geolocation directly follows Common Fields' );
expect( $sections['userinfo'] === $native_sections['userinfo'], 'Host groups remain unchanged' );
expect( isset( $editor->sections( array() )['nfgeo_geolocation'] ), 'Group survives a missing Common Fields section' );
ob_start();
$editor->templates();
$template = ob_get_clean();
expect( strpos( $template, 'nf-setting-label' ) === false, 'Multi-select labels must not use section-header styling' );
expect( strpos( $template, '{{{ data.renderTooltip() }}}' ) !== false, 'Country and type controls use the native tooltip renderer' );
$defaults = NinjaGeolocationAutocomplete\Features\Form\Fields\Address\Settings::defaults();
expect( count( $defaults ) === 13, 'Explicit autocomplete whitelist' );
expect( ! isset( $defaults['nfgeo_geocoder_id'] ), 'No premium options' );
$schema = NinjaGeolocationAutocomplete\Features\Form\Fields\Address\Settings::get_settings();
expect( $schema['nfgeo_address_autocomplete_types']['label'] === 'Autocomplete result types', 'Clear result-type label' );
expect( $schema['nfgeo_address_autocomplete_country']['label'] === 'Restrict to countries', 'Clear country restriction label' );
foreach ( $schema as $key => $definition ) {
	expect( ! empty( $definition['help'] ) && strip_tags( $definition['help'] ) === $definition['help'], 'All supported controls provide plain-text native help' );
	expect( $definition['value'] === $defaults[ $key ] && $definition['name'] === $key, 'Adding help leaves defaults and saved option names unchanged' );
}
expect( strpos( $schema['nfgeo_force_autocomplete_selection']['help'], 'clear the input' ) !== false, 'Selection help describes blur clearing' );
expect( strpos( $schema['nfgeo_address_autocomplete_language']['help'], 'global language setting' ) !== false, 'Language help describes global defaults' );
expect( strpos( $schema['nfgeo_autocomplete_restriction_usage']['help'], 'does not detect' ) !== false, 'Bias help does not promise visitor detection' );
$products = NinjaGeolocationAutocomplete\Admin\Dashboard::get_products();
$product_urls = array_column( $products, 'url' );
expect( count( $product_urls ) === count( array_unique( $product_urls ) ), 'Discovery cards do not duplicate products' );
expect( in_array( 'https://gravitygeolocation.com/', $product_urls, true ), 'Gravity product is included in discovery cards' );
expect( count( $schema ) === 13 && count( $schema['nfgeo_address_autocomplete_country']['options'] ) > 200, 'Native country controls' );
$features = NinjaGeolocationAutocomplete\Admin\DashboardFeatures::get_features();
expect(
	count(
		array_filter(
			$features,
			static function ( $item ) {
				return $item['included'];
			}
		)
	) === 1,
	'Only autocomplete included'
);
expect( array_keys( NinjaGeolocationAutocomplete\Admin\DashboardFeatures::get_packages() ) === array( 'free', 'starter', 'pro', 'agency' ), 'Host package lineup' );
$processor = new NinjaGeolocationAutocomplete\Features\Form\Runtime\FormGeoProcessor();
$input     = array(
	array(
		'id'                         => 11,
		'type'                       => 'nfgeo_address',
		'nfgeo_address_autocomplete' => 1,
		'nfgeo_geocoder_id'          => 9,
		'nfgeo_server_api_key'       => 'must-not-export',
	),
	array(
		'id'   => 12,
		'type' => 'address',
	),
	array(
		'id'   => 13,
		'type' => 'nfgeo_map',
	),
	array(
		'id'       => 14,
		'settings' => array(
			'type'   => 'repeater',
			'fields' => array(
				array(
					'id'       => 15,
					'settings' => array(
						'type'                       => 'nfgeo_address',
						'nfgeo_address_autocomplete' => 1.0,
					),
				),
			),
		),
	),
);
$args      = 7;
expect( $processor->collect( $input, $args ) === $input, 'Render must not modify forms' );
expect( strpos( $GLOBALS['inline'], 'must-not-export' ) === false, 'No hidden credentials in payload' );
expect( strpos( $GLOBALS['inline'], 'geocoder_id' ) === false, 'No geocoder runtime' );
expect( strpos( $GLOBALS['inline'], '"id":"15"' ) !== false, 'Repeater child and numeric host normalization' );

$GLOBALS['hooks'] = array();
define( 'NFGEO_VERSION', 'premium-fixture' );
NinjaGeolocationAutocomplete\Loader::load();
expect( isset( $GLOBALS['hooks']['admin_notices'] ), 'Persistent conflict notice' );
expect( ! isset( $GLOBALS['hooks']['ninja_forms_register_fields'] ), 'Free yields to premium' );
echo "PHP contracts passed\n";
