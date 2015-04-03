define([
    'require',
    '../tool/matrix',
    '../tool/vector'
], function (require) {
    'use strict';
    var matrix = require('../tool/matrix');
    var vector = require('../tool/vector');
    var origin = [
            0,
            0
        ];
    var mTranslate = matrix.translate;
    var EPSILON = 0.00005;
    function isAroundZero(val) {
        return val > -EPSILON && val < EPSILON;
    }
    function isNotAroundZero(val) {
        return val > EPSILON || val < -EPSILON;
    }
    var Transformable = function () {
        if (!this.position) {
            this.position = [
                0,
                0
            ];
        }
        if (typeof this.rotation == 'undefined') {
            this.rotation = [
                0,
                0,
                0
            ];
        }
        if (!this.scale) {
            this.scale = [
                1,
                1,
                0,
                0
            ];
        }
        this.needLocalTransform = false;
        this.needTransform = false;
    };
    Transformable.prototype = {
        constructor: Transformable,
        updateNeedTransform: function () {
            this.needLocalTransform = isNotAroundZero(this.rotation[0]) || isNotAroundZero(this.position[0]) || isNotAroundZero(this.position[1]) || isNotAroundZero(this.scale[0] - 1) || isNotAroundZero(this.scale[1] - 1);
        },
        updateTransform: function () {
            this.updateNeedTransform();
            var parentHasTransform = this.parent && this.parent.needTransform;
            this.needTransform = this.needLocalTransform || parentHasTransform;
            if (!this.needTransform) {
                return;
            }
            var m = this.transform || matrix.create();
            matrix.identity(m);
            if (this.needLocalTransform) {
                var scale = this.scale;
                if (isNotAroundZero(scale[0]) || isNotAroundZero(scale[1])) {
                    origin[0] = -scale[2] || 0;
                    origin[1] = -scale[3] || 0;
                    var haveOrigin = isNotAroundZero(origin[0]) || isNotAroundZero(origin[1]);
                    if (haveOrigin) {
                        mTranslate(m, m, origin);
                    }
                    matrix.scale(m, m, scale);
                    if (haveOrigin) {
                        origin[0] = -origin[0];
                        origin[1] = -origin[1];
                        mTranslate(m, m, origin);
                    }
                }
                if (this.rotation instanceof Array) {
                    if (this.rotation[0] !== 0) {
                        origin[0] = -this.rotation[1] || 0;
                        origin[1] = -this.rotation[2] || 0;
                        var haveOrigin = isNotAroundZero(origin[0]) || isNotAroundZero(origin[1]);
                        if (haveOrigin) {
                            mTranslate(m, m, origin);
                        }
                        matrix.rotate(m, m, this.rotation[0]);
                        if (haveOrigin) {
                            origin[0] = -origin[0];
                            origin[1] = -origin[1];
                            mTranslate(m, m, origin);
                        }
                    }
                } else {
                    if (this.rotation !== 0) {
                        matrix.rotate(m, m, this.rotation);
                    }
                }
                if (isNotAroundZero(this.position[0]) || isNotAroundZero(this.position[1])) {
                    mTranslate(m, m, this.position);
                }
            }
            if (parentHasTransform) {
                if (this.needLocalTransform) {
                    matrix.mul(m, this.parent.transform, m);
                } else {
                    matrix.copy(m, this.parent.transform);
                }
            }
            this.transform = m;
            this.invTransform = this.invTransform || matrix.create();
            matrix.invert(this.invTransform, m);
        },
        setTransform: function (ctx) {
            if (this.needTransform) {
                var m = this.transform;
                ctx.transform(m[0], m[1], m[2], m[3], m[4], m[5]);
            }
        },
        lookAt: function () {
            var v = vector.create();
            return function (target) {
                if (!this.transform) {
                    this.transform = matrix.create();
                }
                var m = this.transform;
                vector.sub(v, target, this.position);
                if (isAroundZero(v[0]) && isAroundZero(v[1])) {
                    return;
                }
                vector.normalize(v, v);
                var scale = this.scale;
                m[2] = v[0] * scale[1];
                m[3] = v[1] * scale[1];
                m[0] = v[1] * scale[0];
                m[1] = -v[0] * scale[0];
                m[4] = this.position[0];
                m[5] = this.position[1];
                this.decomposeTransform();
            };
        }(),
        decomposeTransform: function () {
            if (!this.transform) {
                return;
            }
            var m = this.transform;
            var sx = m[0] * m[0] + m[1] * m[1];
            var position = this.position;
            var scale = this.scale;
            var rotation = this.rotation;
            if (isNotAroundZero(sx - 1)) {
                sx = Math.sqrt(sx);
            }
            var sy = m[2] * m[2] + m[3] * m[3];
            if (isNotAroundZero(sy - 1)) {
                sy = Math.sqrt(sy);
            }
            position[0] = m[4];
            position[1] = m[5];
            scale[0] = sx;
            scale[1] = sy;
            scale[2] = scale[3] = 0;
            rotation[0] = Math.atan2(-m[1] / sy, m[0] / sx);
            rotation[1] = rotation[2] = 0;
        },
        transformCoordToLocal: function (x, y) {
            var v2 = [
                    x,
                    y
                ];
            if (this.needTransform && this.invTransform) {
                matrix.mulVector(v2, this.invTransform, v2);
            }
            return v2;
        }
    };
    return Transformable;
});