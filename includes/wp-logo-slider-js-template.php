<?php
	defined( 'ABSPATH' ) or die( 'Keep Quit' );
?>

<script type="text/html" id="tmpl-wp-logo-slider-image-single">
	<li class="image" data-attachment_id="{{ data.attachment_id }}">
		<img src="{{ data.attachment_image }}"/>
		<ul class="actions">
			<input type="hidden" name="_wp_logo_slider_images[]" value="{{ data.attachment_id }}">
			<li><a href="#" class="change button button-small button-primary" title="{{ data.change_title }}">{{ data.change_text }}</a></li>
			<li><a href="#" class="delete button button-small button-danger" title="{{ data.delete_title }}">{{ data.delete_text }}</a></li>
		</ul>
	</li>
</script>
