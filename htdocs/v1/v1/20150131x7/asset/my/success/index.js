define("my/success/index",["require","jquery","common/Remoter","common/config","common/header"],function(require){function e(){t()}function t(){n.init();var e,t=6;e=setInterval(function(){if(r("#time-span").text(--t+"秒后自动跳转"),0===t)clearInterval(e),window.location.href="/account/overview/index"},1e3)}var r=require("jquery"),n=(require("common/Remoter"),require("common/config"),require("common/header"));return{init:e}});