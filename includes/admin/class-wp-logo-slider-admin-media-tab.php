<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );

	if ( ! class_exists( 'WP_Logo_Slider_Admin_Media_Tab' ) ) :


		class WP_Logo_Slider_Admin_Media_Tab {

			public function __construct() {
				add_filter( 'media_upload_tabs', array( $this, 'media_tabs' ) );
				add_action( 'media_upload_wp_logo_slider_tab', array( $this, 'tab' ) );
			}

			public function media_tabs( $tabs ) {

				$tabs[ 'wp_logo_slider_tab' ] = esc_html__( 'WP Logo Slider List', 'wp-logo-slider' );

				return $tabs;
			}

			public function tab() {
				wp_iframe( array( $this, 'list_table' ) );
			}

			public function list_table() {


				if ( ! class_exists( 'WP_List_Table' ) ) {
					require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
				}

				include_once WP_Logo_Slider()->plugin_path() . '/includes/admin/class-wp-logo-slider-admin-list-table-iframe.php';

				$wp_list_table = new WP_Logo_Slider_Admin_List_Table_Iframe();

				//$wp_list_table->get_pagenum();

				$wp_list_table->prepare_items();

				?>

				<script type="text/javascript">
					var addWPLogoSliderShortCode = {
						insert : function ($id) {

							$id = jQuery('#gallery-' + $id).val();
							//$column = jQuery('#gallery-column-' + $id).val();
							//$template = jQuery('#gallery-template-' + $id).val();

							//console.log($id, $column, $template);

							var html = '<p>[wp-logo-slider id="' + $id + '"]</p>';

							var win = window.dialogArguments || opener || parent || top;
							win.send_to_editor(html);
							return false;
						}
					};

				</script>


				<div class="wrap">
					<?php $wp_list_table->views(); ?>
					<form id="posts-filter" method="post" action="<?php echo esc_url( add_query_arg( array(
						                                                                                 'chromeless' => TRUE,
						                                                                                 'post_id'    => get_the_ID(),
						                                                                                 'tab'        => 'wp_logo_slider_tab'
					                                                                                 ), admin_url( 'media-upload.php' ) ) ) ?>">


						<input type="hidden" name="post_id" id="post_id" value="<?php echo (int) get_the_ID(); ?>"/>

						<p class="wp-logo-slider-iframe-new-button"><a target="_blank" href="<?php echo add_query_arg( array(
							                                                                                               'post_type' => 'wp-logo-slider',
						                                                                                               ), admin_url( 'post-new.php' ) ) ?>"
						                                               class="page-title-action"><?php esc_html_e( 'Create New Slider', 'wp-logo-slider' ) ?></a></p>
						<?php $wp_list_table->search_box( 'search', 'post' ); ?>
						<?php $wp_list_table->display(); ?>

					</form>
					<br class="clear"/>
				</div>

				<?php
			}
		}

		new WP_Logo_Slider_Admin_Media_Tab();
	endif;

