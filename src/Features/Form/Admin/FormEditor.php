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
 */
final class FormEditor {
	/**
	 * Register drawer groups and page-scoped assets.
	 */
	public function __construct() {
		add_filter( 'ninja_forms_field_type_sections', array( $this, 'sections' ) );
		add_filter( 'ninja_forms_field_settings_groups', array( $this, 'groups' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'ninja_forms_builder_templates', array( $this, 'templates' ) );
	}
	/**
	 * Configure sections.
	 *
	 * @param array $sections Native palette.
	 * @return array
	 */
	public function sections( $sections ) {
		$sections['nfgeo_geolocation'] = array(
			'id'         => 'nfgeo_geolocation',
			'nicename'   => __( 'Address Autocomplete', 'address-autocomplete-for-ninja-forms' ),
			'fieldTypes' => array(),
			'classes'    => 'nfgeo-geolocation',
		);
		return $sections;
	}
	/**
	 * Configure groups.
	 *
	 * @param array $groups Native settings groups.
	 * @return array
	 */
	public function groups( $groups ) {
		$groups['nfgeo_geolocation'] = array(
			'id'       => 'nfgeo_geolocation',
			'label'    => __( 'Autocomplete', 'address-autocomplete-for-ninja-forms' ),
			'display'  => false,
			'priority' => 650,
		);
		return $groups;
	}
	/**
	 * Render a native multi-select that stores arrays.
	 */
	public function templates() {
		?>
		<script id="tmpl-nf-edit-setting-nfgeoac-select-multiple" type="text/template">
			<label class="nf-setting-label" for="{{ data.name }}">{{ data.label }}</label>
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
	 */
	public function enqueue() {
		// Read-only screen routing, not a settings mutation.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = isset( $_GET['page'] ) && is_string( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		if ( 'ninja-forms' !== $page ) {
			return;
		}
		wp_enqueue_script( 'nfgeoac-form-editor', NFGEOAC_URL . 'build/js/admin/nfgeo-form-editor.min.js', array( 'jquery' ), NFGEOAC_VERSION, true );
		wp_enqueue_style( 'nfgeoac-form-editor', NFGEOAC_URL . 'build/css/admin/nfgeo-admin.min.css', array(), NFGEOAC_VERSION );
		wp_localize_script(
			'nfgeoac-form-editor',
			'nfgeoAutocompleteEditor',
			array(
				'overviewUrl' => \NinjaGeolocationAutocomplete\Admin\Dashboard::get_url(),
				'pricingUrl'  => NFGEOAC_SITE_URL . '/pricing/',
				'premium'     => __( 'Available with Ninja Geolocation', 'address-autocomplete-for-ninja-forms' ),
				'features'    => array(
					'map'        => __( 'Map', 'address-autocomplete-for-ninja-forms' ),
					'directions' => __( 'Directions', 'address-autocomplete-for-ninja-forms' ),
					'distance'   => __( 'Distance & Duration', 'address-autocomplete-for-ninja-forms' ),
					'validation' => __( 'Address Validation', 'address-autocomplete-for-ninja-forms' ),
				),
			)
		);
	}
}
