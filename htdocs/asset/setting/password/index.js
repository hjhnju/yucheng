define("setting/password/index",function(require){function e(){s.init(),i(".login-input").on({focus:function(){i(this).parent().children(".user-lable").addClass("hidden"),i(this).parent().removeClass("current"),i(this).next().addClass("current"),t.html("")},blur:function(){var e=i(this).val();if(!e)return i(this).next().hasClass("hidden"),i(this).next().removeClass("hidden"),void i(this).parent().addClass("current");else return void 0}}),i("#login-user").blur(function(){var e=i(this).val();if(!e)t.html("用户名不能为空"),l.user=0;else l.user=1}),i("#login-phone").blur(function(){var e=i(this).val();if(!e)t.html("手机号不能为空"),l.phone=0;else l.phone=1,i("#sendsmscode").removeClass("disable")}),i("#login-testing").blur(function(){var e=i(this).val();if(!e)t.html("验证码不能为空"),l.testing=0;else l.testing=1}),i("#sendsmscode").click(function(){var e=i.trim(i("#login-phone").val());if(e&&!i(this).hasClass("disable"))r.remote({phone:e,type:"resetpwd"})}),r.on("success",function(e){var n=60;if(e&&e.bizError)t.html(e.statusInfo);else{var r=i("#testing-wait");r.text("60秒后重新发送"),r.addClass("show"),timer=setInterval(function(){if(r.text(--n+"秒后重新发送"),0>n)clearInterval(timer),r.removeClass("show")},1e3)}}),i("#login-reset").blur(function(){var e=i(this).val();if(!e)t.html("密码不能为空"),l.reset=0;else l.reset=1}),i("#login-affirm").blur(function(){var e=i("#login-reset").val(),n=i(this).val();if(e!==n)return void t.html("两次密码不一致");else return void 0}),i(".login-fastlogin").click(function(){var e=1,n=i("#login-user").val(),r=i("#login-phone").val(),s=i("#login-testing").val(),a=i("#login-reset").val(),u=i("#login-affirm").val();for(var c in l)if(l.hasOwnProperty(c))if(!l[c])i("#login-"+c).trigger("blur"),e=0;if(a!==u)return void t.html("两次密码不一致");else return void(e&&o.remote("post",{user:n,phone:r,vericode:s,passwd:a,type:"resetpwd"}))}),o.on("success",function(e){if(e&&e.bizError)t.html(e.statusInfo);else;})}var i=require("jquery"),n=(i(".login-username").children(".user-lable"),require("common/Remoter")),r=new n("REGIST_SENDSMSCODE_CHECK"),t=i(".error"),o=new n("USER_REGISTAPI_MODIFYPWD"),s=require("common/header"),l={reset:0,testing:0,phone:0,user:0};return{init:e}});