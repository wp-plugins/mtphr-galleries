<?php
/**
 * Add custom taxonomies
 *
 * @package Metaphor Galleries
 */




add_action( 'init', 'mtphr_galleries_categories' );
/**
 * Create a category taxonomy
 *
 * @since 1.0.0
 */
function mtphr_galleries_categories() {

	// Set the slug
	$settings = mtphr_galleries_settings();
	$slug = $settings['slug'].'-catagory';
	$singular = $settings['singular_label'];

	// Create labels
	$labels = array(
		'name' => sprintf(__('%s Categories', 'mtphr-galleries'), $singular),
		'singular_name' => __('Category', 'mtphr-galleries'),
		'search_items' =>  __('Search Categories', 'mtphr-galleries'),
		'all_items' => __('All Categories', 'mtphr-galleries'),
		'parent_item' => __('Parent', 'mtphr-galleries'),
		'parent_item_colon' => __('Parent:', 'mtphr-galleries'),
		'edit_item' => __('Edit Category', 'mtphr-galleries'),
		'update_item' => __('Update Category', 'mtphr-galleries'),
		'add_new_item' => __('Add New Category', 'mtphr-galleries'),
		'new_item_name' => __('New Category', 'mtphr-galleries'),
		'menu_name' => __('Categories', 'mtphr-galleries'),
	);

	// Create the arguments
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $slug )
	);

	// Register the taxonomy
	register_taxonomy( 'mtphr_gallery_category', array( 'mtphr_gallery' ), $args );
}




add_action( 'init', 'mtphr_galleries_tags' );
/**
 * Create a tags taxonomy
 *
 * @since 1.0.4
 */
function mtphr_galleries_tags() {

	// Set the slug
	$settings = mtphr_galleries_settings();
	$slug = $settings['slug'].'-tag';
	$singular = $settings['singular_label'];

	// Create labels
	$labels = array(
		'name' => sprintf(__('%s Tags', 'mtphr-galleries'), $singular),
		'singular_name' => __('Tag', 'mtphr-galleries'),
		'search_items' =>  __('Search Tags', 'mtphr-galleries'),
		'all_items' => __('All Tags', 'mtphr-galleries'),
		'parent_item' => __('Parent', 'mtphr-galleries'),
		'parent_item_colon' => __('Parent:', 'mtphr-galleries'),
		'edit_item' => __('Edit Tag', 'mtphr-galleries'),
		'update_item' => __('Update Tag', 'mtphr-galleries'),
		'add_new_item' => __('Add New Tag', 'mtphr-galleries'),
		'new_item_name' => __('New Tag', 'mtphr-galleries'),
		'menu_name' => __('Tags', 'mtphr-galleries'),
	);

	// Create the arguments
	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $slug )
	);

	// Register the taxonomy
	register_taxonomy( 'mtphr_gallery_tag', array( 'mtphr_gallery' ), $args );
}



