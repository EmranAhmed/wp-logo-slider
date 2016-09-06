<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );

	if ( ! function_exists( 'wp_logo_slider_array2html_attr' ) ):
		function wp_logo_slider_array2html_attr( $attributes, $do_not_add = array() ) {

			$attributes = wp_parse_args( $attributes, array() );

			if ( ! empty( $do_not_add ) and is_array( $do_not_add ) ) {
				foreach ( $do_not_add as $att_name ) {
					unset( $attributes[ $att_name ] );
				}
			}

			$attributes_array = array();

			foreach ( $attributes as $key => $value ) {
				if ( is_bool( $attributes[ $key ] ) and $attributes[ $key ] === TRUE ) {
					return $attributes[ $key ] ? $key : '';
				} elseif ( is_bool( $attributes[ $key ] ) and $attributes[ $key ] === FALSE ) {
					$attributes_array[] = '';
				} else {
					$attributes_array[] = $key . '="' . $value . '"';
				}
			}

			return implode( ' ', $attributes_array );
		}
	endif;