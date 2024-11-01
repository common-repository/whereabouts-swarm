<?php

defined( 'ABSPATH' ) OR exit;
/*
Plugin Name: Whereabouts: Swarm
Plugin URI: https://whereabouts.haptiq.dev/
Description: Display your current location, automatically updated by your latest Swarm check-in.
Version: 0.5.0
Author: Florian Ziegler
Author URI: https://florianziegler.de/
License: GPLv2 or later
Text Domain: whereabouts-swarm
*/


/**
 * Plugin Setup
 *
 * @since 0.1.0
 */
if ( ! function_exists( 'whereabouts_swarm_setup' ) ) {

	function whereabouts_swarm_setup() {

		// Define include path for this plugin
		define( 'WHEREABOUTS_SWARM_PATH', plugin_dir_path( __FILE__ ) );

		// Define url for this plugin
		define( 'WHEREABOUTS_SWARM_URL', plugin_dir_url( __FILE__ ) );

		// Define version
		define( 'WHEREABOUTS_SWARM_VERSION', '0.4.0' );

		// Get location
		require WHEREABOUTS_SWARM_PATH . '/includes/get-location.php';

		// Display location
		require WHEREABOUTS_SWARM_PATH . '/includes/display-location.php';

		// Widget
		require WHEREABOUTS_SWARM_PATH . '/includes/widget.php';

	}

}

add_action( 'after_setup_theme', 'whereabouts_swarm_setup' );


/**
 * Load language files
 *
 * @since 0.1.0
 */

load_plugin_textdomain( 'whereabouts-swarm', false, basename( dirname( __FILE__ ) ) . '/languages' );


/**
 * Enqueue styles and scripts
 *
 * @since 0.1.0
 */

function whereabouts_swarm_init() {
	if ( is_admin() ) {
		wp_enqueue_style( 'whereabouts-swarm-admin', WHEREABOUTS_SWARM_URL . '/css/whereabouts-swarm-admin.css', array( 'dashicons'), WHEREABOUTS_SWARM_VERSION );

		wp_enqueue_script( 'whereabouts-swarm', WHEREABOUTS_SWARM_URL . 'js/whereabouts-swarm.min.js', array( 'jquery' ), WHEREABOUTS_SWARM_VERSION, true );
	}
}

add_action( 'init', 'whereabouts_swarm_init' );


/**
 * Register settings menu for Whereabouts Swarm
 *
 * @since 0.1.0
 */
function whereabouts_swarm_menu() {
	add_options_page(
		'Whereabouts: Swarm',
		'Whereabouts: Swarm',
		'manage_options',
		'whereabouts_swarm',
		'whereabouts_swarm_load_menu_page'
	);
}

add_action( 'admin_menu', 'whereabouts_swarm_menu' );


