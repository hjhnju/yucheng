define([
    'require',
    './config',
    './tool/util',
    './tool/log',
    './loadingEffect/Base',
    './Layer',
    './shape/Image'
], function (require) {
    'use strict';
    var config = require('./config');
    var util = require('./tool/util');
    var log = require('./tool/log');
    var BaseLoadingEffect = require('./loadingEffect/Base');
    var Layer = require('./Layer');
    function returnFalse() {
        return false;
    }
    function doNothing() {
    }
    function isLayerValid(layer) {
        if (!layer) {
            return false;
        }
        if (layer.isBuildin) {
            return true;
        }
        if (typeof layer.resize !== 'function' || typeof layer.refresh !== 'function') {
            return false;
        }
        return true;
    }
    var Painter = function (root, storage) {
        this.root = root;
        root.style['-webkit-tap-highlight-color'] = 'transparent';
        root.style['-webkit-user-select'] = 'none';
        root.style['user-select'] = 'none';
        root.style['-webkit-touch-callout'] = 'none';
        this.storage = storage;
        root.innerHTML = '';
        this._width = this._getWidth();
        this._height = this._getHeight();
        var domRoot = document.createElement('div');
        this._domRoot = domRoot;
        domRoot.style.position = 'relative';
        domRoot.style.overflow = 'hidden';
        domRoot.style.width = this._width + 'px';
        domRoot.style.height = this._height + 'px';
        root.appendChild(domRoot);
        this._layers = {};
        this._zlevelList = [];
        this._layerConfig = {};
        this._loadingEffect = new BaseLoadingEffect({});
        this.shapeToImage = this._createShapeToImageProcessor();
        this._bgDom = document.createElement('div');
        this._bgDom.style.cssText = [
            'position:absolute;left:0px;top:0px;width:',
            this._width,
            'px;height:',
            this._height + 'px;',
            '-webkit-user-select:none;user-select;none;',
            '-webkit-touch-callout:none;'
        ].join('');
        this._bgDom.setAttribute('data-zr-dom-id', 'bg');
        domRoot.appendChild(this._bgDom);
        this._bgDom.onselectstart = returnFalse;
        var hoverLayer = new Layer('_zrender_hover_', this);
        this._layers['hover'] = hoverLayer;
        domRoot.appendChild(hoverLayer.dom);
        hoverLayer.initContext();
        hoverLayer.dom.onselectstart = returnFalse;
        hoverLayer.dom.style['-webkit-user-select'] = 'none';
        hoverLayer.dom.style['user-select'] = 'none';
        hoverLayer.dom.style['-webkit-touch-callout'] = 'none';
        this.refreshNextFrame = null;
    };
    Painter.prototype.render = function (callback) {
        if (this.isLoading()) {
            this.hideLoading();
        }
        this.refresh(callback, true);
        return this;
    };
    Painter.prototype.refresh = function (callback, paintAll) {
        var list = this.storage.getShapeList(true);
        this._paintList(list, paintAll);
        for (var i = 0; i < this._zlevelList.length; i++) {
            var z = this._zlevelList[i];
            var layer = this._layers[z];
            if (!layer.isBuildin && layer.refresh) {
                layer.refresh();
            }
        }
        if (typeof callback == 'function') {
            callback();
        }
        return this;
    };
    Painter.prototype._preProcessLayer = function (layer) {
        layer.unusedCount++;
        layer.updateTransform();
    };
    Painter.prototype._postProcessLayer = function (layer) {
        layer.dirty = false;
        if (layer.unusedCount == 1) {
            layer.clear();
        }
    };
    Painter.prototype._paintList = function (list, paintAll) {
        if (typeof paintAll == 'undefined') {
            paintAll = false;
        }
        this._updateLayerStatus(list);
        var currentLayer;
        var currentZLevel;
        var ctx;
        this.eachBuildinLayer(this._preProcessLayer);
        for (var i = 0, l = list.length; i < l; i++) {
            var shape = list[i];
            if (currentZLevel !== shape.zlevel) {
                if (currentLayer) {
                    if (currentLayer.needTransform) {
                        ctx.restore();
                    }
                    ctx.flush && ctx.flush();
                }
                currentZLevel = shape.zlevel;
                currentLayer = this.getLayer(currentZLevel);
                if (!currentLayer.isBuildin) {
                    log('ZLevel ' + currentZLevel + ' has been used by unkown layer ' + currentLayer.id);
                }
                ctx = currentLayer.ctx;
                currentLayer.unusedCount = 0;
                if (currentLayer.dirty || paintAll) {
                    currentLayer.clear();
                }
                if (currentLayer.needTransform) {
                    ctx.save();
                    currentLayer.setTransform(ctx);
                }
            }
            if ((currentLayer.dirty || paintAll) && !shape.invisible) {
                if (!shape.onbrush || shape.onbrush && !shape.onbrush(ctx, false)) {
                    if (config.catchBrushException) {
                        try {
                            shape.brush(ctx, false, this.refreshNextFrame);
                        } catch (error) {
                            log(error, 'brush error of ' + shape.type, shape);
                        }
                    } else {
                        shape.brush(ctx, false, this.refreshNextFrame);
                    }
                }
            }
            shape.__dirty = false;
        }
        if (currentLayer) {
            if (currentLayer.needTransform) {
                ctx.restore();
            }
            ctx.flush && ctx.flush();
        }
        this.eachBuildinLayer(this._postProcessLayer);
    };
    Painter.prototype.getLayer = function (zlevel) {
        var layer = this._layers[zlevel];
        if (!layer) {
            layer = new Layer(zlevel, this);
            layer.isBuildin = true;
            if (this._layerConfig[zlevel]) {
                util.merge(layer, this._layerConfig[zlevel], true);
            }
            layer.updateTransform();
            this.insertLayer(zlevel, layer);
            layer.initContext();
        }
        return layer;
    };
    Painter.prototype.insertLayer = function (zlevel, layer) {
        if (this._layers[zlevel]) {
            log('ZLevel ' + zlevel + ' has been used already');
            return;
        }
        if (!isLayerValid(layer)) {
            log('Layer of zlevel ' + zlevel + ' is not valid');
            return;
        }
        var len = this._zlevelList.length;
        var prevLayer = null;
        var i = -1;
        if (len > 0 && zlevel > this._zlevelList[0]) {
            for (i = 0; i < len - 1; i++) {
                if (this._zlevelList[i] < zlevel && this._zlevelList[i + 1] > zlevel) {
                    break;
                }
            }
            prevLayer = this._layers[this._zlevelList[i]];
        }
        this._zlevelList.splice(i + 1, 0, zlevel);
        var prevDom = prevLayer ? prevLayer.dom : this._bgDom;
        if (prevDom.nextSibling) {
            prevDom.parentNode.insertBefore(layer.dom, prevDom.nextSibling);
        } else {
            prevDom.parentNode.appendChild(layer.dom);
        }
        this._layers[zlevel] = layer;
    };
    Painter.prototype.eachLayer = function (cb, context) {
        for (var i = 0; i < this._zlevelList.length; i++) {
            var z = this._zlevelList[i];
            cb.call(context, this._layers[z], z);
        }
    };
    Painter.prototype.eachBuildinLayer = function (cb, context) {
        for (var i = 0; i < this._zlevelList.length; i++) {
            var z = this._zlevelList[i];
            var layer = this._layers[z];
            if (layer.isBuildin) {
                cb.call(context, layer, z);
            }
        }
    };
    Painter.prototype.eachOtherLayer = function (cb, context) {
        for (var i = 0; i < this._zlevelList.length; i++) {
            var z = this._zlevelList[i];
            var layer = this._layers[z];
            if (!layer.isBuildin) {
                cb.call(context, layer, z);
            }
        }
    };
    Painter.prototype.getLayers = function () {
        return this._layers;
    };
    Painter.prototype._updateLayerStatus = function (list) {
        var layers = this._layers;
        var elCounts = {};
        this.eachBuildinLayer(function (layer, z) {
            elCounts[z] = layer.elCount;
            layer.elCount = 0;
        });
        for (var i = 0, l = list.length; i < l; i++) {
            var shape = list[i];
            var zlevel = shape.zlevel;
            var layer = layers[zlevel];
            if (layer) {
                layer.elCount++;
                if (layer.dirty) {
                    continue;
                }
                layer.dirty = shape.__dirty;
            }
        }
        this.eachBuildinLayer(function (layer, z) {
            if (elCounts[z] !== layer.elCount) {
                layer.dirty = true;
            }
        });
    };
    Painter.prototype.refreshShapes = function (shapeList, callback) {
        for (var i = 0, l = shapeList.length; i < l; i++) {
            var shape = shapeList[i];
            shape.modSelf();
        }
        this.refresh(callback);
        return this;
    };
    Painter.prototype.setLoadingEffect = function (loadingEffect) {
        this._loadingEffect = loadingEffect;
        return this;
    };
    Painter.prototype.clear = function () {
        this.eachBuildinLayer(this._clearLayer);
        return this;
    };
    Painter.prototype._clearLayer = function (layer) {
        layer.clear();
    };
    Painter.prototype.modLayer = function (zlevel, config) {
        if (config) {
            if (!this._layerConfig[zlevel]) {
                this._layerConfig[zlevel] = config;
            } else {
                util.merge(this._layerConfig[zlevel], config, true);
            }
            var layer = this._layers[zlevel];
            if (layer) {
                util.merge(layer, this._layerConfig[zlevel], true);
            }
        }
    };
    Painter.prototype.delLayer = function (zlevel) {
        var layer = this._layers[zlevel];
        if (!layer) {
            return;
        }
        this.modLayer(zlevel, {
            position: layer.position,
            rotation: layer.rotation,
            scale: layer.scale
        });
        layer.dom.parentNode.removeChild(layer.dom);
        delete this._layers[zlevel];
        this._zlevelList.splice(util.indexOf(this._zlevelList, zlevel), 1);
    };
    Painter.prototype.refreshHover = function () {
        this.clearHover();
        var list = this.storage.getHoverShapes(true);
        for (var i = 0, l = list.length; i < l; i++) {
            this._brushHover(list[i]);
        }
        var ctx = this._layers.hover.ctx;
        ctx.flush && ctx.flush();
        this.storage.delHover();
        return this;
    };
    Painter.prototype.clearHover = function () {
        var hover = this._layers.hover;
        hover && hover.clear();
        return this;
    };
    Painter.prototype.showLoading = function (loadingEffect) {
        this._loadingEffect && this._loadingEffect.stop();
        loadingEffect && this.setLoadingEffect(loadingEffect);
        this._loadingEffect.start(this);
        this.loading = true;
        return this;
    };
    Painter.prototype.hideLoading = function () {
        this._loadingEffect.stop();
        this.clearHover();
        this.loading = false;
        return this;
    };
    Painter.prototype.isLoading = function () {
        return this.loading;
    };
    Painter.prototype.resize = function () {
        var domRoot = this._domRoot;
        domRoot.style.display = 'none';
        var width = this._getWidth();
        var height = this._getHeight();
        domRoot.style.display = '';
        if (this._width != width || height != this._height) {
            this._width = width;
            this._height = height;
            domRoot.style.width = width + 'px';
            domRoot.style.height = height + 'px';
            for (var id in this._layers) {
                this._layers[id].resize(width, height);
            }
            this.refresh(null, true);
        }
        return this;
    };
    Painter.prototype.clearLayer = function (zLevel) {
        var layer = this._layers[zLevel];
        if (layer) {
            layer.clear();
        }
    };
    Painter.prototype.dispose = function () {
        if (this.isLoading()) {
            this.hideLoading();
        }
        this.root.innerHTML = '';
        this.root = this.storage = this._domRoot = this._layers = null;
    };
    Painter.prototype.getDomHover = function () {
        return this._layers.hover.dom;
    };
    Painter.prototype.toDataURL = function (type, backgroundColor, args) {
        if (window['G_vmlCanvasManager']) {
            return null;
        }
        var imageLayer = new Layer('image', this);
        this._bgDom.appendChild(imageLayer.dom);
        imageLayer.initContext();
        var ctx = imageLayer.ctx;
        imageLayer.clearColor = backgroundColor || '#fff';
        imageLayer.clear();
        var self = this;
        this.storage.iterShape(function (shape) {
            if (!shape.invisible) {
                if (!shape.onbrush || shape.onbrush && !shape.onbrush(ctx, false)) {
                    if (config.catchBrushException) {
                        try {
                            shape.brush(ctx, false, self.refreshNextFrame);
                        } catch (error) {
                            log(error, 'brush error of ' + shape.type, shape);
                        }
                    } else {
                        shape.brush(ctx, false, self.refreshNextFrame);
                    }
                }
            }
        }, {
            normal: 'up',
            update: true
        });
        var image = imageLayer.dom.toDataURL(type, args);
        ctx = null;
        this._bgDom.removeChild(imageLayer.dom);
        return image;
    };
    Painter.prototype.getWidth = function () {
        return this._width;
    };
    Painter.prototype.getHeight = function () {
        return this._height;
    };
    Painter.prototype._getWidth = function () {
        var root = this.root;
        var stl = root.currentStyle || document.defaultView.getComputedStyle(root);
        return ((root.clientWidth || parseInt(stl.width, 10)) - parseInt(stl.paddingLeft, 10) - parseInt(stl.paddingRight, 10)).toFixed(0) - 0;
    };
    Painter.prototype._getHeight = function () {
        var root = this.root;
        var stl = root.currentStyle || document.defaultView.getComputedStyle(root);
        return ((root.clientHeight || parseInt(stl.height, 10)) - parseInt(stl.paddingTop, 10) - parseInt(stl.paddingBottom, 10)).toFixed(0) - 0;
    };
    Painter.prototype._brushHover = function (shape) {
        var ctx = this._layers.hover.ctx;
        if (!shape.onbrush || shape.onbrush && !shape.onbrush(ctx, true)) {
            var layer = this.getLayer(shape.zlevel);
            if (layer.needTransform) {
                ctx.save();
                layer.setTransform(ctx);
            }
            if (config.catchBrushException) {
                try {
                    shape.brush(ctx, true, this.refreshNextFrame);
                } catch (error) {
                    log(error, 'hoverBrush error of ' + shape.type, shape);
                }
            } else {
                shape.brush(ctx, true, this.refreshNextFrame);
            }
            if (layer.needTransform) {
                ctx.restore();
            }
        }
    };
    Painter.prototype._shapeToImage = function (id, shape, width, height, devicePixelRatio) {
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        canvas.style.width = width + 'px';
        canvas.style.height = height + 'px';
        canvas.setAttribute('width', width * devicePixelRatio);
        canvas.setAttribute('height', height * devicePixelRatio);
        ctx.clearRect(0, 0, width * devicePixelRatio, height * devicePixelRatio);
        var shapeTransform = {
                position: shape.position,
                rotation: shape.rotation,
                scale: shape.scale
            };
        shape.position = [
            0,
            0,
            0
        ];
        shape.rotation = 0;
        shape.scale = [
            1,
            1
        ];
        if (shape) {
            shape.brush(ctx, false);
        }
        var ImageShape = require('./shape/Image');
        var imgShape = new ImageShape({
                id: id,
                style: {
                    x: 0,
                    y: 0,
                    image: canvas
                }
            });
        if (shapeTransform.position != null) {
            imgShape.position = shape.position = shapeTransform.position;
        }
        if (shapeTransform.rotation != null) {
            imgShape.rotation = shape.rotation = shapeTransform.rotation;
        }
        if (shapeTransform.scale != null) {
            imgShape.scale = shape.scale = shapeTransform.scale;
        }
        return imgShape;
    };
    Painter.prototype._createShapeToImageProcessor = function () {
        if (window['G_vmlCanvasManager']) {
            return doNothing;
        }
        var me = this;
        return function (id, e, width, height) {
            return me._shapeToImage(id, e, width, height, config.devicePixelRatio);
        };
    };
    return Painter;
});