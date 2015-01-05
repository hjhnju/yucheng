define('zrender/shape/Rose', [
    'require',
    './Base',
    '../tool/math',
    '../tool/util'
], function (require) {
    var Base = require('./Base');
    var Rose = function (options) {
        this.brushTypeOnly = 'stroke';
        Base.call(this, options);
    };
    Rose.prototype = {
        type: 'rose',
        buildPath: function (ctx, style) {
            var _x;
            var _y;
            var _R = style.r;
            var _r;
            var _k = style.k;
            var _n = style.n || 1;
            var _offsetX = style.x;
            var _offsetY = style.y;
            var _math = require('../tool/math');
            ctx.moveTo(_offsetX, _offsetY);
            for (var i = 0, _len = _R.length; i < _len; i++) {
                _r = _R[i];
                for (var j = 0; j <= 360 * _n; j++) {
                    _x = _r * _math.sin(_k / _n * j % 360, true) * _math.cos(j, true) + _offsetX;
                    _y = _r * _math.sin(_k / _n * j % 360, true) * _math.sin(j, true) + _offsetY;
                    ctx.lineTo(_x, _y);
                }
            }
        },
        getRect: function (style) {
            if (style.__rect) {
                return style.__rect;
            }
            var _R = style.r;
            var _offsetX = style.x;
            var _offsetY = style.y;
            var _max = 0;
            for (var i = 0, _len = _R.length; i < _len; i++) {
                if (_R[i] > _max) {
                    _max = _R[i];
                }
            }
            style.maxr = _max;
            var lineWidth;
            if (style.brushType == 'stroke' || style.brushType == 'fill') {
                lineWidth = style.lineWidth || 1;
            } else {
                lineWidth = 0;
            }
            style.__rect = {
                x: -_max - lineWidth + _offsetX,
                y: -_max - lineWidth + _offsetY,
                width: 2 * _max + 3 * lineWidth,
                height: 2 * _max + 3 * lineWidth
            };
            return style.__rect;
        }
    };
    require('../tool/util').inherits(Rose, Base);
    return Rose;
});