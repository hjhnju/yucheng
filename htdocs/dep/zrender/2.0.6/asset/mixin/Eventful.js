/*! 2014 Baidu Inc. All Rights Reserved */
define('zrender/mixin/Eventful', function (require) {
    var Eventful = function () {
        this._handlers = {};
    };
    Eventful.prototype.one = function (event, handler, context) {
        var _h = this._handlers;
        if (!handler || !event) {
            return this;
        }
        if (!_h[event]) {
            _h[event] = [];
        }
        _h[event].push({
            h: handler,
            one: true,
            ctx: context || this
        });
        return this;
    };
    Eventful.prototype.bind = function (event, handler, context) {
        var _h = this._handlers;
        if (!handler || !event) {
            return this;
        }
        if (!_h[event]) {
            _h[event] = [];
        }
        _h[event].push({
            h: handler,
            one: false,
            ctx: context || this
        });
        return this;
    };
    Eventful.prototype.unbind = function (event, handler) {
        var _h = this._handlers;
        if (!event) {
            this._handlers = {};
            return this;
        }
        if (handler) {
            if (_h[event]) {
                var newList = [];
                for (var i = 0, l = _h[event].length; i < l; i++) {
                    if (_h[event][i]['h'] != handler) {
                        newList.push(_h[event][i]);
                    }
                }
                _h[event] = newList;
            }
            if (_h[event] && _h[event].length === 0) {
                delete _h[event];
            }
        } else {
            delete _h[event];
        }
        return this;
    };
    Eventful.prototype.dispatch = function (type) {
        if (this._handlers[type]) {
            var args = arguments;
            var argLen = args.length;
            if (argLen > 3) {
                args = Array.prototype.slice.call(args, 1);
            }
            var _h = this._handlers[type];
            var len = _h.length;
            for (var i = 0; i < len;) {
                switch (argLen) {
                case 1:
                    _h[i]['h'].call(_h[i]['ctx']);
                    break;
                case 2:
                    _h[i]['h'].call(_h[i]['ctx'], args[1]);
                    break;
                case 3:
                    _h[i]['h'].call(_h[i]['ctx'], args[1], args[2]);
                    break;
                default:
                    _h[i]['h'].apply(_h[i]['ctx'], args);
                    break;
                }
                if (_h[i]['one']) {
                    _h.splice(i, 1);
                    len--;
                } else {
                    i++;
                }
            }
        }
        return this;
    };
    Eventful.prototype.dispatchWithContext = function (type) {
        if (this._handlers[type]) {
            var args = arguments;
            var argLen = args.length;
            if (argLen > 4) {
                args = Array.prototype.slice.call(args, 1, args.length - 1);
            }
            var ctx = args[args.length - 1];
            var _h = this._handlers[type];
            var len = _h.length;
            for (var i = 0; i < len;) {
                switch (argLen) {
                case 1:
                    _h[i]['h'].call(ctx);
                    break;
                case 2:
                    _h[i]['h'].call(ctx, args[1]);
                    break;
                case 3:
                    _h[i]['h'].call(ctx, args[1], args[2]);
                    break;
                default:
                    _h[i]['h'].apply(ctx, args);
                    break;
                }
                if (_h[i]['one']) {
                    _h.splice(i, 1);
                    len--;
                } else {
                    i++;
                }
            }
        }
        return this;
    };
    return Eventful;
});