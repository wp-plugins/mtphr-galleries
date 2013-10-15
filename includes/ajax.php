<?php

/* --------------------------------------------------------- */
/* !Display a gallery thumb via ajax - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_gallery_thumb_ajax() {

	// Get access to the database
	global $wpdb;

	// Check the nonce
	check_ajax_referer( 'mtphr_galleries', 'security' );

	// Get variables
	$attachments = $_POST['attachments'];

	// Display the files
	foreach( $attachments as $attachment ) {
		mtphr_gallery_render_thumb( $attachment['id'] );
	}

	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_mtphr_gallery_thumb_ajax', 'mtphr_gallery_thumb_ajax' );


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