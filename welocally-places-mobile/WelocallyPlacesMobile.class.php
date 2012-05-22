<?php

if (!class_exists('WelocallyPlacesMobile')) { 

	class WelocallyPlacesMobile {
		const VERSION = '1.1.0';
		const OPTIONNAME = 'wl_places_mobile_options';

		function __construct() {
			$this->pluginDir		= basename(dirname(__FILE__));
			$this->pluginUrl 		= WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));			
			add_action( 'plugins_loaded', array(&$this, 'detectMobile') );
			add_action( 'template_redirect', array(&$this, 'showTemplate') );
			$this->detectMobile();			
		}
		
		public function loadDomainStylesScripts() {
			


		}
		
		public function loadAdminDomainStylesScripts() {
			

		}
		
		public function onActivate() {
			

		}
		
		public function detectMobile() {
			
			$container = $_SERVER['HTTP_USER_AGENT'];
			$this->mobile = false;
			$useragents = $this->getUserAgents();
			$exclude_agents =$this->getExcludedUserAgents();
			$devfile =  dirname(__FILE__) . '/include/developer.mode';
			foreach ( $useragents as $useragent ) {
				if ( preg_match( "#$useragent#i", $container ) || file_exists( $devfile ) ) {
					$this->mobile = true;
					break;
				}
			}
	
			if ( $this->mobile && !file_exists( $devfile ) ) {
				foreach( $exclude_agents as $agent ) {
					if ( preg_match( "#$agent#i", $container ) ) {	
						$this->mobile = false;
						break;
					}
				}
			}
			
		}
		
		/**
		 * location = the location for the search using haversine
		 * radius = radius of search
		 * 
		 * mean radius of 6,366.56 km (Å3,956 mi).
		 * 
		 * 1 LAT/LNG = 111KM = 68.9722023 mi
		 */
		public function geoSearch($loc, $dist, $units='km'){
			global $wpdb;
			
			//units
			$r= 6366.56;
			$cvrt = 111.0;
			if($units=='m'){
				$r= 3956.00;
				$cvrt = 69;
			}
			
			
		
			$lng1 = $loc['lng']-$dist/abs(cos(deg2rad($loc['lat']))*$cvrt);
			$lng2 = $loc['lng']+$dist/abs(cos(deg2rad($loc['lat']))*$cvrt);
			$lat1 = $loc['lat']-($dist/$cvrt);
			$lat2 = $loc['lat']+($dist/$cvrt);
						
			//this is a pretty complex query, we try to make it as fast as possible by making
			//a bounding though, alas this is the best way to do geo, maybe index the lat,lng fields?
			$querygeo = sprintf("SELECT ".
					$wpdb->prefix."posts.ID post_id, ".
					$wpdb->prefix."posts.post_title, ".
					$wpdb->prefix."wl_places.id, ".
					$wpdb->prefix."wl_places.wl_id, ".
					$wpdb->prefix."wl_places.lat, ".
					$wpdb->prefix."wl_places.lng, " .
					"%s * 2 * ASIN(SQRT( POWER(SIN((lat - %s) * ". 
			  "pi()/180 / 2), 2) + COS(lat * pi()/180) * COS(%s * pi()/180) * ". 
			  "POWER(SIN((lng - %s) * pi()/180 / 2), 2) )) rawgeosearchdistance ".  
			  "FROM ".$wpdb->prefix."wl_places,".$wpdb->prefix."wl_places_posts,".$wpdb->prefix."posts " .
			  "WHERE ".$wpdb->prefix."wl_places_posts.place_id = ".$wpdb->prefix."wl_places.id ".   
			  "AND ".$wpdb->prefix."wl_places_posts.post_id = ".$wpdb->prefix."posts.ID ".   
			  "AND ( lat between %s and %s ) ".  
			  "AND ( lng between %s and %s ) ".  
			  "HAVING rawgeosearchdistance < %s ". 
			  "ORDER BY rawgeosearchdistance ASC", 
			    $r,
			  	$loc['lat'],
			  	$loc['lat'],
			  	$loc['lng'],
			  	$lat1,
			  	$lat2,
			  	$lng1,
			  	$lng2,
			  	$dist); 
		
			  								
			$results = $wpdb->get_results($querygeo, OBJECT);
			foreach ( $results as $post ) {
       			$post->permalink = get_permalink( $post->post_id );
			}			
						
			return $results;
			
		}
		
		public function getPost($post_id){
			global $wpdb;
			 
			$query = sprintf("SELECT * FROM %s where ID=%d",$wpdb->prefix.'posts', $post_id);
							
			$results = $wpdb->get_results($query, OBJECT);
			$post = $results[0];
			
			if (has_post_thumbnail( $post->ID ) ){
				$images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
				$post->featured_image = $images[0];
			}
						
			return $post;
		}
		
		public function showTemplate() {	
			
			if($this->mobile && is_home()){
				ob_start();
	            include(dirname(__FILE__) . '/views/mobile-view.php');
	            $resultContent = ob_get_contents();
	            ob_end_clean();          
	            $t = null;
	            echo $resultContent;
	            exit;
			}	
		}
		
		public function getUserAgents(){
			return array(		
				"iPhone",  				 
				"iPod", 			
				"Android"		
			);
		}
		
		public function getExcludedUserAgents(){
			return array(		
				'CUPCAKE'	
			);
		}

	}
	
	global $wlPlacesMobile;
	$wlPlacesMobile = new WelocallyPlacesMobile();
}
?>
