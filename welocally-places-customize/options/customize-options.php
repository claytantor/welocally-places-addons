<style type="text/css">
	.left {
	float: left
	}
	.right {
	float: right;
	}
	.clear {
		margin: 0;
		padding: 0;
		clear: both;
	}
	.arrow_bottom span {
		border-color: #000 transparent transparent transparent;
		margin-top: 6px;
	}
	
	.arrow_right span {
		border-color: transparent transparent transparent #000;
		margin-top: 3px;
	}
	
	.arrow {
		width:0; 
		height:0;
		border-style: solid;
		border-width: 5px; 
		display: block;
		margin-right: 5px;
		float: left;
	}
	.wrap {
		width: 100%;
	}
	#style_support {
		color: #C14A1C;
		font-weight: bold;
		cursor: pointer;
		margin-top: 10px;
	}
	.theme-support p {
		margin: 0;
	}
	.wrap-theme-support {
		padding: 0 0 50px 5px;
		border-bottom: 1px solid #bbb;
		border-left: 1px solid #bbb;
		border-right: 1px solid #bbb;
		-moz-border-radius: 5px;
		-khtml-border-radius: 5px;
		-webkit-border-radius: 5px;
	}
	p.header {
		background:#f2f2f2 repeat-x scroll left top;
		text-decoration: none;
		font-size: 11px;
		line-height: 16px;
		padding: 5px 11px;
		margin-top:10px;
		cursor: pointer;
		border: 1px solid #bbb;
		-moz-border-radius: 5px;
		-khtml-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		-moz-box-sizing: content-box;
		-webkit-box-sizing: content-box;
		-khtml-box-sizing: content-box;
		box-sizing: content-box;
		text-shadow: rgba(255,255,255,1) 0 1px 0;
		color: #6b6b6b;
		font-weight: bold;
	}
	.style-option-item textarea {
		width:100%;
	}
	.wl_style_options {
		width:80%;
	}
</style>
<script type="text/javascript">
var wl_options_imgfield = '';

	jQuery(document).ready(function() {
		jQuery('#style_support').toggle(
			function(){
				jQuery(this).removeClass('arrow_bottom');
				jQuery(this).addClass('arrow_right');
				jQuery(this).parent().find('div').hide('slow');
			},
			function(){
				jQuery(this).removeClass('arrow_right');
				jQuery(this).addClass('arrow_bottom');
				jQuery(this).parent().find('div').show('slow');
		});
		jQuery('.style-option-item p.toggle').toggle(
				function(){
					jQuery(this).removeClass('arrow_right');
					jQuery(this).addClass('arrow_bottom');
					jQuery(this).parent().find('div').show('slow');
				},
				function(){
					jQuery(this).removeClass('arrow_bottom');
					jQuery(this).addClass('arrow_right');
					jQuery(this).parent().find('div').hide('slow');
		});
		
		
		jQuery('#upload_image_button_1').click(function() {	 
			wl_options_imgfield = jQuery('#welocally_map_default_marker').attr('name');
			tb_show('Custom Marker Sprite', 'media-upload.php?type=image&amp;TB_iframe=true');
			return false;
		});
		
			
		window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
		 jQuery("#"+wl_options_imgfield).val(imgurl);
		 tb_remove();
	}
		
	});
	function styleCustomize(){
		jQuery('#ajax_load').show();
		
		var style_customize;
			
		var n = jQuery("#style_customize_selector input:checked").length;
		if(n==1){
			jQuery('#style_customize_value').val('on');
			style_customize = 'on';
		} else if(n==0){
			jQuery('#style_customize_value').val('off');
			style_customize = 'off';
		}
				
		var submitApplication = function(data) {
			if(data.success == 'true'){
				jQuery('#style_customize_value').val(data.style_customize);
				if(data.style_customize == 'on') {
					jQuery('.wl_style_options').show();
				}
				if (data.style_customize == 'off') {
					jQuery('.wl_style_options').hide();
				}
				jQuery('#ajax_load').hide();
			}
			else{
				alert(data.error);
				jQuery('#style_customize').removeAttr("checked");
			    jQuery('#ajax_load').hide();
			}
			    
		};
		jQuery.ajax({
	        type: 'POST',
	        url: ajaxurl,
	        dataType: 'json',
	        success: submitApplication,
	        data: 'action=style_customize_save&style_customize='+style_customize
	 	});
	}
	
	function categoryCustomize(){
		jQuery('#ajax_load').show();
		
		var category_customize;
			
		var n = jQuery("#category_customize_selector input:checked").length;
		if(n==1){
			jQuery('#category_customize_value').val('on');
			category_customize = 'on';
		} else if(n==0){
			jQuery('#category_customize_value').val('off');
			category_customize = 'off';
		}
				
		var submitApplication = function(data) {
			if(data.success == 'true'){
				jQuery('#category_customize_value').val(data.category_customize);
				if(data.category_customize == 'on') {
					jQuery('.category_options').show();
				}
				if (data.category_customize == 'off') {
					jQuery('.category_options').hide();
				}
				jQuery('#ajax_load').hide();
			}
			else{
				alert(data.error);
				jQuery('#category_customize').removeAttr("checked");
			    jQuery('#ajax_load').hide();
			}
			    
		};
		jQuery.ajax({
	        type: 'POST',
	        url: ajaxurl,
	        dataType: 'json',
	        success: submitApplication,
	        data: 'action=category_customize_save&category_customize='+category_customize
	 	});
	}
