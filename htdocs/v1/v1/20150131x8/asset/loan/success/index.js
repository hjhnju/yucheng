define("loan/success/index",["require","jquery","common/Remoter","common/config","common/header","etpl","./success.tpl"],function(require){function e(){t(),r.init(),i.compile(o),n("#success-id").html(i.render("Loan"))}function t(){var e,t=6;e=setInterval(function(){if(n("#time-span").text(--t+"秒后自动跳转"),0===t)clearInterval(e),window.location.href="/index/index"},1e3)}var n=require("jquery"),r=(require("common/Remoter"),require("common/config"),require("common/header")),i=require("etpl"),o=require("./success.tpl");return{init:e}});