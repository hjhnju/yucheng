/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var phoneSubmite = new Remoter('EDIT_PHONE_SUBMITE');
    var phoneSubmite2nd = new Remoter('EDIT_PHONE_SUBMITE');
    var getSmscode = new Remoter('EDIT_GETSMSCODE_CHECK');
    var etpl = require('etpl');
    var tpl = require('./phone.tpl');


    function init (){
        changePhone();
        etpl.compile(tpl);

    }

    function changePhone() {

        var box_id = $('#checkphone');

        var phone_id = $('#login-phonenew');

        box_id.delegate('.login-input', 'focus', function () {
            var parent = $(this).parent();

            $(this).next().addClass('hidden');
            parent.removeClass('current');
            $('#login-phonenew-error').html('');
            $('#username-error-error').html('');
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

        });


        //获取验证码
        box_id.delegate('#sendsmscode', 'click', function () {
            var value = $('#login-phonenew').val();

            if(value) {
                getSmscode.remote({
                    newphone: value
                })
            }

        });

        //getSmscodeCb
        getSmscode.on('success', function (data) {
            var error = $('#login-phonenew-error')
            if(data && data.bizError) {
                error.parent().addClass('current');
                error.html(data.statusInfo);
                //alert(data.statusInfo);
            }
            else {
                var timer;
                var value = 300;
                var wait = $('#testing-wait');

                setInterval(function () {
                    wait.text('300秒后重新发送');
                    wait.addClass('show');

                    wait.text(--value + '秒后重新发送');
                    if(value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                    }

                },1000);
            }
        });

        //检查验证码
        box_id.delegate('#login-test', 'blur', function () {

            var value = $(this).val();
            var error = $('#username-error-error');
            if(!value) {
                $(this).parent().addClass('current');
                error.html('验证码不正确');
                return;
            }

            error.html('<span class="username-error-span"></span>');

        });

        // 点击下一步
        box_id.delegate('#confirm','click', function (e) {
            e.preventDefault();
            $('.login-input').trigger('blur');
            //if('#login-phonenew'.hasClass('phone-current')) {
            //
            //}

            var errors = $('.login-username.current');

            if(errors.length) {
                return;
            }
            phoneSubmite.remote({
                oldPhone: $('#login-phonenew').val()
            });

        });

        //phoneSubmiteCb
        phoneSubmite.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                $("#checkphone").html(etpl.render('list2nd'));
            }
        });



        //第二次点击确定
        box_id.delegate('#confirm2nd','click', function (e) {
            e.preventDefault();
            $('.login-input').trigger('blur');

            var errors = $('.login-username.current');

            if(errors.length) {
                return;
            }
            phoneSubmite2nd.remote({
                newPhone: $('#login-phonenew2nd').val()
            });

        });

        phoneSubmite2nd.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                $("#checkphone").html(etpl.render('list3th', {
                    user: data
                }));
            }
        })

    }



    return {init:init};
});
