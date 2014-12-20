define('setting/regist/index', function (require) {
    var $ = require('jquery');
    var picScroll = require('../common/picScroll');
    var Remoter = require('common/Remoter');
    var checkName = new Remoter('REGIST_CHECKNAME_CHECK');
    var checkphone = new Remoter('REGIST_CHECKPHONE_CHECK');
    var sendsmscode = new Remoter('REGIST_SENDSMSCODE_CHECK');
    var checkInviter = new Remoter('REGIST_CHECKINVITER_CHECK');
    var checksmscode = new Remoter('REGIST_CHECKSMSCODE_CHECK');
    var registSubmit = new Remoter('REGIST_INDEX_CHECK');
    var testPwd = /^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/;
    var CORRECT = '<span class="username-error-span"></span>';
    var loginInput = {
            loginUser: $('#regist-user'),
            loginPwd: $('#regist-pwd'),
            loginPhone: $('#regist-phone'),
            loginTest: $('#regist-testing'),
            loginTuiJian: $('#regist-tuijian')
        };
    var error = {
            userError: $('#regist-user-error'),
            phoneError: $('#regist-phone-error'),
            pwdError: $('#regist-pwd-error'),
            testError: $('#regist-testing-error'),
            tuiJianError: $('#regist-tuijian-error')
        };
    function init() {
        picScroll.init();
        bindEvents();
        callBack();
    }
    function bindEvents() {
        $('.regist .login-input').on({
            focus: function () {
                var parent = $(this).parent();
                var error = parent.children('.username-error');
                parent.removeClass('current');
                error.html('');
                $(this).next().addClass('hidden');
            },
            blur: function () {
                var parent = $(this).parent();
                var value = $.trim($(this).val());
                var text = $(this).attr('data-text');
                var id = $(this).attr('id');
                if (!value) {
                    if (!$(this).hasClass('login-tuijian')) {
                        parent.addClass('current');
                        $('#' + id + '-error').html(text + '\u4E0D\u80FD\u4E3A\u7A7A');
                    }
                    $(this).next().removeClass('hidden');
                }
            }
        });
        loginInput.loginUser.blur(function () {
            var value = $.trim($(this).val());
            if (value) {
                checkName.remote({ name: value });
            }
        });
        loginInput.loginPwd.blur(function () {
            var value = $.trim($(this).val());
            if (!value) {
                return;
            }
            if (!testPwd.test(value)) {
                $(this).parent().addClass('current');
                error.pwdError.html('\u5BC6\u7801\u53EA\u80FD\u4E3A 6 - 32 \u4F4D\u6570\u5B57\uFF0C\u5B57\u6BCD\u53CA\u5E38\u7528\u7B26\u53F7\u7EC4\u6210');
                return;
            }
            error.pwdError.html(CORRECT);
        });
        loginInput.loginPhone.blur(function () {
            var value = $.trim($(this).val());
            if (value) {
                checkphone.remote({ phone: value });
            }
        });
        loginInput.loginTest.blur(function () {
            var value = $.trim($(this).val());
            var phone = $.trim(loginInput.loginPhone.val());
            if (!phone) {
                loginInput.loginPhone.trigger('blur');
                return;
            }
            if (value) {
                checksmscode.remote({
                    vericode: value,
                    phone: phone
                });
            }
        });
        $('.login-username-testing').click(function (e) {
            e.preventDefault();
            var value = $.trim(loginInput.loginPhone.val());
            if (value) {
                sendsmscode.remote({ phone: value });
            }
        });
        loginInput.loginTuiJian.blur(function () {
            var value = $.trim($(this).val());
            if (value) {
                checkInviter.remote({ inviter: value });
            }
        });
        $('.regist .login-fastlogin').click(function (e) {
            e.preventDefault();
            $('.regist .login-input').trigger('blur');
            var errors = $('.regist .login-username.current');
            if (!$('#tiaoyue-itp')[0].checked) {
                alert('\u8BF7\u540C\u610F\u7528\u6237\u6761\u7EA6');
                return;
            }
            if (errors.length) {
                return;
            }
            registSubmit.remote({
                name: loginInput.loginUser.val(),
                passwd: loginInput.loginPwd.val(),
                phone: loginInput.loginPhone.val(),
                inviter: loginInput.loginTuiJian.val()
            });
        });
    }
    function callBack() {
        var timer;
        checkName.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginUser.parent().addClass('current');
                error.userError.html(data.statusInfo);
            } else {
                error.userError.html(CORRECT);
            }
        });
        checkphone.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginPhone.parent().addClass('current');
                error.phoneError.html(data.statusInfo);
            } else {
                error.phoneError.html(CORRECT);
            }
        });
        checkInviter.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginTuiJian.parent().addClass('current');
                error.tuiJianError.html(data.statusInfo);
            } else {
                error.tuiJianError.html(CORRECT);
            }
        });
        sendsmscode.on('success', function (data) {
            var value = 300;
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                var wait = $('#testing-wait');
                wait.text('300\u79D2\u540E\u91CD\u65B0\u53D1\u9001');
                wait.addClass('show');
                timer = setInterval(function () {
                    wait.text(--value + '\u79D2\u540E\u91CD\u65B0\u53D1\u9001');
                    if (value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                    }
                }, 1000);
            }
        });
        checksmscode.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginTest.parent().addClass('current');
                error.testError.html(data.statusInfo);
            } else {
                error.testError.html(CORRECT);
            }
        });
        registSubmit.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                window.location.href = 'http://www.baidu.com';
            }
        });
    }
    return { init: init };
});