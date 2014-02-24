<?php

function mtphr_galleries_resource_data_update( $resource ) {
	
	if( !is_array($resource) ) {
		
		$updated_resource = array();

		$updated_resource['type'] = $type = mtphr_galleries_resource_type( $resource );
		
		switch( $type ) {
			case 'vimeo':
				// Strip out the id from url
				$value_array = explode( '/', $resource );
				$value = end( $value_array );
				$updated_resource['id'] = $value;
				$updated_resource['link'] = $resource;
				break;
				
			case 'youtube':
				// Strip out the id from url
				parse_str( $resource, $value_array );
				if( isset($value_array['v']) ) {
					$value = $value_array['v'];
				} else {
					$value = reset( $value_array );
				}
				$updated_resource['id'] = $value;
				$updated_resource['link'] = $resource;
				break;
				
			default:
				$updated_resource['id'] = $resource;
				break;
		}

		if( !get_post($resource) ) {
			$updated_resource['external'] = true;
		}
		
		return $updated_resource;
	} 	
	
	return $resource;
}
add_filter( 'mtphr_galleries_resource_data', 'mtphr_galleries_resource_data_update', 1 );
add_filter( 'mtphr_galleries_metabox_resource_data', 'mtphr_galleries_resource_data_update', 1 );