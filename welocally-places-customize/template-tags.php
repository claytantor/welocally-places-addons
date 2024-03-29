<?php
function wl_create_custom_files_error(){
	echo json_encode(array('success' =>'false' ,'error' =>  'error, files can\'t created'));
	exit();
}


function wl_create_custom_style_files(){
	
	$source_path = WP_PLUGIN_DIR.'/welocally-places-customize/resources/example/stylesheets';	
	$target_path = WP_PLUGIN_DIR.'/welocally-places-customize/resources/custom/stylesheets';
	
	
	if (!file_exists($target_path)){
		mkdir($target_path,1) or wl_create_custom_files_error();
		chmod($target_path, 0755);
	}
	if (!file_exists($target_path.'/wl_places.css')){
		copy ( $source_path.'/wl_places.css' , $target_path.'/wl_places.css' );		
		chmod($target_path.'/wl_places.css', 0755);				
	}
	if (!file_exists($target_path.'/wl_places_place.css')){
		copy ( $source_path.'/wl_places_place.css' , $target_path.'/wl_places_place.css' );		
		chmod($target_path.'/wl_places_place.css', 0755);				
	}
	if (!file_exists($target_path.'/wl_places_multi.css')){
		copy ( $source_path.'/wl_places_multi.css' , $target_path.'/wl_places_multi.css' );		
		chmod($target_path.'/wl_places_multi.css', 0755);				
	}
		
	return true;	 
}

function wl_create_custom_category_files(){
	
	$source_path = WP_PLUGIN_DIR.'/welocally-places-customize/views/example';	
	$target_path = WP_PLUGIN_DIR.'/welocally-places-customize/views/custom';
		
	
	if (!file_exists($target_path)){
		mkdir($target_path,1) or wl_create_custom_files_error();
		chmod($target_path, 0755);
	}
	if (!file_exists($target_path.'/category-places-map.php')){
		//syslog(LOG_WARNING, print_r( $source_path.'/category-places-map.php, '.$target_path.'/category-places-map.php', true));
		copy ( $source_path.'/category-places-map.php' , $target_path.'/category-places-map.php' );		
		chmod($target_path.'/category-places-map.php', 0755);				
	}
	
	return true;	 
}

function wl_read_custom_file($filename){
	$fh = fopen($filename, 'r');
	$theData = fread($fh,5000);
	fclose($fh);
	print $theData;
}

function wl_save_custom_file($fieldname,$stringData){
	
	global $wlPlaces;
	$options = $wlPlaces->getOptions();
	
	//category-places-map 
	if($fieldname == 'category-places-map' 
		&& $options['category_customize'] == 'on' 
		&& strlen ( $stringData )>0)
	{
		
		$target = WP_PLUGIN_DIR.'/welocally-places-customize/views/custom/category-places-map.php';	
		//syslog(LOG_WARNING, 'WRITING FILE:'.$target);	
		$fh = fopen($target, 'w');
		fwrite($fh, stripslashes($stringData));
		fclose($fh);
	}
	
	if($fieldname == 'basic-styles' 
		&& $options['style_customize'] == 'on' 
		&& strlen ( $stringData )>0)
	{
		
		$target_path = WP_PLUGIN_DIR.'/welocally-places-customize/resources/custom/stylesheets';	
		$fh = fopen($target_path.'/wl_places.css', 'w');
		fwrite($fh, stripslashes($stringData));
		fclose($fh);
	}
	
	if($fieldname == 'places-place-styles' 
		&& $options['style_customize'] == 'on' 
		&& strlen ( $stringData )>0)
	{
		
		$target_path = WP_PLUGIN_DIR.'/welocally-places-customize/resources/custom/stylesheets';	
		$fh = fopen($target_path.'/wl_places_place.css', 'w');
		fwrite($fh, stripslashes($stringData));
		fclose($fh);
	}

	if($fieldname == 'places-multi-styles' 
		&& $options['style_customize'] == 'on' 
		&& strlen ( $stringData )>0)
	{
		
		$target_path = WP_PLUGIN_DIR.'/welocally-places-customize/resources/custom/stylesheets';	
		$fh = fopen($target_path.'/wl_places_multi.css', 'w');
		fwrite($fh, stripslashes($stringData));
		fclose($fh);
	}
	

}

function wl_places_customize_get_template_category(){
	global $wlPlaces;
	
	//dont use template for feed
	if(is_Feed()) {
		return;
	}			
	
	$cat = get_query_var( 'cat' );
		
	if($cat){
		ob_start();
		include(dirname(__FILE__) . '/views/custom/category-places-map.php');
		$html = ob_get_contents();
		ob_end_clean();

		echo($wlPlaces->replaceTagsInContent($html));
		exit();
		
	}

	return;
		
}