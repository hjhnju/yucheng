<<<<<<< HEAD
/*! 2014 Baidu Inc. All Rights Reserved */
define("setting/chpassword/index",["require","jquery","common/Remoter","etpl","./chpassword.tpl"],function(require){function e(){t(),o.compile(s)}function t(){r(".chpwd-link").click(function(){var e=r("#new-ipt"),t=+e.val(),n=+r("#confirm-new-ipt").val();if(t!==n)return void r("#error").html("两次密码不一致");else return void i.remote({oldpwd:r("#old-ipt").val(),newpwd:e.val()})}),i.on("success",function(e){if(e&&e.bizError)r("#error").html(e.statusInfo);else{var t,n=8;t=setInterval(function(){if(r("#time-span").text(--n+"秒后自动跳转"),0===n)clearInterval(t),window.location.href="/account/views/overview/index"},1e3),r("#error").html(""),r("#chpwd-box").html(o.render("list"))}})}var r=require("jquery"),n=require("common/Remoter"),i=new n("EDIT_CHPWD_SUBMITE"),o=require("etpl"),s=require("./chpassword.tpl");return{init:e}});
=======
define('setting/chpassword/index', function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var chpwdSubmite = new Remoter('EDIT_CHPWD_SUBMITE');
    var testPwd = /^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/;
    var etpl = require('etpl');
    var tpl = require('./chpassword.tpl');
    function init() {
        bingEvent();
        etpl.compile(tpl);
    }
    function bingEvent() {
        $('.chpwd-link').click(function () {
            var newIpt = $('#new-ipt');
            var newval = +newIpt.val();
            var me = +$('#confirm-new-ipt').val();
            if (newval !== me) {
                $('#error').html('\u4E24\u6B21\u5BC6\u7801\u4E0D\u4E00\u81F4');
                return;
            }
            chpwdSubmite.remote({
                oldpwd: $('#old-ipt').val(),
                newpwd: newIpt.val()
            });
        });
        chpwdSubmite.on('success', function (data) {
            if (data && data.bizError) {
                $('#error').html(data.statusInfo);
            } else {
                var value = 8;
                var timer;
                timer = setInterval(function () {
                    $('#time-span').text(--value + '\u79D2\u540E\u81EA\u52A8\u8DF3\u8F6C');
                    if (value === 0) {
                        clearInterval(timer);
                        window.location.href = '/account/views/overview/index';
                    }
                }, 1000);
                $('#error').html('');
                $('#chpwd-box').html(etpl.render('list'));
            }
        });
    }
    return { init: init };
});
>>>>>>> 08911090d2f27b93d9e16a217078d267fd454503
