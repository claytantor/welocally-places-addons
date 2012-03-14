<?php
/*
Plugin Name: Welocally Places Customize
Plugin URI: http://www.welocally.com/wordpress/?page_id=2
Description: The Welocally Places Customize add on lets you customize the plugin to your theme
Author: Welocally Inc. 
Author URI: http://welocally.com
License: Welocally Places Beta Add On License
Notes: none

*/

register_activation_hook(__FILE__, 'welocally_places_customize_activate');

//need to check for welocally places
function welocally_activate() {
	if (version_compare(PHP_VERSION, "5.1", "<") && welocally_is_curl_installed()) {
		trigger_error('Can Not Install Welocally Places Customize, Please Check Make Sure That Welocally Places is Installed', E_USER_ERROR);
	} else {
		syslog(LOG_WARNING, "activate");
	}
}

if (version_compare(phpversion(), "5.1", ">=") && welocally_is_curl_installed()) {

}

?>
