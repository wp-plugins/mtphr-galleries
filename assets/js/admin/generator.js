/* Table of Contents

* jQuery triggers
* mtphr_gallery_archive

*/

jQuery( document ).ready( function($) {
	
	
	var $button = $('.mtphr-shortcodes-modal-submit');
	

	/* --------------------------------------------------------- */
	/* !Shortcode generator initialize - 2.0.11 */
	/* --------------------------------------------------------- */

	$('body').on('mtphr_shortcode_generator_init', function() {

		var $container = jQuery('.mtphr-shortcode-gen'),
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
	/* !Shortcode generator trigger - 2.0.11 */
	/* --------------------------------------------------------- */

	$('body').on('mtphr_shortcode_generator_value', function() {

		var $container = jQuery('.mtphr-shortcode-gen'),
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

		var $taxonomy = $container.find('select[name="taxonomy"]'),
				$tax = $container.find('.mtphr-shortcode-gen-taxonomy'),
				$tax_fields = $container.find('.mtphr-shortcode-gen-taxonomy-fields').hide(),
				$terms = $container.find('.mtphr-shortcode-gen-terms');
				
				
		// Taxonomy change
		$taxonomy.live('change', function() {
		
			if( $(this).val() == '' ) {
				$tax_fields.hide();
			} else {
			
				var data = {
					action: 'mtphr_shortcode_gen_tax_change',
					taxonomy: $(this).val(),
					security: mtphr_shortcodes_generator_vars.security
				};
				jQuery.post( ajaxurl, data, function( response ) {
					$terms.html(response);
				});
				$tax_fields.show();
			}
		});
		
		// Trigger the sorting
		$('.mtphr-shortcode-gen-rearranger').sortable( {
			items: '.mtphr-ui-multi-check'
		});	
		
		$button.removeAttr('disabled');
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
				att_taxonomy = $container.find('select[name="taxonomy"]').val(),
				$terms = $container.find('.mtphr-shortcode-gen-terms'),
				att_operator = $container.find('select[name="operator"]').val(),
				$assets = $container.find('.mtphr-shortcode-gen-assets'),
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
			
		if( att_taxonomy == 'mtphr_gallery_category' || att_taxonomy == 'mtphr_gallery_tag' ) {
			
			// Create the term list	
			var term_list = ''
			$terms.each( function( index ) {
				if( $(this).is(':checked') ) {
					term_list += $(this).val()+',';
				}
			});
			term_list = term_list.substr(0, term_list.length-1);
		
			if( att_taxonomy != 'mtphr_gallery_category' ) { value += ' categories="'+term_list+'"'; }
			if( att_taxonomy != 'mtphr_gallery_tag' ) { value += ' tags="'+term_list+'"'; }
			if( att_operator != 'IN' ) { value += ' operator="'+att_operator+'"'; }
		}
		
		if( $assets.length > 0 ) {
			
			// Create the term list	
			var asset_list = ''
			$assets.each( function( index ) {
				if( $(this).is(':checked') ) {
					asset_list += $(this).val()+',';
				}
			});
			asset_list = asset_list.substr(0, asset_list.length-1);
			if( asset_list != '' && asset_list != 'thumbnail,like,title,excerpt' ) { value += ' assets="'+asset_list+'"'; }
		}
		
		value += ']';

		$insert.val( value );
	}



	/* --------------------------------------------------------- */
	/* !mtphr_gallery init - 2.0.11 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_gallery_init( $container ) {

		var $id = $container.find('select[name="id"]');

		$id.live('change', function() {
			if( $(this).val() == '' ) {
				$button.attr('disabled', 'disabled');
			} else {
				$button.removeAttr('disabled');
			}
		});
		
		// Trigger the sorting
		$('.mtphr-shortcode-gen-rearranger').sortable( {
			items: '.mtphr-ui-multi-check'
		});

		if( $id.val() != '' ) {
			$button.removeAttr('disabled');
		}
	}

	/* --------------------------------------------------------- */
	/* !mtphr_gallery value - 1.0.5 */
	/* --------------------------------------------------------- */

	function mtphr_shortcode_generate_mtphr_gallery_value( $container ) {

		var $insert = $container.children('input.shortcode-insert'),
				att_id = $container.find('select[name="id"]').val(),
				att_class = $container.find('input[name="class"]').val(),
				$assets = $container.find('.mtphr-shortcode-gen-assets'),
				value = '[mtphr_gallery';

		if( att_id != '' ) { value += ' id="'+parseInt(att_id)+'"'; }
		if( att_class != '' ) { value += ' class="'+att_class+'"'; }
		if( $assets.length > 0 ) {
			
			// Create the term list	
			var asset_list = ''
			$assets.each( function( index ) {
				if( $(this).is(':checked') ) {
					asset_list += $(this).val()+',';
				}
			});
			asset_list = asset_list.substr(0, asset_list.length-1);
			if( asset_list != '' && asset_list != 'gallery,navigation' ) { value += ' slider_layout="'+asset_list+'"'; }
		}
		
		value += ']';

		$insert.val( value );
	}


});