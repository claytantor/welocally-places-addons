<?php
global $wlPlacesMobile;
$options = $wlPlacesMobile->getOptions();
?>
<div class="wrap">
<div class="icon32"><img src="<?php echo $wlPlacesMobile->pluginUrl; ?>/images/screen_icon.png" alt="" title="" height="32px" width="32px"/></div>
<h2>Welocally Mobile Options</h2>
<?php
if ( ( !empty( $_POST ) ) && ( check_admin_referer( 'welocally-places-mobile', 'welocally_places_mobile_nonce' ) ) ) { 
	
	$options[ 'mobile_logo_img' ] = trim($_POST[ 'mobile_logo_img' ])? trim($_POST[ 'mobile_logo_img' ]): $wlPlacesMobile->pluginUrl . "/images/mobile_default_logo.png";	
	$options[ 'mobile_banner_img' ] = trim($_POST[ 'mobile_banner_img' ])? trim($_POST[ 'mobile_banner_img' ]): $wlPlacesMobile->pluginUrl . "/images/mobile_default_banner.png";	
	$options[ 'mobile_theme' ] = strtolower($_POST[ 'mobile_theme' ]);
	$options[ 'search_radius' ] = strtolower($_POST[ 'search_radius' ]);
	$options[ 'search_units' ] = strtolower($_POST[ 'search_units' ]);
	$options[ 'results_max' ] = $_POST[ 'results_max' ];
	$options[ 'theme_swatch' ] = strtolower($_POST[ 'theme_swatch' ]);
	$options[ 'header_type' ] = strtolower($_POST[ 'header_type' ]);
	$options[ 'banner_type' ] = strtolower($_POST[ 'banner_type' ]);
	$options[ 'allow_users' ] = strtolower($_POST[ 'allow_users' ]);
	$options[ 'mobile_post_types' ] = strtolower($_POST[ 'mobile_post_types' ]);
	$options[ 'allowed_user_list' ] = strtolower($_POST[ 'allowed_user_list' ]);
	$options[ 'google_analytics_account_id' ] = $_POST[ 'google_analytics_account_id' ];
			
	$wlPlacesMobile->saveOptions($options);

	echo '<div class="updated fade"><p><strong>' . __( 'Settings Saved.' ) . 
		"</strong></p></div>\n";
		
}

$theme_prefix = $wlPlacesMobile->pluginUrl."/themes/";
?>

<script type="text/javascript" charset="utf-8">
var wl_options_imgfield = '';
jQuery(document).ready(function() {
	
	//header type
	var selected_header_type ='<?php echo $options["header_type"] ?>';
	if(selected_header_type == 'logo'){
		jQuery('#header_type_logo_upload').show();
	}
	

	jQuery('input[name=header_type]:radio').click(function() {	
		var selected = jQuery(this).val();
		if(selected == 'title'){
			jQuery('#header_type_logo_upload').hide('slow');
		} else {
			jQuery('#header_type_logo_upload').show('slow');
		}
	});
	
	//banner type
	var selected_banner_type ='<?php echo $options["banner_type"] ?>';
	if(selected_banner_type == 'image'){
		jQuery('#banner_upload').show();
	}
	

	jQuery('input[name=banner_type]:radio').click(function() {	
		var selected = jQuery(this).val();
		if(selected == 'none'){
			jQuery('#banner_upload').hide('slow');
		} else {
			jQuery('#banner_upload').show('slow');
		}
	});
	
	
	jQuery('#upload_image_button_1').click(function() {	 
			wl_options_imgfield = jQuery('#mobile_logo_img').attr('name');
			tb_show('Custom Mobile Logo', 'media-upload.php?type=image&amp;TB_iframe=true');
			return false;
	});
	
	jQuery('#upload_image_button_2').click(function() {	 
			wl_options_imgfield = jQuery('#mobile_banner_img').attr('name');
			tb_show('Custom Mobile Banner', 'media-upload.php?type=image&amp;TB_iframe=true');
			return false;
	});
	
	//user control
	var selected_user_type ='<?php echo $options["allow_users"] ?>';
	if(selected_user_type == 'list'){
		jQuery('#allow_users_list_area').show();
	}
	
	jQuery('input[name=allow_users]:radio').click(function() {	
		var selected = jQuery(this).val();
		if(selected == 'list'){
			jQuery('#allow_users_list_area').show('slow');
		} else {			
			jQuery('#allow_users_list_area').hide('slow');
		}
	});	
		
	//swatch
	var theme = jQuery('#mobile_theme').val().toLowerCase();
	var swatch = jQuery('#theme_swatch').val().toLowerCase();		
	var theme_img = '<img src="<?php echo $theme_prefix; ?>'+theme+'/images/'+theme+'_'+swatch+'.png"/>';
	jQuery('#wl_mobile_swatch_image').html(theme_img);
			
	jQuery('#mobile_theme').change(function() {		
		var theme = jQuery(this).val().toLowerCase();	
		var swatch = jQuery('#theme_swatch').val().toLowerCase();	
		var theme_img = '<img src="<?php echo $theme_prefix; ?>'+theme+'/images/'+theme+'_'+swatch+'.png"/>';
		jQuery('#wl_mobile_swatch_image').hide();
		jQuery('#wl_mobile_swatch_image').html(theme_img);
		jQuery('#wl_mobile_swatch_image').show('slow');	
	});	
	
	jQuery('#theme_swatch').change(function() {	
		var swatch = jQuery(this).val().toLowerCase();	
		var theme = jQuery('#mobile_theme').val().toLowerCase();	
		var theme_img = '<img src="<?php echo $theme_prefix; ?>'+theme+'/images/'+theme+'_'+swatch+'.png"/>';
		jQuery('#wl_mobile_swatch_image').hide();
		jQuery('#wl_mobile_swatch_image').html(theme_img);	
		jQuery('#wl_mobile_swatch_image').show('slow');
	});	
	
	
	//send to editor
	window.send_to_editor = function(html) {
	 imgurl = jQuery('img',html).attr('src');
	 jQuery("#"+wl_options_imgfield).val(imgurl);
	 tb_remove();
	}

});
</script>

