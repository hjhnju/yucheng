/*! 2014 Baidu Inc. All Rights Reserved */
define('my/safe/index', [
    'require',
    'jquery',
    'common/header',
    'common/Remoter'
], function (require) {
    var $ = require('jquery');
    var header = require('common/header');
    var Remoter = require('common/Remoter');
    var secureDergee = new Remoter('SECURE_DEGREE');
    function init() {
        header.init();
        bindEvent();
    }
    function bindEvent() {
        $('.finish-grade-refresh').click(function () {
            secureDergee.remote();
        });
        secureDergee.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
            }
        });
    }
    return { init: init };
});