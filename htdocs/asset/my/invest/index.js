define("my/invest/index",function(require){function e(){l=a("#my-invest-list"),d.init(),u.compile(w),h.remote({page:1}),i(),n()}function n(){a(".my-invest-tab-item").click(function(){if(!a(this).hasClass("current"))a(".my-invest-tab-item").removeClass("current"),a(this).addClass("current"),b=+a.trim(a(this).attr("data-value")),r(1)}),a(".my-invest-list").delegate(".view-plan-btn","click",function(){var e=a(".my-invest-item");if(a(this).hasClass("current"))return e.removeClass("current"),void a(this).removeClass("current");var n=a.trim(a(this).attr("data-id"));if(I=a(this),e.removeClass("current"),a(".view-plan-btn").removeClass("current"),a(this).closest(".my-invest-item").addClass("current"),a(this).addClass("current"),!a(this).hasClass("hasDetail"))v.remote({id:n})})}function i(){h.on("success",function(e){if(e.bizError)s(e);else{if(!o)o=new m(a.extend({},f.pagerOpt,{main:a("#my-invest-pager"),total:+e.pageall})),o.render(+e.page),o.on("change",function(e){r(e.value)});t("returnMoneyList",e)}}),h.on("fail",function(e){s(e)}),v.on("success",function(e){var n=a(I).closest(".my-invest-item").addClass("current").find(".my-invest-detail");if(e.bizError)n.render(u.render("Error",{msg:e.statusInfo}));else{if(!I)return;a(I).addClass("hasDetail");for(var i=0,r=e.list.length;r>i;i++)e.list[i].timeInfo=c.unix(e.list[i].time).format(y);n.html(u.render("returnMoneyDetail",{data:e}))}}),p.on("success",function(e){if(e.bizError)s(e);else t("tenderingList",e)}),p.on("fail",function(e){s(e)}),E.on("success",function(e){if(e.bizError)s(e);else t("endedList",e)}),E.on("fail",function(e){s(e)}),C.on("success",function(e){if(e.bizError)s(e);else t("tenderFailList",e)}),C.on("fail",function(e){s(e)})}function r(e){switch(l.html(u.render("Loading")),a("#my-invest-pager").html(""),b){case 1:h.remote({page:e});break;case 2:p.remote({page:e});break;case 3:E.remote({page:e});break;case 4:C.remote({page:e})}}function t(e,n){o.setOpt("pageall",+n.pageall),o.render(+n.page);for(var i=0,r=n.list.length;r>i;i++)if(n.list[i].timeInfo=c.unix(n.list[i].tenderTime).format("YYYY-MM-DD hh:mm"),n.list[i].endTime)n.list[i].endTimeInfo=c.unix(n.list[i].endTime).format(y);l.html(u.render(e,{list:n.list}))}function s(e){l.render(u.render("Error",{msg:e.statusInfo}))}var o,l,a=require("jquery"),u=require("etpl"),c=require("moment"),f=require("common/data"),d=require("common/header"),m=require("common/ui/Pager/Pager"),g=require("common/Remoter"),h=new g("MY_INVEST_GET"),v=new g("MY_INVEST_DETAIL"),p=new g("MY_INVEST_TENDERING"),E=new g("MY_INVEST_ENDED"),C=new g("MY_INVEST_TENDERFAIL"),w=require("./list.tpl"),b=1,I=null,y="YYYY-MM-DD";return{init:e}});