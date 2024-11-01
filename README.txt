=== WP User Timezone ===
Contributors: ravikatha 
Donate link: https://donatetounicef.in/
Tags: date, time, timezone
Requires at least: 3.0.1
Tested up to: 4.5.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP User Timezone displays the front-end dates & times in the browser's local timezone without actually modifying your database.

== Description ==
Wordpress has a site-level timezone setting configured from the admin back-end. All dates and times displayed on the
front-end are typically displayed in this timezone.

This plugin enables the display of dates/times in the front-end using the browser's timezone. This is done by overriding the
Wordpress's **'timezone_string'** and **'gmt_offset'** options to match the user's timezone **for the duration of a particular request**
**ONLY on the front-end interface**.

= Browser Timezone Detection =
The browser's timezone is detected using one of the following two methods

1. [jsTimezoneDetect](http://pellepim.bitbucket.org/jstz/) (default)
1. [HTML5 Geolocation API](http://caniuse.com/geolocation) and [Google Timezone API](https://developers.google.com/maps/documentation/timezone)

**jsTimezoneDetect (default)**  
	This is the default timezone detection mechanism. Please refer to the script [home page](http://pellepim.bitbucket.org/jstz/)
for the compatibility and other notes

**HTML5 Geolocation API**  
	Detects the current geo-location using *[navigator.geolocation.getCurrentPosition](http://caniuse.com/geolocation)*.
The location thus detected will be sent to the [Google Timezone API](https://developers.google.com/maps/documentation/timezone)
which returns the timezone id - similar to "Asia/Kolkata".

This method requires you to

* Enable the "Google Timezone API" option on the plugin options page
* Specify a valid [Google API Key](https://developers.google.com/maps/documentation/timezone/get-api-key)
 * The key should be a browser key
 * The "Accepted HTTP Referrers" should include the domain name used by the hosting wordpress site or left blank to allow all referrers

If the required options for the usage of Google Timezone API are not met, the detection mechanism falls back to the default one.
 
= Post Timezone Detection =
* If the browser's timezone id is not detected successfully, everything else behaves as if this plugin is not installed.
* If the browser's timezone id is detected, the following actions take place
 * A cookie will be set in the browser, based on the cookie options specified on the plugin options page
 * If a cookie already exists previously with the same id, no action is taken
 * If a cookie doesn't already exist previously or exists with a different timezone id, then a *window.reload* is triggered
to refresh the page so that the dates/times on the page are updated

= EventON Hook =
This plugin provides a feature to hook into the EventON plugin. When enabled, all EventON dates/times are updated to reflect
the browser's timezone.

= Shortcodes =
This plugin defines the below shortcodes

1. [wp_user_timezone_id] => Returns the timezone id of the browser or *null* if not available.
1. [wp_user_timezone_offset] => Returns the timezone's gmt offset in hours of the browser or *null* if not available.

= Options =
This plugin defines the below options to retrieve the original timezone_string and gmt_offset defined at the site level

1. get_option( 'site_timezone_string' ) => Returns the original timezone_string option for the site
1. get_option( 'site_gmt_offset' ) => Returns the original gmt_offset option for the site

= Actions/Hooks =
This plugin defines the below filters/hooks

1. apply_filters( 'wp_user_timezone_id', $tz_id ) => Allows you to override the timezone string determined by the plugin.
This filter can be used to apply user's timezone preference. If any plugin or wordpress core implements a feature to accept and
store user's preferred timezone, the 'wp_user_timezone_id' can be used to apply that timezone value to the front-end.
Reference: [Add user-level timezone setting](https://core.trac.wordpress.org/ticket/18146)).

***
= Admin Backend Dates/Times =
**Important: The admin back-end is not modified by this plugin. All dates/times displayed in the back-end are still in the original timezone specified
by the Wordpress General Settings.**
***

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin zip file using Wordpress -> Plugins -> Add New -> Upload Plugin
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the options by visiting the plugin options page

== Frequently Asked Questions ==

= What happens when the timezone detection does not work? =

When the timezone detection mechanism is unable to detect a valid timezone, nothing else is changed. The
front-end behaves as if this plugin is not installed.

= What if I specify a Google API key which is not valid or is blocked? =

The plugin tries the Google Timezone API when possible. If the API call fails, the plugin falls back
to the default mechanism (jsTimezoneDetect).

= What is cookie expiry days option? =

The cookie created by the plugin is by default stored as a session cookie, which is cleared when the browser
session is closed. The next time, you open the browser, the cookie does not exist. On the other hand, if the
cookie expiry is specified, it will be persisted into the user's computer and is available till it expires.

= What does the plugin do on the browser to enable this functionality? =

When each front-end page is loaded, the plugin script checks if the cookie is present and valid. If it is present,
the plugin does nothing. If the cookie is absent or is expired, the timezone detection process takes place as mentioned
in the above sections.

= How long should I set the cookie expiry? =

It is typically advised to set the expiry as small as possible. This will ensure that the user's timezone is updated
as soon as possible. On the contrary, setting too small expiry period will trigger the timezone detection script too
often causing page loads every time the cookie is expired.

A reasonable value would be 7 days.

== Screenshots ==

1. Plugin configuration

== Changelog ==

= 1.0.2 =
* Minor validation fixes

= 1.0.1 =
* jQuery cookie script inclusion bug fix

= 1.0.0 =
* Initial version

== Upgrade Notice ==

= 1.0.2 =
* Contains admin settings validation fixes

= 1.0.1 =
* Contains script related bug fixes

= 1.0.0 =
* Initial version