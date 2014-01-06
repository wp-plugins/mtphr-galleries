<?php

/* --------------------------------------------------------- */
/* !Register the category widget - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_gallery_categories_widget_init() {
	register_widget( 'mtphr_gallery_categories_widget' );
}
add_action( 'widgets_init', 'mtphr_gallery_categories_widget_init' );


/* --------------------------------------------------------- */
/* !Register the data widget - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_gallery_data_widget_init() {
	register_widget( 'mtphr_gallery_data_widget' );
}
add_action( 'widgets_init', 'mtphr_gallery_data_widget_init' );




/* --------------------------------------------------------- */
/* !Create the category widget class - 1.0.5 */
/* --------------------------------------------------------- */

class mtphr_gallery_categories_widget extends WP_Widget {


	/* --------------------------------------------------------- */
	/* !Initialize the widget - 1.0.5 */
	/* --------------------------------------------------------- */

	function mtphr_gallery_categories_widget() {

		// Widget settings
		$widget_ops = array(
			'classname' => 'mtphr-gallery-categories',
			'description' => __('Display a list of gallery categories', 'mtphr-galleries')
		);

		// Widget control settings
		$control_ops = array(
			'id_base' => 'mtphr-gallery-categories'
		);

		// Create the widget
		$this->WP_Widget( 'mtphr-gallery-categories', __('Metaphor Gallery Categories', 'mtphr-galleries'), $widget_ops, $control_ops );
	}


	/* --------------------------------------------------------- */
	/* !Display the widget - 1.1.2 */
	/* --------------------------------------------------------- */

	function widget( $args, $instance ) {

		extract( $args );

		// User-selected settings
		$title = $instance['title'];
		$title = apply_filters( 'widget_title', $title );

		$count = isset( $instance['count'] );
		$hierarchical = isset( $instance['hierarchical'] );
		$all = $instance['all'];
		

		// Before widget (defined by themes)
		echo $before_widget;

		// Title of widget (before and after defined by themes)
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		// Get the current category
		$current = isset( $_GET['category'] ) ? $_GET['category'] : '';

		// Get the terms
		$args = array(
			'hierarchical' => $hierarchical
		);
		$terms = get_terms( array('mtphr_gallery_category'), $args );
		$total = count($terms);
		 if ( $total > 0 ) {
	     echo '<ul>';

	     $active = ( $current == '' ) ? ' class="mtphr-galleries-current-category"' : '';
	     $post_count = wp_count_posts('mtphr_gallery');
	     $term_count = ( $count ) ? ' <span class="mtphr-galleries-count">('.$post_count->publish.')</span>' : '';
	     $obj = get_post_type_object( 'mtphr_gallery' );
	     $label = $obj->labels->name;
	     echo '<li'.$active.'><a href="'.add_query_arg( 'category', false, get_permalink($all) ).'">'.sprintf(__('All %s', 'mtphr-galleries'), $label).$term_count.'</a></li>';

	     foreach ( $terms as $term ) {

		     $active = ( $current == $term->slug ) ? ' class="mtphr-galleries-current-category"' : '';
		     $term_count = ( $count ) ? ' <span class="mtphr-galleries-count">('.$term->count.')</span>' : '';
	       echo '<li'.$active.'><a href="'.add_query_arg( 'category', $term->slug, get_permalink($all) ).'">'.$term->name.$term_count.'</a></li>';
	     }
	     echo '</ul>';
		 }

		// After widget (defined by themes)
		echo $after_widget;
	}


	/* --------------------------------------------------------- */
	/* !Update the widget - 1.0.5 */
	/* --------------------------------------------------------- */

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		// Strip tags (if needed) and update the widget settings
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['count'] = $new_instance['count'];
		$instance['hierarchical'] = $new_instance['hierarchical'];
		$instance['all'] = $new_instance['all'];

