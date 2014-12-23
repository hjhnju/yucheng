/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var Renmoter = require('common/Remoter');
    var checkPhone = new Renmoter('EDIT_CHECKPHONE_CHECK');
    var sendsmscode = new Renmoter('REGIST_SENDSMSCODE_CHECK');


    function init (){
        changePhone();

    }

    function changePhone() {

        var error = {
            phonenewError: $('#login-phonenew-error'),
            usernameError: $('#username-error-error')
        };

        $('.login-input').on({

            focus: function () {
                var parent = $(this).parent();

                $(this).next().addClass('hidden');
                $(this).parent().removeClass('current');
                error.phonenewError.html('');
            },
            blur: function () {

                var value = $.trim($(this).val());

                !value && $(this).next().removeClass('hidden');
            }
        });

        //验证手机
        $('#login-phonenew').blur(function () {

            var value = $(this).val();
            if(!value) {
                $(this).parent().addClass('current');
                error.phonenewError.html('手机号码不能为空');
                return;
            }
            else {
                checkPhone.remote({
                   oldpwd: value
                });
            }

        });

        // checkPhoneCb
        checkPhone.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                error.phonenewError.html('<span class="username-error-span"></span>');
            }
        });

        //检查是否获取验证码
        $('#sendsmscode').click(function () {
            var EditIpt = $('#login-test');
            var value = $.trim(EditIpt.val());
            //console.log(value)
            if(value) {
                sendsmscode.remote({
                    phone: value
                })
            }

        });

        //sendsmscodeCb
        sendsmscode.on('success', function (data) {
            var timer;
            var value = 300;
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                var wait = $('#testing-wait');
                wait.text('300秒后重新发送');
                wait.addClass('show');

                timer = setInterval(function () {
                    wait.text(--value + "秒后重新发送");
                    if (value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                    }
                },1000);

            }
        });
    }

    return {init:init};
});
