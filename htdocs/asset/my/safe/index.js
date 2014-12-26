/*! 2014 Baidu Inc. All Rights Reserved */
define('my/safe/index', function (require) {
    var $ = require('jquery');
    var header = require('common/header');
    var Remoter = require('common/Remoter');
    function init() {
        header.init();
        bindEvent();
    }
    function bindEvent() {
        $('.testing-link').click(function () {
        });
    }
    return { init: init };
});