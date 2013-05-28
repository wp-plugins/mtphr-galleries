<?php
/**
 * Load CSS & jQuery Scripts
 *
 * @package Metaphor Galleries
 */




add_action( 'admin_enqueue_scripts', 'mtphr_galleries_admin_scripts' );
/**
 * Load the admin scripts
 *
 * @since 1.8.0
 */
function mtphr_galleries_admin_scripts( $hook ) {

	global $typenow;

	if ( $typenow == 'mtphr_gallery' && in_array($hook, array('post-new.php', 'post.php', 'mtphr_gallery_page_mtphr_galleries_settings_menu')) ) {

		// Load the style sheet
		wp_register_style( 'mtphr-galleries-metaboxer', MTPHR_GALLERIES_URL.'/includes/metaboxer/metaboxer.css', false, MTPHR_GALLERIES_VERSION );
		wp_enqueue_style( 'mtphr-galleries-metaboxer' );

		// Load scipts for the media uploader
		if(function_exists( 'wp_enqueue_media' )){
	    wp_enqueue_media();
		} else {
	    wp_enqueue_style('thickbox');
	    wp_enqueue_script('media-upload');
	    wp_enqueue_script('thickbox');
		}

		// Load the jQuery
		wp_register_script( 'mtphr-galleries-metaboxer', MTPHR_GALLERIES_URL.'/includes/metaboxer/metaboxer.js', array('jquery'), MTPHR_GALLERIES_VERSION, true );
		wp_enqueue_script( 'mtphr-galleries-metaboxer' );

		// Localize scripts
		wp_localize_script( 'mtphr-galleries-metaboxer', 'mtphr_galleries_metaboxer_vars', array(
				'security' => wp_create_nonce( 'mtphr_galleries' ),
				'gallery_lightbox_title' => __('Gallery attachments', 'mtphr-galleries'),
				'gallery_lightbox_button' => __('Insert attachments', 'mtphr-galleries'),
				'gallery_lightbox_button_single' => __('Insert attachment', 'mtphr-galleries'),
				'gallery_invalid' => __('Invalid file type or url', 'mtphr-galleries')
			)
		);
	}

	// Load the style sheet
	wp_register_style( 'mtphr-galleries-admin', MTPHR_GALLERIES_URL.'/assets/css/style-admin.css', false, MTPHR_GALLERIES_VERSION );
	wp_enqueue_style( 'mtphr-galleries-admin' );
}




add_action( 'wp_enqueue_scripts', 'mtphr_galleries_scripts' );
/**
 * Load the front end scripts
 *
 * @since 1.8.0
 */
function mtphr_galleries_scripts() {

	// Load the style sheet
	wp_register_style( 'mtphr-galleries', MTPHR_GALLERIES_URL.'/assets/css/style.css', false, MTPHR_GALLERIES_VERSION );
	wp_enqueue_style( 'mtphr-galleries' );

	// Add jQuery easing & timers
  wp_register_script( 'jquery-easing', MTPHR_GALLERIES_URL.'/assets/js/jquery.easing.1.3.js', array('jquery'), MTPHR_GALLERIES_VERSION, true );

	wp_register_script( 'mtphr-gallery-slider', MTPHR_GALLERIES_URL.'/assets/js/mtphr-gallery-slider.js', array('jquery', 'jquery-easing'), MTPHR_GALLERIES_VERSION, true );
	wp_enqueue_script( 'mtphr-gallery-slider' );

	wp_register_script( 'respond', MTPHR_GALLERIES_URL.'/assets/js/respond.min.js', array('jquery'), MTPHR_GALLERIES_VERSION, true );
	wp_enqueue_script( 'respond' );
}




add_action( 'mtphr_gallery_slider_after', 'mtphr_gallery_slider_scripts', 10, 2 );
/**
 * Add the class scripts
 *
 * @since 1.0.0
 */
function mtphr_gallery_slider_scripts( $gallery_id, $meta_data ) {

	extract( $meta_data );

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
	ob_start(); ?>
	<script>
	jQuery( window ).load( function() {
		jQuery( '#<?php echo $gallery_id; ?>' ).mtphr_gallery_slider({
			rotate_type : '<?php echo $_mtphr_gallery_slider_type; ?>',
			auto_rotate : <?php echo $rotate; ?>,
			rotate_delay : <?php echo intval($_mtphr_gallery_slider_delay); ?>,
			rotate_pause : <?php echo $pause; ?>,
			rotate_speed : <?php echo intval($_mtphr_gallery_slider_speed); ?>,
			rotate_ease : '<?php echo $_mtphr_gallery_slider_ease; ?>',
			nav_reverse : <?php echo $nav_reverse; ?>
		});
	});
	</script>
	<?php // Echo the compressed scripts
	echo ob_get_clean();
}
