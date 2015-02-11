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
    var phoneSubmite2nd = new Remoter('EDIT_PHONE_SUBMITE2ND');
    var getSmscode = new Remoter('REGIST_SENDSMSCODE_CHECK');
    var etpl = require('etpl');
    var tpl = require('./phone.tpl');
    var header = require('common/header');

    var testPhone = /\d{11}/g;


    function init (){
        changePhone();
        etpl.compile(tpl);
        header.init();
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



        //获取验证码
        box_id.delegate('#sendsmscode', 'click', function () {
            var value = +$('#login-phonenew').val();

            if (!testPhone.test(value)) {
                $('.error').html('手机号码格式不正确');
                return;
            }

            if(value) {
                $('.error').html('');
                getSmscode.remote({
                    phone: value,
                    type: 3
                });
            }

        });

        //getSmscodeCb
        getSmscode.on('success', function (data) {
            var error = $('#login-phonenew-error');
            if(data && data.bizError) {
                error.parent().addClass('current');
                error.html(data.statusInfo);
                //alert(data.statusInfo);
            }
            else {
                var timer;
                var value = 60;
                var wait = $('#testing-wait');

                setInterval(function () {
                    wait.text('60秒后重新发送');
                    wait.addClass('show');

                    wait.text(--value + '秒后重新发送');
                    if(value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                    }

                },1000);
            }
        });

        // 点击下一步
        box_id.delegate('#confirm','click', function (e) {
            e.preventDefault();
            var phonenew = $('#login-phonenew').val();
            var test = $('#login-test').val();
            if(!phonenew || !test) {
                $('.error').html('手机或验证码不能为空');

                return;
            }

            phoneSubmite.remote({
                oldPhone: phonenew,
                vericode: test,
                type: 3
            });

        });

        //phoneSubmiteCb
        phoneSubmite.on('success', function (data) {
            if(data && data.bizError) {
                $('.error').html(data.statusInfo);
            }
            else {

                $("#checkphone").html(etpl.render('list2nd'));
            }
        });



        //第二次点击确定
        box_id.delegate('#confirm2nd','click', function (e) {
            e.preventDefault();
            var phonenew = $('#login-phonenew').val();
            var test = $('#login-test').val();
            if(!phonenew || !test) {
                $('.error').html('手机或验证码不能为空');

                return;
            }

            phoneSubmite2nd.remote({
                newPhone: phonenew,
                vericode: test,
                type: 3
            });

        });

        phoneSubmite2nd.on('success', function (data) {
            if(data && data.bizError) {
                $('.error').html(data.statusInfo);
            }
            else {
                var value = 8;
                var timer;

                $("#checkphone").html(etpl.render('list3th', {
                    user: data
                }));


                timer = setInterval(function () {

                    $('#time-span').text(--value + '秒后自动跳转');
                    if(value === 0) {
                        clearInterval(timer);
                        window.location.href = '/account/overview/index';
                    }

                },1000);
            }
        })

    }



    return {init:init};
});
