define("my/message/index",function(require){function e(){r.init();var e,l=n("#my-msg-list");t.compile(c),a.remote({status:m,page:1}),a.on("success",function(r){if(r.bizError)l.html(t.render("Error",r.statusInfo));else{if(!r.list.length)return l.html(t.render("Error",{msg:"您当前还没有消息哟"})),void n("#my-msg-pager").html("");if(!e)e=new o(n.extend({},s.pagerOpt,{main:n("#my-msg-pager"),total:+r.pageall})),e.on("change",function(e){a.remote({status:m,page:+e.value})});e.render(+r.page);for(var u=0,c=r.list.length;c>u;u++){var d=r.list[u];d.timeInfo=i.unix(+d.time).format("YYYY-MM-DD hh:mm")}l.html(t.render("msgList",{list:r.list}))}}),l.delegate(".msg-content-text","click",function(){var e=n(this).attr("data-id"),t=n(this).closest(".my-invest-item");if(t.removeClass("unread"),t.hasClass("current"))t.removeClass("current");else t.addClass("current"),u.remote({mid:e})}),l.delegate(".close-detail","click",function(){n(this).closest(".my-invest-item").removeClass("current")}),n(".my-invest-tab-item").click(function(){m=+n(this).attr("data-value"),n(".my-invest-tab-item").removeClass("current"),n(this).addClass("current"),a.remote({status:m,page:1})})}var n=require("jquery"),t=require("etpl"),r=require("common/header"),i=require("moment"),s=require("common/data"),o=require("common/ui/Pager/Pager"),l=require("common/Remoter"),a=new l("MY_MSG_LIST"),u=new l("MY_MSG_SETREAD_ADD"),c=require("./list.tpl"),m=0;return{init:e}});