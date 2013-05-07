<?php
/**
 * Put all the general code here
 *
 * @package Metaphor Galleries
 */




/**
 * Return a value from the options table if it exists,
 * or return a default value
 *
 * @since 1.0.0
 */
function mtphr_galleries_settings() {

	// Get the options
	$settings = get_option('mtphr_galleries_settings', array());

	$defaults = array(
		'slug' => 'galleries',
		'singular_label' => __( 'Gallery', 'mtphr-galleries' ),
		'plural_label' => __( 'Galleries', 'mtphr-galleries' )
	);

	return wp_parse_args( $settings, $defaults );
}




/**
 * Add the thumbnail support
 *
 * @since 1.0.0
 */
add_theme_support( 'post-thumbnails', array('mtphr_gallery') );

/**
 * Add WooSidebars support
 *
 * @since 1.0.0
 */
add_post_type_support( 'mtphr_gallery', 'woosidebars' );




/**
 * Return the resource meta
 *
 * @since 1.0.0
 */
function mtphr_gallery_resource_meta( $post_id=false ) {
	$post_id = $post_id ? $post_id : get_the_id();
	return get_post_meta( $post_id, '_mtphr_gallery_resources', true );
}




/**
 * Return the resource type
 *
 * @since 1.0.0
 */
function mtphr_galleries_type( $url ) {
	return mtphr_galleries_metaboxer_gallery_type( $url );
}




/**
 * Display the gallery thumbnail
 *
 * @since 1.0.0
 */
function mtphr_gallery_thumbnail( $post_id=false ) {
	echo get_mtphr_gallery_thumbnail( $post_id );
}

/**
 * Return the gallery thumbnail
 *
 * @since 1.0.0
 */
function get_mtphr_gallery_thumbnail( $post_id=false, $width=false, $height=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	// Return the featured image thumbnail
	if( $att_id = get_post_thumbnail_id( $post_id ) ) {
		return wp_get_attachment_image( $att_id, apply_filters('mtphr_galleries_thumbnail_size', 'thumbnail') );

	} else {

		// Return the first attachment thumbnail
		$resources = mtphr_gallery_resource_meta( $post_id );
		if( is_array($resources) && isset($resources[0]) ) {
			return mtphr_galleries_metaboxer_gallery_thumbnail( $resources[0], false, false, apply_filters('mtphr_galleries_thumbnail_size', 'thumbnail') );
		}
	}

	return false;
}




/**
 * Display the gallery resource
 *
 * @since 1.0.0
 */
function mtphr_gallery_resource( $url ) {
	echo get_mtphr_gallery_resource( $url );
}

/**
 * Return the gallery resource
 *
 * @since 1.0.0
 */
function get_mtphr_gallery_resource( $url, $width=false, $height=false ) {
	return mtphr_galleries_metaboxer_gallery_resource( $url, $width, $height, apply_filters('mtphr_galleries_thumbnail_size', 'large') );
}




/**
 * Return the count of the gallery resources
 *
 * @since 1.0.0
 */
function mtphr_gallery_count( $post_id=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	$count = 0;
	$resources = mtphr_gallery_resource_meta( $post_id );
	if( is_array($resources) ) {
		foreach( $resources as $i=>$resource ) {
			if( mtphr_galleries_metaboxer_gallery_type( $resource ) ) {
				$count++;
			}
		}
	}
	return $count;
}




/**
 * Display the gallery
 *
 * @since 1.0.0
 */
function mtphr_gallery( $post_id=false, $width=false, $height=false, $args=false ) {
	echo get_mtphr_gallery( $post_id, $width, $height, $args );
}

/**
 * Return the gallery
 *
 * @since 1.0.0
 */
