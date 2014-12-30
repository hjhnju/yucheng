define('my/safe/index', function (require) {
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
            $('.fen').html('\u68C0\u6D4B\u4E2D...');
        });
        secureDergee.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                $('.finish-main-grade-box-internal').css('width', data.score + '%');
                $('.fen').html(data.score + '\u5206');
            }
        });
    }
    return { init: init };
});