<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}


	class MP_Gallery_Meta_Box_Images {


		public static function output( $post ) {
			?>
			<div id="mp-gallery-images-container">
				<ul class="mp-gallery-images">
					<?php

						$gallery_image = get_post_meta( $post->ID, '_gallery_image', TRUE );

						$attachments = array_filter( explode( ',', $gallery_image ) );

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
									<li><a href="#" class="delete" title="' . esc_attr__( 'Delete image', 'mp-gallery' ) . '">' . esc_html__( 'Delete', 'mp-gallery' ) . '</a></li>
								</ul>
							</li>';

								// rebuild ids to be saved
								$updated_gallery_ids[] = $attachment_id;
							}

							// need to update product meta to set new gallery ids
							if ( $update_meta ) {
								update_post_meta( $post->ID, '_gallery_image', implode( ',', $updated_gallery_ids ) );
							}
						}
					?>
				</ul>

				<input type="hidden" id="mp-gallery-image-field" name="_gallery_image"
				       value="<?php echo esc_attr( $gallery_image ); ?>"/>

			</div>
			<p class="add-mp-gallery-images hide-if-no-js">
				<a class="button button-primary" href="#"
				   data-choose="<?php esc_attr_e( 'Add Image', 'mp-gallery' ); ?>"
				   data-update="<?php esc_attr_e( 'Add to gallery', 'mp-gallery' ); ?>"
				   data-delete="<?php esc_attr_e( 'Delete image', 'mp-gallery' ); ?>"
				   data-text="<?php esc_attr_e( 'Delete', 'mp-gallery' ); ?>"><?php esc_html_e( 'Add Image', 'mp-gallery' ); ?></a>
			</p>
			<?php
		}


		public static function save( $post_id, $post ) {
			$attachment_ids = isset( $_POST[ '_gallery_image' ] ) ? array_filter( explode( ',', $_POST[ '_gallery_image' ] ) ) : array();

			update_post_meta( $post_id, '_gallery_image', implode( ',', $attachment_ids ) );
		}
	}
