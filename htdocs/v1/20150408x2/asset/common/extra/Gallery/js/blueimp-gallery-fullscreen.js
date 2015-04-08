(function (factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        define('common/extra/Gallery/js/blueimp-gallery-fullscreen', [
            './blueimp-helper',
            './blueimp-gallery'
        ], factory);
    } else {
        factory(window.blueimp.helper || window.jQuery, window.blueimp.Gallery);
    }
}(function ($, Gallery) {
    'use strict';
    $.extend(Gallery.prototype.options, { fullScreen: false });
    var initialize = Gallery.prototype.initialize, close = Gallery.prototype.close;
    $.extend(Gallery.prototype, {
        getFullScreenElement: function () {
            return document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;
        },
        requestFullScreen: function (element) {
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
        },
        exitFullScreen: function () {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        },
        initialize: function () {
            initialize.call(this);
            if (this.options.fullScreen && !this.getFullScreenElement()) {
                this.requestFullScreen(this.container[0]);
            }
        },
        close: function () {
            if (this.getFullScreenElement() === this.container[0]) {
                this.exitFullScreen();
            }
            close.call(this);
        }
    });
    return Gallery;
}));