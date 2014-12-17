/**
 * @ignore
 * @file XEmitter.js
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-11-18
 */

define(function (require) {

    var Emitter = require('saber-emitter');
    var $ = require('jquery');

    Emitter.mixin = function (target) {
        target = $.type(target) === 'function' && target.prototype;
        var proto = Emitter.prototype;

        for (var p in proto) {
            if (proto.hasOwnProperty(p)) {
                target[p] = proto[p];
            }
        }
    };

    function XEmitter() {

    }

    XEmitter.prototype = {
        constructor: XEmitter
    };

    Emitter.mixin(XEmitter);

    XEmitter.mixin = Emitter.mixin;

    return XEmitter;
});
