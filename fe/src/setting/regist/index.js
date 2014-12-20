/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function (require) {

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

    /**
     * input集合
     * @type {Object}
     */
    var loginInput = {
        loginUser: $('#login-user'),
        loginPwd: $('#login-pwd'),
        loginPhone: $('#login-phone'),
        loginTest: $('#login-testing'),
        loginTuiJian: $('#login-tuijian')
    };

    /**
     * error集合
     * @type {Object}
     */
    var error = {
        userError: $('#login-user-error'),
        phoneError: $('#login-phone-error'),
        pwdError: $('#login-pwd-error'),
        testError: $('#login-testing-error'),
        tuiJianError: $('#login-tuijian-error')
    };

    function init() {
        picScroll.init();
        bindEvents();
        callBack();
    }

    function bindEvents() {
        // 控制placeHolder
        $('.login-input').on({
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
                    // 不是推荐
                    if (!$(this).hasClass('login-tuijian')) {
                        parent.addClass('current');
                        $('#' + id + '-error').html(text + '不能为空');
                    }

                    $(this).next().removeClass('hidden');
                }
            }
        });

        // 检查用户名
        loginInput.loginUser.blur(function () {
            var value = $.trim($(this).val());

            if (value) {
                checkName.remote({
                    name: value
                });
            }

        });

        // 密码格式验证
        loginInput.loginPwd.blur(function () {
            var value = $.trim($(this).val());

            if (!value) {
                return;
            }

            if (!testPwd.test(value)) {
                $(this).parent().addClass('current');
                error.pwdError.html('密码只能为 6 - 32 位数字，字母及常用符号组成');
            }
        });


        // 检查手机号
        loginInput.loginPhone.blur(function () {
            var value = $.trim($(this).val());

            if (value) {
                checkphone.remote({
                    phone: value
                });
            }

        });


        // 检查验证码
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

        // 检查是否获取验证码
        $('.login-username-testing').click(function (e) {
            e.preventDefault();

            var value = $.trim(loginInput.loginPhone.val());

            if (value) {
                sendsmscode.remote({
                    phone: value
                });
            }

        });

        // 检查推荐人
        loginInput.loginTuiJian.blur(function () {
            var value = $.trim($(this).val());

            if (value) {
                checkInviter.remote({
                    inviter: value
                });
            }

        });

        // 检查快速注册
        $('.login-fastlogin').click(function (e) {
            e.preventDefault();

            $('.login-input').trigger('blur');
            var errors = $('.login-username.current');

            if (!$('#tiaoyue-itp')[0].checked) {
                alert('请同意用户条约');
                return;
            }

            if (errors.length) {
                return;  // 跳出click方法，不往下执行
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

        // checkNameCb
        checkName.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginUser.parent().addClass('current');
                error.userError.html(data.statusInfo);
            }
            else {
                error.userError.html('<span class="username-error-span"></span>');
            }
        });

        // checkphoneCb
        checkphone.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginPhone.parent().addClass('current');
                error.phoneError.html(data.statusInfo);
            }
            else {
                error.phoneError.html('<span class="username-error-span"></span>');
            }
        });

        // checkInviterCb
        checkInviter.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginTuiJian.parent().addClass('current');
                error.tuiJianError.html(data.statusInfo);
            }
            else {
                error.tuiJianError.html('<span class="username-error-span"></span>');
            }
        });

        // sendsmscodeCb
        sendsmscode.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                var wait = $('#testing-wait');
                var value = +wait.text();
                wait.addClass('show');

                timer = setInterval(function () {

                    wait.text(value--);
                    if (value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                        wait.text('10');
                    }
                }, 1000);
            }
        });

        checksmscode.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginTest.parent().addClass('current');
                error.testError.html(data.statusInfo);
            }
            else {
                error.testError.html('<span class="username-error-span"></span>');
            }
        });

        registSubmit.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                window.location.href = 'http://www.baidu.com';
            }
        });
    }
    return {
        init: init
    };
});
