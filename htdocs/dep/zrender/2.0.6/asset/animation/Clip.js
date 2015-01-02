/*! 2015 Baidu Inc. All Rights Reserved */
define('zrender/animation/Clip', function (require) {
    var Easing = require('./easing');
    function Clip(options) {
        this._targetPool = options.target || {};
        if (!(this._targetPool instanceof Array)) {
            this._targetPool = [this._targetPool];
        }
        this._life = options.life || 1000;
        this._delay = options.delay || 0;
        this._startTime = new Date().getTime() + this._delay;
        this._endTime = this._startTime + this._life * 1000;
        this.loop = typeof options.loop == 'undefined' ? false : options.loop;
        this.gap = options.gap || 0;
        this.easing = options.easing || 'Linear';
        this.onframe = options.onframe;
        this.ondestroy = options.ondestroy;
        this.onrestart = options.onrestart;
    }
    Clip.prototype = {
        step: function (time) {
            var percent = (time - this._startTime) / this._life;
            if (percent < 0) {
                return;
            }
            percent = Math.min(percent, 1);
            var easingFunc = typeof this.easing == 'string' ? Easing[this.easing] : this.easing;
            var schedule = typeof easingFunc === 'function' ? easingFunc(percent) : percent;
            this.fire('frame', schedule);
            if (percent == 1) {
                if (this.loop) {
                    this.restart();
                    return 'restart';
                }
                this._needsRemove = true;
                return 'destroy';
            }
            return null;
        },
        restart: function () {
            var time = new Date().getTime();
            var remainder = (time - this._startTime) % this._life;
            this._startTime = new Date().getTime() - remainder + this.gap;
            this._needsRemove = false;
        },
        fire: function (eventType, arg) {
            for (var i = 0, len = this._targetPool.length; i < len; i++) {
                if (this['on' + eventType]) {
                    this['on' + eventType](this._targetPool[i], arg);
                }
            }
        },
        constructor: Clip
    };
    return Clip;
});