<?php
global $wlPlaces; 
$options = $wlPlaces->getOptions(); 
$marker_image_path = WP_PLUGIN_URL.'/welocally-places/resources/images/marker_all_base.png' ;
?>
 <div class="wl_category_container_tag"> 
 	<div id="wl_places_multi_wrapper_<?php echo $t->uid; ?>"></div>
    <div id="wl_category_script_<?php echo $t->uid; ?>">
      <script type="text/javascript">
//<![CDATA[
		
		//the selected display
		var placeSelected_<?php echo $t->uid; ?> = new WELOCALLY_PlaceWidget();
		
		//the multi map
		var placesMulti_<?php echo $t->uid; ?> = 
			  new WELOCALLY_PlacesMultiWidget(); 
			  
			  
		placesMulti_<?php echo $t->uid; ?>.initCfg({ 
				id:'multi_<?php echo $t->uid; ?>',
				imagePath:'<?php echo($marker_image_path); ?>',
				showSelection: <?php echo $t->showSelection; ?>,
				showLetters: <?php echo $t->showLetters; ?>,
				<?php if(isset($options['category_selector_override'])):?> overrideSelectableStyle:<?php echo('\''.$options['category_selector_override'].'\''.','); endif;?>
				observers:[placeSelected_<?php echo $t->uid; ?>]		
		});
			
		jQuery('#wl_places_multi_wrapper_<?php echo $t->uid; ?>').html(placesMulti_<?php echo $t->uid; ?>.makeWrapper());						
					
		//setup the selected area   
		placeSelected_<?php echo $t->uid; ?>.initCfg({ imagePath:'<?php echo($marker_image_path); ?>', hidePlaceSectionMap: true });
		placesMulti_<?php echo $t->uid; ?>.getSelectedArea().append(placeSelected_<?php echo $t->uid; ?>.makeWrapper());
		
		//this is the cloud google search, shows all places in sheet
		var placesSearch_<?php echo $t->uid; ?> = new WELOCALLY_GoogleDocsSearch({
			key: '<?php echo $t->collectionKey; ?>',
			observers: [placesMulti_<?php echo $t->uid; ?>]
			}).init();
		
		//get places from the google spreadsheet
		placesSearch_<?php echo $t->uid; ?>.search();		
        
	 
      //]]>
      </script>
    </div>
  </div>
