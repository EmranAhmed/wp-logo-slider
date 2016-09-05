jQuery(function ($) {

    "use strict";

    (function () {

        var mp_gallery_frame;
        var $gallery_image_ids = $('#mp-gallery-image-field');
        var $images = $('#mp-gallery-images-container').find('ul.mp-gallery-images');

        $('.add-mp-gallery-images').on('click', 'a', function (event) {
            var $el = $(this);

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (mp_gallery_frame) {
                mp_gallery_frame.open();
                return;
            }

            mp_gallery_frame = wp.media.frames.mp_gallery = wp.media({
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
            mp_gallery_frame.on('select', function () {
                var selection = mp_gallery_frame.state().get('selection');
                var attachment_ids = $gallery_image_ids.val();

                selection.map(function (attachment) {
                    attachment = attachment.toJSON();

                    if (attachment.id) {
                        attachment_ids = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                        var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                        $images.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>');
                    }
                });

                $gallery_image_ids.val(attachment_ids);
            });

            // Finally, open the modal.
            mp_gallery_frame.open();
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
            placeholder          : 'mp-gallery-metabox-sortable-placeholder',
            start                : function (event, ui) {
                ui.item.css('background-color', '#f6f6f6');
            },
            stop                 : function (event, ui) {
                ui.item.removeAttr('style');
            },
            update               : function () {
                var attachment_ids = '';

                $('#mp-gallery-images-container').find('ul li.image').css('cursor', 'default').each(function () {
                    var attachment_id = $(this).attr('data-attachment_id');
                    attachment_ids = attachment_ids + attachment_id + ',';
                });

                $gallery_image_ids.val(attachment_ids);
            }
        });

        // Remove images
        $('#mp-gallery-images-container').on('click', 'a.delete', function () {
            $(this).closest('li.image').remove();

            var attachment_ids = '';

            $('#mp-gallery-images-container').find('ul li.image').css('cursor', 'default').each(function () {
                var attachment_id = $(this).attr('data-attachment_id');
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            $gallery_image_ids.val(attachment_ids);

            return false;
        });

    }());


});