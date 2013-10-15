<?php

/* --------------------------------------------------------- */
/* !Get the settings - 1.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings') ) {
function mtphr_galleries_settings() {
	$settings = get_option( 'mtphr_galleries_settings', array() );
	return wp_parse_args( $settings, mtphr_galleries_settings_defaults() );
}
}
if( !function_exists('mtphr_galleries_settings_defaults') ) {
function mtphr_galleries_settings_defaults() {
	$defaults = array(
		'slug' => 'galleries',
		'singular_label' => __( 'Gallery', 'mtphr-galleries' ),
		'plural_label' => __( 'Galleries', 'mtphr-galleries' )
	);
	return $defaults;
}
}



/* --------------------------------------------------------- */
/* !Initializes the settings page - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_galleries_initialize_settings() {

	$settings = mtphr_galleries_settings();


	/* --------------------------------------------------------- */
	/* !Add the setting sections - 1.0.5 */
	/* --------------------------------------------------------- */

	add_settings_section( 'mtphr_galleries_settings_section', __( 'General settings', 'mtphr-galleries' ).'<input type="submit" class="button button-primary" value="'.__('Save Changes', 'mtphr-galleries').'">', false, 'mtphr_galleries_settings' );


	/* --------------------------------------------------------- */
	/* !Add the general settings - 1.0.5 */
	/* --------------------------------------------------------- */

	/* Slug */
	$title = '<div class="mtphr-galleries-label-alt"><label>'.__( 'Slug', 'mtphr-galleries' ).'</label><small>'.__('Set the slug for the gallery post type and category', 'mtphr-galleries').'</small></div>';
	add_settings_field( 'mtphr_galleries_settings_slug', $title, 'mtphr_galleries_settings_slug', 'mtphr_galleries_settings', 'mtphr_galleries_settings_section', array('settings' => $settings) );

	/* Singular label */
	$title = '<div class="mtphr-galleries-label-alt"><label>'.__( 'Singular label', 'mtphr-galleries' ).'</label><small>'.__('Set the singular label for the gallery post type and category', 'mtphr-galleries').'</small></div>';
	add_settings_field( 'mtphr_galleries_settings_singular_label', $title, 'mtphr_galleries_settings_singular_label', 'mtphr_galleries_settings', 'mtphr_galleries_settings_section', array('settings' => $settings) );

	/* Plural label */
	$title = '<div class="mtphr-galleries-label-alt"><label>'.__( 'Plural label', 'mtphr-galleries' ).'</label><small>'.__('Set the plural label for the gallery post type and category', 'mtphr-galleries').'</small></div>';
	add_settings_field( 'mtphr_galleries_settings_plural_label', $title, 'mtphr_galleries_settings_plural_label', 'mtphr_galleries_settings', 'mtphr_galleries_settings_section', array('settings' => $settings) );


	/* --------------------------------------------------------- */
	/* !Register the settings - 1.0.5 */
	/* --------------------------------------------------------- */

	if( false == get_option('mtphr_galleries_settings') ) {
		add_option( 'mtphr_galleries_settings' );
	}
	register_setting( 'mtphr_galleries_settings', 'mtphr_galleries_settings', 'mtphr_galleries_settings_sanitize' );

}
add_action( 'admin_init', 'mtphr_galleries_initialize_settings' );



/* --------------------------------------------------------- */
/* !Slug - 1.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_slug') ) {
function mtphr_galleries_settings_slug( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_galleries_settings_slug">';
		echo '<input type="text" name="mtphr_galleries_settings[slug]" value="'.$settings['slug'].'" /><br/>';
		echo '<small style="display:block;line-height:13px;font-style:italic;padding-top:3px;">* '.__('You must update permalinks after changing the slug.', 'mtphr-galleries').'<br/>* '.__('You must not have a page slug with the same name as this slug.', 'mtphr-galleries').'</small>';
	echo '</div>';
}
}

/* --------------------------------------------------------- */
/* !Singular label - 1.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_singular_label') ) {
function mtphr_galleries_settings_singular_label( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_galleries_settings_singular_label">';
		echo '<input type="text" name="mtphr_galleries_settings[singular_label]" value="'.$settings['singular_label'].'" />';
	echo '</div>';
}
}

/* --------------------------------------------------------- */
/* !Plural label - 1.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_plural_label') ) {
function mtphr_galleries_settings_plural_label( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_galleries_settings_plural_label">';
		echo '<input type="text" name="mtphr_galleries_settings[plural_label]" value="'.$settings['plural_label'].'" />';
	echo '</div>';
}
}



/* --------------------------------------------------------- */
/* !Sanitize the setting fields - 1.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_sanitize') ) {
function mtphr_galleries_settings_sanitize( $fields ) {

	// General settings
	if( isset($fields['slug']) ) {
		$fields['slug'] = isset( $fields['slug'] ) ? sanitize_text_field($fields['slug']) : '';
		$fields['singular_label'] = isset( $fields['singular_label'] ) ? sanitize_text_field($fields['singular_label']) : '';
		$fields['plural_label'] = isset( $fields['plural_label'] ) ? sanitize_text_field($fields['plural_label']) : '';
	}

	return wp_parse_args( $fields, get_option('mtphr_galleries_settings', array()) );
}
}



/* --------------------------------------------------------- */
/* !Add a menu page to display options - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_galleries_settings_page() {

	add_submenu_page(
		'edit.php?post_type=mtphr_gallery',							// The ID of the top-level menu page to which this submenu item belongs
		__('Settings', 'mtphr-galleries'),							// The value used to populate the browser's title bar when the menu page is active
		__('Settings', 'mtphr-galleries'),							// The label of this submenu item displayed in the menu
		'administrator',																// What roles are able to access this submenu item
		'mtphr_galleries_settings_menu',								// The ID used to represent this submenu item
		'mtphr_galleries_settings_display'							// The callback function used to render the options for this submenu item
	);
}
add_action( 'admin_menu', 'mtphr_galleries_settings_page' );

/* --------------------------------------------------------- */
/* !Render the settings page - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_galleries_settings_display() {
	$settings = mtphr_galleries_settings();
	?>
	<div class="wrap">

		<div id="icon-mtphr_galleries" class="icon32"></div>
		<h2><?php printf( __('%s Settings', 'mtphr-galleries'), $settings['singular_label']); ?></h2>
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

