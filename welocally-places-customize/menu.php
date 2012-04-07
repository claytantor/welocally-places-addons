<?php 
function wl_menu_customize_initialize() {
	

	if(function_exists('welocally_activate')){
		wl_add_submenu( 'Welocally Places Options', 'Customize', 'welocally-places-customize-options', 'wl_support_theme_options' );
	}
	
	
	
}

add_action( 'admin_menu','wl_menu_customize_initialize',100);

function wl_support_theme_options(){
	include_once( WP_PLUGIN_DIR . "/welocally-places-customize/options/customize-options.php" );
}
