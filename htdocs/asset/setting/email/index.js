define('setting/email/index', [
    'require',
    'jquery',
    'common/Remoter',
    'common/config',
    'etpl',
    './email.tpl'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var config = require('common/config');
    var emailConfirm = new Remoter('EDIT_EMAILCONFIRM');
    var etpl = require('etpl');
    var tpl = require('./email.tpl');
    var emailVal;
    var IMGURL = config.URL.IMG_GET + 'email';
    function init() {
        changeEmail();
        etpl.compile(tpl);
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
            $(this).attr('src', IMGURL);
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
                var value = 6;
                $('#checkemial').html(etpl.render('list2nd', { email: emailVal }));
                timer = setInterval(function () {
                    $('#time-span').text(--value + '\u79D2\u540E\u81EA\u52A8\u8DF3\u8F6C');
                    if (value === 0) {
                        clearInterval(timer);
                        window.location.href = '/account/overview/index';
                    }
                }, 1000);
            }
        });
    }
    return { init: init };
});