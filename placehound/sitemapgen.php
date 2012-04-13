<?php 
	include (dirname(__FILE__) . "/PlacehoundNetwork.class.php");	
	include (dirname(__FILE__) . "/PlaceHound.class.php");
	global $placehoundNetwork, $placeHound;
	$t =$placeHound->getConfigs(); 
	header('Content type: text/xml');
?>    
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

	<url>
		<loc>http://placehound.com</loc>
		<priority>0.95</priority>
	</url>
	<url>
		<loc>http://placehound.com/index.html</loc>
		<priority>0.85</priority>
	</url>	
	<url>
		<loc>http://placehound.com/types.html</loc>
		<priority>0.5</priority>
	</url>
</urlset>