define("my/account/index",["require","jquery","etpl","./line","common/header","common/Remoter"],function(require){function e(e){t.init();var o=r("#all-account-line");if(1===+e)s.remote();s.on("success",function(e){if(e.bizError)o.html(n.render("Error",e.statusInfo));else i.render("all-account-line",e)})}var r=require("jquery"),n=require("etpl"),i=require("./line"),t=require("common/header"),o=require("common/Remoter"),s=new o("LINE_GET");return{init:e}});