<?php

/* --------------------------------------------------------- */
/* !Add the gallery settings metabox - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_galleries_rotator_metabox() {

	$settings = mtphr_galleries_settings();
	add_meta_box( 'mtphr_gallery_settings_metabox', sprintf(__('%s Settings', 'mtphr-galleries'), $settings['singular_label']), 'mtphr_gallery_settings_render_metabox', 'mtphr_gallery', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mtphr_galleries_rotator_metabox' );



/* --------------------------------------------------------- */
/* !Render the gallery settings metabox - 2.0.4 */
/* --------------------------------------------------------- */

function mtphr_gallery_settings_render_metabox() {

	global $post;
	$settings = mtphr_galleries_settings();

	$client = get_post_meta($post->ID, '_mtphr_gallery_client', true);
	$link = get_post_meta($post->ID, '_mtphr_gallery_link', true);
	
	// Filter the tabs
	$tabs = apply_filters( 'mtphr_galleries_tabs', array(
		'resources' => __('Resources', 'mtphr-galleries'),
		'settings' => __('Rotator Settings', 'mtphr-galleries'),
		'data' => sprintf(__('%s Data', 'mtphr-galleries'), $settings['singular_label'])
	));
	
	// Filter the data meta
	$data_meta = apply_filters( 'mtphr_galleries_data_meta', array(
		'client' => 'client',
		'filter' => 'filter',
		'external_link' => 'external_link'
	));	
	
	echo '<input type="hidden" name="mtphr_galleries_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	echo '<div id="mtphr-galleries-page-tabs">';
  	echo '<ul>';
  		do_action('mtphr_galleries_metabox_tabs_before');
			if( is_array($tabs) && count($tabs) > 0 ) {
				foreach( $tabs as $type=>$button ) {
					echo '<li class="nav-tab"><a href="#mtphr-galleries-page-tabs-'.$type.'">'.$button.'</a></li>';
				}
			}
			do_action('mtphr_galleries_metabox_tabs_after');
		echo '</ul>';

		do_action('mtphr_galleries_metabox_before');

		/* --------------------------------------------------------- */
		/* !Gallery resources - 1.0.5 */
		/* --------------------------------------------------------- */
		
		if( isset($tabs['resources']) ) {
		
			echo '<div id="mtphr-galleries-page-tabs-resources" class="mtphr-galleries-page-tabs-page">';
				mtphr_galleries_resources_metabox();
			echo '</div>';
		}

		/* --------------------------------------------------------- */
		/* !Rotator settings - 2.0.5 */
		/* --------------------------------------------------------- */
		
		if( isset($tabs['settings']) ) {
			
			echo '<div id="mtphr-galleries-page-tabs-settings" class="mtphr-galleries-page-tabs-page">';
				mtphr_galleries_settings_metabox();
			echo '</div>';
		}

		/* --------------------------------------------------------- */
		/* !Gallery data - 1.0.5 */
		/* --------------------------------------------------------- */
		
		if( isset($tabs['data']) ) {
			
			echo '<div id="mtphr-galleries-page-tabs-data" class="mtphr-galleries-page-tabs-page">';
	
				do_action('mtphr_galleries_data_metabox_before');
				echo '<table class="mtphr-galleries-table">';
					do_action('mtphr_galleries_data_metabox_top');
					
					// Display the data meta
					if( is_array($data_meta) && count($data_meta) > 0 ) {
						foreach( $data_meta as $i=>$meta ) {

							switch( $meta ) {
							
								case 'client':
									
									echo '<tr>';
										echo '<td class="mtphr-galleries-label">';
											echo '<label>'.__('Client', 'mtphr-galleries').'</label>';
											echo '<small>'.sprintf(__('Add a client to the %s', 'mtphr-galleries'), strtolower($settings['singular_label'])).'</small>';
										echo '</td>';
										echo '<td>';
											echo '<input type="text" name="_mtphr_gallery_client" value="'.$client.'" />';
										echo '</td>';
									echo '</tr>';
					
									break;
									
								case 'external_link':
								
									echo '<tr>';
										echo '<td class="mtphr-galleries-label">';
											echo '<label>'.__('External link', 'mtphr-galleries').'</label>';
											echo '<small>'.sprintf(__('Add an external link to associate with the %s', 'mtphr-galleries'), strtolower($settings['singular_label'])).'</small>';
										echo '</td>';
										echo '<td>';
											echo '<input type="text" name="_mtphr_gallery_link" value="'.$link.'" />';
										echo '</td>';
									echo '</tr>';

									break;
									
								case 'filter':
									do_action('mtphr_galleries_data_metabox_middle');
									break;
									
								default:
									break;
									
							}
						}
					}

					do_action('mtphr_galleries_data_metabox_bottom');
				echo '</table>';
				do_action('mtphr_galleries_data_metabox_after');
	
			echo '</div>';
		}

		do_action('mtphr_galleries_metabox_after');

	echo '</div>';
}


