jQuery( document ).ready( function($) {

	/* --------------------------------------------------------- */
	/* !Initiate the page tabs - 1.0.5 */
	/* --------------------------------------------------------- */

	$( '#mtphr-galleries-page-tabs' ).tabs({ active: 0 });


	/* --------------------------------------------------------- */
	/* !Gallery thumbnails - 1.0.5 */
	/* --------------------------------------------------------- */

	if( $('#mtphr-galleries-thumbnails').length > 0 ) {

		$('#mtphr-galleries-thumbnails').sortable( {
			items: '.mtphr-gallery-thumbnail',
		  helper: function(e, tr) {
		    var $originals = tr.children();
		    var $helper = tr.clone();
		    $helper.children().each(function(index) {
		      $(this).width($originals.eq(index).width());
		      $(this).height($originals.eq(index).height());
		    });
		    return $helper;
		  },
		});

		// Delete thumbnails
		$('.mtphr-gallery-image-delete').live( 'click', function(e) {
			e.preventDefault();

			// Fade out the item
			$(this).parents('.mtphr-gallery-thumbnail').fadeOut( function() {
				var $table = $(this).parents('#mtphr-galleries-thumbnails');
				$(this).remove();
			});
		});

		// Add images
		$('#mtphr-galleries-add-images').click( function(e) {
		  e.preventDefault();

		  // Save the container
		  var $container = $(this).next().find('tr');

		  // Create a custom uploader
		  var uploader;
		  if( uploader ) {
		    uploader.open();
		    return;
		  }

		  // Set the uploader attributes
		  uploader = wp.media({
		    title: mtphr_galleries_vars.img_title,
		    button: { text: mtphr_galleries_vars.img_button, size: 'small' },
		    multiple: true,
		    library : {
		    	type : 'image'
	    	}
		  });

		  uploader.on( 'select', function() {

				attachments = uploader.state().get('selection').toJSON();
				if( attachments.length > 0 ) {
					var data = {
						action: 'mtphr_gallery_thumb_ajax',
						attachments: attachments,
						security: mtphr_galleries_vars.security
					};
					jQuery.post( ajaxurl, data, function( response ) {

						// Add the audio and adjust the toggles
						$container.append( response );
					});
				}
		  });

		  //Open the uploader dialog
		  uploader.open();

		  return false;
		});
	}

});