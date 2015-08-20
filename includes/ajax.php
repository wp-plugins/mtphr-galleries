<?php

/* --------------------------------------------------------- */
/* !Display a gallery thumb via ajax - 2.0.3 */
/* --------------------------------------------------------- */

function mtphr_gallery_thumb_ajax() {

	// Get access to the database
	global $wpdb;

	// Check the nonce
	check_ajax_referer( 'mtphr_galleries', 'security' );

	// Get variables
	$type = $_POST['type'];
	$name_resources = isset($_POST['name_resources']) ? $_POST['name_resources'] : '';
	$attachments = $_POST['attachments'];

	// Display the files
	foreach( $attachments as $attachment ) {
		//print_r($attachment);
		if( $attachment['type'] == 'image' ) {
			if( $type == 'field' ) {
				mtphr_gallery_admin_render_image_field( $attachment, false, $name_resources );
			} else {
				mtphr_gallery_admin_render_image_thumb( $attachment['id'] );
			}
		} elseif( $attachment['type'] == 'video' ) {
			if( $type == 'field' ) {
				mtphr_gallery_admin_render_video_field( $attachment, false, $name_resources );
			} else {
				mtphr_gallery_admin_render_video_thumb( $attachment['id'] );
			}
		} elseif( $attachment['type'] == 'audio' ) {
			if( $type == 'field' ) {
				mtphr_gallery_admin_render_audio_field( $attachment, false, $name_resources );
			} else {
				mtphr_gallery_admin_render_audio_thumb( $attachment['id'] );
			}
		}
	}

	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_mtphr_gallery_thumb_ajax', 'mtphr_gallery_thumb_ajax' );


/* --------------------------------------------------------- */
/* !Display an external gallery thumb via ajax - 2.0.3 */
/* --------------------------------------------------------- */

function mtphr_gallery_external_thumb_ajax() {

	// Get access to the database
	global $wpdb;

	// Check the nonce
	check_ajax_referer( 'mtphr_galleries', 'security' );

	// Get variables
	$type = $_POST['type'];
	$value = $_POST['value'];
	$parent = $_POST['parent'];
	$name_resources = $_POST['name_resources'];
	
	// Get the upload directory
	$upload_dir = wp_upload_dir();
	$gallery_upload_dir = $upload_dir['basedir'].'/mtphr-galleries';
	$gallery_upload_url = $upload_dir['baseurl'].'/mtphr-galleries';
	
	// Create the galleries directory
	if( !file_exists($gallery_upload_dir) ) {
	  mkdir( $gallery_upload_dir );
	}

	// Display the files
	switch( $type ) {
		case 'youtube':
		
			// Strip out the id from url
			parse_str( $value, $value_array );
			if( isset($value_array['v']) ) {
				$value = $value_array['v'];
			} else {
				$value = reset( $value_array );
			}
			
			$response = wp_remote_get('http://youtube.com/get_video_info?video_id='.$value);
			if( $response['response']['code'] == 200 ) {
				parse_str($response['body'], $ytarr);
				$url = $ytarr['iurlhq'];
				$title = $ytarr['title'];
				$file_path = $gallery_upload_dir.'/youtube-'.$value.'.jpg';
				$file_url = $gallery_upload_url.'/youtube-'.$value.'.jpg';
				$data = array(
					'id' => $value,
					'title' => $title,
					'description' => '',
					'poster' => '',
					'link' => 'https://www.youtube.com/watch?v='.$value
				);
				if( mtphr_galleries_copy_poster($url, $file_path) ) {
					$data['poster'] = mtphr_galleries_create_external_thumb_attachment( $file_path, $file_url, $title, false, $parent );
				}
				sleep( 2 );
				mtphr_gallery_admin_render_youtube_field( $data, false, $name_resources );
			} else {
				echo 'error';
			}
			break;
		case 'vimeo':
			
			// Strip out the id from url
			$value_array = explode( '/', $value );
			$value = end( $value_array );
		
			$response = wp_remote_get( 'http://vimeo.com/api/v2/video/'.$value.'.json' );
			if( $response['response']['code'] == 200 ) {
				$body = json_decode($response['body'], true);
				$url = $body[0]['thumbnail_large'];
				$title = $body[0]['title'];
				$file_path = $gallery_upload_dir.'/vimeo-'.$value.'.jpg';
				$file_url = $gallery_upload_url.'/vimeo-'.$value.'.jpg';
				$data = array(
					'id' => $value,
					'title' => $title,
					'description' => urlencode($body[0]['description']),
					'poster' => $url,
					'link' => $body[0]['url']
				);
				if( mtphr_galleries_copy_poster($url, $file_path) ) {
					$data['poster'] = mtphr_galleries_create_external_thumb_attachment( $file_path, $file_url, $title, false, $parent );
				}
				sleep( 2 );
				mtphr_gallery_admin_render_vimeo_field( $data, false, $name_resources );
			} else {
				echo 'error';
			}
			
			break;
	}

	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_mtphr_gallery_external_thumb_ajax', 'mtphr_gallery_external_thumb_ajax' );


/* --------------------------------------------------------- */
/* !Create an external gallery thumb via ajax - 2.0.0 */
/* --------------------------------------------------------- */

function mtphr_gallery_create_external_thumb_ajax() {

	// Get access to the database
	global $wpdb;

	// Check the nonce
	check_ajax_referer( 'mtphr_galleries', 'security' );

	// Get variables
	$type = $_POST['type'];
	$value = $_POST['value'];
	$poster = $_POST['poster'];
	$parent = $_POST['parent'];
	
	// Get the upload directory
	$upload_dir = wp_upload_dir();
	$gallery_upload_dir = $upload_dir['basedir'].'/mtphr-galleries';
	$gallery_upload_url = $upload_dir['baseurl'].'/mtphr-galleries';
	
	// Create the galleries directory
	if( !file_exists($gallery_upload_dir) ) {
	  mkdir( $gallery_upload_dir );
	}

	// Display the files
	switch( $type ) {
		case 'youtube':
			$response = wp_remote_get('http://youtube.com/get_video_info?video_id='.$value);
			if( $response['response']['code'] == 200 ) {
				parse_str($response['body'], $ytarr);			
				$url = $ytarr['iurlhq'];
				$title = $ytarr['title'];
				$file_path = $gallery_upload_dir.'/youtube-'.$value.'.jpg';
				$file_url = $gallery_upload_url.'/youtube-'.$value.'.jpg';	
				
				if( mtphr_galleries_copy_poster($url, $file_path) ) {
					if( $id = mtphr_galleries_create_external_thumb_attachment( $file_path, $file_url, $title, $poster, $parent ) ) {
						sleep( 2 );
						echo mtphr_gallery_admin_render_youtube_thumb( $id );
					}
				}
			}
			break;
		case 'vimeo':
			$response = wp_remote_get( 'http://vimeo.com/api/v2/video/'.$value.'.json' );
			if( $response['response']['code'] == 200 ) {
				$body = json_decode($response['body'], true);
				$url = $body[0]['thumbnail_large'];
				$title = $body[0]['title'];
				$file_path = $gallery_upload_dir.'/vimeo-'.$value.'.jpg';
				$file_url = $gallery_upload_url.'/vimeo-'.$value.'.jpg';	
				
				if( mtphr_galleries_copy_poster($url, $file_path) ) {
					if( $id = mtphr_galleries_create_external_thumb_attachment( $file_path, $file_url, $title, $poster, $parent ) ) {
						sleep( 2 );
						echo mtphr_gallery_admin_render_vimeo_thumb( $id );
					}
				}
			}
			break;
	}

	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_mtphr_gallery_create_external_thumb_ajax', 'mtphr_gallery_create_external_thumb_ajax' );

if( !function_exists('mtphr_galleries_copy_poster') ) {
function mtphr_galleries_copy_poster( $url, $file_path ) {
	
	if( ini_get('allow_url_fopen') ) {	
		return file_put_contents( $file_path, file_get_contents($url) );
	} else {
		$ch = curl_init( $url );
		$fp = fopen( $file_path, 'wb' );
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		return fclose($fp);
	}
}
}

if( !function_exists('mtphr_galleries_create_external_thumb_attachment') ) {
function mtphr_galleries_create_external_thumb_attachment( $file_path, $file_url, $title, $attachment_id='', $parent='' ) {

	$attachmment_path = get_attached_file( $attachment_id );
	
	// Create the attachment
	if( $attachment_id == '' && (false === $attachmment_path || !file_exists($attachmment_path)) ) {
		$path_parts = pathinfo( $file_path );
		$wp_filetype = wp_check_filetype( $file_path, null );
		$attachment = array(
		    'guid'           => $file_url,
		    'post_mime_type' => $wp_filetype['type'],
		    'post_title'     => $title,
		    'post_status'    => 'inherit',
		    'post_date'      => date('Y-m-d H:i:s')
		);
		
		$attachment_id = wp_insert_attachment( $attachment, $file_path, $parent );
	}

	if( $attachment_id ) {
		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $file_path );
		wp_update_attachment_metadata($attachment_id, $attachment_data);
		return $attachment_id;
	}
	
	return false;
}
}


/* --------------------------------------------------------- */
/* !Update the like count - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_gallery_likes_update() {

	// Check the nonce
	check_ajax_referer( 'mtphr-galleries', 'security' );

	// Get variables
	$post_id  = $_POST['postid'];
	$likes = get_post_meta( $post_id, '_mtphr_gallery_likes', true );
	$cookie = 'mtphr_gallery_likes_'.sanitize_key(get_bloginfo('blogname')).'_'.$post_id;

	if( isset($_COOKIE[$cookie]) ){
		echo intval($likes);
	} else {
		$likes = intval($likes)+1;
		update_post_meta( $post_id, '_mtphr_gallery_likes', $likes );
		setcookie( $cookie, $post_id, time()+(10*365*24*60*60), '/' );
		echo number_format($likes);
	}

	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_mtphr_gallery_likes_update', 'mtphr_gallery_likes_update' );
add_action( 'wp_ajax_nopriv_mtphr_gallery_likes_update', 'mtphr_gallery_likes_update' );