/*! 2015 Baidu Inc. All Rights Reserved */
define('echarts/util/shape/Ribbon', function (require) {
    var Base = require('zrender/shape/Base');
    var PathProxy = require('zrender/shape/util/PathProxy');
    var zrUtil = require('zrender/tool/util');
    var area = require('zrender/tool/area');
    function RibbonShape(options) {
        Base.call(this, options);
        this._pathProxy = new PathProxy();
    }
    RibbonShape.prototype = {
        type: 'ribbon',
        buildPath: function (ctx, style) {
            var clockWise = style.clockWise || false;
            var path = this._pathProxy;
            path.begin(ctx);
            var cx = style.x;
            var cy = style.y;
            var r = style.r;
            var s0 = style.source0 / 180 * Math.PI;
            var s1 = style.source1 / 180 * Math.PI;
            var t0 = style.target0 / 180 * Math.PI;
            var t1 = style.target1 / 180 * Math.PI;
            var sx0 = cx + Math.cos(s0) * r;
            var sy0 = cy + Math.sin(s0) * r;
            var sx1 = cx + Math.cos(s1) * r;
            var sy1 = cy + Math.sin(s1) * r;
            var tx0 = cx + Math.cos(t0) * r;
            var ty0 = cy + Math.sin(t0) * r;
            var tx1 = cx + Math.cos(t1) * r;
            var ty1 = cy + Math.sin(t1) * r;
            path.moveTo(sx0, sy0);
            path.arc(cx, cy, style.r, s0, s1, !clockWise);
            path.bezierCurveTo((cx - sx1) * 0.7 + sx1, (cy - sy1) * 0.7 + sy1, (cx - tx0) * 0.7 + tx0, (cy - ty0) * 0.7 + ty0, tx0, ty0);
            if (style.source0 === style.target0 && style.source1 === style.target1) {
                return;
            }
            path.arc(cx, cy, style.r, t0, t1, !clockWise);
            path.bezierCurveTo((cx - tx1) * 0.7 + tx1, (cy - ty1) * 0.7 + ty1, (cx - sx0) * 0.7 + sx0, (cy - sy0) * 0.7 + sy0, sx0, sy0);
        },
        getRect: function (style) {
            if (style.__rect) {
                return style.__rect;
            }
            if (!this._pathProxy.isEmpty()) {
                this.buildPath(null, style);
            }
            return this._pathProxy.fastBoundingRect();
        },
        isCover: function (x, y) {
            var rect = this.getRect(this.style);
            if (x >= rect.x && x <= rect.x + rect.width && y >= rect.y && y <= rect.y + rect.height) {
                return area.isInsidePath(this._pathProxy.pathCommands, 0, 'fill', x, y);
            }
        }
    };
    zrUtil.inherits(RibbonShape, Base);
    return RibbonShape;
});