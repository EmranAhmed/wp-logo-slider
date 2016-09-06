<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );

	if ( ! class_exists( 'WP_Logo_Slider_Post_Type' ) ):

		class WP_Logo_Slider_Post_Type {

			private static $post_type = 'wp-logo-slider';

			/**
			 * Hook in methods.
			 */
			public static function init() {
				add_action( 'init', array( __CLASS__, 'register_post_types' ) );
				add_filter( "manage_" . self::$post_type . "_posts_columns", array( __CLASS__, 'columns_table_header' ) );
				add_action( "manage_" . self::$post_type . "_posts_custom_column", array( __CLASS__, 'columns_table_data' ), 10, 2 );
			}

			/**
			 * Register core post types.
			 */
			public static function register_post_types() {

				if ( post_type_exists( 'wp-logo-slider' ) ) {
					return;
				}

				do_action( 'wp_logo_slider_register_post_type' );

				$declaration = self::declaration();
				register_post_type( self::$post_type, $declaration );

			}

			// SHOW THE FEATURED IMAGE

			public static function declaration() {
				return array(
					'label'               => esc_html__( 'WP Logo Slider', 'wp-logo-slider' ),
					'description'         => __( 'WordPress Logo Image Slider', 'wp-logo-slider' ),
					'labels'              => self::label(),
					'supports'            => array( 'title' ),
					'taxonomies'          => array(),
					'hierarchical'        => FALSE,
					'public'              => TRUE,
					'show_ui'             => TRUE,
					'show_in_menu'        => TRUE,
					'menu_position'       => 20,
					'menu_icon'           => 'dashicons-leftright',
					'show_in_admin_bar'   => TRUE,
					'show_in_nav_menus'   => FALSE,
					'can_export'          => TRUE,
					'has_archive'         => FALSE,
					'exclude_from_search' => TRUE,
					'publicly_queryable'  => FALSE,
					'capability_type'     => 'post'
				);
			}

			public static function label() {
				return apply_filters( 'wp_logo_slider_post_type_label', array(
					'name'                  => esc_html_x( 'WP Logo Slider', 'Post Type General Name', 'wp-logo-slider' ),
					'singular_name'         => esc_html_x( 'WP Logo Slider', 'Post Type Singular Name', 'wp-logo-slider' ),
					'menu_name'             => esc_html__( 'WP Logo Slider', 'wp-logo-slider' ),
					'name_admin_bar'        => esc_html__( 'WP Logo Slider', 'wp-logo-slider' ),
					'archives'              => esc_html__( 'WP Logo Slider Archives', 'wp-logo-slider' ),
					'parent_item_colon'     => esc_html__( 'Parent Item:', 'wp-logo-slider' ),
					'all_items'             => esc_html__( 'All Items', 'wp-logo-slider' ),
					'add_new_item'          => esc_html__( 'Add New Item', 'wp-logo-slider' ),
					'add_new'               => esc_html__( 'Add New Item', 'wp-logo-slider' ),
					'new_item'              => esc_html__( 'New Item', 'wp-logo-slider' ),
					'edit_item'             => esc_html__( 'Edit Item', 'wp-logo-slider' ),
					'update_item'           => esc_html__( 'Update Item', 'wp-logo-slider' ),
					'view_item'             => esc_html__( 'View Item', 'wp-logo-slider' ),
					'search_items'          => esc_html__( 'Search Item', 'wp-logo-slider' ),
					'not_found'             => esc_html__( 'Not found', 'wp-logo-slider' ),
					'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'wp-logo-slider' ),
					'featured_image'        => esc_html__( 'Featured Image', 'wp-logo-slider' ),
					'set_featured_image'    => esc_html__( 'Set featured image', 'wp-logo-slider' ),
					'remove_featured_image' => esc_html__( 'Remove featured image', 'wp-logo-slider' ),
					'use_featured_image'    => esc_html__( 'Use as featured image', 'wp-logo-slider' ),
					'insert_into_item'      => esc_html__( 'Add into item', 'wp-logo-slider' ),
					'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', 'wp-logo-slider' ),
					'items_list'            => esc_html__( 'Items list', 'wp-logo-slider' ),
					'items_list_navigation' => esc_html__( 'Items list navigation', 'wp-logo-slider' ),
					'filter_items_list'     => esc_html__( 'Filter items list', 'wp-logo-slider' ),
				) );
			}

			public static function columns_table_header( $defaults ) {

				$date = $defaults[ 'date' ];
				unset( $defaults[ 'date' ] );

				$defaults[ 'shortcode' ] = esc_html__( 'Shortcodes', 'wp-logo-slider' );
				$defaults[ 'function' ] = esc_html__( 'Function', 'wp-logo-slider' );
				$defaults[ 'date' ]      = $date;

				return $defaults;
			}

			public static function columns_table_data( $column_name, $id ) {
				switch ( $column_name ) {
					case 'shortcode':
						echo '<code>[wp-logo-slider id="' . get_the_ID() . '"]</code>';
						break;
					case 'function':
						echo '<code>&lt;?php WP_Logo_Slider()->display("' . get_the_ID() . '"); ?&gt;</code>';
						break;
				}
			}
		}

		WP_Logo_Slider_Post_Type::init();

	endif;

