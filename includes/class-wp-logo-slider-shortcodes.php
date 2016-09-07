<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );

	if ( ! class_exists( 'WP_Logo_Slider_Shortcodes' ) ):

		class WP_Logo_Slider_Shortcodes {

			public function __construct() {
				if ( ! is_admin() ):
					add_action( 'init', array( $this, 'init' ) );
				endif;
			}

			public function init() {
				$shortcodes = array(
					'wp-logo-slider' => __CLASS__ . '::wp_logo_slider_display',
				);

				foreach ( $shortcodes as $shortcode => $function ) {
					add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
				}
			}

			public static function wp_logo_slider_display( $atts ) {
				$atts = shortcode_atts( array( 'id' => '' ), $atts, 'wp-logo-slider' );

				$args = array(
					'post_type'   => 'wp-logo-slider',
					'post_status' => 'publish',
					'p'           => $atts[ 'id' ]
				);


				ob_start();

				$slider = new WP_Query( apply_filters( 'wp-logo-slider_shortcode_query', $args, $atts ) );

				if ( $slider->have_posts() ) :

					wp_logo_slider_get_shortcode_template( 'before-wp-logo-slider.php' );

					while ( $slider->have_posts() ) : $slider->the_post();

						wp_logo_slider_get_shortcode_template( 'wp-logo-slider.php' );
					endwhile; // end of the loop.

					wp_logo_slider_get_shortcode_template( 'after-wp-logo-slider.php' );

				else:
					wp_logo_slider_get_shortcode_template( 'wp-logo-slider-not-found.php' );
				endif;

				wp_reset_postdata();

				return ob_get_clean();
			}
		}

		new WP_Logo_Slider_Shortcodes();
	endif;