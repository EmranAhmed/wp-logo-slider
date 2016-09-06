<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );


	if ( ! class_exists( 'WP_Logo_Slider_Settings' ) ):

		class WP_Logo_Slider_Settings {

			private $settings_name = 'wp_logo_slider_setting';

			private $fields = array();

			public function __construct() {

				add_action( 'admin_menu', array( $this, 'add_menu' ) );

				add_action( 'admin_init', array( $this, 'settings_init' ), 99 );


				do_action( 'wp_logo_slider_settings_init', $this );
			}



			public function settings_init() {

				register_setting( $this->settings_name, $this->settings_name );

				$this->fields = apply_filters( 'wp_logo_slider_settings', $this->fields );

				foreach ( $this->fields as $tabs ) {

					$tabs = apply_filters( 'wp_logo_slider_setting_sections', $tabs );

					foreach ( $tabs[ 'sections' ] as $section ) {

						add_settings_section(
							$tabs[ 'id' ] . $section[ 'id' ],
							$section[ 'title' ],
							function () use ( $section ) {
								if ( isset( $section[ 'desc' ] ) && ! empty( $section[ 'desc' ] ) ) {
									echo '<div class="inside">' . $section[ 'desc' ] . '</div>';
								}
							},
							$tabs[ 'id' ] . $section[ 'id' ]
						);

						$section = apply_filters( 'wp_logo_slider_setting_fields', $section );

						foreach ( $section[ 'fields' ] as $field ) {

							//$field[ 'label_for' ] = $this->settings_name . '[' . $field[ 'id' ] . ']';
							$field[ 'label_for' ] = $field[ 'id' ] . '-field';
							$field[ 'default' ]   = isset( $field[ 'default' ] ) ? $field[ 'default' ] : '';

							if ( $field[ 'type' ] == 'checkbox' ) {
								unset( $field[ 'label_for' ] );
							}

							add_settings_field(
								$this->settings_name . '[' . $field[ 'id' ] . ']',
								$field[ 'title' ],
								//array( $this, $field[ 'type' ] . '_field_callback' ),
								array( $this, 'field_callback' ),
								$tabs[ 'id' ] . $section[ 'id' ],
								$tabs[ 'id' ] . $section[ 'id' ],
								$field
							);
						}
					}
				}
			}

			public function field_callback( $field ) {

				switch ( $field[ 'type' ] ) {
					case 'checkbox':
						$this->checkbox_field_callback( $field );
						break;

					case 'select':
						$this->select_field_callback( $field );
						break;

					case 'number':
						$this->number_field_callback( $field );
						break;

					case 'post_select':
						$this->post_select_field_callback( $field );
						break;

					default:
						$this->text_field_callback( $field );
						break;
				}
			}

			public function checkbox_field_callback( $args ) {
				$current = esc_attr( Hippo_Ticket()->get_option( $args[ 'id' ] ) );
				$value   = esc_attr( $args[ 'value' ] );
				$size    = isset( $args[ 'size' ] ) && ! is_null( $args[ 'size' ] ) ? $args[ 'size' ] : 'regular';
				$html    = sprintf( '<label><input type="checkbox" id="%2$s-field" name="%4$s[%2$s]" value="%3$s" %5$s/> %6$s</label>', $size, $args[ 'id' ], $value, $this->settings_name, checked( $current, $value, FALSE ), esc_attr( $args[ 'desc' ] ) );

				echo $html;
			}

			public function select_field_callback( $args ) {

				$options = apply_filters( "hippo_ticket_settings_{$args[ 'id' ]}_select_options", $args[ 'options' ] );

				$value = esc_attr( Hippo_Ticket()->get_option( $args[ 'id' ], $args[ 'default' ] ) );

				$options = array_map( function ( $key, $option ) use ( $value ) {
					return "<option value='{$key}'" . selected( $key, $value, FALSE ) . ">{$option}</option>";
				}, array_keys( $options ), $options );

				$size = isset( $args[ 'size' ] ) && ! is_null( $args[ 'size' ] ) ? $args[ 'size' ] : 'regular';
				$html = sprintf( '<select class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]">%3$s</select>', $size, $args[ 'id' ], implode( '', $options ), $this->settings_name );
				$html .= $this->get_field_description( $args );
				echo $html;
			}

			public function get_field_description( $args ) {
				if ( ! empty( $args[ 'desc' ] ) ) {
					$desc = sprintf( '<p class="description">%s</p>', $args[ 'desc' ] );
				} else {
					$desc = '';
				}

				return $desc;
			}

			public function post_select_field_callback( $args ) {

				$options = apply_filters( "hippo_ticket_settings_{$args[ 'id' ]}_post_select_options", $args[ 'options' ] );

				$value = esc_attr( Hippo_Ticket()->get_option( $args[ 'id' ], $args[ 'default' ] ) );

				$options = array_map( function ( $option ) use ( $value ) {
					return "<option value='{$option->ID}'" . selected( $option->ID, $value, FALSE ) . ">$option->post_title</option>";
				}, $options );

				$size = isset( $args[ 'size' ] ) && ! is_null( $args[ 'size' ] ) ? $args[ 'size' ] : 'regular';
				$html = sprintf( '<select class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]">%3$s</select>', $size, $args[ 'id' ], implode( '', $options ), $this->settings_name );
				$html .= $this->get_field_description( $args );
				echo $html;
			}

			public function text_field_callback( $args ) {
				$value = esc_attr( Hippo_Ticket()->get_option( $args[ 'id' ], $args[ 'default' ] ) );
				$size  = isset( $args[ 'size' ] ) && ! is_null( $args[ 'size' ] ) ? $args[ 'size' ] : 'regular';
				$html  = sprintf( '<input type="text" class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]" value="%3$s"/>', $size, $args[ 'id' ], $value, $this->settings_name );
				$html .= $this->get_field_description( $args );
				echo $html;
			}

			public function number_field_callback( $args ) {
				$value = esc_attr( Hippo_Ticket()->get_option( $args[ 'id' ], $args[ 'default' ] ) );
				$size  = isset( $args[ 'size' ] ) && ! is_null( $args[ 'size' ] ) ? $args[ 'size' ] : 'small';

				$min    = isset( $args[ 'min' ] ) && ! is_null( $args[ 'min' ] ) ? 'min="' . $args[ 'min' ] . '"' : '';
				$max    = isset( $args[ 'max' ] ) && ! is_null( $args[ 'max' ] ) ? 'max="' . $args[ 'max' ] . '"' : '';
				$step   = isset( $args[ 'step' ] ) && ! is_null( $args[ 'step' ] ) ? 'step="' . $args[ 'step' ] . '"' : '';
				$suffix = isset( $args[ 'suffix' ] ) && ! is_null( $args[ 'suffix' ] ) ? ' <span>' . $args[ 'suffix' ] . '</span>' : '';

				$html = sprintf( '<input type="number" class="%1$s-text" id="%2$s-field" name="%4$s[%2$s]" value="%3$s" %5$s %6$s %7$s /> %8$s', $size, $args[ 'id' ], $value, $this->settings_name, $min, $max, $step, $suffix );
				$html .= $this->get_field_description( $args );
				echo $html;
			}

			public function add_menu() {
				add_submenu_page(
					'edit.php?post_type=wp-logo-slider',
					'Slider Settings',
					'Settings',
					'manage_options',
					'wp-logo-slider-settings',
					array( $this, 'settings_form' ) );
			}

			public function settings_form() {
				if ( ! current_user_can( 'manage_options' ) ) {
					wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
				}
				?>
				<div class="wrap settings-wrap">

					<h1><?php echo get_admin_page_title() ?></h1>

					<form method="post" action="options.php" enctype="multipart/form-data">
						<?php
							//settings_errors();
							//settings_fields( $this->settings_name );
						?>

						<?php //$this->options_tabs(); ?>

						<div id="settings-tabs">
							<!--							<?php /*foreach ( $this->fields as $tab ): */ ?>

								<div id="<?php /*echo $tab[ 'id' ] */ ?>"
								     class="settings-tab hippo-ticket-setting-tab"
								     style="<?php /*echo( ! isset( $tab[ 'active' ] ) ? 'display: none' : '' ) */ ?>">
									<?php /*foreach ( $tab[ 'sections' ] as $section ):
										$this->do_settings_sections( $tab[ 'id' ] . $section[ 'id' ] );
									endforeach; */ ?>
								</div>

							--><?php /*endforeach; */ ?>
						</div>
						<?php
							submit_button();
						?>
					</form>
				</div>
				<?php
			}

			public function options_tabs() {
				?>
				<h2 class="nav-tab-wrapper wp-clearfix">
					<?php foreach ( $this->fields as $tabs ): ?>
						<a data-target="<?php echo $tabs[ 'id' ] ?>"
						   class="hippo-ticket-settings-nav-tab nav-tab <?php echo ( isset( $tabs[ 'active' ] ) and $tabs[ 'active' ] ) ? 'nav-tab-active' : '' ?> "
						   href="#<?php echo $tabs[ 'id' ] ?>"><?php echo $tabs[ 'title' ] ?></a>
					<?php endforeach; ?>
				</h2>
				<?php
			}

			private function do_settings_sections( $page ) {
				global $wp_settings_sections, $wp_settings_fields;

				if ( ! isset( $wp_settings_sections[ $page ] ) ) {
					return;
				}

				foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
					if ( $section[ 'title' ] ) {
						echo "<h2>{$section['title']}</h2>\n";
					}

					if ( $section[ 'callback' ] ) {
						call_user_func( $section[ 'callback' ], $section );
					}

					if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section[ 'id' ] ] ) ) {
						continue;
					}

					echo '<table class="form-table">';
					$this->do_settings_fields( $page, $section[ 'id' ] );
					echo '</table>';
				}
			}

			private function build_dependency( $require_array ) {
				$b_array = array();
				foreach ( $require_array as $k => $v ) {
					$b_array[ '#' . $k . '-field' ] = $v;
				}

				return "data-depends='[" . json_encode( $b_array ) . "]'";
			}

			private function do_settings_fields( $page, $section ) {
				global $wp_settings_fields;

				if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
					return;
				}

				foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
					/*$class = '';

					if ( ! empty( $field[ 'args' ][ 'class' ] ) ) {
						$class = ' class="' . esc_attr( $field[ 'args' ][ 'class' ] ) . '"';
					}*/

					$custom_attributes = hippo_ticket_array2html_attr( isset( $field[ 'args' ][ 'attributes' ] ) ? $field[ 'args' ][ 'attributes' ] : array() );


					$wrapper_id = ! empty( $field[ 'args' ][ 'id' ] ) ? esc_attr( $field[ 'args' ][ 'id' ] ) . '-wrapper' : '';
					$dependency = ! empty( $field[ 'args' ][ 'require' ] ) ? $this->build_dependency( $field[ 'args' ][ 'require' ] ) : '';


					echo "<tr id='" . $wrapper_id . "' {$custom_attributes} {$dependency}>";

					if ( ! empty( $field[ 'args' ][ 'label_for' ] ) ) {
						echo '<th scope="row"><label for="' . esc_attr( $field[ 'args' ][ 'label_for' ] ) . '">' . $field[ 'title' ] . '</label></th>';
					} else {
						echo '<th scope="row">' . $field[ 'title' ] . '</th>';
					}

					echo '<td>';
					call_user_func( $field[ 'callback' ], $field[ 'args' ] );
					echo '</td>';
					echo '</tr>';
				}
			}
		}

		new WP_Logo_Slider_Settings();

	endif;
