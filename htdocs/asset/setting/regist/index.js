<<<<<<< HEAD
/*! 2014 Baidu Inc. All Rights Reserved */
define("setting/regist/index",["require","jquery","../common/picScroll","common/header","common/Remoter"],function(require){function r(){o.init(),i.init(),e(),n()}function e(){t(".regist .login-input").on({focus:function(){var r=t(this).parent(),e=r.children(".username-error");r.removeClass("current"),e.html(""),t(this).next().addClass("hidden")},blur:function(){var r=t(this).parent(),e=t.trim(t(this).val()),n=t(this).attr("data-text"),i=t(this).attr("id");if(!e){if(!t(this).hasClass("login-tuijian"))r.addClass("current"),t("#"+i+"-error").html(n+"不能为空");t(this).next().removeClass("hidden")}}}),h.loginUser.blur(function(){var r=t.trim(t(this).val());if(r)l.remote({name:r});else p.user=0}),h.loginPwd.blur(function(){var r=t.trim(t(this).val());if(!r)return void(p.pwd=0);if(!g.test(r))return p.pwd=0,t(this).parent().addClass("current"),void v.pwdError.html("密码只能为 6 - 32 位数字，字母及常用符号组成");else return void v.pwdError.html(d)}),h.loginPhone.blur(function(){var r=t.trim(t(this).val());if(r)a.remote({phone:r});else p.phone=0}),h.loginTest.blur(function(){var r=t.trim(t(this).val()),e=t.trim(h.loginPhone.val());if(!e)return h.loginPhone.trigger("blur"),void(p.test=0);if(r)m.remote({vericode:r,phone:e,type:1});else p.test=0}),t(".login-username-testing").click(function(r){r.preventDefault();var e=t.trim(h.loginPhone.val());if(e)u.remote({phone:e,type:1})}),h.loginTuiJian.blur(function(){var r=t.trim(t(this).val());if(r)return void c.remote({inviter:r});else return void 0}),t(".regist .login-fastlogin").click(function(r){r.preventDefault();var e=1;if(!t("#tiaoyue-itp")[0].checked)return void alert("请同意用户条约");for(var n in p)if(p.hasOwnProperty(n))if(!p[n])h[E[n]].trigger("blur"),e=0;e&&f.remote({name:h.loginUser.val(),passwd:h.loginPwd.val(),phone:h.loginPhone.val(),inviter:h.loginTuiJian.val(),test:h.loginTest.val()})})}function n(){var r;l.on("success",function(r){if(r&&r.bizError)h.loginUser.parent().addClass("current"),v.userError.html(r.statusInfo),p.user=0;else v.userError.html(d),p.user=1}),a.on("success",function(r){if(r&&r.bizError)h.loginPhone.parent().addClass("current"),v.phoneError.html(r.statusInfo),p.phone=0;else v.phoneError.html(d),p.phone=1}),c.on("success",function(r){if(r&&r.bizError)h.loginTuiJian.parent().addClass("current"),v.tuiJianError.html(r.statusInfo),p.tui=0;else v.tuiJianError.html(d),p.tui=1}),u.on("success",function(e){var n=60;if(e&&e.bizError)alert(e.statusInfo);else{var i=t("#testing-wait");i.text("300秒后重新发送"),i.addClass("show"),r=setInterval(function(){if(i.text(--n+"秒后重新发送"),0>n)clearInterval(r),i.removeClass("show")},1e3)}}),m.on("success",function(r){if(r&&r.bizError)h.loginTest.parent().addClass("current"),v.testError.html(r.statusInfo),p.test=0;else v.testError.html(d),p.test=1}),f.on("success",function(r){if(r&&r.bizError)alert(r.statusInfo);else window.location.href="/account/overview/index"})}var t=require("jquery"),i=require("../common/picScroll"),o=require("common/header"),s=require("common/Remoter"),l=new s("REGIST_CHECKNAME_CHECK"),a=new s("REGIST_CHECKPHONE_CHECK"),u=new s("REGIST_SENDSMSCODE_CHECK"),c=new s("REGIST_CHECKINVITER_CHECK"),m=new s("REGIST_CHECKSMSCODE_CHECK"),f=new s("REGIST_INDEX_CHECK"),g=/^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/,d='<span class="username-error-span"></span>',h={loginUser:t("#regist-user"),loginPwd:t("#regist-pwd"),loginPhone:t("#regist-phone"),loginTest:t("#regist-testing"),loginTuiJian:t("#regist-tuijian")},v={userError:t("#regist-user-error"),phoneError:t("#regist-phone-error"),pwdError:t("#regist-pwd-error"),testError:t("#regist-testing-error"),tuiJianError:t("#regist-tuijian-error")},p={user:0,phone:0,pwd:0,test:0,tui:1},E={user:"loginUser",phone:"loginPhone",pwd:"loginPwd",test:"loginTest",tui:"loginTuiJian"};return{init:r}});
=======
define('setting/regist/index', function (require) {
    var $ = require('jquery');
    var picScroll = require('../common/picScroll');
    var header = require('common/header');
    var Remoter = require('common/Remoter');
    var checkName = new Remoter('REGIST_CHECKNAME_CHECK');
    var checkphone = new Remoter('REGIST_CHECKPHONE_CHECK');
    var sendsmscode = new Remoter('REGIST_SENDSMSCODE_CHECK');
    var checkInviter = new Remoter('REGIST_CHECKINVITER_CHECK');
    var checksmscode = new Remoter('REGIST_CHECKSMSCODE_CHECK');
    var registSubmit = new Remoter('REGIST_INDEX_CHECK');
    var testPwd = /^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/;
    var CORRECT = '<span class="username-error-span"></span>';
    var loginInput = {
            loginUser: $('#regist-user'),
            loginPwd: $('#regist-pwd'),
            loginPhone: $('#regist-phone'),
            loginTest: $('#regist-testing'),
            loginTuiJian: $('#regist-tuijian')
        };
    var error = {
            userError: $('#regist-user-error'),
            phoneError: $('#regist-phone-error'),
            pwdError: $('#regist-pwd-error'),
            testError: $('#regist-testing-error'),
            tuiJianError: $('#regist-tuijian-error')
        };
    var allStatus = {
            user: 0,
            phone: 0,
            pwd: 0,
            test: 0,
            tui: 1
        };
    var mapInput = {
            user: 'loginUser',
            phone: 'loginPhone',
            pwd: 'loginPwd',
            test: 'loginTest',
            tui: 'loginTuiJian'
        };
    function init() {
        header.init();
        picScroll.init();
        bindEvents();
        callBack();
    }
    function bindEvents() {
        $('.regist .login-input').on({
            focus: function () {
                var parent = $(this).parent();
                var error = parent.children('.username-error');
                parent.removeClass('current');
                error.html('');
                $(this).next().addClass('hidden');
            },
            blur: function () {
                var parent = $(this).parent();
                var value = $.trim($(this).val());
                var text = $(this).attr('data-text');
                var id = $(this).attr('id');
                if (!value) {
                    if (!$(this).hasClass('login-tuijian')) {
                        parent.addClass('current');
                        $('#' + id + '-error').html(text + '\u4E0D\u80FD\u4E3A\u7A7A');
                    }
                    $(this).next().removeClass('hidden');
                }
            }
        });
        loginInput.loginUser.blur(function () {
            var value = $.trim($(this).val());
            if (value) {
                checkName.remote({ name: value });
            } else {
                allStatus.user = 0;
            }
        });
        loginInput.loginPwd.blur(function () {
            var value = $.trim($(this).val());
            if (!value) {
                allStatus.pwd = 0;
                return;
            }
            if (!testPwd.test(value)) {
                allStatus.pwd = 0;
                $(this).parent().addClass('current');
                error.pwdError.html('\u5BC6\u7801\u53EA\u80FD\u4E3A 6 - 32 \u4F4D\u6570\u5B57\uFF0C\u5B57\u6BCD\u53CA\u5E38\u7528\u7B26\u53F7\u7EC4\u6210');
                return;
            }
            allStatus.pwd = 1;
            error.pwdError.html(CORRECT);
        });
        loginInput.loginPhone.blur(function () {
            var value = $.trim($(this).val());
            if (value) {
                checkphone.remote({ phone: value });
            } else {
                allStatus.phone = 0;
            }
        });
        loginInput.loginTest.blur(function () {
            var value = $.trim($(this).val());
            var phone = $.trim(loginInput.loginPhone.val());
            if (!phone) {
                loginInput.loginPhone.trigger('blur');
                allStatus.test = 0;
                return;
            }
            if (value) {
                checksmscode.remote({
                    vericode: value,
                    phone: phone,
                    type: 1
                });
            } else {
                allStatus.test = 0;
            }
        });
        $('.login-username-testing').click(function (e) {
            e.preventDefault();
            var value = $.trim(loginInput.loginPhone.val());
            if (value) {
                sendsmscode.remote({
                    phone: value,
                    type: 1
                });
            }
        });
        loginInput.loginTuiJian.blur(function () {
            var value = $.trim($(this).val());
            if (value) {
                checkInviter.remote({ inviter: value });
            }
        });
        $('.regist .login-fastlogin').click(function (e) {
            e.preventDefault();
            var status = 1;
            if (!$('#tiaoyue-itp')[0].checked) {
                alert('\u8BF7\u540C\u610F\u7528\u6237\u6761\u7EA6');
                return;
            }
            for (var name in allStatus) {
                if (allStatus.hasOwnProperty(name)) {
                    if (!allStatus[name]) {
                        loginInput[mapInput[name]].trigger('blur');
                        status = 0;
                    }
                }
            }
            status && registSubmit.remote({
                name: loginInput.loginUser.val(),
                passwd: loginInput.loginPwd.val(),
                phone: loginInput.loginPhone.val(),
                inviter: loginInput.loginTuiJian.val()
            });
        });
    }
    function callBack() {
        var timer;
        checkName.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginUser.parent().addClass('current');
                error.userError.html(data.statusInfo);
                allStatus.user = 0;
            } else {
                error.userError.html(CORRECT);
                allStatus.user = 1;
            }
        });
        checkphone.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginPhone.parent().addClass('current');
                error.phoneError.html(data.statusInfo);
                allStatus.phone = 0;
            } else {
                error.phoneError.html(CORRECT);
                allStatus.phone = 1;
            }
        });
        checkInviter.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginTuiJian.parent().addClass('current');
                error.tuiJianError.html(data.statusInfo);
                allStatus.tui = 0;
            } else {
                error.tuiJianError.html(CORRECT);
                allStatus.tui = 1;
            }
        });
        sendsmscode.on('success', function (data) {
            var value = 60;
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                var wait = $('#testing-wait');
                wait.text('300\u79D2\u540E\u91CD\u65B0\u53D1\u9001');
                wait.addClass('show');
                timer = setInterval(function () {
                    wait.text(--value + '\u79D2\u540E\u91CD\u65B0\u53D1\u9001');
                    if (value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                    }
                }, 1000);
            }
        });
        checksmscode.on('success', function (data) {
            if (data && data.bizError) {
                loginInput.loginTest.parent().addClass('current');
                error.testError.html(data.statusInfo);
                allStatus.test = 0;
            } else {
                error.testError.html(CORRECT);
                allStatus.test = 1;
            }
        });
        registSubmit.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                window.location.href = '/account/overview/index';
            }
        });
    }
    return { init: init };
});
>>>>>>> 08911090d2f27b93d9e16a217078d267fd454503
