<?php

/* --------------------------------------------------------- */
/* !Add WooSidebars support - 1.0.0 */
/* --------------------------------------------------------- */

add_post_type_support( 'mtphr_gallery', 'woosidebars' );



/* --------------------------------------------------------- */
/* !Setup localization - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_galleries_localization() {
  load_plugin_textdomain( 'mtphr-galleries', false, 'mtphr-galleries/languages/' );
}
add_action( 'plugins_loaded', 'mtphr_galleries_localization' );



/* --------------------------------------------------------- */
/* !Setup custom image sizes - 2.0.0 */
/* --------------------------------------------------------- */

function mtphr_galleries_image_sizes() {

	if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'mtphr-galleries-admin-thumb', 250, 160, true );
		add_image_size( 'mtphr-galleries-image', 1200 );
	}
}
add_action( 'plugins_loaded', 'mtphr_galleries_image_sizes' );


 
/* --------------------------------------------------------- */
/* !Return the resource meta - 1.0.0 */
/* --------------------------------------------------------- */

function mtphr_gallery_resource_meta( $post_id=false ) {
	$post_id = $post_id ? $post_id : get_the_id();
	return get_post_meta( $post_id, '_mtphr_gallery_resources', true );
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
	return mtphr_galleries_resource( $url, $width, $height, apply_filters('mtphr_galleries_thumbnail_size', 'large') );
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
			if( mtphr_galleries_resource_type( $resource ) ) {
				$count++;
			}
		}
	}
	return apply_filters( 'mtphr_gallery_count', $count, $post_id );
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
 * @since 1.0.5
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
	if( !is_array($_mtphr_gallery_slider_layout) ) {
		$_mtphr_gallery_slider_layout = explode(',', $_mtphr_gallery_slider_layout);
	}

	foreach( $_mtphr_gallery_slider_layout as $asset ) {

		switch( $asset ) {

			case 'like':
				if( isset($_mtphr_gallery_slider_control_nav) && $_mtphr_gallery_slider_control_nav ) {
					$html .= get_mtphr_gallery_likes();
				}
				break;

			case 'navigation':
				if( isset($_mtphr_gallery_slider_control_nav) && $_mtphr_gallery_slider_control_nav ) {
					$html .= get_mtphr_galleries_navigation( $post_id );
				}
				break;

			case 'gallery':
				$html .= '<div class="mtphr-gallery-wrapper">';
				$html .= apply_filters( 'mtphr_gallery', get_mtphr_gallery_resources($post_id), $post_id );
				if( isset($_mtphr_gallery_slider_directional_nav) && $_mtphr_gallery_slider_directional_nav ) {
					$html .= '<a href="#" class="mtphr-gallery-nav-prev" rel="nofollow">'.apply_filters( 'mtphr_gallery_navigation_previous', __('Previous', 'mtphr-galleries') ).'</a>';
					$html .= '<a href="#" class="mtphr-gallery-nav-next" rel="nofollow">'.apply_filters( 'mtphr_gallery_navigation_next', __('Next', 'mtphr-galleries') ).'</a>';
				}
				$html .= '</div>';
				break;
		}
	}

	$html .= '</div>';

	// Add an after action
	ob_start();
	do_action( 'mtphr_gallery_slider_after', $gallery_id, $meta_data );
	$html .= ob_get_clean();

	// Add to the gallery scripts to display in the footer
	global $mtphr_galleries_scripts;
	$rotate = 0; $pause = 0; $nav_autohide = 0; $nav_reverse = 0;
	if( isset($_mtphr_gallery_slider_auto_rotate) ) {
		$rotate = $_mtphr_gallery_slider_auto_rotate ? 1 : 0;
	}
	if( isset($_mtphr_gallery_slider_pause) ) {
		$pause = $_mtphr_gallery_slider_pause ? 1 : 0;
	}
	if( isset($_mtphr_gallery_slider_directional_nav_reverse) ) {
		$nav_reverse = $_mtphr_gallery_slider_directional_nav_reverse ? 1 : 0;
	}
	$mtphr_galleries_scripts[] = array(
		'id' => $gallery_id,
		'rotate_type' => $_mtphr_gallery_slider_type,
		'auto_rotate' => $rotate,
		'rotate_delay' => intval($_mtphr_gallery_slider_delay),
		'rotate_pause' => $pause,
		'rotate_speed' => intval($_mtphr_gallery_slider_speed),
		'rotate_ease' => $_mtphr_gallery_slider_ease,
		'nav_reverse' => $nav_reverse
	);

	// Return the gallery
	return $html;
}




/**
 * Return the gallery navigation
 *
 * @since 1.0.5
 */
function get_mtphr_galleries_navigation( $post_id=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	$navigation = '<div class="mtphr-gallery-navigation">';

	$count = mtphr_gallery_count( $post_id );
	for( $i=0; $i<$count; $i++ ) {
		$link = '<a href="'.$i.'" rel="nofollow">'.intval($i+1).'</a>';
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
			$resource = apply_filters( 'mtphr_galleries_resource_data', $resource );
			$div = '<div id="mtphr-gallery-resource-'.$i.'" class="mtphr-gallery-resource mtphr-gallery-resource-'.$resource['type'].'">';
				$div .= get_mtphr_gallery_resource( $resource, $width, $height );
			$div .= '</div>';
			$gallery .=  apply_filters( 'mtphr_gallery_resource', $div, $resource, $width, $height, $post_id );
		}
		$gallery .= '</div>';
		return $gallery;
	}
	return false;
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



