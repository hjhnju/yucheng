define("setting/login/index",["require","jquery","../common/picScroll","common/header","common/Remoter","common/config"],function(require){function n(n){o=n||"login",l.init(),t.init(),i(),e()}function i(){r(".login .login-input").on({focus:function(){var n=r(this).parent(),i=n.children(".username-error");n.removeClass("current"),i.html(""),r(this).next().addClass("hidden")},blur:function(){var n=r.trim(r(this).val());!n&&r(this).next().removeClass("hidden")}}),r("#login-testing").focus(function(){u.html("")}),f.click(function(n){n.preventDefault(),r(this).attr("src",m)}),r(".login .login-fastlogin").click(function(n){n.preventDefault();var i=r.trim(r("#login-user").val()),e=r.trim(r("#login-pwd").val()),t=r.trim(r("#login-testing").val());if(!i||!e)return void u.html("用户名或密码不能为空");if(!r(".login-username").hasClass("login-display-none")){if(!t)return void u.html("验证码不能为空");a.remote({name:i,passwd:e,imagecode:t,type:o})}else a.remote({name:i,passwd:e,type:o})}),r("#login-pwd").keyup(function(n){if(13===n.keyCode)r(".login-fastlogin").trigger("click"),r(this).trigger("blur")}),r("#login-testing").keyup(function(n){if(13===n.keyCode)r(".login-fastlogin").trigger("click"),r(this).trigger("blur")})}function e(){a.on("success",function(n){if(n.imgCode){var i=r(".login-display-none:eq(0)");i.removeClass("login-display-none"),f.attr("src",n.data.url)}else if(n.bizError)r("#login-error").html(n.statusInfo),f.trigger("click");else window.location.href="/account/overview/index"}),g.on("success",function(n){if(n.bizError)alert(n.statusInfo);else r("#login-testing-error").html('<span class="username-error-span"></span>')})}var o,r=require("jquery"),t=require("../common/picScroll"),l=require("common/header"),s=require("common/Remoter"),c=require("common/config"),a=new s("LOGIN_INDEX_CHECK"),g=new s("LOGIN_IMGCODE_CHECK"),m=c.URL.IMG_GET+"login",u=r("#login-error"),f=r("#login-img-url");return{init:n}});