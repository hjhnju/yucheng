/*! 2014 Baidu Inc. All Rights Reserved */
define('zrender/shape/Image', [
    'require',
    './Base',
    '../tool/util'
], function (require) {
    var Base = require('./Base');
    var ZImage = function (options) {
        Base.call(this, options);
    };
    ZImage.prototype = {
        type: 'image',
        brush: function (ctx, isHighlight, refreshNextFrame) {
            var style = this.style || {};
            if (isHighlight) {
                style = this.getHighlightStyle(style, this.highlightStyle || {});
            }
            var image = style.image;
            var self = this;
            if (!this._imageCache) {
                this._imageCache = {};
            }
            if (typeof image === 'string') {
                var src = image;
                if (this._imageCache[src]) {
                    image = this._imageCache[src];
                } else {
                    image = new Image();
                    image.onload = function () {
                        image.onload = null;
                        self.modSelf();
                        refreshNextFrame();
                    };
                    image.src = src;
                    this._imageCache[src] = image;
                }
            }
            if (image) {
                if (image.nodeName.toUpperCase() == 'IMG') {
                    if (window.ActiveXObject) {
                        if (image.readyState != 'complete') {
                            return;
                        }
                    } else {
                        if (!image.complete) {
                            return;
                        }
                    }
                }
                var width = style.width || image.width;
                var height = style.height || image.height;
                var x = style.x;
                var y = style.y;
                if (!image.width || !image.height) {
                    return;
                }
                ctx.save();
                this.doClip(ctx);
                this.setContext(ctx, style);
                this.setTransform(ctx);
                if (style.sWidth && style.sHeight) {
                    var sx = style.sx || 0;
                    var sy = style.sy || 0;
                    ctx.drawImage(image, sx, sy, style.sWidth, style.sHeight, x, y, width, height);
                } else if (style.sx && style.sy) {
                    var sx = style.sx;
                    var sy = style.sy;
                    var sWidth = width - sx;
                    var sHeight = height - sy;
                    ctx.drawImage(image, sx, sy, sWidth, sHeight, x, y, width, height);
                } else {
                    ctx.drawImage(image, x, y, width, height);
                }
                if (!style.width) {
                    style.width = width;
                }
                if (!style.height) {
                    style.height = height;
                }
                if (!this.style.width) {
                    this.style.width = width;
                }
                if (!this.style.height) {
                    this.style.height = height;
                }
                this.drawText(ctx, style, this.style);
                ctx.restore();
            }
        },
        getRect: function (style) {
            return {
                x: style.x,
                y: style.y,
                width: style.width,
                height: style.height
            };
        },
        clearCache: function () {
            this._imageCache = {};
        }
    };
    require('../tool/util').inherits(ZImage, Base);
    return ZImage;
});