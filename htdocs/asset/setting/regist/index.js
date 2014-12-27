/*! 2014 Baidu Inc. All Rights Reserved */
define("setting/regist/index",function(require){function r(){o.init(),i.init(),n(),e()}function n(){t(".regist .login-input").on({focus:function(){var r=t(this).parent(),n=r.children(".username-error");r.removeClass("current"),n.html(""),t(this).next().addClass("hidden")},blur:function(){var r=t(this).parent(),n=t.trim(t(this).val()),e=t(this).attr("data-text"),i=t(this).attr("id");if(!n){if(!t(this).hasClass("login-tuijian"))r.addClass("current"),t("#"+i+"-error").html(e+"不能为空");t(this).next().removeClass("hidden")}}}),d.loginUser.blur(function(){var r=t.trim(t(this).val());if(r)l.remote({name:r})}),d.loginPwd.blur(function(){var r=t.trim(t(this).val());if(r)if(!m.test(r))return t(this).parent().addClass("current"),void v.pwdError.html("密码只能为 6 - 32 位数字，字母及常用符号组成");else return void v.pwdError.html(h)}),d.loginPhone.blur(function(){var r=t.trim(t(this).val());if(r)a.remote({phone:r})}),d.loginTest.blur(function(){var r=t.trim(t(this).val()),n=t.trim(d.loginPhone.val());if(!n)return void d.loginPhone.trigger("blur");if(r)f.remote({vericode:r,phone:n,type:1})}),t(".login-username-testing").click(function(r){r.preventDefault();var n=t.trim(d.loginPhone.val());if(n)u.remote({phone:n})}),d.loginTuiJian.blur(function(){var r=t.trim(t(this).val());if(r)c.remote({inviter:r})}),t(".regist .login-fastlogin").click(function(r){r.preventDefault(),t(".regist .login-input").trigger("blur");var n=t(".regist .login-username.current");if(!t("#tiaoyue-itp")[0].checked)return void alert("请同意用户条约");if(!n.length)g.remote({name:d.loginUser.val(),passwd:d.loginPwd.val(),phone:d.loginPhone.val(),inviter:d.loginTuiJian.val()})})}function e(){var r;l.on("success",function(r){if(r&&r.bizError)d.loginUser.parent().addClass("current"),v.userError.html(r.statusInfo);else v.userError.html(h)}),a.on("success",function(r){if(r&&r.bizError)d.loginPhone.parent().addClass("current"),v.phoneError.html(r.statusInfo);else v.phoneError.html(h)}),c.on("success",function(r){if(r&&r.bizError)d.loginTuiJian.parent().addClass("current"),v.tuiJianError.html(r.statusInfo);else v.tuiJianError.html(h)}),u.on("success",function(n){var e=300;if(n&&n.bizError)alert(n.statusInfo);else{var i=t("#testing-wait");i.text("300秒后重新发送"),i.addClass("show"),r=setInterval(function(){if(i.text(--e+"秒后重新发送"),0>e)clearInterval(r),i.removeClass("show")},1e3)}}),f.on("success",function(r){if(r&&r.bizError)d.loginTest.parent().addClass("current"),v.testError.html(r.statusInfo);else v.testError.html(h)}),g.on("success",function(r){if(r&&r.bizError)alert(r.statusInfo);else window.location.href="http://www.baidu.com"})}var t=require("jquery"),i=require("../common/picScroll"),o=require("common/header"),s=require("common/Remoter"),l=new s("REGIST_CHECKNAME_CHECK"),a=new s("REGIST_CHECKPHONE_CHECK"),u=new s("REGIST_SENDSMSCODE_CHECK"),c=new s("REGIST_CHECKINVITER_CHECK"),f=new s("REGIST_CHECKSMSCODE_CHECK"),g=new s("REGIST_INDEX_CHECK"),m=/^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/,h='<span class="username-error-span"></span>',d={loginUser:t("#regist-user"),loginPwd:t("#regist-pwd"),loginPhone:t("#regist-phone"),loginTest:t("#regist-testing"),loginTuiJian:t("#regist-tuijian")},v={userError:t("#regist-user-error"),phoneError:t("#regist-phone-error"),pwdError:t("#regist-pwd-error"),testError:t("#regist-testing-error"),tuiJianError:t("#regist-tuijian-error")};return{init:r}});