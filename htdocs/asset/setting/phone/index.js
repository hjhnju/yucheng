define("setting/phone/index",["require","jquery","common/Remoter","etpl","./phone.tpl","common/header"],function(require){function e(){t(),a.compile(l),m.init()}function t(){{var e=n("#checkphone");n("#login-phonenew")}e.delegate(".login-input","focus",function(){var e=n(this).parent();n(this).next().addClass("hidden"),e.removeClass("current"),n("#login-phonenew-error").html(""),n("#username-error-error").html("")}),e.delegate(".login-input","blur",function(){var e=n.trim(n(this).val());!e&&n(this).next().removeClass("hidden")}),e.delegate("#sendsmscode","click",function(){var e=+n("#login-phonenew").val();if(!c.test(e))return void n(".error").html("手机号码格式不正确");if(e)n(".error").html(""),s.remote({phone:e,type:3})}),s.on("success",function(e){var t=n("#login-phonenew-error");if(e&&e.bizError)t.parent().addClass("current"),t.html(e.statusInfo);else{var r,i=60,o=n("#testing-wait");setInterval(function(){if(o.text("60秒后重新发送"),o.addClass("show"),o.text(--i+"秒后重新发送"),0>i)clearInterval(r),o.removeClass("show")},1e3)}}),e.delegate("#confirm","click",function(e){e.preventDefault();var t=n("#login-phonenew").val(),r=n("#login-test").val();if(!t||!r)return void n(".error").html("手机或验证码不能为空");else return void i.remote({oldPhone:t,vericode:r,type:3})}),i.on("success",function(e){if(e&&e.bizError)n(".error").html(e.statusInfo);else n("#checkphone").html(a.render("list2nd"))}),e.delegate("#confirm2nd","click",function(e){e.preventDefault();var t=n("#login-phonenew").val(),r=n("#login-test").val();if(!t||!r)return void n(".error").html("手机或验证码不能为空");else return void o.remote({newPhone:t,vericode:r,type:3})}),o.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else{var t,r=8;n("#checkphone").html(a.render("list3th",{user:e})),t=setInterval(function(){if(n("#time-span").text(--r+"秒后自动跳转"),0===r)clearInterval(t),window.location.href="/account/views/overview/index"},1e3)}})}var n=require("jquery"),r=require("common/Remoter"),i=new r("EDIT_PHONE_SUBMITE"),o=new r("EDIT_PHONE_SUBMITE"),s=new r("REGIST_SENDSMSCODE_CHECK"),a=require("etpl"),l=require("./phone.tpl"),m=require("common/header"),c=/\d{11}/g;return{init:e}});