<?php

/* --------------------------------------------------------- */
/* !Get the gallery object type - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_resource_type') ) {
function mtphr_galleries_resource_type( $resource ) {

	$type = is_array( $resource ) ? $resource['type'] : '';
	if( $type == '' ) {
	
		$post = get_post( $resource );
		if( $post ) {
			$type = substr($post->post_mime_type, 0, 5);
		}
	
		$resource = esc_url_raw( $resource );
	
		if( $type == '' && strpos($resource,'.jpg') !== false ) {
	    $type = 'image';
	    
		} elseif( $type == '' && strpos($resource,'.jpeg') !== false ) {
	    $type = 'image';
		
		} elseif( $type == '' && strpos($resource,'.png') !== false ) {
	    $type = 'image';
		
		} elseif( $type == '' && strpos($resource,'.gif') !== false ) {
			$type = 'image';
		
		} elseif( $type == '' && strpos($resource,'.mp3') !== false ) {
			$type = 'audio';
		
		} elseif( $type == '' && strpos($resource,'.m4v') !== false ) {
			$type = 'video';
		
		} elseif( $type == '' && strpos($resource,'.mp4') !== false ) {
			$type = 'video';
		
		} elseif( $type == '' && strpos($resource,'.mov') !== false ) {
			$type = 'video';
		
		} elseif( $type == '' && strpos($resource,'.ogg') !== false ) {
			$type = 'video';
		
		} elseif( $type == '' && strpos($resource,'http://vimeo.com/') !== false ) {
	    $type = 'vimeo';
		
		} elseif( $type == '' && strpos($resource,'http://www.youtube.com/watch?v=') !== false ) {
	    $type = 'youtube';
		}
	}

	if( $type == '' ) {
		return false;
	} else {
		return $type;
	}
}
}
if( !function_exists('mtphr_galleries_metaboxer_gallery_type') ) {
function mtphr_galleries_metaboxer_gallery_type( $url ) {
	mtphr_galleries_resource_type( $url );
}
}
if( !function_exists('mtphr_galleries_type') ) {
function mtphr_galleries_type( $url ) {
	return mtphr_galleries_resource_type( $url );
}
}


/* --------------------------------------------------------- */
/* !Get attachment by id - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_attachments_id_by_url') ) {
function mtphr_galleries_attachments_id_by_url( $url ) {
 
	// Split the $url into two parts with the wp-content directory as the separator.
	$parse_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );
 
	// Get the host of the current site and the host of the $url, ignoring www.
	$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
 
	// Return nothing if there aren't any $url parts or if the current host and $url host do not match.
	if ( ! isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host != $file_host ) )
		return;
 
	// Now we're going to quickly search the DB for any attachment GUID with a partial path match.
	// Example: /uploads/2013/05/test-image.jpg
	global $wpdb;
 
	$prefix     = $wpdb->prefix;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM " . $prefix . "posts WHERE guid RLIKE %s;", $parse_url[1] ) );
 
	// Returns null if no attachment is found.
	return isset($attachment[0]);
}
}


/* --------------------------------------------------------- */
/* !Get gallery resources by type - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_resources_by_type') ) {
function mtphr_galleries_resources_by_type( $id, $type ) {
	
	$filtered_resources = array();
	$resources = get_post_meta( $id, '_mtphr_gallery_resources', true );
	if( is_array($resources) && count($resources) > 0 ) {
		foreach( $resources as $resource ) {
			if( isset($resource['type']) && $resource['type'] == $type ) {
				$filtered_resources[] = $resource;
			}
		}
	}
	
	return $filtered_resources;
}
}