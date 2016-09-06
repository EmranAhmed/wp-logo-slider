<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );

	if ( ! class_exists( 'WP_Logo_Slider_Admin_Assets' ) ) :

		class WP_Logo_Slider_Admin_Assets {

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
				wp_register_style( 'wp-logo-slider-admin-styles', WP_Logo_Slider()->plugin_url() . '/assets/css/admin.css', array() );

				wp_enqueue_style( 'jquery-ui-style' );
				//wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style( 'wp-logo-slider-admin-styles' );

			}

			/**
			 * Enqueue scripts.
			 */
			public function admin_scripts() {


				// Register scripts
				wp_register_script( 'wp-logo-slider-admin-scripts', WP_Logo_Slider()->plugin_url() . '/assets/js/admin.js', array(
					'jquery',
					'jquery-ui-sortable',
					'jquery-ui-core',
					'wp-util'
				) );


				wp_enqueue_media();
				wp_enqueue_script( 'wp-logo-slider-admin-scripts' );

				$params = array(
					'plugin_url'   => WP_Logo_Slider()->plugin_url(),
					'ajax_url'     => esc_url( admin_url( 'admin-ajax.php' ) ),
					'delete_title' => esc_attr__( 'Delete Image', 'wp-logo-slider' ),
					'delete_text'  => esc_attr__( 'Delete', 'wp-logo-slider' ),
					'change_title' => esc_attr__( 'Change Image', 'wp-logo-slider' ),
					'change_text'  => esc_attr__( 'Change', 'wp-logo-slider' ),
				);

				wp_localize_script( 'wp-logo-slider-admin-scripts', 'wp_logo_slider_admin_js_object', $params );
			}
		}

		new WP_Logo_Slider_Admin_Assets();
	endif;