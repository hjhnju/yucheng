/*! 2015 Baidu Inc. All Rights Reserved */
define('zrender/shape/Text', function (require) {
    var area = require('../tool/area');
    var Base = require('./Base');
    var Text = function (options) {
        Base.call(this, options);
    };
    Text.prototype = {
        type: 'text',
        brush: function (ctx, isHighlight) {
            var style = this.style;
            if (isHighlight) {
                style = this.getHighlightStyle(style, this.highlightStyle || {});
            }
            if (typeof style.text == 'undefined' || style.text === false) {
                return;
            }
            ctx.save();
            this.doClip(ctx);
            this.setContext(ctx, style);
            this.setTransform(ctx);
            if (style.textFont) {
                ctx.font = style.textFont;
            }
            ctx.textAlign = style.textAlign || 'start';
            ctx.textBaseline = style.textBaseline || 'middle';
            var text = (style.text + '').split('\n');
            var lineHeight = area.getTextHeight('\u56FD', style.textFont);
            var rect = this.getRect(style);
            var x = style.x;
            var y;
            if (style.textBaseline == 'top') {
                y = rect.y;
            } else if (style.textBaseline == 'bottom') {
                y = rect.y + lineHeight;
            } else {
                y = rect.y + lineHeight / 2;
            }
            for (var i = 0, l = text.length; i < l; i++) {
                if (style.maxWidth) {
                    switch (style.brushType) {
                    case 'fill':
                        ctx.fillText(text[i], x, y, style.maxWidth);
                        break;
                    case 'stroke':
                        ctx.strokeText(text[i], x, y, style.maxWidth);
                        break;
                    case 'both':
                        ctx.fillText(text[i], x, y, style.maxWidth);
                        ctx.strokeText(text[i], x, y, style.maxWidth);
                        break;
                    default:
                        ctx.fillText(text[i], x, y, style.maxWidth);
                    }
                } else {
                    switch (style.brushType) {
                    case 'fill':
                        ctx.fillText(text[i], x, y);
                        break;
                    case 'stroke':
                        ctx.strokeText(text[i], x, y);
                        break;
                    case 'both':
                        ctx.fillText(text[i], x, y);
                        ctx.strokeText(text[i], x, y);
                        break;
                    default:
                        ctx.fillText(text[i], x, y);
                    }
                }
                y += lineHeight;
            }
            ctx.restore();
            return;
        },
        getRect: function (style) {
            if (style.__rect) {
                return style.__rect;
            }
            var width = area.getTextWidth(style.text, style.textFont);
            var height = area.getTextHeight(style.text, style.textFont);
            var textX = style.x;
            if (style.textAlign == 'end' || style.textAlign == 'right') {
                textX -= width;
            } else if (style.textAlign == 'center') {
                textX -= width / 2;
            }
            var textY;
            if (style.textBaseline == 'top') {
                textY = style.y;
            } else if (style.textBaseline == 'bottom') {
                textY = style.y - height;
            } else {
                textY = style.y - height / 2;
            }
            style.__rect = {
                x: textX,
                y: textY,
                width: width,
                height: height
            };
            return style.__rect;
        }
    };
    require('../tool/util').inherits(Text, Base);
    return Text;
});