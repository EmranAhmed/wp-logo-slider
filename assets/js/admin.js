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
                wp_logo_slider_frame = null;
            }

            wp_logo_slider_frame = wp.media.frames.wp_logo_slider = wp.media({
                title    : $el.data('choose'),
                multiple : 'add',
                editing  : true,
                //editing  : false,
                library  : {
                    type : 'image'
                },
                button   : {
                    text : $el.data('update')
                }
            });

            // When an image is selected, run a callback.
            wp_logo_slider_frame.on('select', function () {
                var selection = wp_logo_slider_frame.state().get('selection');
                var attachment_ids = $slider_image_ids.val();

                selection.map(function (attachment) {
                    attachment = attachment.toJSON();

                    if (attachment.id) {
                        attachment_ids = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
                        var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                        var template = wp.template('wp-logo-slider-image-single');
                        $images.append(template({
                            attachment_id    : attachment.id,
                            attachment_image : attachment_image,
                            delete_title     : wp_logo_slider_admin_js_object.delete_title,
                            delete_text      : wp_logo_slider_admin_js_object.delete_text,
                            change_title     : wp_logo_slider_admin_js_object.change_title,
                            change_text      : wp_logo_slider_admin_js_object.change_text,
                        }));
                        //$images.append('<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>');
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
        $slider_images_container.on('click', 'a.delete', function (e) {

            e.preventDefault();

            $(this).closest('li.image').remove();

            var attachment_ids = '';

            $slider_images_container.find('ul li.image').css('cursor', 'default').each(function () {
                var attachment_id = $(this).attr('data-attachment_id');
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            $slider_image_ids.val(attachment_ids);

            return false;
        });

        // Change images
        $slider_images_container.on('click', 'a.change', function (e) {

            e.preventDefault();


            if (wp_logo_slider_frame) {
                wp_logo_slider_frame = null;
            }

            wp_logo_slider_frame = wp.media.frames.wp_logo_slider_frame = wp.media({
                title    : $(this).attr('title'),
                button   : {
                    text : $(this).attr('title'),
                },
                multiple : false,
                library  : {
                    type : 'image'
                }
            });

            // When selected items
            wp_logo_slider_frame.on('select', function () {
                var attachment = wp_logo_slider_frame.state().get('selection').first().toJSON();
                var $src;

                $(this).parent().parent().find('input:hidden').val(attachment.id);

                console.log(attachment.url);

                if (typeof(attachment.sizes.thumbnail) == 'undefined') {
                    $src = attachment.url;
                }
                else {
                    $src = attachment.sizes.thumbnail.url;
                }

                // I tried to use closest but some how it did not worked :(
                $(this).parent().parent().parent().attr('data-attachment_id', attachment.id)
                $(this).parent().parent().prev().attr('src', $src);
                $(this).parent().parent().prev().attr('srcset', '');
            }.bind(this));


            // When open select selected
            wp_logo_slider_frame.on('open', function () {

                // Grab our attachment selection and construct a JSON representation of the model.
                var selection = wp_logo_slider_frame.state().get('selection');

                var current = $(this).parent().parent().find('input:hidden').val();

                var attachment = wp.media.attachment(current);
                attachment.fetch();
                selection.add(attachment ? [attachment] : []);
            }.bind(this));

            wp_logo_slider_frame.open();


        });

    }());

    (function () {
        $('[data-depends]').formFieldDependency();
    }());

    (function () {
        /* Storage Handling */
        var $supports_html5_storage;
        try {
            $supports_html5_storage = ( 'sessionStorage' in window && window.sessionStorage !== null );
        }
        catch (err) {
            $supports_html5_storage = false;
        }

        var $settings_nav_tab = $('.wp-logo-slider-settings-nav-tab');

        $settings_nav_tab.on('click', function (e) {
            e.preventDefault();

            $settings_nav_tab.removeClass('nav-tab-active');

            $(this).addClass('nav-tab-active');
            var id = $(this).attr('href');

            if ($supports_html5_storage) {
                window.sessionStorage.setItem('wp-logo-slider-active-setting-tab', $(this).index());
            }

            $('.wp-logo-slider-setting').hide();
            $(id).show();

        });


        if ($supports_html5_storage) {

            var $current_state = window.sessionStorage.getItem('wp-logo-slider-active-setting-tab');
            if ($current_state != null) {
                $settings_nav_tab.eq($current_state).trigger('click');
            }
        }
    }());
});