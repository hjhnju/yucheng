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
    var etpl = require('etpl');
    var tpl = require('./email.tpl');


    function init (){
        changeEmail();
        etpl.compile(tpl);

    }

    function changeEmail() {

        $('.login-input').on({


            focus: function () {

                var parent = $(this).parent();

                $(this).next().addClass('hidden');
                parent.removeClass('current');
                parent.find($('.username-error')).html('');

            },
            blur: function () {
                var value = $(this).val();
                if(!value) {
                    $(this).next().removeClass('hidden');
                    $(this).parent().addClass('current');
                    $(this).parent().find('.username-error').html('内容不能为空');
                    return;
                }
            }
        });


        $('#confirm').click(function () {
            $('.login-input').trigger('blur');
            var emailVal = $('#login-email').val();
            var errors = $('.login-username.current');
            var smscodeVal = $('#login-testing').val();

            if(errors.length) {
                return;
            }

            emailConfirm.remote({
                email: emailVal,
                smscode: smscodeVal
            });

        });

        emailConfirm.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                $('#checkemial').html(etpl.render('list2nd', {

                }));
            }
        })

    }
    return {init:init};
});

