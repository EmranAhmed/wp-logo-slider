<?php
	/**
	 * Plugin Name:  WP Logo Slider
	 * Description:  WordPress Logo Slider
	 * Plugin URI:   https://wordpress.org/plugins/wp-logo-slider/
	 * Version:      1.0.0
	 * Author:       Emran
	 * Author URI:   https://emran.me/
	 * License:      GPLv2.0+
	 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
	 * Text Domain:  wp-logo-slider
	 * Domain Path:  /languages/
	 */

	defined( 'ABSPATH' ) or die( 'Keep Quit' );

	if ( ! class_exists( 'WP_Logo_Slider' ) ) :

		final class WP_Logo_Slider {

			/**
			 * Instance of this class.
			 *
			 * @var object
			 */
			protected static $instance = NULL;

			/**
			 * Initialize the plugin public actions.
			 */
			private function __construct() {
				// Load plugin text domain
				add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

				add_action( 'admin_footer', array( $this, 'print_templates' ) );

				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

				$this->includes();

				do_action( 'wp_logo_slider_loaded', $this );

			}

			public function print_templates() {
				include_once 'includes/wp-logo-slider-js-template.php';
			}

			/**
			 * Return an instance of this class.
			 *
			 * @return object A single instance of this class.
			 */
			public static function get_instance() {
				// If the single instance hasn't been set, set it now.
				if ( NULL == self::$instance ) {
					self::$instance = new self();
				}

				return self::$instance;
			}

			/**
			 * Includes.
			 *
			 * @return
			 */
			private function includes() {
				include_once 'includes/class-wp-logo-slider-post-type.php';

				if ( is_admin() ) {

					include_once 'includes/admin/class-wp-logo-slider-admin-assets.php';
					include_once 'includes/admin/meta-boxes/class-wp-logo-slider-meta-box-images.php';
					include_once 'includes/admin/class-wp-logo-slider-admin-meta-boxes.php';
					include_once 'includes/admin/class-wp-logo-slider-admin-media-tab.php';
				}
			}

			/**
			 * Get the plugin url.
			 * @return string
			 */
			public function plugin_url() {
				return untrailingslashit( plugins_url( '/', __FILE__ ) );
			}

			/**
			 * Get the plugin path.
			 * @return string
			 */
			public function plugin_path() {
				return untrailingslashit( plugin_dir_path( __FILE__ ) );
			}

			/**
			 * Load the plugin text domain for translation.
			 *
			 * @return void
			 */
			public function load_plugin_textdomain() {
				load_plugin_textdomain( 'wp-logo-slider', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			}

			/**
			 * Add relevant links to plugins page.
			 *
			 * @param  array $links
			 *
			 * @return array
			 */
			public function plugin_action_links( $links ) {
				$plugin_links = array(
					'<a href="' . esc_url( admin_url( 'admin.php?page=wp-logo-slider-settings' ) ) . '">' . esc_html__( 'Settings', 'wp-logo-slider' ) . '</a>',
				);

				return array_merge( $plugin_links, $links );
			}
		}


	endif;

	function WP_Logo_Slider() {
		return WP_Logo_Slider::get_instance();
	}

	WP_Logo_Slider();

	function remove_caption( $form_fields, $post ) {

		$custom_link = esc_url( get_post_meta( $post->ID, 'custom_link', TRUE ) );

		$form_fields[ 'custom_link' ] = array(
			'input'         => 'text',
			'value'         => $custom_link,
			'label'         => __( 'Custom link' ),
			'required'      => FALSE,
			//'helps'         => __( 'Set a location for this attachment' ),
			'show_in_edit'  => FALSE,
			'show_in_modal' => TRUE,
			//'extra_rows'    => array( 'classname' => 'HTML' ),
			//'errors'        => array( 'Voool' )
		);

		return $form_fields;
	}

	add_filter( 'attachment_fields_to_edit', 'remove_caption', 10, 2 );


	function insert_custom_default_caption( $post, $attachment ) {

		if ( substr( $post[ 'post_mime_type' ], 0, 5 ) == 'image' ) {

			if ( strlen( trim( $attachment[ 'custom_link' ] ) ) > 0 && esc_url( $attachment[ 'custom_link' ] ) ) {
				update_post_meta( $post[ 'ID' ], "custom_link", esc_url( $attachment[ 'custom_link' ] ) );
				//$post[ 'custom_link' ] = get_post_meta( $post->ID, "custom_link", TRUE )
			} else {
				$post[ 'errors' ][ 'custom_link' ][ 'errors' ][] = __( 'Image custom link was empty.' );
			}
		}

		return $post;
	}

	add_filter( 'attachment_fields_to_save', 'insert_custom_default_caption', 10, 2 );