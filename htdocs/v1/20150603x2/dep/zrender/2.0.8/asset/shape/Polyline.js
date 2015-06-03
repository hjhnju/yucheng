define('zrender/shape/Polyline', [
    'require',
    './Base',
    './util/smoothSpline',
    './util/smoothBezier',
    './util/dashedLineTo',
    './Polygon',
    '../tool/util'
], function (require) {
    var Base = require('./Base');
    var smoothSpline = require('./util/smoothSpline');
    var smoothBezier = require('./util/smoothBezier');
    var dashedLineTo = require('./util/dashedLineTo');
    var Polyline = function (options) {
        this.brushTypeOnly = 'stroke';
        this.textPosition = 'end';
        Base.call(this, options);
    };
    Polyline.prototype = {
        type: 'polyline',
        buildPath: function (ctx, style) {
            var pointList = style.pointList;
            if (pointList.length < 2) {
                return;
            }
            var len = Math.min(style.pointList.length, Math.round(style.pointListLength || style.pointList.length));
            if (style.smooth && style.smooth !== 'spline') {
                if (!style.controlPointList) {
                    this.updateControlPoints(style);
                }
                var controlPointList = style.controlPointList;
                ctx.moveTo(pointList[0][0], pointList[0][1]);
                var cp1;
                var cp2;
                var p;
                for (var i = 0; i < len - 1; i++) {
                    cp1 = controlPointList[i * 2];
                    cp2 = controlPointList[i * 2 + 1];
                    p = pointList[i + 1];
                    ctx.bezierCurveTo(cp1[0], cp1[1], cp2[0], cp2[1], p[0], p[1]);
                }
            } else {
                if (style.smooth === 'spline') {
                    pointList = smoothSpline(pointList);
                    len = pointList.length;
                }
                if (!style.lineType || style.lineType == 'solid') {
                    ctx.moveTo(pointList[0][0], pointList[0][1]);
                    for (var i = 1; i < len; i++) {
                        ctx.lineTo(pointList[i][0], pointList[i][1]);
                    }
                } else if (style.lineType == 'dashed' || style.lineType == 'dotted') {
                    var dashLength = (style.lineWidth || 1) * (style.lineType == 'dashed' ? 5 : 1);
                    ctx.moveTo(pointList[0][0], pointList[0][1]);
                    for (var i = 1; i < len; i++) {
                        dashedLineTo(ctx, pointList[i - 1][0], pointList[i - 1][1], pointList[i][0], pointList[i][1], dashLength);
                    }
                }
            }
            return;
        },
        updateControlPoints: function (style) {
            style.controlPointList = smoothBezier(style.pointList, style.smooth, false, style.smoothConstraint);
        },
        getRect: function (style) {
            return require('./Polygon').prototype.getRect(style);
        }
    };
    require('../tool/util').inherits(Polyline, Base);
    return Polyline;
});