		return $instance;
	}


	/* --------------------------------------------------------- */
	/* !Create the widget form - 1.0.5 */
	/* --------------------------------------------------------- */

	function form( $instance ) {

		// Set up some default widget settings
		$defaults = array(
			'title' => '',
			'count' => '',
			'hierarchical' => '',
			'all' => ''
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	  <!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'mtphr-galleries' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>

		<p>
		<!-- Count: Checkbox -->
		<input class="checkbox" type="checkbox" <?php checked( $instance['count'], 'on' ); ?> id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Show post counts', 'mtphr-galleries' ); ?></label><br/>

		<!-- Hierarchical: Checkbox -->
		<input class="checkbox" type="checkbox" <?php checked( $instance['hierarchical'], 'on' ); ?> id="<?php echo $this->get_field_id( 'hierarchical' ); ?>" name="<?php echo $this->get_field_name( 'hierarchical' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'hierarchical' ); ?>"><?php _e( 'Show hierarchy', 'mtphr-galleries' ); ?></label>

		</p>

	  <!-- All Page: Select -->
		<p>
			<label for="<?php echo $this->get_field_id( 'all' ); ?>"><?php _e( 'All Galleries Page:', 'mtphr-galleries' ); ?></label><br/>
			<select id="<?php echo $this->get_field_id( 'all' ); ?>" name="<?php echo $this->get_field_name( 'all' ); ?>">
			<?php
			$pages = get_pages( 'numberposts=0&orderby=name&order=ASC' );
			foreach( $pages as $page ) {
				if( $instance['all'] == $page->ID ) {
					echo '<option value="'.$page->ID.'" selected="selected">'.$page->post_title.'</option>';
				} else {
					echo '<option value="'.$page->ID.'">'.$page->post_title.'</option>';
				}
			}
			?>
			</select>
		</p>
		<?php
	}
}



/* --------------------------------------------------------- */
/* !Create the data widget class - 1.0.5 */
/* --------------------------------------------------------- */

class mtphr_gallery_data_widget extends WP_Widget {


	/* --------------------------------------------------------- */
	/* !Initialize the widget - 1.0.5 */
	/* --------------------------------------------------------- */

	function mtphr_gallery_data_widget() {

		// Widget settings
		$widget_ops = array(
			'classname' => 'mtphr-gallery-data',
			'description' => __('Display individual gallery data', 'mtphr-galleries')
		);

		// Widget control settings
		$control_ops = array(
			'id_base' => 'mtphr-gallery-data'
		);

		// Create the widget
		$this->WP_Widget( 'mtphr-gallery-data', __('Metaphor Gallery Data', 'mtphr-galleries'), $widget_ops, $control_ops );
	}


	/* --------------------------------------------------------- */
	/* !Display the widget - 1.0.5 */
	/* --------------------------------------------------------- */

	function widget( $args, $instance ) {

		extract( $args );

		// User-selected settings
		$title = $instance['title'];
		$title = apply_filters( 'widget_title', $title );

		$date_display = isset( $instance['date_display'] );
		$client_display = isset( $instance['client_display'] );
		$category_display = isset( $instance['category_display'] );
		$tag_display = isset( $instance['tag_display'] );
		$button_label = ( $instance['button_label'] != '' ) ? $instance['button_label'] :  __('Launch', 'mtphr-galleries');
		$button_target = $instance['button_target'];

		// Before widget (defined by themes)
		echo $before_widget;

		// Title of widget (before and after defined by themes)
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		echo '<table>';
			if( $date_display ) {
				echo '<tr>';
					echo '<th>'.__('Date:', 'mtphr-galleries').'</th>';
					echo '<td>'.get_the_time( get_option('date_format') ).'</td>';
				echo '</tr>';
			}
			if( $client_display ) {
				$client = get_post_meta( get_the_id(), '_mtphr_gallery_client', true );
				if( $client != '' ) {
					echo '<tr>';
						echo '<th>'.__('Client:', 'mtphr-galleries').'</th>';
						echo '<td>'.sanitize_text_field($client).'</td>';
					echo '</tr>';
				}
			}
			if( $category_display ) {
				$terms = get_the_term_list( get_the_id(), 'mtphr_gallery_category', '', ', ', '' );
				if( $terms != '' ) {
				echo '<tr>';
					echo '<th>'.__('Categories:', 'mtphr-galleries').'</th>';
					echo '<td>'.$terms.'</td>';
				echo '</tr>';
				}
			}
			if( $tag_display ) {
				$terms = get_the_term_list( get_the_id(), 'mtphr_gallery_tag', '', ', ', '' );
				if( $terms != '' ) {
				echo '<tr>';
					echo '<th>'.__('Tags:', 'mtphr-galleries').'</th>';
					echo '<td>'.$terms.'</td>';
				echo '</tr>';
				}
			}
		echo '</table>';

		$link = get_post_meta( get_the_id(), '_mtphr_gallery_link', true );
		if( $link != '' ) {
			echo '<a class="btn mtphr-gallery-link" href="'.esc_url($link).'" target="'.$button_target.'">'.$button_label.'</a>';
		}

		// After widget (defined by themes)
		echo $after_widget;
	}


