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



/* --------------------------------------------------------- */
/* !Add the gallery rotator - 2.0.5 */
/* --------------------------------------------------------- */

function mtphr_gallery_add_rotator( $post_id ) {
	echo apply_filters( 'mtphr_gallery', get_mtphr_gallery_resources($post_id), $post_id );
}
add_action( 'mtphr_gallery_wrapper', 'mtphr_gallery_add_rotator', 10 );



/* --------------------------------------------------------- */
/* !Add the directional navigation - 2.0.13 */
/* --------------------------------------------------------- */

function mtphr_gallery_add_directional_nav( $post_id, $meta_data ) {
	
	$settings = mtphr_galleries_settings();
	
	// Extract the metadata array into variables
	extract( $meta_data );
	
	$directional_nav = ($settings['global_slider_settings'] == 'on') ? ( $settings['slider_directional_nav'] == 'on' ) : (isset($_mtphr_gallery_slider_directional_nav) && $_mtphr_gallery_slider_directional_nav);
	
	$resources = mtphr_gallery_resource_meta( $post_id );
	if( is_array($resources) && count($resources) > 1 ) {
		
		$html = '';
		if( $directional_nav ) {
			$html .= '<a href="#" class="mtphr-gallery-nav-prev" rel="nofollow">'.apply_filters( 'mtphr_gallery_navigation_previous', __('Previous', 'mtphr-galleries') ).'</a>';
			$html .= '<a href="#" class="mtphr-gallery-nav-next" rel="nofollow">'.apply_filters( 'mtphr_gallery_navigation_next', __('Next', 'mtphr-galleries') ).'</a>';
		}
		echo $html;
		
	}
}
add_action( 'mtphr_gallery_wrapper', 'mtphr_gallery_add_directional_nav', 15, 2 );