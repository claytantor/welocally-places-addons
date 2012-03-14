/*
	copyright 2012 clay graham. NO WARRANTIES PROVIDED
*/
if (!window.WELOCALLY) {
    window.WELOCALLY = {
    }
}

//http://localhost:8082/geodb/deal/1_0/search.json?q=*:*&loc=37.8113159_-122.26822449999997&radiusKm=30
function WELOCALLY_DealFinderWidget (cfg) {		

		this.wrapper;
		this.slides;
		this.list;
		this.item_width;
		this.left_value;
		this.run;
		this.endpont;
		
		this.init = function() {
			var error;
			// validate config
			if (!cfg) {
				error = "Please provide configuration for the widget";
				cfg = {};
			}
			
			// summary (optional) - the summary of the article
			// hostname (optional) - the name of the host to use
			if (!cfg.endpoint) {
				cfg.endpoint = 'http://stage.welocally.com';
			}
			
			this.endpoint = cfg.endpoint;
			
			// Get current script object
			var script = jQuery('SCRIPT');
			script = script[script.length - 1];
	
			// Build Widget
			this.wrapper = jQuery('<div></div>');
			jQuery(this.wrapper).css('display','none');			
			jQuery(this.wrapper).attr('class','welocally_deals_widget');
			jQuery(this.wrapper).attr('id','welocally_deals_widget');
			//jQuery(this.wrapper).html('welocally widget '+cfg.info+'<br/>');
			
			this.slides = jQuery('<div></div>').attr('id','wl_deals_slides');
			this.list = jQuery('<ul></ul>').attr('id','wl_slides_list');
			jQuery(this.slides).append(this.list);
			jQuery(this.wrapper).append(this.slides);			
			
			jQuery(script).parent().before(this.wrapper);
		
		}
}

/*
[
    {
        "startDate": 1331166957000,
        "location": {
            "phoneNumber": "(415) 374-9294",
            "address": "215 Fremont Street",
            "zipCode": "95105",
            "name": "Anthropos Performance",
            "state": "CA",
            "longitude": -122.394299,
            "latitude": 37.789139,
            "city": "San Francisco"
        },
        "smallImageUrl": "http://www.signpost.com/media/BAhbB1sHOgZmSSIzMjAxMS8xMi8xNC8xNy8xMC8yOS85MTQvQ3JvcHBlckNhcHR1cmVfMjRfLmpwZwY6BkVUWwk6BnA6DGNvbnZlcnRJIhIgLXNjYWxlIDQ1eDQ1BjsGRjA",
        "endDate": 1331508957000,
        "available": 15,
        "savings": 50,
        "url": "http://signpost.go2cloud.org/aff_c?offer_id=516&aff_id=1286&url=http%3A%2F%2Fwww.signpost.com%2Fdeals%2Fsan-francisco-ca%2Fanthroposperformance-com%2F50-for-introductory-personal-training-sessions%2F447%3Futm_source%3Dhasoffers-1286%26utm_medium%3Daffiliate%26utm_campaign%3Dsan-francisco-ca%26utm_content%3Danthroposperformance-com",
        "distance": 11.348207197610375,
        "title": "jQuery50 for Introductory Personal Training Sessions",
        "price": 50,
        "_id": "WLD_b4krrih2hd17v2k44ca5a4_37.789139_-122.394299@1331238112",
        "percentageDiscount": 50,
        "details": "So, how did you do on your fitness goals for 2011? Were you able to find that great workout and\nstick to it? If not, we have a great deal for you from Anthropos Performance. They are offering 2\nspecial introductory personal training sessions for just jQuery50. This is 50% off the normal price of jQuery100.\nAnthropos is located in San Francisco and focuses on kettlebell and barbell training for health and\nfitness. The trainers at Anthropos provide guidance to help you achieve your fitness goals, lose fat\nand build muscle, which results in increased strength and health. They seek to bridge the divide\nbetween what the elite trainers and academics know, and what the general population typically\nemploys to achieve health and fitness. They filter the high-level technical information down to\npracticable approaches that work. Now is a great time to take your fitness seriously. Are you commit to your health and wellness? If so, the team at Anthropos\nPerformance can help.",
        "signpostId": "447",
        "value": 100,
        "largeImageUrl": "http://www.signpost.com/media/BAhbB1sHOgZmSSIzMjAxMS8xMi8xNC8xNy8xMC8yOS85MTQvQ3JvcHBlckNhcHR1cmVfMjRfLmpwZwY6BkVUWwk6BnA6DGNvbnZlcnRJIhQgLXNjYWxlIDE5MHgxOTAGOwZGMA",
        "voucherExprirationDate": 1342058400000,
        "categories": [],
        "quantity": 50,
        "mediumImageUrl": "http://www.signpost.com/media/BAhbB1sHOgZmSSIzMjAxMS8xMi8xNC8xNy8xMC8yOS85MTQvQ3JvcHBlckNhcHR1cmVfMjRfLmpwZwY6BkVUWwk6BnA6DGNvbnZlcnRJIhIgLXNjYWxlIDk1eDk1BjsGRjA",
        "sold": 0
    }
]
*/
WELOCALLY_DealFinderWidget.prototype.setLocation = function(lat, lng) {

	var query = {
			q: '*:*',
			loc: lat+'_'+lng,
			radiusKm: 30
	};

	var surl = this.endpoint +
			'/geodb/deal/1_0/search.json?'+WELOCALLY.util.serialize(query)+"&callback=?";
	console.log(surl);
	
	var t = this;
	
	jQuery(this.wrapper).hide();
	jQuery(this.list).empty();
		
	jQuery.ajax({
		  url: surl,
		  dataType: "json",
		  success: function(data) {
			jQuery.each(data, function(i,item){						
				console.log('deal:'+item.location.name);
				
				var listItem = jQuery('<li></li>');
				
				//float left image
				var img = jQuery('<img/>').attr('src',item.mediumImageUrl).attr('class','wl_deal_img');
				jQuery(listItem).append(img);
				jQuery(listItem).append('<div class="wl_deal_summary"><a target="_new" href="'+item.url+'">'+item.title+'</a></div>');
				jQuery(listItem).append('<div class="wl_deal_location">'+item.location.name+', '+item.location.city+' '+item.location.state+'</div>')
							
							
				jQuery(t.list).append(listItem);	
			});
			//rotation speed and timer
			var speed = 5000; 
			if(t.run){
				clearInterval(t.run);
			}
 			t.run = setInterval(function(){t.next();}, speed);
			 
			//grab the width and calculate left value
			this.item_width = jQuery('#wl_deals_slides li').outerWidth(); 
			this.left_value = this.item_width * (-1); 
				 
			//move the last item before first item, just in case user click prev button
			jQuery('#wl_deals_slides li:first').before(jQuery('#wl_deals_slides li:last'));
			 
			//set the default item to the correct position 
			jQuery('#wl_deals_slides ul').css({'left' : this.left_value});
			
			if(data.length>0){
				jQuery(t.wrapper).show();
			}
													
		}
	});
	
};

WELOCALLY_DealFinderWidget.prototype.next = function() {
 //get the right position
 		console.log('next');
        var left_indent = parseInt(jQuery('#wl_deals_slides ul').css('left')) - this.item_width;
         
        //slide the item
        jQuery('#wl_deals_slides ul').animate({'left' : left_indent}, 200, function () {
             
            //move the first item and put it as last item
            jQuery('#wl_deals_slides li:last').after(jQuery('#wl_deals_slides li:first'));                  
             
            //set the default item to correct position
            jQuery('#wl_deals_slides ul').css({'left' : this.left_value});
         
        });
                        
};




