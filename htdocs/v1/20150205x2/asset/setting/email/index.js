define("setting/email/index",function(require){function e(){t(),a.compile(l),m.init()}function t(){r(".login-input").on({focus:function(){r(this).parent();r(this).next().addClass("hidden"),r(".error").html("")},blur:function(){var e=r(this).val();!e&&r(this).next().removeClass("hidden")}}),r("#email-img").click(function(){r(this).attr("src",c)}),r("#confirm").click(function(){n=r("#login-email").val();var e=r("#login-testing").val();s.remote({email:n,vericode:e,type:"checkEmailType"})}),s.on("success",function(e){if(e&&e.bizError)r(".error").html(e.statusInfo),r("#email-img").trigger("click");else{var t,i=6;r("#checkemial").html(a.render("list2nd",{email:n})),t=setInterval(function(){if(r("#time-span").text(--i+"秒后自动跳转"),0===i)clearInterval(t),window.location.href="/account/overview/index"},1e3)}})}var n,r=require("jquery"),i=require("common/Remoter"),o=require("common/config"),s=new i("EDIT_EMAILCONFIRM"),a=require("etpl"),l=require("./email.tpl"),c=o.URL.IMG_GET+"email",m=require("common/header");return{init:e}});