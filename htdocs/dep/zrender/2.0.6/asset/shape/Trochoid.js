define('zrender/shape/Trochoid', function (require) {
    var Base = require('./Base');
    var Trochoid = function (options) {
        this.brushTypeOnly = 'stroke';
        Base.call(this, options);
    };
    Trochoid.prototype = {
        type: 'trochoid',
        buildPath: function (ctx, style) {
            var _x1;
            var _y1;
            var _x2;
            var _y2;
            var _R = style.r;
            var _r = style.r0;
            var _d = style.d;
            var _offsetX = style.x;
            var _offsetY = style.y;
            var _delta = style.location == 'out' ? 1 : -1;
            var _math = require('../tool/math');
            if (style.location && _R <= _r) {
                alert('\u53C2\u6570\u9519\u8BEF');
                return;
            }
            var _num = 0;
            var i = 1;
            var _theta;
            _x1 = (_R + _delta * _r) * _math.cos(0) - _delta * _d * _math.cos(0) + _offsetX;
            _y1 = (_R + _delta * _r) * _math.sin(0) - _d * _math.sin(0) + _offsetY;
            ctx.moveTo(_x1, _y1);
            do {
                _num++;
            } while (_r * _num % (_R + _delta * _r) !== 0);
            do {
                _theta = Math.PI / 180 * i;
                _x2 = (_R + _delta * _r) * _math.cos(_theta) - _delta * _d * _math.cos((_R / _r + _delta) * _theta) + _offsetX;
                _y2 = (_R + _delta * _r) * _math.sin(_theta) - _d * _math.sin((_R / _r + _delta) * _theta) + _offsetY;
                ctx.lineTo(_x2, _y2);
                i++;
            } while (i <= _r * _num / (_R + _delta * _r) * 360);
        },
        getRect: function (style) {
            if (style.__rect) {
                return style.__rect;
            }
            var _R = style.r;
            var _r = style.r0;
            var _d = style.d;
            var _delta = style.location == 'out' ? 1 : -1;
            var _s = _R + _d + _delta * _r;
            var _offsetX = style.x;
            var _offsetY = style.y;
            var lineWidth;
            if (style.brushType == 'stroke' || style.brushType == 'fill') {
                lineWidth = style.lineWidth || 1;
            } else {
                lineWidth = 0;
            }
            style.__rect = {
                x: -_s - lineWidth + _offsetX,
                y: -_s - lineWidth + _offsetY,
                width: 2 * _s + 2 * lineWidth,
                height: 2 * _s + 2 * lineWidth
            };
            return style.__rect;
        }
    };
    require('../tool/util').inherits(Trochoid, Base);
    return Trochoid;
});