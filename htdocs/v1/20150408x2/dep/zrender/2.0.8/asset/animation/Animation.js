define('zrender/animation/Animation', [
    'require',
    './Clip',
    '../tool/color',
    '../tool/util',
    '../tool/event'
], function (require) {
    'use strict';
    var Clip = require('./Clip');
    var color = require('../tool/color');
    var util = require('../tool/util');
    var Dispatcher = require('../tool/event').Dispatcher;
    var requestAnimationFrame = window.requestAnimationFrame || window.msRequestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || function (func) {
            setTimeout(func, 16);
        };
    var arraySlice = Array.prototype.slice;
    var Animation = function (options) {
        options = options || {};
        this.stage = options.stage || {};
        this.onframe = options.onframe || function () {
        };
        this._clips = [];
        this._running = false;
        this._time = 0;
        Dispatcher.call(this);
    };
    Animation.prototype = {
        add: function (clip) {
            this._clips.push(clip);
        },
        remove: function (clip) {
            var idx = util.indexOf(this._clips, clip);
            if (idx >= 0) {
                this._clips.splice(idx, 1);
            }
        },
        _update: function () {
            var time = new Date().getTime();
            var delta = time - this._time;
            var clips = this._clips;
            var len = clips.length;
            var deferredEvents = [];
            var deferredClips = [];
            for (var i = 0; i < len; i++) {
                var clip = clips[i];
                var e = clip.step(time);
                if (e) {
                    deferredEvents.push(e);
                    deferredClips.push(clip);
                }
            }
            for (var i = 0; i < len;) {
                if (clips[i]._needsRemove) {
                    clips[i] = clips[len - 1];
                    clips.pop();
                    len--;
                } else {
                    i++;
                }
            }
            len = deferredEvents.length;
            for (var i = 0; i < len; i++) {
                deferredClips[i].fire(deferredEvents[i]);
            }
            this._time = time;
            this.onframe(delta);
            this.dispatch('frame', delta);
            if (this.stage.update) {
                this.stage.update();
            }
        },
        start: function () {
            var self = this;
            this._running = true;
            function step() {
                if (self._running) {
                    requestAnimationFrame(step);
                    self._update();
                }
            }
            this._time = new Date().getTime();
            requestAnimationFrame(step);
        },
        stop: function () {
            this._running = false;
        },
        clear: function () {
            this._clips = [];
        },
        animate: function (target, options) {
            options = options || {};
            var deferred = new Animator(target, options.loop, options.getter, options.setter);
            deferred.animation = this;
            return deferred;
        },
        constructor: Animation
    };
    util.merge(Animation.prototype, Dispatcher.prototype, true);
    function _defaultGetter(target, key) {
        return target[key];
    }
    function _defaultSetter(target, key, value) {
        target[key] = value;
    }
    function _interpolateNumber(p0, p1, percent) {
        return (p1 - p0) * percent + p0;
    }
    function _interpolateArray(p0, p1, percent, out, arrDim) {
        var len = p0.length;
        if (arrDim == 1) {
            for (var i = 0; i < len; i++) {
                out[i] = _interpolateNumber(p0[i], p1[i], percent);
            }
        } else {
            var len2 = p0[0].length;
            for (var i = 0; i < len; i++) {
                for (var j = 0; j < len2; j++) {
                    out[i][j] = _interpolateNumber(p0[i][j], p1[i][j], percent);
                }
            }
        }
    }
    function _isArrayLike(data) {
        switch (typeof data) {
        case 'undefined':
        case 'string':
            return false;
        }
        return typeof data.length !== 'undefined';
    }
    function _catmullRomInterpolateArray(p0, p1, p2, p3, t, t2, t3, out, arrDim) {
        var len = p0.length;
        if (arrDim == 1) {
            for (var i = 0; i < len; i++) {
                out[i] = _catmullRomInterpolate(p0[i], p1[i], p2[i], p3[i], t, t2, t3);
            }
        } else {
            var len2 = p0[0].length;
            for (var i = 0; i < len; i++) {
                for (var j = 0; j < len2; j++) {
                    out[i][j] = _catmullRomInterpolate(p0[i][j], p1[i][j], p2[i][j], p3[i][j], t, t2, t3);
                }
            }
        }
    }
    function _catmullRomInterpolate(p0, p1, p2, p3, t, t2, t3) {
        var v0 = (p2 - p0) * 0.5;
        var v1 = (p3 - p1) * 0.5;
        return (2 * (p1 - p2) + v0 + v1) * t3 + (-3 * (p1 - p2) - 2 * v0 - v1) * t2 + v0 * t + p1;
    }
    function _cloneValue(value) {
        if (_isArrayLike(value)) {
            var len = value.length;
            if (_isArrayLike(value[0])) {
                var ret = [];
                for (var i = 0; i < len; i++) {
                    ret.push(arraySlice.call(value[i]));
                }
                return ret;
            } else {
                return arraySlice.call(value);
            }
        } else {
            return value;
        }
    }
    function rgba2String(rgba) {
        rgba[0] = Math.floor(rgba[0]);
        rgba[1] = Math.floor(rgba[1]);
        rgba[2] = Math.floor(rgba[2]);
        return 'rgba(' + rgba.join(',') + ')';
    }
    var Animator = function (target, loop, getter, setter) {
        this._tracks = {};
        this._target = target;
        this._loop = loop || false;
        this._getter = getter || _defaultGetter;
        this._setter = setter || _defaultSetter;
        this._clipCount = 0;
        this._delay = 0;
        this._doneList = [];
        this._onframeList = [];
        this._clipList = [];
    };
    Animator.prototype = {
        when: function (time, props) {
            for (var propName in props) {
                if (!this._tracks[propName]) {
                    this._tracks[propName] = [];
                    if (time !== 0) {
                        this._tracks[propName].push({
                            time: 0,
                            value: _cloneValue(this._getter(this._target, propName))
                        });
                    }
                }
                this._tracks[propName].push({
                    time: parseInt(time, 10),
                    value: props[propName]
                });
            }
            return this;
        },
        during: function (callback) {
            this._onframeList.push(callback);
            return this;
        },
        start: function (easing) {
            var self = this;
            var setter = this._setter;
            var getter = this._getter;
            var useSpline = easing === 'spline';
            var ondestroy = function () {
                self._clipCount--;
                if (self._clipCount === 0) {
                    self._tracks = {};
                    var len = self._doneList.length;
                    for (var i = 0; i < len; i++) {
                        self._doneList[i].call(self);
                    }
                }
            };
            var createTrackClip = function (keyframes, propName) {
                var trackLen = keyframes.length;
                if (!trackLen) {
                    return;
                }
                var firstVal = keyframes[0].value;
                var isValueArray = _isArrayLike(firstVal);
                var isValueColor = false;
                var arrDim = isValueArray && _isArrayLike(firstVal[0]) ? 2 : 1;
                keyframes.sort(function (a, b) {
                    return a.time - b.time;
                });
                var trackMaxTime;
                if (trackLen) {
                    trackMaxTime = keyframes[trackLen - 1].time;
                } else {
                    return;
                }
                var kfPercents = [];
                var kfValues = [];
                for (var i = 0; i < trackLen; i++) {
                    kfPercents.push(keyframes[i].time / trackMaxTime);
                    var value = keyframes[i].value;
                    if (typeof value == 'string') {
                        value = color.toArray(value);
                        if (value.length === 0) {
                            value[0] = value[1] = value[2] = 0;
                            value[3] = 1;
                        }
                        isValueColor = true;
                    }
                    kfValues.push(value);
                }
                var cacheKey = 0;
                var cachePercent = 0;
                var start;
                var i;
                var w;
                var p0;
                var p1;
                var p2;
                var p3;
                if (isValueColor) {
                    var rgba = [
                            0,
                            0,
                            0,
                            0
                        ];
                }
                var onframe = function (target, percent) {
                    if (percent < cachePercent) {
                        start = Math.min(cacheKey + 1, trackLen - 1);
                        for (i = start; i >= 0; i--) {
                            if (kfPercents[i] <= percent) {
                                break;
                            }
                        }
                        i = Math.min(i, trackLen - 2);
                    } else {
                        for (i = cacheKey; i < trackLen; i++) {
                            if (kfPercents[i] > percent) {
                                break;
                            }
                        }
                        i = Math.min(i - 1, trackLen - 2);
                    }
                    cacheKey = i;
                    cachePercent = percent;
                    var range = kfPercents[i + 1] - kfPercents[i];
                    if (range === 0) {
                        return;
                    } else {
                        w = (percent - kfPercents[i]) / range;
                    }
                    if (useSpline) {
                        p1 = kfValues[i];
                        p0 = kfValues[i === 0 ? i : i - 1];
                        p2 = kfValues[i > trackLen - 2 ? trackLen - 1 : i + 1];
                        p3 = kfValues[i > trackLen - 3 ? trackLen - 1 : i + 2];
                        if (isValueArray) {
                            _catmullRomInterpolateArray(p0, p1, p2, p3, w, w * w, w * w * w, getter(target, propName), arrDim);
                        } else {
                            var value;
                            if (isValueColor) {
                                value = _catmullRomInterpolateArray(p0, p1, p2, p3, w, w * w, w * w * w, rgba, 1);
                                value = rgba2String(rgba);
                            } else {
                                value = _catmullRomInterpolate(p0, p1, p2, p3, w, w * w, w * w * w);
                            }
                            setter(target, propName, value);
                        }
                    } else {
                        if (isValueArray) {
                            _interpolateArray(kfValues[i], kfValues[i + 1], w, getter(target, propName), arrDim);
                        } else {
                            var value;
                            if (isValueColor) {
                                _interpolateArray(kfValues[i], kfValues[i + 1], w, rgba, 1);
                                value = rgba2String(rgba);
                            } else {
                                value = _interpolateNumber(kfValues[i], kfValues[i + 1], w);
                            }
                            setter(target, propName, value);
                        }
                    }
                    for (i = 0; i < self._onframeList.length; i++) {
                        self._onframeList[i](target, percent);
                    }
                };
                var clip = new Clip({
                        target: self._target,
                        life: trackMaxTime,
                        loop: self._loop,
                        delay: self._delay,
                        onframe: onframe,
                        ondestroy: ondestroy
                    });
                if (easing && easing !== 'spline') {
                    clip.easing = easing;
                }
                self._clipList.push(clip);
                self._clipCount++;
                self.animation.add(clip);
            };
            for (var propName in this._tracks) {
                createTrackClip(this._tracks[propName], propName);
            }
            return this;
        },
        stop: function () {
            for (var i = 0; i < this._clipList.length; i++) {
                var clip = this._clipList[i];
                this.animation.remove(clip);
            }
            this._clipList = [];
        },
        delay: function (time) {
            this._delay = time;
            return this;
        },
        done: function (cb) {
            if (cb) {
                this._doneList.push(cb);
            }
            return this;
        }
    };
    return Animation;
});