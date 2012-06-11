<?php

/*
Plugin Name: Welocally Places Mobile
Plugin URI: http://www.welocally.com/wordpress/?page_id=2
Description: The Welocally Places mobile plugin makes your content location aware using welocally places
Version: 1.1.0.BETA
Author: Welocally  
Author URI: http://welocally.com
License: GPL2 
*/

register_activation_hook(__FILE__, 'welocally_places_mobile_activate');
add_filter( 'init', 'wl_places_mobile_init' );
add_action('wp_ajax_get_posts', 'wl_places_mobile_get_posts');
add_action('wp_ajax_nopriv_get_posts', 'wl_places_mobile_get_posts');

add_action('wp_ajax_get_post_place', 'wl_places_mobile_get_post_place');
add_action('wp_ajax_nopriv_get_post_place', 'wl_places_mobile_get_post_place');

add_action('wp_ajax_like_place', 'wl_places_mobile_like_place');
add_action('wp_ajax_nopriv_like_place', 'wl_places_mobile_like_place');

function welocally_places_mobile_activate() {
	if (version_compare(PHP_VERSION, "5.1", "<")) {
		trigger_error('Can Not Install Welocally Places, Please Check Requirements', E_USER_ERROR);
	} else {
		require_once (dirname(__FILE__) . "/WelocallyPlacesMobile.class.php");
		require_once (dirname(__FILE__) . "/menu.php");
		global $wlPlacesMobile;
		$wlPlacesMobile->onActivate();
	}
}

if (version_compare(phpversion(), "5.1", ">=")) {
	require_once (dirname(__FILE__) . "/WelocallyPlacesMobile.class.php");
	require_once (dirname(__FILE__) . "/menu.php");
}

function wl_places_mobile_init() {
			
	if ( !is_admin() ) {
		
	}
}

function wl_places_mobile_get_posts() {	
	
	global $wlPlacesMobile;
	$lat = $_POST['lat'];	
	$lng = $_POST['lng'];
	if(!empty($_POST['units']))
		$units = $_POST['units'];	
	
	if(!empty($_POST['dist']))
		$dist = $_POST['dist'];
	else
		$dist = 100.00;
		
	$loc = array('lat'=>$lat,'lng' =>$lng);
	$options = $wlPlacesMobile->getOptions();
	
	//print_r($options[ 'mobile_post_types']);
	//$placesPosts = $wlPlacesMobile->geoSearch($loc, $dist, $units, intval($options[ 'results_max' ]));
	//echo json_encode($wlPlacesMobile->geoSearch($loc, $dist, $units, intval($options[ 'results_max' ])));

	echo json_encode(
		$wlPlacesMobile->geoSearch(
			$loc, 
			$dist, 
			$units, 
			intval($options[ 'results_max' ]),
			$options[ 'mobile_post_types']
		)
	);
	

	die(); // this is required to return a proper result	
		
}

function wl_places_mobile_like_place() {	
}


function wl_places_mobile_get_post_place() {	
	global $wlPlacesMobile;
	$post_id = $_POST['post_id'];	
	$wl_id = $_POST['wl_id'];	
	$post = $wlPlacesMobile->getPostPlace($post_id,$wl_id);
	echo json_encode($post);

	die(); // this is required to return a proper result		
}


?>
