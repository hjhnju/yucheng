/*! 2014 Baidu Inc. All Rights Reserved */
define("setting/phone/index",["require","jquery","common/Remoter","etpl","./phone.tpl"],function(require){function e(){t(),a.compile(l)}function t(){{var e=r("#checkphone");r("#login-phonenew")}e.delegate(".login-input","focus",function(){var e=r(this).parent();r(this).next().addClass("hidden"),e.removeClass("current"),r("#login-phonenew-error").html(""),r("#username-error-error").html("")}),e.delegate(".login-input","blur",function(){var e=r.trim(r(this).val());!e&&r(this).next().removeClass("hidden")}),e.delegate("#sendsmscode","click",function(){var e=+r("#login-phonenew").val();if(!c.test(e))return void r(".error").html("手机号码格式不正确");if(e)r(".error").html(""),s.remote({phone:e,type:3})}),s.on("success",function(e){var t=r("#login-phonenew-error");if(e&&e.bizError)t.parent().addClass("current"),t.html(e.statusInfo);else{var n,i=60,o=r("#testing-wait");setInterval(function(){if(o.text("60秒后重新发送"),o.addClass("show"),o.text(--i+"秒后重新发送"),0>i)clearInterval(n),o.removeClass("show")},1e3)}}),e.delegate("#confirm","click",function(e){e.preventDefault();var t=r("#login-phonenew").val(),n=r("#login-test").val();if(!t||!n)return void r(".error").html("手机或验证码不能为空");else return void i.remote({oldPhone:t,vericode:n,type:3})}),i.on("success",function(e){if(e&&e.bizError)r(".error").html(e.statusInfo);else r("#checkphone").html(a.render("list2nd"))}),e.delegate("#confirm2nd","click",function(e){e.preventDefault();var t=r("#login-phonenew").val(),n=r("#login-test").val();if(!t||!n)return void r(".error").html("手机或验证码不能为空");else return void o.remote({newPhone:t,vericode:n,type:3})}),o.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else{var t,n=8;r("#checkphone").html(a.render("list3th",{user:e})),t=setInterval(function(){if(r("#time-span").text(--n+"秒后自动跳转"),0===n)clearInterval(t),window.location.href="/account/views/overview/index"},1e3)}})}var r=require("jquery"),n=require("common/Remoter"),i=new n("EDIT_PHONE_SUBMITE"),o=new n("EDIT_PHONE_SUBMITE"),s=new n("REGIST_SENDSMSCODE_CHECK"),a=require("etpl"),l=require("./phone.tpl"),c=/\d{11}/g;return{init:e}});