/*! 2015 Baidu Inc. All Rights Reserved */
define("project/list/index",function(require){function e(e){c.pagesize=+e.pagesize,s.compile(a),t(),n=new l({total:+e.pageall,main:i("#test2"),startPage:1}),n.render(+e.page),n.on("change",function(e){c.page=e.value,o.remote(c)})}function t(){i(".type_id").click(function(){i(".type_id").removeClass("current"),i(this).addClass("current"),c.type_id=+i(this).attr("data-value"),c.page=1,o.remote("post",c)}),o.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else n.setOpt("total",+e.pageall),n.render(+e.page),i("#invest-main").html(s.render("list",{list:e.list}))}),i(".cat_id").click(function(){i(".cat_id").removeClass("current"),i(this).addClass("current"),c.cat_id=+i(this).attr("data-value"),c.page=1,o.remote("post",c)}),i(".qixian").click(function(){i(".qixian").removeClass("current"),i(this).addClass("current"),c.duration=+i(this).attr("data-value"),o.remote("post",c)})}var n,i=require("jquery"),r=require("common/Remoter"),o=new r("INVEST_LIST"),s=require("etpl"),a=require("./list.tpl"),l=require("common/ui/Pager/Pager"),c={type_id:0,cat_id:0,duration:0,page:1,pagesize:10};return{init:e}});