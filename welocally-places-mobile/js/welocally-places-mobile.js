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
		
	var postlinks = jQuery('<ul class="wl-listview" id="wl_places_mobile_postlinks" data-role="listview" data-inset="true" data-filter="true" data-filter-theme="'+_instance.cfg.filterTheme+'"></ul>');
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
			  jQuery('#wl_error_page_message').html('ERROR: There is a problem: '+textStatus+' Please try again.');			
			  jQuery('#wl_error_page_explain').html('You probably just need to wait a little while and reload, but if you have waited long enough and still have problems <strong>disable mobile browsing.</strong>' );		 		
	          jQuery.mobile.changePage( '#wl_error_page',{transition: 'pop', role: 'dialog'}  );
		  }		
	  },		  
	  success : function(data, textStatus, jqXHR) {
		jQuery( '#wl_mobile_startup' ).remove();
		
		if(data -1) {
			jQuery('#wl_error_page_message').html('ERROR: There is a problem with the request. Please try again.');			
			jQuery('#wl_error_page_explain').html('You probably just need to wait a little while and reload, but if you have waited long enough and still have problems <strong>disable mobile browsing.</strong>' );		 		
    		jQuery.mobile.changePage( '#wl_error_page',{transition: 'pop', role: 'dialog'}  );
			
		} else if(data != null && data.errors != null) {
			jQuery('#wl_error_page_message').html('ERROR:'+WELOCALLY.util.getErrorString(data.errors));	
			jQuery('#wl_error_page_explain').html('You probably just need to wait a little while and reload, but if you have waited long enough and still have problems <strong>disable mobile browsing.</strong>' );		 		
    		jQuery.mobile.changePage( '#wl_error_page',{transition: 'pop', role: 'dialog'}  );
			
		} else {
			var items = jQuery.parseJSON(data);
			jQuery.each(items, function(i,item){
				var distance = eval(item.rawgeosearchdistance);
				var itemRow = jQuery('<li></li>');
				
				var itemLink = jQuery('<a id="wl_places_mobile_item_'+item.post_id+'" href="#details?id='+item.post_id+'&wl_id='+wl_base64.encode(item.wl_id)+'" data-transition="slide"></a>');
				
				var place_name = _instance.ellipsise(item.place_name, 26, 'end', '...')
				jQuery(itemLink).html('<span class="wl_mobile_place_name">'+place_name+'</span>, <span class="wl_mobile_place_distance"> '+
						distance.toFixed(2)+units+'</span>');
				
				//title
				var titleArea = jQuery('<div class="wl_mobile_place_info"></div>');				
				if(item.post_title != item.place_name){
					jQuery(titleArea).append('<div class="wl_mobile_post_title">'+item.post_title+'</div>' );
				}
				jQuery(itemLink).append(titleArea);
				
				//cats
				var catgeoriesArea = jQuery('<div class="wl_mobile_place_info"></div>');				
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
		jQuery.mobile.hidePageLoadingMsg();
	  }
	});
	
};

//geocode by ip address
//http://freegeoip.net/{format}/{ip_or_hostname}
//http://freegeoip.net/json/google.com?callback=show
/*
 * {"city": "Mountain View", 
 *  "region_code": "CA", "region_name": 
 *   "California", "metrocode": "807", 
 *   "zipcode": "94043", 
 *   "longitude": "-122.057", "country_name": "United States", "country_code": "US", "ip": "209.85.145.147", "latitude": "37.4192"}
 * 
 */
WELOCALLY_PlacesMobile.prototype.getPostsByIpAddress = function(ip_or_hostname) { 
	
	var _instance = this;
	_instance.jqxhr = jQuery.ajax({
		  type: 'GET',		  
		  url: 'http://freegeoip.net/json/'+ip_or_hostname+'?callback=?',
		  dataType: 'jsonp',
		  beforeSend: function(jqXHR){
			_instance.jqxhr = jqXHR;
		  },
		  error : function(jqXHR, textStatus, errorThrown) {
			  jQuery.mobile.hidePageLoadingMsg();
			  if(textStatus != 'abort'){
				  jQuery('#wl_error_page_message').html('ERROR: There is a problem with the getting the location. Please try again.');			
				  jQuery('#wl_error_page_explain').html('Most location errors occur due to a misconfiguration of the mobile browser location detection settings. Make sure you have enabled location detection for this website. if you have tried to enable location, and cleared the cache and cookies and are still not able to see this website then <strong>disable mobile browsing.</strong>' );		 		
		    	  jQuery.mobile.changePage( '#wl_error_page',{transition: 'pop', role: 'dialog'}  );
			  }		
		  },		  
		  success : function(data, textStatus, jqXHR) {
			  WELOCALLY.ui.setStatus(_instance.statusArea,'FOUND for ip: '+data.ip, 'error', false);
			  _instance.getPosts({
					coords: {
					latitude: eval(data.latitude),
					longitude: eval(data.longitude)
				}			
			});
		  }
		});
	
	
};



//Where is one of ['front','middle','end'] -- default is 'end'
WELOCALLY_PlacesMobile.prototype.ellipsise = function(orig, toLength, where, ellipsis) { 
	if (toLength < 1) return orig;
	ellipsis = ellipsis || '\u2026';
	if (orig.length < toLength) return orig;
	switch (where) {
		case 'front':
			return ellipsis + orig.substr(orig.length - toLength);
			break;
		case 'middle':
			return orig.substr(0, toLength / 2) + ellipsis + orig.substr(orig.length - toLength / 2)
			break;
		case 'end':
		default:
			return orig.substr(0, toLength) + ellipsis;
			break;
	}
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
				jQuery('#wl_error_page_message').html('ERROR : '+textStatus+'. Please try again.');			
				jQuery('#wl_error_page_explain').html('You probably just need to wait a little while and reload, but if you have waited long enough and still have problems <strong>disable mobile browsing.</strong>' );		 		
	    		jQuery.mobile.changePage( '#wl_error_page',{transition: 'pop', role: 'dialog'}  );

			WELOCALLY.ui.setStatus(jQuery('#wl_place_post_content_status'),'ERROR : '+textStatus, 'error', false);
		  }		
	  },		  
	  success : function(data, textStatus, jqXHR) {		  
	    if(data -1) {
			jQuery('#wl_error_page_message').html('ERROR: There is a problem with the request. Please try again.');			
			jQuery('#wl_error_page_explain').html('You probably just need to wait a little while and reload, but if you have waited long enough and still have problems <strong>disable mobile browsing.</strong>' );		 		
    		jQuery.mobile.changePage( '#wl_error_page',{transition: 'pop', role: 'dialog'}  );
			
		} else if(data != null && data.errors != null) {
			jQuery('#wl_error_page_message').html('ERROR:'+WELOCALLY.util.getErrorString(data.errors));	
			jQuery('#wl_error_page_explain').html('You probably just need to wait a little while and reload, but if you have waited long enough and still have problems <strong>disable mobile browsing.</strong>' );		 		
    		jQuery.mobile.changePage( '#wl_error_page',{transition: 'pop', role: 'dialog'}  );
			
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