/* --------------------------------------------------------- */
/* !Gallery resources setup - 2.0.6 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_resources_metabox') ) {
function mtphr_galleries_resources_metabox( $name_resources='_mtphr_gallery_resources', $args=false ) {

	global $post;
	
	$defaults = array(
		'filter_prefix' => 'mtphr_galleries',
		'limit_types' => '',
		'single_resource' => false
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	
	// Get the resources
	$resources = get_post_meta( $post->ID, $name_resources, true );

	// Filter the media types
	$media_types = apply_filters( $filter_prefix.'_media_types', array(
		'image' => __('Add Images', 'mtphr-galleries'),
		'video' => __('Add Videos', 'mtphr-galleries'),
		'audio' => __('Add Audio', 'mtphr-galleries'),
		'youtube' => __('Add YouTube', 'mtphr-galleries'),
		'vimeo' => __('Add Vimeo', 'mtphr-galleries')
	));
	
	// Remove unwanted media types
	if( is_array($limit_types) && count($limit_types) > 0 ) {
		foreach( $media_types as $i=>$type ) {
			if( !in_array($i, $limit_types) ) {
				unset($media_types[$i]);
			}
		}
	}
	
	$single = $single_resource ? 'data-single="true"' : '';
	$hidden = ($single_resource && is_array($resources)) ? 'style="display:none;"' : '';

	do_action($filter_prefix.'_resources_metabox_before');
	
	echo '<div class="mtphr-galleries-add-buttons" '.$hidden.'>';
		if( is_array($media_types) && count($media_types) > 0 ) {
			foreach( $media_types as $type=>$button ) {
				echo '<a href="#" class="mtphr-galleries-add-'.$type.' button-primary" data-prefix="'.$name_resources.'" '.$single.'>'.$button.'</a> ';
			}
		}
	echo '</div>';
	
	echo '<table class="mtphr-galleries-add-external">';
		echo '<tr>';
			echo '<td class="mtphr-galleries-add-external-title"></td>';
			echo '<td class="mtphr-galleries-add-external-input">';
				echo '<input type="text" size="30" />';
			echo '</td>';
			echo '<td class="mtphr-galleries-add-external-submit">';
				echo '<a href="#" class="button" data-prefix="'.$name_resources.'" '.$single.'>'.__('Submit', 'mtphr-galleries').'</a>';
				echo '<span class="spinner"></span>';
				echo '<i class="mtphr-galleries-add-external-error mtphr-galleries-icon-warning"></i>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
	
	echo '<table class="mtphr-galleries-thumbnails" '.$single.'>';
		echo '<tr class="clearfix">';
			if( is_array($resources) && count($resources) > 0 ) {	
				foreach( $resources as $i=>$resource ) {

					$resource = apply_filters( $filter_prefix.'_metabox_resource_data', $resource );

					if( is_array($resource) && isset($resource['type']) && array_key_exists($resource['type'], $media_types) ) {			
						if( function_exists('mtphr_gallery_admin_render_'.$resource['type'].'_field') ) {
							call_user_func( 'mtphr_gallery_admin_render_'.$resource['type'].'_field', $resource, $i, $name_resources );
						}
					}
				}
			}
		echo '</tr>';
	echo '</table>';

	do_action($filter_prefix.'_resources_metabox_after');
}
}


/* --------------------------------------------------------- */
/* !Gallery settings setup - 2.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_metabox') ) {
function mtphr_galleries_settings_metabox( $meta_prefix='_mtphr_gallery', $args=false ) {

	global $post;
	
	$rotate_type = get_post_meta( $post->ID, $meta_prefix.'_slider_type', true );
	$rotate_type = ($rotate_type != '') ? $rotate_type : 'fade';
	$dynamic_direction = get_post_meta( $post->ID, $meta_prefix.'_slider_directional_nav_reverse', true ) ? 'on' : false;
	$auto_rotate = get_post_meta( $post->ID, $meta_prefix.'_slider_auto_rotate', true ) ? 'on' : false;
	$rotate_delay = get_post_meta( $post->ID, $meta_prefix.'_slider_delay', true );
	$rotate_delay = ( $rotate_delay != '' ) ? $rotate_delay : 7;
	
	$rotate_pause = get_post_meta( $post->ID, $meta_prefix.'_slider_pause', true ) ? 'on' : false;
	$rotate_speed = get_post_meta( $post->ID, $meta_prefix.'_slider_speed', true );
	$rotate_speed = ( $rotate_speed != '' ) ? $rotate_speed : 5;
	
	$rotate_easing = get_post_meta( $post->ID, $meta_prefix.'_slider_ease', true );
	$directional_nav = get_post_meta( $post->ID, $meta_prefix.'_slider_directional_nav', true ) ? 'on' : false;
	$hide_directional_nav = get_post_meta( $post->ID, $meta_prefix.'_slider_directional_nav_hide', true ) ? 'on' : false;
	$control_nav = get_post_meta( $post->ID, $meta_prefix.'_slider_control_nav', true ) ? 'on' : false;
	
	$defaults = array(
		'filter_prefix' => 'mtphr_galleries'
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	do_action($filter_prefix.'_rotator_metabox_before');
	echo '<table class="mtphr-galleries-table">';
		do_action($filter_prefix.'_rotator_metabox_top');

		echo '<tr>';
			echo '<td class="mtphr-galleries-label">';
				echo '<label>'.__('Rotation type', 'mtphr-galleries').'</label>';
				echo '<small>'.__('Set the type of rotation for the rotator', 'mtphr-galleries').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label class="mtphr-galleries-radio"><input type="radio" name="'.$meta_prefix.'_slider_type" value="fade" '.checked('fade', $rotate_type, false).' /> '.__('Fade', 'mtphr-galleries').'</label>';
				echo '<label class="mtphr-galleries-radio"><input type="radio" name="'.$meta_prefix.'_slider_type" value="slide_left" '.checked('slide_left', $rotate_type, false).' /> '.__('Slide left', 'mtphr-galleries').'</label>';
				echo '<label class="mtphr-galleries-radio"><input type="radio" name="'.$meta_prefix.'_slider_type" value="slide_right" '.checked('slide_right', $rotate_type, false).' /> '.__('Slide right', 'mtphr-galleries').'</label>';
				echo '<label class="mtphr-galleries-radio"><input type="radio" name="'.$meta_prefix.'_slider_type" value="slide_up" '.checked('slide_up', $rotate_type, false).' /> '.__('Slide up', 'mtphr-galleries').'</label>';
				echo '<label style="margin-right:20px;" class="mtphr-galleries-radio"><input type="radio" name="'.$meta_prefix.'_slider_type" value="slide_down" '.checked('slide_down', $rotate_type, false).' /> '.__('Slide down', 'mtphr-galleries').'</label>';
				echo '<label class="mtphr-galleries-checkbox"><input type="checkbox" name="'.$meta_prefix.'_slider_directional_nav_reverse" value="on" '.checked('on', $dynamic_direction, false).' /> '.__('Dynamic slide direction', 'mtphr-galleries').'</label>';
			echo '</td>';
		echo '</tr>';

		echo '<tr>';
			echo '<td class="mtphr-galleries-label">';
				echo '<label>'.__('Auto rotate', 'mtphr-galleries').'</label>';
				echo '<small>'.__('Set the delay between rotations', 'mtphr-galleries').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label style="margin-right:20px;" class="mtphr-galleries-checkbox"><input type="checkbox" name="'.$meta_prefix.'_slider_auto_rotate" value="on" '.checked('on', $auto_rotate, false).' /> '.__('Enable', 'mtphr-galleries').'</label>';
				echo '<label style="margin-right:20px;" class="mtphr-galleries-checkbox"><input type="number" name="'.$meta_prefix.'_slider_delay" value="'.$rotate_delay.'" /> '.__('Seconds delay', 'mtphr-galleries').'</label>';
				echo '<label class="mtphr-galleries-checkbox"><input type="checkbox" name="'.$meta_prefix.'_slider_pause" value="on" '.checked('on', $rotate_pause, false).' /> '.__('Pause on mouse over', 'mtphr-galleries').'</label>';
			echo '</td>';
		echo '</tr>';

		echo '<tr>';
			echo '<td class="mtphr-galleries-label">';
				echo '<label>'.__('Rotate speed', 'mtphr-galleries').'</label>';
				echo '<small>'.__('Set the speed & easing of the rotation', 'mtphr-galleries').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label style="margin-right:20px;" class="mtphr-galleries-checkbox"><input type="number" name="'.$meta_prefix.'_slider_speed" value="'.$rotate_speed.'" /> '.__('Tenths of a second', 'mtphr-galleries').'</label>';
				echo '<select name="'.$meta_prefix.'_slider_ease">';
					$eases = array('linear','swing','jswing','easeInQuad','easeInCubic','easeInQuart','easeInQuint','easeInSine','easeInExpo','easeInCirc','easeInElastic','easeInBack','easeInBounce','easeOutQuad','easeOutCubic','easeOutQuart','easeOutQuint','easeOutSine','easeOutExpo','easeOutCirc','easeOutElastic','easeOutBack','easeOutBounce','easeInOutQuad','easeInOutCubic','easeInOutQuart','easeInOutQuint','easeInOutSine','easeInOutExpo','easeInOutCirc','easeInOutElastic','easeInOutBack','easeInOutBounce');
					foreach( $eases as $ease ) {
						echo '<option '.selected($ease, $rotate_easing, false).'>'.$ease.'</option>';
					}
				echo '</select>';
			echo '</td>';
		echo '</tr>';

		echo '<tr>';
			echo '<td class="mtphr-galleries-label">';
				echo '<label>'.__('Directional navigation', 'mtphr-galleries').'</label>';
				echo '<small>'.__('Set the directional navigation options', 'mtphr-galleries').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label style="margin-right:20px;" class="mtphr-galleries-checkbox"><input type="checkbox" name="'.$meta_prefix.'_slider_directional_nav" value="on" '.checked('on', $directional_nav, false).' /> '.__('Enable', 'mtphr-galleries').'</label>';
				echo '<label class="mtphr-galleries-checkbox"><input type="checkbox" name="'.$meta_prefix.'_slider_directional_nav_hide" value="on" '.checked('on', $hide_directional_nav, false).' /> '.__('Autohide navigation', 'mtphr-galleries').'</label>';
			echo '</td>';
		echo '</tr>';

		echo '<tr>';
			echo '<td class="mtphr-galleries-label">';
				echo '<label>'.__('Control navigation', 'mtphr-galleries').'</label>';
				echo '<small>'.__('Set the control navigation options', 'mtphr-galleries').'</small>';
			echo '</td>';
			echo '<td>';
				echo '<label style="margin-right:20px;" class="mtphr-galleries-checkbox"><input type="checkbox" name="'.$meta_prefix.'_slider_control_nav" value="on" '.checked('on', $control_nav, false).' /> '.__('Enable', 'mtphr-galleries').'</label>';
			echo '</td>';
		echo '</tr>';

		do_action($filter_prefix.'_rotator_metabox_bottom');
	echo '</table>';
	do_action($filter_prefix.'_rotator_metabox_after');
}
}



/* --------------------------------------------------------- */
/* !Save the custom meta - 2.0.4 */
/* --------------------------------------------------------- */

