/*! 2014 Baidu Inc. All Rights Reserved */
define('setting/phone/index', [
    'require',
    'jquery',
    'common/Remoter',
    'etpl',
    './phone.tpl'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var checkPhone = new Remoter('EDIT_CHECKPHONE_CHECK');
    var sendsmscode = new Remoter('REGIST_SENDSMSCODE_CHECK');
    var phoneSubmite = new Remoter('EDIT_PHONE_SUBMITE');
    var getSmscode = new Remoter('EDIT_GETSMSCODE_CHECK');
    var getSmscode2nd = new Remoter('EDIT_GETSMSCODE2ND_CHECK');
    var etpl = require('etpl');
    var tpl = require('./phone.tpl');
    function init() {
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
        box_id.delegate('#login-phonenew', 'blur', function () {
            var value = $(this).val();
            if (!value) {
                $(this).parent().addClass('current');
                $('#login-phonenew-error').html('\u624B\u673A\u53F7\u7801\u4E0D\u80FD\u4E3A\u7A7A');
                return;
            }
            $(this).addClass('phone-current');
            checkPhone.remote({ oldphone: value });
        });
        checkPhone.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                $('#login-phonenew-error').html('<span class="username-error-span"></span>');
            }
        });
        box_id.delegate('#sendsmscode', 'click', function () {
            var value = $('#login-phonenew').val();
            if (value) {
                getSmscode.remote({ newphone: value });
            }
        });
        getSmscode.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                var timer;
                var value = 300;
                var wait = $('#testing-wait');
                setInterval(function () {
                    wait.text('300\u79D2\u540E\u91CD\u65B0\u53D1\u9001');
                    wait.addClass('show');
                    wait.text(--value + '\u79D2\u540E\u91CD\u65B0\u53D1\u9001');
                    if (value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                    }
                }, 1000);
            }
        });
        box_id.delegate('#login-test', 'blur', function () {
            var value = $(this).val();
            var error = $('#username-error-error');
            if (!value) {
                $(this).parent().addClass('current');
                error.html('\u9A8C\u8BC1\u7801\u4E0D\u6B63\u786E');
                return;
            }
            error.html('<span class="username-error-span"></span>');
        });
        box_id.delegate('#confirm', 'click', function (e) {
            e.preventDefault();
            !$('#login-phonenew').hasClass('phone-current') && $('.login-input').trigger('blur');
            var errors = $('.login-username.current');
            if (errors.length) {
                return;
            }
            phoneSubmite.remote({ oldPhone: phone_id.val() });
        });
        phoneSubmite.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                $('#checkphone').html(etpl.render('list2nd'));
            }
        });
        box_id.delegate('#confirm2nd', 'click', function (e) {
            e.preventDefault();
            !$('#confirm2nd').hasClass('phone-current') && $('.login-input').trigger('blur');
            var errors = $('.login-username.current');
            if (errors.length) {
                return;
            }
            getSmscode2nd.remote({ newPhone: phone_id.val() });
        });
        getSmscode2nd.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                $('#checkphone').html(etpl.render('list3th', { user: data }));
            }
        });
    }
    return { init: init };
});