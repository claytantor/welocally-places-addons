<?php
/*
Plugin Name: Welocally Places Customize
Plugin URI: http://www.welocally.com/wordpress/?page_id=2
Description: The Welocally Places Customize add on lets you customize the plugin to your theme. Fully activated Welocally Places is required for this plugin to function.
Author: Welocally Inc. 
Version: 1.1.18.DEV
Author URI: http://welocally.com
License: Welocally Places Beta Add On License
Notes: none

*/

register_activation_hook(__FILE__, 'welocally_customize_activate');
register_deactivation_hook(WP_PLUGIN_DIR.'/welocally-places/welocally-places.php', 'welocally_customize_deactivate');


//need to check for welocally places
function welocally_customize_activate() {
	
	if (version_compare(PHP_VERSION, "5.1", "<") && welocally_is_curl_installed()) {
		br_trigger_error('Can Not Activate Welocally Places Customize addon, Please Check Requirements', E_USER_ERROR);
	} else {
		  global $wlPlaces;
		  
		  require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		 
		  if ( function_exists('welocally_activate') )
		  {
		    require_once ( WP_PLUGIN_DIR . '/welocally-places/welocally-places.php' );
		    
			//set the version
			$options = $wlPlaces->getOptions();
			$options['style_customize_version'] = 'v'.microtime(true);
			
			//set defaults for init
			if(!isset($options['style_customize'])){
				$options['style_customize'] = 'off';
			}
					//set defaults for init
			if(!isset($options['category_customize'])){
				$options['category_customize'] = 'off';
			}
			
			wl_save_options($options);
		  }
		  else
		  {
		    br_trigger_error('Can Not Activate Welocally Places Customize, Welocally Places Is Not Activated', E_USER_ERROR);
		    deactivate_plugins( __FILE__);
		  }
	}
}

function br_trigger_error($message, $errno) {
 
    if(isset($_GET['action'])
          && $_GET['action'] == 'error_scrape') {
 
        echo '<div style="font-family:arial,sans-serif; font-size:1.0em; "><em>' . $message . '</em></div>';
 
        exit;
 
    } else {
 
        trigger_error($message, $errno);
 
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

	if(function_exists('welocally_activate')){
		global $wlPlaces;
	
		$options = $wlPlaces->getOptions();
		if($options['category_customize'] == 'on'){
			add_filter('category_template', 'wl_places_customize_get_template_category',100);
		}
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

function wl_customize_plugin_base(){
	$plugin = plugin_basename( __FILE__ );
	return $plugin;
}

function wl_customize_deactivate(){
	deactivate_plugins( __FILE__);
}






?>
