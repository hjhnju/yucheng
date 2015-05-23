(function (factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        define('common/extra/Gallery/js/jquery.blueimp-gallery', [
            'jquery',
            './blueimp-gallery'
        ], factory);
    } else {
        factory(window.jQuery, window.blueimp.Gallery);
    }
}(function ($, Gallery) {
    'use strict';
    $(document).on('click', '[data-gallery]', function (event) {
        var id = $(this).data('gallery'), widget = $(id), container = widget.length && widget || $(Gallery.prototype.options.container), callbacks = {
                onopen: function () {
                    container.data('gallery', this).trigger('open');
                },
                onopened: function () {
                    container.trigger('opened');
                },
                onslide: function () {
                    container.trigger('slide', arguments);
                },
                onslideend: function () {
                    container.trigger('slideend', arguments);
                },
                onslidecomplete: function () {
                    container.trigger('slidecomplete', arguments);
                },
                onclose: function () {
                    container.trigger('close');
                },
                onclosed: function () {
                    container.trigger('closed').removeData('gallery');
                }
            }, options = $.extend(container.data(), {
                container: container[0],
                index: this,
                event: event
            }, callbacks), links = $('[data-gallery="' + id + '"]');
        if (options.filter) {
            links = links.filter(options.filter);
        }
        return new Gallery(links, options);
    });
}));