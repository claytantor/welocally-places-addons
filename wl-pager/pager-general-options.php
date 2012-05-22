<?php
	
global $wlPager;
$options = $wlPager->getOptions();

?>
<div class="wrap wl_pager_admin">
<div id="action_bar">
	<div class="rollover" id="btn_support">
	<a href="http://support.welocally.com/categories/welocally-paginator-shortcode" target="_new">
		<img src="<?php echo WP_PLUGIN_URL; ?>/wl-pager/images/btn_spacer.png">
		</a>
	</div>
	<div class="rollover" id="btn_contact">
	<a href="http://welocally.com/?page_id=139" target="_new">
		<img src="<?php echo WP_PLUGIN_URL; ?>/wl-pager/images/btn_spacer.png">
		</a>
	</div>
	<div class="rollover" id="btn_guide">
	<a href="http://welocally.com/?page_id=1199" target="_new">
		<img src="<?php echo WP_PLUGIN_URL; ?>/wl-pager/images/btn_spacer.png">
		</a>
	</div>	
	<div style="display:inline-block;">
		<img src="<?php echo WP_PLUGIN_URL; ?>/wl-pager/images/quick-help.png">
	</div>									
</div>	
<div class="titlebar"><img src="<?php echo WP_PLUGIN_URL; ?>/wl-pager/images/pager-titlebar.png"/></div>

<?php
if ( ( !empty( $_POST ) ) && ( check_admin_referer( 'welocally-pager-general', 'welocally_pager_general_nonce' ) ) ) { 
	
	$options[ 'theme' ] = $_POST[ 'wl_pager_theme' ];
	
	
	//infobox_title_link
	$options[ 'tables' ] = $_POST[ 'wl_pager_tables' ];
	if(!isset($options['tables']) || $options['tables'] == ''){
		$options['tables'] = null;
	} 
		
	$wlPager->saveOptions($options);

	echo '<div class="updated fade"><p><strong>' . __( 'Settings Saved.' ) . "</strong></p></div>\n";
}


?>

<form method="post" action="<?php echo bloginfo( 'wpurl' ).'/wp-admin/admin.php?page=welocally-pager-general' ?>">

<span class="wl_options_heading"><?php _e( 'General Settings' ); ?></span>
<table class="wl-form-table">
	<tr valign="top">
		<th scope="row"><?php _e( 'Theme' ); ?></th>
		<td>
		<span class="description"><?php _e( 'Choose which theme you would like to use for pagination buttons.' ); ?></span>
		</br>
		<select name="wl_pager_theme">
			<option value="basic" <?php if($options[ 'theme' ] == "default"){ _e('selected'); } ?> >Basic</option>
		  	<option value="sky" <?php if($options[ 'theme' ] == "sky"){ _e('selected'); } ?>>Sky</option>
		  	<option value="froggy" <?php if($options[ 'theme' ] == "froggy"){ _e('selected'); } ?>>Froggy</option>
		  	<option value="santafe" <?php if($options[ 'theme' ] == "santafe"){ _e('selected'); } ?>>Santa Fe</option>
		  	<option value="cherry" <?php if($options[ 'theme' ] == "cherry"){ _e('selected'); } ?>>Cherry</option>
		</select>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><?php _e('Permit Tables' ); ?></th>
		<td>
			<span class="description"><?php _e( 'The plugin will only permit tables you specify here to be paginated. Provide a comma deliminated list of tables allowed to paginate.' ); ?></span>
			</br>
			<input id="wl_pager_tables" name="wl_pager_tables"  type="text" size="56" value="<?php _e($options[ 'tables' ]) ?>" />
		</td>
	</tr>
	
</table>


<?php wp_nonce_field( 'welocally-pager-general','welocally_pager_general_nonce', true, true ); ?>

<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Settings' ); ?>"/></p>

</form>

</div>