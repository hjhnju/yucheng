/*! 2014 Baidu Inc. All Rights Reserved */
define('home/index', function (require) {
    var $ = require('jquery');
    function init() {
        var header = require('common/header');
        header.init();
    }
    return { init: init };
});