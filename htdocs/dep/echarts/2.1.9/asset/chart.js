/*! 2014 Baidu Inc. All Rights Reserved */
define('echarts/chart', [], function () {
    var self = {};
    var _chartLibrary = {};
    self.define = function (name, clazz) {
        _chartLibrary[name] = clazz;
        return self;
    };
    self.get = function (name) {
        return _chartLibrary[name];
    };
    return self;
});