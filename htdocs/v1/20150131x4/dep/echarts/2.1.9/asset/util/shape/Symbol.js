define('echarts/util/shape/Symbol', [
    'require',
    'zrender/shape/Base',
    'zrender/shape/Polygon',
    'zrender/tool/util',
    './normalIsCover'
], function (require) {
    var Base = require('zrender/shape/Base');
    var PolygonShape = require('zrender/shape/Polygon');
    var polygonInstance = new PolygonShape({});
    var zrUtil = require('zrender/tool/util');
    function Symbol(options) {
        Base.call(this, options);
    }
    Symbol.prototype = {
        type: 'symbol',
        buildPath: function (ctx, style) {
            var pointList = style.pointList;
            var len = pointList.length;
            if (len === 0) {
                return;
            }
            var subSize = 10000;
            var subSetLength = Math.ceil(len / subSize);
            var sub;
            var subLen;
            var isArray = pointList[0] instanceof Array;
            var size = style.size ? style.size : 2;
            var curSize = size;
            var halfSize = size / 2;
            var PI2 = Math.PI * 2;
            var percent;
            var x;
            var y;
            for (var j = 0; j < subSetLength; j++) {
                ctx.beginPath();
                sub = j * subSize;
                subLen = sub + subSize;
                subLen = subLen > len ? len : subLen;
                for (var i = sub; i < subLen; i++) {
                    if (style.random) {
                        percent = style['randomMap' + i % 20] / 100;
                        curSize = size * percent * percent;
                        halfSize = curSize / 2;
                    }
                    if (isArray) {
                        x = pointList[i][0];
                        y = pointList[i][1];
                    } else {
                        x = pointList[i].x;
                        y = pointList[i].y;
                    }
                    if (curSize < 3) {
                        ctx.rect(x - halfSize, y - halfSize, curSize, curSize);
                    } else {
                        switch (style.iconType) {
                        case 'circle':
                            ctx.moveTo(x, y);
                            ctx.arc(x, y, halfSize, 0, PI2, true);
                            break;
                        case 'diamond':
                            ctx.moveTo(x, y - halfSize);
                            ctx.lineTo(x + halfSize / 3, y - halfSize / 3);
                            ctx.lineTo(x + halfSize, y);
                            ctx.lineTo(x + halfSize / 3, y + halfSize / 3);
                            ctx.lineTo(x, y + halfSize);
                            ctx.lineTo(x - halfSize / 3, y + halfSize / 3);
                            ctx.lineTo(x - halfSize, y);
                            ctx.lineTo(x - halfSize / 3, y - halfSize / 3);
                            ctx.lineTo(x, y - halfSize);
                            break;
                        default:
                            ctx.rect(x - halfSize, y - halfSize, curSize, curSize);
                        }
                    }
                }
                ctx.closePath();
                if (j < subSetLength - 1) {
                    switch (style.brushType) {
                    case 'both':
                        ctx.fill();
                        style.lineWidth > 0 && ctx.stroke();
                        break;
                    case 'stroke':
                        style.lineWidth > 0 && ctx.stroke();
                        break;
                    default:
                        ctx.fill();
                    }
                }
            }
        },
        getRect: function (style) {
            return style.__rect || polygonInstance.getRect(style);
        },
        isCover: require('./normalIsCover')
    };
    zrUtil.inherits(Symbol, Base);
    return Symbol;
});