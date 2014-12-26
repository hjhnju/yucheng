/*! 2014 Baidu Inc. All Rights Reserved */
define('project/detail/index', [
    'require',
    'jquery',
    'common/Remoter'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var confirm = new Remoter('INVEST_DETAIL_CONFIRM');
    function init() {
        bindEvent();
    }
    function bindEvent() {
        var valBox = +$('#money-all').text();
        var valIpt = $('.right-top-ipt-input');
        var valMy = +$('#my-money').text();
        $('.right-top-btn-confirm').click(function () {
            confirm;
        });
    }
    return { init: init };
});