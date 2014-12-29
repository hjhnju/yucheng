define('setting/phone/index', [
    'require',
    'jquery',
    'common/Remoter',
    'etpl',
    './phone.tpl'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var phoneSubmite = new Remoter('EDIT_PHONE_SUBMITE');
    var phoneSubmite2nd = new Remoter('EDIT_PHONE_SUBMITE');
    var getSmscode = new Remoter('REGIST_SENDSMSCODE_CHECK');
    var etpl = require('etpl');
    var tpl = require('./phone.tpl');
    var testPhone = /\d{11}/g;
    function init(e) {
        e.preventDefault();
        changePhone();
        etpl.compile(tpl);
    }
    function changePhone() {
        var box_id = $('#checkphone');
        var phone_id = $('#login-phonenew');
        box_id.delegate('.login-input', 'focus', function () {
            var parent = $(this).parent();
            $(this).next().addClass('hidden');
            parent.removeClass('current');
            $('#login-phonenew-error').html('');
            $('#username-error-error').html('');
        });
        box_id.delegate('.login-input', 'blur', function () {
            var value = $.trim($(this).val());
            !value && $(this).next().removeClass('hidden');
        });
        box_id.delegate('#sendsmscode', 'click', function () {
            var value = +$('#login-phonenew').val();
            if (!testPhone.test(value)) {
                $('.error').html('\u624B\u673A\u53F7\u7801\u683C\u5F0F\u4E0D\u6B63\u786E');
                return;
            }
            if (value) {
                $('.error').html('');
                getSmscode.remote({
                    phone: value,
                    type: 3
                });
            }
        });
        getSmscode.on('success', function (data) {
            var error = $('#login-phonenew-error');
            if (data && data.bizError) {
                error.parent().addClass('current');
                error.html(data.statusInfo);
            } else {
                var timer;
                var value = 60;
                var wait = $('#testing-wait');
                setInterval(function () {
                    wait.text('60\u79D2\u540E\u91CD\u65B0\u53D1\u9001');
                    wait.addClass('show');
                    wait.text(--value + '\u79D2\u540E\u91CD\u65B0\u53D1\u9001');
                    if (value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                    }
                }, 1000);
            }
        });
        box_id.delegate('#confirm', 'click', function (e) {
            e.preventDefault();
            var phonenew = $('#login-phonenew').val();
            var test = $('#login-test').val();
            if (!phonenew || !test) {
                $('.error').html('\u624B\u673A\u6216\u9A8C\u8BC1\u7801\u4E0D\u80FD\u4E3A\u7A7A');
                return;
            }
            phoneSubmite.remote({
                oldPhone: phonenew,
                vericode: test,
                type: 3
            });
        });
        phoneSubmite.on('success', function (data) {
            if (data && data.bizError) {
                $('.error').html(data.statusInfo);
            } else {
                $('#checkphone').html(etpl.render('list2nd'));
            }
        });
        box_id.delegate('#confirm2nd', 'click', function (e) {
            e.preventDefault();
            var phonenew = $('#login-phonenew').val();
            var test = $('#login-test').val();
            if (!phonenew || !test) {
                $('.error').html('\u624B\u673A\u6216\u9A8C\u8BC1\u7801\u4E0D\u80FD\u4E3A\u7A7A');
                return;
            }
            phoneSubmite2nd.remote({
                newPhone: phonenew,
                vericode: test,
                type: 3
            });
        });
        phoneSubmite2nd.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                var value = 8;
                var timer;
                $('#checkphone').html(etpl.render('list3th', { user: data }));
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