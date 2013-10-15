<?php

/* --------------------------------------------------------- */
/* !Set the custom columns - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_galleries_set_like_columns( $columns ){

	$new_columns = array();
	$i = 0;
	foreach( $columns as $key => $value ) {
		if( $key == 'date' ) {
			$new_columns['mtphr_gallery_likes'] = __( '<i class="icon-heart"></i>', 'mtphr-galleries' );
		}
		$new_columns[$key] = $value;
		$i++;
	}
	return $new_columns;
}
add_filter( 'manage_mtphr_gallery_posts_columns', 'mtphr_galleries_set_like_columns' );



/* --------------------------------------------------------- */
/* !Display the custom columns - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_galleries_display_like_columns( $column, $post_id ){

	switch ( $column ) {

		case 'mtphr_gallery_likes':

			// Display the number of likes
			$likes = get_post_meta( $post_id, '_mtphr_gallery_likes', true );
			?>
		  <span class="mtphr-gallery-likes-count"><?php echo number_format(intval($likes)); ?></span>
			<?php
			break;
	}
}
add_action( 'manage_mtphr_gallery_posts_custom_column',  'mtphr_galleries_display_like_columns', 10, 2 );