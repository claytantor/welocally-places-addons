//http://localhost:8082/geodb/place/1_0/WL_3Wnkj5RxX8iKzTR5qek2Fs_37.826065_-122.209171@1293134755.json
function WELOCALLY_PlaceWidget (cfg) {	
	
	this.selectedSection;
	this.cfg;
	this.wrapper;
	this.map_canvas;
	
	this.init = function() {
		
		var error;
		if (!cfg) {
			error = "Please provide configuration for the widget";
			cfg = {};
		}
		
		// summary (optional) - the summary of the article
		// hostname (optional) - the name of the host to use
		if (!cfg.endpoint) {
			cfg.endpoint = 'http://stage.welocally.com';
		}
		
		if (!cfg.imagePath) {
			cfg.imagePath = 'http://placehound.com/images';
		}
		
		if (!cfg.zoom) {
			cfg.zoom = 16;
		}
		
		if (!cfg.showShare) {
			cfg.showShare = false;
		}
		
		//look in query string
		if (!cfg.id) {
			cfg.id = WELOCALLY.util.getParameter(
					window.top.location.search.substring(1),
					'id');
			console.log(cfg.id);
		}
			
		this.cfg = cfg;
		
		// Get current script object
		var script = jQuery('SCRIPT');
		script = script[script.length - 1];

		// Build Widget
		this.wrapper = jQuery('<div></div>');
		jQuery(this.wrapper).css('display','none');			
		jQuery(this.wrapper).attr('class','welocally_place_widget');
		jQuery(this.wrapper).attr('id','welocally_place_widget_'+this.cfg.id);
		
		//google maps does not like jquery instances
		this.map_canvas = document.createElement('DIV');
		jQuery(this.map_canvas).css('display','none');	
	    jQuery(this.map_canvas).attr('class','welocally_place_widget_map_canvas');
		jQuery(this.map_canvas).attr("id","wl_place_map_canvas_widget_"+cfg.id);
		jQuery(this.wrapper).append(this.map_canvas);
				
		this.load(this.map_canvas);
	
		jQuery(script).parent().before(this.wrapper);
		
		return this;
					
	};
	
}

WELOCALLY_PlaceWidget.prototype.loadWithWrapper = function(cfg, map_canvas, wrapper) {
	this.cfg = cfg;
	this.wrapper = wrapper;
	jQuery(this.wrapper).html(map_canvas);
	this.load(map_canvas);
	return this;
};

WELOCALLY_PlaceWidget.prototype.load = function(map_canvas) {
	var _instance = this;
	
	if(WELOCALLY.util.startsWith(_instance.cfg.id,"WL_")){			
		var surl = _instance.cfg.endpoint +
		'/geodb/place/1_0/'+_instance.cfg.id+'.json?callback=?';
		
		console.log('loading place:'+surl);
		
		jQuery.ajax({
			url: surl,
			dataType: "json",
			success: function(data) {
				_instance.initMapForPlace(data[0],map_canvas);
				_instance.show(data[0]);
			},
			error: function() {
			}
		});
	}		
};	


