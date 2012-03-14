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

register_activation_hook(__FILE__, 'welocally_customize_activate');

//need to check for welocally places
function welocally_customize_activate() {
	if (version_compare(PHP_VERSION, "5.1", "<") && welocally_is_curl_installed()) {
		trigger_error('Can Not Install Welocally Places Customize addon, Please Check Requirements', E_USER_ERROR);
	} 
	if(!function_exists('welocally_activate')){
		trigger_error('Can Not Install Welocally Places Customize addon, Plugin Welocally Places not instaled ', E_USER_ERROR);
	}
	else {
		syslog(LOG_WARNING, "activate");
	}
}

require_once (dirname(__FILE__) . "/template-tags.php");
require_once (dirname(__FILE__) . "/menu.php");

//ajax calls
add_action('wp_ajax_customize_save', 'welocally_theme_options_customize_save');

// add action for apply filters
add_action( 'init','welocally_places_customize_filters',100);

function welocally_places_customize_filters(){
	global $wlPlaces;
	$options = $wlPlaces->getOptions();
	if($options['theme_customize'] == 'on'){
		add_filter('map_widget_template', 'wl_places_customize_get_template_map_widget',100);
		add_filter('list_widget_template', 'wl_places_customize_get_template_list_widget',100);
		add_filter('category_template', 'wl_places_customize_get_template_category',100);
	}
}


function welocally_theme_options_customize_save() {
	 global $wlPlaces;
	 $options = $wlPlaces->getOptions();
	 if ($_POST['customize'] == 'on'){
	 	 if (!wl_create_custom_files()){echo json_encode(array('success' =>'false' ,'error' =>  'error, files can\'t created'));exit();}
	 	$customize = 'off';
	 }
	 else {
	 	$customize = 'on';
	 }
	 $options['theme_customize'] = $_POST['customize'];
	 wl_save_options($options);
	 echo json_encode(array('success' =>'true' ,'customize' =>  $customize));
	 exit();
}


?>
