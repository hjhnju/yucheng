define("my/topup/index",function(require){function e(){r.init(),t()}function t(){n(".topup-select-con-box").click(function(){n(".topup-select-con-box").removeClass("current"),n(this).addClass("current");var e=n(this).index();n(".topup-prompt-box").removeClass("current"),n(".topup-prompt-box:eq("+e+")").addClass("current"),n(this).find(".topup-select-ipt")[0].checked=!0}),n("#pay").click(function(){n("#topup-form").trigger("submit")}),n("#topup-form").on("submit",function(){var e=n("#box-ipt").val(),t=n(".topup-money-box-ipt").val(),r=n("#topup-error");if(!e)return r.html("充值金额不能为空"),!1;if(isNaN(t))return r.html("输入金额必须是数字"),!1;else return void r.html("")})}{var n=require("jquery"),r=require("common/header");require("common/Remoter"),n("#map-one"),n("#map-day")}return{init:e}});