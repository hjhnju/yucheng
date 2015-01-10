define("setting/regist/index",["require","jquery","../common/picScroll","common/header","common/Remoter"],function(require){function e(e){i=e?1:0,s.init(),o.init(),r(),n()}function r(){t(".regist .login-input").on({focus:function(){var e=t(this).parent(),r=e.children(".username-error");e.removeClass("current"),r.html(""),t(this).next().addClass("hidden")},blur:function(){var e=t(this).parent(),r=t.trim(t(this).val()),n=t(this).attr("data-text"),i=t(this).attr("id");if(!r){if(!t(this).hasClass("login-tuijian"))e.addClass("current"),t("#"+i+"-error").html(n+"不能为空");t(this).next().removeClass("hidden")}}}),v.loginUser.blur(function(){var e=t.trim(t(this).val());if(e)a.remote({name:e});else E.user=0}),v.loginPwd.blur(function(){var e=t.trim(t(this).val());if(!e)return void(E.pwd=0);if(!h.test(e))return E.pwd=0,t(this).parent().addClass("current"),void p.pwdError.html("密码只能为 6 - 32 位数字，字母及常用符号组成");else return E.pwd=1,void p.pwdError.html(f)}),v.loginPhone.blur(function(){var e=t.trim(t(this).val());if(e)u.remote({phone:e});else E.phone=0,t(".login-username-testing").addClass("disabled")}),v.loginTest.blur(function(){var e=t.trim(t(this).val()),r=t.trim(v.loginPhone.val());if(!r)return v.loginPhone.trigger("blur"),void(E.vericode=0);if(e)g.remote({vericode:e,phone:r,type:1});else E.vericode=0}),t(".login-username-testing").click(function(e){e.preventDefault();var r=t.trim(v.loginPhone.val());if(!t(this).hasClass("disabled")&&r)c.remote({phone:r,type:1})}),v.loginTuiJian.blur(function(){var e=t.trim(t(this).val());if(e)d.remote({inviter:e})}),t(".regist .login-fastlogin").click(function(e){e.preventDefault();var r=1;if(!t("#tiaoyue-itp")[0].checked)return void alert("请同意用户条约");for(var n in E)if(E.hasOwnProperty(n))if(!E[n])v[C[n]].trigger("blur"),r=0;r&&m.remote({name:v.loginUser.val(),passwd:v.loginPwd.val(),phone:v.loginPhone.val(),inviter:v.loginTuiJian.val(),vericode:v.loginTest.val(),isthird:i})})}function n(){var e;a.on("success",function(e){if(e&&e.bizError)v.loginUser.parent().addClass("current"),p.userError.html(e.statusInfo),E.user=0;else p.userError.html(f),E.user=1}),u.on("success",function(e){if(e&&e.bizError)v.loginPhone.parent().addClass("current"),p.phoneError.html(e.statusInfo),E.phone=0,t(".login-username-testing").addClass("disabled");else p.phoneError.html(f),E.phone=1,t(".login-username-testing").removeClass("disabled")}),d.on("success",function(e){if(e&&e.bizError)v.loginTuiJian.parent().addClass("current"),p.tuiJianError.html(e.statusInfo),E.tui=0;else p.tuiJianError.html(f),E.tui=1}),c.on("success",function(r){var n=60;if(r&&r.bizError)alert(r.statusInfo);else{var i=t("#testing-wait");i.text("60秒后重新发送"),i.addClass("show"),e=setInterval(function(){if(i.text(--n+"秒后重新发送"),0>n)clearInterval(e),i.removeClass("show")},1e3)}}),g.on("success",function(e){if(e&&e.bizError)v.loginTest.parent().addClass("current"),p.testError.html(e.statusInfo),E.vericode=0;else p.testError.html(f),E.vericode=1}),m.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else window.location.href="/user/open/index"})}var i,t=require("jquery"),o=require("../common/picScroll"),s=require("common/header"),l=require("common/Remoter"),a=new l("REGIST_CHECKNAME_CHECK"),u=new l("REGIST_CHECKPHONE_CHECK"),c=new l("REGIST_SENDSMSCODE_CHECK"),d=new l("REGIST_CHECKINVITER_CHECK"),g=new l("REGIST_CHECKSMSCODE_CHECK"),m=new l("REGIST_INDEX_CHECK"),h=/^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/,f='<span class="username-error-span"></span>',v={loginUser:t("#regist-user"),loginPwd:t("#regist-pwd"),loginPhone:t("#regist-phone"),loginTest:t("#regist-testing"),loginTuiJian:t("#regist-tuijian")},p={userError:t("#regist-user-error"),phoneError:t("#regist-phone-error"),pwdError:t("#regist-pwd-error"),testError:t("#regist-testing-error"),tuiJianError:t("#regist-tuijian-error")},E={user:0,phone:0,pwd:0,vericode:0,tui:1},C={user:"loginUser",phone:"loginPhone",pwd:"loginPwd",vericode:"loginTest",tui:"loginTuiJian"};return{init:e}});