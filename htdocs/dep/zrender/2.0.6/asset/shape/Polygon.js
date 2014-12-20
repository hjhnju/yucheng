/*! 2014 Baidu Inc. All Rights Reserved */
define('zrender/shape/Polygon', function (require) {
    var Base = require('./Base');
    var smoothSpline = require('./util/smoothSpline');
    var smoothBezier = require('./util/smoothBezier');
    var dashedLineTo = require('./util/dashedLineTo');
    var Polygon = function (options) {
        Base.call(this, options);
    };
    Polygon.prototype = {
        type: 'polygon',
        brush: function (ctx, isHighlight) {
            var style = this.style;
            if (isHighlight) {
                style = this.getHighlightStyle(style, this.highlightStyle || {});
            }
            ctx.save();
            this.setContext(ctx, style);
            this.setTransform(ctx);
            var hasPath = false;
            if (style.brushType == 'fill' || style.brushType == 'both' || typeof style.brushType == 'undefined') {
                ctx.beginPath();
                if (style.lineType == 'dashed' || style.lineType == 'dotted') {
                    this.buildPath(ctx, {
                        lineType: 'solid',
                        lineWidth: style.lineWidth,
                        pointList: style.pointList
                    });
                    hasPath = false;
                } else {
                    this.buildPath(ctx, style);
                    hasPath = true;
                }
                ctx.closePath();
                ctx.fill();
            }
            if (style.lineWidth > 0 && (style.brushType == 'stroke' || style.brushType == 'both')) {
                if (!hasPath) {
                    ctx.beginPath();
                    this.buildPath(ctx, style);
                }
                ctx.stroke();
            }
            this.drawText(ctx, style, this.style);
            ctx.restore();
            return;
        },
        buildPath: function (ctx, style) {
            var pointList = style.pointList;
            if (pointList.length < 2) {
                return;
            }
            if (style.smooth && style.smooth !== 'spline') {
                var controlPoints = smoothBezier(pointList, style.smooth, true, style.smoothConstraint);
                ctx.moveTo(pointList[0][0], pointList[0][1]);
                var cp1;
                var cp2;
                var p;
                var len = pointList.length;
                for (var i = 0; i < len; i++) {
                    cp1 = controlPoints[i * 2];
                    cp2 = controlPoints[i * 2 + 1];
                    p = pointList[(i + 1) % len];
                    ctx.bezierCurveTo(cp1[0], cp1[1], cp2[0], cp2[1], p[0], p[1]);
                }
            } else {
                if (style.smooth === 'spline') {
                    pointList = smoothSpline(pointList, true);
                }
                if (!style.lineType || style.lineType == 'solid') {
                    ctx.moveTo(pointList[0][0], pointList[0][1]);
                    for (var i = 1, l = pointList.length; i < l; i++) {
                        ctx.lineTo(pointList[i][0], pointList[i][1]);
                    }
                    ctx.lineTo(pointList[0][0], pointList[0][1]);
                } else if (style.lineType == 'dashed' || style.lineType == 'dotted') {
                    var dashLength = style._dashLength || (style.lineWidth || 1) * (style.lineType == 'dashed' ? 5 : 1);
                    style._dashLength = dashLength;
                    ctx.moveTo(pointList[0][0], pointList[0][1]);
                    for (var i = 1, l = pointList.length; i < l; i++) {
                        dashedLineTo(ctx, pointList[i - 1][0], pointList[i - 1][1], pointList[i][0], pointList[i][1], dashLength);
                    }
                    dashedLineTo(ctx, pointList[pointList.length - 1][0], pointList[pointList.length - 1][1], pointList[0][0], pointList[0][1], dashLength);
                }
            }
            return;
        },
        getRect: function (style) {
            if (style.__rect) {
                return style.__rect;
            }
            var minX = Number.MAX_VALUE;
            var maxX = Number.MIN_VALUE;
            var minY = Number.MAX_VALUE;
            var maxY = Number.MIN_VALUE;
            var pointList = style.pointList;
            for (var i = 0, l = pointList.length; i < l; i++) {
                if (pointList[i][0] < minX) {
                    minX = pointList[i][0];
                }
                if (pointList[i][0] > maxX) {
                    maxX = pointList[i][0];
                }
                if (pointList[i][1] < minY) {
                    minY = pointList[i][1];
                }
                if (pointList[i][1] > maxY) {
                    maxY = pointList[i][1];
                }
            }
            var lineWidth;
            if (style.brushType == 'stroke' || style.brushType == 'fill') {
                lineWidth = style.lineWidth || 1;
            } else {
                lineWidth = 0;
            }
            style.__rect = {
                x: Math.round(minX - lineWidth / 2),
                y: Math.round(minY - lineWidth / 2),
                width: maxX - minX + lineWidth,
                height: maxY - minY + lineWidth
            };
            return style.__rect;
        }
    };
    require('../tool/util').inherits(Polygon, Base);
    return Polygon;
});