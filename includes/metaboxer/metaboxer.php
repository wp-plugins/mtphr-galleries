<?php
/**
 * Put all the Metaboxer admin function here fields here
 *
 * @package Metaphor Galleries
 * @author  Metaphor Creations
 * @license http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 */



/**
 * Create a field container and switch.
 *
 * @since 1.0.0
 */
function mtphr_galleries_metaboxer_container( $field, $context ) {

	global $post;

	$default = isset( $field['default'] ) ? $field['default'] : '';
	$value = ( get_post_meta( $post->ID, $field['id'], true ) != '' ) ? get_post_meta( $post->ID, $field['id'], true ) : $default;
	$display = isset( $field['display'] ) ? $field['display'] : '';
	?>
	<tr class="mtphr-galleries-metaboxer-field mtphr-galleries-metaboxer-field-<?php echo $field['type']; ?> mtphr-galleries-metaboxer<?php echo $field['id']; ?><?php if( isset($field['class']) ) { echo ' '.$field['class']; } ?> clearfix">

		<?php
		$content_class = 'mtphr-galleries-metaboxer-field-content mtphr-galleries-metaboxer-field-content-full mtphr-galleries-metaboxer-'.$field['type'].' clearfix';
		$content_span = ' colspan="2"';
		$label = false;

		if ( isset($field['name']) || isset($field['description']) ) {

			$content_class = 'mtphr-galleries-metaboxer-field-content mtphr-galleries-metaboxer-'.$field['type'].' clearfix';
			$content_span = '';
			$label = true;
			?>

			<?php if( $context == 'side' || $display == 'vertical' ) { ?><td><table><tr><?php } ?>

			<td class="mtphr-galleries-metaboxer-label">
				<?php if( isset($field['name']) ) { ?><label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label><?php } ?>
				<?php if( isset($field['description']) ) { ?><small><?php echo $field['description']; ?></small><?php } ?>
			</td>

			<?php if( $context == 'side' || $display == 'vertical' ) { echo '</tr>'; } ?>

			<?php
		}
		?>

		<?php if( $label ) { if( $context == 'side' || $display == 'vertical' ) { echo '<tr>'; } } ?>

		<td<?php echo $content_span; ?> class="<?php echo $content_class; ?>" id="<?php echo $post->ID; ?>">
			<?php
			// Call the function to display the field
			if ( function_exists('mtphr_galleries_metaboxer_'.$field['type']) ) {
				call_user_func( 'mtphr_galleries_metaboxer_'.$field['type'], $field, $value );
			}
			?>
		</td>

		<?php if( $label ) { if( $context == 'side' || $display == 'vertical' ) { echo '</tr></table></td>'; } } ?>

	</tr>
	<?php
}




/**
 * Append fields
 *
 * @since 1.0.0
 */
function mtphr_galleries_metaboxer_append_field( $field ) {

	// Add appended fields
	if( isset($field['append']) ) {

		$fields = $field['append'];
		$settings = ( isset($field['option'] ) ) ? $field['option'] : false;

		if( is_array($fields) ) {

			foreach( $fields as $id => $field ) {

				// Get the value
				if( $settings) {
					$options = get_option( $settings );
					$value = isset( $options[$id] ) ? $options[$id] : get_option( $id );
				} else {
					global $post;
					$value = get_post_meta( $post->ID, $id, true );
				}

				// Set the default if no value
				if( $value == '' && isset($field['default']) ) {
					$value = $field['default'];
				}

				if( isset($field['type']) ) {

					if( $settings ) {
						$field['id'] = $settings.'['.$id.']';
						$field['option'] = $settings;
					} else {
						$field['id'] = $id;
					}

					// Call the function to display the field
					if ( function_exists('mtphr_galleries_metaboxer_'.$field['type']) ) {
						echo '<div class="mtphr-galleries-metaboxer-appended mtphr-galleries-metaboxer'.$field['id'].'">';
						call_user_func( 'mtphr_galleries_metaboxer_'.$field['type'], $field, $value );
						echo '</div>';
					}
				}
			}
		}
	}
}





