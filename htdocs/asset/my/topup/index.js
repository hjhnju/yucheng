define("my/topup/index",function(require){function e(){n.init(),t()}function t(){r(".topup-select-con-box").click(function(){r(".topup-select-con-box").removeClass("current"),r(this).addClass("current"),r(this).find(".topup-select-ipt").prop("checked",!0)}),r("#pay").click(function(){var e=r("#box-ipt").val(),t=r(".topup-select-con-box.current").find(".topup-select-ipt").attr("data-value"),n=r(".topup-money-box-ipt").val(),i=r("#topup-error");if(!e)return void i.html("充值金额不能为空");if(isNaN(n))return void i.html("输入金额必须是数字");else return i.html(""),void o.remote({id:t,value:e})}),o.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else window.location.href="/mock/entry/my/success.php"})}var r=require("jquery"),n=require("common/header"),i=require("common/Remoter"),o=new i("ACCOUNT_CASH_RECHARGE_ADD");return{init:e}});