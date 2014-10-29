<?php
/**
 * Create the post type
 *
 * @package Metaphor Galleries
 */




add_action( 'wp_loaded','mtphr_galleries_posttype' );
/**
 * Add the gallery post type
 *
 * @since 1.0.9
 */
function mtphr_galleries_posttype() {

	// Set the slug
	$settings = mtphr_galleries_settings();
	$slug = $settings['slug'];
	$singular = $settings['singular_label'];
	$plural = $settings['plural_label'];
	$public = ( $settings['public'] == 'true' ) ? true : false;
	$has_archive = ( $settings['has_archive'] == 'true' ) ? true : false;

	// Create labels
	$labels = array(
		'name' => sprintf( __( '%s', 'mtphr-galleries' ), $plural ),
		'singular_name' => sprintf( __( '%s', 'mtphr-galleries' ), $singular ),
		'add_new' => __( 'Add New', 'mtphr-galleries' ),
		'add_new_item' => sprintf( __( 'Add New %s', 'mtphr-galleries' ), $singular ),
		'edit_item' => sprintf( __( 'Edit %s', 'mtphr-galleries' ), $singular ),
		'new_item' => sprintf( __( 'New %s', 'mtphr-galleries' ), $singular ),
		'view_item' => sprintf( __( 'View %s', 'mtphr-galleries' ), $singular ),
		'search_items' => sprintf( __( 'Search %s', 'mtphr-galleries' ), $plural ),
		'not_found' => sprintf( __( 'No %s Found', 'mtphr-galleries' ), $plural ),
		'not_found_in_trash' => sprintf( __( 'No %s Found in Trash', 'mtphr-galleries' ), $plural ),
		'parent_item_colon' => '',
		'menu_name' => sprintf( __( '%s', 'mtphr-galleries' ), $plural )
	);

	// Create the arguments
	$args = array(
		'labels' => $labels,
		'public' => $public,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_icon' => 'dashicons-format-gallery',
		'query_var' => true,
		'rewrite' => true,
		'supports' => array( 'title', 'thumbnail', 'editor', 'excerpt', 'comments', 'page-attributes' ),
		'show_in_nav_menus' => true,
		'rewrite' => array( 'slug' => $slug ),
		'has_archive' => $has_archive
	);

	// Register post type
	register_post_type( 'mtphr_gallery', $args );
}