function whereabouts_swarm_load_menu_page() {

	// De-Authentication
	if ( isset( $_GET['deauthenticate'] ) AND $_GET['deauthenticate'] == 'true' ) {
		delete_option( 'whereabouts_swarm_auth_code' );
	}

?>
    <div class="wrap">
		<h1><?php _e( 'Whereabouts: Swarm settings', 'whereabouts_swarm' ); ?></h1>
		<form class="whereabouts-swarm-form" action="options.php" method="post">
			<?php
				$auth_code = get_option( 'whereabouts_swarm_auth_code' );
			?>

			<fieldset id="swarm_authentification">
				<legend><?php _e( 'Authentification', 'whereabouts-swarm' ); ?></legend>
			<?php
				if ( ( isset( $auth_code ) AND ! empty( $auth_code ) ) OR ( isset( $_GET['swarm_auth_code'] ) AND ! empty( $_GET['swarm_auth_code'] ) ) ) {

					$de_auth_url = get_admin_url() . 'options-general.php?page=whereabouts_swarm&deauthenticate=true';
					?>
					<p class="authenticated"><span><?php _e( 'Authenticated with', 'whereabouts-swarm' ); ?></span> <img src="<?php echo WHEREABOUTS_SWARM_URL; ?>/images/swarm-logo.png" alt="" /></p>
					<p><a class="button button-secondary" href="<?php echo $de_auth_url; ?>"><?php _e( 'De-Authenticate', 'whereabouts-swarm' ); ?></a></p>
					<p><?php _e( '<strong>Notice:</strong> This will remove the authentication token from the database. Whereabouts will stop fetching your current location from Swarm. If you want to completely revoke access, remove the Whereabouts app from your <a href="https://foursquare.com/settings/connections">Foursquare account</a>.', 'whereabouts-swarm' ); ?></p>
					<?php
				}
				else { ?>

				<?php
					$admin_url = get_admin_url() . 'options-general.php?page=whereabouts_swarm';

					$redirect_url = urlencode( 'https://whereabouts.haptiq.dev/?whereabouts_swarm_redirect=' . $admin_url );
					?>
					<p><?php _e( 'Before you can use this plugin, you have to authenticate <strong>Whereabouts Swarm</strong>:', 'whereabouts-swarm' ); ?></p>
					<a class="button button-primary" href="https://foursquare.com/oauth2/authenticate?client_id=NQYO5LOJSC22R042DBOU0YG53ZLNSTWOGWN0P3DWBYRM231N&response_type=code&redirect_uri=<?php echo $redirect_url; ?>"><?php _e( 'Authenticate with Swarm', 'whereabouts-swarm' ); ?></a>
					<?php
				}
			?>
			</fieldset>

			<fieldset>
				<?php
					$fetch_url = get_admin_url() . 'options-general.php?page=whereabouts_swarm&fetch_location=true';

					if ( isset( $_GET['fetch_location'] ) AND $_GET['fetch_location'] == true ) {
						echo whereabouts_swarm_fetch_location_go();
					}
				?>
				<legend><?php _e( 'Fetch Location', 'whereabouts-swarm' ); ?></legend>
				<p><?php _e( 'By default WordPress will fetch the location from Swarm every hour, automatically. If you need it to update right away, you can get you latest check-in, manually:', 'whereabouts-swarm' ); ?></p>
				<p><a href="<?php echo $fetch_url; ?>" class="button"><?php _e( 'Get Location Now', 'whereabouts-swarm'); ?></a></p>
			</fieldset>

			<?php

				if ( isset( $_GET['swarm_auth_code'] ) AND ! empty( $_GET['swarm_auth_code'] ) ) {
					// Save code in the db
					update_option( 'whereabouts_swarm_auth_code', $_GET['swarm_auth_code'] );
				}

				settings_fields( 'whereabouts_swarm_settings' );
				$options = get_option( 'whereabouts_swarm_settings' );

				$show_venue = ( $options['show_venue'] ) ?: 'on';
				$show_city = ( $options['show_city'] ) ?: 'on';
				$show_country = ( $options['show_country'] ) ?: 'on';
				$show_time_zone = ( $options['show_time_zone'] ) ?: 'on';

				$show_venue_icon = ( $options['show_venue_icon'] ) ?: 'off';
				$link_venue = ( $options['link_venue'] ) ?: 'on';
				$link_venue_website = ( $options['link_venue_website'] ) ?: 'off';
			?>

			<fieldset id="display_settings">
				<legend><?php _e( 'Display Settings', 'whereabouts-swarm' ); ?></legend>
				<h4><?php _e( 'Show:', 'picu' ); ?></h4>
				<p>
					<span class="nowrap"><input type="checkbox" name="whereabouts_swarm_settings[show_venue]" id="show_venue"<?php checked( 'on', $show_venue, true ); ?> /> <label class="after" for="show_venue"><?php _e( 'Venue', 'whereabouts-swarm' ); ?></label></span>
					<span class="nowrap"><input type="checkbox" name="whereabouts_swarm_settings[show_city]" id="show_city"<?php checked( 'on', $show_city, true ); ?> /> <label class="after" for="show_city"><?php _e( 'City', 'whereabouts-swarm' ); ?></label></span>
					<span class="nowrap"><input type="checkbox" name="whereabouts_swarm_settings[show_country]" id="show_country"<?php checked( 'on', $show_country, true ); ?> /> <label class="after" for="show_country"><?php _e( 'Country', 'whereabouts-swarm' ); ?></label></span>
					<span class="nowrap"><input type="checkbox" name="whereabouts_swarm_settings[show_time_zone]" id="show_time_zone"<?php checked( 'on', $show_time_zone, true ); ?> /> <label class="after" for="show_time_zone"><?php _e( 'Time Zone', 'whereabouts-swarm' ); ?></label></span>
				</p>
				<p><input type="checkbox" name="whereabouts_swarm_settings[show_venue_icon]" id="show_venue_icon"<?php checked( 'on', $show_venue_icon, true ); ?><?php if ( $show_venue == 'off' ) { echo ' disabled="disabled"'; } ?> /> <label class="after" for="show_venue_icon"><?php _e( 'Show venue category icon<br /><span class="description">Shows a category icon provided by foursquare, eg. "hotel" or "restaurant".</span>', 'whereabouts-swarm' ); ?></label></p>
				<p><input type="checkbox" name="whereabouts_swarm_settings[link_venue]" id="link_venue"<?php checked( 'on', $link_venue, true ); ?><?php if ( $show_venue == 'off' ) { echo ' disabled="disabled"'; } ?> /> <label class="after" for="link_venue"><?php _e( 'Link venue to its foursquare page<br /><span class="description">This will link your last check-in to the corresponding page on foursquare.com.</span>', 'whereabouts-swarm' ); ?></label></p>
				<p><input type="checkbox" name="whereabouts_swarm_settings[link_venue_website]" id="link_venue_website"<?php checked( 'on', $link_venue_website, true ); ?><?php if ( $show_venue == 'off' ) { echo ' disabled="disabled"'; } ?> /> <label class="after" for="link_venue_website"><?php _e( 'Link venue to its own website<br /><span class="description">This will link your last check-in to the venue\'s website, if a URL is listed on foursquare.</span>', 'whereabouts-swarm' ); ?></label></p>
				<?php submit_button( __( 'Save Settings', 'whereabouts-swarm' ) ); ?>
			</fieldset>
		</form>
	</div>
<?php
}


