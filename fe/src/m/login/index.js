/**
 * @ignore
 * @file index.js
 * @author fanyy 
 * @time 2015-04-06
 */

define(function (require) {

    var $ = require('jquery');  
    var config = require('common/config');
    var Remoter = require('common/Remoter'); 

    var loginCheck = new Remoter('LOGIN_INDEX_CHECK');
    var imgcodeCheck = new Remoter('LOGIN_IMGCODE_CHECK');

    var IMGURL = config.URL.IMG_GET;

    var loginError = $('#login-error');
    var imgUrl = $('#login-img-url');
    var status = 0;
    var loginType;

    function init (type) {
        loginType = type || 'login';  
        bindEvents();
        bindCallback();
    }

    function bindEvents() {

        // 控制placeHolder
        $('.login .input').on({
            focus: function () { 
                loginError.html("\u0020\u0020"); 
            },
            blur: function () {
                var value = $.trim($(this).val());
                var text = $(this).attr('data-text'); 
                !value && loginError.html(text + '不能为空');
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
            $(this).attr('src', IMGURL + loginType);
        });





        // 登录按钮
        $('.login .login-fastlogin').click(function (e) {
            e.preventDefault();

            var user = $.trim($('#login-phone').val());
            var pwd = $.trim($('#login-pwd').val());
            var volid = $.trim($('#login-testing').val());


            if(!user || !pwd) {
                loginError.html('用户名或密码不能为空');
                return;
            }

            if (!$('.testing-box').hasClass('none')) {
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
                var imgBox = $('.testing-box');
                imgBox.removeClass('none');
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
        init: init
    };
});