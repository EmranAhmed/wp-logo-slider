<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );


	function wp_logo_slider_locate_shortcode_template( $template_name ) {

		$template_path = apply_filters( 'wp_logo_slider_shortcode_template_dir', '' );
		$default_path  = apply_filters( 'wp_logo_slider_shortcode_template_path', untrailingslashit( WP_Logo_Slider()->plugin_path() ) . '/templates/' );

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name
			)
		);

		// Get default template
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		// Return what we found.
		return apply_filters( 'wp_logo_slider_locate_shortcode_template', $template, $template_name, $template_path );
	}

	function wp_logo_slider_get_shortcode_template( $template_name, $template_args = array() ) {

		$located = apply_filters( 'wp_logo_slider_get_shortcode_template', wp_logo_slider_locate_shortcode_template( $template_name ) );

		do_action( 'wp_logo_slider_before_get_shortcode_template', $template_name, $template_args );

		extract( $template_args );
		include $located;

		do_action( 'wp_logo_slider_after_get_shortcode_template', $template_name, $template_args );
	}