</script>
<?php 
global $wlPlaces;
$options = $wlPlaces->getOptions();

if ( ( !empty( $_POST ) ) && 
	( check_admin_referer( 
		'welocally-places-customize-general', 
		'welocally_places_customize_general_nonce' ) ) ) { 

	
	
	if(!isset($options['style_customize']) || $options['style_customize'] == ''){
		$options['style_customize'] = 'off';
	}
	
	if(!isset($options['category_customize']) || $options['category_customize'] == ''){
		$options['category_customize'] = 'off';
	}
	
	if(isset($_POST['welocally_font_names']) || $_POST['welocally_font_names'] != ''){
		$options['font_names'] = $_POST['welocally_font_names'];
	}
	
	if(isset($_POST['welocally_map_custom_style']) || $_POST['welocally_map_custom_style'] != ''){
		$options['map_custom_style'] = $_POST['welocally_map_custom_style'];		
	}
	
	if(isset($_POST['welocally_widget_selector_override']) || $_POST['welocally_widget_selector_override'] != ''){
		$options['widget_selector_override'] = $_POST['welocally_widget_selector_override'];		
	}
	
	if(isset($_POST['welocally_category_selector_override']) || $_POST['welocally_category_selector_override'] != ''){
		$options['category_selector_override'] = $_POST['welocally_category_selector_override'];		
	}
	
	$options[ 'map_default_marker' ] = trim($_POST[ 'welocally_map_default_marker' ])? trim($_POST[ 'welocally_map_default_marker' ]):plugins_url() . "/welocally-places/resources/images/marker_all_base.png";
	
	foreach($_POST as $key=>$value){
		wl_save_custom_file($key,$value);
	}
	$options['style_customize_version'] = 'v'.microtime(true);
	wl_save_options($options);
}
?>

