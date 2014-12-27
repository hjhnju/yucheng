/*! 2014 Baidu Inc. All Rights Reserved */
define('echarts/util/kwargs', [], function () {
    function kwargs(func, defaults) {
        var removeComments = new RegExp('(\\/\\*[\\w\\\'\\,\\(\\)\\s\\r\\n\\*]*\\*\\/)|(\\/\\/[\\w\\s\\\'][^\\n\\r]*$)|(<![\\-\\-\\s\\w\\>\\/]*>)', 'gim');
        var removeWhitespc = new RegExp('\\s+', 'gim');
        var matchSignature = new RegExp('function.*?\\((.*?)\\)', 'i');
        var names = func.toString().replace(removeComments, '').replace(removeWhitespc, '').match(matchSignature)[1].split(',');
        if (defaults !== Object(defaults)) {
            defaults = {};
        }
        return function () {
            var args = Array.prototype.slice.call(arguments);
            var kwargs = args[args.length - 1];
            if (kwargs && kwargs.constructor === Object) {
                args.pop();
            } else {
                kwargs = {};
            }
            for (var i = 0; i < names.length; i++) {
                var name = names[i];
                if (name in kwargs) {
                    args[i] = kwargs[name];
                } else if (name in defaults && args[i] == null) {
                    args[i] = defaults[name];
                }
            }
            return func.apply(this, args);
        };
    }
    return kwargs;
});