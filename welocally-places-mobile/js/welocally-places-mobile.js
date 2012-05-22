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

	if (errors.length > 0)
		return errors;

	this.cfg = cfg;
};

WELOCALLY_PlacesMobile.prototype.makeWrapper = function(){
	
	var _instance = this;
	
	var wrapper = jQuery('<div></div>');
	
	this.statusArea = jQuery('<div></div>');
	this.statusArea.css('display','none');
	jQuery(wrapper).append(this.statusArea);
		
	var postlinks = jQuery('<ul id="wl_places_mobile_postlinks" data-role="listview" data-inset="true" data-filter="true"></ul>');
	jQuery(wrapper).append(postlinks);
		
	this.wrapper = wrapper;
	return wrapper;
	
};

WELOCALLY_PlacesMobile.prototype.getPosts = function(position){
		
	var _instance=this; 
	var data = {
			action: 'get_posts',
			lat: position.coords.latitude,
			lng: position.coords.longitude
	};
		   
	_instance.jqxhr = jQuery.ajax({
	  type: 'POST',		  
	  url: _instance.cfg.ajaxurl,
	  data: data,
	  beforeSend: function(jqXHR){
		_instance.jqxhr = jqXHR;
	  },
	  error : function(jqXHR, textStatus, errorThrown) {
		if(textStatus != 'abort'){
			WELOCALLY.ui.setStatus(_instance.statusArea,'ERROR : '+textStatus, 'error', false);
		}		
	  },		  
	  success : function(data, textStatus, jqXHR) {
		if(data != null && data.errors != null) {
			//WELOCALLY.ui.setStatus(_instance.statusArea,'ERROR:'+WELOCALLY.util.getErrorString(data.errors), 'wl_error', false);
		} else if(data != null && data.errors != null) {
			//WELOCALLY.ui.setStatus(_instance.statusArea,'Could not delete place:'+WELOCALLY.util.getErrorString(data.errors), 'wl_error', false);
		} else {
			var items = jQuery.parseJSON(data);
			jQuery.each(items, function(i,item){
				var distance = eval(item.rawgeosearchdistance);
				var itemRow = jQuery('<li style="padding:5px;"></li>');
				
				var itemLink = jQuery('<a href="#details" data-transition="slide">'+item.post_title+' <span style="font-size:0.75em"> '+distance.toFixed(2)+'km</span></a>');							 
				jQuery(itemLink).bind('click',{instance: _instance, post_id: item.post_id }, _instance.postClickedHandler);				
				jQuery(itemRow).append(itemLink);
				
//				var itemLink2 = jQuery('<a href="#details" data-role="button" data-transition="slide" data-icon="wl-places-mobile-mapit" data-iconpos="right">Map It</a>');							 
//				jQuery(itemLink2).bind('click',{instance: _instance, post_id: item.post_id }, _instance.postClickedHandler);				
//				jQuery(itemRow).append(itemLink2);
				
				jQuery(_instance.wrapper).find('#wl_places_mobile_postlinks').append(itemRow);
			});
			
			jQuery(_instance.wrapper).find('#wl_places_mobile_postlinks').listview();

		}
	  }
	});
	
};

