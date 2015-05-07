define('common/global', ['jquery'], function () {
    var $ = require('jquery');
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
        $(document).ready(function () {
        });
        var global = window.GLOBAL || {};
        for (var key in global) {
            cache.set(key, global[key]);
        }
        return cache;
    }
    return init();
});