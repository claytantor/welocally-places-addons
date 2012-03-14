<style type="text/css">

</style>
<script type="text/javascript">
	jQuery(document).ready(function() {
		
	});
	
</script>
<?php 
global $wlPlaces;
$options = $wlPlaces->getOptions();

if(!isset($options['wl_places_deals']) || $options['wl_places_deals'] == ''){
	$options['wl_places_deals'] = 'off';
	wl_save_options($options);
}

if (!empty($_POST)) {
	foreach($_POST as $key=>$value){
		wl_save_custom_file($key,$value);
	}
}
?>
<div class="wrap">
	<div class="icon32"><img src="<?php echo WP_PLUGIN_URL; ?>/welocally-places/resources/images/screen_icon.png" alt="" title="" height="32px" width="32px"/><br /></div>
	<h2>Welocally Places Deals Options</h2>
	<form method="post" action="<?php echo get_bloginfo( 'wpurl' ).'/wp-admin/admin.php?page=welocally-places-deals-options' ?>">
		<span class="wl_options_heading"><?php _e( 'Deals Options' ); ?></span>
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Settings' ); ?>"/></p>		
	</form>
</div>