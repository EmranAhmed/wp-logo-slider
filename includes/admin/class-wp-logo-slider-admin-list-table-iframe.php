<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );

	if ( ! function_exists( 'WP_Logo_Slider_Admin_List_Table_Iframe' ) ):

		class WP_Logo_Slider_Admin_List_Table_Iframe extends WP_List_Table {

			private $post_type = 'wp-logo-slider';
			private $per_page  = 10;


			public function __construct( $args = array() ) {

				parent::__construct( array(
					                     'plural' => 'posts',
					                     'screen' => NULL,
				                     ) );
			}

			public function prepare_items() {
				global $wp_query;

				$query                     = array();
				$query[ 'post_type' ]      = $this->post_type;
				$query[ 'posts_per_page' ] = $this->per_page;
				$query[ 'orderby' ]        = 'title';
				$query[ 'order' ]          = 'asc';
				$query[ 'post_status' ]    = 'publish';

				wp( $query );

				$this->set_pagination_args( array(
					                            'total_items' => $wp_query->found_posts,
					                            'per_page'    => $this->per_page
				                            ) );
			}

			/**
			 *
			 * @return bool
			 */
			public function has_items() {
				return have_posts();
			}

			public function get_columns() {

				$posts_columns = array();

				//$posts_columns['cb'] = '<input type="checkbox" />';
				$posts_columns[ 'title' ]            = esc_html_x( 'Title', 'column name', 'wp-logo-slider' );
				//$posts_columns[ 'grid_column' ]      = esc_html_x( 'Grid Column', 'column name', 'wp-logo-slider' );
				//$posts_columns[ 'gallery_template' ] = esc_html_x( 'Template', 'column name', 'wp-logo-slider' );
				$posts_columns[ 'add_button' ]       = esc_html_x( 'Action', 'column name', 'wp-logo-slider' );

				return $posts_columns;

			}

			public function column_default( $post, $column_name ) {
				switch ( $column_name ) {
					case 'grid_column':
						echo '<select id="gallery-column-' . get_the_ID() . '">';
						echo '<option value="1">Column 1</option>';
						echo '<option value="2">Column 2</option>';
						echo '<option selected value="3">Column 3</option>';
						echo '<option value="4">Column 4</option>';
						echo '<option value="6">Column 6</option>';
						echo '</select>';
						break;
					case 'gallery_template':
						echo '<select id="gallery-template-' . get_the_ID() . '">';
						echo '<option value="default">Default</option>';
						echo '<option value="simple">Simple</option>';
						echo '<option selected value="hover">Hover</option>';
						echo '</select>';
						break;
					case 'add_button':
						echo '<a class="button button-primary" onclick="addWPLogoSliderShortCode.insert(\'' . get_the_ID() . '\')" href="javascript:;">Insert</a>';
						break;
				}

				//do_action( "manage_{$this->post_type}_posts_custom_column", $column_name, $post->ID );

			}


			public function display_rows( $posts = array(), $level = 0 ) {
				global $wp_query;

				if ( empty( $posts ) ) {
					$posts = $wp_query->posts;
				}

				add_filter( 'the_title', 'esc_html' );

				$this->_display_rows( $posts, $level );

			}

			/**
			 * @param array $posts
			 * @param int   $level
			 */
			private function _display_rows( $posts, $level = 0 ) {

				foreach ( $posts as $post ) {
					$this->single_row( $post, $level );
				}
			}

			public function single_row( $post, $level = 0 ) {
				$global_post = get_post();

				$post = get_post( $post );

				$GLOBALS[ 'post' ] = $post;
				setup_postdata( $post );

				?>
				<tr id="post-<?php echo $post->ID; ?>">
					<?php $this->single_row_columns( $post ); ?>
				</tr>
				<?php
				$GLOBALS[ 'post' ] = $global_post;
			}

			public function column_cb( $post ) {
				if ( current_user_can( 'edit_post', $post->ID ) ): ?>
					<label class="screen-reader-text" for="cb-select-<?php the_ID(); ?>"><?php
							printf( __( 'Select %s' ), _draft_or_post_title() );
						?></label>
					<input id="cb-select-<?php the_ID(); ?>" type="checkbox" name="post[]" value="<?php the_ID(); ?>"/>
					<div class="locked-indicator"></div>
				<?php endif;
			}

			/**
			 *
			 * @return array
			 */
			protected function get_table_classes() {
				return array(
					'widefat',
					'fixed',
					'striped',
					'posts'
				);
			}

			/**
			 *
			 * @return array
			 */
			protected function get_sortable_columns() {
				return array(
					'title' => 'title',
				);
			}

			/**
			 * @since  4.3.0
			 * @access protected
			 *
			 * @param WP_Post $post
			 * @param string  $classes
			 * @param string  $data
			 * @param string  $primary
			 */
			protected function _column_title( $post, $classes, $data, $primary ) {
				echo '<td class="' . $classes . ' page-title" ', $data, '>';
				$this->column_title( $post );
				echo '</td>';
			}

			/**
			 * Handles the title column output.
			 *
			 * @since  4.3.0
			 * @access public
			 *
			 * @global string $mode
			 *
			 * @param WP_Post $post The current WP_Post object.
			 */
			public function column_title( $post ) {


				$title = _draft_or_post_title();
				echo "<strong>";

				printf(
					'<a target="_blank" class="row-title" href="%s">%s</a>',
					get_edit_post_link( $post->ID ),
					esc_attr( $title )
				);

				echo '<input type="hidden" id="gallery-' . get_the_ID() . '" value="' . get_the_ID() . '">';

				echo "</strong>";

			}
		}

	endif;
