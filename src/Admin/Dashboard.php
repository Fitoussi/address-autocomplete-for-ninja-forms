<?php
/**
 * Local product overview for the standalone Address Autocomplete plugin.
 * This presentation-only page never reads licenses, creates forms or writes
 * settings. Actual configuration remains on the native Ninja Forms screen.
 *
 * @package NinjaGeolocationAutocomplete\Admin
 * @since 1.0.0
 */

namespace NinjaGeolocationAutocomplete\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Own local product-page metadata, routing and page-scoped presentation.
 *
 * @since 1.0.0
 */
final class Dashboard {

	/**
	 * Stable product-page identity independent of the installed folder name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private const PAGE_SLUG = 'nfgeoac-dashboard';

	/**
	 * Native WordPress page hook returned during menu registration.
	 *
	 * @since 1.0.0
	 * @var string|false
	 */
	private static $page_hook = '';

	/**
	 * Register the local product page before Ninja Forms initializes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function register() {
		SetupNotice::register();
		add_action( 'admin_menu', [ self::class, 'register_page' ], 30 );
		add_action( 'admin_enqueue_scripts', [ self::class, 'enqueue' ] );
		add_filter( 'plugin_action_links_' . NFGEOAC_BASENAME, [ self::class, 'add_action_link' ], 120 );
	}

	/**
	 * Place the overview with its Ninja Forms host, without an account SDK.
	 * The Settings fallback keeps setup/help available while the host is inactive.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function register_page() {
		$title = __( 'Address Autocomplete', 'address-autocomplete-for-ninja-forms' );
		if ( class_exists( '\Ninja_Forms' ) ) {
			self::$page_hook = add_submenu_page( 'ninja-forms', NFGEOAC_PLUGIN_NAME, $title, 'manage_options', self::PAGE_SLUG, [ self::class, 'render' ] );
		} else {
			self::$page_hook = add_options_page( NFGEOAC_PLUGIN_NAME, $title, 'manage_options', self::PAGE_SLUG, [ self::class, 'render' ] );
		}
	}

	/**
	 * Add the standalone plugin's overview, settings and documentation links.
	 *
	 * @since 1.0.0
	 * @param array $links Existing WordPress plugin action links.
	 * @return array
	 */
	public static function add_action_link( $links ) {
		return [
			'overview' => '<a href="' . esc_url( self::get_url() ) . '">' . esc_html__( 'Overview', 'address-autocomplete-for-ninja-forms' ) . '</a>',
			'settings' => '<a href="' . esc_url( admin_url( 'admin.php?page=nf-settings#ninja_forms_metabox_nfgeo_geolocation_settings' ) ) . '">' . esc_html__( 'Settings', 'address-autocomplete-for-ninja-forms' ) . '</a>',
			'docs'     => '<a href="' . esc_url( NFGEOAC_SITE_URL . '/docs/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Docs', 'address-autocomplete-for-ninja-forms' ) . '</a>',
		] + $links;
	}

	/**
	 * Build a local navigation link using a whitelisted, non-mutating section.
	 *
	 * @since 1.0.0
	 * @param string $section Requested section.
	 * @return string
	 */
	public static function get_url( $section = 'overview' ) {
		$section = \in_array( $section, [ 'overview', 'comparison', 'help', 'products' ], true ) ? $section : 'overview';
		return add_query_arg( 'section', $section, admin_url( 'admin.php?page=' . self::PAGE_SLUG ) );
	}

