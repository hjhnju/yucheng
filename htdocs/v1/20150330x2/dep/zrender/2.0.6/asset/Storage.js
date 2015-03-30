define([
    'require',
    './tool/util',
    './Group'
], function (require) {
    'use strict';
    var util = require('./tool/util');
    var Group = require('./Group');
    var defaultIterateOption = {
            hover: false,
            normal: 'down',
            update: false
        };
    function shapeCompareFunc(a, b) {
        if (a.zlevel == b.zlevel) {
            if (a.z == b.z) {
                return a.__renderidx - b.__renderidx;
            }
            return a.z - b.z;
        }
        return a.zlevel - b.zlevel;
    }
    var Storage = function () {
        this._elements = {};
        this._hoverElements = [];
        this._roots = [];
        this._shapeList = [];
        this._shapeListOffset = 0;
    };
    Storage.prototype.iterShape = function (fun, option) {
        if (!option) {
            option = defaultIterateOption;
        }
        if (option.hover) {
            for (var i = 0, l = this._hoverElements.length; i < l; i++) {
                var el = this._hoverElements[i];
                el.updateTransform();
                if (fun(el)) {
                    return this;
                }
            }
        }
        if (option.update) {
            this.updateShapeList();
        }
        switch (option.normal) {
        case 'down':
            var l = this._shapeList.length;
            while (l--) {
                if (fun(this._shapeList[l])) {
                    return this;
                }
            }
            break;
        default:
            for (var i = 0, l = this._shapeList.length; i < l; i++) {
                if (fun(this._shapeList[i])) {
                    return this;
                }
            }
            break;
        }
        return this;
    };
    Storage.prototype.getHoverShapes = function (update) {
        var hoverElements = [];
        for (var i = 0, l = this._hoverElements.length; i < l; i++) {
            hoverElements.push(this._hoverElements[i]);
            var target = this._hoverElements[i].hoverConnect;
            if (target) {
                var shape;
                target = target instanceof Array ? target : [target];
                for (var j = 0, k = target.length; j < k; j++) {
                    shape = target[j].id ? target[j] : this.get(target[j]);
                    if (shape) {
                        hoverElements.push(shape);
                    }
                }
            }
        }
        hoverElements.sort(shapeCompareFunc);
        if (update) {
            for (var i = 0, l = hoverElements.length; i < l; i++) {
                hoverElements[i].updateTransform();
            }
        }
        return hoverElements;
    };
    Storage.prototype.getShapeList = function (update) {
        if (update) {
            this.updateShapeList();
        }
        return this._shapeList;
    };
    Storage.prototype.updateShapeList = function () {
        this._shapeListOffset = 0;
        for (var i = 0, len = this._roots.length; i < len; i++) {
            var root = this._roots[i];
            this._updateAndAddShape(root);
        }
        this._shapeList.length = this._shapeListOffset;
        for (var i = 0, len = this._shapeList.length; i < len; i++) {
            this._shapeList[i].__renderidx = i;
        }
        this._shapeList.sort(shapeCompareFunc);
    };
    Storage.prototype._updateAndAddShape = function (el, clipShapes) {
        if (el.ignore) {
            return;
        }
        el.updateTransform();
        if (el.type == 'group') {
            if (el.clipShape) {
                el.clipShape.parent = el;
                el.clipShape.updateTransform();
                if (clipShapes) {
                    clipShapes = clipShapes.slice();
                    clipShapes.push(el.clipShape);
                } else {
                    clipShapes = [el.clipShape];
                }
            }
            for (var i = 0; i < el._children.length; i++) {
                var child = el._children[i];
                child.__dirty = el.__dirty || child.__dirty;
                this._updateAndAddShape(child, clipShapes);
            }
            el.__dirty = false;
        } else {
            el.__clipShapes = clipShapes;
            this._shapeList[this._shapeListOffset++] = el;
        }
    };
    Storage.prototype.mod = function (elId, params) {
        var el = this._elements[elId];
        if (el) {
            el.modSelf();
            if (params) {
                if (params.parent || params._storage || params.__startClip) {
                    var target = {};
                    for (var name in params) {
                        if (name == 'parent' || name == '_storage' || name == '__startClip') {
                            continue;
                        }
                        if (params.hasOwnProperty(name)) {
                            target[name] = params[name];
                        }
                    }
                    util.merge(el, target, true);
                } else {
                    util.merge(el, params, true);
                }
            }
        }
        return this;
    };
    Storage.prototype.drift = function (shapeId, dx, dy) {
        var shape = this._elements[shapeId];
        if (shape) {
            shape.needTransform = true;
            if (shape.draggable === 'horizontal') {
                dy = 0;
            } else if (shape.draggable === 'vertical') {
                dx = 0;
            }
            if (!shape.ondrift || shape.ondrift && !shape.ondrift(dx, dy)) {
                shape.drift(dx, dy);
            }
        }
        return this;
    };
    Storage.prototype.addHover = function (shape) {
        shape.updateNeedTransform();
        this._hoverElements.push(shape);
        return this;
    };
    Storage.prototype.delHover = function () {
        this._hoverElements = [];
        return this;
    };
    Storage.prototype.hasHoverShape = function () {
        return this._hoverElements.length > 0;
    };
    Storage.prototype.addRoot = function (el) {
        if (el instanceof Group) {
            el.addChildrenToStorage(this);
        }
        this.addToMap(el);
        this._roots.push(el);
    };
    Storage.prototype.delRoot = function (elId) {
        if (typeof elId == 'undefined') {
            for (var i = 0; i < this._roots.length; i++) {
                var root = this._roots[i];
                if (root instanceof Group) {
                    root.delChildrenFromStorage(this);
                }
            }
            this._elements = {};
            this._hoverElements = [];
            this._roots = [];
            this._shapeList = [];
            this._shapeListOffset = 0;
            return;
        }
        if (elId instanceof Array) {
            for (var i = 0, l = elId.length; i < l; i++) {
                this.delRoot(elId[i]);
            }
            return;
        }
        var el;
        if (typeof elId == 'string') {
            el = this._elements[elId];
        } else {
            el = elId;
        }
        var idx = util.indexOf(this._roots, el);
        if (idx >= 0) {
            this.delFromMap(el.id);
            this._roots.splice(idx, 1);
            if (el instanceof Group) {
                el.delChildrenFromStorage(this);
            }
        }
    };
    Storage.prototype.addToMap = function (el) {
        if (el instanceof Group) {
            el._storage = this;
        }
        el.modSelf();
        this._elements[el.id] = el;
        return this;
    };
    Storage.prototype.get = function (elId) {
        return this._elements[elId];
    };
    Storage.prototype.delFromMap = function (elId) {
        var el = this._elements[elId];
        if (el) {
            delete this._elements[elId];
            if (el instanceof Group) {
                el._storage = null;
            }
        }
        return this;
    };
    Storage.prototype.dispose = function () {
        this._elements = this._renderList = this._roots = this._hoverElements = null;
    };
    return Storage;
});