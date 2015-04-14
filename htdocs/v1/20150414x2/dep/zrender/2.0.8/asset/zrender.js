define('zrender/zrender', [
    'require',
    './dep/excanvas',
    './tool/util',
    './tool/log',
    './tool/guid',
    './Handler',
    './Painter',
    './Storage',
    './animation/Animation',
    './tool/env'
], function (require) {
    require('./dep/excanvas');
    var util = require('./tool/util');
    var log = require('./tool/log');
    var guid = require('./tool/guid');
    var Handler = require('./Handler');
    var Painter = require('./Painter');
    var Storage = require('./Storage');
    var Animation = require('./animation/Animation');
    var _instances = {};
    var zrender = {};
    zrender.version = '2.0.8';
    zrender.init = function (dom) {
        var zr = new ZRender(guid(), dom);
        _instances[zr.id] = zr;
        return zr;
    };
    zrender.dispose = function (zr) {
        if (zr) {
            zr.dispose();
        } else {
            for (var key in _instances) {
                _instances[key].dispose();
            }
            _instances = {};
        }
        return zrender;
    };
    zrender.getInstance = function (id) {
        return _instances[id];
    };
    zrender.delInstance = function (id) {
        delete _instances[id];
        return zrender;
    };
    function getFrameCallback(zrInstance) {
        return function () {
            var animatingElements = zrInstance.animatingElements;
            for (var i = 0, l = animatingElements.length; i < l; i++) {
                zrInstance.storage.mod(animatingElements[i].id);
            }
            if (animatingElements.length || zrInstance._needsRefreshNextFrame) {
                zrInstance.refresh();
            }
        };
    }
    var ZRender = function (id, dom) {
        this.id = id;
        this.env = require('./tool/env');
        this.storage = new Storage();
        this.painter = new Painter(dom, this.storage);
        this.handler = new Handler(dom, this.storage, this.painter);
        this.animatingElements = [];
        this.animation = new Animation({ stage: { update: getFrameCallback(this) } });
        this.animation.start();
        var self = this;
        this.painter.refreshNextFrame = function () {
            self.refreshNextFrame();
        };
        this._needsRefreshNextFrame = false;
        var self = this;
        var storage = this.storage;
        var oldDelFromMap = storage.delFromMap;
        storage.delFromMap = function (elId) {
            var el = storage.get(elId);
            self.stopAnimation(el);
            oldDelFromMap.call(storage, elId);
        };
    };
    ZRender.prototype.getId = function () {
        return this.id;
    };
    ZRender.prototype.addShape = function (shape) {
        this.addElement(shape);
        return this;
    };
    ZRender.prototype.addGroup = function (group) {
        this.addElement(group);
        return this;
    };
    ZRender.prototype.delShape = function (shapeId) {
        this.delElement(shapeId);
        return this;
    };
    ZRender.prototype.delGroup = function (groupId) {
        this.delElement(groupId);
        return this;
    };
    ZRender.prototype.modShape = function (shapeId, shape) {
        this.modElement(shapeId, shape);
        return this;
    };
    ZRender.prototype.modGroup = function (groupId, group) {
        this.modElement(groupId, group);
        return this;
    };
    ZRender.prototype.addElement = function (el) {
        this.storage.addRoot(el);
        this._needsRefreshNextFrame = true;
        return this;
    };
    ZRender.prototype.delElement = function (el) {
        this.storage.delRoot(el);
        this._needsRefreshNextFrame = true;
        return this;
    };
    ZRender.prototype.modElement = function (el, params) {
        this.storage.mod(el, params);
        this._needsRefreshNextFrame = true;
        return this;
    };
    ZRender.prototype.modLayer = function (zLevel, config) {
        this.painter.modLayer(zLevel, config);
        this._needsRefreshNextFrame = true;
        return this;
    };
    ZRender.prototype.addHoverShape = function (shape) {
        this.storage.addHover(shape);
        return this;
    };
    ZRender.prototype.render = function (callback) {
        this.painter.render(callback);
        this._needsRefreshNextFrame = false;
        return this;
    };
    ZRender.prototype.refresh = function (callback) {
        this.painter.refresh(callback);
        this._needsRefreshNextFrame = false;
        return this;
    };
    ZRender.prototype.refreshNextFrame = function () {
        this._needsRefreshNextFrame = true;
        return this;
    };
    ZRender.prototype.refreshHover = function (callback) {
        this.painter.refreshHover(callback);
        return this;
    };
    ZRender.prototype.refreshShapes = function (shapeList, callback) {
        this.painter.refreshShapes(shapeList, callback);
        return this;
    };
    ZRender.prototype.resize = function () {
        this.painter.resize();
        return this;
    };
    ZRender.prototype.animate = function (el, path, loop) {
        if (typeof el === 'string') {
            el = this.storage.get(el);
        }
        if (el) {
            var target;
            if (path) {
                var pathSplitted = path.split('.');
                var prop = el;
                for (var i = 0, l = pathSplitted.length; i < l; i++) {
                    if (!prop) {
                        continue;
                    }
                    prop = prop[pathSplitted[i]];
                }
                if (prop) {
                    target = prop;
                }
            } else {
                target = el;
            }
            if (!target) {
                log('Property "' + path + '" is not existed in element ' + el.id);
                return;
            }
            var animatingElements = this.animatingElements;
            if (el.__animators == null) {
                el.__animators = [];
            }
            var animators = el.__animators;
            if (animators.length === 0) {
                animatingElements.push(el);
            }
            var animator = this.animation.animate(target, { loop: loop }).done(function () {
                    animators.splice(el.__animators.indexOf(animator), 1);
                    if (animators.length === 0) {
                        var idx = util.indexOf(animatingElements, el);
                        animatingElements.splice(idx, 1);
                    }
                });
            animators.push(animator);
            return animator;
        } else {
            log('Element not existed');
        }
    };
    ZRender.prototype.stopAnimation = function (el) {
        if (el.__animators) {
            var animators = el.__animators;
            var len = animators.length;
            for (var i = 0; i < len; i++) {
                animators[i].stop();
            }
            if (len > 0) {
                var animatingElements = this.animatingElements;
                animatingElements.splice(animatingElements.indexOf(el), 1);
            }
            animators.length = 0;
        }
        return this;
    };
    ZRender.prototype.clearAnimation = function () {
        this.animation.clear();
        this.animatingElements.length = 0;
        return this;
    };
    ZRender.prototype.showLoading = function (loadingEffect) {
        this.painter.showLoading(loadingEffect);
        return this;
    };
    ZRender.prototype.hideLoading = function () {
        this.painter.hideLoading();
        return this;
    };
    ZRender.prototype.getWidth = function () {
        return this.painter.getWidth();
    };
    ZRender.prototype.getHeight = function () {
        return this.painter.getHeight();
    };
    ZRender.prototype.toDataURL = function (type, backgroundColor, args) {
        return this.painter.toDataURL(type, backgroundColor, args);
    };
    ZRender.prototype.shapeToImage = function (e, width, height) {
        var id = guid();
        return this.painter.shapeToImage(id, e, width, height);
    };
    ZRender.prototype.on = function (eventName, eventHandler, context) {
        this.handler.on(eventName, eventHandler, context);
        return this;
    };
    ZRender.prototype.un = function (eventName, eventHandler) {
        this.handler.un(eventName, eventHandler);
        return this;
    };
    ZRender.prototype.trigger = function (eventName, event) {
        this.handler.trigger(eventName, event);
        return this;
    };
    ZRender.prototype.clear = function () {
        this.storage.delRoot();
        this.painter.clear();
        return this;
    };
    ZRender.prototype.dispose = function () {
        this.animation.stop();
        this.clear();
        this.storage.dispose();
        this.painter.dispose();
        this.handler.dispose();
        this.animation = this.animatingElements = this.storage = this.painter = this.handler = null;
        zrender.delInstance(this.id);
    };
    return zrender;
});

define('zrender', ['zrender/zrender'], function ( main ) { return main; });