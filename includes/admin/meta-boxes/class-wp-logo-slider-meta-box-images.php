<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );

	if ( ! class_exists( 'WP_Logo_Slider_Meta_Box_Images' ) ):

		class WP_Logo_Slider_Meta_Box_Images {


			public static function output( $post ) {
				?>
				<div id="wp-logo-slider-images-container">
					<ul class="wp-logo-slider-images">
						<?php

							$slider_image = get_post_meta( $post->ID, '_slider_image', TRUE );

							$attachments = array_filter( explode( ',', $slider_image ) );

							$update_meta = FALSE;

							if ( ! empty( $attachments ) ) {
								foreach ( $attachments as $attachment_id ) {
									$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );

									// if attachment is empty skip
									if ( empty( $attachment ) ) {
										$update_meta = TRUE;
										continue;
									}

									echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
								' . $attachment . '
								<ul class="actions">
									<li><a href="#" class="change" title="' . esc_attr__( 'Change image', 'wp-logo-slider' ) . '">' . esc_html__( 'Change', 'wp-logo-slider' ) . '</a></li>
									<li><a href="#" class="delete" title="' . esc_attr__( 'Delete image', 'wp-logo-slider' ) . '">' . esc_html__( 'Delete', 'wp-logo-slider' ) . '</a></li>
								</ul>
							</li>';

									// rebuild ids to be saved
									$updated_slider_ids[] = $attachment_id;
								}

								// need to update product meta to set new gallery ids
								if ( $update_meta ) {
									update_post_meta( $post->ID, '_slider_image', implode( ',', $updated_slider_ids ) );
								}
							}
						?>
					</ul>

					<input type="hidden" id="wp-logo-slider-image-field" name="_slider_image" value="<?php echo esc_attr( $slider_image ); ?>"/>

				</div>
				<p class="add-wp-logo-slider-images hide-if-no-js">
					<a class="button button-primary" href="#"
					   data-choose="<?php esc_attr_e( 'Add Image', 'wp-logo-slider' ); ?>"
					   data-update="<?php esc_attr_e( 'Add to slide', 'wp-logo-slider' ); ?>"
					   data-delete="<?php esc_attr_e( 'Delete image', 'wp-logo-slider' ); ?>"
					   data-text="<?php esc_attr_e( 'Delete', 'wp-logo-slider' ); ?>">
						<?php esc_html_e( 'Add Image', 'wp-logo-slider' ); ?>
					</a>
				</p>
				<?php
			}


			public static function save( $post_id, $post ) {
				$attachment_ids = isset( $_POST[ '_slider_image' ] ) ? array_filter( explode( ',', $_POST[ '_slider_image' ] ) ) : array();

				update_post_meta( $post_id, '_slider_image', implode( ',', $attachment_ids ) );
			}
		}

	endif;
