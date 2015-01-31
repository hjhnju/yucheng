define("project/detail/index",function(require){function e(e){r=o("#toulist"),h.init(),o.extend(p,e),p.amountRest=+e.amountRest.replace(",",""),p.days=+e.days,p.userAmount=+e.userAmount.replace(",",""),p.interest=+e.interest,c.compile(u),t(),r.html(c.render("Loading")),m.remote({page:1,id:p.id}),m.on("success",function(e){if(e&&e.bizError)g.parent().addClass("show"),g.html(e.statusInfo);else{if(!e.list.length)return o("#page").html(""),void r.html(c.render("Error",{msg:"当前没有数据哟"}));if(!i)i=new f({total:+e.pageall,main:o("#page"),startPage:1}),i.on("change",function(e){m.remote({page:e.value,id:p.id})});i.render(+e.page);for(var t=0,n=e.list.length;n>t;t++){var s=e.list[t];s.timeInfo=d.unix(+s.create_time).format("YYYY-MM-DD hh:mm")}r.html(c.render("list",{list:e.list}))}})}function t(){if(o(".showproject").click(function(){o(this).closest(".project-main").attr("class","project-main project")}),o(".showfile").click(function(){o(this).closest(".project-main").attr("class","project-main file")}),o(".showrecord").click(function(){o(this).closest(".project-main").attr("class","project-main record")}),o(".confirm-all").click(function(){var e=o(".right-top-ipt-input"),t=o(".chongzhi-error");t.hasClass("show")&&t.removeClass("show"),!e[0].disabled&&e.val(Math.min(p.userAmount,p.amountRest))}),o(".detail-error-cha").click(function(){o(this).parent().remove()}),o(".confirm-submit").click(function(){var e=o(".right-top-ipt-input"),t=o(".chongzhi-error");!e[0].disabled&&!t.hasClass("show")&&o("#invest-form").get(0).submit()}),o(".right-top-ipt-input").on({keydown:function(){var e=+o.trim(o(this).val()),t=Math.min(p.userAmount,p.amountRest),i=o(".chongzhi-error");if(isNaN(e))return void i.addClass("show").html("输入内容不合法");if(e>t)i.addClass("show").html("投资金额不得超过可用余额和可投金额"),e>p.amountRest&&o(this).val(p.amountRest)&&i.removeClass("show");else i.removeClass("show");o(".chongzhi-span").html(n(+o.trim(o(this).val())||0))},keyup:function(){var e=+o.trim(o(this).val()),t=Math.min(p.userAmount,p.amountRest),i=o(".chongzhi-error");if(isNaN(e))return void i.addClass("show").html("输入内容不合法");if(e>t)i.addClass("show").html("投资金额不得超过可用余额和可投金额"),e>p.amountRest&&o(this).val(p.amountRest)&&i.removeClass("show");else i.removeClass("show");o(".chongzhi-span").html(n(+o.trim(o(this).val())||0))}}),o(".right-top-allmoney-time span").length>0)a.init(".right-top-allmoney-time span",1e3*p.sTime,1e3*p.eTime);a.init("#full-time",1e3*p.sTime,1e3*p.fullTime)}function n(e){var t=e*p.interest/100*p.days/365;return t=t.toFixed(2),s.addCommas(t)}var i,r,o=require("jquery"),s=require("common/util"),a=require("common/countDown"),l=require("common/Remoter"),m=new l("INVEST_DETAIL_START"),c=require("etpl"),u=require("./detail.tpl"),d=require("moment"),f=require("common/ui/Pager/Pager"),h=require("common/header"),g=(new l("INVEST_DETAIL_CONFIRM_ADD"),o("#detail-error-span")),p={};return{init:e}});