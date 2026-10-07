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
 *
 * @since 1.0.0
 */
final class Bootstrap {

	/**
	 * Register native field, settings, editor and render hooks.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		new \NinjaGeolocationAutocomplete\Admin\Settings();
		new \NinjaGeolocationAutocomplete\Features\Form\Fields\Address\Field();
		new \NinjaGeolocationAutocomplete\Features\Form\Admin\FormEditor();
		new \NinjaGeolocationAutocomplete\Features\Form\Runtime\FormGeoProcessor();
	}
}
