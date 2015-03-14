define([
    'require',
    './mixin/Transformable',
    './tool/util',
    './config'
], function (require) {
    var Transformable = require('./mixin/Transformable');
    var util = require('./tool/util');
    var vmlCanvasManager = window['G_vmlCanvasManager'];
    var config = require('./config');
    function returnFalse() {
        return false;
    }
    function createDom(id, type, painter) {
        var newDom = document.createElement(type);
        var width = painter.getWidth();
        var height = painter.getHeight();
        newDom.style.position = 'absolute';
        newDom.style.left = 0;
        newDom.style.top = 0;
        newDom.style.width = width + 'px';
        newDom.style.height = height + 'px';
        newDom.width = width * config.devicePixelRatio;
        newDom.height = height * config.devicePixelRatio;
        newDom.setAttribute('data-zr-dom-id', id);
        return newDom;
    }
    var Layer = function (id, painter) {
        this.id = id;
        this.dom = createDom(id, 'canvas', painter);
        this.dom.onselectstart = returnFalse;
        this.dom.style['-webkit-user-select'] = 'none';
        this.dom.style['user-select'] = 'none';
        this.dom.style['-webkit-touch-callout'] = 'none';
        this.dom.style['-webkit-tap-highlight-color'] = 'rgba(0,0,0,0)';
        vmlCanvasManager && vmlCanvasManager.initElement(this.dom);
        this.domBack = null;
        this.ctxBack = null;
        this.painter = painter;
        this.unusedCount = 0;
        this.config = null;
        this.dirty = true;
        this.elCount = 0;
        this.clearColor = 0;
        this.motionBlur = false;
        this.lastFrameAlpha = 0.7;
        this.zoomable = false;
        this.panable = false;
        this.maxZoom = Infinity;
        this.minZoom = 0;
        Transformable.call(this);
    };
    Layer.prototype.initContext = function () {
        this.ctx = this.dom.getContext('2d');
        var dpr = config.devicePixelRatio;
        if (dpr != 1) {
            this.ctx.scale(dpr, dpr);
        }
    };
    Layer.prototype.createBackBuffer = function () {
        if (vmlCanvasManager) {
            return;
        }
        this.domBack = createDom('back-' + this.id, 'canvas', this.painter);
        this.ctxBack = this.domBack.getContext('2d');
        var dpr = config.devicePixelRatio;
        if (dpr != 1) {
            this.ctxBack.scale(dpr, dpr);
        }
    };
    Layer.prototype.resize = function (width, height) {
        var dpr = config.devicePixelRatio;
        this.dom.style.width = width + 'px';
        this.dom.style.height = height + 'px';
        this.dom.setAttribute('width', width * dpr);
        this.dom.setAttribute('height', height * dpr);
        if (dpr != 1) {
            this.ctx.scale(dpr, dpr);
        }
        if (this.domBack) {
            this.domBack.setAttribute('width', width * dpr);
            this.domBack.setAttribute('height', height * dpr);
            if (dpr != 1) {
                this.ctxBack.scale(dpr, dpr);
            }
        }
    };
    Layer.prototype.clear = function () {
        var dom = this.dom;
        var ctx = this.ctx;
        var width = dom.width;
        var height = dom.height;
        var haveClearColor = this.clearColor && !vmlCanvasManager;
        var haveMotionBLur = this.motionBlur && !vmlCanvasManager;
        var lastFrameAlpha = this.lastFrameAlpha;
        var dpr = config.devicePixelRatio;
        if (haveMotionBLur) {
            if (!this.domBack) {
                this.createBackBuffer();
            }
            this.ctxBack.globalCompositeOperation = 'copy';
            this.ctxBack.drawImage(dom, 0, 0, width / dpr, height / dpr);
        }
        ctx.clearRect(0, 0, width / dpr, height / dpr);
        if (haveClearColor) {
            ctx.save();
            ctx.fillStyle = this.clearColor;
            ctx.fillRect(0, 0, width / dpr, height / dpr);
            ctx.restore();
        }
        if (haveMotionBLur) {
            var domBack = this.domBack;
            ctx.save();
            ctx.globalAlpha = lastFrameAlpha;
            ctx.drawImage(domBack, 0, 0, width / dpr, height / dpr);
            ctx.restore();
        }
    };
    util.merge(Layer.prototype, Transformable.prototype);
    return Layer;
});