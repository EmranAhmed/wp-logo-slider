<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );


	if ( ! class_exists( 'WP_Logo_Slider_Admin_Meta_Boxes' ) ):

		class WP_Logo_Slider_Admin_Meta_Boxes {

			public function __construct() {
				add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
				add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );

				// Save Meta Boxes
				add_action( 'wp_logo_slider_process_post_meta', 'WP_Logo_Slider_Meta_Box_Images::save', 10, 2 );

			}

			public function add_meta_boxes() {
				$screen = get_current_screen();
				add_meta_box( 'wp-logo-slider-images', esc_html__( 'Slider Images', 'wp-logo-slider' ), 'WP_Logo_Slider_Meta_Box_Images::output', 'wp-logo-slider', 'normal' );

			}

			public function save_meta_boxes( $post_id, $post ) {

				// $post_id and $post are required
				if ( empty( $post_id ) || empty( $post ) ) {
					return;
				}

				if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
					return;
				}

				// Check user has permission to edit
				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}

				do_action( 'wp_logo_slider_process_post_meta', $post_id, $post );

			}
		}

		new WP_Logo_Slider_Admin_Meta_Boxes();

	endif;
