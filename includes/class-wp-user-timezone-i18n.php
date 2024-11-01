<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://in.linkedin.com/in/ravikatha
 * @since      1.0.0
 *
 * @package    Wp_User_Timezone
 * @subpackage Wp_User_Timezone/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_User_Timezone
 * @subpackage Wp_User_Timezone/includes
 * @author     Ravi Kiran Katha <info@dotcastle.com>
 */
class Wp_User_Timezone_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-user-timezone',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
