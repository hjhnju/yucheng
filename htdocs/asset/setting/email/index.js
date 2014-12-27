/*! 2014 Baidu Inc. All Rights Reserved */
define('setting/email/index', function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var emailConfirm = new Remoter('EDIT_EMAILCONFIRM');
    var etpl = require('etpl');
    var tpl = require('./email.tpl');
    function init() {
        changeEmail();
        etpl.compile(tpl);
    }
    function changeEmail() {
        $('.login-input').on({
            focus: function () {
                var parent = $(this).parent();
                $(this).next().addClass('hidden');
                parent.removeClass('current');
                parent.find($('.username-error')).html('');
            },
            blur: function () {
                var value = $(this).val();
                if (!value) {
                    $(this).next().removeClass('hidden');
                    $(this).parent().addClass('current');
                    $(this).parent().find('.username-error').html('\u5185\u5BB9\u4E0D\u80FD\u4E3A\u7A7A');
                    return;
                }
            }
        });
        $('#confirm').click(function () {
            $('.login-input').trigger('blur');
            var emailVal = $('#login-email').val();
            var errors = $('.login-username.current');
            var smscodeVal = $('#login-testing').val();
            if (errors.length) {
                return;
            }
            emailConfirm.remote({
                email: emailVal,
                smscode: smscodeVal
            });
        });
        emailConfirm.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                $('#checkemial').html(etpl.render('list2nd', {}));
            }
        });
    }
    return { init: init };
});