/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function (require) {

    var $ = require('jquery');
    var picScroll = require('setlogin/common/picScroll');
    var Remoter = require('common/Remoter');
    var Index = new Remoter('LOGIN_INDEX');

    function init () {

       picScroll.init();
        bindLogin();


    }

    function bindLogin() {

        // 控制placeHolder
        $('.login-input').on({
            focus: function () {
                var parent = $(this).parent();
                var Error = parent.children('.username-error');

                parent.removeClass('current');
                Error.html('');
                $(this).next().addClass('hidden');
            },
            blur: function () {

                var value = $.trim($(this).val());

                !value && $(this).next().removeClass('hidden');

            }
        });


        $('.login-fastlogin').click(function (data) {

            if(!$('#login-user').val() || !$('#login-pwd').val()) {
                alert('用户名或者密码不能为空');
            }
            else {
                Index.remote({
                    name: $('#login-user').val(),
                    passwd: $('#login-pwd').val()
                })
            }
        });
        Index.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                alert('注册成功');
            }
        })

    }






    return {
        init:init
    };
});
