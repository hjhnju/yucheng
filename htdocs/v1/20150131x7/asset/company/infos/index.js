define("company/infos/index",function(require){function e(e){var l=i("#infos-list");t=e.type,o.init(),r.compile(m),n=new s(i.extend({},a.pagerOpt,{total:+e.pageall,main:i("#pager")})),n.on("change",function(e){l.html(r.render("Loading")),c.remote({page:+e.value,type:t})}),n.render(+e.page),c.on("success",function(e){if(e.bizError)l.html(r.render("Error",{msg:e.statusInfo}));else{if(!e.list.length){if("post"===t)l.html(r.render("Error",{msg:"当前没有公告"}));else l.html(r.render("Error",{msg:"当前没有媒体信息"}));return void i("#pager").html("")}n.render(+e.page),l.html(r.render("infosList",{list:e.list}))}})}var t,n,i=require("jquery"),r=require("etpl"),o=require("common/header"),s=require("common/ui/Pager/Pager"),a=require("common/data"),l=require("common/Remoter"),c=new l("COMPANY_INFOS_LIST"),m=require("./list.tpl");return{init:e}});