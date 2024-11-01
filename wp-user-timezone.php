<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://in.linkedin.com/in/ravikatha
 * @since             1.0.0
 * @package           WP_User_Timezone
 *
 * @wordpress-plugin
 * Plugin Name:       WP User Timezone
 * Plugin URI:        https://wordpress.org/plugins/wp-user-timezone/
 * Description:       Enables the date/time on the wordpress front-end to be displayed in user's timezone
 * Version:           1.0.2
 * Author:            Ravi Kiran Katha
 * Author URI:        https://in.linkedin.com/in/ravikatha
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-user-timezone
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-user-timezone-activator.php
 */
function activate_wp_user_timezone() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-user-timezone-activator.php';
	Wp_User_Timezone_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-user-timezone-deactivator.php
 */
function deactivate_wp_user_timezone() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-user-timezone-deactivator.php';
	Wp_User_Timezone_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_user_timezone' );
register_deactivation_hook( __FILE__, 'deactivate_wp_user_timezone' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-user-timezone.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_user_timezone() {

	$plugin = new Wp_User_Timezone();
	$plugin->run();

}
run_wp_user_timezone();
