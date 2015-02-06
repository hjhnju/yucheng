define('zrender/shape/Droplet', function (require) {
    'use strict';
    var Base = require('./Base');
    var PathProxy = require('./util/PathProxy');
    var area = require('zrender/tool/area');
    var Droplet = function (options) {
        Base.call(this, options);
        this._pathProxy = new PathProxy();
    };
    Droplet.prototype = {
        type: 'droplet',
        buildPath: function (ctx, style) {
            var path = this._pathProxy || new PathProxy();
            path.begin(ctx);
            path.moveTo(style.x, style.y + style.a);
            path.bezierCurveTo(style.x + style.a, style.y + style.a, style.x + style.a * 3 / 2, style.y - style.a / 3, style.x, style.y - style.b);
            path.bezierCurveTo(style.x - style.a * 3 / 2, style.y - style.a / 3, style.x - style.a, style.y + style.a, style.x, style.y + style.a);
            path.closePath();
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
    require('../tool/util').inherits(Droplet, Base);
    return Droplet;
});