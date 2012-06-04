<?php 
function wl_menu_mobile_initialize() {
	if(function_exists('wl_places_core_add_submenu')){
		$mobile_help = file_get_contents(dirname( __FILE__ ) . '/help/mobile-help.php');
		$mobile_slug = wl_places_core_add_submenu( 'Welocally Places Options', 'Mobile', 'welocally-places-mobile', 'wl_places_mobile_options',$mobile_help );
	}	
}

add_action( 'admin_menu','wl_menu_mobile_initialize',100);

function wl_places_mobile_options(){
	include_once( WP_PLUGIN_DIR . "/welocally-places-mobile/options/mobile-options.php" );
}

?>