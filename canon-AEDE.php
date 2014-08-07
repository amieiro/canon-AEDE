<?php
/*
Plugin Name: Canon AEDE
Plugin URI: http://fontethemes.com/es/canon-aede/
Description: Replace the URL base of the communication media who are members of CEDRO and AEDE
Version: 0.2.0
Author: Jesus Amieiro
Author URI: http://fontethemes.com
License: GPL2 or later
*/
/*
Copyright 2014  Jesus Amieiro  (email : info@fontethemes.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

	include('domain-list.php');

	$url_base = get_bloginfo('url');
	$domain_base = str_replace("http://","",get_bloginfo('url'));
	$domain_base = str_replace("https://","",$domain_base);
	$reg_exUrl = "!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?&_/=\-%#;]+!"; // Original regex
	$reg_exDomain = "/[\w\d\-\.]+\.[\w\d]{1,3}/"; // Original regex
	//$reg_exUrl = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i"; 
	//$reg_exDomain = "/[\w\d\-\.]+\.[\w\d]{1,3}[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/";

// Hooks list http://codex.wordpress.org/Plugin_API/Filter_Reference
add_filter('the_content', 'remove_links');
add_filter('the_title', 'remove_links');
///////////////add_filter('the_permalink', 'remove_links');

function remove_links($content) {
	// Remove the URL: http|ftp|scp)(s)
	preg_match_all($GLOBALS['reg_exUrl'], $content, $matches);
	foreach ($matches[0] as $pattern) {
			if (strposa($pattern,$GLOBALS['domain_list'])) {
				$content = str_replace_first($pattern, $GLOBALS['url_base'],$content);
			}
	}
	// Remove the remain domains
	preg_match_all($GLOBALS['reg_exDomain'], $content, $matches);
    foreach ($matches[0] as $pattern) {
			if (in_array(str_ireplace('www.', '', parse_url('http://'. $pattern,PHP_URL_HOST)), $GLOBALS['host_list'])) {
				if (strpos($pattern, "www") === 0) {
		 	        $content = str_replace_first($pattern, $GLOBALS['domain_base'], $content);		
				}
				 else {
				 		$pos = strpos($content, $pattern); // get the position of the pattern
				 		$letter_before = substr($content, $pos -1, 1); // get the letter before
				 		if (($letter_before == "\n") || ($letter_before ==" ") || ($letter_before ==">"))  {
				 	        $content = str_replace_first($pattern, $GLOBALS['domain_base'], $content);			
			 	        } else {
				 	        $content = str_replace_first(' ' . $pattern, ' ' . $GLOBALS['domain_base'], $content);			
			 	        }	
				}
            }
    }
    return $content;
}

function strposa($haystack, $needle, $offset=0) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $query) {
        if(stripos($haystack, $query, $offset) !== false) return true; // stop on first true result
    }
    return false;
}

function str_replace_first($search, $replace, $subject) {
    return implode($replace, explode($search, $subject, 2));
}