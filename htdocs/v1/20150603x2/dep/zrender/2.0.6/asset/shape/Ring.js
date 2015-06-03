define([
    'require',
    './Base',
    '../tool/util'
], function (require) {
    var Base = require('./Base');
    var Ring = function (options) {
        Base.call(this, options);
    };
    Ring.prototype = {
        type: 'ring',
        buildPath: function (ctx, style) {
            ctx.arc(style.x, style.y, style.r, 0, Math.PI * 2, false);
            ctx.moveTo(style.x + style.r0, style.y);
            ctx.arc(style.x, style.y, style.r0, 0, Math.PI * 2, true);
            return;
        },
        getRect: function (style) {
            if (style.__rect) {
                return style.__rect;
            }
            var lineWidth;
            if (style.brushType == 'stroke' || style.brushType == 'fill') {
                lineWidth = style.lineWidth || 1;
            } else {
                lineWidth = 0;
            }
            style.__rect = {
                x: Math.round(style.x - style.r - lineWidth / 2),
                y: Math.round(style.y - style.r - lineWidth / 2),
                width: style.r * 2 + lineWidth,
                height: style.r * 2 + lineWidth
            };
            return style.__rect;
        }
    };
    require('../tool/util').inherits(Ring, Base);
    return Ring;
});