var wl_base64 = {
 
	keys : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
 
	encode : function (input) {
		var output = "";
		var c1, c2, c3, e1, e2, e3, e4;
		var i = 0;
 
		input = wl_base64.utf8Encode(input);
 
		while (i < input.length) {
 
			c1 = input.charCodeAt(i++);
			c2 = input.charCodeAt(i++);
			c3 = input.charCodeAt(i++);
 
			e1 = c1 >> 2;
			e2 = ((c1 & 3) << 4) | (c2 >> 4);
			e3 = ((c2 & 15) << 2) | (c3 >> 6);
			e4 = c3 & 63;
 
			if (isNaN(c2)) {
				e3 = e4 = 64;
			} else if (isNaN(c3)) {
				e4 = 64;
			}
 
			output = output +
			this.keys.charAt(e1) + this.keys.charAt(e2) +
			this.keys.charAt(e3) + this.keys.charAt(e4);
 
		}
 
		return output;
	},
 
	decode : function (input) {
		var output = "";
		var c1, c2, c3;
		var e1, e2, e3, e4;
		var i = 0;
 
		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
 
		while (i < input.length) {
 
			e1 = this.keys.indexOf(input.charAt(i++));
			e2 = this.keys.indexOf(input.charAt(i++));
			e3 = this.keys.indexOf(input.charAt(i++));
			e4 = this.keys.indexOf(input.charAt(i++));
 
			c1 = (e1 << 2) | (e2 >> 4);
			c2 = ((e2 & 15) << 4) | (e3 >> 2);
			c3 = ((e3 & 3) << 6) | e4;
 
			output = output + String.fromCharCode(c1);
 
			if (e3 != 64) {
				output = output + String.fromCharCode(c2);
			}
			if (e4 != 64) {
				output = output + String.fromCharCode(c3);
			}
 
		}
 
		output = wl_base64.utf8Decode(output);
 
		return output;
 
	},
 
	utf8Encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
 
		for (var n = 0; n < string.length; n++) {
 
			var c = string.charCodeAt(n);
 
			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
 
		}
 
		return utftext;
	},
 
	utf8Decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
 
		while ( i < utftext.length ) {
 
			c = utftext.charCodeAt(i);
 
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
 
		}
 
		return string;
	}
 
}