define("my/account/index",["require","jquery","etpl","./line","common/header","common/Remoter"],function(require){function e(e){t.init();var o=n("#all-account-line");if(1===+e)s.remote();s.on("success",function(e){if(e.bizError)o.html(i.render("Error",{msg:e.statusInfo}));else r.render("all-account-line",e)})}var n=require("jquery"),i=require("etpl"),r=require("./line"),t=require("common/header"),o=require("common/Remoter"),s=new o("LINE_GET");return{init:e}});