<div class="wrap">
<div class="icon32"><img src="<?php echo WP_PLUGIN_URL; ?>/welocally-places/resources/images/screen_icon.png" alt="" title="" height="32px" width="32px"/><br /></div>
<h2>Welocally Places Customize Options</h2>

	<form method="post" action="<?php echo get_bloginfo( 'wpurl' ).'/wp-admin/admin.php?page=welocally-places-customize-options' ?>">
		<span class="wl_options_heading"><?php _e( 'Maps' ); ?></span>
		
		<div id="wl_customize_form" class="wl_font_support">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e('Toggel Options' ); ?></th>
					<td>
						<ul>
							<li><input type="checkbox" id="welocally_infobox_title_link" name="welocally_infobox_title_link" <?php if($options[ 'infobox_title_link' ]=='on') { echo 'checked';  }  ?>>Infobox Link Place Name To Post</li>
						</ul>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'Marker Image Sprite' ); ?></th>
					<td>
					<input id="welocally_map_default_marker" name="welocally_map_default_marker"  type="text" size="36" value="<?php echo $options[ 'map_default_marker' ]; ?>" />
					<input id="upload_image_button_1" type="button" value="Upload Image" /><br/>
					<span class="description"><?php _e( 'This is the marker maps will use for places' ); ?></span>
					</td>
				</tr>	
		
				<tr>
				<th scope="row"><?php _e( 'Custom Map Style' ); ?></th>
					<td>
						<textarea rows="4" cols="60" name="welocally_map_custom_style" class="wl_admin_codetext wl_admin_wide80"><?php printf(stripslashes($options[ 'map_custom_style' ])); ?></textarea><br/>
						<div class="description"><?php _e( 'This is the custom styling for your maps. Leave blank to use default style. To style your map use the <a href="http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html">Maps Style Wizard</a>' ); ?></div>
					</td>
				</tr>	
			</table>
			
			
			<span class="wl_options_heading"><?php _e( 'Fonts' ); ?></span>
			
			<table class="form-table">
				<tr>
				<th scope="row"><?php _e( 'Font Names' ); ?></th>
					<td>
						<input id="welocally_font_names" name="welocally_font_names"  type="text" class="wl_admin_codetext wl_admin_wide80"  value="<?php echo $options[ 'font_names' ]; ?>" />	
						<div class="description"><?php _e( 'These are the is the custom fonts to be loaded.  Add fonts in the format: "Dosis|Londrina+Solid|Berkshire+Swash". See all fonts at <a href="http://www.google.com/webfonts#ChoosePlace:select">Google Web Fonts</a>' ); ?></div>
					</td>			
				</tr>	
									
			</table>
			
			<span class="wl_options_heading"><?php _e( 'Styles' ); ?></span>
			
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e( 'Allow Custom Styles' ); ?></th>
					<td>
						<div id="style_customize_selector">
						<input id="style_customize_value" type="hidden" <?php if($options['style_customize'] == 'on'):?> value="on" <?php else:?> value ="off" <?php endif;?> />
						<input id="style_customize" type="checkbox" <?php if($options['style_customize'] == 'on'):?> checked="checked" <?php endif;?>  onChange="styleCustomize();">
						<label for="style_customize">Customize</label></p>
						</div>
					</td>			
				</tr>
				<tr class="wl_style_options" <?php if($options['style_customize'] == 'off'):?> style="display:none" <?php endif;?>>
				<th scope="row"><?php _e( 'Widget Selector Style Override' ); ?></th>
					<td>
						<input id="welocally_widget_selector_override" name="welocally_widget_selector_override"  type="text" class="wl_admin_codetext wl_admin_wide80"  value="<?php echo $options[ 'widget_selector_override' ]; ?>" />	
						<div class="description"><?php _e( 'Use this CSS style override for the style for widget map selectors. Commonly this is something like width:98%;' ); ?></div>
					</td>			
				</tr>						
				<tr class="wl_style_options" <?php if($options['style_customize'] == 'off'):?> style="display:none" <?php endif;?>>
					<td colspan="2">
						
						<!-- list -->
						<ul class="wl_style_options" <?php if($options['style_customize'] == 'off'):?> style="display:none" <?php else:?> style="display: block; width:80%; min-width:600px;" <?php endif;?>>
						<li class="style-option-item">
							<p class="header toggle arrow_right"><span class="arrow"></span>Basic Styles</p>
							<div style="display: none">
								<textarea id="option_basic" 
									name="basic-styles" 
									rows="10" 
									class="wl_admin_codetext wl_admin_wide80"><?php wl_read_custom_file(WP_PLUGIN_DIR.'/welocally-places-customize/resources/custom/stylesheets/wl_places.css');?></textarea>
							</div>
						</li>
						<li class="style-option-item">
							<p class="header toggle arrow_right"><span class="arrow"></span>Place Widget</p>
							<div style="display: none">
								<textarea 
									id="option_place" 
									name="places-place-styles" 
									rows="10" 
									class="wl_admin_codetext wl_admin_wide80"><?php wl_read_custom_file(WP_PLUGIN_DIR.'/welocally-places-customize/resources/custom/stylesheets/wl_places_place.css');?></textarea>
							</div>
						</li>
						<li class="style-option-item">
							<p class="header toggle arrow_right"><span class="arrow"></span>Multi Place Map</p>
							<div style="display: none">
								<textarea 
									id="option_multi" 
									name="places-multi-styles" 
									rows="10" 
									class="wl_admin_codetext wl_admin_wide80"><?php wl_read_custom_file(WP_PLUGIN_DIR.'/welocally-places-customize/resources/custom/stylesheets/wl_places_multi.css');?></textarea>
							</div>
						</li>
						</ul>											
					</td>			
				</tr>													
			</table>	
						
			<span class="wl_options_heading"><?php _e( 'Category Pages' ); ?></span>
			
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e( 'Allow Custom Category Page' ); ?></th>
					<td>
						<div id="category_customize_selector">
						<input id="category_customize_value" type="hidden" <?php if($options['category_customize'] == 'on'):?> value="off" <?php else:?> value ="on" <?php endif;?> />
						<input id="category_customize" type="checkbox" <?php if($options['category_customize'] == 'on'):?> checked="checked" <?php endif;?>  onChange="categoryCustomize();">
						<label for="category_customize">Customize</label></p>
						</div>
					</td>			
				</tr>	
				<tr class="category_options" <?php if($options['category_customize'] == 'off'):?> style="display:none" <?php endif;?>>
				<th scope="row"><?php _e( 'Category Selector Style Override' ); ?></th>
					<td>
						<input id="welocally_category_selector_override" name="welocally_category_selector_override"  type="text" class="wl_admin_codetext wl_admin_wide80"  value="<?php echo $options['category_selector_override']; ?>" />	
						<div class="description wl_admin_wide80"><?php _e( 'Use this CSS style override for the style for category map selectors. Commonly this is something like width:25%; height:200px;' ); ?></div>
					</td>			
				</tr>								
				<tr class="category_options" <?php if($options['category_customize'] == 'off'):?> style="display:none" <?php endif;?>>
					<th scope="row"><?php _e( 'Category Page Template' ); ?></th>
					<td>
						<textarea name="category-places-map" 
								rows="10" 
								class="wl_admin_codetext wl_admin_wide80"><?php wl_read_custom_file(WP_PLUGIN_DIR.'/welocally-places-customize/views/custom/category-places-map.php');?></textarea><br/>
						<div class="description wl_admin_wide80"><?php _e( 'You will need to copy your theme\'s archive page, usually called archive.php, into this text area and place the [welocally type="category"/] tag where you want it to show up.' ); ?></div>										
					
					</td>
				</tr>													
			</table>		
		
		
		</div>
		
		

		<?php wp_nonce_field( 'welocally-places-customize-general','welocally_places_customize_general_nonce', true, true ); ?>
		<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Settings' ); ?>"/></p>
		
	</form>
</div>