<?php
/*
Plugin Name: Welocally Paginator
Plugin URI: http://www.welocally.com/wordpress/?page_id=2
Description: The Welocally Paginator lets you easily paginate any data in your wordpress database and apply each row to a simple template
Author: Welocally Inc. 
Version: 1.1.5
Author URI: http://welocally.com
License: Welocally Software License (included)
Notes: none
*/

register_activation_hook(__FILE__, 'wl_pager_activate');
add_action('wp_ajax_nopriv_getpage', 'wl_pager_getpage');
add_action('wp_ajax_getpage', 'wl_pager_getpage');
add_action('wp_ajax_nopriv_get_metadata', 'wl_pager_get_metadata');
add_action('wp_ajax_get_metadata', 'wl_pager_get_metadata');

function br_trigger_error($message, $errno) {
 
    if(isset($_GET['action'])
          && $_GET['action'] == 'error_scrape') {
 
        echo '<div style="font-family:arial,sans-serif; font-size:1.0em; "><em>' . $message . '</em></div>';
 
        exit;
 
    } else {
        trigger_error($message, $errno); 
    }
}

function wl_pager_activate() {
	if (version_compare(PHP_VERSION, "5.1", "<")) {
		br_trigger_error('Can Not Install Welocally Pager, Please Check Requirements', E_USER_ERROR);
	} else {
		require_once (dirname(__FILE__) . "/WelocallyWPPagination.class.php");	
		require_once (dirname(__FILE__) . "/menu.php");	
		global $wlPager;
	}
}

function wl_pager_get_metadata() {	
	
	global $wlPager;
	$fields = $_POST['fields'];
	$table = $_POST['table'];
	$filter = $_POST['filter'];
	$orderBy = $_POST['orderBy'];
	$pagesize = $_POST['pagesize'];
	
	echo json_encode($wlPager->getMetadata($table, $fields, $filter, $orderBy, $pagesize));	

	die(); // this is required to return a proper result		
}

function wl_pager_getpage() {
	
	global $wlPager;
	$fields = $_POST['fields'];
	$table = $_POST['table'];
	$filter = $_POST['filter'];
	$pagesize = $_POST['pagesize'];
	$orderBy = $_POST['orderBy'];
	$odd = $_POST['odd'];
	$even = $_POST['even'];	
	$page = $_POST['page'];
	
	$content = base64_decode($_POST['content']);
	
	if($wlPager->tableAllowed($table))	
		echo $wlPager->embedPagination($table, $fields, $filter, $orderBy, $pagesize, $content, $page, $odd, $even);
	else
		echo '<div class="wl_error">The table you have selected for pagination is not permitted. Goto Welocally Pager Shortcode options in Admin Dashboard for settings.</div>' ;	
		
	die(); // this is required to return a proper result
}

require_once (dirname(__FILE__) . "/WelocallyWPPagination.class.php");
require_once (dirname(__FILE__) . "/menu.php");	

?>
