<?php
function wl_menu_initialise() {

	$main_slug = add_menu_page( 'Welocally Places Options', 'Welocally Places', 'manage_options', 'welocally-places-general', 'wl_general_options', WP_PLUGIN_URL . '/welocally-places/resources/images/welocally_places_button_color.png' );
	$main_content =  file_get_contents(dirname( __FILE__ ) . '/help/options-general-help.php');
	add_contextual_help( $main_slug, __( $main_content ) );
	

	ob_start();
	$imagePrefix = WP_PLUGIN_URL.'/welocally-places/resources/images/';
    include(dirname( __FILE__ ) . '/options/options-general-help.php');
    $main_content = ob_get_contents();
    ob_end_clean();
	
	
	add_contextual_help( $main_slug, __( $main_content ) );	
	add_filter( 'plugin_action_links', 'wl_add_settings_link', 10, 2 );	
	
	
	
}
add_action( 'admin_menu','wl_menu_initialise' );
add_filter( 'plugin_row_meta', 'wl_set_plugin_meta', 10, 2 );



function wl_general_options() {
	include_once( WP_PLUGIN_DIR . "/welocally-places/options/options-general.php" );
}



function wl_add_settings_link( $links, $file ) {

	static $this_plugin;

	if ( !$this_plugin ) { $this_plugin = plugin_basename( __FILE__ ); }

	if ( strpos( $file, 'welocally-places.php' ) !== false ) {
		$settings_link = '<a href="admin.php?page=welocally-places-general">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}


function wl_set_plugin_meta( $links, $file ) {

	if ( strpos( $file, 'welocally-places.php' ) !== false ) {
		$links = array_merge( $links, array( '<a href="admin.php?page=welocally-places-options">' . __( 'Options' ) . '</a>' ) );	
	}

	return $links;
}

?>