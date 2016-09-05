<?php
	defined( 'ABSPATH' ) or die( 'Keep Quit' );
?>

<script type="text/html" id="tmpl-wp-logo-slider-image-single">
	<li class="image" data-attachment_id="{{ data.attachment_id }}">
		<img src="{{ data.attachment_image }}"/>
		<ul class="actions">
			<li><a href="#" class="delete" title="{{ data.delete_title }}">{{ data.delete_text }}</a></li>
		</ul>
	</li>
</script>
