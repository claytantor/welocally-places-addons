/*
	copyright 2012 clay graham. NO WARRANTIES PROVIDED
*/
if (!window.WELOCALLY) {
    window.WELOCALLY = {
    	env: {
    		init: function(){
    			console.log('init WELOCALLY');
    		}
    	},
    	util: {
    		serialize: function(obj, prefix) {
				var str = [];
				for(var p in obj) {
					var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
					str.push(typeof v == "object" ? 
						serialize(v, k) :
						encodeURIComponent(k) + "=" + encodeURIComponent(v));
				}
				return str.join("&");
			},
			trim: function (str) { 
	    			return WELOCALLY.util.ltrim(WELOCALLY.util.rtrim(str), ' '); 
			}, 
			ltrim: function (str) { 
				return str.replace(new RegExp("^[" + ' ' + "]+", "g"), ""); 
			},    		 
			rtrim: function (str) { 
				return str.replace(new RegExp("[" + ' ' + "]+$", "g"), ""); 
			},
			preload: function(arrayOfImages) {
				jQuery(arrayOfImages).each(function(){
					jQuery('<img/>')[0].src = this;
					// Alternatively you could use:
					// (new Image()).src = this;
				});
			},
			update: function() {
					var obj = arguments[0], i = 1, len=arguments.length, attr;
					for (; i<len; i++) {
							for (attr in arguments[i]) {
									obj[attr] = arguments[i][attr];
							}
					}
					return obj;
			},
			escape: function(s) {
					return ((s == null) ? '' : s)
							.toString()
							.replace(/[<>"&\\]/g, function(s) {
									switch(s) {
											case '<': return '&lt;';
											case '>': return '&gt;';
											case '"': return '\"';
											case '&': return '&amp;';
											case '\\': return '\\\\';
											default: return s;
									}
							});
			},
			unescape: function (unsafe) {
				  return unsafe
					  .replace(/&amp;/g, "&")
					  .replace(/&lt;/g, "<")
					  .replace(/&gt;/g, ">")
					  .replace(/&quot;/g, '"')
					  .replace(/&#039;/g, "'");
			},
			notundef: function(a, b) {
					return typeof(a) == 'undefined' ? b : a;
			},
			guidGenerator: function() {
				return (WELOCALLY.util.S4()+WELOCALLY.util.S4()+"-"+
						WELOCALLY.util.S4()+"-"+WELOCALLY.util.S4()+"-"+
						WELOCALLY.util.S4()+"-"+
						WELOCALLY.util.S4()+WELOCALLY.util.S4()+WELOCALLY.util.S4());
			},
			keyGenerator: function() {
				return (WELOCALLY.util.S4()+WELOCALLY.util.S4());
			},
			tokenGenerator: function() {
				 return (WELOCALLY.util.S4()+WELOCALLY.util.S4()+
						WELOCALLY.util.S4()+WELOCALLY.util.S4()+
						WELOCALLY.util.S4()+
						WELOCALLY.util.S4()+WELOCALLY.util.S4()+WELOCALLY.util.S4());
			},
			S4: function() {
			   return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
			},
			replaceAll: function(txt, replace, with_this) {
				  return txt.replace(new RegExp(replace, 'g'),with_this);
			}
			
			
			
    	}
    	
    }
}
