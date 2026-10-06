<?php
/**
 * Native host wiring without framework, licensing or migrations.
 *
 * @package NinjaGeolocationAutocomplete\Core
 * @since 1.0.0
 */

namespace NinjaGeolocationAutocomplete\Core;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the standalone integrations once per request.
 */
final class Bootstrap {
	/**
	 * Register native field, settings, editor and render hooks.
	 */
	public function __construct() {
		new \NinjaGeolocationAutocomplete\Admin\Settings();
		new \NinjaGeolocationAutocomplete\Features\Form\Fields\Address\Field();
		new \NinjaGeolocationAutocomplete\Features\Form\Admin\FormEditor();
		new \NinjaGeolocationAutocomplete\Features\Form\Runtime\FormGeoProcessor();
	}
}
