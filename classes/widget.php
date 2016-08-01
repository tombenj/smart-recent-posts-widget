<?php
/**
 * The custom recent posts widget.
 * This widget gives total control over the output to the user.
 */
class SMART_RECENT_POSTS_WIDGET extends WP_Widget {

	/**
	 * Sets up the widgets.
	 */
	public function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'   => 'widget_smart_recent_entries smart_recent_posts',
			'description' => __( 'An advanced widget that gives you total control over the output of your site’s most recent Posts.', 'smart-recent-posts-widget' ),
			'customize_selective_refresh' => true
		);

		$control_options = array(
			'width'  => 450
		);

		// Create the widget.
		parent::__construct(
			'srpw_widget',                                           // $this->id_base
			__( 'Smart Recent Posts', 'smart-recent-posts-widget' ), // $this->name
			$widget_options,                                         // $this->widget_options
			$control_options                                         // $this->control_options
		);

		$this->alt_option_name = 'widget_smart_recent_entries';

	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		// Get the recent posts
		$recent = srpw_get_recent_posts( $instance );

		if ( $recent ) {

			// Output the theme's $before_widget wrapper.
			echo $args['before_widget'];

			// If both title and title url is not empty, display it.
			if ( ! empty( $instance['title_url'] ) && ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . '<a href="' . esc_url( $instance['title_url'] ) . '" title="' . esc_attr( $instance['title'] ) . '">' . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . '</a>' . $args['after_title'];

			// If the title not empty, display it.
			} elseif ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $args['after_title'];
			}

			// Get the recent posts query.
			echo $recent;

			// Close the theme's widget wrapper.
			echo $args['after_widget'];

		}

	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 */
	public function update( $new_instance, $old_instance ) {

		// Validate post_type submissions
		$name = get_post_types( array( 'public' => true ), 'names' );
		$types = array();
		foreach( $new_instance['post_type'] as $type ) {
			if ( in_array( $type, $name ) ) {
				$types[] = $type;
			}
		}
		if ( empty( $types ) ) {
			$types[] = 'post';
		}

		$instance                     = $old_instance;
		$instance['title']            = sanitize_text_field( $new_instance['title'] );
		$instance['title_url']        = esc_url_raw( $new_instance['title_url'] );

		$instance['ignore_sticky']    = isset( $new_instance['ignore_sticky'] ) ? (bool) $new_instance['ignore_sticky'] : 0;
		$instance['exclude_current']  = isset( $new_instance['exclude_current'] ) ? (bool) $new_instance['exclude_current'] : 0;
		$instance['limit']            = intval( $new_instance['limit'] );
		$instance['offset']           = intval( $new_instance['offset'] );
		$instance['order']            = esc_attr( $new_instance['order'] );
		$instance['orderby']          = esc_attr( $new_instance['orderby'] );
		$instance['post_type']        = $types;
		$instance['post_status']      = esc_attr( $new_instance['post_status'] );
		$instance['cat']              = $new_instance['cat'];
		$instance['tag']              = $new_instance['tag'];
		$instance['cat_exclude']      = $new_instance['cat_exclude'];
		$instance['tag_exclude']      = $new_instance['tag_exclude'];

		$instance['excerpt']          = isset( $new_instance['excerpt'] ) ? (bool) $new_instance['excerpt'] : false;
		$instance['length']           = intval( $new_instance['length'] );
		$instance['date']             = isset( $new_instance['date'] ) ? (bool) $new_instance['date'] : false;
		$instance['date_relative']    = isset( $new_instance['date_relative'] ) ? (bool) $new_instance['date_relative'] : false;
		$instance['date_modified']    = isset( $new_instance['date_modified'] ) ? (bool) $new_instance['date_modified'] : false;
		$instance['readmore']         = isset( $new_instance['readmore'] ) ? (bool) $new_instance['readmore'] : false;
		$instance['readmore_text']    = sanitize_text_field( $new_instance['readmore_text'] );
		$instance['comment_count']    = isset( $new_instance['comment_count'] ) ? (bool) $new_instance['comment_count'] : false;
		$instance['author']           = isset( $new_instance['author'] ) ? (bool) $new_instance['author'] : false;

		$instance['thumbnail']        = isset( $new_instance['thumbnail'] ) ? (bool) $new_instance['thumbnail'] : false;
		$instance['thumbnail_size']   = esc_attr( $new_instance['thumbnail_size'] );
		// $instance['thumb_height']     = intval( $new_instance['thumb_height'] );
		// $instance['thumb_width']      = intval( $new_instance['thumb_width'] );
		$instance['thumbnail_default'] = esc_url_raw( $new_instance['thumbnail_default'] );
		$instance['thumbnail_align']   = esc_attr( $new_instance['thumbnail_align'] );

		$instance['css_class']        = sanitize_html_class( $new_instance['css_class'] );
		$instance['css']              = $new_instance['css'];

		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['before'] = $new_instance['before'];
		} else {
			$instance['before'] = wp_kses_post( $new_instance['before'] );
		}

		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['after'] = $new_instance['after'];
		} else {
			$instance['after'] = wp_kses_post( $new_instance['after'] );
		}

		return $instance;

	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 */
	public function form( $instance ) {

		// Merge the user-selected arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, srpw_get_default_args() );

		// Extract the array to allow easy use of variables.
		extract( $instance );

		// Loads the widget form.
		include( SRPW_INCLUDES . 'form.php' );

	}

}