WELOCALLY_PlacesMobile.prototype.postClickedHandler = function(event,ui) {
	
	
	jQuery.mobile.changePage( "#wl_placepost_details", { transition: "slide"} );
	
	var _instance=event.data.instance; 
	var data = {
			action: 'get_post',
			post_id: event.data.post_id
	};
		   
	WELOCALLY.ui.setStatus(jQuery('#wl_place_post_content_wrapper'), 'Loading post...','wl_message',true);
	
	_instance.jqxhr = jQuery.ajax({
	  type: 'POST',		  
	  url: _instance.cfg.ajaxurl,
	  data: data,
	  beforeSend: function(jqXHR){
		_instance.jqxhr = jqXHR;
	  },
	  error : function(jqXHR, textStatus, errorThrown) {
		if(textStatus != 'abort'){
			WELOCALLY.ui.setStatus(_instance.statusArea,'ERROR : '+textStatus, 'error', false);
		}		
	  },		  
	  success : function(data, textStatus, jqXHR) {
		if(data != null && data.errors != null) {
			//WELOCALLY.ui.setStatus(_instance.statusArea,'ERROR:'+WELOCALLY.util.getErrorString(data.errors), 'wl_error', false);
		} else if(data != null && data.errors != null) {
			//WELOCALLY.ui.setStatus(_instance.statusArea,'Could not delete place:'+WELOCALLY.util.getErrorString(data.errors), 'wl_error', false);
		} else {
			var item = jQuery.parseJSON(data);
			jQuery('#wl_mobile_post_title').html(item.post_title);
			jQuery('#wl_place_post_content_wrapper').empty();
			
			if(item.featured_image){
				jQuery('#wl_place_post_content_wrapper').append('<div style="text-align:center; width:100%;"><img class="wl_places_mobile_post_img" src="'+item.featured_image+'"/></div>');
			}
			
			jQuery('#wl_place_post_content_wrapper').append(item.post_content);
			jQuery('#wl_place_post_content_wrapper').addClass('wl_place_post_content');
			jQuery('#wl_place_post_content_actions').show();
			
		}
	  }
	});
	
	
	
		
	return false;

}


