jQuery( document ).ready( function($) {
	
	/* --------------------------------------------------------- */
	/* !Likes functionality */
	/* --------------------------------------------------------- */
	
	$('.mtphr-gallery-likes').click( function(e) {
		e.preventDefault();

		var $button = $(this),
				$icon = $button.find('i'),
				$loader = $button.find('.like-loader');
		var postid = $button.attr('href');
		postid = postid.substr( 1, postid.length );

		$icon.hide();
		$loader.show().css('display', 'inline-block');

		// Create the display
		var data = {
			action: 'mtphr_gallery_likes_update',
			postid: postid,
			security: mtphr_galleries_vars.security
		};
		$.post( ajaxurl, data, function( response ) {
			$button.addClass('active');
			$button.children('.mtphr-gallery-likes-count').text(response);
			$icon.fadeIn();
			$loader.hide();
		});
	});

});