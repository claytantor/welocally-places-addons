<?php
global $wlPlaces;
$options = $wlPlaces->getOptions();
$marker_image_path = WP_PLUGIN_URL.'/welocally-places/resources/images/marker_all_base.png' ;

?>

<script type="text/javascript">
jQuery(document).ready(function() {	
	

});
</script>

<div id="wl-place-content-<?php echo $t->uid; ?>" class="wl-place-content">
	<div class="template-wrapper">
		<div id="wl_place_widget_wrapper_<?php echo $t->uid; ?>"></div>
		<div>
		<script type="text/javascript" charset="utf-8">
		
		
		var placeWidget_<?php echo $t->uid; ?> = 
			  new WELOCALLY_PlaceWidget();
		  
		placeWidget_<?php echo $t->uid; ?>.initCfg({ 
			id:  'place_<?php echo $t->uid; ?>', 
			imagePath:'<?php echo($marker_image_path); ?>',
		});
		jQuery('#wl_place_widget_wrapper_<?php echo $t->uid; ?>').html(placeWidget_<?php echo $t->uid; ?>.makeWrapper());						
		  
		var loader_<?php echo $t->uid; ?> = new WELOCALLY_GoogleDocsLoad({			
				row: <?php echo $t->placeId; ?>,
				key: '<?php echo $t->collectionKey; ?>',
				observers: [placeWidget_<?php echo $t->uid; ?>]
			}).init();
		
		loader_<?php echo $t->uid; ?>.load();
		
		</script>
		</div>
	</div>

</div>
