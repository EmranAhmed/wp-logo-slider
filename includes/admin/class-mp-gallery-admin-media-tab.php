<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	if ( ! class_exists( 'MP_Gallery_Admin_Media_Tab' ) ) :


		class MP_Gallery_Admin_Media_Tab {

			public function __construct() {
				add_filter( 'media_upload_tabs', array( $this, 'media_tabs' ) );
				add_action( 'media_upload_mp_gallery_tab', array( $this, 'mp_gallery_tab' ) );
			}

			public function media_tabs( $tabs ) {

				$tabs[ 'mp_gallery_tab' ] = esc_html__( 'MP Gallery List', 'mp-gallery' );

				return $tabs;
			}

			public function mp_gallery_tab() {
				wp_iframe( array( $this, 'mp_gallery_admin_list_table' ) );
			}

			public function mp_gallery_admin_list_table() {


				if ( ! class_exists( 'WP_List_Table' ) ) {
					require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
				}

				include_once MP_Gallery()->plugin_path() . '/includes/admin/class-mp-gallery-admin-list-table-iframe.php';

				$wp_list_table = new MP_Gallery_Admin_List_Table_Iframe();

				//$wp_list_table->get_pagenum();

				$wp_list_table->prepare_items();

				?>

				<script type="text/javascript">
					var addMPGalleryShortCode = {
						insert : function ($id) {

							$id = jQuery('#gallery-' + $id).val();
							$column = jQuery('#gallery-column-' + $id).val();
							$template = jQuery('#gallery-template-' + $id).val();

							console.log($id, $column, $template);

							var html = '<p>[mp-gallery id="' + $id + '" column="' + $column + '" template="' + $template + '"]</p>';

							var win = window.dialogArguments || opener || parent || top;
							win.send_to_editor(html);
							return false;
						}
					};

				</script>


				<div class="wrap">
					<?php $wp_list_table->views(); ?>
					<form id="posts-filter" method="post"
					      action="<?php echo add_query_arg( array(
						                                        'chromeless' => TRUE,
						                                        'post_id'    => get_the_ID(),
						                                        'tab'        => 'mp_gallery_tab'
					                                        ), admin_url( 'media-upload.php' ) ) ?>">


						<input type="hidden" name="post_id" id="post_id" value="<?php echo (int) get_the_ID(); ?>"/>

						<p class="mp-gallery-iframe-new-button"><a target="_blank" href="<?php echo add_query_arg( array(
							                                                                           'post_type' => 'mp-gallery',
						                                                                           ), admin_url( 'post-new.php' ) ) ?>"
						                           class="page-title-action">Create New Gallery</a></p>
						<?php $wp_list_table->search_box( 'search', 'post' ); ?>
						<?php $wp_list_table->display(); ?>

					</form>
					<br class="clear"/>
				</div>

				<?php
			}
		}

	endif;

	new MP_Gallery_Admin_Media_Tab();