<?php
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}


	class MP_Gallery_Admin_Meta_Boxes {

		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
			add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );

			// Save Meta Boxes
			add_action( 'mp-gallery-process-post-meta', 'MP_Gallery_Meta_Box_Images::save', 10, 2 );

		}

		public function add_meta_boxes() {
			$screen = get_current_screen();
			add_meta_box( 'mp-gallery-images', esc_html__( 'Gallery Images', 'mp-gallery' ), 'MP_Gallery_Meta_Box_Images::output', 'mp-gallery', 'normal' );

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

			do_action( 'mp-gallery-process-post-meta', $post_id, $post );

		}
	}

	new MP_Gallery_Admin_Meta_Boxes();
