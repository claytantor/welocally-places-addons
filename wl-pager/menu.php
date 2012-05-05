<?php
function wl_pager_menu_initialise() {
	$main_slug = add_menu_page( 'Welocally Pager Shortcode', 'Welocally Pager Shortcode', 'manage_options', 'welocally-pager-general', 'pager_general_options', WP_PLUGIN_URL . '/wl-pager/images/pager_button_color.png' );
	
	ob_start();
	$foo='bar';
	$imagePrefix = WP_PLUGIN_URL.'/wl-pager/images/';
    include(dirname( __FILE__ ) . '/pager-general-help.php');
    $main_content = ob_get_contents();
    ob_end_clean();
	
	
	//$main_content =  file_get_contents(dirname( __FILE__ ) . '/pager-general-help.php');
	
	
	add_contextual_help( $main_slug, __( $main_content ) );	
	add_filter( 'plugin_action_links', 'wl_add_settings_link', 10, 2 );	
}

add_action( 'admin_menu','wl_pager_menu_initialise' );
add_filter( 'plugin_row_meta', 'wl_pager_set_plugin_meta', 10, 2 );

function pager_general_options(){
	include_once( WP_PLUGIN_DIR . "/wl-pager/pager-general-options.php" );
}

function wl_pager_add_settings_link( $links, $file ) {

	static $this_plugin;

	if ( !$this_plugin ) { $this_plugin = plugin_basename( __FILE__ ); }

	if ( strpos( $file, 'wl-pager.php' ) !== false ) {
		$settings_link = '<a href="admin.php?page=welocally-pager-general">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}

function wl_pager_set_plugin_meta( $links, $file ) {

	if ( strpos( $file, 'welocally-places.php' ) !== false ) {
		$links = array_merge( $links, array( '<a href="admin.php?page=welocally-pager-general">' . __( 'Options' ) . '</a>' ) );		
	}

	return $links;
}



?>