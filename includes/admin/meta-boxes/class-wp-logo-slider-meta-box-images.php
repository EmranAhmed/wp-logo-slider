<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );

	if ( ! class_exists( 'WP_Logo_Slider_Meta_Box_Images' ) ):

		class WP_Logo_Slider_Meta_Box_Images {

			public static function output( $post ) {
				?>
				<div id="wp-logo-slider-images-container">
					<ul class="wp-logo-slider-images">
						<?php

							$_wp_logo_slider_images = get_post_meta( $post->ID, '_wp_logo_slider_images', TRUE );

							if ( ! empty( $_wp_logo_slider_images ) ) {
								foreach ( $_wp_logo_slider_images as $attachment_id ) {
									$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );

									echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
								' . $attachment . '
								<ul class="actions">
								    <input type="hidden" name="_wp_logo_slider_images[]" value="' . esc_attr( $attachment_id ) . '">
									<li><a href="#" class="change button button-small button-primary" title="' . esc_attr__( 'Change Image', 'wp-logo-slider' ) . '">' . esc_html__( 'Change', 'wp-logo-slider' ) . '</a></li>
									<li><a href="#" class="delete button button-small button-danger" title="' . esc_attr__( 'Delete Image', 'wp-logo-slider' ) . '">' . esc_html__( 'Delete', 'wp-logo-slider' ) . '</a></li>
								</ul>
							</li>';
								}
							}
						?>
					</ul>
				</div>
				<p class="add-wp-logo-slider-images hide-if-no-js">
					<a class="button button-primary button-hero" href="#"
					   data-choose="<?php esc_attr_e( 'Add Image', 'wp-logo-slider' ); ?>"
					   data-update="<?php esc_attr_e( 'Add to Slide', 'wp-logo-slider' ); ?>"
					   data-delete="<?php esc_attr_e( 'Delete Image', 'wp-logo-slider' ); ?>"
					   data-text="<?php esc_attr_e( 'Delete', 'wp-logo-slider' ); ?>">
						<?php esc_html_e( 'Add New Image to Slide', 'wp-logo-slider' ); ?>
					</a>
				</p>
				<?php wp_nonce_field( '_wp_logo_slider_image', '_wp_logo_slider_nonce' ) ?>
				<?php
			}

			public static function save( $post_id, $post ) {
				if ( isset( $_POST[ '_wp_logo_slider_nonce' ] ) && wp_verify_nonce( $_POST[ '_wp_logo_slider_nonce' ], '_wp_logo_slider_image' ) ) {

					if ( isset( $_POST[ '_wp_logo_slider_images' ] ) ) {
						$attachment_ids = $_POST[ '_wp_logo_slider_images' ];
						update_post_meta( $post_id, '_wp_logo_slider_images', $attachment_ids );
					} else {
						delete_post_meta( $post_id, '_wp_logo_slider_images' );
					}
				}
			}
		}

	endif;