	/* --------------------------------------------------------- */
	/* !Update the widget - 1.0.5 */
	/* --------------------------------------------------------- */

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		// Strip tags (if needed) and update the widget settings
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['date_display'] = $new_instance['date_display'];
		$instance['client_display'] = $new_instance['client_display'];
		$instance['category_display'] = $new_instance['category_display'];
		$instance['tag_display'] = $new_instance['tag_display'];
		$instance['button_label'] = sanitize_text_field( $new_instance['button_label'] );
		$instance['button_target'] = $new_instance['button_target'];

		return $instance;
	}


	/* --------------------------------------------------------- */
	/* !Create the widget form - 1.0.5 */
	/* --------------------------------------------------------- */

	function form( $instance ) {

		// Set up some default widget settings
		$defaults = array(
			'title' => '',
			'date_display' => '',
			'client_display' => '',
			'category_display' => '',
			'tag_display' => '',
			'button_label' => __('Launch', 'mtphr-galleries'),
			'button_target' => '_blank'
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	  <!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'mtphr-galleries' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>

		<p>
			<!-- Date display: Checkbox -->
			<input class="checkbox" type="checkbox" <?php checked( $instance['date_display'], 'on' ); ?> id="<?php echo $this->get_field_id( 'date_display' ); ?>" name="<?php echo $this->get_field_name( 'date_display' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'date_display' ); ?>"><?php _e( 'Display date', 'mtphr-galleries' ); ?></label><br/>

			<!-- Client display: Checkbox -->
			<input class="checkbox" type="checkbox" <?php checked( $instance['client_display'], 'on' ); ?> id="<?php echo $this->get_field_id( 'client_display' ); ?>" name="<?php echo $this->get_field_name( 'client_display' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'client_display' ); ?>"><?php _e( 'Display client', 'mtphr-galleries' ); ?></label><br/>

			<!-- Category display: Checkbox -->
			<input class="checkbox" type="checkbox" <?php checked( $instance['category_display'], 'on' ); ?> id="<?php echo $this->get_field_id( 'category_display' ); ?>" name="<?php echo $this->get_field_name( 'category_display' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'category_display' ); ?>"><?php _e( 'Display categories', 'mtphr-galleries' ); ?></label><br/>

			<!-- Tag display: Checkbox -->
			<input class="checkbox" type="checkbox" <?php checked( $instance['tag_display'], 'on' ); ?> id="<?php echo $this->get_field_id( 'tag_display' ); ?>" name="<?php echo $this->get_field_name( 'tag_display' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'tag_display' ); ?>"><?php _e( 'Display tags', 'mtphr-galleries' ); ?></label><br/>
		</p>

	  <!-- Button label -->
		<p>
			<label for="<?php echo $this->get_field_id( 'button_label' ); ?>"><?php _e( 'Button label:', 'mtphr-galleries' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'button_label' ); ?>" name="<?php echo $this->get_field_name( 'button_label' ); ?>" value="<?php echo $instance['button_label']; ?>" style="width:97%;" />
		</p>

		<!-- Button target -->
		<p>
			<label for="<?php echo $this->get_field_id( 'button_target' ); ?>"><?php _e( 'Button target:', 'mtphr-galleries' ); ?></label><br/>
			<select id="<?php echo $this->get_field_id( 'button_target' ); ?>" name="<?php echo $this->get_field_name( 'button_target' ); ?>">
				<option <?php selected('_blank', $instance['button_target']); ?>>_blank</option>
				<option <?php selected('_self', $instance['button_target']); ?>>_self</option>
				<option <?php selected('_parent', $instance['button_target']); ?>>_parent</option>
				<option <?php selected('_top', $instance['button_target']); ?>>_top</option>
			</select>
		</p>
		<?php
	}
}