<form method="post" action="<?php echo bloginfo( 'wpurl' ).'/wp-admin/admin.php?page=welocally-places-mobile' ?>">
<table class="wl-form-table" style="margin-top:20px;">
	<tr valign="top">
		<th scope="row"><?php _e('Header Look and Feel' ); ?></th>
		<td>
			<ul>
				<li><input type="radio" name="header_type" value="title" <?php if($options[ 'header_type' ]=='title') { echo 'checked';} ?>> Blog Title</li>
				<li><input type="radio" name="header_type" value="logo" <?php if($options[ 'header_type' ]=='logo') { echo 'checked';} ?>> Uploaded Logo</li>
				<li id="header_type_logo_upload" style="display:none">
					<input id="mobile_logo_img" name="mobile_logo_img"  type="text" size="36" value="<?php echo $options[ 'mobile_logo_img' ]; ?>"/>
					<input id="upload_image_button_1" type="button" value="Upload Image" /><br/>
					<span class="description"><?php _e( 'This is the header logo.' ); ?></span>				
				</li>
			</ul>
			
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Custom Banner' ); ?></th>
		<td>
			<ul>
				<li><input type="radio" name="banner_type" value="none" <?php if($options[ 'banner_type' ]=='none') { echo 'checked';} ?>> No Banner</li>
				<li><input type="radio" name="banner_type" value="image" <?php if($options[ 'banner_type' ]=='image') { echo 'checked';} ?>> Uploaded Logo</li>
				<li id="banner_upload" style="display:none">
					<input id="mobile_banner_img" name="mobile_banner_img"  type="text" size="36" value="<?php echo $options[ 'mobile_banner_img' ]; ?>"/>
					<input id="upload_image_button_2" type="button" value="Upload Image" /><br/>
					<span class="description"><?php _e( 'This is the landing banner.' ); ?></span>				
				</li>
			</ul>
			
		</td>
	</tr>		
	<tr valign="top">
		<th scope="row"><?php _e('Geo Search Options' ); ?></th>
		<td>
			<ul>
				<li>Search Units<br/>
					<select name="search_units" id="search_units">
						<option <?php if($options[ 'search_units' ]=='kilometers') { echo 'selected';} ?>>Kilometers</option>
						<option <?php if($options[ 'search_units' ]=='miles') { echo 'selected';} ?>>Miles</option>						
					</select>
				</li>			
				<li>Search Radius<br/>
					<select name="search_radius" id="search_radius">
						<option value="5" <?php if($options[ 'search_radius' ]=='5') { echo 'selected';} ?>>5</option>
						<option value="10" <?php if($options[ 'search_radius' ]=='10') { echo 'selected';} ?>>10</option>
						<option value="15" <?php if($options[ 'search_radius' ]=='15') { echo 'selected';} ?>>15</option>
						<option value="25" <?php if($options[ 'search_radius' ]=='25') { echo 'selected';} ?>>25</option>
						<option value="50" <?php if($options[ 'search_radius' ]=='50') { echo 'selected';} ?>>50</option>
						<option value="100" <?php if($options[ 'search_radius' ]=='100') { echo 'selected';} ?>>100</option>
						<option value="5000" <?php if($options[ 'search_radius' ]=='5000') { echo 'selected';} ?>>no limit</option>
					</select>
				</li>
				<li>Results Maximum<br/>
					<select name="results_max" id="results_max">
						<option value="10" <?php if($options[ 'results_max' ]=='10') { echo 'selected';} ?>>10</option>
						<option value="15" <?php if($options[ 'results_max' ]=='15') { echo 'selected';} ?>>15</option>
						<option value="15" <?php if($options[ 'results_max' ]=='20') { echo 'selected';} ?>>20</option>
						<option value="25" <?php if($options[ 'results_max' ]=='25') { echo 'selected';} ?>>25</option>
						<option value="500" <?php if($options[ 'results_max' ]=='500') { echo 'selected';} ?>>no limit</option>
					</select>
				</li>
				<li>Post Types (comma separated) <br /><input style="width:400px" type="text" id="mobile_post_types" name="mobile_post_types" value="<?php echo $options[ 'mobile_post_types' ];?>"></li>
			</ul>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Theme Control' ); ?></th>
		<td>
			<ul>
				<li>Mobile Theme<br/>
					<select name="mobile_theme" id="mobile_theme">
						<option value="welocally" <?php if($options[ 'mobile_theme' ]=='welocally') { echo 'selected';} ?>>Welocally</option>
						<option value="froggy" <?php if($options[ 'mobile_theme' ]=='froggy') { echo 'selected';} ?>>Froggy</option>
						<option value="sky" <?php if($options[ 'mobile_theme' ]=='sky') { echo 'selected';} ?>>Sky</option>
						<option value="placehound" <?php if($options[ 'mobile_theme' ]=='placehound') { echo 'selected';} ?>>Placehound</option>
						<option value="piefight" <?php if($options[ 'mobile_theme' ]=='piefight') { echo 'selected';} ?>>Pie Fight</option>
					</select>
				</li>
				<li>Swatch<br/>
					<select name="theme_swatch" id="theme_swatch">
						<option <?php if($options[ 'theme_swatch' ]=='a') { echo 'selected';} ?>>A</option>
						<option <?php if($options[ 'theme_swatch' ]=='b') { echo 'selected';} ?>>B</option>
						<option <?php if($options[ 'theme_swatch' ]=='c') { echo 'selected';} ?>>C</option>
					</select>
				</li>				
			</ul>
			<div id="wl_mobile_swatch_image"></div>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Mobile View Control' ); ?></th>
		<td>
			<ul>				
				<li><input type="radio" name="allow_users" value="none" <?php if($options[ 'allow_users' ]=='none') { echo 'checked';} ?>> Disable Mobile View</li>
				<li><input type="radio" name="allow_users" value="dev" <?php if($options[ 'allow_users' ]=='dev') { echo 'checked';} ?>> Dev Mode (ALL REQUESTS)</li>
				<li><input type="radio" name="allow_users" value="all" <?php if($options[ 'allow_users' ]=='all') { echo 'checked';} ?>> Allow All Mobile Users</li>
				<li><input type="radio" name="allow_users" value="list" <?php if($options[ 'allow_users' ]=='list') { echo 'checked';} ?>> Only Allow Mobile Users In List</li>
				<li id="allow_users_list_area" style="display:none">Allowed User List (comma seperated)<br/><input style="width:400px" type="text" id="allowed_user_list" name="allowed_user_list" value="<?php echo $options[ 'allowed_user_list' ]; ?>"></li>
			</ul>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Google Analytics For Mobile' ); ?></th>
		<td>
			<ul>
				<li>Google Analytics Account Id<br/><input style="width:400px" type="text" id="google_analytics_account_id" name="google_analytics_account_id" value="<?php echo $options[ 'google_analytics_account_id' ]; ?>"></li>
			</ul>
		</td>
	</tr>			
	
</table>


<?php wp_nonce_field( 'welocally-places-mobile','welocally_places_mobile_nonce', true, true ); ?>

<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Settings' ); ?>"/></p>

</form>

</div>

