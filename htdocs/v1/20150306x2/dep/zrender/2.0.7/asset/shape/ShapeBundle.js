define([
    'require',
    './Base',
    '../tool/util'
], function (require) {
    var Base = require('./Base');
    var ShapeBundle = function (options) {
        Base.call(this, options);
    };
    ShapeBundle.prototype = {
        constructor: ShapeBundle,
        type: 'shape-bundle',
        brush: function (ctx, isHighlight) {
            var style = this.beforeBrush(ctx, isHighlight);
            ctx.beginPath();
            for (var i = 0; i < style.shapeList.length; i++) {
                var subShape = style.shapeList[i];
                var subShapeStyle = subShape.style;
                if (isHighlight) {
                    subShapeStyle = subShape.getHighlightStyle(subShapeStyle, subShape.highlightStyle || {}, subShape.brushTypeOnly);
                }
                subShape.buildPath(ctx, subShapeStyle);
            }
            switch (style.brushType) {
            case 'both':
                ctx.fill();
            case 'stroke':
                style.lineWidth > 0 && ctx.stroke();
                break;
            default:
                ctx.fill();
            }
            this.drawText(ctx, style, this.style);
            this.afterBrush(ctx);
        },
        getRect: function (style) {
            if (style.__rect) {
                return style.__rect;
            }
            var minX = Number.MAX_VALUE;
            var maxX = Number.MIN_VALUE;
            var minY = Number.MAX_VALUE;
            var maxY = Number.MIN_VALUE;
            for (var i = 0; i < style.shapeList.length; i++) {
                var subShape = style.shapeList[i];
                var subRect = subShape.getRect(subShape.style);
                var minX = Math.min(subRect.x, minX);
                var minY = Math.min(subRect.y, minY);
                var maxX = Math.max(subRect.x + subRect.width, maxX);
                var maxY = Math.max(subRect.y + subRect.height, maxY);
            }
            style.__rect = {
                x: minX,
                y: minY,
                width: maxX - minX,
                height: maxY - minY
            };
            return style.__rect;
        },
        isCover: function (x, y) {
            var originPos = this.getTansform(x, y);
            x = originPos[0];
            y = originPos[1];
            var rect = this.style.__rect;
            if (!rect) {
                rect = this.getRect(this.style);
            }
            if (x >= rect.x && x <= rect.x + rect.width && y >= rect.y && y <= rect.y + rect.height) {
                for (var i = 0; i < this.style.shapeList.length; i++) {
                    var subShape = this.style.shapeList[i];
                    if (subShape.isCover(x, y)) {
                        return true;
                    }
                }
            }
            return false;
        }
    };
    require('../tool/util').inherits(ShapeBundle, Base);
    return ShapeBundle;
});