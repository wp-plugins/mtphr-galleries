/* Table of Contents

* jQuery triggers
* mtphr_gallery_archive

*/

jQuery( document ).ready( function($) {

	/* --------------------------------------------------------- */
	/* !Shortcode generator initialize - 1.0.5 */
	/* --------------------------------------------------------- */

	$('body').on('mtphr_shortcode_generator_init', function() {

		var $container = jQuery('.mtphr-shortcode-gen-container'),
				shortcode = $container.children('input.shortcode').val();

		switch( shortcode ) {
			case 'mtphr_gallery_archive':
				mtphr_shortcode_generate_mtphr_gallery_archive_init( $container );
				break;
			case 'mtphr_gallery':
				mtphr_shortcode_generate_mtphr_gallery_init( $container );
				break;
		}
	});

	/* --------------------------------------------------------- */
	/* !Shortcode generator trigger - 1.0.5 */
	/* --------------------------------------------------------- */

	$('body').on('mtphr_shortcode_generator_value', function() {

		var $container = jQuery('.mtphr-shortcode-gen-container'),
				shortcode = $container.children('input.shortcode').val();

		switch( shortcode ) {
			case 'mtphr_gallery_archive':
				mtphr_shortcode_generate_mtphr_gallery_archive_value( $container );
				break;
			case 'mtphr_gallery':
				mtphr_shortcode_generate_mtphr_gallery_value( $container );
				break;
		}
	});



	/* --------------------------------------------------------- */
	/* !mtphr_gallery_archive init - 1.0.5 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_gallery_archive_init( $container ) {

		var $button = $('.mtphr-shortcode-gen-insert-button');
		$button.show();
	}

	/* --------------------------------------------------------- */
	/* !mtphr_gallery_archive value - 1.0.5 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_gallery_archive_value( $container ) {

		var $insert = $container.children('input.shortcode-insert'),
				att_posts_per_page = $container.find('input[name="posts_per_page"]').val(),
				att_columns = $container.find('select[name="columns"]').val(),
				att_orderby = $container.find('select[name="orderby"]').val(),
				att_order = $container.find('select[name="order"]').val(),
				att_excerpt_length = $container.find('input[name="excerpt_length"]').val(),
				att_excerpt_more = $container.find('input[name="excerpt_more"]').val(),
				att_more_link = $container.find('input[name="more_link"]').is(':checked'),
				att_categories = $container.find('input[name="categories"]').val(),
				att_tags = $container.find('input[name="tags"]').val(),
				att_assets = $container.find('input[name="assets"]').val(),
				value = '[mtphr_gallery_archive';

		if( att_more_link && att_excerpt_more != '' ) {
			att_excerpt_more = '{'+att_excerpt_more+'}';
		}

		if( att_posts_per_page != '' && att_posts_per_page != 6 ) { value += ' posts_per_page="'+parseInt(att_posts_per_page)+'"'; }
		if( att_columns != '' && att_columns != 3 ) { value += ' columns="'+parseInt(att_columns)+'"'; }
		if( att_orderby != 'menu_order' ) { value += ' orderby="'+att_orderby+'"'; }
		if( att_order != 'DESC' ) { value += ' order="'+att_order+'"'; }
		if( att_excerpt_length != '' ) { value += ' excerpt_length="'+att_excerpt_length+'"'; }
		if( att_excerpt_more != '' ) { value += ' excerpt_more="'+att_excerpt_more+'"'; }
		if( att_categories != '' ) { value += ' categories="'+att_categories+'"'; }
		if( att_tags != '' ) { value += ' tags="'+att_tags+'"'; }
		if( att_assets != '' && att_assets != 'thumbnail,title,excerpt' ) { value += ' assets="'+att_assets+'"'; }
		value += ']';

		$insert.val( value );
	}



	/* --------------------------------------------------------- */
	/* !mtphr_gallery init - 1.0.5 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_gallery_init( $container ) {

		var $button = $('.mtphr-shortcode-gen-insert-button'),
				$id = $container.find('select[name="id"]');

		$id.live('change', function() {
			if( $(this).val() == '' ) {
				$button.hide();
			} else {
				$button.show();
			}
		});

		if( $id.val() != '' ) {
			$button.show();
		}
	}

	/* --------------------------------------------------------- */
	/* !mtphr_gallery value - 1.0.5 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_gallery_value( $container ) {

		var $insert = $container.children('input.shortcode-insert'),
				att_id = $container.find('select[name="id"]').val(),
				att_class = $container.find('input[name="class"]').val(),
				att_width = $container.find('input[name="width"]').val(),
				att_height = $container.find('input[name="height"]').val(),
				att_slider_layout = $container.find('input[name="slider_layout"]').val(),
				value = '[mtphr_gallery';

		if( att_id != '' ) { value += ' id="'+parseInt(att_id)+'"'; }
		if( att_class != '' ) { value += ' class="'+att_class+'"'; }
		if( att_width != '' ) { value += ' width="'+parseInt(att_width)+'"'; }
		if( att_height != '' ) { value += ' height="'+parseInt(att_height)+'"'; }
		if( att_slider_layout != '' && att_slider_layout != 'gallery,navigation' ) { value += ' slider_layout="'+att_slider_layout+'"'; }
		value += ']';

		$insert.val( value );
	}


});