/**
 * @ignore
 * @file global
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function () {
    var $ = require('jquery');

    /**
     * 全局缓存
     * @type {Object}
     */
    var _cache = {};

    var cache = {
        set: function (key, val) {
            _cache[key] = val;
            return this;
        },
        get: function (key) {
            return _cache[key];
        },
        clear: function () {
            _cache = {};
            return this;
        },
        remove: function (key) {
            if (key) {
                delete _cache[key];
            }
            return this;
        }
    };

    function init() {

        // 非必要模快 可后載入
        $(document).ready(function () {

        });

        // --! 依赖 没法搞 只能放在window上了
        var global = window.GLOBAL || {};

        for (var key in global) {
            cache.set(key, global[key]);
        }

        return cache;

    }

    return init();
});
