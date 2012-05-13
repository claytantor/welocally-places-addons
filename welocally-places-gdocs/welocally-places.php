<?php

/*
Plugin Name: Welocally Places
Plugin URI: http://www.welocally.com/wordpress/?page_id=2
Description: The Welocally Places plugin lets easily associate places from our 16 million US POI database without manual geocoding, hust use our simple tags. The map widget makes it easy for your users to find the places your are writing about on a map.
Version: 1.1.18
Author: Welocally  
Author URI: http://welocally.com
License: GPL2 
*/

register_activation_hook(__FILE__, 'welocally_activate');



//----- end of neworking section ----------//
function br_trigger_error($message, $errno) {
 
    if(isset($_GET['action'])
          && $_GET['action'] == 'error_scrape') {
 
        echo '<div style="font-family:arial,sans-serif; font-size:1.0em; "><em>' . $message . '</em></div>';
 
        exit;
 
    } else {
        trigger_error($message, $errno); 
    }
}




function welocally_activate() {
	if (version_compare(PHP_VERSION, "5.1", "<")) {
		br_trigger_error('Can Not Install Welocally Places, Please Check Requirements', E_USER_ERROR);
	} else {
		require_once (dirname(__FILE__) . "/welocally-places.class.php");
		require_once (dirname(__FILE__) . "/menu.php");
	
		global $wlPlaces;
		$wlPlaces->on_activate();
	}
}


if (version_compare(phpversion(), "5.1", ">=")) {
	require_once (dirname(__FILE__) . "/welocally-places.class.php");
	require_once (dirname(__FILE__) . "/menu.php");	
}


?>
