/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var emailConfirm = new Remoter('EDIT_EMAILCONFIRM');
    var getSmscode = new Remoter('LOGIN_IMGCODE_ADD');
    var sendSmscode = new Remoter('LOGIN_IMGCODE_CHECK');
    var etpl = require('etpl');
    var tpl = require('./email.tpl');
    var emailVal;


    function init (){
        changeEmail();
        etpl.compile(tpl);

        getSmscode.remote({
            type: 4
        });

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
            getSmscode.remote({
                type: 4
            })
        });

        // getSmscodeCb
        getSmscode.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                $('#email-img').attr('src', data.url);

            }
        });


        //确定点击
        $('#confirm').click(function () {

            emailVal = $('#login-email').val();
            var smscodeVal = $('#login-testing').val();

            emailConfirm.remote({
                email: emailVal,
                smscode: smscodeVal,
                type:4
            });

        });

        // emailConfirmCb
        emailConfirm.on('success', function (data) {
            if(data && data.bizError) {
                $('.error').html(data.statusInfo);
            }
            else {
                var timer;
                var value = 8;
                $('#checkemial').html(etpl.render('list2nd', {
                    email: emailVal
                }));

                timer = setInterval(function () {

                    $('#time-span').text(--value + '秒后自动跳转');
                    if(value === 0) {
                        clearInterval(timer);
                        window.location.href = '/account/views/overview/index';
                    }

                },1000);
            }
        })

    }
    return {init:init};
});

