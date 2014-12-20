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

    var loginError = $('#login-error');

    function init () {
        picScroll.init();
        bindLogin();
    }

    function bindLogin() {

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
                var value = $.trim($(this).val());
                !value && $(this).next().removeClass('hidden');
            }
        });


        $('.login-fastlogin').click(function (e) {
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

        loginCheck.on('success', function (data) {
            if(data && data.bizError) {
                $('#login-error').html(data.statusInfo);
            }
            else {
                window.location.href = 'http://www.baidu.com';
            }
        })

    }






    return {
        init:init
    };
});
