<?php

/* --------------------------------------------------------- */
/* !Load the admin scripts - 2.0.0 */
/* --------------------------------------------------------- */

function mtphr_galleries_admin_scripts( $hook ) {

	global $typenow;

	//if ( $typenow == 'mtphr_gallery' ) {

		// Load scipts for the media uploader
		if(function_exists( 'wp_enqueue_media' )){
	    wp_enqueue_media();
		} else {
	    wp_enqueue_style('thickbox');
	    wp_enqueue_script('media-upload');
	    wp_enqueue_script('thickbox');
		}

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-tabs' );


		// Register icon font
	  wp_register_style( 'mtphr-galleries-font', MTPHR_GALLERIES_URL.'/assets/fontastic/styles.css', false, MTPHR_GALLERIES_VERSION );
	  wp_enqueue_style( 'mtphr-galleries-font' );

		// Load the admin scripts
		wp_register_script( 'mtphr-galleries', MTPHR_GALLERIES_URL.'/assets/js/admin/script.js', false, MTPHR_GALLERIES_VERSION, true );
		wp_enqueue_script( 'mtphr-galleries' );
		wp_localize_script( 'mtphr-galleries', 'mtphr_galleries_vars', array(
				'security' => wp_create_nonce( 'mtphr_galleries' ),
				'img_title' => __( 'Upload or select images', 'mtphr-galleries' ),
				'img_button' => __( 'Insert Image(s)', 'mtphr-galleries' ),
				'poster_title' => __( 'Upload or select a poster image', 'mtphr-galleries' ),
				'poster_button' => __( 'Insert Poster Image', 'mtphr-galleries' ),
				'add_poster' => __( 'Add Poster Image', 'mtphr-galleries' ),
				'remove_poster' => __( 'Remove Poster Image', 'mtphr-galleries' ),
				'video_title' => __( 'Upload or select videos', 'mtphr-galleries' ),
				'video_button' => __( 'Insert Video(s)', 'mtphr-galleries' ),
				'audio_title' => __( 'Upload or select Audio Files', 'mtphr-galleries' ),
				'audio_button' => __( 'Insert Audio File(s)', 'mtphr-galleries' ),
				'youtube_input_title' => __( 'YouTube Video URL or ID', 'mtphr-galleries' ),
				'vimeo_input_title' => __( 'Vimeo Video URL or ID', 'mtphr-galleries' )
			)
		);
	//}
	
	// Load the admin styles
	wp_register_style( 'mtphr-galleries', MTPHR_GALLERIES_URL.'/assets/css/style-admin.css', false, MTPHR_GALLERIES_VERSION );
	wp_enqueue_style( 'mtphr-galleries' );

	// Shortcode generator
	wp_register_script( 'mtphr-galleries-sc-gen', MTPHR_GALLERIES_URL.'/assets/js/admin/generator.js', array('jquery'), MTPHR_GALLERIES_VERSION, true );
	wp_enqueue_script( 'mtphr-galleries-sc-gen' );
}
add_action( 'admin_enqueue_scripts', 'mtphr_galleries_admin_scripts' );



/* --------------------------------------------------------- */
/* !Load the front-end scripts - 2.0.0 */
/* --------------------------------------------------------- */

function mtphr_galleries_scripts() {

	global $wp_styles;

	// Register icon font
  wp_register_style( 'mtphr-galleries-font', MTPHR_GALLERIES_URL.'/assets/fontastic/styles.css', false, MTPHR_GALLERIES_VERSION );
  wp_enqueue_style( 'mtphr-galleries-font' );

	// Load the style sheet
	wp_register_style( 'mtphr-galleries', MTPHR_GALLERIES_URL.'/assets/css/style.css', false, MTPHR_GALLERIES_VERSION );
	wp_enqueue_style( 'mtphr-galleries' );

  // Register jQuery touchSwipe
	wp_register_script( 'touchSwipe', MTPHR_GALLERIES_URL.'/assets/js/jquery.touchSwipe.min.js', array('jquery'), MTPHR_GALLERIES_VERSION, true );

	// Add jQuery easing
  wp_register_script( 'jquery-easing', MTPHR_GALLERIES_URL.'/assets/js/jquery.easing.1.3.js', array('jquery'), MTPHR_GALLERIES_VERSION, true );
  
  // Add media element
	wp_enqueue_style( 'wp-mediaelement' );
	wp_enqueue_script( 'wp-mediaelement' );

	// Add jQuery gallery class
	wp_register_script( 'mtphr-gallery-slider', MTPHR_GALLERIES_URL.'/assets/js/mtphr-gallery-slider.js', array('jquery', 'jquery-easing'), MTPHR_GALLERIES_VERSION, true );
	wp_enqueue_script( 'mtphr-gallery-slider' );

	wp_register_script( 'mtphr-galleries', MTPHR_GALLERIES_URL.'/assets/js/script.js', array('jquery'), MTPHR_GALLERIES_VERSION, true );
	wp_enqueue_script( 'mtphr-galleries' );
	wp_localize_script( 'mtphr-galleries', 'mtphr_galleries_vars', array(
			'security' => wp_create_nonce( 'mtphr-galleries' )
		)
	);

	wp_register_script( 'respond', MTPHR_GALLERIES_URL.'/assets/js/respond.min.js', array('jquery'), MTPHR_GALLERIES_VERSION, true );
	wp_enqueue_script( 'respond' );
}
add_action( 'wp_enqueue_scripts', 'mtphr_galleries_scripts' );



/* --------------------------------------------------------- */
/* !Initialize the gallery scripts - 2.0.12 */
/* --------------------------------------------------------- */

function mtphr_galleries_init_scripts() {

	global $mtphr_galleries_scripts;
	if( is_array($mtphr_galleries_scripts) && !empty($mtphr_galleries_scripts) ) {
		wp_print_scripts('touchSwipe');
		wp_print_scripts('jquery-easing');
		wp_print_scripts('mtphr-gallery-slider');
		?>
		<script>
			jQuery( window ).load( function() {
			<?php foreach( $mtphr_galleries_scripts as $gallery ) { ?>
				jQuery( '#<?php echo $gallery['id']; ?>' ).mtphr_gallery_slider({
					rotate_type 	: '<?php echo $gallery['rotate_type']; ?>',
					auto_rotate 	: <?php echo $gallery['auto_rotate']; ?>,
					delay 				: <?php echo intval($gallery['rotate_delay']); ?>,
					rotate_pause 	: <?php echo $gallery['rotate_pause']; ?>,
					rotate_speed 	: <?php echo intval($gallery['rotate_speed']); ?>,
					rotate_ease 	: '<?php echo $gallery['rotate_ease']; ?>',
					nav_reverse 	: <?php echo $gallery['nav_reverse']; ?>
				});
			 <?php } ?>
			});
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'mtphr_galleries_init_scripts', 20 );



/* --------------------------------------------------------- */
/* !Add ajax url - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_galleries_ajaxurl() {
	?>
	<script type="text/javascript">
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	</script>
	<?php
}
add_action( 'wp_head','mtphr_galleries_ajaxurl' );

