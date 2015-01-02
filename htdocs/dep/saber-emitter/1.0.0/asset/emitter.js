/*! 2015 Baidu Inc. All Rights Reserved */
define('saber-emitter/emitter', function () {
    function Emitter() {
    }
    var proto = Emitter.prototype;
    proto._getEvents = function () {
        if (!this._events) {
            this._events = {};
        }
        return this._events;
    };
    proto._getMaxListeners = function () {
        if (isNaN(this.maxListeners)) {
            this.maxListeners = 10;
        }
        return this.maxListeners;
    };
    proto.on = function (event, listener) {
        var events = this._getEvents();
        var maxListeners = this._getMaxListeners();
        events[event] = events[event] || [];
        var currentListeners = events[event].length;
        if (currentListeners >= maxListeners && maxListeners !== 0) {
            throw new RangeError('Warning: possible Emitter memory leak detected. ' + currentListeners + ' listeners added.');
        }
        events[event].push(listener);
        return this;
    };
    proto.once = function (event, listener) {
        var me = this;
        function on() {
            me.off(event, on);
            listener.apply(this, arguments);
        }
        on.listener = listener;
        this.on(event, on);
        return this;
    };
    proto.off = function (event, listener) {
        var events = this._getEvents();
        if (0 === arguments.length) {
            this._events = {};
            return this;
        }
        var listeners = events[event];
        if (!listeners) {
            return this;
        }
        if (1 === arguments.length) {
            delete events[event];
            return this;
        }
        var cb;
        for (var i = 0; i < listeners.length; i++) {
            cb = listeners[i];
            if (cb === listener || cb.listener === listener) {
                listeners.splice(i, 1);
                break;
            }
        }
        return this;
    };
    proto.emit = function (event) {
        var events = this._getEvents();
        var listeners = events[event];
        var args = Array.prototype.slice.call(arguments, 1);
        if (listeners) {
            listeners = listeners.slice(0);
            for (var i = 0, len = listeners.length; i < len; i++) {
                listeners[i].apply(this, args);
            }
        }
        return this;
    };
    proto.listeners = function (event) {
        var events = this._getEvents();
        return events[event] || [];
    };
    proto.setMaxListeners = function (number) {
        this.maxListeners = number;
        return this;
    };
    Emitter.mixin = function (obj) {
        for (var key in Emitter.prototype) {
            obj[key] = Emitter.prototype[key];
        }
        return obj;
    };
    return Emitter;
});

define('saber-emitter', ['saber-emitter/emitter'], function ( main ) { return main; });