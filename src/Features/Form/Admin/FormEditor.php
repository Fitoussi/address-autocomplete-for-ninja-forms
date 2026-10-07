<?php
/**
 * Native builder controls and four non-functional premium field promotions.
 *
 * @package NinjaGeolocationAutocomplete\Features\Form\Admin
 * @since 1.0.0
 */

namespace NinjaGeolocationAutocomplete\Features\Form\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Keep discovery separate from real field registration and saved form data.
 *
 * @since 1.0.0
 */
final class FormEditor {

	/**
	 * Register drawer groups and page-scoped assets.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'ninja_forms_field_type_sections', [ $this, 'sections' ] );
		add_filter( 'ninja_forms_available_fields', [ $this, 'available_fields' ], 20 );
		add_filter( 'ninja_forms_field_settings_groups', [ $this, 'groups' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'ninja_forms_builder_templates', [ $this, 'templates' ] );
	}

	/**
	 * Place the native Geolocation palette directly after Common Fields.
	 *
	 * @param array $sections Native palette.
	 * @return array
	 * @since 1.0.0
	 */
	public function sections( $sections ) {
		$sections['nfgeo_geolocation'] = [
			'id'         => 'nfgeo_geolocation',
			'nicename'   => __( 'Geolocation', 'address-autocomplete-for-ninja-forms' ),
			'fieldTypes' => [],
			'classes'    => 'nfgeo-geolocation',
		];
		$ordered                       = [];
		foreach ( $sections as $key => $section ) {
			if ( 'nfgeo_geolocation' === $key ) {
				continue;
			}
			$ordered[ $key ] = $section;
			if ( 'common' === $key ) {
				$ordered['nfgeo_geolocation'] = $sections['nfgeo_geolocation'];
			}
		}
		// Preserve a usable group if another extension removes Common Fields.
		if ( ! isset( $ordered['nfgeo_geolocation'] ) ) {
			$ordered['nfgeo_geolocation'] = $sections['nfgeo_geolocation'];
		}
		return $ordered;
	}

	/**
	 * Reuse premium's native unavailable-field cards without registering runtime fields.
	 *
	 * @param array $fields Existing Ninja available-field definitions.
	 * @return array Four discovery cards alongside the host's own definitions.
	 * @since 1.0.0
	 */
	public function available_fields( $fields ) {
		$features = [
			'nfgeo_map'                => [ __( 'Map', 'address-autocomplete-for-ninja-forms' ), __( 'Display connected locations on an interactive form map.', 'address-autocomplete-for-ninja-forms' ) ],
			'nfgeo_directions'         => [ __( 'Directions', 'address-autocomplete-for-ninja-forms' ), __( 'Connect origin, destination and waypoints to display routes and directions.', 'address-autocomplete-for-ninja-forms' ) ],
			'nfgeo_distance'           => [ __( 'Distance & Duration', 'address-autocomplete-for-ninja-forms' ), __( 'Calculate travel distance and estimated travel time between locations.', 'address-autocomplete-for-ninja-forms' ) ],
			'nfgeo_address_validation' => [ __( 'Address Validation', 'address-autocomplete-for-ninja-forms' ), __( 'Validate and standardize addresses using Google Address Validation.', 'address-autocomplete-for-ninja-forms' ) ],
		];
		foreach ( $features as $name => $feature ) {
			$fields[ $name ] = [
				'section'       => 'nfgeo_geolocation',
				'name'          => $name,
				'nicename'      => $feature[0],
				'link'          => \NinjaGeolocationAutocomplete\Admin\Dashboard::get_url(),
				'plugin_path'   => 'geolocation-for-ninja-forms/geolocation-for-ninja-form.php',
				'modal_content' => sprintf(
					'<div class="available-action-modal"><h2>%1$s</h2><p>%2$s</p><p>%3$s</p><div class="actions"><a target="_blank" rel="noopener noreferrer" href="%4$s">%5$s</a><a href="%6$s" class="primary nf-button">%7$s</a></div></div>',
					esc_html( $feature[0] ),
					esc_html__( 'This field is available with Ninja Geolocation. Upgrade to use it in your forms.', 'address-autocomplete-for-ninja-forms' ),
					esc_html( $feature[1] ),
					esc_url( NFGEOAC_SITE_URL . '/pricing/' ),
					esc_html__( 'View packages', 'address-autocomplete-for-ninja-forms' ),
					esc_url( \NinjaGeolocationAutocomplete\Admin\Dashboard::get_url() ),
					esc_html__( 'Explore features', 'address-autocomplete-for-ninja-forms' )
				),
			];
		}
		return $fields;
	}

	/**
	 * Add the Address Autocomplete options group to the native field drawer.
	 *
	 * @param array $groups Native settings groups.
	 * @return array
	 * @since 1.0.0
	 */
	public function groups( $groups ) {
		$groups['nfgeo_geolocation'] = [
			'id'       => 'nfgeo_geolocation',
			'label'    => __( 'Address Autocomplete', 'address-autocomplete-for-ninja-forms' ),
			'display'  => false,
			'priority' => 650,
		];
		return $groups;
	}

	/**
	 * Render a native multi-select that stores arrays.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function templates() {
		?>
		<script id="tmpl-nf-edit-setting-nfgeoac-select-multiple" type="text/template">
			<label for="{{ data.name }}">{{ data.label }} {{{ data.renderTooltip() }}}</label>
			<select id="{{ data.name }}" class="setting nfgeoac-multiple" data-id="{{ data.name }}" multiple="multiple">
				<# _.each( data.options, function( option ) { #>
				<option value="{{ option.value }}" <# if ( _.contains( data.value || [], option.value ) ) { #>selected<# } #>>{{ option.label }}</option>
				<# }); #>
			</select>
		</script>
		<?php
	}

	/**
	 * Enqueue only on the form builder.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue() {
		// Read-only screen routing, not a settings mutation.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = isset( $_GET['page'] ) && is_string( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		if ( ! in_array( $page, [ 'ninja-forms', 'nf-settings' ], true ) ) {
			return;
		}
		wp_enqueue_style( 'nfgeoac-choices', NFGEOAC_URL . 'assets/css/choices.css', [], NFGEOAC_VERSION );
		wp_enqueue_script( 'nfgeoac-form-editor', NFGEOAC_URL . 'build/js/admin/nfgeo-form-editor.min.js', [ 'jquery' ], NFGEOAC_VERSION, true );
		wp_enqueue_style( 'nfgeoac-form-editor', NFGEOAC_URL . 'build/css/admin/nfgeo-admin.min.css', [ 'nfgeoac-choices' ], NFGEOAC_VERSION );
		wp_localize_script(
			'nfgeoac-form-editor',
			'nfgeoAutocompleteEditor',
			[
				'allCountries' => __( 'All countries', 'address-autocomplete-for-ninja-forms' ),
				'allTypes'     => __( 'All place types', 'address-autocomplete-for-ninja-forms' ),
				'search'       => __( 'Search', 'address-autocomplete-for-ninja-forms' ),
			]
		);
	}
}
