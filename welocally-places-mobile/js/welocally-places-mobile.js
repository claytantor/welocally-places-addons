/*
 * copyright 2012 welocally. NO WARRANTIES PROVIDED
 */
function WELOCALLY_PlacesMobile(cfg) {
	this.cfg;
	this.wrapper;

	this.init = function() {
		return this;
		
	};

};

WELOCALLY_PlacesMobile.prototype.initCfg = function(cfg) {
	var errors = [];
	if (!cfg) {
		errors.push("Please provide configuration for the widget");
		cfg = {};
	}
	
	if (!cfg.units) {
		cfg.units = 'kilometers';
	}
	
	if (!cfg.dist) {
		cfg.dist = '100';
	}

	if (errors.length > 0)
		return errors;

	this.cfg = cfg;
};

WELOCALLY_PlacesMobile.prototype.makeWrapper = function(){
	
	var _instance = this;
	
	var wrapper = jQuery('<div></div>');
		
	var postlinks = jQuery('<ul class="wl-listview" style="margin-top: 0px; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" id="wl_places_mobile_postlinks" data-role="listview" data-inset="true" data-filter="true" data-filter-theme="'+_instance.cfg.filterTheme+'"></ul>');
	jQuery(wrapper).append(postlinks);
	
	var listviewStatus = jQuery('<div class="ui-shadow-inset ui-overlay-c wl_places_listview_status" id="wl_listview_results_status" style="display:none"></div>');
	jQuery(wrapper).append(listviewStatus);
			
	this.wrapper = wrapper;
	return wrapper;
	
};

WELOCALLY_PlacesMobile.prototype.getPosts = function(position){
		
	var _instance=this; 
	
	var units = 'km';
	if(_instance.cfg.units == 'miles')
		units = 'mi';
	
	var data = {
			action: 'get_posts',
			lat: position.coords.latitude,
			lng: position.coords.longitude,
			units: units,
			dist: _instance.cfg.dist
	};
	
	jQuery.mobile.showPageLoadingMsg();
		   
	_instance.jqxhr = jQuery.ajax({
	  type: 'POST',		  
	  url: _instance.cfg.ajaxurl,
	  data: data,
	  beforeSend: function(jqXHR){
		_instance.jqxhr = jqXHR;
	  },
	  error : function(jqXHR, textStatus, errorThrown) {
		  jQuery.mobile.hidePageLoadingMsg();
		  if(textStatus != 'abort'){
			WELOCALLY.ui.setStatus(_instance.statusArea,'ERROR : '+textStatus, 'error', false);
		  }		
	  },		  
	  success : function(data, textStatus, jqXHR) {
		jQuery.mobile.hidePageLoadingMsg();
		if(data != null && data.errors != null) {
			//WELOCALLY.ui.setStatus(_instance.statusArea,'ERROR:'+WELOCALLY.util.getErrorString(data.errors), 'wl_error', false);
		} else if(data != null && data.errors != null) {
			//WELOCALLY.ui.setStatus(_instance.statusArea,'Could not delete place:'+WELOCALLY.util.getErrorString(data.errors), 'wl_error', false);
		} else {
			var items = jQuery.parseJSON(data);
			jQuery.each(items, function(i,item){
				var distance = eval(item.rawgeosearchdistance);
				var itemRow = jQuery('<li></li>');
				
				var itemLink = jQuery('<a id="wl_places_mobile_item_'+item.post_id+'" href="#details?id='+item.post_id+'&wl_id='+wl_base64.encode(item.wl_id)+'" data-transition="slide"></a>');
				
				if(item.post_title != item.place_name){
					jQuery(itemLink).html(item.post_title+'<span class="wl_mobile_place_name">, '+
							item.place_name+'</span>, <span class="wl_mobile_place_distance"> '+
							distance.toFixed(2)+units+'</span>');
				} else {
					jQuery(itemLink).html(item.post_title+', <span class="wl_mobile_place_distance"> '+
							distance.toFixed(2)+units+'</span>');
				}
				
				//cats
				var catgeoriesArea = jQuery('<div class="wl_mobile_place_cats"></div>');				
				jQuery.each(item.categories, function(i,category){
					jQuery(catgeoriesArea).append('<div class="wl_mobile_place_cat">'+category+'</div>' );					
				});
				jQuery(itemLink).append(catgeoriesArea);
												
				jQuery(itemLink).bind('click',{instance: _instance, post_id: item.post_id, wl_id: item.wl_id }, _instance.postClickedHandler);				
				jQuery(itemRow).append(itemLink);
				
				jQuery(_instance.wrapper).find('#wl_places_mobile_postlinks').append(itemRow);
			});
			
						
			jQuery(_instance.wrapper).find('#wl_places_mobile_postlinks').listview();
			
			setTimeout(300,jQuery('#wl_places_mobile_postlinks').listview('refresh'));
			
			jQuery(_instance.wrapper).find('#wl_listview_results_status').html('results found: '+items.length+' search area: '+_instance.cfg.dist+' '+_instance.cfg.units);
			jQuery(_instance.wrapper).find('#wl_listview_results_status').show();
		}
	  }
	});
	
};