/* --------------------------------------------------------- */
/* !Text Field - 1.0.2 */
/* --------------------------------------------------------- */

	function mtphr_galleries_metaboxer_text( $field, $value='' ) {
		$size = ( isset($field['size']) ) ? $field['size'] : 40;
		$before = ( isset($field['before']) ) ? '<span>'.$field['before'].' </span>' : '';
		$after = ( isset($field['after']) ) ? '<span> '.$field['after'].'</span>' : '';
		$text_align = ( isset($field['text_align']) ) ? ' style="text-align:'.$field['text_align'].'"' : '' ;
		$output = $before.'<input name="'.$field['id'].'" id="'.$field['id'].'" type="text" value="'.$value.'" size="'.$size.'"'.$text_align.'>'.$after;
		echo $output;

		// Add appended fields
		mtphr_galleries_metaboxer_append_field($field);
	}




/* --------------------------------------------------------- */
/* !Number - 1.0.1 */
/* --------------------------------------------------------- */

	function mtphr_galleries_metaboxer_number( $field, $value='' ) {
		$style = ( isset($field['style']) ) ? ' style="'.$field['style'].'"' : '';
		$before = ( isset($field['before']) ) ? '<span>'.$field['before'].' </span>' : '';
		$after = ( isset($field['after']) ) ? '<span> '.$field['after'].'</span>' : '';
		$output = $before.'<input name="'.$field['id'].'" id="'.$field['id'].'" type="number" value="'.$value.'" class="small-text"'.$style.'>'.$after;
		echo $output;

		// Add appended fields
		mtphr_galleries_metaboxer_append_field($field);
	}




/* --------------------------------------------------------- */
/* !Select - 1.0.1 */
/* --------------------------------------------------------- */

	function mtphr_galleries_metaboxer_select( $field, $value='' ) {

		$before = ( isset($field['before']) ) ? '<span>'.$field['before'].' </span>' : '';
		$after = ( isset($field['after']) ) ? '<span> '.$field['after'].'</span>' : '';

		$output = $before.'<select name="'.$field['id'].'" id="'.$field['id'].'">';

	  if( $field['options'] ) {

	  	$key_val = isset( $field['key_val'] ) ? true : false;

		  foreach ( $field['options'] as $key => $option ) {
		  	if( is_numeric($key) && !$key_val ) {
					$name = ( is_array( $option ) ) ? $option['name'] : $option;
					$val = ( is_array( $option ) ) ? $option['value'] : $option;
				} else {
					$name = $option;
					$val = $key;
				}
				$selected = ( $val == $value ) ? 'selected="selected"' : '';
				$output .= '<option value="'.$val.'" '.$selected.'>'.stripslashes( $name ).'</option>';
			}
		}
	  $output .= '</select>'.$after;

		echo $output;

		// Add appended fields
		mtphr_galleries_metaboxer_append_field($field);
	}




/* --------------------------------------------------------- */
/* !Radio - 1.0.1 */
/* --------------------------------------------------------- */

	function mtphr_galleries_metaboxer_radio( $field, $value='' ) {

		if( isset($field['options']) ) {

			$output = '';
			$break = '<br/>';
			if ( isset($field['display']) ) {
				if( $field['display'] == 'inline' ) {
					$break = '&nbsp;&nbsp;&nbsp;&nbsp;';
				}
			}
			foreach( $field['options'] as $i => $option ) {
				$checked = ( $value == $i ) ? 'checked="checked"' : '';
				$output .= '<label><input name="'.$field['id'].'" id="'.$field['id'].'" type="radio" value="'.$i.'" '.$checked.' /> '.$option.'</label>'.$break;
			}
		}

		echo $output;

		// Add appended fields
		mtphr_galleries_metaboxer_append_field($field);
	}




/* --------------------------------------------------------- */
/* !Checkbox - 1.0.1 */
/* --------------------------------------------------------- */

	function mtphr_galleries_metaboxer_checkbox( $field, $value='' ) {

		$output = '';
		$before = ( isset($field['before']) ) ? '<span>'.$field['before'].' </span>' : '';
		$after = ( isset($field['after']) ) ? '<span> '.$field['after'].'</span>' : '';

		if( isset($field['options']) ) {

			$break = '<br/>';
			if ( isset($field['display']) ) {
				if( $field['display'] == 'inline' ) {
					$break = '&nbsp;&nbsp;&nbsp;&nbsp;';
				}
			}
			foreach( $field['options'] as $i => $option ) {
				$checked = ( isset($value[$i]) ) ? 'checked="checked"' : '';
				$output .= '<label><input name="'.$field['id'].'['.$i.']" id="'.$field['id'].'['.$i.']" type="checkbox" value="1" '.$checked.' /> '.$option.'</label>'.$break;
			}

		} else {

			$checked = ( $value == 1 ) ? 'checked="checked"' : '';
			$output .= '<label><input name="'.$field['id'].'" id="'.$field['id'].'" type="checkbox" value="1" '.$checked.' />';
			if( isset($field['label']) ) {
				$output .= ' '.$field['label'];
			}
			$output .= '</label>';
		}

		echo $before.$output.$after;

		// Add appended fields
		mtphr_galleries_metaboxer_append_field($field);
	}




