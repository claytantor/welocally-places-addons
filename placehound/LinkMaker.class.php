<?php
if (!class_exists('LinkMaker')) {
    
    class LinkMaker {
    	
    	function makeLinks($place){
    		$links = array();
    		return $links;
    	}
    	
    }
    
    global $linkMaker;
	$linkMaker = new LinkMaker();
	
}
?>