/**
 * Register settings
 *
 * @since 0.7.0
 */
function whereabouts_swarm_register_settings() {
	register_setting( 'whereabouts_swarm_settings', 'whereabouts_swarm_settings', 'whereabouts_swarm_settings_validate' );
}

add_action( 'admin_init', 'whereabouts_swarm_register_settings' );


/**
 * Sanizize / Validate settings
 *
 * @since 0.7.0
 */
function whereabouts_swarm_settings_validate( $args ) {

	// Sanitize show_venue
	if ( ! isset( $args['show_venue'] ) OR 'on' != $args['show_venue'] ) {
		$args['show_venue'] = 'off';
	}

	// Sanitize show_city
	if ( ! isset( $args['show_city'] ) OR 'on' != $args['show_city'] ) {
		$args['show_city'] = 'off';
	}

	// Sanitize show_country
	if ( ! isset( $args['show_country'] ) OR 'on' != $args['show_country'] ) {
		$args['show_country'] = 'off';
	}

	// Sanitize show_time_zone
	if ( ! isset( $args['show_time_zone'] ) OR 'on' != $args['show_time_zone'] ) {
		$args['show_time_zone'] = 'off';
	}

	// Sanitize show_venue_icon
	if ( ! isset( $args['show_venue_icon'] ) OR 'on' != $args['show_venue_icon'] ) {
		$args['show_venue_icon'] = 'off';
	}

	// Sanitize link_venue
	if ( ! isset( $args['link_venue'] ) OR 'on' != $args['link_venue'] ) {
		$args['link_venue'] = 'off';
	}

	// Sanitize link_venue_website
	if ( ! isset( $args['link_venue_website'] ) OR 'on' != $args['link_venue_website'] ) {
		$args['link_venue_website'] = 'off';
	}

	return $args;
}


/**
 * Delete options when plugin is deleted
 *
 * @since 0.1.0
 */
function uninstall_whereabouts_swarm() {
	delete_option( 'whereabouts_swarm_settings' );
	delete_option( 'whereabouts_swarm_auth_code' );
	delete_option( 'whereabouts_swarm_current_location' );
	wp_clear_scheduled_hook( 'whereabouts_swarm_fetch_location' );
}

register_uninstall_hook( __FILE__, 'uninstall_whereabouts_swarm' );