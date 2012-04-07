<?php
if (!class_exists('PlacehoundNetwork')) {
    
    class PlacehoundNetwork {
    	
		 //-------- GET ------------
		
		function wl_do_curl_get($url, $headers=null){
			
			//print_r($url);
			
			$result_json = '';
			if (preg_match("/https/", $url)) {
				$result_json = $this->wl_do_curl_get_https($url, $headers);
			} else {
				$result_json = $this->wl_do_curl_get_http($url, $headers);
			}
		
			return $result_json;
		}
		
		function wl_do_curl_get_http($url, $headers) {
			//open connection
			$ch = curl_init();
		
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
			//execute post
			$result_json = curl_exec($ch);
		
			curl_close($ch);
		
			return $result_json;
		}
		
		
		function wl_do_curl_get_https($https_url, $headers) {
		
			//open connection
			$ch = curl_init();
		
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_CAINFO, NULL);
			curl_setopt($ch, CURLOPT_CAPATH, NULL);
		
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $https_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
			//execute post
			$result_json = curl_exec($ch);
		
			curl_close($ch);
		
			return $result_json;
		}
		
		//-------- PUT ------------
		function wl_do_curl_put($url, $selectedPostJson, $headers) {
			//error_log("PUT url:".$url." JSON:".$selectedPostJson, 0);
		
			$result_json = '';
			if (preg_match("/https/", $url)) {
				$result_json = wl_do_curl_put_https($url, $selectedPostJson, $headers);
			} else {
				$result_json = wl_do_curl_put_http($url, $selectedPostJson, $headers);
			}
		
			return $result_json;
		}
		
		
		
		
		function wl_do_curl_put_http($url, $selectedPostJson, $headers) {
			//open connection
			$ch = curl_init();
			
			$requestLength = strlen($selectedPostJson);
		
			$fh = fopen('php://memory', 'rw');
			fwrite($fh, $selectedPostJson);
			rewind($fh);
		
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_INFILE, $fh);
			curl_setopt($ch, CURLOPT_INFILESIZE, $requestLength);
			curl_setopt($ch, CURLOPT_PUT, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
			//execute put
			$result_json = curl_exec($ch);
		
			curl_close($ch);
			
			fclose($fh);
		
			return $result_json;
		}
		
		function wl_do_curl_put_https($https_url, $selectedPostJson, $headers) {
		
			//open connection
			$ch = curl_init();
			
			$requestLength = strlen($selectedPostJson);
		
			$fh = fopen('php://memory', 'rw');
			fwrite($fh, $selectedPostJson);
			rewind($fh);
		
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_CAINFO, NULL);
			curl_setopt($ch, CURLOPT_CAPATH, NULL);
		
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $https_url);
			curl_setopt($ch, CURLOPT_INFILE, $fh);
			curl_setopt($ch, CURLOPT_INFILESIZE, $requestLength);
			curl_setopt($ch, CURLOPT_PUT, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
			//execute put
			$result_json = curl_exec($ch);
		
			curl_close($ch);
			
			fclose($fh);
		
			return $result_json;
		}
		
		
		
		
		
		
		//-------- POST ------------
		function wl_do_curl_post($url, $selectedPostJson, $headers, $returnxfer = false) {
			$result_json = '';
			if (preg_match("/https/", $url)) {
				$result_json = wl_do_curl_post_https($url, $selectedPostJson, $headers,$returnxfer);
			} else {
				$result_json = wl_do_curl_post_http($url, $selectedPostJson, $headers,$returnxfer);
			}
		
			return $result_json;
		}
		
		function wl_do_curl_post_http($url, $selectedPostJson, $headers, $returnxfer = false) {
			//open connection
			$ch = curl_init();
		
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $selectedPostJson);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returnxfer);
			
		
			//execute post
			$result_json = curl_exec($ch);
		
			curl_close($ch);
		
			return $result_json;
		}
		
		function wl_do_curl_post_https($https_url, $selectedPostJson, $headers, $returnxfer = false) {
		
			//open connection
			$ch = curl_init();
		
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_CAINFO, NULL);
			curl_setopt($ch, CURLOPT_CAPATH, NULL);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returnxfer);
		
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $https_url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $selectedPostJson);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
			//execute post
			$result_json = curl_exec($ch);
		
			curl_close($ch);
		
			return $result_json;
		}


//----- end of neworking section ----------//
    	
    }
    
    global $placehoundNetwork;
	$placehoundNetwork = new PlacehoundNetwork();
	
}
?>