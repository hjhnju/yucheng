/*! 2014 Baidu Inc. All Rights Reserved */
define("setting/login/index",["require","jquery","../common/picScroll","common/header","common/Remoter"],function(require){function n(){t.init(),o.init(),e(),r()}function e(){i(".login .login-input").on({focus:function(){var n=i(this).parent(),e=n.children(".username-error");n.removeClass("current"),e.html(""),i(this).next().addClass("hidden")},blur:function(){var n=i.trim(i(this).val());!n&&i(this).next().removeClass("hidden")}}),i("#login-testing").blur(function(){var n=i.trim(i(this).val());if(!n)return i(this).parent().addClass("current"),void i("#login-testing-error").html("验证码不能为空");else return void a.remove({imgcode:n})}),m.click(function(n){n.preventDefault(),c.remote()}),i(".login .login-fastlogin").click(function(n){n.preventDefault();var e=i.trim(i("#login-user").val()),r=i.trim(i("#login-pwd").val());if(!e||!r)u.html("用户名或密码不能为空");else l.remote({name:e,passwd:r})})}function r(){l.on("success",function(n){if(n.imgCode){var e=i(".login-display-none:eq(0)");e.removeClass("login-display-none"),m.attr("src",n.data.url)}else if(n.bizError)i("#login-error").html(n.statusInfo);else window.location.href="http://www.baidu.com"}),c.on("success",function(n){if(n.bizError)alert(n.statusInfo);else m.attr("src",n.url)}),a.on("success",function(n){if(n.bizError)alert(n.statusInfo);else i("#login-testing-error").html('<span class="username-error-span"></span>')})}var i=require("jquery"),o=require("../common/picScroll"),t=require("common/header"),s=require("common/Remoter"),l=new s("LOGIN_INDEX_CHECK"),a=new s("LOGIN_IMGCODE_CHECK"),c=new s("LOGIN_IMGCODE_ADD"),u=i("#login-error"),m=i("#login-img-url");return{init:n}});