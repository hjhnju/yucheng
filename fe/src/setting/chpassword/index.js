/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var chpwdSubmite = new Remoter('EDIT_CHPWD_SUBMITE');
    var testPwd = /^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/;

    function init (){
        bingEvent();

    }

    function bingEvent() {

        $('.chpwd-input').on({

            focus: function () {
                $(this).removeClass('current');
                $(this).next().html('');
            },
            blur: function () {
                var me = $(this);
                var value = $(this).val();

                if(!value) {
                    me.addClass('current');
                    $(this).next().html('密码不能为空');

                    return;
                }
                if(!testPwd.test(value)) {
                    me.addClass('current');
                    $(this).next().html('密码不符合要求');

                    return;
                }
                $(this).next().html('');
            }
        });

        // 确认新密码
        $('#confirm-new-ipt').blur(function () {

            var newval = +$('#new-ipt').val();
            var me = +$(this).val();

            if(newval !== me) {
                $(this).addClass('current');
                $('#repeat-ipt-error').html('两次密码不一致');

            }
        });

        // 点击发送请求
        $('.chpwd-link').click(function () {

            var error = $('.chpwd-input.current');
            $('.chpwd-input').trigger('blur');


            if(error.length) {

                return;
            }

            chpwdSubmite.remote({
                oldpwd: $('#old-ipt').val(),
                newpwd: $('#new-ipt').val()
            });
        });
    }

    return {
        init:init
    };
});

