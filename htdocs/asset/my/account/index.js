/*! 2014 Baidu Inc. All Rights Reserved */
define("my/account/index",["require","jquery","etpl","./line","common/Remoter"],function(require){function e(e){var t=r("#all-account-line");if(1===+e)o.remote();o.on("success",function(e){if(e.bizError)t.html(n.render("Error",e.statusInfo));else i.render("all-account-line",e)})}var r=require("jquery"),n=require("etpl"),i=require("./line"),t=require("common/Remoter"),o=new t("LINE_GET");return{init:e}});