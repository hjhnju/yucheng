define([
    'require',
    './Base',
    './util/dashedLineTo',
    '../tool/util'
], function (require) {
    var Base = require('./Base');
    var dashedLineTo = require('./util/dashedLineTo');
    var Line = function (options) {
        this.brushTypeOnly = 'stroke';
        this.textPosition = 'end';
        Base.call(this, options);
    };
    Line.prototype = {
        type: 'line',
        buildPath: function (ctx, style) {
            if (!style.lineType || style.lineType == 'solid') {
                ctx.moveTo(style.xStart, style.yStart);
                ctx.lineTo(style.xEnd, style.yEnd);
            } else if (style.lineType == 'dashed' || style.lineType == 'dotted') {
                var dashLength = (style.lineWidth || 1) * (style.lineType == 'dashed' ? 5 : 1);
                dashedLineTo(ctx, style.xStart, style.yStart, style.xEnd, style.yEnd, dashLength);
            }
        },
        getRect: function (style) {
            if (style.__rect) {
                return style.__rect;
            }
            var lineWidth = style.lineWidth || 1;
            style.__rect = {
                x: Math.min(style.xStart, style.xEnd) - lineWidth,
                y: Math.min(style.yStart, style.yEnd) - lineWidth,
                width: Math.abs(style.xStart - style.xEnd) + lineWidth,
                height: Math.abs(style.yStart - style.yEnd) + lineWidth
            };
            return style.__rect;
        }
    };
    require('../tool/util').inherits(Line, Base);
    return Line;
});