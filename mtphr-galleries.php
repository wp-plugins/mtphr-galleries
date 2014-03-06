<?php
/*
Plugin Name: Metaphor Galleries
Description: Adds a custom post type to easily create media galleries to add to your site. Add a gallery archive or single gallery to any page with shortcodes.
Version: 2.0.2
Author: Metaphor Creations
Author URI: http://www.metaphorcreations.com
License: GPL2
*/

/*
Copyright 2012 Metaphor Creations  (email : joe@metaphorcreations.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
Fugue Icons

Copyright (C) 2011 Yusuke Kamiyamane. All rights reserved.
The icons are licensed under a Creative Commons Attribution
3.0 license. <http://creativecommons.org/licenses/by/3.0/>

<http://p.yusukekamiyamane.com/>
*/




/**Define Widget Constants */
if ( WP_DEBUG ) {
	define ( 'MTPHR_GALLERIES_VERSION', '2.0.2-'.time() );
} else {
	define ( 'MTPHR_GALLERIES_VERSION', '2.0.2' );
}
define ( 'MTPHR_GALLERIES_DIR', plugin_dir_path(__FILE__) );
define ( 'MTPHR_GALLERIES_URL', plugins_url().'/mtphr-galleries' );



// Load the general functions
require_once( MTPHR_GALLERIES_DIR.'includes/scripts.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/post-types.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/taxonomies.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/filters.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/functions.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/widget-categories.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/widget-data.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/shortcodes.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/display.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/helpers.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/ajax.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/wpml.php' );
require_once( MTPHR_GALLERIES_DIR.'includes/settings.php' );

// Load the admin functions - @since 1.0
if ( is_admin() ) {
	require_once( MTPHR_GALLERIES_DIR.'includes/meta-boxes.php' );
	require_once( MTPHR_GALLERIES_DIR.'includes/shortcode-gen.php' );
	require_once( MTPHR_GALLERIES_DIR.'includes/edit-columns.php' );
}



/* --------------------------------------------------------- */
/* !Register the post type & flush the rewrite rules - 1.0.0 */
/* --------------------------------------------------------- */

function mtphr_galleries_activation() {
	mtphr_galleries_posttype();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'mtphr_galleries_activation' );

/* --------------------------------------------------------- */
/* !Flush the rewrite rules - 1.0.0 */
/* --------------------------------------------------------- */

function mtphr_galleries_deactivation() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'mtphr_galleries_deactivation' );



