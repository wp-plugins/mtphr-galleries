jQuery( document ).ready( function($) {

	/* --------------------------------------------------------- */
	/* !Initiate the page tabs - 1.0.5 */
	/* --------------------------------------------------------- */

	$( '#mtphr-galleries-page-tabs' ).tabs({ active: 0 });


	/* --------------------------------------------------------- */
	/* !Gallery thumbnails - 2.0.5 */
	/* --------------------------------------------------------- */

	if( $('.mtphr-galleries-thumbnails').length > 0 ) {
	
		function mtphr_gallery_remove_external() {
			$('.mtphr-galleries-add-external').slideUp( function() {
				$('.mtphr-galleries-add-external-input input').val('');
				$('.mtphr-galleries-add-external-submit a').removeAttr('type');
				$('.mtphr-galleries-add-external-submit').find( 'span, i' ).hide();
			});
		}
	
		function mtphr_gallery_thumbnails_set_order( $table ) {
			$('.mtphr-galleries-thumbnails').find('.mtphr-gallery-thumbnail').each( function(index) {	
				$(this).find('input, select').each( function() {
					var prefix = $(this).attr('data-prefix'),
							param = $(this).attr('data-param');
					$(this).attr('name', prefix+'['+index+']['+param+']');
				});
			});
		}

		$('.mtphr-galleries-thumbnails').sortable( {
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
		  update: function( event, ui ) {
		  	mtphr_gallery_thumbnails_set_order();
		  }
		});

		// Delete thumbnails
		$('.mtphr-gallery-thumbnail-delete').live( 'click', function(e) {
			e.preventDefault();
			
			var $container = $(this).parents('.mtphr-galleries-thumbnails'),
					$buttons = $container.siblings('.mtphr-galleries-add-buttons'),
					single_resource = $container.data('single');

			// Fade out the item
			$(this).parents('.mtphr-gallery-thumbnail').fadeOut( function() {
				var $table = $(this).parents('.mtphr-galleries-thumbnails');
				$(this).remove();
				
				if( single_resource ) {
					$buttons.show();
				}
			});
		});



		/* --------------------------------------------------------- */
		/* !Add images - 2.0.5 */
		/* --------------------------------------------------------- */
		
		$('.mtphr-galleries-add-image').click( function(e) {
		  e.preventDefault();
		  mtphr_gallery_remove_external();

		  // Save the container
		  var $buttons = $(this).parent(),
		  		$container = $buttons.siblings('.mtphr-galleries-thumbnails').find('tr'),
		  		name_resources = $(this).data('prefix'),
		  		single_resource = $(this).data('single');

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
		    multiple: !single_resource,
		    library : {
		    	type : 'image'
	    	}
		  });

		  uploader.on( 'select', function() {
		  
		  	// Hide the buttons
		  	if( single_resource ) {
			  	$buttons.hide();
		  	}

				attachments = uploader.state().get('selection').toJSON();
				if( attachments.length > 0 ) {
					var data = {
						action: 'mtphr_gallery_thumb_ajax',
						type: 'field',
						name_resources: name_resources,
						attachments: attachments,
						security: mtphr_galleries_vars.security
					};
					jQuery.post( ajaxurl, data, function( response ) {

						// Add the audio and adjust the toggles
						$container.append( response );
						mtphr_gallery_thumbnails_set_order();
					});
				}
		  });

		  //Open the uploader dialog
		  uploader.open();

		  return false;
		});
		
		
		
		/* --------------------------------------------------------- */
		/* !Add videos - 2.0.5 */
		/* --------------------------------------------------------- */
		
		$('.mtphr-galleries-add-video').click( function(e) {
		  e.preventDefault();
		  mtphr_gallery_remove_external();

		  // Save the container
		  var $buttons = $(this).parent(),
		  		$container = $buttons.siblings('.mtphr-galleries-thumbnails').find('tr'),
		  		name_resources = $(this).data('prefix'),
		  		single_resource = $(this).data('single');

		  // Create a custom uploader
		  var uploader;
		  if( uploader ) {
		    uploader.open();
		    return;
		  }

		  // Set the uploader attributes
		  uploader = wp.media({
		    title: mtphr_galleries_vars.video_title,
		    button: { text: mtphr_galleries_vars.video_button, size: 'small' },
		    multiple: !single_resource,
		    library : {
		    	type : 'video'
	    	}
		  });

		  uploader.on( 'select', function() {
		  
		  	// Hide the buttons
		  	if( single_resource ) {
			  	$buttons.hide();
		  	}

				attachments = uploader.state().get('selection').toJSON();
				if( attachments.length > 0 ) {
					var data = {
						action: 'mtphr_gallery_thumb_ajax',
						type: 'field',
						name_resources: name_resources,
						attachments: attachments,
						security: mtphr_galleries_vars.security
					};
					jQuery.post( ajaxurl, data, function( response ) {

						// Add the audio and adjust the toggles
						$container.append( response );
						mtphr_gallery_thumbnails_set_order();
					});
				}
		  });

		  //Open the uploader dialog
		  uploader.open();

		  return false;
		});
		
		
		
		/* --------------------------------------------------------- */
		/* !Add audio - 2.0.5 */
		/* --------------------------------------------------------- */
		
		$('.mtphr-galleries-add-audio').click( function(e) {
		  e.preventDefault();
		  mtphr_gallery_remove_external();

		  // Save the container
		  var $buttons = $(this).parent(),
		  		$container = $buttons.siblings('.mtphr-galleries-thumbnails').find('tr'),
		  		name_resources = $(this).data('prefix'),
		  		single_resource = $(this).data('single');

		  // Create a custom uploader
		  var uploader;
		  if( uploader ) {
		    uploader.open();
		    return;
		  }

		  // Set the uploader attributes
		  uploader = wp.media({
		    title: mtphr_galleries_vars.audio_title,
		    button: { text: mtphr_galleries_vars.audio_button, size: 'small' },
		    multiple: !single_resource,
		    library : {
		    	type : 'audio'
	    	}
		  });

		  uploader.on( 'select', function() {
		  
		  	// Hide the buttons
		  	if( single_resource ) {
			  	$buttons.hide();
		  	}

				attachments = uploader.state().get('selection').toJSON();
				if( attachments.length > 0 ) {
					var data = {
						action: 'mtphr_gallery_thumb_ajax',
						type: 'field',
						name_resources: name_resources,
						attachments: attachments,
						security: mtphr_galleries_vars.security
					};
					jQuery.post( ajaxurl, data, function( response ) {

						// Add the audio and adjust the toggles
						$container.append( response );
						mtphr_gallery_thumbnails_set_order();
					});
				}
		  });

		  //Open the uploader dialog
		  uploader.open();

		  return false;
		});
		
		
		
		/* --------------------------------------------------------- */
		/* !Add external - 2.0.5 */
		/* --------------------------------------------------------- */
		
		$('.mtphr-galleries-add-external-submit a').click( function(e) {
			e.preventDefault();
			
			var $external = $(this).parents('.mtphr-galleries-add-external'),
					$buttons = $external.siblings('.mtphr-galleries-add-buttons'),
					$container = $external.siblings('.mtphr-galleries-thumbnails').find('tr'),
					type = $(this).attr('type'),
					$spinner = $(this).siblings('.spinner'),
					$error = $(this).siblings('.mtphr-galleries-add-external-error'),
					value = $(this).parent().prev().children('input').val(),
					parent = $(this).attr('href'),
					name_resources = $(this).data('prefix'),
					single_resource = $(this).data('single');
			
			parent = parent.substr(1, parent.length);
					
			$error.hide();
			$spinner.show();
			
			// Hide the buttons
	  	if( single_resource ) {
		  	$buttons.hide();
	  	}

		  var data = {
				action: 'mtphr_gallery_external_thumb_ajax',
				type: type,
				value: value,
				parent: parent,
				name_resources: name_resources,
				security: mtphr_galleries_vars.security
			};
			jQuery.post( ajaxurl, data, function( response ) {
				
				$spinner.hide();

				if( response == 'error' ) {
					$error.css('display', 'inline-block');
				} else {
					$container.append( response );
					mtphr_gallery_remove_external();
					mtphr_gallery_thumbnails_set_order();
				}
			});			
		});
		
		/* --------------------------------------------------------- */
		/* !Update the external poster image */
		/* --------------------------------------------------------- */
		
		$('.mtphr-galleries-update-poster-button').live( 'click', function(e) {
		  e.preventDefault();

		  // Save the container
		  var $container = $(this).parents('.mtphr-galleries-admin-thumb'),
		  		$input = $container.siblings('.mtphr-galleries-poster'),
		  		$spinner = $(this).children('.mtphr-gallery-spinner-small'),
		  		type = $container.siblings('.mtphr-galleries-type').val(),
		  		value = $container.siblings('.mtphr-galleries-id').val(),
		  		parent = $(this).attr('href');
		  		
		  parent = parent.substr(1, parent.length);
		  
		  $spinner.show();

		  var data = {
				action: 'mtphr_gallery_create_external_thumb_ajax',
				type: type,
				value: value,
				poster: $input.val(),
				parent: parent,
				security: mtphr_galleries_vars.security
			};
			jQuery.post( ajaxurl, data, function( response ) {
	
				if( response ) {

					// Add the thumbnail
					$thumb = $(response);
					$input.val( $thumb.attr('alt') );

					$container.html( $thumb );
					mtphr_gallery_thumbnails_set_order();
					
					$spinner.hide();
				}
			});
		});

		
		
		
		/* --------------------------------------------------------- */
		/* !Add youtube */
		/* --------------------------------------------------------- */
		
		$('.mtphr-galleries-add-youtube').click( function(e) {
		  e.preventDefault();
		  mtphr_gallery_remove_external();

		  // Save the container
		  var $external = $(this).parent().siblings('.mtphr-galleries-add-external'),
		  		$title = $external.find('.mtphr-galleries-add-external-title'),
		  		$input = $external.find('.mtphr-galleries-add-external-input input'),
		  		$button = $external.find('.mtphr-galleries-add-external-submit').children('a');
			
			$title.text( mtphr_galleries_vars.youtube_input_title );
			$button.attr( 'type', 'youtube' );
			
			$input.focus();
			
			$external.slideDown( function() {
				$input.focus();
			});
		});
		
		
		
		/* --------------------------------------------------------- */
		/* !Add vimeo */
		/* --------------------------------------------------------- */
		
		$('.mtphr-galleries-add-vimeo').click( function(e) {
		  e.preventDefault();
		  mtphr_gallery_remove_external();

		  // Save the container
		  var $external = $(this).parent().siblings('.mtphr-galleries-add-external'),
		  		$title = $external.find('.mtphr-galleries-add-external-title'),
		  		$input = $external.find('.mtphr-galleries-add-external-input input'),
		  		$button = $external.find('.mtphr-galleries-add-external-submit').children('a');
			
			$title.text( mtphr_galleries_vars.vimeo_input_title );
			$button.attr( 'type', 'vimeo' );

			$external.slideDown( function() {
				$input.focus();
			});
		});
		
		
		
		/* --------------------------------------------------------- */
		/* !Add poster image - 2.0.4 */
		/* --------------------------------------------------------- */
		
		$('.mtphr-galleries-poster-button.add-poster').live( 'click', function(e) {
		  e.preventDefault();

		  // Save the container
		  var $container = $(this).parents('.mtphr-galleries-admin-thumb'),
		  		$input = $container.siblings('.mtphr-galleries-poster'),
		  		name_resources = $(this).attr('data-prefix');

		  // Create a custom uploader
		  var uploader;
		  if( uploader ) {
		    uploader.open();
		    return;
		  }

		  // Set the uploader attributes
		  uploader = wp.media({
		    title: mtphr_galleries_vars.poster_title,
		    button: { text: mtphr_galleries_vars.poster_button, size: 'small' },
		    multiple: false,
		    library : {
		    	type : 'image'
	    	}
		  });

		  uploader.on( 'select', function() {

				attachments = uploader.state().get('selection').toJSON();
				if( attachments.length > 0 ) {
				
					// Set the input value
					$input.val(attachments[0].id);
					
					// Set the button class
					$(this).removeClass('add-poster').addClass('remove-poster');
						
					var data = {
						action: 'mtphr_gallery_thumb_ajax',
						type: 'thumbnail',
						name_resources: name_resources,
						attachments: attachments,
						security: mtphr_galleries_vars.security
					};
					jQuery.post( ajaxurl, data, function( response ) {

						// Add the poster image
						$content = response;
						$content += '<a class="mtphr-galleries-poster-button remove-poster" href="#">'+mtphr_galleries_vars.remove_poster+'</a>';
						$container.html( $content );
					});
				}
		  });

		  //Open the uploader dialog
		  uploader.open();

		  return false;
		});
		
		
		
		/* --------------------------------------------------------- */
		/* !Remove poster image */
		/* --------------------------------------------------------- */
		
		$('.mtphr-galleries-poster-button.remove-poster').live( 'click', function(e) {
		  e.preventDefault();

		  // Save the container
		  var $container = $(this).parents('.mtphr-galleries-admin-thumb'),
		  		$input = $container.siblings('.mtphr-galleries-poster'),
		  		type = $container.siblings('.mtphr-galleries-type').val();
		  		
		  // Set the button class
		  $(this).removeClass('remove-poster').addClass('add-poster');

		  // Set the input value
			$input.val('');
			
			// Add the icon
			$content = '<i class="mtphr-galleries-icon-'+type+'"></i>';
			$content += '<a class="mtphr-galleries-poster-button add-poster" href="#">'+mtphr_galleries_vars.add_poster+'</a>';
			$container.html($content);
		});
		
		


	}
	
	
});
