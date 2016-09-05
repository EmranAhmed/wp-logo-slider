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

				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

				$this->includes();

				do_action( 'wp_logo_slider_loaded', $this );

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
