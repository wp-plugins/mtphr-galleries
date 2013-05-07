jQuery(document).ready( function($) {




/* --------------------------------------------------------- */
/* !Gallery -1.0.1 */
/* --------------------------------------------------------- */

$('.mtphr-galleries-metaboxer-gallery').each( function(index) {

	// Add sorting to the items
	mtphr_galleries_metaboxer_gallery_set_sortable( $(this) );

	// Hide handles
	mtphr_galleries_metaboxer_gallery_hide_handles( $(this) );

	// Hide deletes
	mtphr_galleries_metaboxer_gallery_hide_deletes( $(this) );
});

// Gallery - add sorting to the items
function mtphr_galleries_metaboxer_gallery_set_sortable( $gallery ) {

	$gallery.sortable( {
		handle: '.mtphr-galleries-metaboxer-gallery-item-handle',
		items: '.mtphr-galleries-metaboxer-gallery-item',
		axis: 'y',
		helper: function(e, tr) {
	    var $originals = tr.children();
	    var $helper = tr.clone();
	    $helper.children().each(function(index) {
	      // Set helper cell sizes to match the original sizes
	      $(this).width($originals.eq(index).width())
	    });
	    return $helper;
	  }
	});
}

// Gallery - show handles
function mtphr_galleries_metaboxer_gallery_show_handles( $gallery ) {
	if( $gallery.find('.mtphr-galleries-metaboxer-gallery-item').length > 1 ) {
		$gallery.find('.mtphr-galleries-metaboxer-gallery-item-handle').show();
	}
}

// Gallery - hide handles
function mtphr_galleries_metaboxer_gallery_hide_handles( $gallery ) {
	if( $gallery.find('.mtphr-galleries-metaboxer-gallery-item').length == 1 ) {
		$gallery.find('.mtphr-galleries-metaboxer-gallery-item-handle').hide();
	}
}

// Gallery - show deletes
function mtphr_galleries_metaboxer_gallery_show_deletes( $gallery ) {
	if( $gallery.find('.mtphr-galleries-metaboxer-gallery-item').length > 1 || $gallery.find('.mtphr-galleries-metaboxer-gallery-value').val() != '' ) {
		$gallery.find('.mtphr-galleries-metaboxer-gallery-item-delete').show();
	}
}

// Gallery - hide deletes
function mtphr_galleries_metaboxer_gallery_hide_deletes( $gallery ) {
	if( $gallery.find('.mtphr-galleries-metaboxer-gallery-item').length == 1 && $gallery.find('.mtphr-galleries-metaboxer-gallery-value').val() == '' ) {
		$gallery.find('.mtphr-galleries-metaboxer-gallery-item-delete').hide();
	}
}

// Gallery - handle hover
$('.mtphr-galleries-metaboxer-gallery-item-handle').live( 'hover', function() {
	mtphr_galleries_metaboxer_gallery_set_sortable( $(this).parents('.mtphr-galleries-metaboxer-gallery') );
});

// Gallery - add item click
$('.mtphr-galleries-metaboxer-gallery-item-add').live( 'click', function(e) {
	e.preventDefault();

	$gallery = $(this).parents('.mtphr-galleries-metaboxer-gallery');

	// Create a new item with blank content
	var $parent = $(this).parents('.mtphr-galleries-metaboxer-gallery-item');
	var $new = $parent.clone(true).hide();
	$new.find('input,textarea,select').removeAttr('value').removeAttr('checked').removeAttr('selected');
	$new.find('.mtphr-galleries-metaboxer-gallery-uploader').children().not('a.button').remove();
	$new.find('.mtphr-galleries-metaboxer-gallery-uploader').find('a').css('display', 'inline-block');
	$parent.after($new);
	$new.fadeIn().css('display', 'table-row');

	// Show handles
	mtphr_galleries_metaboxer_gallery_show_handles( $gallery );

	// Show deletes
	mtphr_galleries_metaboxer_gallery_show_deletes( $gallery );
});

// Gallery - delete item click
$('.mtphr-galleries-metaboxer-gallery-item-delete').live( 'click', function(e) {
	e.preventDefault();

	$gallery = $(this).parents('.mtphr-galleries-metaboxer-gallery');

	if( $(this).parents('.mtphr-galleries-metaboxer-gallery-item').siblings().length == 0 ) {

		var $container = $(this).siblings('.mtphr-galleries-metaboxer-gallery-uploader');
		var $input = $(this).siblings('.mtphr-galleries-metaboxer-gallery-display').children('input');

		// Remove old asset && show the button
		$container.children().not('a.button').remove();
		$container.children('a.button').show();
		$input.val('');

		// Hide deletes
		mtphr_galleries_metaboxer_gallery_hide_deletes( $gallery );

	} else {

		// Fade out the item
		$(this).parents('.mtphr-galleries-metaboxer-gallery-item').fadeOut( function() {

			// Hide the delete if only one element
			if( $(this).siblings().length < 2 ) {
				$(this).siblings().find('.mtphr-galleries-metaboxer-gallery-item-handle').hide();
			}

			// Remove the item
			$(this).remove();

			// Hide handles
			mtphr_galleries_metaboxer_gallery_hide_handles( $gallery );

			// Hide deletes
			mtphr_galleries_metaboxer_gallery_hide_deletes( $gallery );
		});
	}
});

// Gallery - uploader click
$('.mtphr-galleries-metaboxer-gallery-uploader a').live('click', function(e) {
  e.preventDefault();

  // Save the field id
  var field_id = $(this).attr('id');
  var multiple = $(this).attr('multiple') ? true : false;

  // Save the container
  var $gallery = $(this).parents('.mtphr-galleries-metaboxer-gallery');
  var $container = $(this).parent();
  var input = $container.next().find('input');

  // Create a custom uploader
  var gallery_uploader;
  if( gallery_uploader ) {
    gallery_uploader.open();
    return;
  }

  var button = multiple ? mtphr_galleries_metaboxer_vars.gallery_lightbox_button : mtphr_galleries_metaboxer_vars.gallery_lightbox_button_single;

  // Set the uploader attributes
  gallery_uploader = wp.media.frames.file_frame = wp.media({
    title: mtphr_galleries_metaboxer_vars.gallery_lightbox_title,
    button: { text: button },
    multiple: multiple,
  });

  gallery_uploader.on( 'select', function() {

		attachments = gallery_uploader.state().get('selection').toJSON();

		if( attachments[0].type == 'audio' || attachments[0].type == 'video' ) {
			input.val(mtphr_galleries_metaboxer_vars.gallery_invalid);
		} else {

			$container.children('a.button').hide();
			$container.append( '<img src="'+attachments[0].url+'" width="100" />' );
			input.val( attachments[0].id );
		}

		// Remove the first item
		attachments.shift();

		if( attachments.length > 0 ) {

			// Create the display
			var data = {
				action: 'mtphr_galleries_metaboxer_ajax_gallery_display',
				field_id: field_id,
				attachments: attachments,
				security: mtphr_galleries_metaboxer_vars.security
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post( ajaxurl, data, function( response ) {

				// Append the new data
				$container.parents('.mtphr-galleries-metaboxer-gallery-table').append( response );

				// Show handles
				mtphr_galleries_metaboxer_gallery_show_handles( $gallery );
			});
		}

		// Show deletes
		mtphr_galleries_metaboxer_gallery_show_deletes( $gallery );
  });

  //Open the uploader dialog
  gallery_uploader.open();

  return false;
});

// Gallery - value change
$('.mtphr-galleries-metaboxer-gallery-value').blur( function() {

	var $input = $(this);
	var value = $input.val();
	var $gallery = $(this).parents('.mtphr-galleries-metaboxer-gallery');
	var $container = $input.parent().siblings('.mtphr-galleries-metaboxer-gallery-uploader');

	if( value == '' ) {

		// Remove old asset && show the button
		$container.children().not('a.button').remove();
		$container.children('a.button').show();

		// Hide deletes
		mtphr_galleries_metaboxer_gallery_hide_deletes( $gallery );

	} else {

		// Create the display
		var data = {
			action: 'mtphr_galleries_metaboxer_ajax_gallery_update',
			value: value,
			security: mtphr_galleries_metaboxer_vars.security
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post( ajaxurl, data, function( response ) {

			if( response ) {

				// Remove old asset && hide the button
				$container.children('a.button').hide();
				$container.children().not('a.button').remove();

				// Add the updated data
				$container.append( $(response) );
			} else {

				$input.val(mtphr_galleries_metaboxer_vars.gallery_invalid+' ('+$input.val()+')');
			}
		});

		// Show deletes
		mtphr_galleries_metaboxer_gallery_show_deletes( $gallery );
	}
});





});