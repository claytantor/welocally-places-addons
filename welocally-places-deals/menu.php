<?php 
function wl_menu_deals_initialize() {
	wl_add_submenu( 'Welocally Places Options', 'Deals', 'welocally-places-deals-options', 'wl_support_deals_options' );
}

add_action( 'admin_menu','wl_menu_deals_initialize',100);

function wl_support_deals_options(){
	include_once( WP_PLUGIN_DIR . "/welocally-places-deals/options/deals-options.php" );
}
