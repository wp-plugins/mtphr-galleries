<?php

/* --------------------------------------------------------- */
/* !Display the gallery archive - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_gallery_archive_display( $atts, $content = null ) {

	// Set the defaults
	$defaults = array(
		'posts_per_page' => 6,
		'columns' => 3,
		'order' => 'DESC',
		'orderby' => 'menu_order',
		'categories' => false,
		'tags' => false,
		'excerpt_length' => 140,
		'excerpt_more' => '&hellip;',
		'assets' => 'thumbnail,like,title,excerpt',
		'responsive' => false
	);
	$defaults = apply_filters( 'mtphr_gallery_archive_default_args', $defaults );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args );

	// Set the responsiveness of the grid
	$row = apply_filters( 'mtphr_galleries_responsive_grid', $responsive );
	$row_class = $row ? 'mtphr-galleries-row-responsive' : 'mtphr-galleries-row';

	// Filter the container
	$container = apply_filters( 'mtphr_gallery_container', 'article' );

	// Set the span
	$span = intval(12/intval($columns));

	// Create an array of the order
	$asset_order = explode( ',', $assets );

	$page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args = array(
		'post_type'=> 'mtphr_gallery',
		'order' => sanitize_text_field( $order ),
		'orderby' => sanitize_text_field( $orderby ),
		'paged' => $page,
		'posts_per_page' => intval($posts_per_page)
	);

	// Check for query var filters
	if( isset($_GET['category']) ) {
		$categories = $_GET['category'];
	}
	if( isset($_GET['tag']) ) {
		$tags = $_GET['tag'];
	}
	if( $categories || $tags ) {
		$args['tax_query'] = array();
	}
	if( $categories ) {
		$category_array = explode(',', $categories);
		$args['tax_query'][] = array(
			'taxonomy' => 'mtphr_gallery_category',
			'field' => 'slug',
			'terms' => $category_array
		);
	}
	if( $tags ) {
		$tag_array = explode(',', $tags);
		$args['tax_query'][] = array(
			'taxonomy' => 'mtphr_gallery_tag',
			'field' => 'slug',
			'terms' => $tag_array
		);
	}

	// Save the original query & create a new one
	global $wp_query;
	$original_query = $wp_query;
	$wp_query = null;
	$wp_query = new WP_Query();
	$wp_query->query( $args );
	?>

	<?php ob_start(); ?>

	<div class="mtphr-galleries-archive">

	<?php if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

		<?php
		// Get the count
		$count = ( $wp_query->current_post );
		if( $count%intval($columns) == 0 ) {
			echo '<div class="'.$row_class.'">';
		}
		?>

		<div class="mtphr-galleries-grid<?php echo $span; ?>">

			<?php do_action( 'mtphr_gallery_before' ); ?>
			<<?php echo $container; ?> id="post-<?php the_id(); ?>" <?php post_class('mtphr-clearfix'); ?>>
				<?php do_action( 'mtphr_gallery_top' ); ?>

				<?php
				$permalink = ( $categories ) ? add_query_arg( array('taxonomy' => 'mtphr_gallery_category', 'terms' => $categories), get_permalink() ) : remove_query_arg( array('taxonomy', 'terms'), get_permalink() );
				foreach( $asset_order as $asset ) {

					switch( trim($asset) ) {

						case 'thumbnail':
							// Display the gallery thumb
							if( $thumbnail = get_mtphr_gallery_thumbnail() ) {
								echo apply_filters( 'mtphr_gallery_thumbnail', $thumbnail, $permalink );
							}
							break;

						case 'like':
							echo get_mtphr_gallery_likes();
							break;

						case 'title':
							// Display the gallery title
							$title = '<h2 class="mtphr-gallery-title"><a href="'.$permalink.'" title="'.sprintf( esc_attr__('Permalink to %s', 'mtphr-galleries'), the_title_attribute('echo=0') ).'" rel="bookmark">'.get_the_title().'</a></h2>';
							echo apply_filters( 'mtphr_gallery_archive_title', $title );
							break;

						case 'excerpt':

							if( $excerpt_length > 0 ) {

								$links = array();
								preg_match('/{(.*?)\}/s', $excerpt_more, $links);
								if( isset($links[0]) ) {
									$more_link = '<a href="'.get_permalink().'">'.$links[1].'</a>';
									$excerpt_more = preg_replace('/{(.*?)\}/s', $more_link, $excerpt_more);
								}
								$excerpt = wp_html_excerpt( get_the_excerpt(), intval($excerpt_length) );
								$excerpt .= $excerpt_more;

								// Display the member excerpt
								echo '<p class="mtphr-gallery-excerpt">'.apply_filters( 'mtphr_gallery_excerpt', $excerpt, $excerpt_length, $excerpt_more ).'</p>';
							}
							break;
					}
				}
				?>

				<?php do_action( 'mtphr_gallery_bottom' ); ?>
			</<?php echo $container; ?>><!-- #post-<?php the_ID(); ?> -->
			<?php do_action( 'mtphr_gallery_after' ); ?>

		</div>

		<?php
		// Get the count
		$count = $count+1;
		if( $count%intval($columns) == 0 || $count == $wp_query->post_count ) {
			echo '</div>';
		}
		?>

	<?php
	endwhile;
	else :
	endif;
	?>

	<?php if ( $wp_query->max_num_pages > 1 ) { ?>

		<?php ob_start(); ?>
		<nav class="mtphr-galleries-content-nav clearfix">
			<?php if( $prev = get_previous_posts_link(__('Newer', 'mtphr-galleries')) ) { ?>
			<div class="mtphr-galleries-nav-next"><?php echo $prev; ?></div>
			<?php } ?>
			<?php if( $next = get_next_posts_link(__('Older', 'mtphr-galleries')) ) { ?>
			<div class="mtphr-galleries-nav-previous"><?php echo $next; ?></div>
			<?php } ?>
		</nav>

		<?php echo apply_filters( 'mtphr_galleries_archive_navigation', ob_get_clean() ); ?>

	<?php } ?>

	<?php
	$wp_query = null;
	$wp_query = $original_query;
	wp_reset_postdata();
	?>

	</div><!-- .mtphr-gallery-archive -->

	<?php
	// Return the output
	return ob_get_clean();
}
add_shortcode( 'mtphr_gallery_archive', 'mtphr_gallery_archive_display' );



/* --------------------------------------------------------- */
/* !Display a gallery - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_gallery_display( $atts, $content = null ) {

	// Set the defaults
	$defaults = array(
		'id' => '',
		'class' => '',
		'width' => false,
		'height' => false,
		'slider_layout' => 'gallery,navigation'
	);
	$defaults = apply_filters( 'mtphr_gallery_default_args', $defaults );
	$args = shortcode_atts( $defaults, $atts );
	extract( $args );
	return get_mtphr_gallery( $id, $width, $height, $args, $class );
}
add_shortcode( 'mtphr_gallery', 'mtphr_gallery_display' );





