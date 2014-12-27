/*! 2014 Baidu Inc. All Rights Reserved */
define('zrender/shape/Ellipse', function (require) {
    var Base = require('./Base');
    var Ellipse = function (options) {
        Base.call(this, options);
    };
    Ellipse.prototype = {
        type: 'ellipse',
        buildPath: function (ctx, style) {
            var k = 0.5522848;
            var x = style.x;
            var y = style.y;
            var a = style.a;
            var b = style.b;
            var ox = a * k;
            var oy = b * k;
            ctx.moveTo(x - a, y);
            ctx.bezierCurveTo(x - a, y - oy, x - ox, y - b, x, y - b);
            ctx.bezierCurveTo(x + ox, y - b, x + a, y - oy, x + a, y);
            ctx.bezierCurveTo(x + a, y + oy, x + ox, y + b, x, y + b);
            ctx.bezierCurveTo(x - ox, y + b, x - a, y + oy, x - a, y);
            ctx.closePath();
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
                x: Math.round(style.x - style.a - lineWidth / 2),
                y: Math.round(style.y - style.b - lineWidth / 2),
                width: style.a * 2 + lineWidth,
                height: style.b * 2 + lineWidth
            };
            return style.__rect;
        }
    };
    require('../tool/util').inherits(Ellipse, Base);
    return Ellipse;
});