/*! 2015 Baidu Inc. All Rights Reserved */
define('zrender/tool/guid', function () {
    var idStart = 2311;
    return function () {
        return 'zrender__' + idStart++;
    };
});