/* --------------------------------------------------------- */
/* !Return the gallery resource 1.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_resource') ) {
function mtphr_galleries_resource( $resource, $width=false, $height=false, $size='mtphr-galleries-image' ) {
	
	switch( $resource['type'] ) {
		case 'image':
			if( isset($resource['external']) ) {
				return '<img src="'.$resource['id'].'" />';
			} else {
				$post = get_post( $resource['id'] );
				return wp_get_attachment_image( $post->ID, $size );	
			}		
			break;

		case 'video':
			$post = get_post( $resource['id'] );
			$poster = isset($resource['poster']) ? wp_get_attachment_image_src($resource['poster'], $size) : false;
			$poster = $poster ? $poster[0] : '';
			$video = '<video class="mtphr-galleries-video mtphr-galleries-mep" src="'.$post->guid.'" width="100%" height="100%" style="width:100%;height:100%;" poster="'.$poster.'">';
				$video .= '<source src="'.$post->guid.'" type="'.$post->post_mime_type.'">';
			$video .= '</video>';
			return $video;

		case 'audio':
			$post = get_post( $resource['id'] );
			$audio = '';
			if( isset($resource['poster']) && $resource['poster'] != '' ) {
				$audio .= wp_get_attachment_image( $resource['poster'], $size );	
			}
			$audio .= '<audio class="mtphr-galleries-audio mtphr-galleries-mep" src="'.$post->guid.'" width="100%" height="100%" style="width:100%;height:100%;">';
				$audio .= '<source src="'.$post->guid.'" type="'.$post->post_mime_type.'">';
			$audio .= '</audio>';
			return $audio;

		case 'vimeo':
			$width = $width ? $width : 640;
			$height = $height ? $height : intval( $width/16*9 );
			return '<iframe class="mtphr-galleries-vimeo mtphr-galleries-iframe" src="http://player.vimeo.com/video/'.$resource['id'].'?title=0&amp;byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
			break;

		case 'youtube':
			$width = $width ? $width : 640;
			$height = $height ? $height : intval( $width/16*9 );
			return '<iframe class="mtphr-galleries-youtube mtphr-galleries-iframe" width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$resource['id'].'?rel=0&showinfo=0?wmode=opaque" frameborder="0" allowfullscreen></iframe>';
			break;
	}

	return false;
}
}
if( !function_exists('mtphr_galleries_metaboxer_gallery_resource') ) {
function mtphr_galleries_metaboxer_gallery_resource( $url, $width=false, $height=false, $size='medium' ) {
	mtphr_galleries_resource( $url, $width, $height, $size );
}
}



/* --------------------------------------------------------- */
/* !Return the gallery thumbnail - 1.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_thumbnail') ) {
function mtphr_galleries_thumbnail( $url, $width=false, $height=false, $size='medium' ) {

	$type = '';
	$thumb = '';

	$post = get_post( $url );
	if( $post ) {
		$type = substr($post->post_mime_type, 0, 5);
		if( $type == 'image' ) {
			$img = wp_get_attachment_image_src( $post->ID, $size );
			$url = $img[0];
		}
	}

	// Get the resource type
	if( $type == '' ) {
		$type = mtphr_galleries_resource_type( $url );
	}

	switch( $type ) {
		case 'image':
			return '<img src="'.$url.'" width="'.$width.'" height="'.$height.'" />';
			break;

		case 'vimeo':
			$id = substr($url, 17);
			$vimeo = simplexml_load_file('http://vimeo.com/api/v2/video/'.$id.'.xml');
			$url = $vimeo->video->thumbnail_large;
			return '<img src="'.$url.'" width="'.$width.'" height="'.$height.'" />';
			break;

		case 'youtube':
			$id = substr($url, 31);
			$url = 'http://img.youtube.com/vi/'.$id.'/0.jpg';
			return '<img src="'.$url.'" width="'.$width.'" height="'.$height.'" />';
			break;

		case 'video':
			return false;

		case 'audio':
			return false;
	}

	return false;
}
}
if( !function_exists('mtphr_galleries_metaboxer_gallery_thumbnail') ) {
function mtphr_galleries_metaboxer_gallery_thumbnail( $url, $width=false, $height=false, $size='medium' ) {
	mtphr_galleries_thumbnail( $url, $width, $height, $size );
}
}



/* --------------------------------------------------------- */
/* !Remove the gallery data widget - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_galleries_remove_widget( $sidebars_widgets ) {

	if( !is_admin() && !(is_single() && get_post_type() == 'mtphr_gallery') ) {

		foreach( $sidebars_widgets as $s=>$sidebars ) {
			$remove = array();
			if( is_array($sidebars) ) {
				foreach( $sidebars as $i=>$widget ) {
					if( strpos($widget,'mtphr-gallery-data') !== false ) {
						$remove[] = $i;
					}
				}
			}
			$remove = array_reverse( $remove );
			foreach( $remove as $r ) {
				unset( $sidebars_widgets[$s][$r] );
			}
		}
	}

	return $sidebars_widgets;
}
add_filter( 'sidebars_widgets', 'mtphr_galleries_remove_widget' );



/* --------------------------------------------------------- */
/* !Return the like count - 1.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('get_mtphr_gallery_likes') ) {
function get_mtphr_gallery_likes( $post_id = false ) {

	if( !$post_id ) {
		$post_id = get_the_id();
	}

	$likes = get_post_meta( $post_id, '_mtphr_gallery_likes', true );
	$class = isset($_COOKIE['mtphr_gallery_likes_'.sanitize_key(get_bloginfo('blogname')).'_'.$post_id]) ? 'mtphr-gallery-likes active' : 'mtphr-gallery-likes';
	$html = '<a class="'.$class.'" href="#'.$post_id.'" rel="nofollow"><span class="like-loader"></span><i class="mtphr-galleries-icon-like"></i><span class="mtphr-gallery-likes-count">'.number_format(intval($likes)).'</span></a>';
	return $html;
}
}


