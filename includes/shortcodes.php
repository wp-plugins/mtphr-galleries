<?php
/**
 * Shortcodes
 *
 * @package Metaphor Galleries
 */




add_shortcode( 'mtphr_gallery_archive', 'mtphr_gallery_archive_display' );
/**
 * Display the gallery archive.
 *
 * @since 1.0.1
 */
function mtphr_gallery_archive_display( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'posts_per_page' => 6,
		'columns' => 3,
		'excerpt_length' => 140,
		'excerpt_more' => '&hellip;',
		'assets' => 'thumbnail,title,excerpt',
		'responsive' => false
	), $atts ) );
	
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
		'paged' => $page,
		'posts_per_page' => intval($posts_per_page)
	);
	
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
			<<?php echo $container; ?> id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php do_action( 'mtphr_gallery_top' ); ?>

				<?php
				foreach( $asset_order as $asset ) {
					
					switch( trim($asset) ) {
						
						case 'thumbnail':
							// Display the gallery thumb
							if( $thumbnail = get_mtphr_gallery_thumbnail() ) {
								echo apply_filters( 'mtphr_gallery_thumbnail', $thumbnail );			
							}
							break;
							
						case 'title':
							// Display the gallery title
							$title = '<h2 class="mtphr-gallery-title"><a href='.get_permalink().'" title="'.sprintf( esc_attr__('Permalink to %s', 'mtphr-galleries'), the_title_attribute('echo=0') ).'" rel="bookmark">'.get_the_title().'</a></h2>';
							echo apply_filters( 'mtphr_gallery_archive_title', $title );
							break;
							
						case 'excerpt':
						
							// Get the excerpt
							$excerpt = '';
							if( $excerpt_length > 0 ) {
							
								$links = array();
								preg_match('/{(.*?)\}/s', $excerpt_more, $links);
								if( isset($links[0]) ) {
									$more_link = '<a href="'.get_permalink().'">'.$links[1].'</a>';
									$excerpt_more = preg_replace('/{(.*?)\}/s', $more_link, $excerpt_more);
								}
								$excerpt = get_mtphr_galleries_excerpt( $excerpt_length, $excerpt_more );
							}
							
							// Display the member excerpt
							echo '<p class="mtphr-gallery-excerpt">'.apply_filters( 'mtphr_gallery_excerpt', $excerpt, $excerpt_length, $excerpt_more ).'</p>';
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




add_shortcode( 'mtphr_gallery', 'mtphr_gallery_display' );
/**
 * Display a gallery.
 *
 * @since 1.0.0
 */
function mtphr_gallery_display( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'id' => '',
		'class' => '',
		'width' => false,
		'height' => false,
		'slider_layout' => false
	), $atts ) );
	
	unset($atts['id']);
	unset($atts['width']);
	unset($atts['height']);
	unset($atts['class']);
	
	return get_mtphr_gallery( $id, $width, $height, $atts, $class );
}





