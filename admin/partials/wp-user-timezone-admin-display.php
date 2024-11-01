<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link https://in.linkedin.com/in/ravikatha
 * @since 1.0.0
 *       
 * @package Wp_User_Timezone
 * @subpackage Wp_User_Timezone/admin/partials
 */

// Get Options
$options = (new WP_User_Timezone_Options($this->plugin_name))->get_options();

?>
<div class="wrap">
	<h2><?php echo esc_html(get_admin_page_title()); ?></h2>
	
	<form method="post" action="options.php">
		<?php
	        settings_fields($this->plugin_name);
	        do_settings_sections($this->plugin_name);
	    ?>
		<table>
			<tr>
				<td style="vertical-align: top; text-align: left; padding-right: 20px; white-space: nowrap; padding-bottom: 20px;">
					<label for="<?php echo $this->plugin_name; ?>-cookieName">Cookie name</label>
				</td>
				<td style="vertical-align: top; text-align: left; padding-bottom: 20px;">
				 	<input type="text" name="<?php echo $this->plugin_name . '[' . WP_User_Timezone_Options::COOKIE_NAME_OPTION . ']'; ?>"
				 		   id="<?php echo $this->plugin_name; ?>-cookieName"
				 		   value="<?php echo esc_attr_e($options[WP_User_Timezone_Options::COOKIE_NAME_OPTION]); ?>" />
				 	<br />
				 	<span><?php esc_attr_e('Name of the cookie to use (default: ' . WP_User_Timezone_Options::DEFAULT_COOKIE_NAME . ')', $this->plugin_name); ?></span>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top; text-align: left; padding-right: 20px; white-space: nowrap; padding-bottom: 20px;">
					<label for="<?php echo $this->plugin_name; ?>-cookieExpiryDays">Expiry Days</label>
				</td>
				<td style="vertical-align: top; text-align: left; padding-bottom: 20px;">
				 	<input type="text" name="<?php echo $this->plugin_name . '[' . WP_User_Timezone_Options::COOKIE_EXPIRY_DAYS_OPTION . ']'; ?>"
				 		   id="<?php echo $this->plugin_name; ?>-cookieExpiryDays"
				 		   value="<?php echo esc_attr_e($options[WP_User_Timezone_Options::COOKIE_EXPIRY_DAYS_OPTION]); ?>" />
				 	<br />
				 	<span><?php esc_attr_e('Number of days for the cookie to expire (default: 0 => Non-persistent session cookie)', $this->plugin_name); ?></span>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top; text-align: left; padding-right: 20px; white-space: nowrap; padding-bottom: 20px;">
					<label for="<?php echo $this->plugin_name; ?>-googleTimezoneApi">Google Timezone API</label>
				</td>
				<td style="vertical-align: top; text-align: left; padding-bottom: 20px;">
				 	<input type="checkbox" name="<?php echo $this->plugin_name . '[' . WP_User_Timezone_Options::GOOGLE_TIMEZONE_API_OPTION . ']'; ?>"
				 		   id="<?php echo $this->plugin_name; ?>-googleTimezoneApi"
				 		   value="1" <?php checked($options[WP_User_Timezone_Options::GOOGLE_TIMEZONE_API_OPTION], 1); ?>" />
				 	<br />
				 	<span><?php esc_attr_e('Whether to use Google Timezone API to retrieve the timezone (default: false => Uses a custom script)', $this->plugin_name); ?></span>
				 	<br />
				 	<span><?php esc_attr_e('If enabled, Geolocation is detected from the HTML5 Geolocation API and timezone is resolved using Google Timezone API.', $this->plugin_name); ?></span>
				 	<br />
				 	<span><?php esc_attr_e('Default custom script used: ', $this->plugin_name); ?><a href="http://pellepim.bitbucket.org/jstz/" target="_blank">jsTimezoneDetect</a></span>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top; text-align: left; padding-right: 20px; white-space: nowrap;">
					<label for="<?php echo $this->plugin_name; ?>-googleApiKey">Google API Key</label>
				</td>
				<td style="vertical-align: top; text-align: left;">
				 	<input type="text" name="<?php echo $this->plugin_name . '[' . WP_User_Timezone_Options::GOOGLE_API_KEY_OPTION . ']';?>"
				 		   id="<?php echo $this->plugin_name; ?>-googleApiKey"
				 		   value="<?php echo esc_attr_e($options[WP_User_Timezone_Options::GOOGLE_API_KEY_OPTION]); ?>" />
				 	<br />
				 	<span><?php esc_attr_e('Key for Google API (Required when using Timezone API) (default: <empty> => Timezone API is not used).', $this->plugin_name); ?></span>
				 	<br />
				 	<span><?php esc_attr_e('Ensure that the key is a browser key and allows the current origin (' . site_url() . ') for the api requests.', $this->plugin_name); ?></span>
				 	<br />
				 	<span>Refer to the <a href="http://caniuse.com/geolocation" target="_blank">compatibility</a> and <a href="https://goo.gl/rStTGz" target="_blank">deprecation notice</a></span>
				</td>
			</tr>
			<tr>
				<td style="vertical-align: top; text-align: left; padding-right: 20px; white-space: nowrap; padding-bottom: 20px;">
					<label for="<?php echo $this->plugin_name; ?>-eventOnHooks">Enable EventON Hooks</label>
				</td>
				<td style="vertical-align: top; text-align: left; padding-bottom: 20px;">
				 	<input type="checkbox" name="<?php echo $this->plugin_name . '[' . WP_User_Timezone_Options::EVENTON_HOOKS_OPTION . ']'; ?>"
				 		   id="<?php echo $this->plugin_name; ?>-eventOnHooks"
				 		   value="1" <?php checked($options[WP_User_Timezone_Options::EVENTON_HOOKS_OPTION], 1); ?>" />
				 	<br />
				 	<span><?php esc_attr_e('Whether to enable eventON plugin hooks (default: false)', $this->plugin_name); ?></span>
				 </td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<?php submit_button('Save all changes', 'primary','submit', true); ?>
				</td>
			</tr>
		</table>
	</form>
</div>