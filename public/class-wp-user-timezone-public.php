<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link https://in.linkedin.com/in/ravikatha
 * @since 1.0.0
 *       
 * @package Wp_User_Timezone
 * @subpackage Wp_User_Timezone/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package Wp_User_Timezone
 * @subpackage Wp_User_Timezone/public
 * @author Ravi Kiran Katha <info@dotcastle.com>
 */
class Wp_User_Timezone_Public {
	
	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	
	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;
	
	/**
	 * Options
	 */
	private $options_wrapper;
	
	/**
	 * Site timezone_string
	 */
	private $site_timezone_string;
	
	/**
	 * Site gmt_offset
	 */
	private $site_gmt_offset;
	
	/**
	 * Whether to skip get_post_metadata processing
	 */
	private $skip_post_metadata_processing;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name
	 *        	The name of the plugin.
	 * @param string $version
	 *        	The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $site_timezone_string, $site_gmt_offset ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->site_timezone_string = $site_timezone_string;
		$this->site_gmt_offset = $site_gmt_offset;
		$this->options_wrapper = new WP_User_Timezone_Options( $this->plugin_name );
		$this->skip_post_metadata_processing = false;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
	/**
	 * This function is provided for demonstration purposes only.
	 *
	 * An instance of this class should be passed to the run() function
	 * defined in Wp_User_Timezone_Loader as all of the hooks are defined
	 * in that particular class.
	 *
	 * The Wp_User_Timezone_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 */
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_User_Timezone_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_User_Timezone_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		// Not for admin
		if ( is_admin() ) {
			return;
		}
		
		// Cookie is required for all options
		wp_enqueue_script( 'jquery-cookie', plugin_dir_url( __FILE__ ) . 'js/jquery.cookie.min.js', array (
			'jquery' 
		), '1.4.1', false );
		
		// JSTZ
		wp_enqueue_script( 'jstz', plugin_dir_url( __FILE__ ) . 'js/jstz.min.js', array (), '1.0.6', false );
		
		// Plugin
		wp_enqueue_script( $this->plugin_name . '-plugin', plugin_dir_url( __FILE__ ) . 'js/wp-user-timezone-public.js', array ( 
			'jquery', 'jstz', 'jquery-cookie'
		), $this->version, false );
	}

	/**
	 * wp_head hook
	 */
	public function head() {
		// Not for admin
		if ( is_admin() ) {
			return;
		}
		
		$options = $this->options_wrapper->get_options_json();
?>
		<script>
			window['WP_USER_TIMEZONE_OPTIONS'] = '<?php echo str_replace( '\'', '\\\'', $options ); ?>';
		</script>
<?php
	}

	/**
	 * timezone_string option override
	 */
	public function on_site_timezone_string() {
		return $this->site_timezone_string;
	}
	
	/**
	 * gmt_offset option override
	 */
	public function on_site_gmt_offset() {
		return $this->site_gmt_offset;
	}
	
	/**
	 * timezone_string option override
	 */
	public function on_timezone_string() {
		// Not for admin
		if ( is_admin() ) {
			return false;
		}
		
		$tz_id = wp_cache_get( 'wp_user_timezone_id' );
		if ( $tz_id === false ) {
			$tz_id = $this->options_wrapper->detect_user_timezone_id();
			wp_cache_set( 'wp_user_timezone_id', $tz_id );
		}
		return is_null( $tz_id ) ? false : $tz_id;
	}

	/**
	 * gmt_offset option override
	 */
	public function on_gmt_offset() {
		// Not for admin
		if ( is_admin() ) {
			return false;
		}
		
		$gmt_offset = wp_cache_get( 'wp_user_timezone_gmt_offset' );
		if ( $gmt_offset === false ) {
			$gmt_offset = wp_timezone_override_offset();
			$gmt_offset = ( $gmt_offset === false ) ? null : $gmt_offset;
			wp_cache_set( 'wp_user_timezone_gmt_offset', $gmt_offset );
		}
		return is_null( $gmt_offset ) ? false : floatval( $gmt_offset );
	}

	/**
	 * Time zone id display
	 */
	public function user_timezone_id_shortcode( $atts ) {
		// Not for admin
		if ( is_admin() ) {
			return null;
		}
		
		$tz_id = $this->on_timezone_string();
		if ( $tz_id === false ) {
			return null;
		}
		return $tz_id;
	}

	/**
	 * Time zone offset display
	 */
	public function user_timezone_offset_shortcode( $atts ) {
		// Not for admin
		if ( is_admin() ) {
			return null;
		}
		
		$tz_offset = $this->on_gmt_offset();
		if ( $tz_offset === false ) {
			return null;
		}
		return floatval( $tz_offset );
	}

	/**
	 * get_post_metadata filter
	 */
	public function on_get_post_metadata( $post, $object_id, $meta_key, $single ) {
		// Skip
		if ( $this->skip_post_metadata_processing || is_admin() ) {
			return null;
		}
		
		// Not enabled
		$options = $this->options_wrapper->get_options();
		if ( !$options[ WP_User_Timezone_Options::EVENTON_HOOKS_OPTION ] ) {
			return null;
		}
		
		// Timezone
		$tz_id = $this->on_timezone_string();
		if ( $tz_id === false ) {
			return null;
		}
		
		// Get the actual post
		$this->skip_post_metadata_processing = true;
		$post = get_metadata( 'post', $object_id, '', false );
		$this->skip_post_metadata_processing = false;
		
		// Check if valid
		if ( $post === false ) {
			return null;
		}
		
		// Check if this is an event post
		if ( !isset( $post[ 'evcal_srow' ] ) || !is_array( $post[ 'evcal_srow' ] ) ) {
			return null;
		}
		
		// Remove timezone
		unset( $post[ 'evo_event_timezone' ] );
		unset( $post[ 'evcal_allday' ] );
		
		// Adjustments
		$site_gmt_offset_seconds = $this->site_gmt_offset * 60 * 60;
		$user_tz_offset = $this->on_gmt_offset() * 60 * 60;
		$adjustment = $user_tz_offset - $site_gmt_offset_seconds;
		$event_year = null;
		
		// Update
		foreach ( $post[ 'evcal_srow' ] as &$item ) {
			$item = intval( $item + $adjustment );
			if ( is_null( $event_year ) ) {
				$event_year = new DateTime( 'now', new DateTimeZone( $tz_id ) );
				$event_year->setTimestamp( $item );
				$event_year = intval( $event_year->format( 'Y' ) );
			}
		}
		foreach ( $post[ 'evcal_erow' ] as &$item ) {
			$item = intval( $item + $adjustment );
		}
		
		// Year
		if ( !is_null( $event_year ) ) {
			$post[ 'event_year' ] = array ( 
				$event_year 
			);
		}
		
		// Return
		if ( !$meta_key ) {
			return ( $single && is_array( $post ) ) ? array ( 
				$post 
			) : $post;
		}
		if ( isset( $post[ $meta_key ] ) ) {
			if ( $single ) {
				$ret = maybe_unserialize( $post[ $meta_key ][ 0 ] );
				return is_array( $ret ) ? array ( 
					$ret 
				) : $ret;
			} else {
				return array_map( 'maybe_unserialize', $post[ $meta_key ] );
			}
		}
		return $single ? '' : array ();
	}
}
