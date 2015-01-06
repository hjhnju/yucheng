define('loan/loanform/index', [
    'require',
    'jquery',
    'common/Remoter'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var loanSubmit = new Remoter('LOAN_REQUEST');
    function init() {
        bindEvent();
    }
    function bindEvent() {
        var ipt = $('.loan-form-right-ipt');
        var value = ipt.val();
        var error = $('.form-error');
        ipt.on({
            focus: function () {
                error.html('');
            },
            blur: function () {
                var meVal = $(this).val();
                var text = $(this).parent().prev().text();
                if (!meVal) {
                    error.html(text + '\u5185\u5BB9\u4E0D\u80FD\u4E3A\u7A7A');
                }
            }
        });
        $('.form-con-right-link').click(function () {
            if (!value) {
                error.html('\u5E26*\u7684\u9009\u9879\u5185\u5BB9\u4E0D\u80FD\u4E3A\u7A7A');
                return;
            }
            loanSubmit.remote({});
        });
    }
    return { init: init };
});