function mtphr_galleries_metabox_save( $post_id ) {

	global $post;

	// verify nonce
	if (!isset($_POST['mtphr_galleries_nonce']) || !wp_verify_nonce($_POST['mtphr_galleries_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) return $post_id;

	// don't save if only a revision
	if ( isset($post->post_type) && $post->post_type == 'revision' ) return $post_id;

	// check permissions
	if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	// Save the resources
	mtphr_galleries_resources_save( $post_id );
	
	// Save the settings
	mtphr_galleries_settings_save( $post_id );
	
	
	
	// Save the additional data
	if( isset($_POST['_mtphr_gallery_client']) ) {
	
		$client = isset( $_POST['_mtphr_gallery_client'] ) ? sanitize_text_field($_POST['_mtphr_gallery_client']) : '';
		$link = isset($_POST['_mtphr_gallery_link']) ? esc_url($_POST['_mtphr_gallery_link']) : '';
		
		update_post_meta( $post_id, '_mtphr_gallery_client', $client );
		update_post_meta( $post_id, '_mtphr_gallery_link', $link );
	}
}
add_action( 'save_post', 'mtphr_galleries_metabox_save' );


/* --------------------------------------------------------- */
/* !Save the resources - 2.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_resources_save') ) {
function mtphr_galleries_resources_save( $post_id, $name_resources='_mtphr_gallery_resources' ) {
	
	if( isset($_POST[$name_resources]) ) {
		$resources = isset($_POST[$name_resources]) ? $_POST[$name_resources] : array();
		update_post_meta( $post_id, $name_resources, $resources );
	} else {
		delete_post_meta( $post_id, $name_resources );
	}
}
}


/* --------------------------------------------------------- */
/* !Save the settings - 2.0.5 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_galleries_settings_save') ) {
function mtphr_galleries_settings_save( $post_id, $meta_prefix='_mtphr_gallery' ) {

	if( isset($_POST[$meta_prefix.'_slider_type']) ) {

		$rotate_type = isset($_POST[$meta_prefix.'_slider_type']) ? $_POST[$meta_prefix.'_slider_type'] : '';
		$dynamic_direction = isset($_POST[$meta_prefix.'_slider_directional_nav_reverse']) ? $_POST[$meta_prefix.'_slider_directional_nav_reverse'] : '';
		$auto_rotate = isset($_POST[$meta_prefix.'_slider_auto_rotate']) ? $_POST[$meta_prefix.'_slider_auto_rotate'] : '';
		$rotate_delay = isset($_POST[$meta_prefix.'_slider_delay']) ? intval($_POST[$meta_prefix.'_slider_delay']) : '';
		$rotate_pause = isset($_POST[$meta_prefix.'_slider_pause']) ? $_POST[$meta_prefix.'_slider_pause'] : '';
		$rotate_speed = isset($_POST[$meta_prefix.'_slider_speed']) ? intval($_POST[$meta_prefix.'_slider_speed']) : '';
		$rotate_easing = isset($_POST[$meta_prefix.'_slider_ease']) ? $_POST[$meta_prefix.'_slider_ease'] : '';
		$directional_nav = isset($_POST[$meta_prefix.'_slider_directional_nav']) ? $_POST[$meta_prefix.'_slider_directional_nav'] : '';
		$hide_directional_nav = isset($_POST[$meta_prefix.'_slider_directional_nav_hide']) ? $_POST[$meta_prefix.'_slider_directional_nav_hide'] : '';
		$control_nav = isset($_POST[$meta_prefix.'_slider_control_nav']) ? $_POST[$meta_prefix.'_slider_control_nav'] : '';
		
		update_post_meta( $post_id, $meta_prefix.'_slider_type', $rotate_type );
		update_post_meta( $post_id, $meta_prefix.'_slider_directional_nav_reverse', $dynamic_direction );
		update_post_meta( $post_id, $meta_prefix.'_slider_auto_rotate', $auto_rotate );
		update_post_meta( $post_id, $meta_prefix.'_slider_delay', $rotate_delay );
		update_post_meta( $post_id, $meta_prefix.'_slider_pause', $rotate_pause );
		update_post_meta( $post_id, $meta_prefix.'_slider_speed', $rotate_speed );
		update_post_meta( $post_id, $meta_prefix.'_slider_ease', $rotate_easing );
		update_post_meta( $post_id, $meta_prefix.'_slider_directional_nav', $directional_nav );
		update_post_meta( $post_id, $meta_prefix.'_slider_directional_nav_hide', $hide_directional_nav );
		update_post_meta( $post_id, $meta_prefix.'_slider_control_nav', $control_nav );
	}
}
}


