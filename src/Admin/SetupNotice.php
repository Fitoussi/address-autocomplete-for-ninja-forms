<?php
/**
 * One-time setup guidance with site-wide, persistent dismissal.
 *
 * @package NinjaGeolocationAutocomplete\Admin
 * @since 1.0.0
 */

namespace NinjaGeolocationAutocomplete\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Guide administrators without activation redirects or remote requests.
 *
 * @since 1.0.0
 */
final class SetupNotice {

	/**
	 * Site-local state, separate from the host's shared plugin preferences.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private const OPTION = 'nfgeoac_setup_notice';

	/**
	 * Nonce-protected dismissal action.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private const DISMISS_ACTION = 'nfgeoac_dismiss_setup_notice';

	/**
	 * Record first activation without resetting dismissed or completed setup.
	 *
	 * Network activation does not initialize individual sites' preferences.
	 *
	 * @since 1.0.0
	 * @param bool $network_wide Whether the plugin is activated for a network.
	 * @return void
	 */
	public static function activate( $network_wide = false ) {
		if ( $network_wide || defined( 'NFGEO_VERSION' ) ) {
			return;
		}
		add_option( self::OPTION, 'pending', '', false );
	}

	/**
	 * Register guidance and its authenticated dismissal handler.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function register() {
		add_action( 'admin_notices', [ self::class, 'render' ] );
		add_action( 'admin_post_' . self::DISMISS_ACTION, [ self::class, 'dismiss' ] );
	}

	/**
	 * Show setup guidance across site admin screens until dismissed or configured.
	 *
	 * Missing hosts and inactive free distributions have their own notices.
	 * Existing installations are not enrolled merely by updating plugin files.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function render() {
		if ( ! is_admin() || is_network_admin() || ! current_user_can( 'manage_options' ) || defined( 'NFGEO_VERSION' ) ) {
			return;
		}
		if ( ! ( class_exists( '\\Ninja_Forms' ) && version_compare( \Ninja_Forms::VERSION, NFGEOAC_MIN_HOST_VERSION, '>=' ) ) || 'pending' !== get_option( self::OPTION ) ) {
			return;
		}
		if ( self::has_api_key() ) {
			update_option( self::OPTION, 'configured', false );
			return;
		}
		?>
		<div class="notice notice-info nfgeoac-setup-notice">
			<p>
				<strong><?php
					echo esc_html(
						sprintf(
							/* translators: %s: Plugin name. */
							__( '%s is ready to configure.', 'address-autocomplete-for-ninja-forms' ),
							NFGEOAC_PLUGIN_NAME
						)
					);
				?></strong><br>
				<?php esc_html_e( 'Add your Google API key, then enable autocomplete in your form\'s field options.', 'address-autocomplete-for-ninja-forms' ); ?>
			</p>
			<div style="margin-bottom: 12px;">
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=nf-settings#ninja_forms_metabox_nfgeo_geolocation_settings' ) ); ?>"><?php esc_html_e( 'Set up autocomplete', 'address-autocomplete-for-ninja-forms' ); ?></a>
				<a class="button button-secondary" href="<?php echo esc_url( Dashboard::get_url() ); ?>"><?php esc_html_e( 'View plugin overview', 'address-autocomplete-for-ninja-forms' ); ?></a>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: inline-block; margin-left: 12px;">
					<input type="hidden" name="action" value="<?php echo esc_attr( self::DISMISS_ACTION ); ?>">
					<?php wp_nonce_field( self::DISMISS_ACTION ); ?>
					<button class="button-link" type="submit"><?php esc_html_e( 'Dismiss', 'address-autocomplete-for-ninja-forms' ); ?></button>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Detect existing configuration without reading or exporting paid settings.
	 *
	 * @since 1.0.0
	 * @return bool Whether a nonempty scalar browser key is configured.
	 */
	private static function has_api_key() {
		$key = \Ninja_Forms()->get_setting( 'nfgeo_google_maps_browser_api_key' );
		return is_scalar( $key ) && '' !== trim( (string) $key );
	}

	/**
	 * Permanently dismiss for this site and return to the same admin page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function dismiss() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to dismiss this notice.', 'address-autocomplete-for-ninja-forms' ), '', [ 'response' => 403 ] );
		}
		check_admin_referer( self::DISMISS_ACTION );
		update_option( self::OPTION, 'dismissed', false );
		$referer = wp_get_referer();
		$target  = is_string( $referer ) && 0 === strpos( $referer, admin_url() ) ? $referer : admin_url();
		wp_safe_redirect( $target );
		exit;
	}
}
