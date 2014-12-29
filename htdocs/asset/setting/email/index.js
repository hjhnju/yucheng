/*! 2014 Baidu Inc. All Rights Reserved */
define('setting/email/index', [
    'require',
    'jquery',
    'common/Remoter',
    'etpl',
    './email.tpl'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var emailConfirm = new Remoter('EDIT_EMAILCONFIRM');
    var getSmscode = new Remoter('LOGIN_IMGCODE_ADD');
    var sendSmscode = new Remoter('LOGIN_IMGCODE_CHECK');
    var etpl = require('etpl');
    var tpl = require('./email.tpl');
    var emailVal;
    function init() {
        changeEmail();
        etpl.compile(tpl);
        getSmscode.remote({ type: 4 });
    }
    function changeEmail() {
        $('.login-input').on({
            focus: function () {
                var parent = $(this).parent();
                $(this).next().addClass('hidden');
            },
            blur: function () {
                var value = $(this).val();
                !value && $(this).next().removeClass('hidden');
            }
        });
        $('#email-img').click(function () {
            getSmscode.remote({ type: 4 });
        });
        getSmscode.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                $('#email-img').attr('src', data.url);
            }
        });
        $('#confirm').click(function () {
            emailVal = $('#login-email').val();
            var smscodeVal = $('#login-testing').val();
            emailConfirm.remote({
                email: emailVal,
                smscode: smscodeVal,
                type: 4
            });
        });
        emailConfirm.on('success', function (data) {
            if (data && data.bizError) {
                $('.error').html(data.statusInfo);
            } else {
                var timer;
                var value = 8;
                $('#checkemial').html(etpl.render('list2nd', { email: emailVal }));
                timer = setInterval(function () {
                    $('#time-span').text(--value + '\u79D2\u540E\u81EA\u52A8\u8DF3\u8F6C');
                    if (value === 0) {
                        clearInterval(timer);
                        window.location.href = '/account/views/overview/index';
                    }
                }, 1000);
            }
        });
    }
    return { init: init };
});