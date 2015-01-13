define("project/detail/index",["require","jquery","common/util","common/countDown","common/Remoter","etpl","./detail.tpl","moment","common/ui/Pager/Pager","common/header"],function(require){function e(e){f.init(),i.extend(p,e),p.amountRest=+e.amountRest.replace(",",""),p.days=+e.days,p.userAmount=+e.userAmount.replace(",",""),p.interest=+e.interest,m.compile(c),t(),l.remote({page:1,id:p.id}),l.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else{if(!r)r=new d({total:+e.pageall,main:i("#page"),startPage:1}),r.on("change",function(e){l.remote({page:e.value,id:p.id})});r.render(+e.page);for(var t=0,n=e.list.length;n>t;t++){var o=e.list[t];o.timeInfo=u.unix(+o.create_time).format("YYYY-MM-DD hh:mm:ss")}i("#toulist").html(m.render("list",{list:e.list}))}})}function t(){if(i(".showproject").click(function(){i(this).closest(".project-main").attr("class","project-main project")}),i(".showfile").click(function(){i(this).closest(".project-main").attr("class","project-main file")}),i(".showrecord").click(function(){i(this).closest(".project-main").attr("class","project-main record")}),i(".confirm-all").click(function(){i(".right-top-ipt-input").val(Math.min(p.userAmount,p.amountRest))}),i(".confirm-submit").click(function(){h.remote({id:p.id,amount:+i(".right-top-ipt-input").val()||0})}),i(".right-top-ipt-input").on({keydown:function(){var e=+i.trim(i(this).val()),t=Math.min(p.userAmount,p.amountRest);if(!isNaN(e)){if(e>t)i(this).val(t),e=t;i(".chongzhi-span").html(n(e))}},keyup:function(){var e=+i.trim(i(this).val()),t=Math.min(p.userAmount,p.amountRest);if(!isNaN(e))if(e>t)i(this).val(t)}}),i(".right-top-allmoney-time span").length>0)s.init(".right-top-allmoney-time span",1e3*p.sTime,1e3*p.eTime);s.init("#full-time",1e3*p.sTime,1e3*p.fullTime)}function n(e){var t=e*p.interest/100*p.days/365;return t=t.toFixed(2),o.addCommas(t)}var r,i=require("jquery"),o=require("common/util"),s=require("common/countDown"),a=require("common/Remoter"),l=new a("INVEST_DETAIL_START"),m=require("etpl"),c=require("./detail.tpl"),u=require("moment"),d=require("common/ui/Pager/Pager"),f=require("common/header"),h=new a("INVEST_DETAIL_CONFIRM_ADD"),p={};return{init:e}});