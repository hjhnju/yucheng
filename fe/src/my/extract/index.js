/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function (require) {

    var $ = require('jquery');
    var header = require('common/header');
    var Remoter = require('common/Remoter');
//    var withdraw = new Remoter('ACCOUNT_CASH_WITHDRAW_ADD');
    var checksmscode = new Remoter('REGIST_CHECKSMSCODE_CHECK');
    var sendsmscode = new Remoter('REGIST_SENDSMSCODE_CHECK');
    var util = require('common/util');

    function init() {
        header.init();
        bindEvent();
    }

    function bindEvent() {

        // 检查输入金额
        $('#search-ipt').on({
            focus: function () {
                $('#money-error').html('');
            },
            blur: function () {
                var value = $(this).val();

                if(!value) {
                    $('#money-error').html('输入金额不能为空');
                    $(this).addClass('current');

                    return;
                }
                if(isNaN(value)) {
                    $('#money-error').html('输入金额只能为数字');
                    $(this).addClass('current');
                }
            }
        });



        // 检查验证码
        $('#sms-ipt').blur(function () {
            var value = $.trim($(this).val());
            var phone = $('.get-sms').attr('data-value');

            if (!value) {
                $('#smscode-error').html('验证码不能为空');
                $(this).addClass('current');
                return;
            }

            checksmscode.remote({
                phone: phone,
                vericode: value,
                type: 6
            });

        });

        // checksmscodeCb
        checksmscode.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }

        });

        // 检查是否获取验证码
        $('.get-sms').click(util.debounce(function (e) {
            e.preventDefault();
            var phone = $('.get-sms').attr('data-value');

            sendsmscode.remote({
                phone: phone,
                type: 6
            });
        }, 1000));

        // sendsmscodeCb
        sendsmscode.on('success', function(data) {
            var value = 60;
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                var wait = $('#testing-wait');

                wait.text('60秒后重新发送');
                wait.addClass('show');

                var timer = setInterval(function () {

                    wait.text(--value + '秒后重新发送');
                    if (value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                    }

                }, 1000);
            }
        });

        $('#submit').click(function () {

            $('#extract').trigger('submit');

//            withdraw.remote({
//                value: value,
//                invercode: invercode
//            });
        });

        $('#extract').on('submit', function () {
            var ipt = $('#search-ipt');
            var value = ipt.val();
            // var smsid = $('#sms-ipt');
            // var invercode = smsid.val();

            if(!value) {
                $('#money-error').html('输入金额不能为空');
                ipt.addClass('current');

                return false;
            }
            if(isNaN(value)) {
                $('#money-error').html('输入金额只能为数字');
                ipt.addClass('current');

                return false;
            }
            $('.ext-ipt.current').trigger('blur');
            // if (!invercode) {
            //     $('#smscode-error').html('验证码不能为空');
            //     smsid.addClass('current');
            //     return false;
            // }
        });

        // withdrawCb
//        withdraw.on('success', function (data) {
//            if(data && data.bizError) {
//                alert(data.statusInfo);
//            }
//
//        });

    }


    return {
        init:init
    };
});

