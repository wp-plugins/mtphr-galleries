<?php
/**
 * Create the meta boxes
 *
 * @package Metaphor Galleries
 */




add_action( 'admin_init', 'mtphr_galleries_links_metabox' );
/**
 * Add gallery resources
 *
 * @since 1.0.0
 */
function mtphr_galleries_links_metabox() {

	// Create an array to store the fields
	$fields = array();

	$fields['link'] = array(
		'id' => '_mtphr_gallery_link',
		'type' => 'text',
		'name' => __( 'External link', 'mtphr-galleries' ),
		'description' => __( 'Add an external link to associate with the gallery.', 'mtphr-galleries' )
	);

	$gallery_links = array(
		'id' => '_mtphr_gallery_links_metabox',
		'title' => __( 'Gallery Links', 'mtphr-galleries' ),
		'page' => array( 'mtphr_gallery' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => apply_filters('mtphr_galleries_links_metabox', $fields)
	);
	new MTPHR_GALLERIES_MetaBoxer( $gallery_links );
}




add_action( 'admin_init', 'mtphr_galleries_resources_metabox' );
/**
 * Add gallery resources
 *
 * @since 1.0.0
 */
function mtphr_galleries_resources_metabox() {

	// Create an array to store the fields
	$fields = array();

	$fields['resources'] = array(
		'id' => '_mtphr_gallery_resources',
		'type' => 'gallery',
		'name' => __( 'Resources', 'mtphr-galleries' ),
		'description' => __( 'Insert attachments or paste url directly into text field.', 'mtphr-galleries' )
	);

	$gallery_resources = array(
		'id' => '_mtphr_gallery_resources_metabox',
		'title' => __( 'Gallery Resources', 'mtphr-galleries' ),
		'page' => array( 'mtphr_gallery' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => apply_filters('mtphr_galleries_resources_metabox', $fields)
	);
	new MTPHR_GALLERIES_MetaBoxer( $gallery_resources );
}






add_action( 'admin_init', 'mtphr_galleries_settings_metabox' );
/**
 * Add gallery settings
 *
 * @since 1.0.0
 */
function mtphr_galleries_settings_metabox() {

	// Create an array to store the fields
	$fields = array();

	// Add the type field
	$fields['type'] = array(
		'id' => '_mtphr_gallery_slider_type',
		'type' => 'radio',
		'name' => __('Rotation type', 'mtphr-galleries'),
		'options' => array(
			'fade' => __('Fade', 'mtphr-galleries'),
			'slide_left' => __('Slide left', 'mtphr-galleries'),
			'slide_right' => __('Slide right', 'mtphr-galleries'),
			'slide_up' => __('Slide up', 'mtphr-galleries'),
			'slide_down' => __('Slide down', 'mtphr-galleries')
		),
		'default' => 'fade',
		'description' => __('Set the type of rotation for the ticker.', 'mtphr-galleries'),
		'display' => 'inline',
		'append' => array(
			'_mtphr_gallery_slider_directional_nav_reverse' => array(
				'type' => 'checkbox',
				'label' => __('Dynamic slide direction', 'mtphr-galleries')
			)
		)
	);

	// Add the rotate delay field
	$fields['delay'] = array(
		'id' => '_mtphr_gallery_slider_auto_rotate',
		'type' => 'checkbox',
		'name' => __('Auto rotate', 'mtphr-galleries'),
		'label' => __('Enable', 'mtphr-galleries'),
		'description' => __('Set the delay between rotations.', 'mtphr-galleries'),
		'append' => array(
			'_mtphr_gallery_slider_delay' => array(
				'type' => 'number',
				'default' => 7,
				'after' => __('Seconds delay', 'mtphr-galleries')
			),
			'_mtphr_gallery_slider_pause' => array(
				'type' => 'checkbox',
				'label' => __('Pause on mouse over', 'mtphr-galleries')
			)
		)
	);

	// Add the rotate speed field
	$fields['speed'] = array(
		'id' => '_mtphr_gallery_slider_speed',
		'type' => 'number',
		'name' => __('Rotate speed', 'mtphr-galleries'),
		'default' => 3,
		'after' => __('Tenths of a second', 'mtphr-galleries'),
		'description' => __('Set the speed & easing of the rotation.', 'mtphr-galleries'),
		'append' => array(
			'_mtphr_gallery_slider_ease' => array(
				'type' => 'select',
				'options' => array('linear','swing','jswing','easeInQuad','easeInCubic','easeInQuart','easeInQuint','easeInSine','easeInExpo','easeInCirc','easeInElastic','easeInBack','easeInBounce','easeOutQuad','easeOutCubic','easeOutQuart','easeOutQuint','easeOutSine','easeOutExpo','easeOutCirc','easeOutElastic','easeOutBack','easeOutBounce','easeInOutQuad','easeInOutCubic','easeInOutQuart','easeInOutQuint','easeInOutSine','easeInOutExpo','easeInOutCirc','easeInOutElastic','easeInOutBack','easeInOutBounce')
			)
		)
	);

	// Add the rotate navigation field
	$fields['directional_nav'] = array(
		'id' => '_mtphr_gallery_slider_directional_nav',
		'type' => 'checkbox',
		'name' => __('Directional navigation', 'mtphr-galleries'),
		'label' => __('Enable', 'mtphr-galleries'),
		'description' => __('Set the directional navigation options.', 'mtphr-galleries'),
		'append' => array(
			'_mtphr_gallery_slider_directional_nav_hide' => array(
				'type' => 'checkbox',
				'label' => __('Autohide navigation', 'mtphr-galleries')
			)
		)
	);

	// Add the rotate navigation field
	$fields['control_nav'] = array(
		'id' => '_mtphr_gallery_slider_control_nav',
		'type' => 'checkbox',
		'name' => __('Control navigation', 'mtphr-galleries'),
		'label' => __('Enable', 'mtphr-galleries'),
		'description' => __('Set the control navigation options.', 'mtphr-galleries')
	);

	$gallery_resources = array(
		'id' => '_mtphr_gallery_settings_metabox',
		'title' => __( 'Gallery Settings', 'mtphr-galleries' ),
		'page' => array( 'mtphr_gallery' ),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => apply_filters('mtphr_galleries_settings_metabox', $fields)
	);
	new MTPHR_GALLERIES_MetaBoxer( $gallery_resources );
}


