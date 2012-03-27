<?php
/*
Plugin Name: Welocally Places Customize
Plugin URI: http://www.welocally.com/wordpress/?page_id=2
Description: The Welocally Places Customize add on lets you customize the plugin to your theme
Author: Welocally Inc. 
Version: 1.1.18.DEV
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

require_once (dirname(__FILE__) . "/welocally-places-customize.class.php");
require_once (dirname(__FILE__) . "/template-tags.php");
require_once (dirname(__FILE__) . "/menu.php");

//ajax calls
add_action('wp_ajax_style_customize_save', 'welocally_style_options_customize_save');
add_action('wp_ajax_category_customize_save', 'welocally_category_options_customize_save');

// add action for apply filters
add_action( 'init','welocally_places_customize_filters',100);

function welocally_places_customize_filters(){
	global $wlPlaces;
	$options = $wlPlaces->getOptions();
	if($options['category_customize'] == 'on'){
		add_filter('category_template', 'wl_places_customize_get_template_category',100);
	}
	

}



function welocally_style_options_customize_save() {
	 global $wlPlaces;
	 $options = $wlPlaces->getOptions();
	 //syslog(LOG_WARNING, print_r( $_POST['style_customize'], true));
	 if ($_POST['style_customize'] == 'on'){
	 	 if (!wl_create_custom_style_files()){echo json_encode(array('success' =>'false' ,'error' =>  'error, files can\'t created'));exit();}
	 	$style_customize = 'on';
	 }else {
	 	$style_customize = 'off';
	 }
	 $options['style_customize'] = $_POST['style_customize'];
	 wl_save_options($options);
	 echo json_encode(array('success' =>'true' ,'style_customize' =>  $style_customize));
	 exit();
}


function welocally_category_options_customize_save() {
	 global $wlPlaces;
	 $options = $wlPlaces->getOptions();
	 //syslog(LOG_WARNING, print_r( $_POST['category_customize'], true));
	 if ($_POST['category_customize'] == 'on'){
	 	 if (!wl_create_custom_category_files()){echo json_encode(array('success' =>'false' ,'error' =>  'error, files can\'t created'));exit();}
	 	$category_customize = 'on';
	 }else {
	 	$category_customize = 'off';
	 }
	 $options['category_customize'] = $_POST['category_customize'];
	 wl_save_options($options);
	 echo json_encode(array('success' =>'true' ,'category_customize' =>  $category_customize));
	 exit();
}



?>
