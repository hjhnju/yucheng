/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var config = require('common/config');
    var emailConfirm = new Remoter('EDIT_EMAILCONFIRM');

    var etpl = require('etpl');
    var tpl = require('./email.tpl');
    var emailVal;
    var IMGURL = config.URL.IMG_GET + 'email';
    var header = require('common/header');

    function init (){
        changeEmail();
        etpl.compile(tpl);
        header.init();
    }

    function changeEmail() {

        $('.login-input').on({


            focus: function () {

                var parent = $(this).parent();

                $(this).next().addClass('hidden');

            },
            blur: function () {
                var value = $(this).val();
                !value && $(this).next().removeClass('hidden');
            }
        });

        // 点击刷新验证码
        $('#email-img').click(function () {
            $(this).attr('src', IMGURL);
        });

        // getSmscodeCb
        //getSmscode.on('success', function (data) {
        //    if(data && data.bizError) {
        //        alert(data.statusInfo);
        //    }
        //    else {
        //        $('#email-img').attr('src', data.url);
        //
        //    }
        //});


        //确定点击
        $('#confirm').click(function () {

            emailVal = $('#login-email').val();
            var smscodeVal = $('#login-testing').val();

            emailConfirm.remote({
                email: emailVal,
                vericode: smscodeVal,
                type: checkEmailType
            });

        });

        // emailConfirmCb
        emailConfirm.on('success', function (data) {
            if(data && data.bizError) {
                $('.error').html(data.statusInfo);
            }
            else {
                var timer;
                var value = 6;
                $('#checkemial').html(etpl.render('list2nd', {
                    email: emailVal
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

