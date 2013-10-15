<?php

/* --------------------------------------------------------- */
/* !Add shortcodes to the generator - 1.0.5 */
/* --------------------------------------------------------- */

	function mtphr_galleries_shortcodes() {

		global $mtphr_shortcode_gen_assets;

		$shortcodes = array();
		$shortcodes['mtphr_gallery_archive_gen'] = array(
			'label' => __('Gallery Archive', 'mtphr-galleries')
		);
		$shortcodes['mtphr_gallery_gen'] = array(
			'label' => __('Gallery', 'mtphr-galleries')
		);

		// Add the shortcodes to the list
		$mtphr_shortcode_gen_assets['mtphr_galleries'] = array(
			'label' => __('Metaphor Galleries', 'mtphr-galleries'),
			'shortcodes' => $shortcodes
		);
	}
	add_action( 'admin_init', 'mtphr_galleries_shortcodes' );



/* --------------------------------------------------------- */
/* !Ajax gallery archive shortcode - 1.0.5 */
/* --------------------------------------------------------- */

	function mtphr_gallery_archive_gen() {
		check_ajax_referer( 'mtphr_shortcode_gen_nonce', 'security' );
		?>
		<div class="mtphr-shortcode-gen-container mtphr-shortcode-gen-mtphr_gallery_archive">
			<input type="hidden" class="shortcode" value="mtphr_gallery_archive" />
			<input type="hidden" class="shortcode-insert" />
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Posts Per Page', 'mtphr-galleries'); ?> <small>(<?php _e('Use -1 to display all', 'mtphr-galleries'); ?>)</small></label>
				<input type="number" name="posts_per_page" value="6" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Columns', 'mtphr-galleries'); ?></label>
				<select name="columns">
					<option>1</option>
					<option>2</option>
					<option selected="selected">3</option>
					<option>4</option>
					<option>5</option>
					<option>6</option>
				</select>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Order By', 'mtphr-galleries'); ?></label>
				<select name="orderby">
					<option>ID</option>
					<option>author</option>
					<option>title</option>
					<option>name</option>
					<option>date</option>
					<option>modified</option>
					<option>parent</option>
					<option>rand</option>
					<option>comment_count</option>
					<option selected="selected">menu_order</option>
				</select>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Order', 'mtphr-galleries'); ?></label>
				<select name="order">
					<option>ASC</option>
					<option selected="selected">DESC</option>
				</select>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Excerpt Length', 'mtphr-galleries'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-galleries'); ?>)</small></label>
				<input type="number" name="excerpt_length" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Excerpt More', 'mtphr-galleries'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-galleries'); ?>)</small></label>
				<input type="text" name="excerpt_more" />
				<label class="checkbox"><input type="checkbox" name="more_link" value="true" /> <?php _e('Link to post', 'mtphr-galleries'); ?></label>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Categories', 'mtphr-galleries'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-galleries'); ?>)</small></label>
				<span class="description"><?php _e('Use slugs separated by (,) commas.', 'mtphr-galleries'); ?></span>
				<input type="text" name="categories" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Tags', 'mtphr-galleries'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-galleries'); ?>)</small></label>
				<span class="description"><?php _e('Use slugs separated by (,) commas.', 'mtphr-galleries'); ?></span>
				<input type="text" name="tags" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Assets', 'mtphr-galleries'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-galleries'); ?>)</small></label>
				<span class="description"><?php _e('Separate assets by (,) commas. Re-order or remove the following: <strong>thumbnail</strong>,<strong>like</strong>,<strong>title</strong>,<strong>excerpt</strong>', 'mtphr-galleries'); ?></span>
				<input type="text" name="assets" value="thumbnail,like,title,excerpt" />
			</div>
		</div>
		<?php
		die();
	}
	add_action( 'wp_ajax_mtphr_gallery_archive_gen', 'mtphr_gallery_archive_gen' );



/* --------------------------------------------------------- */
/* !Ajax gallery shortcode - 1.0.5 */
/* --------------------------------------------------------- */

	function mtphr_gallery_gen() {
		check_ajax_referer( 'mtphr_shortcode_gen_nonce', 'security' );
		$args = array(
		  'posts_per_page' => -1,
		  'orderby' => 'title',
			'order' => 'ASC',
			'post_type' => 'mtphr_gallery'
		);
		$posts = get_posts( $args );
		?>
		<div class="mtphr-shortcode-gen-container mtphr-shortcode-gen-mtphr_gallery_archive">
			<input type="hidden" class="shortcode" value="mtphr_gallery" />
			<input type="hidden" class="shortcode-insert" />
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Gallery', 'mtphr-galleries'); ?> <small class="required">(<?php _e('Required', 'mtphr-galleries'); ?>)</small></label>
				<select name="id">
					<option value=""><?php _e('Select a Gallery', 'mtphr-galleries'); ?></option>
					<?php foreach( $posts as $post ) { ?>
						<option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Class', 'mtphr-galleries'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-galleries'); ?>)</small></label>
				<input type="text" name="class" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Width', 'mtphr-galleries'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-galleries'); ?>)</small></label>
				<input type="number" name="width" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Height', 'mtphr-galleries'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-galleries'); ?>)</small></label>
				<input type="number" name="height" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Layout', 'mtphr-galleries'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-galleries'); ?>)</small></label>
				<span class="description"><?php _e('Separate assets by (,) commas. Re-order or remove the following: <strong>gallery</strong>,<strong>navigation</strong>', 'mtphr-galleries'); ?></span>
				<input type="text" name="slider_layout" value="gallery,navigation" />
			</div>
		</div>
		<?php
		die();
	}
	add_action( 'wp_ajax_mtphr_gallery_gen', 'mtphr_gallery_gen' );

