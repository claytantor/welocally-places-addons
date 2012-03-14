<?php 
function wl_menu_customaize_initialise() {
	wl_add_submenu( 'Welocally Places Options', 'Theme Options', 'welocally-places-theme-options', 'wl_support_theme_options' );
}

add_action( 'admin_menu','wl_menu_customaize_initialise',100);

function wl_support_theme_options(){
	include_once( WP_PLUGIN_DIR . "/welocally-places-customize/options/theme_options.php" );
}
