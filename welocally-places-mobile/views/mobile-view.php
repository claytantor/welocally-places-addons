<?php
global $wlPlacesMobile;
$options = $wlPlacesMobile->getOptions();
?>
<!doctype html>
<html>
<head>
	<title><?php echo get_bloginfo('name'); ?> </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta http-equiv="cleartype" content="on">

	<!-- jQuery Mobile CSS -->
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
	<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/'?>themes/<?php echo $options['mobile_theme'];?>/<?php echo $options['mobile_theme'];?>.css" />
	<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/'?>themes/<?php echo $options['mobile_theme'];?>/custom.css" />
	<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/css/jquery-mobile-override.css' ?>" />
	<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/css/welocally-places-mobile.css' ?>" />
	<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/css/wl_places_place_mobile.css' ?>" />
	
	<!-- Startup Images for iDevices -->
	<script>(function(){var a;if(navigator.platform==="iPad"){a=window.orientation!==90||window.orientation===-90?"images/startup-tablet-landscape.png":"images/startup-tablet-portrait.png"}else{a=window.devicePixelRatio===2?"images/startup-retina.png":"images/startup.png"}document.write('<link rel="apple-touch-startup-image" href="'+a+'"/>')})()</script>
	<!-- The script prevents links from opening in mobile safari -->
	<script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>
	
	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>	
	<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
	
	<!-- google maps -->	
	<script src="https://maps.google.com/maps/api/js?sensor=true"></script>
	
	<script src="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/js/welocally-places-mobile.js' ?>" type="text/javascript"></script>
	<script src="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/js/wl_place_mobile.js' ?>" type="text/javascript"></script>
	<script src="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/js/wl_base.js' ?>" type="text/javascript"></script>
	<script src="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/'?>js/wl_base64.js" type="text/javascript"></script>
	
	
	<script type="text/javascript">
jQuery(document).ready(function() {
  jQuery.mobile.showPageLoadingMsg();	
});

jQuery( document ).delegate('#home', 'pageinit', function() {
	
	
	
	jQuery.mobile.showPageLoadingMsg();	
	
	initManager();	
	loadHomePosts();
	hideAddressBar();
	
	jQuery( '#wl_mobile_disable' ).click(function(event,ui){
		WELOCALLY.browser.setCookie('wl_mobile_enabled','false',30);
		window.location.reload(true);

		return false;
	});
	
	jQuery( '#wl_mobile_disable_error' ).click(function(event,ui){
		WELOCALLY.browser.setCookie('wl_mobile_enabled','false',30);
		window.location.reload(true);

		return false;
	});
	
	jQuery( '#wl_mobile_reload_error' ).click(function(event,ui){
		return false;
	});
	
	jQuery( '#wl_mobile_reload' ).click(function(event,ui){
		window.location.reload(true);
		return false;
	});
	
	 
});

jQuery(document).bind('#home', 'pagechange',function(event, data){
	hideAddressBar();
});


jQuery( document ).delegate('#wl_placepost_details', 'pageinit', function() {
	initManager();	
	jQuery.mobile.showPageLoadingMsg();	
	if(window.location.hash){
		var parts1 = window.location.hash.split('?'); 	
		var queryString = parts1[1];
		var postId = WELOCALLY.util.getParameter( queryString, 'id' );
		var wlId = wl_base64.decode(WELOCALLY.util.getParameter( queryString, 'wl_id' ));	
		window.wlPlacesMobile.getPost(postId, wlId, window.wlPlacesMobile.setDetailsPage);
	}
	
	hideAddressBar(); 
});

jQuery(document).bind('#wl_placepost_details', 'pagechange',function(event, data){
	hideAddressBar();
});

jQuery( document ).delegate('#wl_placepost_map', 'pageinit', function() {
	initManager();	
	jQuery.mobile.showPageLoadingMsg();	
	if(window.location.hash){
		var parts1 = window.location.hash.split('?'); 	
		var queryString = parts1[1];
		var postId = WELOCALLY.util.getParameter( queryString, 'id' );
		var wlId = wl_base64.decode(WELOCALLY.util.getParameter( queryString, 'wl_id' ));	
		window.wlPlacesMobile.getPost(postId, wlId, window.wlPlacesMobile.setMapPage);
	}
	
	hideAddressBar(); 
});

jQuery(document).bind('#wl_placepost_map', 'pagechange',function(event, data){
	hideAddressBar();
});



function initManager() {
	if(!window.wlPlacesMobile) {
		
		//make the manager
		window.wlPlacesMobile =
			new WELOCALLY_PlacesMobile();
		window.wlPlacesMobile.initCfg({ 
			<?php if(isset($mapstyle_json)):?> styles:<?php echo($mapstyle_json.','); endif;?>
			<?php if(isset($maps_markers)):?> imagePath: <?php echo(" '".$maps_markers."', "); endif;?>			
			ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
			filterTheme: '<?php echo $options['theme_swatch'];?>',
			units: '<?php echo $options['search_units'];?>',
			dist: '<?php echo $options['search_radius'];?>'
			});
		jQuery('#wl_mobile_wrapper').html(window.wlPlacesMobile.makeWrapper());	
			 			
	}

}

function loadHomePosts() {
	
	var errors = [  'PERMISSION_DENIED', 'POSITION_UNAVAILABLE', 'TIMEOUT' ];
	
	if (navigator.geolocation) {
	    navigator.geolocation.getCurrentPosition(function(position) {
	    	window.wlPlacesMobile.getPosts(position);
	    	    	    	
	    }, function(error) {
	    	window.wlPlacesMobile.getPostsByIpAddress('<?php echo $wlPlacesMobile->getRemoteIP() ?>');
	    		                 
	    },{timeout:20000});
	}else{
		window.wlPlacesMobile.getPostsByIpAddress('<?php echo $wlPlacesMobile->getRemoteIP() ?>');
	}
										
}

