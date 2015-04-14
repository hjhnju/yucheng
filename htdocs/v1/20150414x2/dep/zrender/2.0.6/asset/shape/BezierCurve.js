define([
    'require',
    './Base',
    '../tool/util'
], function (require) {
    'use strict';
    var Base = require('./Base');
    var BezierCurve = function (options) {
        this.brushTypeOnly = 'stroke';
        this.textPosition = 'end';
        Base.call(this, options);
    };
    BezierCurve.prototype = {
        type: 'bezier-curve',
        buildPath: function (ctx, style) {
            ctx.moveTo(style.xStart, style.yStart);
            if (typeof style.cpX2 != 'undefined' && typeof style.cpY2 != 'undefined') {
                ctx.bezierCurveTo(style.cpX1, style.cpY1, style.cpX2, style.cpY2, style.xEnd, style.yEnd);
            } else {
                ctx.quadraticCurveTo(style.cpX1, style.cpY1, style.xEnd, style.yEnd);
            }
        },
        getRect: function (style) {
            if (style.__rect) {
                return style.__rect;
            }
            var _minX = Math.min(style.xStart, style.xEnd, style.cpX1);
            var _minY = Math.min(style.yStart, style.yEnd, style.cpY1);
            var _maxX = Math.max(style.xStart, style.xEnd, style.cpX1);
            var _maxY = Math.max(style.yStart, style.yEnd, style.cpY1);
            var _x2 = style.cpX2;
            var _y2 = style.cpY2;
            if (typeof _x2 != 'undefined' && typeof _y2 != 'undefined') {
                _minX = Math.min(_minX, _x2);
                _minY = Math.min(_minY, _y2);
                _maxX = Math.max(_maxX, _x2);
                _maxY = Math.max(_maxY, _y2);
            }
            var lineWidth = style.lineWidth || 1;
            style.__rect = {
                x: _minX - lineWidth,
                y: _minY - lineWidth,
                width: _maxX - _minX + lineWidth,
                height: _maxY - _minY + lineWidth
            };
            return style.__rect;
        }
    };
    require('../tool/util').inherits(BezierCurve, Base);
    return BezierCurve;
});