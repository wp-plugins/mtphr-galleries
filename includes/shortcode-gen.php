<?php

/* --------------------------------------------------------- */
/* !Add shortcodes to the generator - 1.0.5 */
/* --------------------------------------------------------- */

	function mtphr_galleries_shortcodes() {

		global $mtphr_shortcode_gen_assets;

		$shortcodes = array();
		$shortcodes['mtphr_gallery_archive_gen'] = array(
			'label' => __('Gallery Archive', 'mtphr-galleries'),
			'icon' => 'mtphr-shortcodes-ico-view-thumbnail'
		);
		$shortcodes['mtphr_gallery_gen'] = array(
			'label' => __('Gallery', 'mtphr-galleries'),
			'icon' => 'mtphr-shortcodes-ico-polaroid-2'
		);

		// Add the shortcodes to the list
		$mtphr_shortcode_gen_assets['mtphr_galleries'] = array(
			'label' => __('Metaphor Galleries', 'mtphr-galleries'),
			'shortcodes' => $shortcodes
		);
	}
	add_action( 'admin_init', 'mtphr_galleries_shortcodes' );