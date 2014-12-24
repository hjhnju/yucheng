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
    var phoneSubmite = new Renmoter('EDIT_PHONE_SUBMITE');
    var etpl = require('etpl');
    var tpl = require('./phone.tpl');


    function init (){
        changePhone();
        etpl.compile(tpl);

    }

    function changePhone() {

        var box_id = $('#checkphone');

        var phone_id = $('#login-phonenew');

        var error = {
            phonenewError: $('#login-phonenew-error'),
            usernameError: $('#username-error-error')
        };


        box_id.delegate('.login-input', 'focus', function () {
            var parent = $(this).parent();

            $(this).next().addClass('hidden');
            parent.removeClass('current');
            $(this).parent().find($('#login-phonenew-error')).html('');
        });
        box_id.delegate('.login-input', 'blur', function () {
            var value = $.trim($(this).val());

            !value && $(this).next().removeClass('hidden');
        });




        // 验证手机
        box_id.delegate('#login-phonenew', 'blur', function () {

                var value = $(this).val();
                if(!value) {
                    $(this).parent().addClass('current');
                    $('#login-phonenew-error').html('手机号码不能为空');
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
                $('#login-phonenew-error').html('<span class="username-error-span"></span>');
            }
        });

        // 点击下一步
        box_id.delegate('#confirm','click', function (e) {
            e.preventDefault();
            $('.login-input').trigger('blur');

            var errors = $('.login-username.current');

            if(errors.length) {
                return;
            }
            phoneSubmite.remote({
                oldPhone: phone_id.val()
            });
            $("#checkphone").html(etpl.render('list2nd', {

            }));
        });

        //phoneSubmiteCb
        phoneSubmite.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                $('.login-username').removeClass('current');
                $('#login-phonenew-error').html('');
            }
        })

        box_id.delegate('#confirm2nd','click', function (e) {
            e.preventDefault();
            $('.login-input').trigger('blur');

            var errors = $('.login-username.current');

            if(errors.length) {
                return;
            }
            phoneSubmite.remote({
                newPhone: phone_id.val()
            });
            $("#checkphone").html(etpl.render('list3th', {

            }));
        });

        //检查是否获取验证码
        //$('#sendsmscode').click(function () {
        //    var EditIpt = $('#login-test');
        //    var value = $.trim(EditIpt.val());
        //    //console.log(value)
        //    if(value) {
        //        sendsmscode.remote({
        //            phone: value
        //        })
        //    }
        //
        //});

        //sendsmscodeCb
        //sendsmscode.on('success', function (data) {
        //    var timer;
        //    var value = 300;
        //    if(data && data.bizError) {
        //        alert(data.statusInfo);
        //    }
        //    else {
        //        var wait = $('#testing-wait');
        //        wait.text('300秒后重新发送');
        //        wait.addClass('show');
        //
        //        timer = setInterval(function () {
        //            wait.text(--value + "秒后重新发送");
        //            if (value < 0) {
        //                clearInterval(timer);
        //                wait.removeClass('show');
        //            }
        //        },1000);
        //
        //    }
        //});
    }



    return {init:init};
});
