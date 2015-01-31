define('echarts/component', function () {
    var self = {};
    var _componentLibrary = {};
    self.define = function (name, clazz) {
        _componentLibrary[name] = clazz;
        return self;
    };
    self.get = function (name) {
        return _componentLibrary[name];
    };
    return self;
});