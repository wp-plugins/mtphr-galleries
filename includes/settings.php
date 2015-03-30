<?php

/* --------------------------------------------------------- */
/* !Get the settings - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings') ) {
function mtphr_galleries_settings() {
	$settings = get_option( 'mtphr_galleries_settings', array() );
	
	// Translate the settings
	$settings = mtphr_galleries_translate_settings( $settings );

	return wp_parse_args( $settings, mtphr_galleries_settings_defaults() );
}
}
if( !function_exists('mtphr_galleries_settings_defaults') ) {
function mtphr_galleries_settings_defaults() {
	$defaults = array(
		'slug' => 'galleries',
		'singular_label' => __( 'Gallery', 'mtphr-galleries' ),
		'plural_label' => __( 'Galleries', 'mtphr-galleries' ),
		'public' => 'true',
		'has_archive' => 'false',
		'global_slider_settings' => '',
		'slider_type' => 'fade',
		'slider_directional_nav_reverse' => '',
		'slider_auto_rotate' => '',
		'slider_delay' => 7,
		'slider_pause' => '',
		'slider_speed' => 5,
		'slider_ease' => 'linear',
		'slider_directional_nav' => '',
		'slider_directional_nav_hide' => '',
		'slider_control_nav' => ''
	);
	return $defaults;
}
}



/* --------------------------------------------------------- */
/* !Initializes the settings page - 2.0.13 */
/* --------------------------------------------------------- */