	/**
	 * Load scoped styles on the product page or its native settings subview.
	 * Native settings need only our card styles, not framework dashboard assets.
	 *
	 * @since 1.0.0
	 * @param string $hook_suffix WordPress screen hook.
	 * @return void
	 */
	public static function enqueue( $hook_suffix ) {
		$is_dashboard = ! empty( self::$page_hook ) && self::$page_hook === $hook_suffix;
		// Navigation context only; no submitted settings or mutation are read here.
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$page = isset( $_GET['page'] ) && \is_string( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
		$is_settings = 'nf-settings' === $page;

		if ( ! $is_dashboard && ! $is_settings ) {
			return;
		}

		wp_enqueue_style(
			'nfgeo-product-dashboard',
			NFGEOAC_URL . 'build/css/admin/nfgeo-product-dashboard.min.css',
			[],
			NFGEOAC_VERSION
		);
	}

	/**
	 * Render overview discovery links with no inputs, licensing or remote content.
	 * Link to the internal feature overview first and external pricing second.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function render_settings_link() {
		?>
		<div class="nfgeo-product-settings__content">
			<p>
				<?php esc_html_e( 'Explore maps, current-location detection, dynamic fields, directions, nearby locations, and more with Ninja Geolocation.', 'address-autocomplete-for-ninja-forms' ); ?>
			</p>
			<div class="nfgeo-product-settings__actions">
				<a class="button button-primary" href="<?php echo esc_url( self::get_url() ); ?>">
					<?php esc_html_e( 'Explore features', 'address-autocomplete-for-ninja-forms' ); ?>
				</a>
				<a class="button button-secondary" href="<?php echo esc_url( NFGEOAC_SITE_URL . '/pricing/' ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View packages', 'address-autocomplete-for-ninja-forms' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the selected server-side tab; malformed requests fall back to Overview.
	 * WordPress already gates the submenu. The second check also protects direct
	 * callback invocation. The template receives public metadata/URLs only.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// This GET value selects presentation only: there is no save/delete action.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$requested = isset( $_GET['section'] ) && \is_string( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : '';
		$tabs      = [
			'overview'   => __( 'Overview', 'address-autocomplete-for-ninja-forms' ),
			'comparison' => __( 'Compare Packages', 'address-autocomplete-for-ninja-forms' ),
			'help'       => __( 'Help', 'address-autocomplete-for-ninja-forms' ),
			'products'   => __( 'More Plugins', 'address-autocomplete-for-ninja-forms' ),
		];

		$version      = NFGEOAC_VERSION;
		$section      = isset( $tabs[ $requested ] ) ? $requested : 'overview';
		$settings_url = admin_url( 'admin.php?page=nf-settings#ninja_forms_metabox_nfgeo_geolocation_settings' );
		$pricing_url  = NFGEOAC_SITE_URL . '/pricing/';
		$docs_url     = NFGEOAC_SITE_URL . '/docs/';
		$demo_url     = 'https://demo.ninjageolocation.com/';
		$product_name = NFGEOAC_PLUGIN_NAME;
		$package_name = NFGEOAC_PACKAGE_LABEL;
		$features     = DashboardFeatures::get_features();
		$overview_features = DashboardFeatures::order_for_overview( $features );
		$included     = array_filter(
			$overview_features,
			static function ( $feature ) {
				return $feature['included'];
			}
		);
		$available    = array_filter(
			$overview_features,
			static function ( $feature ) {
				return ! $feature['included'];
			}
		);
		$packages     = DashboardFeatures::get_packages();

		$hero_title     = __( 'Less typing. Better addresses.', 'address-autocomplete-for-ninja-forms' );
		$help_step      = __( 'Add the Address field from the Geolocation group and configure its Address Autocomplete options.', 'address-autocomplete-for-ninja-forms' );
		$feature_groups = [
			'included'  => $included,
			'available' => $available,
		];

		require __DIR__ . '/views/dashboard.php';
	}

	/**
	 * Return bundled product descriptions without remote content or installers.
	 *
	 * @since 1.0.0
	 * @return array[] Public product names, descriptions and website URLs.
	 */
	public static function get_products() {
		return [
			[
				'name'        => 'Ninja Geolocation',
				'url'         => 'https://ninjageolocation.com/',
				'description' => __( 'Maps, geocoding, dynamic fields, directions, nearby locations and more for Ninja Forms.', 'address-autocomplete-for-ninja-forms' ),
			],
			[
				'name'        => 'GEO my WP',
				'url'         => 'https://geomywp.com/',
				'description' => __( 'Build location-based searches and directories for your WordPress site.', 'address-autocomplete-for-ninja-forms' ),
			],
			[
				'name'        => 'Formidable Geolocation',
				'url'         => 'https://formidablegeolocation.com/',
				'description' => __( 'Add geolocation tools and connected location fields to Formidable Forms.', 'address-autocomplete-for-ninja-forms' ),
			],
			[
				'name'        => 'Gravity Geolocation',
				'url'         => 'https://gravitygeolocation.com/',
				'description' => __( 'Maps, geocoding, dynamic fields, directions, nearby locations and more for Gravity Forms.', 'address-autocomplete-for-ninja-forms' ),
			],
			[
				'name'        => 'Gravity Search',
				'url'         => 'https://gravitygeolocation.com/solutions/gravity-search/',
				'description' => __( 'Turn Gravity Forms entries into searchable directories, listings and customer portals—with field filters, proximity search, maps and flexible result layouts.', 'address-autocomplete-for-ninja-forms' ),
			],
			[
				'name'        => 'WPForms Geolocation',
				'url'         => 'https://wpgeoforms.com/',
				'description' => __( 'Add address autocomplete, maps, coordinates and directions to WPForms.', 'address-autocomplete-for-ninja-forms' ),
			],
			[
				'name'        => 'WP Job Manager Geolocation',
				'url'         => 'https://jobmanagergeolocation.com/',
				'description' => __( 'Add proximity searches and interactive maps to your WP Job Manager job board.', 'address-autocomplete-for-ninja-forms' ),
			],
		];
	}
}
