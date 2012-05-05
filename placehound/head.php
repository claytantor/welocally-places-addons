<?php
	include (dirname(__FILE__) . "/Env.class.php");	
	include (dirname(__FILE__) . "/PlacehoundNetwork.class.php");	
	include (dirname(__FILE__) . "/PlaceHound.class.php");
	global $placehoundNetwork, $placeHound;
	$t = $placeHound->getConfigs(); 
	if(isset( $_GET["q"])){
		$q =  $_GET["q"];
		$title = 'PlaceHound - '.$q;
		$meta = $q;
	}
	
	if(isset( $_GET["id"])){
		$id = $_GET["id"];
		$placeJson = $placehoundNetwork->wl_do_curl_get($t->apiEndpoint.'/geodb/place/1_0/'.$id.'.json');
		$place = json_decode($placeJson);
		$meta = $placeHound->getMetaForPlace($place[0]);
		$title = $place[0]->properties->name.' - '.$place[0]->properties->address.' '.$place[0]->properties->city.' '.$place[0]->properties->province;  
		$metall = '';
		foreach($meta as $word){
			$metall = $metall.' '.$word;
		}
	}	
	
	
?>
<head>
  	<?php if(!isset($title)):?><title>Place Hound</title><?php else: ?><title><?php echo($title); ?></title><?php endif;?>
  	<META itemprop="description" NAME="description" CONTENT="<?php if(!isset($metall)){ echo 'Placehound is just a simple way to find, share and discuss places.'; } else { echo($metall); } ?>">
  	
  	<meta property="og:image" content="<?php echo($t->baseUrl.'/images/phound02.png'); ?>"/> 
	
  	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/smoothness/jquery-ui.css"  type="text/css" />
  	
  	<link rel="stylesheet" href="stylesheets/blueprint/screen.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="stylesheets/blueprint/print.css" type="text/css" media="print">
	<link rel="stylesheet" href="stylesheets/placehound.css" type="text/css" />
	<link rel="stylesheet" href="stylesheets/sticky-footer.css" type="text/css" media="screen">
  	
  	<link rel="stylesheet" href="stylesheets/<?php echo(PlaceHound::VERSION); ?>/wlp_placehound/wl_places.css" type="text/css" />
  	<link rel="stylesheet" href="stylesheets/<?php echo(PlaceHound::VERSION); ?>/wlp_placehound/wl_places_place.css" type="text/css" />
  	<link rel="stylesheet" href="stylesheets/<?php echo(PlaceHound::VERSION); ?>/wlp_placehound/wl_places_finder.css" type="text/css" />
  	<link rel="stylesheet" href="stylesheets/<?php echo(PlaceHound::VERSION); ?>/wlp_placehound/wl_places_multi.css" type="text/css" />
  	 	
  	
  	<link href='https://fonts.googleapis.com/css?family=Fresca|Asap' rel='stylesheet' type='text/css'>
  	<link rel="icon" type="image/png" href="images/hound_icon.png">  	
  	<script src="https://maps.google.com/maps/api/js?key=<?php echo($t->googleMapsKey); ?>&sensor=true&language=en"
	  	type="text/javascript"></script>
    <script src="javascripts/<?php echo(PlaceHound::VERSION); ?>/wl_base.js" type="text/javascript"></script>   
    <script src="javascripts/<?php echo(PlaceHound::VERSION); ?>/wl_place_widget.js" type="text/javascript"></script>
    <script src="javascripts/<?php echo(PlaceHound::VERSION); ?>/wl_dealfinder_widget.js" type="text/javascript"></script> 
    <script src="javascripts/<?php echo(PlaceHound::VERSION); ?>/wl_placefinder_widget.js" type="text/javascript"></script>
    <script src="javascripts/<?php echo(PlaceHound::VERSION); ?>/wl_places_multi_widget.js" type="text/javascript"></script>
    <script src="javascripts/<?php echo(PlaceHound::VERSION); ?>/wl_infobox.js" type="text/javascript"></script>
    <script src="javascripts/<?php echo(PlaceHound::VERSION); ?>/wl_place_selection_listener.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" 
    	type="text/javascript"></script>
	<script src=
		"https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type=
		"text/javascript"></script>
	<script type="text/javascript" src="http://openlike.org/v1/openlike.js"></script>
		


</head>