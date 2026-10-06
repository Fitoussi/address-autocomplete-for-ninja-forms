<?php
/**
 * Plugin Name: Address Autocomplete for Ninja Forms
 * Plugin URI: https://ninjageolocation.com
 * Description: Modern Google Places autocomplete in a dedicated Ninja Forms Address field. Includes country and language controls, location bias and required suggestion selection. Requires Ninja Forms and your own Google API key.
 * Version: 1.0.0
 * Author: Eyal Fitoussi
 * Author URI: https://www.wpgeo.com
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Requires Plugins: ninja-forms
 * Text Domain: address-autocomplete-for-ninja-forms
 * Domain Path: /languages/
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package NinjaGeolocationAutocomplete
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Isolated bootstrap; saved nfgeo field/settings identities remain compatible.
define( 'NFGEOAC_VERSION', '1.0.0' );
define( 'NFGEOAC_PLUGIN_FILE', __FILE__ );
define( 'NFGEOAC_BASENAME', plugin_basename( __FILE__ ) );
define( 'NFGEOAC_PREFIX', 'nfgeo' );
define( 'NFGEOAC_PLUGIN_NAME', 'Address Autocomplete for Ninja Forms' );
define( 'NFGEOAC_PATH', plugin_dir_path( __FILE__ ) );
define( 'NFGEOAC_URL', plugin_dir_url( __FILE__ ) );
define( 'NFGEOAC_PACKAGE_TYPE', 'free' );
define( 'NFGEOAC_PACKAGE_LABEL', 'Address Autocomplete' );
define( 'NFGEOAC_SITE_URL', 'https://ninjageolocation.com' );
define( 'NFGEOAC_MIN_HOST_VERSION', '3.13.2' );

spl_autoload_register(
	static function ( $class_name ) {
		$prefix = 'NinjaGeolocationAutocomplete\\';
		if ( 0 !== strpos( $class_name, $prefix ) ) {
			return;
		}
		$file = __DIR__ . '/src/' . str_replace( '\\', '/', substr( $class_name, strlen( $prefix ) ) ) . '.php';
		if ( is_file( $file ) ) {
			require_once $file;
		}
	}
);

// Inspect all active plugin bootstraps before registering overlapping field types.
add_action( 'plugins_loaded', array( NinjaGeolocationAutocomplete\Loader::class, 'load' ), 20 );
