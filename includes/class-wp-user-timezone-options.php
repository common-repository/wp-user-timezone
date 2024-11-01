<?php

/**
 * Options wrapper
 */
class WP_User_Timezone_Options {
	const DEFAULT_COOKIE_NAME = '_WPUTZID';
	const COOKIE_NAME_OPTION = 'cookieName';
	const COOKIE_EXPIRY_DAYS_OPTION = 'cookieExpiryDays';
	const GOOGLE_TIMEZONE_API_OPTION = 'googleTimezoneApi';
	const GOOGLE_API_KEY_OPTION = 'googleApiKey';
	const EVENTON_HOOKS_OPTION = 'eventOnHooks';
	
	/**
	 * Plugin name
	 */
	protected $plugin_name = '';
	
	/**
	 * Constructor
	 */
	public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;
	}
	
	/**
	 * Registration
	 */
	public function register() {
		// Setting
		register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );
	}
	
	/**
	 * Retrieves the options assoc array
	 */
	public function get_options() {
		$options = get_option( $this->plugin_name );
		$options = ( !isset( $options ) || is_null( $options ) || ( gettype( $options ) !== 'array' ) ) ? array () : $options;
		
		static::adjust_options( $options );
		return $options;
	}

	/**
	 * Adjusts options values
	 */
	private static function adjust_options( &$options ) {
		$options[ static::COOKIE_NAME_OPTION ] = ( isset( $options[ static::COOKIE_NAME_OPTION ] ) && !is_null( $options[ static::COOKIE_NAME_OPTION ] ) && ( gettype( $options[ static::COOKIE_NAME_OPTION ] ) === 'string' ) ) ? trim( $options[ static::COOKIE_NAME_OPTION ] ) : static::DEFAULT_COOKIE_NAME;
		$options[ static::COOKIE_NAME_OPTION ] = !empty ( $options[ static::COOKIE_NAME_OPTION ] ) ? $options[ static::COOKIE_NAME_OPTION ] : static::DEFAULT_COOKIE_NAME;
		$options[ static::COOKIE_EXPIRY_DAYS_OPTION ] = ( isset( $options[ static::COOKIE_EXPIRY_DAYS_OPTION ] ) && !is_null( $options[ static::COOKIE_EXPIRY_DAYS_OPTION ] ) ) ? intval( $options[ static::COOKIE_EXPIRY_DAYS_OPTION ] ) : 0;
		$options[ static::COOKIE_EXPIRY_DAYS_OPTION ] = ( $options[ static::COOKIE_EXPIRY_DAYS_OPTION ] < 0 ) ? 0 : $options[ static::COOKIE_EXPIRY_DAYS_OPTION ];
		$options[ static::GOOGLE_TIMEZONE_API_OPTION ] = ( isset( $options[ static::GOOGLE_TIMEZONE_API_OPTION ] ) && !is_null( $options[ static::GOOGLE_TIMEZONE_API_OPTION ] ) ) ? boolval( $options[ static::GOOGLE_TIMEZONE_API_OPTION ] ) : false;
		$options[ static::GOOGLE_API_KEY_OPTION ] = ( isset( $options[ static::GOOGLE_API_KEY_OPTION ] ) && !is_null( $options[ static::GOOGLE_API_KEY_OPTION ] ) && ( gettype( $options[ static::GOOGLE_API_KEY_OPTION ] ) === 'string' ) ) ? trim( $options[ static::GOOGLE_API_KEY_OPTION ] ) : '';
		$options[ static::EVENTON_HOOKS_OPTION ] = ( isset( $options[ static::EVENTON_HOOKS_OPTION ] ) && !is_null( $options[ static::EVENTON_HOOKS_OPTION ] ) ) ? boolval( $options[ static::EVENTON_HOOKS_OPTION ] ) : false;
	}

	/**
	 * Returns the options object as JSON string
	 */
	public function get_options_json() {
		$options = static::get_options();
		return json_encode( $options );
	}

	/**
	 * Validates user form inputs
	 */
	public function validate( $input ) {
		$valid = array ();
		
		// Cookie Name
		$cookie_name = static::DEFAULT_COOKIE_NAME;
		if(isset($input[static::COOKIE_NAME_OPTION])) {
			$cookie_name = $input[static::COOKIE_NAME_OPTION];
			if(gettype($cookie_name) === 'string') {
				$cookie_name = trim($cookie_name);
			} else {
				$cookie_name = static::DEFAULT_COOKIE_NAME;
			}
		}
		if(!is_null($cookie_name)) {
			$valid[static::COOKIE_NAME_OPTION] = $cookie_name;
		} else {
			$valid[static::COOKIE_NAME_OPTION] = $input[static::COOKIE_NAME_OPTION];
		}
		
		// Cookie Expiry Days
		$cookie_expiry_days = 0;
		if(isset($input[static::COOKIE_EXPIRY_DAYS_OPTION])) {
			$cookie_expiry_days_str = trim(strval($input[static::COOKIE_EXPIRY_DAYS_OPTION]));
			if($cookie_expiry_days_str !== '') {
				$cookie_expiry_days = intval($cookie_expiry_days_str);
				if(strval($cookie_expiry_days) !== $cookie_expiry_days_str) {
					add_settings_error( $this->plugin_name, '', __( 'The cookie expiry days should be a positive integer!', $this->plugin_name ), 'error' );
					$cookie_expiry_days = null;
				} else if($cookie_expiry_days < 0) {
					add_settings_error( $this->plugin_name, '', __( 'The cookie expiry days should be a positive integer!', $this->plugin_name ), 'error' );
					$cookie_expiry_days = null;
				}
			}
		}
		if(!is_null($cookie_expiry_days)) {
			$valid[static::COOKIE_EXPIRY_DAYS_OPTION] = $cookie_expiry_days;
		} else {
			$valid[static::COOKIE_EXPIRY_DAYS_OPTION] = $input[static::COOKIE_EXPIRY_DAYS_OPTION];
		}
		
		// Timezone API
		$geo_location = false;
		if(isset($input[static::GOOGLE_TIMEZONE_API_OPTION])) {
			$geo_location = boolval($input[static::GOOGLE_TIMEZONE_API_OPTION]);
		}
		if(!is_null($geo_location)) {
			$valid[static::GOOGLE_TIMEZONE_API_OPTION] = $geo_location;
		} else {
			$valid[static::GOOGLE_TIMEZONE_API_OPTION] = $input[static::GOOGLE_TIMEZONE_API_OPTION];
		}

		// Google Api Key
		$google_api_key = '';
		if(isset($input[static::GOOGLE_API_KEY_OPTION])) {
			$google_api_key = $input[static::GOOGLE_API_KEY_OPTION];
			if(gettype($google_api_key) === 'string') {
				$google_api_key = trim($google_api_key);
			} else {
				$google_api_key = '';
			}
		}
		if(!is_null($google_api_key)) {
			$valid[static::GOOGLE_API_KEY_OPTION] = $google_api_key;
		} else {
			$valid[static::GOOGLE_API_KEY_OPTION] = $input[static::GOOGLE_API_KEY_OPTION];
		}
		if ( $valid[static::GOOGLE_TIMEZONE_API_OPTION] && empty( $valid[static::GOOGLE_API_KEY_OPTION] ) ) {
			add_settings_error( $this->plugin_name, '', __( 'The Google API Key is required when Geolocation API is enabled!', $this->plugin_name ), 'error' );
		}
		
		// EventON Hooks
		$event_on_hooks = false;
		if(isset($input[static::EVENTON_HOOKS_OPTION])) {
			$event_on_hooks = boolval($input[static::EVENTON_HOOKS_OPTION]);
		}
		if(!is_null($event_on_hooks)) {
			$valid[static::EVENTON_HOOKS_OPTION] = $event_on_hooks;
		} else {
			$valid[static::EVENTON_HOOKS_OPTION] = $input[static::EVENTON_HOOKS_OPTION];
		}
		
		// Success
		return $valid;
	}
	
	/**
	 * Retrieves the user timezone id
	 */
	public function detect_user_timezone_id() {
		$options = $this->get_options();
		$tz_id = $_COOKIE[ $options[ static::COOKIE_NAME_OPTION ] ];
		
		// Check if we have a timezone id
		if ( isset( $tz_id ) && !is_null( $tz_id ) && ( gettype( $tz_id ) === 'string' ) ) {
			
			// Trim
			$tz_id = trim( $tz_id );
			
			// Non-empty
			if ( !empty( $tz_id ) ) {
				
				// Filter
				$filtered_tz_id = apply_filters( 'wp_user_timezone_id', $tz_id );
				
				// Check if we have a filtered id
				if ( $filtered_tz_id !== false ) {
					$tz_id = $filtered_tz_id;
				}
				
				// Check if we have a timezone id
				if ( isset( $tz_id ) && !is_null( $tz_id ) && ( gettype( $tz_id ) === 'string' ) ) {
					
					// Trim
					$tz_id = trim( $tz_id );
					
					// Set if valid
					if ( !empty( $tz_id ) ) {
						// Return
						return $tz_id;
					}
				}
			}
		}
		
		// Return
		return null;
	}
}