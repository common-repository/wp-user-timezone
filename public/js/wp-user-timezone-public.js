(function( $ ) {
	'use strict';
	
	// On Document Load
	$(function () {
		// Plugin Options
		var pluginOptions = window['WP_USER_TIMEZONE_OPTIONS'];
		if(!pluginOptions) {
			return;
		}
		pluginOptions = JSON.parse(pluginOptions);
		if(!pluginOptions.cookieName) {
			return;
		}
		
		// Cookie
		var savedTz = $.cookie(pluginOptions.cookieName);
		savedTz = (typeof(savedTz) === 'string') ? savedTz.trim() : null;
		var currentJsTz = null;
		
		// Sanitize
		var sanitizeTz = function(tz) {
			// Sanitize
			tz = (typeof(tz) === 'string') ? tz.trim() : null;
			if(!tz) {
				return null;
			}
			
			// Map
			var tzMap = {
				'Asia/Calcutta': 'Asia/Kolkata'
			};
			var tzMapped = tzMap[tz];
			if(typeof(tzMapped) === 'string') {
				tz = tzMapped;
			}
			
			// Return
			return tz;
		};
		
		// Default detection
		var defaultDetect = function() {
			if(currentJsTz) {
				return currentJsTz;
			}
			var tzObj = (jstz && (typeof(jstz.determine) === 'function')) ? jstz.determine() : null;  
			return sanitizeTz((tzObj && (typeof(tzObj.name) === 'function')) ? tzObj.name() : null);
		};
		
		// Google detection (callback(tz))
		var googleDetect = function(callback) {
			// Try get current position
			navigator.geolocation.getCurrentPosition(function(pos) {
				var timezoneUrl = 'https://maps.googleapis.com/maps/api/timezone/json';
				timezoneUrl += '?key=' + encodeURIComponent(pluginOptions.googleApiKey);
				timezoneUrl += '&timestamp=' + (Math.round((new Date().getTime())/1000)).toString();
				timezoneUrl += '&location=' + pos.coords.latitude + ',' + pos.coords.longitude;
				
				// Timezone api
				$.ajax(timezoneUrl, { cache: false, async: true })
					.done(function(dt) {
						// Check & Callback
						if(dt.status.toUpperCase() === 'OK') {
							var tz = sanitizeTz(dt.timeZoneId);
							if(tz) {
								callback(tz);
								return;
							}
						}
						
						// Failed
						callback(defaultDetect());
					})
					.fail(function () {
						callback(defaultDetect());
					});
			}, function (err) {
				// Error
				callback(defaultDetect());
			}, {
				enableHighAccuracy: true,
				timeout: 5000,
				maximumAge: 86400000
			});
		}
		
		/**
		 * Applies the detected timezone
		 * @param tz - string - Timezone id
		 */
		var applyTz = function(tz) {
			// Validate
			if(!tz) {
				return;
			}
			
			// Apply
			if((typeof(pluginOptions.cookieExpiryDays) === 'number') && !isNaN(pluginOptions.cookieExpiryDays)) {
				$.cookie(pluginOptions.cookieName, tz, { expires: pluginOptions.cookieExpiryDays, path: '/' });
			} else {
				$.cookie(pluginOptions.cookieName, tz, { path: '/' });
			}
			
			// Reload
			if(!savedTz || (savedTz.toLowerCase() !== tz.toLowerCase())) {
				window.location.reload(true);
			}
		};
		
		// Check if cookie exists and is different from the new tz
		if(savedTz) {
			// Use Default detect & see if the timezone changed
			currentJsTz = defaultDetect();
			if(currentJsTz) {
				if(currentJsTz.toLowerCase() === savedTz.toLowerCase()) {
					// Nothing to do. Return
					return;
				}
			} else {
				// New tz could not be detected. Nothing to do, since we already have a saved tz
				return;
			}
		}
		
		// New detection required.
		if(!pluginOptions.googleTimezoneApi || !pluginOptions.googleApiKey
				|| !navigator.geolocation || !navigator.geolocation.getCurrentPosition) {
			// Use default detection
			applyTz(defaultDetect());
		} else {
			// Use Google Timezone Detection
			googleDetect(applyTz);
		}
	});
})( jQuery );
