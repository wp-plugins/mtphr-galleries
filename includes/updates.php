<?php

/* --------------------------------------------------------- */
/* !Update the galleries to 2.0.0 */
/* --------------------------------------------------------- */

function mtphr_galleries_update_2_0_0() {

	$update = get_option( 'mtphr_galleries_update_2_0_0' );
	if( $update != 'true' ) {

		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'mtphr_gallery',
			'post_status' => 'any'
		);
		$galleries = get_posts( $args );
		if( is_array($galleries) && count($galleries) > 0 ) {
			foreach( $galleries as $gallery ) {
				
				$updated_resources = array();
				$resources = get_post_meta( $gallery->ID, '_mtphr_gallery_resources', true );
				if( is_array($resources) && count($resources) > 0 ) {
					foreach( $resources as $resource ) {
						
						$type = mtphr_galleries_resource_type( $resource );
						switch( $type ) {
						
							case 'image':
								$post = get_post( $resource );
								if( $post ) {
									$updated_resources[] = array(
										'id' => $resource,
										'type' => 'image'
									);
								}
								break;
								
							case 'video':
								$post = get_post( $resource );
								if( $post ) {
									$updated_resources[] = array(
										'id' => $resource,
										'type' => 'video'
									);
								}
								break;
								
							case 'audio':
								$post = get_post( $resource );
								if( $post ) {
									$updated_resources[] = array(
										'id' => $resource,
										'type' => 'audio'
									);
								}
								break;
								
							case 'youtube':
								parse_str( $resource, $resource_array );
								if( isset($resource_array['v']) ) {
									$resource = $resource_array['v'];
								} else {
									reset( $resource_array );
									$resource = key( $resource_array );
								}
								$updated_resources[] = array(
									'id' => $resource,
									'type' => 'youtube'
								);
								break;
								
							case 'vimeo':
								$resource_array = explode( '/', $resource );
								$resource = end( $resource_array );
								$updated_resources[] = array(
									'id' => $resource,
									'type' => 'vimeo'
								);
								break;
						}
					}
				}
				update_post_meta( $gallery->ID, '_mtphr_gallery_resources', $updated_resources );
			}
		}	
		update_option( 'mtphr_galleries_update_2_0_0', 'true' );
	}
}
add_action( 'init', 'mtphr_galleries_update_2_0_0' );