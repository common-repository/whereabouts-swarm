<?php
/**
 * Display location
 *
 * @package whereabouts-swarm
 * @since 0.1.0
 */

function whereabouts_swarm_display_location( $args ) {

	$location = get_option( 'whereabouts_swarm_current_location' );

	if ( isset( $loccation ) OR ! empty( $location ) ) {

		$options = get_option( 'whereabouts_swarm_settings' );

		$output = '<div class="whereabouts-swarm-location">';

		$show_venue = ( $options['show_venue'] ) ?: 'on';
		$show_city = ( $options['show_city'] ) ?: 'on';
		$show_country = ( $options['show_country'] ) ?: 'on';
		$show_time_zone = ( $options['show_time_zone'] ) ?: 'on';
		$link_venue = ( $options['link_venue'] ) ?: 'on';

		$venue = $location->venue->name;
		if ( isset( $location->venue->location->city ) ) {
			$city = ( $location->venue->location->city ) ?: '';
		}
		if ( isset( $location->venue->location->country ) ) {
			$country = ( $location->venue->location->country ) ?: '';
		}
		if ( isset( $location->venue->url ) ) {
			$venue_url = ( $location->venue->url ) ?: '';
		}

		$location_output = array();

		if ( isset( $options['show_venue_icon'] ) AND 'on' == $options['show_venue_icon'] ) {
			$output .= '<img class="swarm_icon" src="'.$location->venue->categories[0]->icon->prefix.'bg_32'.$location->venue->categories[0]->icon->suffix.'" alt="" /> ';
		}

		if ( 'on' == $show_venue ) {

			if ( 'on' == $link_venue ) {

				if ( isset( $options['link_venue_website'] ) AND 'on' == $options['link_venue_website'] AND isset( $venue_url ) AND ! empty( $venue_url ) ) {
					$link = $venue_url;
				}
				else {
					$link = 'https://foursquare.com/venue/' . $location->venue->id;
				}

				$location_output[] = '<a class="venue" href="' . $link . '">' . $venue . '</a>';
			}
			else {
				$location_output[] = '<span class="venue">' . $venue . '</span>';
			}

		}

		if ( 'on' == $show_city AND ! empty( $city ) ) {
			$location_output[] = '<span class="city">' . $city . '</span>';
		}

		if ( 'on' == $show_country ) {
			$location_output[] = '<span class="country">' . $country . '</span>';
		}

		$output .= implode( ', ', $location_output );
		$output .= '';

		if ( 'on' == $show_time_zone ) {
			$output .= ', <span class="timezone">UTC';
			$time = $location->timeZoneOffset / 60;
			if ( $time > 0) {
				$output .= '+' . $time;
			}
			else {
				$output .= $time;
			}
			$output .= '</span>';
		}

		$output .= '</div>';

		$output = apply_filters( 'whereabouts-swarm-output', $output, $options, $location );

		return $output;

	}
	else {
		// Do nothing if location is not set
		return;
	}

}

add_shortcode( 'whereabouts-swarm', 'whereabouts_swarm_display_location' );