<?php

if (!class_exists('WelocallyPlacesMobile')) { 

	class WelocallyPlacesMobile {
		const VERSION = '1.1.0.BETA';
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
		
		function getRemoteIP ()
		{
		  
		    // check to see whether the user is behind a proxy - if so,
		    // we need to use the HTTP_X_FORWARDED_FOR address (assuming it's available)
		
		    if (strlen($_SERVER["HTTP_X_FORWARDED_FOR"]) > 0) { 
		
		      // this address has been provided, so we should probably use it
		
		      $f = $_SERVER["HTTP_X_FORWARDED_FOR"];
		
		      // however, before we're sure, we should check whether it is within a range 
		      // reserved for internal use (see http://tools.ietf.org/html/rfc1918)- if so 
		      // it's useless to us and we might as well use the address from REMOTE_ADDR
		
		      $reserved = false;
		
		      // check reserved range 10.0.0.0 - 10.255.255.255
		      if (substr($f, 0, 3) == "10.") {
		        $reserved = true;
		      }
		
		      // check reserved range 172.16.0.0 - 172.31.255.255
		      if (substr($f, 0, 4) == "172." && substr($f, 4, 2) > 15 && substr($f, 4, 2) < 32) {
		        $reserved = true;
		      }
		
		      // check reserved range 192.168.0.0 - 192.168.255.255
		      if (substr($f, 0, 8) == "192.168.") {
		        $reserved = true;
		      }
		
		      // now we know whether this address is any use or not
		      if (!$reserved) {
		        $ip = $f;
		      }
		
		    } 
		
		    // if we didn't successfully get an IP address from the above, we'll have to use
		    // the one supplied in REMOTE_ADDR
		
		    if (!isset($ip)) {
		      $ip = $_SERVER["REMOTE_ADDR"];
		    }
		
		    // done!
		    return $ip;
		
		}
		
		public function detectMobile() {
			
			$container = $_SERVER['HTTP_USER_AGENT'];
			$this->mobile = false;
			$useragents = $this->getUserAgents();
			$exclude_agents =$this->getExcludedUserAgents();
			$devfile =  dirname(__FILE__) . '/include/developer.mode';
			
			
			//we want to allow for testing by the site owner, or trials
			foreach ( $useragents as $useragent ) {
				if ( (preg_match( "#$useragent#i", $container ))) {
					$this->mobile = true;
					break;
				}
			}
	
			if ( $this->mobile) {
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
		public function geoSearch($loc, $dist, $units='km', $max=10, $post_type='post'){
			global $wpdb,$wlPlaces;
			
			
			//units
			$r= 6366.56;
			$cvrt = 111.0;
			if($units=='mi'){
				$r= 3956.00;
				$cvrt = 69;
			}
			
			$post_type = explode(",",$post_type);

			$post_type_and = '1=1';
			if (is_array($post_type) && !empty($post_type)) {
				$post_type_and = "{$wpdb->posts}.post_type IN ('" . implode('\',\'', $post_type) . "')";
			} elseif (is_string($post_type)) {
				$post_type_and = "{$wpdb->posts}.post_type = '" . $wpdb->escape($post_type) . "'";
			}
				
			$lng1 = $loc['lng']-$dist/abs(cos(deg2rad($loc['lat']))*$cvrt);
			$lng2 = $loc['lng']+$dist/abs(cos(deg2rad($loc['lat']))*$cvrt);
			$lat1 = $loc['lat']-($dist/$cvrt);
			$lat2 = $loc['lat']+($dist/$cvrt);
						
			//"AND ".$wpdb->prefix."posts.post_status = 'publish' AND " . $post_type_and . 
			//this is a pretty complex query, we try to make it as fast as possible by making
			//a bounding though, alas this is the best way to do geo, maybe index the lat,lng fields?
			$querygeo = sprintf("SELECT ".
					$wpdb->prefix."posts.ID post_id, ".
					$wpdb->prefix."posts.post_title, ".
					$wpdb->prefix."wl_places.id, ".
					$wpdb->prefix."wl_places.place, ".
					$wpdb->prefix."wl_places.wl_id, ".
					$wpdb->prefix."wl_places.lat, ".
					$wpdb->prefix."wl_places.lng, " .
					"%s * 2 * ASIN(SQRT( POWER(SIN((lat - %s) * ". 
			  "pi()/180 / 2), 2) + COS(lat * pi()/180) * COS(%s * pi()/180) * ". 
			  "POWER(SIN((lng - %s) * pi()/180 / 2), 2) )) rawgeosearchdistance ".  
			  "FROM ".$wpdb->prefix."wl_places,".$wpdb->prefix."wl_places_posts,".$wpdb->prefix."posts " .
			  "WHERE ".$wpdb->prefix."wl_places_posts.place_id = ".$wpdb->prefix."wl_places.id ".   
			  "AND ".$wpdb->prefix."wl_places_posts.post_id = ".$wpdb->prefix."posts.ID ". 
			  "AND ".$wpdb->prefix."posts.post_status = 'publish' AND " . $post_type_and .  
			  "AND ( lat between %s and %s ) ".  
			  "AND ( lng between %s and %s ) ".  
			  "HAVING rawgeosearchdistance < %s ". 
			  "ORDER BY rawgeosearchdistance ASC LIMIT 0,%d", 
			    $r,
			  	$loc['lat'],
			  	$loc['lat'],
			  	$loc['lng'],
			  	$lat1,
			  	$lat2,
			  	$lng1,
			  	$lng2,
			  	$dist,
			  	$max); 
			  	
			syslog(LOG_WARNING,print_r($querygeo,true));
					  								
			$results = $wpdb->get_results($querygeo, OBJECT);
			foreach ( $results as $post ) {
       			//$post->permalink = get_permalink( $post->post_id );
       			
       			$cat_ids = wp_get_post_categories( $post->post_id );
       			$cats_filtered = array();
       			foreach ( $cat_ids as $term_id ) {
       				if($term_id != $wlPlaces->placeCategory()){
       					$term = get_term( $term_id, 'category');
       					array_push( $cats_filtered, $term->name);
       				}
       				
       			}
       			      			
       			$post->categories = $cats_filtered;
       			$placeJSON = $post->place;
       			$post_place = json_decode($placeJSON);   
       			$post->place_name= $post_place->properties->name;   			
			}
		
						
			return $results;
			
		}
		
		public function getPostPlace($post_id, $wl_id){
			global $wpdb;
			

			$query = "SELECT ".$wpdb->prefix."posts.ID, ".$wpdb->prefix."posts.post_content, ".$wpdb->prefix."wl_places.place ".
			"FROM ".$wpdb->prefix."posts, ".$wpdb->prefix."wl_places,".$wpdb->prefix."wl_places_posts ".
			"WHERE ".$wpdb->prefix."posts.ID=".$wpdb->prefix."wl_places_posts.post_id ".
			"AND ".$wpdb->prefix."wl_places.id=".$wpdb->prefix."wl_places_posts.place_id ".
			"AND ".$wpdb->prefix."wl_places.wl_id='".$wl_id."' ".
			"AND ".$wpdb->prefix."posts.ID=".$post_id;

							
			$results = $wpdb->get_results($query, OBJECT);
			//file_put_contents(dirname(__FILE__) . "/sql_result.log", print_r($results,true));
			$post = $results[0];
			
			if (has_post_thumbnail( $post->ID ) ){
				$images = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 
							'single-post-thumbnail' );
				$post->featured_image = $images[0];
			}
							
			$post->post_content = $this->getSafeExcerpt($post->post_content, 300);
			$post->permalink = get_permalink( $post->ID ); 	
			$post->place = json_decode( $post->place ); 		
			return $post;
		}
		
		
		function writeStream($fp, $string) {
		    for ($written = 0; $written < strlen($string); $written += $fwrite) {
		        $fwrite = fwrite($fp, substr($string, $written));
		        if ($fwrite === false) {
		            return $written;
		        }
		    }
		    return $written;
		}
		
		
		function getSafeExcerpt($content, $max_length=350) {
			$content = strip_tags( $content );
			$content = $this->stripSelectedShortcodes($content);
			return $this->utf8Truncate( $content, $max_length);
		}
		
		function stripSelectedShortcodes($text)
		{ // Strips specific start and end shortcodes tags. Preserves contents.
		    return preg_replace('%
		        # Match an opening or closing WordPress tag.
		        \[/?                 # Tag opening "[" delimiter.
		        (?:                  # Group for Wordpress tags.
		          welocally     	 # Add other tags separated by |
		        )\b                  # End group of tag name alternative.
		        (?:                  # Non-capture group for optional attribute(s).
		          \s+                # Attributes must be separated by whitespace.
		          [\w\-.:]+          # Attribute name is required for attr=value pair.
		          (?:                # Non-capture group for optional attribute value.
		            \s*=\s*          # Name and value separated by "=" and optional ws.
		            (?:              # Non-capture group for attrib value alternatives.
		              "[^"]*"        # Double quoted string.
		            | \'[^\']*\'     # Single quoted string.
		            | [\w\-.:]+      # Non-quoted attrib value can be A-Z0-9-._:
		            )                # End of attribute value alternatives.
		          )?                 # Attribute value is optional.
		        )*                   # Allow zero or more attribute=value pairs
		        \s*                  # Whitespace is allowed before closing delimiter.
		        /?                   # Tag may be empty (with self-closing "/>" sequence.
		        \]                   # Opening tag closing "]" delimiter.
		        %six', '', $text);
		}
		
		function utf8Truncate($string, $length = 200, $etc = '...', $break_words = false)
		{
		    if($length == 0)
		        return '';
		
		    if(strlen($string) > $length)
		    {
		        $length -= strlen($etc);
		
		        $splitallowed = FALSE;
		        while(!$splitallowed)
		        {
		            $utf8type = $this->utf8charactertype(substr($string, $length, 1));
		            switch($utf8type)
		            {
		                case '0':
		                case '1':
		                    $splitallowed = TRUE;
		                    break;
		                case '2':
		                    $length--;
		                    break;
		            }
		        }
		
		        if(!$break_words)
		        {
		            $splitallowed = FALSE;
		            while(!$splitallowed && $length)
		            {
		                $char = substr($string, $length, 1);
		                $utf8type = $this->utf8charactertype($char);
		                switch($utf8type)
		                {
		                    case '0':
		                        if($char == ' ')
		                        {
		                            $splitallowed = TRUE;
		                            $length++;
		                        }
		                        else
		                        {
		                            $length--;
		                        }
		                        break;
		                    case '1':
		                        $splitallowed = TRUE;
		                        $length++;
		                    case '2':
		                        $length--;
		                        break;
		                }
		            }
		        }
		
		        return substr($string, 0, $length) . $etc;
		    }
		    else
		    {
		        return $string;
		    }
		}
		
		function utf8charactertype($char)
		{
		    if(ord($char) < 128) return 0;
		    $charbin = decbin(ord($char));
		    if(substr($charbin, 0, 2) == '11') return 1;
		    if(substr($charbin, 0, 2) == '10') return 2;
		}
		
					
		public function showTemplate() {
			
			global $wlPlaces;
									
			if( $this->showMobileTemplate() ){
				
				$options = $this->getOptions();
				
				//map style
				$mapstyle = dirname( __FILE__ ) . '/themes/'.$options['mobile_theme' ].
					'/mapstyle.json';				
				if (file_exists($mapstyle)) {
					$mapstyle_json = file_get_contents($mapstyle);		    
				} 
				
				//custom markers
				$maps_markers = dirname( __FILE__ ) . '/themes/'.$options['mobile_theme' ].
					'/images/marker_all_base.png';
				if (file_exists($maps_markers)) {
					$maps_markers = $this->pluginUrl. '/themes/'.$options['mobile_theme' ].
					'/images/marker_all_base.png';	    
				} else {
					$maps_markers = $wlPlaces->pluginUrl. '/resources/images/marker_all_base.png';
				}
								
				ob_start();
	            include(dirname(__FILE__) . '/views/mobile-view.php');
	            $resultContent = ob_get_contents();
	            ob_end_clean();          
	            $t = null;
	            echo $resultContent;
	            exit;
			}	
		}
		
		private function showMobileTemplate(){
			global $current_user;
			$options = $this->getOptions();
			
			if (!empty($_COOKIE['wl_mobile_enabled'])) 
				return false;
			
			if(!empty($options['allowed_user_list'])) {
				$user_list = $pieces = explode(",", $options['allowed_user_list']);
			}
						
			if($options['allow_users'] == 'dev' && is_home())
				return true;
			else if($this->mobile && $options['allow_users'] == 'all' && is_home())
				return true;
			else if($this->mobile && $options['allow_users'] == 'list' 
					&& $this->userMobileAllowed($current_user, $user_list) 
					&& is_home())
				return true;
			else
				return false;
			
		}
		
		private function userMobileAllowed($current_user, $user_list){
			if(isset($current_user))
			{
				foreach ( $user_list as $username ) {
					if($current_user == trim($username))
						return true;      
				}				
			} 
			return false;
		}
		
		
		public function getUserAgents(){
			return array(		
				"iPhone",  				 
				"iPod", 			
				"Android",
				"incognito", 			
				"webmate", 				
				"dream", 				 	
				"CUPCAKE", 			 	
				"blackberry9500",	 	
				"blackberry9530",	 	
				"blackberry9520",	 	
				"blackberry9550",	 	
				"blackberry9800",	
				"Googlebot-Mobile"			
			);
		}
		
		public function getExcludedUserAgents(){
			return array(			
			);
		}
		
		/// OPTIONS DATA
		//--------------------------------------------
        public function getOptions() {
            $options = get_option(WelocallyPlacesMobile::OPTIONNAME, array());
            
            $default_mobile_theme = 'welocally'; 
            $default_mobile_logo_img = $this->pluginUrl. "/images/mobile_default_logo.png";
            $default_mobile_banner_img = $this->pluginUrl. "/images/mobile_default_banner.png";
            $default_search_units = 'kilometers';
            $default_search_radius = '15';
            $default_results_max = '10';           
            $default_theme_swatch = 'A';
            $default_header_type = 'title';
            $default_banner_type = 'none';
            $default_allow_users = 'none';
            $default_mobile_post_types = 'post';
            	
   			//defaults          
            if ( !array_key_exists( 'mobile_theme', $options ) ) { $options[ 'mobile_theme' ] = $default_mobile_theme; $changed = true; }
            if ( !array_key_exists( 'mobile_logo_img', $options ) ) { $options[ 'mobile_logo_img' ] = $default_mobile_logo_img; $changed = true; }
            if ( !array_key_exists( 'mobile_banner_img', $options ) ) { $options[ 'mobile_banner_img' ] = $default_mobile_banner_img; $changed = true; }
            if ( !array_key_exists( 'search_units', $options ) ) { $options[ 'search_units' ] = $default_search_units; $changed = true; }
            if ( !array_key_exists( 'search_radius', $options ) ) { $options[ 'search_radius' ] = $default_search_radius; $changed = true; }
            if ( !array_key_exists( 'results_max', $options ) ) { $options[ 'results_max' ] = $default_results_max; $changed = true; }
            if ( !array_key_exists( 'theme_swatch', $options ) ) { $options[ 'theme_swatch' ] = $default_theme_swatch; $changed = true; }
            if ( !array_key_exists( 'header_type', $options ) ) { $options[ 'header_type' ] = $default_header_type; $changed = true; }
            if ( !array_key_exists( 'banner_type', $options ) ) { $options[ 'banner_type' ] = $default_banner_type; $changed = true; }
            if ( !array_key_exists( 'allow_users', $options ) ) { $options[ 'allow_users' ] = $default_allow_users; $changed = true; }
            if ( !array_key_exists( 'mobile_post_types', $options ) ) { $options[ 'mobile_post_types' ] = $default_mobile_post_types; $changed = true; }
            
                      	    	
			$this->latestOptions = $options;
		            
            return $this->latestOptions;
        }

		public function getSingleOption( $key ) {
			$options = $this->getOptions();
			return $options[$key];
		}
		        
        public function saveOptions($options) {
           update_option(WelocallyPlacesMobile::OPTIONNAME, $options);
           $this->latestOptions = $options;
        }
        
        public function deleteOptions() {
            delete_option(WelocallyPlacesMobile::OPTIONNAME);
        }

	}
	
	global $wlPlacesMobile;
	$wlPlacesMobile = new WelocallyPlacesMobile();
}
?>
