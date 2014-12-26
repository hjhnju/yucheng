/*! 2014 Baidu Inc. All Rights Reserved */
define('zrender/shape/Sector', [
    'require',
    '../tool/math',
    '../tool/computeBoundingBox',
    '../tool/vector',
    './Base',
    '../tool/util'
], function (require) {
    var math = require('../tool/math');
    var computeBoundingBox = require('../tool/computeBoundingBox');
    var vec2 = require('../tool/vector');
    var Base = require('./Base');
    var min0 = vec2.create();
    var min1 = vec2.create();
    var max0 = vec2.create();
    var max1 = vec2.create();
    var Sector = function (options) {
        Base.call(this, options);
    };
    Sector.prototype = {
        type: 'sector',
        buildPath: function (ctx, style) {
            var x = style.x;
            var y = style.y;
            var r0 = style.r0 || 0;
            var r = style.r;
            var startAngle = style.startAngle;
            var endAngle = style.endAngle;
            var clockWise = style.clockWise || false;
            startAngle = math.degreeToRadian(startAngle);
            endAngle = math.degreeToRadian(endAngle);
            if (!clockWise) {
                startAngle = -startAngle;
                endAngle = -endAngle;
            }
            var unitX = math.cos(startAngle);
            var unitY = math.sin(startAngle);
            ctx.moveTo(unitX * r0 + x, unitY * r0 + y);
            ctx.lineTo(unitX * r + x, unitY * r + y);
            ctx.arc(x, y, r, startAngle, endAngle, !clockWise);
            ctx.lineTo(math.cos(endAngle) * r0 + x, math.sin(endAngle) * r0 + y);
            if (r0 !== 0) {
                ctx.arc(x, y, r0, endAngle, startAngle, clockWise);
            }
            ctx.closePath();
            return;
        },
        getRect: function (style) {
            if (style.__rect) {
                return style.__rect;
            }
            var x = style.x;
            var y = style.y;
            var r0 = style.r0 || 0;
            var r = style.r;
            var startAngle = math.degreeToRadian(style.startAngle);
            var endAngle = math.degreeToRadian(style.endAngle);
            var clockWise = style.clockWise;
            if (!clockWise) {
                startAngle = -startAngle;
                endAngle = -endAngle;
            }
            if (r0 > 1) {
                computeBoundingBox.arc(x, y, r0, startAngle, endAngle, !clockWise, min0, max0);
            } else {
                min0[0] = max0[0] = x;
                min0[1] = max0[1] = y;
            }
            computeBoundingBox.arc(x, y, r, startAngle, endAngle, !clockWise, min1, max1);
            vec2.min(min0, min0, min1);
            vec2.max(max0, max0, max1);
            style.__rect = {
                x: min0[0],
                y: min0[1],
                width: max0[0] - min0[0],
                height: max0[1] - min0[1]
            };
            return style.__rect;
        }
    };
    require('../tool/util').inherits(Sector, Base);
    return Sector;
});