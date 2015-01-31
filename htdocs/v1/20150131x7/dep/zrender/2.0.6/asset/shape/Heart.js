define('zrender/shape/Heart', [
    'require',
    './Base',
    './util/PathProxy',
    'zrender/tool/area',
    '../tool/util'
], function (require) {
    'use strict';
    var Base = require('./Base');
    var PathProxy = require('./util/PathProxy');
    var area = require('zrender/tool/area');
    var Heart = function (options) {
        Base.call(this, options);
        this._pathProxy = new PathProxy();
    };
    Heart.prototype = {
        type: 'heart',
        buildPath: function (ctx, style) {
            var path = this._pathProxy || new PathProxy();
            path.begin(ctx);
            path.moveTo(style.x, style.y);
            path.bezierCurveTo(style.x + style.a / 2, style.y - style.b * 2 / 3, style.x + style.a * 2, style.y + style.b / 3, style.x, style.y + style.b);
            path.bezierCurveTo(style.x - style.a * 2, style.y + style.b / 3, style.x - style.a / 2, style.y - style.b * 2 / 3, style.x, style.y);
            path.closePath();
            return;
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
            var originPos = this.getTansform(x, y);
            x = originPos[0];
            y = originPos[1];
            var rect = this.getRect(this.style);
            if (x >= rect.x && x <= rect.x + rect.width && y >= rect.y && y <= rect.y + rect.height) {
                return area.isInsidePath(this._pathProxy.pathCommands, this.style.lineWidth, this.style.brushType, x, y);
            }
        }
    };
    require('../tool/util').inherits(Heart, Base);
    return Heart;
});