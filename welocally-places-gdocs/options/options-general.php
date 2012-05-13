<?php
global $wlPlaces;
$options = $wlPlaces->getOptions();
?>
<div class="wrap wl_places_admin">
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
<div class="wrap">
<div class="icon32"><img src="<?php echo WP_PLUGIN_URL; ?>/welocally-places/resources/images/screen_icon.png" alt="" title="" height="32px" width="32px"/><br /></div>
<h2>Welocally Places Options</h2>
<?php
if ( ( !empty( $_POST ) ) && ( check_admin_referer( 'welocally-places-general', 'welocally_places_general_nonce' ) ) ) { 
	
	$options[ 'theme' ] = $_POST[ 'wl_places_theme' ];
	$options[ 'gmaps_key' ] = $_POST[ 'welocally_gmaps_key' ];
		
	wl_save_options($options);

	echo '<div class="updated fade"><p><strong>' . __( 'Settings Saved.' ) . "</strong></p></div>\n";
}
?>

<form method="post" action="<?php echo bloginfo( 'wpurl' ).'/wp-admin/admin.php?page=welocally-places-general' ?>">

<span class="wl_options_heading"><?php _e( 'General Settings' ); ?></span>
<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e( 'Google Maps API Key' ); ?></th>
		<td>
		<span class="description"><?php _e( 'Not required, but if you are seeing more than 25,000 map views daily you will need a key from Google.' ); ?></span>
		</br>
		<input id="welocally_gmaps_key" name="welocally_gmaps_key"  type="text" size="36" value="<?php echo $options[ 'gmaps_key' ]; ?>" />
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e( 'Theme' ); ?></th>
		<td>
		<span class="description"><?php _e( 'Choose which theme you would like to use for pagination buttons.' ); ?></span>
		</br>
		<select name="wl_places_theme">
			<option value="basic" <?php if($options[ 'theme' ] == "welocally"){ _e('selected'); } ?> >Welocally</option>
		  	<option value="sky" <?php if($options[ 'theme' ] == "sky"){ _e('selected'); } ?>>Sky</option>
		  	<option value="froggy" <?php if($options[ 'theme' ] == "froggy"){ _e('selected'); } ?>>Froggy</option>
		  	<option value="santafe" <?php if($options[ 'theme' ] == "santafe"){ _e('selected'); } ?>>Santa Fe</option>
		  	<option value="cherry" <?php if($options[ 'theme' ] == "cherry"){ _e('selected'); } ?>>Cherry</option>
		</select>
		</td>
	</tr>	
</table>

<?php wp_nonce_field( 'welocally-places-general','welocally_places_general_nonce', true, true ); ?>

<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Settings' ); ?>"/></p>

</form>

</div>