function hideAddressBar() {
	if (navigator.userAgent.match(/Android/i)) {
		window.scrollTo(0, 0); // reset in case prev not scrolled
		var nPageH = $(document).height();
		var nViewH = window.outerHeight;
		if (nViewH > nPageH) {
			nViewH = nViewH / window.devicePixelRatio;
			$('BODY').css('height', nViewH + 'px');
		}
		window.scrollTo(0, 1);
	} else {
		addEventListener('load', function() {
			setTimeout(hideURLbar, 0);
			setTimeout(hideURLbar, 1000);
		}, false);
	}
	function hideURLbar() {
		if (!pageYOffset) {
			window.scrollTo(0, 1);
		}
	}
	
	return this;
}



</script>
</head> 
<body>
<div data-role="page" id="home" data-theme="<?php echo $options['theme_swatch'];?>">
	<div data-role="header" data-theme="<?php echo $options['theme_swatch'];?>">
		<?php if($options['header_type'] == 'title'): ?>
		<h1><?php echo get_bloginfo('name'); ?></h1>
		<?php elseif($options['header_type'] == 'logo'): ?>
		<div style="width:100%;text-align:center;"><img src="<?php echo $options['mobile_logo_img']  ; ?>"/></div>
		<?php endif; ?>
	</div>
	<?php if($options['banner_type'] == 'image'): ?>
	<div id="wl_mobile_banner" class="wl_mobile_banner ui-bar-c">
		<img class="wl_mobile_banner_img" src="<?php echo $options['mobile_banner_img']  ; ?>"/> 
	</div>
	<?php endif; ?>
	<div data-role="content">
		<div id="wl_mobile_startup" style="width:100%; text-align:center;"><h3>Aquiring your location...</h3></div>		
		<div id="wl_home_status" class="wl_mobile_status"></div>	
		<div id="wl_mobile_wrapper"></div>
		<div id="wl_user_info" class="wl_user_info">
		<?php
		global $current_user;
		if(!empty($current_user->user_login)):?>
		logged in as <?php echo $current_user->user_login; ?>
		<?php else: ?>
		not logged in
		<?php endif; ?>	
		</div>
		<a href="#" id="wl_mobile_disable"  data-role="button" data-icon="delete" data-inline="false" class="ui-btn-right">Disable Mobile View</a>
	</div>
</div>

<div data-role="page" id="wl_placepost_details" data-theme="<?php echo $options['theme_swatch'];?>">
	<div data-role="header" data-theme="<?php echo $options['theme_swatch'];?>">
		<a href="#home" data-transition="slide" data-direction="reverse" data-icon="arrow-l">Back</a>
		<h1 id="wl_placepost_details_title">Details</h1>
	</div>
	<div data-role="content">
		<div id="wl_banner" class="wl_mobile_banner"></div>
		<div id="wl_place_post_content_status" class="wl_mobile_status"></div>
		<div id="wl_place_post_content_wrapper"></div>
		<a href="#" class="wl_post_actions"  id="wl_place_post_btn_post"  rel="external" data-ajax="false" data-role="button" data-icon="wl-places-mobile-post" data-inline="false">See Post</a>
		<a href="#" class="wl_post_actions" id="wl_place_post_btn_map" data-role="button" data-icon="wl-places-mobile-mapit"  data-inline="false" data-transition="flip">Map &amp; Contact</a>
		<a href="#" class="wl_post_actions" data-role="button" data-icon="wl-places-mobile-likeit"  data-inline="false" style="display:none">Like This Place</a>			
	</div>
</div>

<div data-role="page" id="wl_placepost_map" data-theme="<?php echo $options['theme_swatch'];?>">
	<div data-role="header" data-theme="<?php echo $options['theme_swatch'];?>">
		<a href="#wl_placepost_details" data-transition="flip" data-direction="reverse" data-icon="arrow-l">Back</a>
		<h1 id="wl_placepost_map_title">Map</h1>
	</div>
	<div data-role="content">
		<div id="wl_map_status"></div>
		<div id="wl_map_content"></div>	
		<div id="wl_map_buttons">
			<a href="#" class="wl_map_actions call" id="wl_place_post_btn_call" data-role="button" data-icon="wl-places-mobile-call"  data-inline="false" style="display:none">Call Now</a>
			<a href="#" class="wl_map_actions" id="wl_place_post_btn_web" data-role="button" data-icon="wl-places-mobile-web"  data-inline="false" style="display:none">Website</a>		
			<a href="#" class="wl_map_actions" id="wl_place_post_btn_directions" data-role="button" data-icon="wl-places-mobile-driving" data-inline="false" style="display:none">Directions</a>
		</div>		
	</div>
</div>

<div data-role="page" id="wl_error_page" data-rel="dialog" data-transition="pop">
	<div data-role="content">
		<div class="wl_error_page_area">
			<div class="wl_error_page_title">There was an error.</div>
			<div class="wl_error_page_message" id="wl_error_page_message"></div>
			<div class="wl_error_page_explain" id="wl_error_page_explain"></div>
			<a href="#" id="wl_mobile_disable_error" data-role="button" data-icon="delete" data-inline="false">Disable Mobile Browsing</a>
			<a href="#" id="wl_mobile_reload"  data-role="button" data-icon="refresh" data-inline="false">Refresh</a>			
		</div>	
	</div>
</div>

<?php if(!empty($options['google_analytics_account_id'])):  ?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $options['google_analytics_account_id'];  ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php endif;  ?>
</body>


</html>