function get_mtphr_gallery( $post_id=false, $width=false, $height=false, $args=false, $class='' ) {

	// Get the post id
	$post_id = $post_id ? $post_id : get_the_id();

	// Get all the custom data
	$custom_fields = get_post_custom( $post_id );
	$meta_data = array();
	foreach( $custom_fields as $key => $value ) {
		$meta_data[$key] = maybe_unserialize( $value[0] );
	}

	// Manually set the layout
	$meta_data['_mtphr_gallery_slider_layout'] = array('navigation', 'gallery');

	// Override meta data with supplied attributes
	if( is_array($args) ) {
		foreach( $args as $key => $value ) {
			$meta_data["_mtphr_gallery_{$key}"] = $value;
		}
	}

	// Extract the metadata array into variables
	extract( $meta_data );

	// Create the gallery
	$gallery_id = 'mtphr-gallery-'.$post_id;
	$html = '<div id="'.$gallery_id.'" class="mtphr-gallery '.sanitize_html_class($class).'">';

	// Set the layout
	foreach( $_mtphr_gallery_slider_layout as $asset ) {

		switch( $asset ) {

			case 'navigation':
				if( isset($_mtphr_gallery_slider_control_nav) && $_mtphr_gallery_slider_control_nav ) {
					$html .= get_mtphr_galleries_navigation( $post_id );
				}
				break;

			case 'gallery':
				$html .= '<div class="mtphr-gallery-wrapper">';
				$html .= apply_filters( 'mtphr_gallery', get_mtphr_gallery_resources($post_id), $post_id );
				if( isset($_mtphr_gallery_slider_directional_nav) && $_mtphr_gallery_slider_directional_nav ) {
					$html .= '<a href="#" class="mtphr-gallery-nav-prev">'.__('Previous', 'mtphr-galleries').'</a><a href="#" class="mtphr-gallery-nav-next">'.__('Next', 'mtphr-galleries').'</a>';
				}
				$html .= '</div>';
				break;
		}
	}

	$html .= '</div>';

	// Add an after action to add scripts
	ob_start();
	do_action( 'mtphr_gallery_slider_after', $gallery_id, $meta_data );
	$html .= ob_get_clean();

	// Return the gallery
	return $html;
}




/**
 * Return the gallery navigation
 *
 * @since 1.0.0
 */
function get_mtphr_galleries_navigation( $post_id=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	$navigation = '<div class="mtphr-gallery-navigation">';

	$count = mtphr_gallery_count();
	for( $i=0; $i<$count; $i++ ) {
		$link = '<a href="'.$i.'">'.intval($i+1).'</a>';
		$navigation .= apply_filters( 'mtphr_gallery_navigation', $link, $i, $post_id );
	}

	$navigation .= '</div>';

	return $navigation;
}




/**
 * Return the gallery resources
 *
 * @since 1.0.0
 */
function get_mtphr_gallery_resources( $post_id=false, $width=false, $height=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	$resources = mtphr_gallery_resource_meta( $post_id );

	if( is_array($resources) && isset($resources[0]) ) {

		$gallery = '<div class="mtphr-gallery-resource-container">';
		foreach( $resources as $i=>$resource ) {
			if( mtphr_galleries_metaboxer_gallery_type( $resource ) ) {
				$div = '<div id="mtphr-gallery-resource-'.$i.'" class="mtphr-gallery-resource">'.get_mtphr_gallery_resource($resource, $width, $height).'</div>';
				$gallery .=  apply_filters( 'mtphr_gallery_resource', $div, $resource, $width, $height, $post_id );
			}
		}
		$gallery .= '</div>';
		return $gallery;
	}
	return false;
}




/**
 * Set a maximum excerpt length
 *
 * @since 1.0.0
 */
function mtphr_galleries_excerpt( $length = 200, $more = '&hellip;'  ) {
	echo get_mtphr_galleries_excerpt( $length, $more );
}
function get_mtphr_galleries_excerpt( $length = 200, $more = '&hellip;' ) {
	$excerpt = get_the_excerpt();
	$length++;

	$output = '';
	if( function_exists('mb_strlen') ) {
		if ( mb_strlen( $excerpt ) > $length ) {
			$subex = mb_substr( $excerpt, 0, $length - mb_strlen($more) );
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				$output .= mb_substr( $subex, 0, $excut );
			} else {
				$output .= $subex;
			}
			$output .= $more;
		} else {
			$output .= $excerpt;
		}
	} else {
		if ( strlen( $excerpt ) > $length ) {
			$subex = substr( $excerpt, 0, $length - strlen($more) );
			$exwords = explode( ' ', $subex );
			$excut = - ( strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				$output .= substr( $subex, 0, $excut );
			} else {
				$output .= $subex;
			}
			$output .= $more;
		} else {
			$output .= $excerpt;
		}
	}
	return $output;
}



/**
 * Minify scripts for output
 *
 * @since 1.0.0
 */
function mtphr_galleries_compress_script( $str ) {

	$lines = explode( "\n", $str );
	$output = '';
	foreach( $lines as $line ) {
		if( substr(trim($line), 0, 3) != '// ' ) {
			$output .= trim( $line );
		}
	}

	return $output;
}

