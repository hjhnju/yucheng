define('echarts/util/shape/Cross', [
    'require',
    'zrender/shape/Base',
    'zrender/shape/Line',
    'zrender/tool/util',
    './normalIsCover'
], function (require) {
    var Base = require('zrender/shape/Base');
    var LineShape = require('zrender/shape/Line');
    var zrUtil = require('zrender/tool/util');
    function Cross(options) {
        Base.call(this, options);
    }
    Cross.prototype = {
        type: 'cross',
        buildPath: function (ctx, style) {
            var rect = style.rect;
            style.xStart = rect.x;
            style.xEnd = rect.x + rect.width;
            style.yStart = style.yEnd = style.y;
            LineShape.prototype.buildPath(ctx, style);
            style.xStart = style.xEnd = style.x;
            style.yStart = rect.y;
            style.yEnd = rect.y + rect.height;
            LineShape.prototype.buildPath(ctx, style);
        },
        getRect: function (style) {
            return style.rect;
        },
        isCover: require('./normalIsCover')
    };
    zrUtil.inherits(Cross, Base);
    return Cross;
});