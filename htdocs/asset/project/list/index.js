define("project/list/index",["require","jquery","common/Remoter","etpl","./list.tpl","common/ui/Pager/Pager","common/header"],function(require){function e(e){r(".nav-item-link:eq(0)").addClass("current"),m.init(),c.pagesize=+e.pagesize,s.compile(a),t(),n=new l({total:+e.pageall,main:r("#test2"),startPage:1}),n.render(+e.page),n.on("change",function(e){c.page=e.value,o.remote(c)})}function t(){r(".type_id").click(function(){r(".type_id").removeClass("current"),r(this).addClass("current"),c.type_id=+r(this).attr("data-value"),c.page=1,o.remote("post",c)}),o.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else n.setOpt("total",+e.pageall),n.render(+e.page),r("#invest-main").html(s.render("list",{list:e.list}))}),r(".cat_id").click(function(){r(".cat_id").removeClass("current"),r(this).addClass("current"),c.cat_id=+r(this).attr("data-value"),c.page=1,o.remote("post",c)}),r(".qixian").click(function(){r(".qixian").removeClass("current"),r(this).addClass("current"),c.duration=+r(this).attr("data-value"),o.remote("post",c)})}var n,r=require("jquery"),i=require("common/Remoter"),o=new i("INVEST_LIST"),s=require("etpl"),a=require("./list.tpl"),l=require("common/ui/Pager/Pager"),m=require("common/header"),c={type_id:0,cat_id:0,duration:0,page:1,pagesize:10};return{init:e}});