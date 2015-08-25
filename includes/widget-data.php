<?php

/* --------------------------------------------------------- */
/* !Create the data widget class - 2.0.18 */
/* --------------------------------------------------------- */

class mtphr_gallery_data_widget extends WP_Widget {
	
	/** Constructor */
	function __construct() {
		parent::__construct(
			'mtphr-gallery-data',
			__('Metaphor Gallery Data', 'mtphr-galleries'),
			array(
				'classname' => 'mtphr-gallery-data',
				'description' => __('Display individual gallery data', 'mtphr-galleries')
			)
		);
	}

	/** @see WP_Widget::widget */
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

	/** @see WP_Widget::update */
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

	/** @see WP_Widget::form */
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
		
		//echo '<pre>';print_r($instance);echo '</pre>';
		//echo '<pre>';print_r($defaults);echo '</pre>';
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	  <!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'mtphr-galleries' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:97%;" />
		</p>

		<p>
			<!-- Date display: Checkbox -->
			<label><input class="checkbox" type="checkbox" <?php checked( $instance['date_display'], 'on' ); ?> id="<?php echo $this->get_field_id( 'date_display' ); ?>" name="<?php echo $this->get_field_name( 'date_display' ); ?>" /> <?php _e( 'Display date', 'mtphr-galleries' ); ?></label><br/>

			<!-- Client display: Checkbox -->
			<label><input class="checkbox" type="checkbox" <?php checked( $instance['client_display'], 'on' ); ?> id="<?php echo $this->get_field_id( 'client_display' ); ?>" name="<?php echo $this->get_field_name( 'client_display' ); ?>" /> <?php _e( 'Display client', 'mtphr-galleries' ); ?></label><br/>

			<!-- Category display: Checkbox -->
			<label><input class="checkbox" type="checkbox" <?php checked( $instance['category_display'], 'on' ); ?> id="<?php echo $this->get_field_id( 'category_display' ); ?>" name="<?php echo $this->get_field_name( 'category_display' ); ?>" /> <?php _e( 'Display categories', 'mtphr-galleries' ); ?></label><br/>

			<!-- Tag display: Checkbox -->
			<label><input class="checkbox" type="checkbox" <?php checked( $instance['tag_display'], 'on' ); ?> id="<?php echo $this->get_field_id( 'tag_display' ); ?>" name="<?php echo $this->get_field_name( 'tag_display' ); ?>" /> <?php _e( 'Display tags', 'mtphr-galleries' ); ?></label><br/>
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


/* --------------------------------------------------------- */
/* !Register the data widget - 1.0.5 */
/* --------------------------------------------------------- */

function mtphr_gallery_data_widget_init() {
	register_widget( 'mtphr_gallery_data_widget' );
}
add_action( 'widgets_init', 'mtphr_gallery_data_widget_init' );