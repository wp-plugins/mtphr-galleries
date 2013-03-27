<?php
/**
 * The global settings
 *
 * @package Metaphor Galleries
 */
 



add_action( 'admin_menu', 'mtphr_galleries_settings_page' );
/**
 * Add a menu page to display options
 *
 * @since 1.0.0
 */
function mtphr_galleries_settings_page() {

	add_submenu_page(
		'edit.php?post_type=mtphr_gallery',							// The ID of the top-level menu page to which this submenu item belongs
		'Settings',																			// The value used to populate the browser's title bar when the menu page is active
		'Settings',																			// The label of this submenu item displayed in the menu
		'administrator',																// What roles are able to access this submenu item
		'mtphr_galleries_settings_menu',								// The ID used to represent this submenu item
		'mtphr_galleries_settings_display'							// The callback function used to render the options for this submenu item
	);
}




add_action( 'admin_init', 'mtphr_galleries_initialize_settings' );
/**
 * Initializes the settings page.
 *
 * @since 1.0.0
 */ 
function mtphr_galleries_initialize_settings() {
	
	$settings['slug'] = array(
		'title' => __( 'Slug', 'mtphr-galleries' ),
		'type' => 'text',
		'default' => __( 'galleries', 'mtphr-galleries' ),
		'size' => 10,
		'description' => __('Set the slug for the gallery post type and category.<br/><strong>* You must update permalinks after changing the slug.</strong><br/><strong>* You must not have a page slug with the same name as this slug.</strong>', 'mtphr-galleries')
	);
	
	$settings['singular_label'] = array(
		'title' => __( 'Singular Label', 'mtphr-galleries' ),
		'type' => 'text',
		'default' => __( 'Gallery', 'mtphr-galleries' ),
		'size' => 20,
		'description' => __('Set the singular label for the gallery post type and category.', 'mtphr-galleries')
	);
	
	$settings['plural_label'] = array(
		'title' => __( 'Plural Label', 'mtphr-galleries' ),
		'type' => 'text',
		'default' => __( 'Galleries', 'mtphr-galleries' ),
		'size' => 20,
		'description' => __('Set the plural label for the gallery post type.', 'mtphr-galleries')
	);

	if( false == get_option('mtphr_galleries_settings') ) {	
		add_option( 'mtphr_galleries_settings' );
	}
	
	/* Register the style options */
	add_settings_section(
		'mtphr_galleries_settings_section',						// ID used to identify this section and with which to register options
		'',																						// Title to be displayed on the administration page
		'mtphr_galleries_settings_callback',					// Callback used to render the description of the section
		'mtphr_galleries_settings'										// Page on which to add this section of options
	);
	
	$settings = apply_filters( 'mtphr_galleries_settings', $settings );

	if( is_array($settings) ) {
		foreach( $settings as $id => $setting ) {	
			$setting['option'] = 'mtphr_galleries_settings';
			$setting['option_id'] = $id;
			$setting['id'] = 'mtphr_galleries_settings['.$id.']';
			add_settings_field( $setting['id'], $setting['title'], 'mtphr_galleries_field_display', 'mtphr_galleries_settings', 'mtphr_galleries_settings_section', $setting);
		}
	}
	
	// Register the fields with WordPress
	register_setting( 'mtphr_galleries_settings', 'mtphr_galleries_settings' );
}




/**
 * Renders a simple page to display for the theme menu defined above.
 *
 * @since 1.0.0
 */
function mtphr_galleries_settings_display() {
	?>
	<div class="wrap">
	
		<div id="icon-mtphr_galleries" class="icon32"></div>
		<h2><?php _e( 'Global Gallery Settings', 'mtphr-galleries' ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
			settings_fields( 'mtphr_galleries_settings' );
			do_settings_sections( 'mtphr_galleries_settings' );
			submit_button();
			?>
		</form>

	</div><!-- /.wrap -->
	<?php
}




/**
 * The callback function for the settings sections.
 *
 * @since 1.0.0
 */ 
function mtphr_galleries_settings_callback() {
	echo '<h4>Global settings for the Gallery post type.</h4>';
}




/**
 * The custom field callback.
 *
 * @since 1.0.0
 */ 
function mtphr_galleries_field_display( $args ) {

	// First, we read the options collection
	if( isset($args['option']) ) {
		$options = get_option( $args['option'] );
		$value = isset( $options[$args['option_id']] ) ? $options[$args['option_id']] : '';
	} else {
		$value = get_option( $args['id'] );
	}	
	if( $value == '' && isset($args['default']) ) {
		$value = $args['default'];
	}
	if( isset($args['type']) ) {
	
		echo '<div class="mtphr-galleries-metaboxer-field mtphr-galleries-metaboxer-'.$args['type'].'">';
		
		// Call the function to display the field
		if ( function_exists('mtphr_galleries_metaboxer_'.$args['type']) ) {
			call_user_func( 'mtphr_galleries_metaboxer_'.$args['type'], $args, $value );
		}
		
		echo '<div>';
	}
	
	// Add a descriptions
	if( isset($args['description']) ) {
		echo '<span class="description"><small>'.$args['description'].'</small></span>';
	}
}

 