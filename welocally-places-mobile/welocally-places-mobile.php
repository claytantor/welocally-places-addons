<?php

/*
Plugin Name: Welocally Places Mobile
Plugin URI: http://www.welocally.com/wordpress/?page_id=2
Description: The Welocally Places mobile plugin makes your content location aware using welocally places
Version: 1.1.0
Author: Welocally  
Author URI: http://welocally.com
License: GPL2 
*/

register_activation_hook(__FILE__, 'welocally_places_mobile_activate');
add_filter( 'init', 'wl_places_mobile_init' );
add_action('wp_ajax_get_posts', 'wl_places_mobile_get_posts');
add_action('wp_ajax_nopriv_get_posts', 'wl_places_mobile_get_posts');

add_action('wp_ajax_get_post', 'wl_places_mobile_get_post');
add_action('wp_ajax_nopriv_get_post', 'wl_places_mobile_get_post');

function welocally_places_mobile_activate() {
	if (version_compare(PHP_VERSION, "5.1", "<")) {
		trigger_error('Can Not Install Welocally Places, Please Check Requirements', E_USER_ERROR);
	} else {
		require_once (dirname(__FILE__) . "/WelocallyPlacesMobile.class.php");
		global $wlPlacesMobile;
		$wlPlacesMobile->onActivate();
	}
}


if (version_compare(phpversion(), "5.1", ">=")) {
	require_once (dirname(__FILE__) . "/WelocallyPlacesMobile.class.php");
}

function wl_places_mobile_init() {	
		
	if ( !is_admin() ) {
		
	}
}

function wl_places_mobile_get_posts() {	
	

	
	global $wlPlacesMobile;
	$lat = $_POST['lat'];	
	$lng = $_POST['lng'];	

	$loc = array('lat'=>$lat,'lng' =>$lng);
	$dist = 5000.00;
	echo json_encode($wlPlacesMobile->geoSearch($loc, $dist));

	die(); // this is required to return a proper result	
	
	
}

function wl_places_mobile_get_post() {	
	global $wlPlacesMobile;
	$post_id = $_POST['post_id'];	
	$post = $wlPlacesMobile->getPost($post_id);
	$post->post_content = get_safe_excerpt($post->post_content, 500);
	
	echo json_encode($post);

	die(); // this is required to return a proper result		
}

function get_safe_excerpt($content, $max_length=350) {
	$content = strip_tags( $content );
	$content = strip_selected_shortcodes($content);
	return utf8_truncate( $content, $max_length);
}

function strip_selected_shortcodes($text)
{ // Strips specific start and end shortcodes tags. Preserves contents.
    return preg_replace('%
        # Match an opening or closing WordPress tag.
        \[/?                 # Tag opening "[" delimiter.
        (?:                  # Group for Wordpress tags.
          welocally     	 # Add other tags separated by |
        )\b                  # End group of tag name alternative.
        (?:                  # Non-capture group for optional attribute(s).
          \s+                # Attributes must be separated by whitespace.
          [\w\-.:]+          # Attribute name is required for attr=value pair.
          (?:                # Non-capture group for optional attribute value.
            \s*=\s*          # Name and value separated by "=" and optional ws.
            (?:              # Non-capture group for attrib value alternatives.
              "[^"]*"        # Double quoted string.
            | \'[^\']*\'     # Single quoted string.
            | [\w\-.:]+      # Non-quoted attrib value can be A-Z0-9-._:
            )                # End of attribute value alternatives.
          )?                 # Attribute value is optional.
        )*                   # Allow zero or more attribute=value pairs
        \s*                  # Whitespace is allowed before closing delimiter.
        /?                   # Tag may be empty (with self-closing "/>" sequence.
        \]                   # Opening tag closing "]" delimiter.
        %six', '', $text);
}

function utf8_truncate($string, $length = 200, $etc = '...', $break_words = false)
{
    if($length == 0)
        return '';

    if(strlen($string) > $length)
    {
        $length -= strlen($etc);

        $splitallowed = FALSE;
        while(!$splitallowed)
        {
            $utf8type = utf8charactertype(substr($string, $length, 1));
            switch($utf8type)
            {
                case '0':
                case '1':
                    $splitallowed = TRUE;
                    break;
                case '2':
                    $length--;
                    break;
            }
        }

        if(!$break_words)
        {
            $splitallowed = FALSE;
            while(!$splitallowed && $length)
            {
                $char = substr($string, $length, 1);
                $utf8type = utf8charactertype($char);
                switch($utf8type)
                {
                    case '0':
                        if($char == ' ')
                        {
                            $splitallowed = TRUE;
                            $length++;
                        }
                        else
                        {
                            $length--;
                        }
                        break;
                    case '1':
                        $splitallowed = TRUE;
                        $length++;
                    case '2':
                        $length--;
                        break;
                }
            }
        }

        return substr($string, 0, $length) . $etc;
    }
    else
    {
        return $string;
    }
}

function utf8charactertype($char)
{
    if(ord($char) < 128) return 0;
    $charbin = decbin(ord($char));
    if(substr($charbin, 0, 2) == '11') return 1;
    if(substr($charbin, 0, 2) == '10') return 2;
}


?>
