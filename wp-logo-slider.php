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

			protected static $instance = NULL;

			private function __construct() {
				$this->includes();
				$this->hook();
				do_action( 'wp_logo_slider_loaded', $this );
			}

			public function hook() {
				// Load plugin text domain
				add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

				add_action( 'admin_footer', array( $this, 'print_templates' ) );

				add_action( 'admin_notices', array( $this, 'php_requirement_notices' ) );

				add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_fields_display' ), 10, 2 );

				add_filter( 'attachment_fields_to_save', array( $this, 'attachment_fields_save' ), 10, 2 );

				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
			}

			public function plugin_action_links( $actions ) {
				$plugin_links = array(
					'settings' => '<a href="' . admin_url( 'edit.php?post_type=wp-logo-slider&page=wp-logo-slider-settings' ) . '">' . esc_html__( 'Settings', 'wp-logo-slider' ) . '</a>',
				);

				return array_merge( $plugin_links, $actions );
			}

			public function php_requirement_notices() {
				$class                = 'notice notice-error is-dismissible';
				$php_version          = phpversion();
				$required_php_version = '5.3';
				$message              = sprintf( __( 'Your server is running PHP version %1$s but <strong>WP Logo Slider</strong> requires at least %2$s or higher.', 'wp-logo-slider' ), $php_version, $required_php_version );

				if ( version_compare( $required_php_version, $php_version, '>' ) ) {
					printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
				}
			}

			public function print_templates() {
				include_once 'includes/wp-logo-slider-js-template.php';
			}

			public function attachment_fields_display( $form_fields, $post ) {

				$custom_link = get_post_meta( $post->ID, 'custom_link', TRUE );

				$form_fields[ 'custom_link' ] = array(
					'input'         => 'text',
					'value'         => ( $custom_link ) ? esc_url( $custom_link ) : '',
					'label'         => esc_html__( 'Custom link', 'wp-logo-slider' ),
					'required'      => FALSE,
					//'helps'         => esc_html__( 'Set a custom link for this image', 'wp-logo-slider' ),
					'show_in_edit'  => FALSE,
					'show_in_modal' => TRUE,
					//'extra_rows'    => array( 'className' => 'HTML' ),
					//'errors'        => array( 'Voool' )
				);

				return $form_fields;
			}

			public function attachment_fields_save( $post, $attachment ) {
				if ( substr( $post[ 'post_mime_type' ], 0, 5 ) == 'image' ) {

					if ( strlen( trim( $attachment[ 'custom_link' ] ) ) > 0 ) {
						update_post_meta( $post[ 'ID' ], "custom_link", esc_url( $attachment[ 'custom_link' ] ) );
					} else {
						$post[ 'errors' ][ 'custom_link' ][ 'errors' ][] = esc_html__( 'Image custom link was empty.', 'wp-logo-slider' );
					}
				}

				return $post;
			}

			public static function get_instance() {
				// If the single instance hasn't been set, set it now.
				if ( NULL == self::$instance ) {
					self::$instance = new self();
				}

				return self::$instance;
			}

			private function includes() {

				include_once 'includes/wp-logo-slider-functions.php';
				include_once 'includes/class-wp-logo-slider-post-type.php';

				if ( is_admin() ) {
					include_once 'includes/admin/wp-logo-slider-settings-fields.php';
					include_once 'includes/admin/class-wp-logo-slider-settings.php';
					include_once 'includes/admin/class-wp-logo-slider-admin-assets.php';
					include_once 'includes/admin/meta-boxes/class-wp-logo-slider-meta-box-images.php';
					include_once 'includes/admin/class-wp-logo-slider-admin-meta-boxes.php';
					include_once 'includes/admin/class-wp-logo-slider-admin-media-tab.php';
				}
			}

			public function plugin_url() {
				return untrailingslashit( plugins_url( '/', __FILE__ ) );
			}

			public function plugin_path() {
				return untrailingslashit( plugin_dir_path( __FILE__ ) );
			}

			public function load_plugin_textdomain() {
				load_plugin_textdomain( 'wp-logo-slider', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
			}

			public function plugin_basename() {
				return plugin_basename( __FILE__ );
			}

			public function plugin_file() {
				return __FILE__;
			}

			public function plugin_data( $index = FALSE ) {
				$data = get_plugin_data( __FILE__ );

				if ( $index ) {
					return $data[ $index ];
				}

				return $data;
			}

			public function get_option( $option, $default = FALSE, $is_checkbox = FALSE ) {
				$options = get_option( 'wp_logo_slider_settings', $default );
				if ( isset( $options[ $option ] ) ) {
					return apply_filters( "wp_logo_slider_get_option", $options[ $option ], $option, $options, $default );
				} else {
					if ( $is_checkbox ) {
						return apply_filters( "wp_logo_slider_get_option", '', $option, $options, $default );
					}

					return apply_filters( "wp_logo_slider_get_option", $default, $option, $options, $default );
				}
			}

			public function update_option( $key, $value ) {
				$options         = get_option( 'wp_logo_slider_settings' );
				$options[ $key ] = $value;

				update_option( 'wp_logo_slider_settings', $options );
			}
		}


	endif;

	function WP_Logo_Slider() {
		return WP_Logo_Slider::get_instance();
	}

	WP_Logo_Slider();
