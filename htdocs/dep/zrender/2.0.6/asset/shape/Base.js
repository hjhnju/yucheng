/*! 2014 Baidu Inc. All Rights Reserved */
define('zrender/shape/Base', function (require) {
    var vmlCanvasManager = window['G_vmlCanvasManager'];
    var matrix = require('../tool/matrix');
    var guid = require('../tool/guid');
    var util = require('../tool/util');
    var log = require('../tool/log');
    var Transformable = require('../mixin/Transformable');
    var Eventful = require('../mixin/Eventful');
    function _fillText(ctx, text, x, y, textFont, textAlign, textBaseline) {
        if (textFont) {
            ctx.font = textFont;
        }
        ctx.textAlign = textAlign;
        ctx.textBaseline = textBaseline;
        var rect = _getTextRect(text, x, y, textFont, textAlign, textBaseline);
        text = (text + '').split('\n');
        var lineHeight = require('../tool/area').getTextHeight('\u56FD', textFont);
        switch (textBaseline) {
        case 'top':
            y = rect.y;
            break;
        case 'bottom':
            y = rect.y + lineHeight;
            break;
        default:
            y = rect.y + lineHeight / 2;
        }
        for (var i = 0, l = text.length; i < l; i++) {
            ctx.fillText(text[i], x, y);
            y += lineHeight;
        }
    }
    function _getTextRect(text, x, y, textFont, textAlign, textBaseline) {
        var area = require('../tool/area');
        var width = area.getTextWidth(text, textFont);
        var lineHeight = area.getTextHeight('\u56FD', textFont);
        text = (text + '').split('\n');
        switch (textAlign) {
        case 'end':
        case 'right':
            x -= width;
            break;
        case 'center':
            x -= width / 2;
            break;
        }
        switch (textBaseline) {
        case 'top':
            break;
        case 'bottom':
            y -= lineHeight * text.length;
            break;
        default:
            y -= lineHeight * text.length / 2;
        }
        return {
            x: x,
            y: y,
            width: width,
            height: lineHeight * text.length
        };
    }
    var Base = function (options) {
        options = options || {};
        this.id = options.id || guid();
        for (var key in options) {
            this[key] = options[key];
        }
        this.style = this.style || {};
        this.highlightStyle = this.highlightStyle || null;
        this.parent = null;
        this.__dirty = true;
        this.__clipShapes = [];
        Transformable.call(this);
        Eventful.call(this);
    };
    Base.prototype.invisible = false;
    Base.prototype.ignore = false;
    Base.prototype.zlevel = 0;
    Base.prototype.draggable = false;
    Base.prototype.clickable = false;
    Base.prototype.hoverable = true;
    Base.prototype.z = 0;
    Base.prototype.brush = function (ctx, isHighlight) {
        var style = this.beforeBrush(ctx, isHighlight);
        ctx.beginPath();
        this.buildPath(ctx, style);
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
    };
    Base.prototype.beforeBrush = function (ctx, isHighlight) {
        var style = this.style;
        if (this.brushTypeOnly) {
            style.brushType = this.brushTypeOnly;
        }
        if (isHighlight) {
            style = this.getHighlightStyle(style, this.highlightStyle || {}, this.brushTypeOnly);
        }
        if (this.brushTypeOnly == 'stroke') {
            style.strokeColor = style.strokeColor || style.color;
        }
        ctx.save();
        this.doClip(ctx);
        this.setContext(ctx, style);
        this.setTransform(ctx);
        return style;
    };
    Base.prototype.afterBrush = function (ctx) {
        ctx.restore();
    };
    var STYLE_CTX_MAP = [
            [
                'color',
                'fillStyle'
            ],
            [
                'strokeColor',
                'strokeStyle'
            ],
            [
                'opacity',
                'globalAlpha'
            ],
            [
                'lineCap',
                'lineCap'
            ],
            [
                'lineJoin',
                'lineJoin'
            ],
            [
                'miterLimit',
                'miterLimit'
            ],
            [
                'lineWidth',
                'lineWidth'
            ],
            [
                'shadowBlur',
                'shadowBlur'
            ],
            [
                'shadowColor',
                'shadowColor'
            ],
            [
                'shadowOffsetX',
                'shadowOffsetX'
            ],
            [
                'shadowOffsetY',
                'shadowOffsetY'
            ]
        ];
    Base.prototype.setContext = function (ctx, style) {
        for (var i = 0, len = STYLE_CTX_MAP.length; i < len; i++) {
            var styleProp = STYLE_CTX_MAP[i][0];
            var styleValue = style[styleProp];
            var ctxProp = STYLE_CTX_MAP[i][1];
            if (typeof styleValue != 'undefined') {
                ctx[ctxProp] = styleValue;
            }
        }
    };
    var clipShapeInvTransform = matrix.create();
    Base.prototype.doClip = function (ctx) {
        if (this.__clipShapes && !vmlCanvasManager) {
            for (var i = 0; i < this.__clipShapes.length; i++) {
                var clipShape = this.__clipShapes[i];
                if (clipShape.needTransform) {
                    var m = clipShape.transform;
                    matrix.invert(clipShapeInvTransform, m);
                    ctx.transform(m[0], m[1], m[2], m[3], m[4], m[5]);
                }
                ctx.beginPath();
                clipShape.buildPath(ctx, clipShape.style);
                ctx.clip();
                if (clipShape.needTransform) {
                    var m = clipShapeInvTransform;
                    ctx.transform(m[0], m[1], m[2], m[3], m[4], m[5]);
                }
            }
        }
    };
    Base.prototype.getHighlightStyle = function (style, highlightStyle, brushTypeOnly) {
        var newStyle = {};
        for (var k in style) {
            newStyle[k] = style[k];
        }
        var color = require('../tool/color');
        var highlightColor = color.getHighlightColor();
        if (style.brushType != 'stroke') {
            newStyle.strokeColor = highlightColor;
            newStyle.lineWidth = (style.lineWidth || 1) + this.getHighlightZoom();
            newStyle.brushType = 'both';
        } else {
            if (brushTypeOnly != 'stroke') {
                newStyle.strokeColor = highlightColor;
                newStyle.lineWidth = (style.lineWidth || 1) + this.getHighlightZoom();
            } else {
                newStyle.strokeColor = highlightStyle.strokeColor || color.mix(style.strokeColor, color.toRGB(highlightColor));
            }
        }
        for (var k in highlightStyle) {
            if (typeof highlightStyle[k] != 'undefined') {
                newStyle[k] = highlightStyle[k];
            }
        }
        return newStyle;
    };
    Base.prototype.getHighlightZoom = function () {
        return this.type != 'text' ? 6 : 2;
    };
    Base.prototype.drift = function (dx, dy) {
        this.position[0] += dx;
        this.position[1] += dy;
    };
    Base.prototype.getTansform = function () {
        var invTransform = [];
        return function (x, y) {
            var originPos = [
                    x,
                    y
                ];
            if (this.needTransform && this.transform) {
                matrix.invert(invTransform, this.transform);
                matrix.mulVector(originPos, invTransform, [
                    x,
                    y,
                    1
                ]);
                if (x == originPos[0] && y == originPos[1]) {
                    this.updateNeedTransform();
                }
            }
            return originPos;
        };
    }();
    Base.prototype.buildPath = function (ctx, style) {
        log('buildPath not implemented in ' + this.type);
    };
    Base.prototype.getRect = function (style) {
        log('getRect not implemented in ' + this.type);
    };
    Base.prototype.isCover = function (x, y) {
        var originPos = this.getTansform(x, y);
        x = originPos[0];
        y = originPos[1];
        var rect = this.style.__rect;
        if (!rect) {
            rect = this.style.__rect = this.getRect(this.style);
        }
        if (x >= rect.x && x <= rect.x + rect.width && y >= rect.y && y <= rect.y + rect.height) {
            return require('../tool/area').isInside(this, this.style, x, y);
        }
        return false;
    };
    Base.prototype.drawText = function (ctx, style, normalStyle) {
        if (typeof style.text == 'undefined' || style.text === false) {
            return;
        }
        var textColor = style.textColor || style.color || style.strokeColor;
        ctx.fillStyle = textColor;
        var dd = 10;
        var al;
        var bl;
        var tx;
        var ty;
        var textPosition = style.textPosition || this.textPosition || 'top';
        switch (textPosition) {
        case 'inside':
        case 'top':
        case 'bottom':
        case 'left':
        case 'right':
            if (this.getRect) {
                var rect = (normalStyle || style).__rect || this.getRect(normalStyle || style);
                switch (textPosition) {
                case 'inside':
                    tx = rect.x + rect.width / 2;
                    ty = rect.y + rect.height / 2;
                    al = 'center';
                    bl = 'middle';
                    if (style.brushType != 'stroke' && textColor == style.color) {
                        ctx.fillStyle = '#fff';
                    }
                    break;
                case 'left':
                    tx = rect.x - dd;
                    ty = rect.y + rect.height / 2;
                    al = 'end';
                    bl = 'middle';
                    break;
                case 'right':
                    tx = rect.x + rect.width + dd;
                    ty = rect.y + rect.height / 2;
                    al = 'start';
                    bl = 'middle';
                    break;
                case 'top':
                    tx = rect.x + rect.width / 2;
                    ty = rect.y - dd;
                    al = 'center';
                    bl = 'bottom';
                    break;
                case 'bottom':
                    tx = rect.x + rect.width / 2;
                    ty = rect.y + rect.height + dd;
                    al = 'center';
                    bl = 'top';
                    break;
                }
            }
            break;
        case 'start':
        case 'end':
            var xStart;
            var xEnd;
            var yStart;
            var yEnd;
            if (typeof style.pointList != 'undefined') {
                var pointList = style.pointList;
                if (pointList.length < 2) {
                    return;
                }
                var length = pointList.length;
                switch (textPosition) {
                case 'start':
                    xStart = pointList[0][0];
                    xEnd = pointList[1][0];
                    yStart = pointList[0][1];
                    yEnd = pointList[1][1];
                    break;
                case 'end':
                    xStart = pointList[length - 2][0];
                    xEnd = pointList[length - 1][0];
                    yStart = pointList[length - 2][1];
                    yEnd = pointList[length - 1][1];
                    break;
                }
            } else {
                xStart = style.xStart || 0;
                xEnd = style.xEnd || 0;
                yStart = style.yStart || 0;
                yEnd = style.yEnd || 0;
            }
            switch (textPosition) {
            case 'start':
                al = xStart < xEnd ? 'end' : 'start';
                bl = yStart < yEnd ? 'bottom' : 'top';
                tx = xStart;
                ty = yStart;
                break;
            case 'end':
                al = xStart < xEnd ? 'start' : 'end';
                bl = yStart < yEnd ? 'top' : 'bottom';
                tx = xEnd;
                ty = yEnd;
                break;
            }
            dd -= 4;
            if (xStart != xEnd) {
                tx -= al == 'end' ? dd : -dd;
            } else {
                al = 'center';
            }
            if (yStart != yEnd) {
                ty -= bl == 'bottom' ? dd : -dd;
            } else {
                bl = 'middle';
            }
            break;
        case 'specific':
            tx = style.textX || 0;
            ty = style.textY || 0;
            al = 'start';
            bl = 'middle';
            break;
        }
        if (tx != null && ty != null) {
            _fillText(ctx, style.text, tx, ty, style.textFont, style.textAlign || al, style.textBaseline || bl);
        }
    };
    Base.prototype.modSelf = function () {
        this.__dirty = true;
        if (this.style) {
            this.style.__rect = null;
        }
        if (this.highlightStyle) {
            this.highlightStyle.__rect = null;
        }
    };
    Base.prototype.isSilent = function () {
        return !(this.hoverable || this.draggable || this.clickable || this.onmousemove || this.onmouseover || this.onmouseout || this.onmousedown || this.onmouseup || this.onclick || this.ondragenter || this.ondragover || this.ondragleave || this.ondrop);
    };
    util.merge(Base.prototype, Transformable.prototype, true);
    util.merge(Base.prototype, Eventful.prototype, true);
    return Base;
});