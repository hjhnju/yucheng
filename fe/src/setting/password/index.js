/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-13
 */

define(function (require) {

    var $ = require('jquery');
    var a = $('.login-username').children('.user-lable');
    var Remoter = require('common/Remoter');
    var sendsmscode = new Remoter('REGIST_SENDSMSCODE_CHECK');
    var error = $('.error');
    var submit = new Remoter('USER_REGISTAPI_MODIFYPWD');

    var statusAll = {
        'reset': 0,
        'testing': 0,
        'phone': 0,
        'user': 0
    };

    function init (){
        $('.login-input').on({

            focus: function(){
                $(this).parent().children('.user-lable').addClass('hidden');
                $(this).parent().removeClass('current');
                $(this).next().addClass('current');
                error.html('');
            },
            blur: function(){

                var val = $(this).val();

                if(!val) {
                    $(this).next().hasClass('hidden');
                    $(this).next().removeClass('hidden');
                    $(this).parent().addClass('current');
                    return;
                }


            }
        });

        // 用户名
        $('#login-user').blur(function () {
            var value = $(this).val();
            if(!value) {
                error.html('用户名不能为空');
                statusAll.user = 0;
            }
            else {
                statusAll.user = 1;
            }


        });

        // 手机
        $('#login-phone').blur(function () {
           var value = $(this).val();

            if(!value) {
                error.html('手机号不能为空');
                statusAll.phone = 0;
            }
            else {
                statusAll.phone = 1;
                $('#sendsmscode').removeClass('disable');
            }

        });
        // 检验验证码
        $('#login-testing').blur(function () {

            var value = $(this).val();

            if(!value) {
                error.html('验证码不能为空');
                statusAll.testing = 0;
            }
            else {
                statusAll.testing = 1;
            }
        });

        // 发送验证码
        $('#sendsmscode').click(function () {
            var value = $.trim($('#login-phone').val());

            if(value && !$(this).hasClass('disable')) {
                sendsmscode.remote({
                    phone: value,
                    type: 7
                })
            }
        });

        //sendsmscodeCb
        sendsmscode.on('success', function (data) {
            var value = 60;

            if(data && data.bizError) {
                error.html(data.statusInfo);
            }
            else {
                var wait = $('#testing-wait');

                wait.text('60秒后重新发送');
                wait.addClass('show');

                timer = setInterval(function () {

                    wait.text(--value + '秒后重新发送');
                    if (value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                    }

                }, 1000);
            }
        });



        // 密码不能为空
        $('#login-reset').blur(function () {
            var value = $(this).val();

            if(!value) {
                error.html('密码不能为空');
                statusAll.reset = 0;
            }
            else {
                statusAll.reset = 1;
            }
        });

        // 校验两次密码

        $('#login-affirm').blur(function () {

            var pwd = $('#login-reset').val();
            var me = $(this).val();

            if(pwd !== me) {
                error.html('两次密码不一致');
                return;
            }

        });


        $('.login-fastlogin').click(function () {
            var status = 1;

            var user = $('#login-user').val();
            var phone = $('#login-phone').val();
            var vericode = $('#login-testing').val();
            var passwd = $('#login-reset').val();
            var affirm = $('#login-affirm').val();



            for (var name in statusAll) {
                if (statusAll.hasOwnProperty(name)) {
                    if (!statusAll[name]) {
                        $('#login-' + name).trigger('blur');
                        status = 0;
                    }
                }
            }
            if(passwd !== affirm) {
                error.html('两次密码不一致');
                return;
            }
            status && submit.remote('post', {
                user: user,
                phone: phone,
                vericode: vericode,
                passwd: passwd,
                type: 7

            })
        });

        // submitCb
        submit.on('success', function (data) {
            if(data && data.bizError) {
                error.html(data.statusInfo);
            }
            else {

            }
        });

    }

    return {init:init};
});