/* --------------------------------------------------------- */
/* !Gallery Field - 1.0.0 */
/* --------------------------------------------------------- */

	function mtphr_galleries_metaboxer_gallery( $field, $value='' ) {
		?>
		<table class="mtphr-galleries-metaboxer-gallery-table">
		<?php
		$multiple = ( isset($field['multiple']) && $field['multiple']==false ) ? false : true;
		if( is_array($value) && count($value) > 0 ) {
			if( $multiple ) {
				foreach( $value as $attachment ) {
					mtphr_galleries_metaboxer_gallery_row( $field['id'], $attachment, $multiple );
				}
			} else {
				mtphr_galleries_metaboxer_gallery_row( $field['id'], $value[0], $multiple );
			}
		} else {
			mtphr_galleries_metaboxer_gallery_row( $field['id'], $value, $multiple );
		}
		?>
		</table>
		<?php
	}

	add_action( 'wp_ajax_mtphr_galleries_metaboxer_ajax_gallery_display', 'mtphr_galleries_metaboxer_ajax_gallery_display' );
	/**
	 * Ajax function used to add additional attachments
	 *
	 * @since 1.0.3
	 */
	function mtphr_galleries_metaboxer_ajax_gallery_display() {

		// Get access to the database
		global $wpdb;

		// Check the nonce
		check_ajax_referer( 'mtphr_galleries', 'security' );

		// Get variables
		$field_id  = $_POST['field_id'];
		$attachments  = $_POST['attachments'];

		foreach( $attachments as $attachment ) {
			//mtphr_galleries_metaboxer_gallery_row( $field_id, $attachment['url'] );
			mtphr_galleries_metaboxer_gallery_row( $field_id, $attachment['id'] );
		}

		die(); // this is required to return a proper result
	}

	// Display the gallery
	function mtphr_galleries_metaboxer_gallery_row( $field_id, $attachment='', $multiple=true ) {
		?>
		<tr class="mtphr-galleries-metaboxer-gallery-item">
			<?php if( $multiple ) { ?>
			<td class="mtphr-galleries-metaboxer-gallery-item-handle"><span><?php _e('Rearrange', 'mtphr-galleries'); ?></span></td>
			<?php } ?>
			<td class="mtphr-galleries-metaboxer-gallery-uploader">
				<?php if( $attachment != '' ) { ?>
					<?php echo mtphr_galleries_metaboxer_gallery_thumbnail( $attachment, 100 ); ?>
					<a id="<?php echo $field_id; ?>" style="display:none;" href="#" class="button"<?php if($multiple){ echo ' multiple="multiple"'; }?>><?php _e('Upload/Select', 'mtphr-galleries'); ?></a>
				<?php } else { ?>
					<a id="<?php echo $field_id; ?>" href="#" class="button"<?php if($multiple){ echo ' multiple="multiple"'; }?>><?php _e('Upload/Select', 'mtphr-galleries'); ?></a>
				<?php } ?>
			</td>
			<td class="mtphr-galleries-metaboxer-gallery-display">
				<input class="mtphr-galleries-metaboxer-gallery-value" type="text" name="<?php echo $field_id; ?><?php if($multiple){ echo '[]'; }?>" value="<?php echo $attachment; ?>" />
			</td>
			<td class="mtphr-galleries-metaboxer-gallery-item-delete"><a href="#"><?php _e('Delete', 'mtphr-galleries'); ?></a></td>
			<?php if( $multiple ) { ?>
			<td class="mtphr-galleries-metaboxer-gallery-item-add"><a href="#"><?php _e('Add', 'mtphr-galleries'); ?></a></td>
			<?php } ?>
		</tr>
		<?php
	}

	add_action( 'wp_ajax_mtphr_galleries_metaboxer_ajax_gallery_update', 'mtphr_galleries_metaboxer_ajax_gallery_update' );
	/**
	 * Ajax function used to update attachments
	 *
	 * @since 1.0.0
	 */
	function mtphr_galleries_metaboxer_ajax_gallery_update() {

		// Get access to the database
		global $wpdb;

		// Check the nonce
		check_ajax_referer( 'mtphr_galleries', 'security' );

		// Get variables
		$value  = $_POST['value'];

		echo mtphr_galleries_metaboxer_gallery_thumbnail( $value, 100 );

		die(); // this is required to return a proper result
	}

	// Get the gallery object type
	function mtphr_galleries_metaboxer_gallery_type( $url ) {

		$type = '';

		$post = get_post( $url );
		if( $post ) {
			$type = substr($post->post_mime_type, 0, 5);
		}

		$url = esc_url_raw( $url );

		if( $type == '' && strpos($url,'.jpg') !== false ) {
	    $type = 'image';
		}
		if( $type == '' && strpos($url,'.jpeg') !== false ) {
	    $type = 'image';
		}
		if( $type == '' && strpos($url,'.png') !== false ) {
	    $type = 'image';
		}
		if( $type == '' && strpos($url,'.gif') !== false ) {
			$type = 'image';
		}
		if( $type == '' && strpos($url,'http://vimeo.com/') !== false ) {
	    $type = 'vimeo';
		}
		if( $type == '' && strpos($url,'http://www.youtube.com/watch?v=') !== false ) {
	    $type = 'youtube';
		}
		/*
if( $type == '' && strpos($url,'.mp3') !== false ) {
			$type = 'audio';
		}
		if( $type == '' && strpos($url,'.m4v') !== false ) {
			$type = 'video';
		}
		if( $type == '' && strpos($url,'.mp4') !== false ) {
			$type = 'video';
		}
		if( $type == '' && strpos($url,'.mov') !== false ) {
			$type = 'video';
		}
		if( $type == '' && strpos($url,'.ogg') !== false ) {
			$type = 'video';
		}
*/

		if( $type == '' || $type == 'video' || $type == 'audio' ) {
			return false;
		} else {
			return $type;
		}
	}

	// Return the gallery resource
	function mtphr_galleries_metaboxer_gallery_resource( $url, $width=false, $height=false, $size='medium' ) {

		$type = '';

		$post = get_post( $url );
		if( $post ) {
			$type = substr($post->post_mime_type, 0, 5);
			if( $type == 'image' ) {
				$img = wp_get_attachment_image_src( $post->ID, $size );
				$url = $img[0];
			}
		}

		// Get the resource type
		if( $type == '' ) {
			$type = mtphr_galleries_metaboxer_gallery_type( $url );
		}

		switch( $type ) {
			case 'image':
				return '<img src="'.$url.'" width="'.$width.'" height="'.$height.'" />';
				break;

			case 'video':
				return false;

			case 'audio':
				return false;

			case 'vimeo':
				$width = $width ? $width : 640;
				$height = $height ? $height : intval( $width/16*9 );
				$id = substr($url, 17);
				return '<iframe src="http://player.vimeo.com/video/'.$id.'?title=0&amp;byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
				break;

			case 'youtube':
				$width = $width ? $width : 640;
				$height = $height ? $height : intval( $width/16*9 );
				$id = substr($url, 31);
				return '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'?rel=0&showinfo=0?wmode=opaque" frameborder="0" allowfullscreen></iframe>';
				break;
		}

		return false;
	}

	function mtphr_galleries_metaboxer_gallery_thumbnail( $url, $width=false, $height=false, $size='medium' ) {

		$type = '';
		$thumb = '';

		$post = get_post( $url );
		if( $post ) {
			$type = substr($post->post_mime_type, 0, 5);
			if( $type == 'image' ) {
				$img = wp_get_attachment_image_src( $post->ID, $size );
				$url = $img[0];
			}
		}

		// Get the resource type
		if( $type == '' ) {
			$type = mtphr_galleries_metaboxer_gallery_type( $url );
		}

		switch( $type ) {
			case 'image':
				return '<img src="'.$url.'" width="'.$width.'" height="'.$height.'" />';
				break;

			case 'vimeo':
				$id = substr($url, 17);
				$vimeo = simplexml_load_file('http://vimeo.com/api/v2/video/'.$id.'.xml');
				$url = $vimeo->video->thumbnail_large;
				return '<img src="'.$url.'" width="'.$width.'" height="'.$height.'" />';
				break;

			case 'youtube':
				$id = substr($url, 31);
				$url = 'http://img.youtube.com/vi/'.$id.'/0.jpg';
				return '<img src="'.$url.'" width="'.$width.'" height="'.$height.'" />';
				break;

			case 'video':
				return false;

			case 'audio':
				return false;
		}

		return false;
	}



