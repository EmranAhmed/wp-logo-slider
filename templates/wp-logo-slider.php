<?php
	defined( 'ABSPATH' ) or die( 'Keep Quit' );

	$sliders = (array) get_post_meta( get_the_ID(), '_wp_logo_slider_images', TRUE );

	if ( empty( $sliders ) ) {
		return;
	}
?>

<ul>
	<?php foreach ( $sliders as $slide ): ?>
		<?php //$attachment =  ?>
		<li>

			<?php if ( WP_Logo_Slider()->get_option( 'enable_link', FALSE ) ): ?>
			<a href="#">
				<?php endif; ?>
				<?php echo wp_get_attachment_image( $slide ) ?>
				<?php if ( WP_Logo_Slider()->get_option( 'enable_link', FALSE ) ): ?>
			</a>
		<?php endif; ?>

		</li>
	<?php endforeach; ?>
</ul>
