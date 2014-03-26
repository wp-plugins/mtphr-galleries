<?php


/* --------------------------------------------------------- */
/* !Display the gallery - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_gallery') ) {
function mtphr_gallery( $post_id=false, $args=false ) {
	echo get_mtphr_gallery( $post_id, $args );
}
}

/* --------------------------------------------------------- */
/* !Return the gallery - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('get_mtphr_gallery') ) {
function get_mtphr_gallery( $post_id=false, $args=false, $class='' ) {

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
				$html .= get_mtphr_gallery_likes();
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
}



/* --------------------------------------------------------- */
/* !Display the gallery thumbnail - 1.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_gallery_thumbnail') ) {
function mtphr_gallery_thumbnail( $post_id=false ) {
	echo get_mtphr_gallery_thumbnail( $post_id );
}
}

/* --------------------------------------------------------- */
/* !Return the gallery thumbnail - 1.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('get_mtphr_gallery_thumbnail') ) {
function get_mtphr_gallery_thumbnail( $post_id=false, $width=false, $height=false ) {

	$post_id = $post_id ? $post_id : get_the_id();

	// Return the featured image thumbnail
	if( $att_id = get_post_thumbnail_id( $post_id ) ) {
		return wp_get_attachment_image( $att_id, apply_filters('mtphr_galleries_thumbnail_size', 'thumbnail') );

	} else {

		// Return the first attachment thumbnail
		$resources = mtphr_gallery_resource_meta( $post_id );
		if( is_array($resources) && isset($resources[0]) ) {
			if( is_array($resources[0]) ) {
				return get_mtphr_gallery_resource_thumbnail( $resources[0] );
			} else {
				return mtphr_galleries_thumbnail( $resources[0], false, false, apply_filters('mtphr_galleries_thumbnail_size', 'thumbnail') );
			}
		}
	}

	return false;
}
}

/* --------------------------------------------------------- */
/* !Return the gallery resource thumbnail - 1.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('get_mtphr_gallery_resource_thumbnail') ) {
function get_mtphr_gallery_resource_thumbnail( $resource ) {

	if( isset( $resource['type']) ) {
		switch( $resource['type'] ) {
			case 'image':
				if( isset($resource['external']) ) {
					return '<img src="'.$resource['id'].'" />';
				} else {
					return wp_get_attachment_image( $resource['id'], apply_filters('mtphr_galleries_thumbnail_size', 'thumbnail') );
				}
				break;
			case 'audio':
				if( isset( $resource['poster']) && $resource['poster'] != '' ) {
					return wp_get_attachment_image( $resource['poster'], apply_filters('mtphr_galleries_thumbnail_size', 'thumbnail') );
				}
				break;
			case 'video':
				if( isset( $resource['poster']) && $resource['poster'] != '' ) {
					return wp_get_attachment_image( $resource['poster'], apply_filters('mtphr_galleries_thumbnail_size', 'thumbnail') );
				}
				break;
			case 'youtube':
				if( isset( $resource['poster']) && $resource['poster'] != '' ) {
					return wp_get_attachment_image( $resource['poster'], apply_filters('mtphr_galleries_thumbnail_size', 'thumbnail') );
				} else {
					$url = 'http://img.youtube.com/vi/'.$resource['id'].'/0.jpg';
					return '<img src="'.$url.'" />';
				}
				break;
			case 'vimeo':
				if( isset( $resource['poster']) && $resource['poster'] != '' ) {
					return wp_get_attachment_image( $resource['poster'], apply_filters('mtphr_galleries_thumbnail_size', 'thumbnail') );
				} else {
					$vimeo = simplexml_load_file('http://vimeo.com/api/v2/video/'.$resource['id'].'.xml');
					$url = $vimeo->video->thumbnail_large;
					return '<img src="'.$url.'" />';
				}
				break;
		}
	}
	return false;
}
}



/* --------------------------------------------------------- */
/* !Render the preview button - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_gallery_admin_preview_button') ) {
function mtphr_gallery_admin_preview_button( $link ) {
	return '<a class="mtphr-gallery-thumbnail-view" href="'.$link.'" target="_blank"><i class="mtphr-galleries-icon-preview"></i></a>';
}
}


/* --------------------------------------------------------- */
/* !Render the delete button - 2.0.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_gallery_admin_delete_button') ) {
function mtphr_gallery_admin_delete_button() {
	return '<a class="mtphr-gallery-thumbnail-delete" href="#"><i class="mtphr-galleries-icon-delete-1"></i></a>';
}
}


/* --------------------------------------------------------- */
/* !Render an image thumb - 2.0.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_gallery_admin_render_image_field') ) {
function mtphr_gallery_admin_render_image_field( $resource, $pos=0, $name_resources ) {

	$link = isset($resource['external']) ? $resource['id'] : get_edit_post_link($resource['id']);

  echo '<td class="mtphr-gallery-thumbnail mtphr-gallery-image-thumbnail">';
  	echo '<div class="mtphr-gallery-thumbnail-contents">';
			echo '<input class="mtphr-galleries-id" type="hidden" name="'.$name_resources.'['.$pos.'][id]" data-prefix="'.$name_resources.'" data-param="id" value="'.$resource['id'].'" />';
			echo '<input class="mtphr-galleries-type" type="hidden" name="'.$name_resources.'['.$pos.'][type]" data-prefix="'.$name_resources.'" data-param="type" value="image" />';
			if( isset($resource['external']) ) {
				echo '<input class="mtphr-galleries-type" type="hidden" name="'.$name_resources.'['.$pos.'][external]" data-prefix="'.$name_resources.'" data-param="external" value="true" />';
				echo mtphr_gallery_admin_render_external_image_thumb( $resource['id'] );
			} else {
				echo mtphr_gallery_admin_render_image_thumb( $resource['id'] );
			}
			echo '<div class="mtphr-galleries-admin-thumb-title clearfix">';
				if( $title = get_the_title( $resource['id'] ) ) {
					echo '<span>'.$title.'</span>';
				} else {
					echo '<span>'.$resource['id'].'</span>';
				}
				echo '<span class="mtphr-galleries-admin-thumb-title-type">'.__('Image', 'mtphr-galleries').'</span>';
				echo mtphr_gallery_admin_preview_button( $link );
				echo mtphr_gallery_admin_delete_button();
			echo '</div>';
		echo '</div>';
	echo '</td>';
}
}

if( !function_exists('mtphr_gallery_admin_render_image_thumb') ) {
function mtphr_gallery_admin_render_image_thumb( $thumbnail='' ) {

	$thumb = wp_get_attachment_image( $thumbnail, 'mtphr-galleries-admin-thumb' );
	echo '<div class="mtphr-galleries-admin-thumb">'.$thumb.'</div>';
}
}
if( !function_exists('mtphr_gallery_admin_render_external_image_thumb') ) {
function mtphr_gallery_admin_render_external_image_thumb( $thumbnail='' ) {

	$thumb = '<img src="'.$thumbnail.'" />';
	echo '<div class="mtphr-galleries-admin-thumb">'.$thumb.'</div>';
}
}


/* --------------------------------------------------------- */
/* !Render a video thumb - 2.0.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_gallery_admin_render_video_field') ) {
function mtphr_gallery_admin_render_video_field( $resource, $pos=0, $name_resources ) {

	$thumbnail = isset($resource['poster']) ? $resource['poster'] : '';

  echo '<td class="mtphr-gallery-thumbnail mtphr-gallery-video-thumbnail">';
  	echo '<div class="mtphr-gallery-thumbnail-contents">';
			echo '<input class="mtphr-galleries-id" type="hidden" name="'.$name_resources.'['.$pos.'][id]" data-prefix="'.$name_resources.'" data-param="id" value="'.$resource['id'].'" />';
			echo '<input class="mtphr-galleries-type" type="hidden" name="'.$name_resources.'['.$pos.'][type]" data-prefix="'.$name_resources.'" data-param="type" value="video" />';
			echo '<input class="mtphr-galleries-poster" type="hidden" name="'.$name_resources.'['.$pos.'][poster]" data-prefix="'.$name_resources.'" data-param="poster" value="'.$thumbnail.'" />';
			echo mtphr_gallery_admin_render_video_thumb( $thumbnail, $name_resources );
			echo '<div class="mtphr-galleries-admin-thumb-title clearfix">';
				echo '<span>'.get_the_title( $resource['id'] ).'</span>';
				echo '<span class="mtphr-galleries-admin-thumb-title-type">'.__('Video', 'mtphr-galleries').'</span>';
				echo mtphr_gallery_admin_preview_button( get_edit_post_link($resource['id']) );
				echo mtphr_gallery_admin_delete_button();
			echo '</div>';
		echo '</div>';
	echo '</td>';
}
}

if( !function_exists('mtphr_gallery_admin_render_video_thumb') ) {
function mtphr_gallery_admin_render_video_thumb( $thumbnail='', $name_resources='' ) {
	
	if( $thumbnail == ''  ) {
		$thumb = '<i class="mtphr-galleries-icon-video"></i>';
		$thumb .= '<a class="mtphr-galleries-poster-button add-poster" href="#" data-prefix="'.$name_resources.'">'.__('Add Poster Image', 'mtphr-galleries').'</a>';
	} else {
		$thumb = wp_get_attachment_image( $thumbnail, 'mtphr-galleries-admin-thumb' );
		$thumb .= '<a class="mtphr-galleries-poster-button remove-poster" href="#">'.__('Remove Poster Image', 'mtphr-galleries').'</a>';
	}
	echo '<div class="mtphr-galleries-admin-thumb">'.$thumb.'</div>';
}
}


/* --------------------------------------------------------- */
/* !Render a audio thumb - 2.0.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_gallery_admin_render_audio_field') ) {
function mtphr_gallery_admin_render_audio_field( $resource, $pos=0, $name_resources ) {

	$thumbnail = isset($resource['poster']) ? $resource['poster'] : '';

  echo '<td class="mtphr-gallery-thumbnail mtphr-gallery-audio-thumbnail">';
  	echo '<div class="mtphr-gallery-thumbnail-contents">';
			echo '<input class="mtphr-galleries-id" type="hidden" name="'.$name_resources.'['.$pos.'][id]" data-prefix="'.$name_resources.'" data-param="id" value="'.$resource['id'].'" />';
			echo '<input class="mtphr-galleries-type" type="hidden" name="'.$name_resources.'['.$pos.'][type]" data-prefix="'.$name_resources.'" data-param="type" value="audio" />';
			echo '<input class="mtphr-galleries-poster" type="hidden" name="'.$name_resources.'['.$pos.'][poster]" data-prefix="'.$name_resources.'" data-param="poster" value="'.$thumbnail.'" />';
			echo mtphr_gallery_admin_render_audio_thumb( $thumbnail, $name_resources );
			echo '<div class="mtphr-galleries-admin-thumb-title clearfix">';
				echo '<span>'.get_the_title( $resource['id'] ).'</span>';
				echo '<span class="mtphr-galleries-admin-thumb-title-type">'.__('Audio', 'mtphr-galleries').'</span>';
				echo mtphr_gallery_admin_preview_button( get_edit_post_link($resource['id']) );
				echo mtphr_gallery_admin_delete_button();
			echo '</div>';
		echo '</div>';
	echo '</td>';
}
}

if( !function_exists('mtphr_gallery_admin_render_audio_thumb') ) {
function mtphr_gallery_admin_render_audio_thumb( $thumbnail='', $name_resources='' ) {
	
	if( $thumbnail == ''  ) {
		$thumb = '<i class="mtphr-galleries-icon-audio"></i>';
		$thumb .= '<a class="mtphr-galleries-poster-button add-poster" href="#" data-prefix="'.$name_resources.'">'.__('Add Poster Image', 'mtphr-galleries').'</a>';
	} else {
		$thumb = wp_get_attachment_image( $thumbnail, 'mtphr-galleries-admin-thumb' );
		$thumb .= '<a class="mtphr-galleries-poster-button remove-poster" href="#">'.__('Remove Poster Image', 'mtphr-galleries').'</a>';
	}
	echo '<div class="mtphr-galleries-admin-thumb">'.$thumb.'</div>';
}
}


/* --------------------------------------------------------- */
/* !Render a youtube thumb - 2.0.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_gallery_admin_render_youtube_field') ) {
function mtphr_gallery_admin_render_youtube_field( $resource, $pos=0, $name_resources ) {
	
	$id = isset($resource['id']) ? $resource['id'] : '';
	$title = isset($resource['title']) ? $resource['title'] : $id;
	$description = isset($resource['description']) ? $resource['description'] : '';
	$thumbnail = isset($resource['poster']) ? $resource['poster'] : '';
	$link = isset($resource['link']) ? $resource['link'] : $id;

  echo '<td class="mtphr-gallery-thumbnail mtphr-gallery-youtube-thumbnail">';
  	echo '<div class="mtphr-gallery-thumbnail-contents">';
			echo '<input class="mtphr-galleries-id" type="hidden" name="'.$name_resources.'['.$pos.'][id]" data-prefix="'.$name_resources.'" data-param="id" value="'.$id.'" />';
			echo '<input class="mtphr-galleries-title" type="hidden" name="'.$name_resources.'['.$pos.'][title]" data-prefix="'.$name_resources.'" data-param="title" value="'.$title.'" />';
			echo '<input class="mtphr-galleries-description" type="hidden" name="'.$name_resources.'['.$pos.'][description]" data-prefix="'.$name_resources.'" data-param="description" value="'.$description.'" />';
			echo '<input class="mtphr-galleries-poster" type="hidden" name="'.$name_resources.'['.$pos.'][poster]" data-prefix="'.$name_resources.'" data-param="poster" value="'.$thumbnail.'" />';
			echo '<input class="mtphr-galleries-link" type="hidden" name="'.$name_resources.'['.$pos.'][link]" data-prefix="'.$name_resources.'" data-param="link" value="'.$link.'" />';
			echo '<input class="mtphr-galleries-type" type="hidden" name="'.$name_resources.'['.$pos.'][type]" data-prefix="'.$name_resources.'" data-param="type" value="youtube" />';
			echo '<div class="mtphr-galleries-admin-thumb">'.mtphr_gallery_admin_render_youtube_thumb( $thumbnail ).'</div>';
			echo '<div class="mtphr-galleries-admin-thumb-title clearfix">';
				echo '<span>'.$title.'</span>';
				echo '<span class="mtphr-galleries-admin-thumb-title-type">'.__('YouTube', 'mtphr-galleries').'</span>';
				echo mtphr_gallery_admin_preview_button( $link );
				echo mtphr_gallery_admin_delete_button();
			echo '</div>';
		echo '</div>';
	echo '</td>';
}
}

if( !function_exists('mtphr_gallery_admin_render_youtube_thumb') ) {
function mtphr_gallery_admin_render_youtube_thumb( $thumbnail='', $pos=0 ) {

	$parent = isset($_GET['post']) ? $_GET['post'] : '';
	
	if( $thumbnail != '' && get_post($thumbnail) ) {
		$thumb = wp_get_attachment_image( $thumbnail, 'mtphr-galleries-admin-thumb', false, array('alt'=>$thumbnail) );
		$thumb .= '<a class="mtphr-galleries-update-poster-button" href="#'.$parent.'">'.__('Refresh Poster Image', 'mtphr-galleries').'<span class="mtphr-gallery-spinner-small"></span></a>';
	} else {
		$thumb = '<i class="mtphr-galleries-icon-youtube"></i>';
		$thumb .= '<a class="mtphr-galleries-update-poster-button" href="#'.$parent.'">'.__('Create Poster Image', 'mtphr-galleries').'<span class="mtphr-gallery-spinner-small"></span></a>';
	}
	return $thumb;
}
}


/* --------------------------------------------------------- */
/* !Render a vimeo thumb - 2.0.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_gallery_admin_render_vimeo_field') ) {
function mtphr_gallery_admin_render_vimeo_field( $resource, $pos=0, $name_resources ) {

	$id = isset($resource['id']) ? $resource['id'] : '';
	$title = isset($resource['title']) ? $resource['title'] : $id;
	$description = isset($resource['description']) ? $resource['description'] : '';
	$thumbnail = isset($resource['poster']) ? $resource['poster'] : '';
	$link = isset($resource['link']) ? $resource['link'] : $id;

  echo '<td class="mtphr-gallery-thumbnail mtphr-gallery-vimeo-thumbnail">';
  	echo '<div class="mtphr-gallery-thumbnail-contents">';
			echo '<input class="mtphr-galleries-id" type="hidden" name="'.$name_resources.'['.$pos.'][id]" data-prefix="'.$name_resources.'" data-param="id" value="'.$id.'" />';
			echo '<input class="mtphr-galleries-title" type="hidden" name="'.$name_resources.'['.$pos.'][title]" data-prefix="'.$name_resources.'" data-param="title" value="'.$title.'" />';
			echo '<input class="mtphr-galleries-description" type="hidden" name="'.$name_resources.'['.$pos.'][description]" data-prefix="'.$name_resources.'" data-param="description" value="'.$description.'" />';
			echo '<input class="mtphr-galleries-poster" type="hidden" name="'.$name_resources.'['.$pos.'][poster]" data-prefix="'.$name_resources.'" data-param="poster" value="'.$thumbnail.'" />';
			echo '<input class="mtphr-galleries-link" type="hidden" name="'.$name_resources.'['.$pos.'][link]" data-prefix="'.$name_resources.'" data-param="link" value="'.$link.'" />';
			echo '<input class="mtphr-galleries-type" type="hidden" name="'.$name_resources.'['.$pos.'][type]" data-prefix="'.$name_resources.'" data-param="type" value="vimeo" />';
			echo '<div class="mtphr-galleries-admin-thumb">'.mtphr_gallery_admin_render_vimeo_thumb( $thumbnail ).'</div>';
			echo '<div class="mtphr-galleries-admin-thumb-title clearfix">';
				echo '<span>'.$title.'</span>';
				echo '<span class="mtphr-galleries-admin-thumb-title-type">'.__('Vimeo', 'mtphr-galleries').'</span>';
				echo mtphr_gallery_admin_preview_button( $link );
				echo mtphr_gallery_admin_delete_button();
			echo '</div>';
		echo '</div>';
	echo '</td>';
}
}

if( !function_exists('mtphr_gallery_admin_render_vimeo_thumb') ) {
function mtphr_gallery_admin_render_vimeo_thumb( $thumbnail='' ) {

	$parent = isset($_GET['post']) ? $_GET['post'] : '';
	
	if( $thumbnail != '' && get_post($thumbnail) ) {
		$thumb = wp_get_attachment_image( $thumbnail, 'mtphr-galleries-admin-thumb', false, array('alt'=>$thumbnail) );
		$thumb .= '<a class="mtphr-galleries-update-poster-button" href="#'.$parent.'">'.__('Refresh Poster Image', 'mtphr-galleries').'<span class="mtphr-gallery-spinner-small"></span></a>';
	} else {
		$thumb = '<i class="mtphr-galleries-icon-vimeo"></i>';
		$thumb .= '<a class="mtphr-galleries-update-poster-button" href="#'.$parent.'">'.__('Create Poster Image', 'mtphr-galleries').'<span class="mtphr-gallery-spinner-small"></span></a>';
	}
	return $thumb;
}
}