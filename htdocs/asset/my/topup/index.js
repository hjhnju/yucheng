define('my/topup/index', [
    'require',
    'jquery',
    'common/header',
    'common/Remoter'
], function (require) {
    var $ = require('jquery');
    var header = require('common/header');
    var Remoter = require('common/Remoter');
    function init() {
        header.init();
        bindEvent();
    }
    function bindEvent() {
        $('.topup-select-con-box').click(function () {
            $('.topup-select-con-box').removeClass('current');
            $(this).addClass('current');
            $(this).find('.topup-select-ipt').attr('checked', 'check');
        });
        $('#topup-select-ipt').click(function () {
            var value = $('#box-ipt').val();
            if (!value) {
                $('#topup-error').html('\u5145\u503C\u91D1\u989D\u4E0D\u80FD\u4E3A\u7A7A');
                return;
            }
        });
    }
    return { init: init };
});