define("setting/login/index",["require","jquery","../common/picScroll","common/header","common/Remoter","common/config"],function(require){function n(){t.init(),r.init(),i(),o()}function i(){e(".login .login-input").on({focus:function(){var n=e(this).parent(),i=n.children(".username-error");n.removeClass("current"),i.html(""),e(this).next().addClass("hidden")},blur:function(){var n=e.trim(e(this).val());!n&&e(this).next().removeClass("hidden")}}),e("#login-testing").focus(function(){m.html("")}),u.click(function(n){n.preventDefault(),e(this).attr("src",g)}),e(".login .login-fastlogin").click(function(n){n.preventDefault();var i=e.trim(e("#login-user").val()),o=e.trim(e("#login-pwd").val()),r=e.trim(e("#login-testing").val());if(!i||!o)return void m.html("用户名或密码不能为空");if(!e(".login-username").hasClass("login-display-none")){if(!r)return void m.html("验证码不能为空");c.remote({name:i,passwd:o,imagecode:r,type:"login"})}else c.remote({name:i,passwd:o})}),e("#login-pwd").keyup(function(n){if(13===n.keyCode)e(".login-fastlogin").trigger("click"),e(this).trigger("blur"),console.log(111)}),e("#login-testing").keyup(function(n){if(13===n.keyCode)e(".login-fastlogin").trigger("click"),e(this).trigger("blur"),console.log(111)})}function o(){c.on("success",function(n){if(n.imgCode){var i=e(".login-display-none:eq(0)");i.removeClass("login-display-none"),u.attr("src",n.data.url)}else if(n.bizError)e("#login-error").html(n.statusInfo);else window.location.href="/account/overview/index"}),a.on("success",function(n){if(n.bizError)alert(n.statusInfo);else e("#login-testing-error").html('<span class="username-error-span"></span>')})}var e=require("jquery"),r=require("../common/picScroll"),t=require("common/header"),l=require("common/Remoter"),s=require("common/config"),c=new l("LOGIN_INDEX_CHECK"),a=new l("LOGIN_IMGCODE_CHECK"),g=s.URL.IMG_GET+"login",m=e("#login-error"),u=e("#login-img-url");return{init:n}});