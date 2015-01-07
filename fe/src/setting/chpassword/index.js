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
    var etpl = require('etpl');
    var tpl = require('./chpassword.tpl');
    var header = require('common/header');

    function init (){
        bingEvent();
        etpl.compile(tpl);
        header.init();
    }

    function bingEvent() {

        // 点击发送请求
        $('.chpwd-link').click(function () {
            var newIpt = $('#new-ipt');

            var newval = +newIpt.val();
            var me = +$('#confirm-new-ipt').val();

            if(newval !== me) {
                $('#error').html('两次密码不一致');

                return;
            }

            chpwdSubmite.remote({
                oldpwd: $('#old-ipt').val(),
                newpwd: newIpt.val()
            });
        });

        // chpwdSubmiteCb
        chpwdSubmite.on('success', function (data) {
            if(data && data.bizError) {
                $('#error').html(data.statusInfo);
            }
            else {

                var value = 8;
                var timer;


                $('#error').html('');
                $('#chpwd-box').html(etpl.render('list'));

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

    return {
        init:init
    };
});

