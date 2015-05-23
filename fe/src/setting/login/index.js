/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function (require) {

    var $ = require('jquery');
    var picScroll = require('../common/picScroll');
    var header = require('common/header');
    var Remoter = require('common/Remoter');
    var config = require('common/config');
    var loginCheck = new Remoter('LOGIN_INDEX_CHECK');
    var imgcodeCheck = new Remoter('LOGIN_IMGCODE_CHECK');

    var IMGURL = config.URL.IMG_GET;

    var loginError = $('#login-error');
    var imgUrl = $('#login-img-url');
    var status = 0;
    var loginType;

    function init (type) {
        loginType = type || 'login';
       // header.init();
        picScroll.init();
        bindEvents();
        bindCallback();
    }

    function bindEvents() {

        // 控制placeHolder
        $('.login-username .login-input').on({
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

        $('#login-testing').focus(function () {
            loginError.html('');
        });
        // 验证码
        //$('#login-testing').blur(function () {
        //    var value = $.trim($(this).val());
        //
        //    if (!value) {
        //        $(this).parent().addClass('current');
        //        $('#login-testing-error').html('验证码不能为空');
        //        return;
        //    }
        //
        //    imgcodeCheck.remote({
        //        imgcode: value,
        //        type: 2
        //    });
        //});

        // 获取图片验证码
        imgUrl.click(function (e) {
            e.preventDefault();
            $(this).attr('src', IMGURL + loginType+'&r='+new Date().getTime());
        });





        // 登录按钮
        $('.login .login-fastlogin').click(function (e) {
            e.preventDefault();

            var user = $.trim($('#login-user').val());
            var pwd = $.trim($('#login-pwd').val());
            var volid = $.trim($('#login-testing').val());


            if(!user || !pwd) {
                loginError.html('用户名或密码不能为空');
                return;
            }

            if (!$('.login-username').hasClass('login-display-none')) {
                if (!volid) {
                    loginError.html('验证码不能为空');
                    return;
                }



                loginCheck.remote({
                    name: user,
                    passwd: pwd,
                    imagecode: volid,
                    type: loginType,
                    isthird: loginType === 'login' ? 0 : 1
                });
            }
            else {
                loginCheck.remote({
                    name: user,
                    passwd: pwd,
                    type: loginType,
                    isthird: loginType === 'login' ? 0 : 1
                });
            }

        });

        //登录回车控制
        $('#login-pwd').keyup(function(e) {
            if(e.keyCode === 13) {
                $('.login-fastlogin').trigger('click');
                $(this).trigger('blur');
            }
        });

            $('#login-testing').keyup(function(e) {
                if(e.keyCode === 13) {
                    $('.login-fastlogin').trigger('click');
                    $(this).trigger('blur');
                }
            })

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
                imgUrl.trigger('click');
            }
            else {
                window.location.href = '/account/overview/index';
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
