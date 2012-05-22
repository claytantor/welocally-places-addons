<?php
global $wlPlacesMobile;
?>
<html>
<head> 
	<title>Welocally Places Mobile</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
	<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/css/welocally-places-mobile.css' ?>" />
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>	
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
	<script src="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/js/welocally-places-mobile.js' ?>"></script>
	<script src="<?php echo WP_PLUGIN_URL.'/welocally-places-mobile/js/wl_base.js' ?>"></script>
	
<script type="text/javascript">
jQuery(document).ready(function(){
     navigator.geolocation.getCurrentPosition(
     	function(position){ 
     		jQuery('#wl_mobile_wrapper').append('Location found'); 
     		if(!window.wlPlacesMobile) {
     			window.wlPlacesMobile =
     				new WELOCALLY_PlacesMobile();
		
				window.wlPlacesMobile.initCfg({ ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>'});
				jQuery('#wl_mobile_wrapper').html(window.wlPlacesMobile.makeWrapper()); 
								
				window.wlPlacesMobile.getPosts(position);	   
     				
     		}
			  
     	}, 
     	function(){ jQuery('#wl_mobile_wrapper').append('Geolocation not supported');   });    
});	
</script>
</head>
<body>
<div data-role="page" id="home">
	<div data-role="header">
		<h1>Welocally Places Mobile</h1>
	</div>
	<div data-role="content">	
		<div id="wl_mobile_wrapper"></div>
	</div>
</div>

<div data-role="page" id="wl_placepost_details">
	<div data-role="header">
		<a href="#home" data-transition="slide" data-direction="reverse" data-icon="arrow-l">Back</a>
		<h1>Details</h1>
	</div>
	<div data-role="content">

		<div id="wl_place_post_content_wrapper"></div>
	
		<a href="#" data-role="button" data-icon="wl-places-mobile-likeit" data-iconpos="right">Like</a>
		<a href="#wl_placepost_map" data-role="button" data-icon="wl-places-mobile-mapit" data-transition="flip" data-iconpos="right">Map</a>
			
	</div>
</div>

<div data-role="page" id="wl_placepost_map">
	<div data-role="header">
		<a href="#wl_placepost_details" data-transition="flip" data-direction="reverse" data-icon="arrow-l">Back</a>
		<h1>Map</h1>
	</div>
	<div data-role="content">
		
	</div>
</div>


</body>
</html>