WELOCALLY_PlacesMobile.prototype.postClickedHandler = function(event,ui) {	
	jQuery.mobile.showPageLoadingMsg();	
	var _instance=event.data.instance; 
	_instance.getPost(event.data.post_id,event.data.wl_id,_instance.setDetailsPage);
	return false;
};

//callback for setting details once available
WELOCALLY_PlacesMobile.prototype.setDetailsPage = function(item, caller) {
	var _instance=caller;
	
	jQuery('#wl_mobile_post_title').html(item.post_title);
	jQuery('#wl_placepost_details_title').html(item.place.properties.name);
	
	jQuery('#wl_place_post_content_wrapper').empty();
	
	if(item.featured_image){
		jQuery('#wl_place_post_content_wrapper').append('<div class="wl_post_img_area"><img class="wl_places_mobile_post_img" src="'+item.featured_image+'"/></div>');
	}
	
		
	jQuery('#wl_place_post_content_wrapper').append('<div class="ui-shadow-inset ui-overlay-c wl_mobile_post_excerpt">'+item.post_content+'</div>');
	
	//post link
	jQuery('#wl_place_post_btn_post').attr('href',item.permalink); 
	jQuery('#wl_place_post_btn_post').show();
	
	
	//setup map links
	jQuery('#wl_place_post_btn_map').unbind();	
	jQuery('#wl_place_post_btn_map').attr('href','#wl_placepost_map?id='+item.ID+'&wl_id='+wl_base64.encode(item.place._id) ); 			
										
	jQuery('#wl_place_post_content_wrapper').addClass('wl_place_post_content');
	jQuery('#wl_place_post_content_actions').show('slow');
	
	//now transition
	WELOCALLY.ui.setStatus(jQuery('#wl_place_post_content_status'), '','wl_message',true);
	jQuery.mobile.hidePageLoadingMsg();
	var wl_id = item.place._id;
	jQuery.mobile.changePage( "#wl_placepost_details?id="+item.ID+"&wl_id="+wl_base64.encode(wl_id), { transition: "slide"} );	
};

/**
 * Separating the click handler from the function that actually gets the post
 * because we have to go get the post on a back, also we want to manage caching 
 */
WELOCALLY_PlacesMobile.prototype.getPost = function(post_id,wl_id,callback) {
	var _instance=this;
	var data = {
			action: 'get_post_place',
			post_id: post_id,
			wl_id: wl_id
	};
	
	_instance.jqxhr = jQuery.ajax({
	  type: 'POST',		  
	  url: _instance.cfg.ajaxurl,
	  data: data,
	  beforeSend: function(jqXHR){ 
		_instance.jqxhr = jqXHR;
	  },
	  error : function(jqXHR, textStatus, errorThrown) {
		  jQuery.mobile.hidePageLoadingMsg();
		  if(textStatus != 'abort'){
			WELOCALLY.ui.setStatus(jQuery('#wl_place_post_content_status'),'ERROR : '+textStatus, 'error', false);
		  }		
	  },		  
	  success : function(data, textStatus, jqXHR) {
		  
		  if(data != null && data.errors != null) {
			//WELOCALLY.ui.setStatus(_instance.statusArea,'ERROR:'+WELOCALLY.util.getErrorString(data.errors), 'wl_error', false);
		  } else if(data != null && data.errors != null) {
			//WELOCALLY.ui.setStatus(_instance.statusArea,'Could not delete place:'+WELOCALLY.util.getErrorString(data.errors), 'wl_error', false);
		  } else {
			var item = jQuery.parseJSON(data);
			callback(item, _instance);
		}
	  }
	});
};

WELOCALLY_PlacesMobile.prototype.mapClickedHandler = function(event,ui) {
	jQuery.mobile.showPageLoadingMsg(); 
	var _instance=event.data.instance; 
	_instance.getPost(event.data.post_id,event.data.wl_id,_instance.setMapPage);	
	return false;					
};

WELOCALLY_PlacesMobile.prototype.setMapPage = function(item,caller) {
	
	var _instance=caller; 
	
	var placeWidget = new WELOCALLY_PlaceMobile();
	var placeCfg = {};
	if(_instance.cfg.styles){
		placeCfg.styles = _instance.cfg.styles;
	}
	
	if(_instance.cfg.imagePath){
		placeCfg.imagePath = _instance.cfg.imagePath;
	}
	
	placeWidget.initCfg(placeCfg);
	var mapCenter = jQuery('<div class="wl_places_mobile_post_map"></div>');
	jQuery(mapCenter).html(placeWidget.makeWrapper());
	jQuery('#wl_map_content').html(mapCenter);
	
	placeWidget.load(item.place);
	
	var latlng = new google.maps.LatLng(
			item.place.geometry.coordinates[1], 
			item.place.geometry.coordinates[0]);
	
	//forced to refresh
	setTimeout(function () {
		placeWidget.refreshMap(latlng);
 	}, 1000);
	
	jQuery.mobile.hidePageLoadingMsg();
	var wl_id = item.place._id;
	jQuery.mobile.changePage( "#wl_placepost_map?id="+item.ID+"&wl_id="+wl_base64.encode(wl_id), { transition: "flip"} );
	
};





