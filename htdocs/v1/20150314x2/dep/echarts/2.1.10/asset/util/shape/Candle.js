define([
    'require',
    'zrender/shape/Base',
    'zrender/tool/util',
    './normalIsCover'
], function (require) {
    var Base = require('zrender/shape/Base');
    var zrUtil = require('zrender/tool/util');
    function Candle(options) {
        Base.call(this, options);
    }
    Candle.prototype = {
        type: 'candle',
        _numberOrder: function (a, b) {
            return b - a;
        },
        buildPath: function (ctx, style) {
            var yList = zrUtil.clone(style.y).sort(this._numberOrder);
            ctx.moveTo(style.x, yList[3]);
            ctx.lineTo(style.x, yList[2]);
            ctx.moveTo(style.x - style.width / 2, yList[2]);
            ctx.rect(style.x - style.width / 2, yList[2], style.width, yList[1] - yList[2]);
            ctx.moveTo(style.x, yList[1]);
            ctx.lineTo(style.x, yList[0]);
        },
        getRect: function (style) {
            if (!style.__rect) {
                var lineWidth = 0;
                if (style.brushType == 'stroke' || style.brushType == 'fill') {
                    lineWidth = style.lineWidth || 1;
                }
                var yList = zrUtil.clone(style.y).sort(this._numberOrder);
                style.__rect = {
                    x: Math.round(style.x - style.width / 2 - lineWidth / 2),
                    y: Math.round(yList[3] - lineWidth / 2),
                    width: style.width + lineWidth,
                    height: yList[0] - yList[3] + lineWidth
                };
            }
            return style.__rect;
        },
        isCover: require('./normalIsCover')
    };
    zrUtil.inherits(Candle, Base);
    return Candle;
});