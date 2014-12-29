<<<<<<< HEAD
/*! 2014 Baidu Inc. All Rights Reserved */
define("setting/email/index",["require","jquery","common/Remoter","etpl","./email.tpl"],function(require){function e(){t(),a.compile(l),s.remote({type:4})}function t(){r(".login-input").on({focus:function(){r(this).parent();r(this).next().addClass("hidden")},blur:function(){var e=r(this).val();!e&&r(this).next().removeClass("hidden")}}),r("#email-img").click(function(){s.remote({type:4})}),s.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else r("#email-img").attr("src",e.url)}),r("#confirm").click(function(){n=r("#login-email").val();var e=r("#login-testing").val();o.remote({email:n,smscode:e,type:4})}),o.on("success",function(e){if(e&&e.bizError)r(".error").html(e.statusInfo);else{var t,i=8;r("#checkemial").html(a.render("list2nd",{email:n})),t=setInterval(function(){if(r("#time-span").text(--i+"秒后自动跳转"),0===i)clearInterval(t),window.location.href="/account/views/overview/index"},1e3)}})}var n,r=require("jquery"),i=require("common/Remoter"),o=new i("EDIT_EMAILCONFIRM"),s=new i("LOGIN_IMGCODE_ADD"),a=(new i("LOGIN_IMGCODE_CHECK"),require("etpl")),l=require("./email.tpl");return{init:e}});
=======
define('setting/email/index', function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var emailConfirm = new Remoter('EDIT_EMAILCONFIRM');
    var getSmscode = new Remoter('LOGIN_IMGCODE_ADD');
    var sendSmscode = new Remoter('LOGIN_IMGCODE_CHECK');
    var etpl = require('etpl');
    var tpl = require('./email.tpl');
    var emailVal;
    function init() {
        changeEmail();
        etpl.compile(tpl);
        getSmscode.remote({ type: 4 });
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
        $('#email-img').click(function () {
            getSmscode.remote({ type: 4 });
        });
        getSmscode.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                $('#email-img').attr('src', data.url);
            }
        });
        $('#confirm').click(function () {
            emailVal = $('#login-email').val();
            var smscodeVal = $('#login-testing').val();
            emailConfirm.remote({
                email: emailVal,
                smscode: smscodeVal,
                type: 4
            });
        });
        emailConfirm.on('success', function (data) {
            if (data && data.bizError) {
                $('.error').html(data.statusInfo);
            } else {
                var timer;
                var value = 8;
                $('#checkemial').html(etpl.render('list2nd', { email: emailVal }));
                timer = setInterval(function () {
                    $('#time-span').text(--value + '\u79D2\u540E\u81EA\u52A8\u8DF3\u8F6C');
                    if (value === 0) {
                        clearInterval(timer);
                        window.location.href = '/account/views/overview/index';
                    }
                }, 1000);
            }
        });
    }
    return { init: init };
});
>>>>>>> 08911090d2f27b93d9e16a217078d267fd454503
