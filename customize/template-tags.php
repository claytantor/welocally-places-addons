<?php
function wl_create_custom_files_error(){
	echo json_encode(array('success' =>'false' ,'error' =>  'error, files can\'t created'));
	exit();
}
function wl_create_custom_files(){
	$target_path = dirname(__FILE__).'/views/custom';
	if (!file_exists($target_path)){
		mkdir($target_path,1) or wl_create_custom_files_error();
		chmod($target_path, 0755);
	}
	if (!file_exists($target_path.'/category-places-map.php')){
		$FileHandle = fopen($target_path.'/category-places-map.php', 'w') or wl_create_custom_files_error();
		fclose($FileHandle);
		chmod($target_path.'/category-places-map.php', 0755);
	}
	if (!file_exists($target_path.'/welocally-places-list-widget-display.php')){
		$FileHandle = fopen($target_path.'/welocally-places-list-widget-display.php', 'w') or wl_create_custom_files_error();
		fclose($FileHandle);
		chmod($target_path.'/welocally-places-list-widget-display.php', 0755);
	}
   if (!file_exists($target_path.'/welocally-places-map-widget-display.php')){
		$FileHandle = fopen($target_path.'/welocally-places-map-widget-display.php', 'w') or wl_create_custom_files_error();
		fclose($FileHandle);
		chmod($target_path.'/welocally-places-map-widget-display.php', 0755);
	}
	return true;
	 
}

function wl_read_custom_file($filename){
	$target_path = dirname(__FILE__).'/views/custom/';
	$fh = fopen($target_path.$filename, 'r');
	$theData = fread($fh,5000);
	fclose($fh);
	print $theData;
}

function wl_save_custom_file($filename,$stringData){
	$target_path = dirname(__FILE__).'/views/custom/';
	$fh = fopen($target_path.$filename.'.php', 'w');
	fwrite($fh, stripslashes($stringData));
	fclose($fh);
	print $theData;
}

function wl_places_customize_get_template_map_widget(){
	return dirname( __FILE__ ).'/views/custom/welocally-places-map-widget-display.php';
}
function wl_places_customize_get_template_list_widget(){
	return dirname( __FILE__ ).'/views/custom/welocally-places-list-widget-display.php';
}
function wl_places_customize_get_template_category(){
	return dirname( __FILE__ ).'/views/custom/category-places-map.php';
}