<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link https://in.linkedin.com/in/ravikatha
 * @since 1.0.0
 *       
 * @package Wp_User_Timezone
 * @subpackage Wp_User_Timezone/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since 1.0.0
 * @package Wp_User_Timezone
 * @subpackage Wp_User_Timezone/includes
 * @author Ravi Kiran Katha <info@dotcastle.com>
 */
class Wp_User_Timezone {
	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var Wp_User_Timezone_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	
	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'wp-user-timezone';
		$this->version = '1.0.0';
		
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_User_Timezone_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_User_Timezone_i18n. Defines internationalization functionality.
	 * - Wp_User_Timezone_Admin. Defines all hooks for the admin area.
	 * - Wp_User_Timezone_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function load_dependencies() {
		
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-user-timezone-loader.php';
		
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-user-timezone-i18n.php';
		
		/**
		 * Wrapper for options object
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-user-timezone-options.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-user-timezone-admin.php';
		
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-user-timezone-public.php';
		
		$this->loader = new Wp_User_Timezone_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_User_Timezone_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function set_locale() {
		$plugin_i18n = new Wp_User_Timezone_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Wp_User_Timezone_Admin(
			$this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_options' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_public_hooks() {
		$plugin_public = new Wp_User_Timezone_Public(
			$this->get_plugin_name(), $this->get_version(),
			get_option ( 'timezone_string' ),
			get_option ( 'gmt_offset' ) );
		
		// Wordpress Actions
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'head' );
		
		// Wordpress Filters
		$this->loader->add_filter( 'pre_option_timezone_string', $plugin_public, 'on_timezone_string' );
		$this->loader->add_filter( 'pre_option_gmt_offset', $plugin_public, 'on_gmt_offset' );
		$this->loader->add_filter( 'pre_option_site_timezone_string', $plugin_public, 'on_site_timezone_string' );
		$this->loader->add_filter( 'pre_option_site_gmt_offset', $plugin_public, 'on_site_gmt_offset' );
		
		// EventON Filters
		$this->loader->add_filter( 'get_post_metadata', $plugin_public, 'on_get_post_metadata', 10, 4 );
		
		// Plugin Shortcodes
		add_shortcode( 'wp_user_timezone_id', array ( 
			$plugin_public, 'user_timezone_id_shortcode' 
		) );
		add_shortcode( 'wp_user_timezone_offset', array ( 
			$plugin_public, 'user_timezone_offset_shortcode' 
		) );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since 1.0.0
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since 1.0.0
	 * @return Wp_User_Timezone_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since 1.0.0
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
