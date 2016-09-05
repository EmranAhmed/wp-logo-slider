<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	if ( ! class_exists( 'MP_Gallery_Admin_Assets' ) ) :


		class MP_Gallery_Admin_Assets {

			/**
			 * Hook in tabs.
			 */
			public function __construct() {
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			}

			/**
			 * Enqueue styles.
			 */
			public function admin_styles() {
				global $wp_scripts;

				$jquery_version = isset( $wp_scripts->registered[ 'jquery-ui-core' ]->ver ) ? $wp_scripts->registered[ 'jquery-ui-core' ]->ver : '1.9.2';

				// Register admin styles
				wp_register_style( 'jquery-ui-style', '//code.jquery.com/ui/' . $jquery_version . '/themes/smoothness/jquery-ui.css', array(), $jquery_version );
				wp_register_style( 'mp-gallery-admin-styles', MP_Gallery()->plugin_url() . '/assets/css/admin.css', array() );

				wp_enqueue_style( 'jquery-ui-style' );
				//wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style( 'mp-gallery-admin-styles' );

			}

			/**
			 * Enqueue scripts.
			 */
			public function admin_scripts() {


				// Register scripts
				wp_register_script( 'mp-gallery-admin-scripts', MP_Gallery()->plugin_url() . '/assets/js/admin.js', array(
					'jquery',
					'jquery-ui-sortable',
					'jquery-ui-core',
				) );


				wp_enqueue_media();
				wp_enqueue_script( 'mp-gallery-admin-scripts' );

				$params = array(
					'plugin_url' => MP_Gallery()->plugin_url(),
					'ajax_url'   => admin_url( 'admin-ajax.php' ),
				);

				wp_localize_script( 'mp-gallery-admin-scripts', 'mp_gallery_admin_js_object', $params );
			}
		}
	endif;

	new MP_Gallery_Admin_Assets();
