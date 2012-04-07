<?php
if (!class_exists('PlaceHound')) {
	
	
    
    class PlaceHound {
    	
    	const ENV = 'DEV';
    	
    	function getConfigs(){
    		return $this->getConfigsForEnvironment(PlaceHound::ENV);
    	}
    	
    	function getConfigsForEnvironment($env){
    		$t = new StdClass();
    		if($env == 'DEV'){
    			$t->baseUrl = 'http://74.0.36.125/placehound' ;
    			$t->apiEndpoint = 'http://dev.welocally.com';
    		}
    		
    		return $t;
    	
    	}
     }
     
    
    global $placeHound;
	$placeHound = new PlaceHound();
	
}
?>