function mtphr_galleries_initialize_settings() {

	$settings = mtphr_galleries_settings();


	/* --------------------------------------------------------- */
	/* !Add the setting sections - 1.0.5 */
	/* --------------------------------------------------------- */

	add_settings_section( 'mtphr_galleries_settings_section', __( 'General settings', 'mtphr-galleries' ).'<input type="submit" class="button button-small" value="'.__('Save Changes', 'mtphr-galleries').'">', false, 'mtphr_galleries_settings' );
	
	add_settings_section( 'mtphr_galleries_slider_section', __( 'Rotator settings', 'mtphr-galleries' ).'<input type="submit" class="button button-small" value="'.__('Save Changes', 'mtphr-galleries').'">', false, 'mtphr_galleries_settings' );


	/* --------------------------------------------------------- */
	/* !Add the general settings - 1.0.9 */
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
	
	/* Public */
	//$title = '<div class="mtphr-galleries-label-alt"><label>'.__( 'Public', 'mtphr-galleries' ).'</label><small>'.__('Set whether or not the post type should be public and has single posts', 'mtphr-galleries').'</small></div>';
	//add_settings_field( 'mtphr_galleries_settings_public', $title, 'mtphr_galleries_settings_public', 'mtphr_galleries_settings', 'mtphr_galleries_settings_section', array('settings' => $settings) );
	
	/* Has archive */
	$title = '<div class="mtphr-galleries-label-alt"><label>'.__( 'Has archive', 'mtphr-galleries' ).'</label><small>'.__('Set whether or not the post type has an archive page', 'mtphr-galleries').'</small></div>';
	add_settings_field( 'mtphr_galleries_settings_has_archive', $title, 'mtphr_galleries_settings_has_archive', 'mtphr_galleries_settings', 'mtphr_galleries_settings_section', array('settings' => $settings) );
	
	
	/* Global slider settings */
	add_settings_field(
		'mtphr_galleries_general_settings_global_slider_settings',
		mtphr_galleries_settings_label( __( 'Global rotator settings', 'mtphr-galleries' ), __('Disable individual rotator settings and use global settings', 'mtphr-galleries') ),
		'mtphr_galleries_settings_checkbox',
		'mtphr_galleries_settings',
		'mtphr_galleries_slider_section',
		array(
			'name' => 'mtphr_galleries_settings[global_slider_settings]',
			'value' => $settings['global_slider_settings'],
			'label' => __('Use global settings for all sliders', 'mtphr-galleries')
		)
	);
	
	/* Rotation type */
	add_settings_field(
		'mtphr_galleries_general_settings_rotation_type',
		mtphr_galleries_settings_label( __( 'Rotation type', 'mtphr-galleries' ), __('Set the type of rotation for the rotator', 'mtphr-galleries') ),
		'mtphr_galleries_settings_rotation_type',
		'mtphr_galleries_settings',
		'mtphr_galleries_slider_section',
		array(
			'name' => 'mtphr_galleries_settings[slider_type]',
			'value' => $settings['slider_type'],
			'name_reverse' => 'mtphr_galleries_settings[slider_directional_nav_reverse]',
			'value_reverse' => $settings['slider_directional_nav_reverse'],
		)
	);
	
	/* Auto rotate */
	add_settings_field(
		'mtphr_galleries_general_settings_auto_rotate',
		mtphr_galleries_settings_label( __( 'Auto rotate', 'mtphr-galleries' ), __('Set the delay between rotations', 'mtphr-galleries') ),
		'mtphr_galleries_settings_auto_rotate',
		'mtphr_galleries_settings',
		'mtphr_galleries_slider_section',
		array(
			'name' => 'mtphr_galleries_settings[slider_auto_rotate]',
			'value' => $settings['slider_auto_rotate'],
			'name_delay' => 'mtphr_galleries_settings[slider_delay]',
			'value_delay' => $settings['slider_delay'],
			'name_pause' => 'mtphr_galleries_settings[slider_pause]',
			'value_pause' => $settings['slider_pause'],
		)
	);
	
	/* Rotate speed */
	add_settings_field(
		'mtphr_galleries_general_settings_rotate_speed',
		mtphr_galleries_settings_label( __( 'Rotate speed', 'mtphr-galleries' ), __('Set the speed & easing of the rotation', 'mtphr-galleries') ),
		'mtphr_galleries_settings_rotate_speed',
		'mtphr_galleries_settings',
		'mtphr_galleries_slider_section',
		array(
			'name' => 'mtphr_galleries_settings[slider_speed]',
			'value' => $settings['slider_speed'],
			'name_ease' => 'mtphr_galleries_settings[slider_ease]',
			'value_ease' => $settings['slider_ease'],
		)
	);
	
	/* Directional navigation */
	add_settings_field(
		'mtphr_galleries_general_settings_directional_navigation',
		mtphr_galleries_settings_label( __( 'Directional navigation', 'mtphr-galleries' ), __('Set the directional navigation options', 'mtphr-galleries') ),
		'mtphr_galleries_settings_directional_navigation',
		'mtphr_galleries_settings',
		'mtphr_galleries_slider_section',
		array(
			'name' => 'mtphr_galleries_settings[slider_directional_nav]',
			'value' => $settings['slider_directional_nav'],
			'name_hide' => 'mtphr_galleries_settings[slider_directional_nav_hide]',
			'value_hide' => $settings['slider_directional_nav_hide'],
		)
	);
	
	/* Control navigation */
	add_settings_field(
		'mtphr_galleries_general_settings_control_navigation',
		mtphr_galleries_settings_label( __( 'Control navigation', 'mtphr-galleries' ), __('Set the control navigation options', 'mtphr-galleries') ),
		'mtphr_galleries_settings_checkbox',
		'mtphr_galleries_settings',
		'mtphr_galleries_slider_section',
		array(
			'name' => 'mtphr_galleries_settings[slider_control_nav]',
			'value' => $settings['slider_control_nav'],
			'label' => __('Enable', 'mtphr-galleries'),
		)
	);


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
/* !Public - 1.0.9 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_public') ) {
function mtphr_galleries_settings_public( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_galleries_settings_public">';
		echo '<select name="mtphr_galleries_settings[public]">';
			echo '<option value="false" '.selected('false', $settings['public'], false).'>'.__('Not Public', 'mtphr-galleries').'</option>';
			echo '<option value="true" '.selected('true', $settings['public'], false).'>'.__('Public', 'mtphr-galleries').'</option>';
		echo '</select>';
	echo '</div>';
}
}

/* --------------------------------------------------------- */
/* !Has archive - 1.0.9 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_has_archive') ) {
function mtphr_galleries_settings_has_archive( $args ) {

	$settings = $args['settings'];
	echo '<div id="mtphr_galleries_settings_has_archive">';
		echo '<select name="mtphr_galleries_settings[has_archive]">';
			echo '<option value="false" '.selected('false', $settings['has_archive'], false).'>'.__('No Archive Page', 'mtphr-galleries').'</option>';
			echo '<option value="true" '.selected('true', $settings['has_archive'], false).'>'.__('Has Archive Page', 'mtphr-galleries').'</option>';
		echo '</select>';
	echo '</div>';
}
}




/* --------------------------------------------------------- */
/* !Sanitize the setting fields - 1.2.0 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_sanitize') ) {
function mtphr_galleries_settings_sanitize( $fields ) {

	// Create an array for WPML to translate
	$wpml = array();

	// General settings
	if( isset($fields['slug']) ) {
		$fields['slug'] = isset( $fields['slug'] ) ? sanitize_text_field($fields['slug']) : '';
		$fields['singular_label'] = $wpml['singular_label'] = isset( $fields['singular_label'] ) ? sanitize_text_field($fields['singular_label']) : '';
		$fields['plural_label'] = $wpml['plural_label'] = isset( $fields['plural_label'] ) ? sanitize_text_field($fields['plural_label']) : '';
		
		$fields['global_slider_settings'] = ( isset($fields['global_slider_settings']) && $fields['global_slider_settings'] == 'on' )  ? 'on' : '';
		$fields['slider_directional_nav_reverse'] = ( isset($fields['slider_directional_nav_reverse']) && $fields['slider_directional_nav_reverse'] == 'on' )  ? 'on' : '';
		$fields['slider_auto_rotate'] = ( isset($fields['slider_auto_rotate']) && $fields['slider_auto_rotate'] == 'on' )  ? 'on' : '';
		$fields['slider_pause'] = ( isset($fields['slider_pause']) && $fields['slider_pause'] == 'on' )  ? 'on' : '';
		$fields['slider_directional_nav'] = ( isset($fields['slider_directional_nav']) && $fields['slider_directional_nav'] == 'on' )  ? 'on' : '';
		$fields['slider_directional_nav_hide'] = ( isset($fields['slider_directional_nav_hide']) && $fields['slider_directional_nav_hide'] == 'on' )  ? 'on' : '';
		$fields['slider_control_nav'] = ( isset($fields['slider_control_nav']) && $fields['slider_control_nav'] == 'on' )  ? 'on' : '';
	}
	
	// Register translatable fields
	mtphr_galleries_register_translate_settings( $wpml );

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


/* --------------------------------------------------------- */
/* !Create a settings label - 2.0.13 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_label') ) {
function mtphr_galleries_settings_label( $title, $description = '' ) {

	$label = '<div class="mtphr-galleries-label-alt">';
		$label .= '<label>'.$title.'</label>';
		if( $description != '' ) {
			$label .= '<small>'.$description.'</small>';
		}
	$label .= '</div>';

	return $label;
}
}


