/*! 2014 Baidu Inc. All Rights Reserved */
define('zrender/shape/Isogon', function (require) {
    var math = require('../tool/math');
    var sin = math.sin;
    var cos = math.cos;
    var PI = Math.PI;
    var Base = require('./Base');
    function Isogon(options) {
        Base.call(this, options);
    }
    Isogon.prototype = {
        type: 'isogon',
        buildPath: function (ctx, style) {
            var n = style.n;
            if (!n || n < 2) {
                return;
            }
            var x = style.x;
            var y = style.y;
            var r = style.r;
            var dStep = 2 * PI / n;
            var deg = -PI / 2;
            var xStart = x + r * cos(deg);
            var yStart = y + r * sin(deg);
            deg += dStep;
            var pointList = style.pointList = [];
            pointList.push([
                xStart,
                yStart
            ]);
            for (var i = 0, end = n - 1; i < end; i++) {
                pointList.push([
                    x + r * cos(deg),
                    y + r * sin(deg)
                ]);
                deg += dStep;
            }
            pointList.push([
                xStart,
                yStart
            ]);
            ctx.moveTo(pointList[0][0], pointList[0][1]);
            for (var i = 0; i < pointList.length; i++) {
                ctx.lineTo(pointList[i][0], pointList[i][1]);
            }
            ctx.closePath();
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
    require('../tool/util').inherits(Isogon, Base);
    return Isogon;
});