//
//
//WELOCALLY_PlacesMobile.prototype.setPlaceRow = function(i, place, row) {
//	
//	jQuery(this.wrapper).find('#wl_placesmgr_searchterm').show();
//	
//	
//	var placeRowContent = jQuery('<div></div>');	
//	jQuery(placeRowContent).append('<div class="wl_placemgr_place_tag">[welocally id="'+place._id+'" /]</div>');	
//	
//	var placeInfo = jQuery('<div class="wl_placemgr_place_info"></div>');
//
//	jQuery(placeInfo).append('<div class="place_field">'+place.properties.name+'</div>');
//	jQuery(placeInfo).append('<div class="place_field">'+place.properties.address+'</div>');
//	jQuery(placeInfo).append('<div class="place_field">'+place.properties.city+'</div>');
//	jQuery(placeInfo).append('<div class="place_field">'+place.properties.province+'</div>');
//	jQuery(placeInfo).append('<div class="place_field">'+place.properties.postcode+'</div>');
//	if(place.properties.website)
//		jQuery(placeInfo).append('<div class="place_field">'+place.properties.website+'</div>');
//	if(place.properties.phone)
//		jQuery(placeInfo).append('<div class="place_field">'+place.properties.phone+'</div>');
//	jQuery(placeRowContent).append(placeInfo);
//	
//	var actions = jQuery('<div class="wl_placemgr_actions"></div>');
//	var btnEdit = jQuery('<a class="wl_placemgr_button" href="#">edit</a>');
//	jQuery(btnEdit).bind('click',{instance: this, place: place, index: i, row: row}, this.editHandler);
//
//	var btnDelete = jQuery('<a class="wl_placemgr_button" href="#">delete</a>');
//	
//	jQuery(btnDelete).bind('click',{instance: this, place: place, index: i, row: row}, this.deleteDialogHandler);
//	
//	jQuery(actions).append(btnEdit);
//	jQuery(actions).append(btnDelete);		
//	jQuery(placeRowContent).append(actions);
//		
//	jQuery(row).html(placeRowContent);
//};
//
//WELOCALLY_PlacesMobile.prototype.editHandler = function(event,ui) {
//	var place = event.data.place;
//	var _instance = event.data.instance;
//	_instance.addPlaceWidget.savedPlace = null;
//	_instance.addPlaceWidget.show(place);
//	jQuery( _instance.addPlaceWrapper).dialog({
//		title: 'edit place',
//		minWidth: 600,
//		modal: true
//	});	
//	
//	jQuery( _instance.addPlaceWrapper).bind('dialogclose',
//			{instance: _instance, 
//			widget:_instance.addPlaceWidget, 
//			index: event.data.index, 
//			row: jQuery('#wl_placemgr_place_'+event.data.index)}, 
//			_instance.editDialogClosedHandler);
//	
//	return false;
//		
//};
//
//
//WELOCALLY_PlacesMobile.prototype.addHandler = function(event,ui) {
//	var _instance = event.data.instance;
//	_instance.addPlaceWidget.clearFields();
//	
//	jQuery( _instance.addPlaceWrapper).dialog({
//		title: 'add place',
//		position: "top",
//		minWidth: 650,
//		modal: true
//	});
//	
//	jQuery(_instance.addPlaceWrapper).bind('dialogclose', {
//		instance : _instance
//	}, _instance.addDialogClosedHandler);
//
//	
//	return false;
//		
//};
//
//
//WELOCALLY_PlacesMobile.prototype.addDialogClosedHandler = function(event,ui) {
//	var _instance = event.data.instance;
//	
//	_instance.pager.getMetadata();	
//	_instance.pager.load(1);	
//	
//	
//	return false;	
//};
//
//WELOCALLY_PlacesMobile.prototype.editDialogClosedHandler = function(event,ui) {
//	var _instance = event.data.instance;
//	var place = event.data.widget.savedPlace;
//	if(place){
//		var index = event.data.index;
//		var row = event.data.row;
//		
//		_instance.setPlaceRow(index, place, row);
//	}
//
//	jQuery(_instance.statusArea).delay(5000).fadeOut('slow'); 
//	
//	return false;	
//};
//
//WELOCALLY_PlacesMobile.prototype.deleteDialogHandler = function(event,ui) {
//	var _instance = event.data.instance;
//	jQuery( _instance.deleteDialog).bind('deleteplace',
//			event.data, 
//			_instance.deleteHandler);
//	jQuery( _instance.deleteDialog).html('Please confirm that you would like to delete '+
//			event.data.place.properties.name+' at '+event.data.place.properties.address+
//			'. <strong>This action can not be undone.</strong> You will also need to delete the tag from your post.');
//	jQuery( _instance.deleteDialog).dialog({
//		resizable: false,
//		title: 'Delete '+event.data.place.properties.name+'?',
//		width: 400,
//		height:200,
//		modal: true,
//		buttons: {
//			"Delete": function() {				
//				jQuery( _instance.deleteDialog).trigger('deleteplace',
//						event.data, 
//						_instance.deleteHandler);
//				jQuery( this ).dialog( "close" );
//			},
//			Cancel: function() {
//				jQuery( this ).dialog( "close" );
//			}
//		}
//	});	
//
//
//	
//};
//
//WELOCALLY_PlacesMobile.prototype.deleteHandler = function(event,ui) {
//	var _instance = event.data.instance;
//	WELOCALLY.ui.setStatus(_instance.statusArea,'Deleting Place...', 'wl_message', true);
//	
//	var data = {
//			action: 'delete_place',
//			id: event.data.index,
//			wl_id: event.data.place._id
//	};
//		   
//	_instance.jqxhr = jQuery.ajax({
//	  type: 'POST',		  
//	  url: ajaxurl,
//	  data: data,
//	  beforeSend: function(jqXHR){
//		_instance.jqxhr = jqXHR;
//	  },
//	  error : function(jqXHR, textStatus, errorThrown) {
//		if(textStatus != 'abort'){
//			WELOCALLY.ui.setStatus(_instance.statusArea,'ERROR : '+textStatus, 'error', false);
//		}		
//	  },		  
//	  success : function(data, textStatus, jqXHR) {
//		if(data != null && data.errors != null) {
//			WELOCALLY.ui.setStatus(_instance.statusArea,'ERROR:'+WELOCALLY.util.getErrorString(data.errors), 'wl_error', false);
//		} else if(data != null && data.errors != null) {
//			WELOCALLY.ui.setStatus(_instance.statusArea,'Could not delete place:'+WELOCALLY.util.getErrorString(data.errors), 'wl_error', false);
//		} else {
//			WELOCALLY.ui.setStatus(_instance.statusArea,'Your place has been deleted!', 'wl_message', false);
//			jQuery('#wl_placemgr_place_'+event.data.index).hide();
//			jQuery('#wl_placemgr_place_'+event.data.index).html('Deleted.');
//			jQuery('#wl_placemgr_place_'+event.data.index).show('slow');
//			jQuery(_instance.statusArea).delay(5000).fadeOut('slow'); 
//			
//		}
//	  }
//	});
//	
//	
//	
//	return false;
//	
//};




