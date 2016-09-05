jQuery(function ($) {

    "use strict";

    (function () {

        var wp_logo_slider_frame,
            $slider_image_ids        = $('#wp-logo-slider-image-field'),
            $slider_images_container = $('#wp-logo-slider-images-container');

        var $images = $slider_images_container.find('ul.wp-logo-slider-images');

        $('.add-wp-logo-slider-images').on('click', 'a', function (event) {
            var $el = $(this);

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (wp_logo_slider_frame) {
                wp_logo_slider_frame.open();
                return;
            }

            wp_logo_slider_frame = wp.media.frames.wp_logo_slider = wp.media({
                title    : $el.data('choose'),
                multiple : 'add',
                editing  : true,
                library  : {
                    type : 'image'
                },
                button   : {
                    text : $el.data('update')
                }
            });

            ///


            // When an image is selected, run a callback.
            wp_logo_slider_frame.on('select', function () {
                var selection = wp_logo_slider_frame.state().get('selection');
                var attachment_ids = $slider_image_ids.val();

                selection.map(function (attachment) {
                    attachment = attachment.toJSON();

                    if (attachment.id) {
                        attachment_ids = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                        var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                        $images.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>');
                    }
                });

                $slider_image_ids.val(attachment_ids);
            });

            // Finally, open the modal.
            wp_logo_slider_frame.open();
        });

        // Image ordering
        $images.sortable({
            items                : 'li.image',
            cursor               : 'move',
            scrollSensitivity    : 40,
            forcePlaceholderSize : true,
            forceHelperSize      : false,
            helper               : 'clone',
            opacity              : 0.65,
            placeholder          : 'wp-logo-slider-metabox-sortable-placeholder',
            start                : function (event, ui) {
                ui.item.css('background-color', '#f6f6f6');
            },
            stop                 : function (event, ui) {
                ui.item.removeAttr('style');
            },
            update               : function () {
                var attachment_ids = '';

                $slider_images_container.find('ul li.image').css('cursor', 'default').each(function () {
                    var attachment_id = $(this).attr('data-attachment_id');
                    attachment_ids = attachment_ids + attachment_id + ',';
                });

                $slider_image_ids.val(attachment_ids);
            }
        });

        // Remove images
        $slider_images_container.on('click', 'a.delete', function () {
            $(this).closest('li.image').remove();

            var attachment_ids = '';

            $slider_images_container.find('ul li.image').css('cursor', 'default').each(function () {
                var attachment_id = $(this).attr('data-attachment_id');
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            $slider_image_ids.val(attachment_ids);

            return false;
        });

    }());
});