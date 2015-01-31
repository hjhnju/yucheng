define("setting/regist/index",function(require){function e(e){n=e?1:0,a.compile(u),o.init(),s.init(),i(),r()}function i(){t(".regist .login-input").on({focus:function(){var e=t(this).parent(),i=e.children(".username-error");e.removeClass("current"),i.html(""),t(this).next().addClass("hidden")},blur:function(){var e=t(this).parent(),i=t.trim(t(this).val()),r=t(this).attr("data-text"),n=t(this).attr("id");if(!i){if(!t(this).hasClass("login-tuijian"))e.addClass("current"),t("#"+n+"-error").html(r+"不能为空");t(this).next().removeClass("hidden")}}}),E.loginUser.blur(function(){var e=t.trim(t(this).val());if(e)c.remote({name:e});else I.user=0}),E.loginPwd.blur(function(){var e=t.trim(t(this).val());if(!e)return void(I.pwd=0);if(!p.test(e))return I.pwd=0,t(this).parent().addClass("current"),void w.pwdError.html("密码只能为 6 - 32 位数字，字母及常用符号组成");else return I.pwd=1,void w.pwdError.html(C)}),E.loginPhone.blur(function(){var e=t.trim(t(this).val());if(e)d.remote({phone:e});else I.phone=0,t(".login-username-testing").addClass("disabled")}),E.loginTest.blur(function(){var e=t.trim(t(this).val()),i=t.trim(E.loginPhone.val());if(!i)return E.loginPhone.trigger("blur"),void(I.vericode=0);if(e)m.remote({vericode:e,phone:i,type:"regist"});else I.vericode=0}),t(".login-username-testing").click(function(e){e.preventDefault();var i=t.trim(E.loginPhone.val());if(!t(this).hasClass("disabled")&&i)f.remote({phone:i,type:"regist"})}),E.loginTuiJian.blur(function(){var e=t.trim(t(this).val());if(e)h.remote({inviter:e})}),t(".regist .login-fastlogin").click(function(e){e.preventDefault();var i=1;if(!t("#tiaoyue-itp")[0].checked)return void alert("请同意用户条约");for(var r in I)if(I.hasOwnProperty(r))if(!I[r])E[b[r]].trigger("blur"),i=0;i&&v.remote({name:E.loginUser.val(),passwd:E.loginPwd.val(),phone:E.loginPhone.val(),inviter:E.loginTuiJian.val(),vericode:E.loginTest.val(),isthird:n})})}function r(){var e;c.on("success",function(e){if(e&&e.bizError)E.loginUser.parent().addClass("current"),w.userError.html(e.statusInfo),I.user=0;else w.userError.html(C),I.user=1}),d.on("success",function(e){if(e&&e.bizError)E.loginPhone.parent().addClass("current"),w.phoneError.html(e.statusInfo),I.phone=0,t(".login-username-testing").addClass("disabled");else w.phoneError.html(C),I.phone=1,t(".login-username-testing").removeClass("disabled")}),h.on("success",function(e){if(e&&e.bizError)E.loginTuiJian.parent().addClass("current"),w.tuiJianError.html(e.statusInfo),I.tui=0;else w.tuiJianError.html(C),I.tui=1}),f.on("success",function(i){var r=60;if(i&&i.bizError)alert(i.statusInfo);else{var n=t("#testing-wait");n.text("60秒后重新发送"),n.addClass("show"),e=setInterval(function(){if(n.text(--r+"秒后重新发送"),0>r)clearInterval(e),n.removeClass("show")},1e3)}}),m.on("success",function(e){if(e&&e.bizError)E.loginTest.parent().addClass("current"),w.testError.html(e.statusInfo),I.vericode=0;else w.testError.html(C),I.vericode=1}),v.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else window.location.href="/user/open/index"}),t(".fix-box-register").click(function(){s.show({width:500,defaultTitle:!1,content:a.render("fixBox")}),l.init("registBind")})}var n,t=require("jquery"),o=require("../common/picScroll"),s=(require("common/header"),require("common/ui/Dialog/Dialog")),l=require("setting/login/index"),a=require("etpl"),u=require("./regist.tpl"),g=require("common/Remoter"),c=new g("REGIST_CHECKNAME_CHECK"),d=new g("REGIST_CHECKPHONE_CHECK"),f=new g("REGIST_SENDSMSCODE_CHECK"),h=new g("REGIST_CHECKINVITER_CHECK"),m=new g("REGIST_CHECKSMSCODE_CHECK"),v=new g("REGIST_INDEX_CHECK"),p=/^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/,C='<span class="username-error-span"></span>',E={loginUser:t("#regist-user"),loginPwd:t("#regist-pwd"),loginPhone:t("#regist-phone"),loginTest:t("#regist-testing"),loginTuiJian:t("#regist-tuijian")},w={userError:t("#regist-user-error"),phoneError:t("#regist-phone-error"),pwdError:t("#regist-pwd-error"),testError:t("#regist-testing-error"),tuiJianError:t("#regist-tuijian-error")},I={user:0,phone:0,pwd:0,vericode:0,tui:1},b={user:"loginUser",phone:"loginPhone",pwd:"loginPwd",vericode:"loginTest",tui:"loginTuiJian"};return{init:e}});