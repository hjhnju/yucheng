/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function (require) {

    var $ = require('jquery');
    var picScroll = require('../common/picScroll');
    var Remoter = require('common/Remoter');
    var loginCheck = new Remoter('LOGIN_INDEX_CHECK');
    var imgcodeCheck = new Remoter('LOGIN_IMGCODE_CHECK');
    var imgcodeGet = new Remoter('LOGIN_IMGCODE_ADD');

    var loginError = $('#login-error');
    var imgUrl = $('#login-img-url');

    function init () {
        picScroll.init();
        bindEvents();
        bindCallback();
    }

    function bindEvents() {

        // 控制placeHolder
        $('.login .login-input').on({
            focus: function () {
                var parent = $(this).parent();
                var error = parent.children('.username-error');

                parent.removeClass('current');
                error.html('');
                $(this).next().addClass('hidden');
            },
            blur: function () {
                var value = $.trim($(this).val());
                !value && $(this).next().removeClass('hidden');
            }
        });

        // 验证码
        $('#login-testing').blur(function () {
            var value = $.trim($(this).val());

            if (!value) {
                $(this).parent().addClass('current');
                $('#login-testing-error').html('验证码不能为空');
                return;
            }

            imgcodeCheck.remove({
                imgcode: value
            });
        });

        // 获取图片验证码
        imgUrl.click(function (e) {
            e.preventDefault();
            imgcodeGet.remote();
        });

        // 登录按钮
        $('.login .login-fastlogin').click(function (e) {
            e.preventDefault();

            var user = $.trim($('#login-user').val());
            var pwd = $.trim($('#login-pwd').val());

            if(!user || !pwd) {
                loginError.html('用户名或密码不能为空');
            }
            else {
                loginCheck.remote({
                    name: user,
                    passwd: pwd
                })
            }
        });
    }

    function bindCallback() {
        // loginCheckCb
        loginCheck.on('success', function (data) {
            if (data.imgCode) {
                var imgBox = $('.login-display-none:eq(0)');
                imgBox.removeClass('login-display-none');
                imgUrl.attr('src', data.data.url);
            }
            else if(data.bizError) {
                $('#login-error').html(data.statusInfo);
            }
            else {
                window.location.href = 'http://www.baidu.com';
            }
        });

        // imgcodeGetCb
        imgcodeGet.on('success', function (data) {
            if (data.bizError) {
                alert(data.statusInfo);
            }
            else {
                imgUrl.attr('src', data.url);
            }
        });

        // imgcodeCheckCb
        imgcodeCheck.on('success', function (data) {
            if (data.bizError) {
                alert(data.statusInfo);
            }
            else {
                $('#login-testing-error').html('<span class="username-error-span"></span>');
            }
        });
    }

    return {
        init:init
    };
});
