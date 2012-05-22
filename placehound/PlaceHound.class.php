<?php
if (!class_exists('PlaceHound')) {
    
    class PlaceHound {
    	
    	const VERSION = '0.8.3';
    	
    	function getConfigs(){
    		return $this->getConfigsForEnvironment(Env::ENV);
    	}
    	
    	function getConfigsForEnvironment($env){
    		$t = new StdClass();
    		if($env == 'DEV'){
    			$t->baseUrl = 'http://gaudi.welocally.com/placehound' ;
    			$t->apiEndpoint = 'http://stage.welocally.com';
    			$t->googleMapsKey = 'AIzaSyACXX0_pKBA6L0Z2ajyIvh5Bi8h9crGVlg';
    			$t->disqusShortname = 'placehounddev';
    		} else if($env == 'STAGE'){
    			$t->baseUrl = 'http://stage.welocally.com/placehound' ;
    			$t->apiEndpoint = 'http://stage.welocally.com';
    			$t->googleMapsKey = 'AIzaSyACXX0_pKBA6L0Z2ajyIvh5Bi8h9crGVlg';
    			$t->disqusShortname = 'placehoundstage';
    		} else if($env == 'PROD'){
    			$t->baseUrl = 'http://placehound.com' ;
    			$t->apiEndpoint = 'http://stage.welocally.com';
    			$t->googleMapsKey = 'AIzaSyACXX0_pKBA6L0Z2ajyIvh5Bi8h9crGVlg';
    			$t->disqusShortname = 'placehound';
    		} 
    		
    		return $t;
    	
    	}
    	
    	function curPageURL() {
		 $pageURL = 'http';
		 //if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
		}
    	
    	function getMetaForPlace($place){
    		$t = new StdClass();
    		$t->tags = array();
    		$meta = '';
    		
    		if(!empty($place->properties->name)){
    			$keywords = preg_split("/[\s,]+/", $place->properties->name);
    			foreach ( $keywords as $keyword )
    			{
    				array_push($t->tags, $keyword);
    				$meta = $meta.' '.$keyword; 
    			}   				
    		} 
    		
    		foreach ( $place->properties->classifiers as $classifier ) {
    			if(!empty($classifier->type)){
    				array_push($t->tags, $classifier->type);
    				$meta = $meta.' '.$classifier->type; 
    			} 
    			
    			if(!empty($classifier->category)){
    				array_push($t->tags, $classifier->category);
    				$meta = $meta.' '.$classifier->category; 
    			} 
    			
    			if(!empty($classifier->subcategory)){
    				array_push($t->tags, $classifier->subcategory);
    				$meta = $meta.' '.$classifier->subcategory; 
    			}    			
    		}
    	 	$t->meta = trim($meta, " ");
    	 	
			return $t;
    		
    	}
    	

     }
        
    global $placeHound;
	$placeHound = new PlaceHound();
	
}
?>