define("my/invest/index",["require","jquery","etpl","moment","common/data","common/header","common/ui/Pager/Pager","common/Remoter","./list.tpl"],function(require){function e(){l=a("#my-invest-list"),d.init(),u.compile(w),h.remote({page:1}),r(),n()}function n(){a(".my-invest-tab-item").click(function(){if(!a(this).hasClass("current"))a(".my-invest-tab-item").removeClass("current"),a(this).addClass("current"),b=+a.trim(a(this).attr("data-value")),i(1)}),a(".my-invest-list").delegate(".view-plan-btn","click",function(){var e=a(".my-invest-item");if(a(this).hasClass("current"))return e.removeClass("current"),void a(this).removeClass("current");var n=a.trim(a(this).attr("data-id"));if(y=a(this),e.removeClass("current"),a(".view-plan-btn").removeClass("current"),a(this).closest(".my-invest-item").addClass("current"),a(this).addClass("current"),!a(this).hasClass("hasDetail"))v.remote({id:n})})}function r(){h.on("success",function(e){if(e.bizError)o(e);else{if(!e.list.length)return s=null,a("#my-invest-pager").html(""),void l.html(u.render("Error",{msg:"您当前没有数据哟"}));if(!s)s=new f(a.extend({},m.pagerOpt,{main:a("#my-invest-pager"),total:+e.pageall})),s.on("change",function(e){i(e.value)});s.render(+e.page),t("returnMoneyList",e)}}),h.on("fail",function(e){o(e)}),v.on("success",function(e){var n=a(y).closest(".my-invest-item").addClass("current").find(".my-invest-detail");if(e.bizError)n.render(u.render("Error",{msg:e.statusInfo}));else{if(!y)return;a(y).addClass("hasDetail");for(var r=0,i=e.list.length;i>r;r++)e.list[r].timeInfo=c.unix(e.list[r].time).format(I);n.html(u.render("returnMoneyDetail",{data:e}))}}),p.on("success",function(e){if(e.bizError)o(e);else{if(!e.list.length)return s=null,a("#my-invest-pager").html(""),void l.html(u.render("Error",{msg:"您当前没有数据哟"}));if(!s)s=new f(a.extend({},m.pagerOpt,{main:a("#my-invest-pager"),total:+e.pageall})),s.on("change",function(e){i(e.value)});s.render(+e.page),t("tenderingList",e)}}),p.on("fail",function(e){o(e)}),E.on("success",function(e){if(e.bizError)o(e);else{if(!e.list.length)return s=null,a("#my-invest-pager").html(""),void l.html(u.render("Error",{msg:"您当前没有数据哟"}));if(!s)s=new f(a.extend({},m.pagerOpt,{main:a("#my-invest-pager"),total:+e.pageall})),s.on("change",function(e){i(e.value)});s.render(+e.page),t("endedList",e)}}),E.on("fail",function(e){o(e)}),C.on("success",function(e){if(e.bizError)o(e);else{if(!e.list.length)return s=null,a("#my-invest-pager").html(""),void l.html(u.render("Error",{msg:"您当前没有数据哟"}));if(!s)s=new f(a.extend({},m.pagerOpt,{main:a("#my-invest-pager"),total:+e.pageall})),s.on("change",function(e){i(e.value)});s.render(+e.page),t("tenderFailList",e)}}),C.on("fail",function(e){o(e)})}function i(e){switch(l.html(u.render("Loading")),a("#my-invest-pager").html(""),b){case 1:h.remote({page:e});break;case 2:p.remote({page:e});break;case 3:E.remote({page:e});break;case 4:C.remote({page:e})}}function t(e,n){s.setOpt("pageall",+n.pageall),s.render(+n.page);for(var r=0,i=n.list.length;i>r;r++)if(n.list[r].timeInfo=c.unix(n.list[r].tenderTime).format("YYYY-MM-DD hh:mm"),n.list[r].endTime)n.list[r].endTimeInfo=c.unix(n.list[r].endTime).format(I);l.html(u.render(e,{list:n.list}))}function o(e){l.render(u.render("Error",{msg:e.statusInfo}))}var s,l,a=require("jquery"),u=require("etpl"),c=require("moment"),m=require("common/data"),d=require("common/header"),f=require("common/ui/Pager/Pager"),g=require("common/Remoter"),h=new g("MY_INVEST_GET"),v=new g("MY_INVEST_DETAIL"),p=new g("MY_INVEST_TENDERING"),E=new g("MY_INVEST_ENDED"),C=new g("MY_INVEST_TENDERFAIL"),w=require("./list.tpl"),b=1,y=null,I="YYYY-MM-DD";return{init:e}});