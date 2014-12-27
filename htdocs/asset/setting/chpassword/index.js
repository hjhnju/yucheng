/*! 2014 Baidu Inc. All Rights Reserved */
define('setting/chpassword/index', [
    'require',
    'jquery',
    'common/Remoter'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var chpwdSubmite = new Remoter('EDIT_CHPWD_SUBMITE');
    var testPwd = /^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/;
    function init() {
        bingEvent();
    }
    function bingEvent() {
        $('.chpwd-input').on({
            focus: function () {
                $(this).removeClass('current');
                $(this).next().html('');
            },
            blur: function () {
                var me = $(this);
                var value = $(this).val();
                if (!value) {
                    me.addClass('current');
                    $(this).next().html('\u5BC6\u7801\u4E0D\u80FD\u4E3A\u7A7A');
                    return;
                }
                if (!testPwd.test(value)) {
                    me.addClass('current');
                    $(this).next().html('\u5BC6\u7801\u4E0D\u7B26\u5408\u8981\u6C42');
                    return;
                }
                $(this).next().html('');
            }
        });
        $('#confirm-new-ipt').blur(function () {
            var newval = +$('#new-ipt').val();
            var me = +$(this).val();
            if (newval !== me) {
                $(this).addClass('current');
                $('#repeat-ipt-error').html('\u4E24\u6B21\u5BC6\u7801\u4E0D\u4E00\u81F4');
            }
        });
        $('.chpwd-link').click(function () {
            var error = $('.chpwd-input.current');
            $('.chpwd-input').trigger('blur');
            if (error.length) {
                return;
            }
            chpwdSubmite.remote({
                oldpwd: $('#old-ipt').val(),
                newpwd: $('#new-ipt').val()
            });
        });
    }
    return { init: init };
});