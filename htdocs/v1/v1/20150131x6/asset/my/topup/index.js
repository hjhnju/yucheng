define("my/topup/index",["require","jquery","common/header","common/Remoter"],function(require){function e(){n.init(),t()}function t(){r(".topup-select-con-box").click(function(){r(".topup-select-con-box").removeClass("current"),r(this).addClass("current");var e=r(this).index();r(".topup-prompt-box").removeClass("current"),r(".topup-prompt-box:eq("+e+")").addClass("current"),r(this).find(".topup-select-ipt")[0].checked=!0}),r("#pay").click(function(){var e=r("#box-ipt").val(),t=r(".topup-money-box-ipt").val(),n=r("#topup-error");if(!e)return void n.html("充值金额不能为空");if(isNaN(t))return void n.html("输入金额必须是数字");else return n.html(""),void r("#topup-form")[0].submit()})}{var r=require("jquery"),n=require("common/header");require("common/Remoter"),r("#map-one"),r("#map-day")}return{init:e}});