WELOCALLY_PlaceWidget.prototype.initMapForPlace = function(place, map_canvas) {
	
	var _instance = this;
	
	var latlng = new google.maps.LatLng(
			place.geometry.coordinates[1], 
			place.geometry.coordinates[0]);
    
	var options = {
      center: latlng,
      zoom: _instance.cfg.zoom,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
    if(_instance.cfg.styles){
		options.styles = _instance.cfg.styles;
	}
    
    var map = new google.maps.Map(
    	map_canvas, 
    	options);
    
    var marker = new google.maps.Marker({
    	position: latlng,
    	map: map,
    	icon: _instance.cfg.imagePath+'/marker_search.png'
      });
        
    jQuery(map_canvas).show();
    return map;

};


WELOCALLY_PlaceWidget.prototype.show = function(selectedPlace) {
	
	jQuery(this.wrapper)
	.append('<div class="wl_selected_name">'+selectedPlace.properties.name+'</div>')
	.append('<div class="wl_selected_adress">'+selectedPlace.properties.address+' '+
		selectedPlace.properties.city+
		' '+selectedPlace.properties.province+
		' '+selectedPlace.properties.postcode+'</div>');
	
	if(selectedPlace.properties.phone != null) {
		jQuery(this.wrapper)
			.append('<div class="wl_selected_phone">'+selectedPlace.properties.phone+'</div>');
	}

	//make the link items
	var links = jQuery('<div id="wl_place_links" class="wl_place_links"></div>');
	
	if(selectedPlace.properties.website != null && 
		selectedPlace.properties.website != '' ) {
		var website = selectedPlace.properties.website;
		if(selectedPlace.properties.website.indexOf('http://') == -1) {
			website = 'http://'+selectedPlace.properties.website;				
		}
		
		jQuery(links)
			.append('<div class="wl_selected_web_link"><a target="_new" href="'+website+'">'+website+'</a></div>');

	} 

	if(selectedPlace.properties.city != null && 
		selectedPlace.properties.province != null){
			var qS = selectedPlace.properties.city+" "+
				selectedPlace.properties.province;
			if(selectedPlace.properties.address != null)
				qs=selectedPlace.properties.address+" "+qS;
			if(selectedPlace.properties.postcode != null)
				qs=qs+" "+selectedPlace.properties.postcode;
			var qVal = qs.replace(" ","+");
			
			jQuery(links)
			.append('<div class="wl_selected_driving_link"><a href="http://maps.google.com/maps?f=d&source=s_q&hl=en&geocode=&q='+
				qVal+'" target="_new">Directions</a></div>');		
	}
	
	//embed wrapper
	var embed = jQuery('<div id="wl_place_embed" class="wl_place_embed"></div>');
	if(!this.cfg.showShare){
		jQuery(embed).hide();
	}
	
	//embed link
	var shareToggle = jQuery('<div class="wl_selected_embed_link"></div>');
	var shareLink = jQuery('<a href="#" target="_new">Share</a>');
	jQuery(shareLink).click(function() {
		jQuery(embed).toggle();
		return false;
	});
	jQuery(shareToggle).append(shareLink);
	jQuery(links).append(shareToggle);
	
	jQuery(this.wrapper).append(links);
	
	
	//tag
	//explain it
	var wlSelectedTagArea = jQuery('<div id="wl_place_widget_tag" class="wl_selected_tag"></div>');	
	var title = jQuery('<div class="wl_place_title">Use this tag to share with <a href="http://welocally.com/?page_id=2" target="_new">Welocally Places</a> for <a href="http://wordpress.org/extend/plugins/welocally-places/" target="_new">WordPress</a></div>');
	jQuery(wlSelectedTagArea).append(title);
	
	//the tag
	var tag = '[welocally id="'+selectedPlace._id+'" /]';
	var inputAreaTag = jQuery('<input/>');
	jQuery(inputAreaTag).val(tag);	
	jQuery(wlSelectedTagArea).append(inputAreaTag);
	
	jQuery(embed).append(wlSelectedTagArea); 
		
	//javascript
	var wlSelectedScriptArea = jQuery('<div id="wl_place_widget_script" class="wl_selected_script"></div>');	
	var title = jQuery('<div class="wl_place_title">Use this script to embed on any jQuery enabled site.</div>');
	jQuery(wlSelectedScriptArea).append(title);
	
	//the script
	var script = '<script src="http://placehound.com/javascripts/wl_place_widget.js" type="text/javascript"/>\n<script type="text/javascript"/>\n WELOCALLY_PlaceWidget({id:"'+selectedPlace._id+'"}).init();\n</script>';
	var inputAreaScript = jQuery('<textarea/>');
	jQuery(inputAreaScript).val(script);	
	jQuery(wlSelectedScriptArea).append(inputAreaScript);
	
	
	jQuery(embed).append(wlSelectedScriptArea); 
	jQuery(this.wrapper).append(embed); 
	
	
	
	
	jQuery(this.wrapper).show();
	                        
};