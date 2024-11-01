<?php
/**
 * @package whereabouts-swarm
 * @since 0.2.0
 */


/** 
 * Tell WordPress about the whereabouts swarm widget
 *
 * @since 0.2.0
 */

class Whereabouts_Swarm_Widget extends WP_Widget {

    // Instantiate parent object
	function __construct() {

        $widget_slug = 'whereabouts_swarm_widget';

		parent::__construct( $widget_slug, 'Whereabouts: Swarm', array( 'description' => __( 'Shows your current location.', 'whereabouts-swarm' ) ) );
	}

    // Front end display of widget
	function widget( $args, $instance ) {

        $title = apply_filters( 'widget_title', $instance['title'] );

        // Echo widget
        echo $args['before_widget'];
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        echo whereabouts_swarm_display_location( NULL );
        echo $args['after_widget'];

	}

	// Save widget options    
	function update( $new_instance, $old_instance ) {

        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;

	}

    // Output admin widget options form
	function form( $instance ) {

        // Set title variable, if it is not saved
        if ( isset( $instance['title'] ) ) {
            $title = $instance['title'];
        }
        else {
            $title = '';
        }

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'whereabouts-swarm' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
	}
}


/** 
 * Register the widget
 *
 * @since 0.2.0
 */

add_action( 'widgets_init', 'whereabouts_swarm_register_widgets' );

function whereabouts_swarm_register_widgets() {
	register_widget( 'Whereabouts_Swarm_Widget' );
}