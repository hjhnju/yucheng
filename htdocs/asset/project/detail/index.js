define("project/detail/index",function(require){function e(e){r=o("#toulist"),h.init(),o.extend(v,e),v.amountRest=+e.amountRest.replace(",",""),v.days=+e.days,v.userAmount=+e.userAmount.replace(",",""),v.interest=+e.interest,c.compile(u),t(),r.html(c.render("Loading")),m.remote({page:1,id:v.id}),m.on("success",function(e){if(e&&e.bizError)p.parent().addClass("show"),p.html(e.statusInfo);else{if(!e.list.length)return o("#page").html(""),void r.html(c.render("Error",{msg:"当前没有数据哟"}));if(!i)i=new f({total:+e.pageall,main:o("#page"),startPage:1}),i.on("change",function(e){m.remote({page:e.value,id:v.id})});i.render(+e.page);for(var t=0,n=e.list.length;n>t;t++){var s=e.list[t];s.timeInfo=d.unix(+s.create_time).format("YYYY-MM-DD hh:mm:ss")}r.html(c.render("list",{list:e.list}))}})}function t(){if(o(".showproject").click(function(){o(this).closest(".project-main").attr("class","project-main project")}),o(".showfile").click(function(){o(this).closest(".project-main").attr("class","project-main file")}),o(".showrecord").click(function(){o(this).closest(".project-main").attr("class","project-main record")}),o(".confirm-all").click(function(){o(".right-top-ipt-input").val(Math.min(v.userAmount,v.amountRest))}),o(".detail-error-cha").click(function(){o(this).parent().remove()}),o(".confirm-submit").click(function(){g.remote({id:v.id,amount:+o(".right-top-ipt-input").val()||0})}),o(".right-top-ipt-input").on({keydown:function(){var e=+o.trim(o(this).val()),t=Math.min(v.userAmount,v.amountRest);if(!isNaN(e)){if(e>t)o(this).val(t),e=t;o(".chongzhi-span").html(n(e))}},keyup:function(){var e=+o.trim(o(this).val()),t=Math.min(v.userAmount,v.amountRest);if(!isNaN(e))if(e>t)o(this).val(t)}}),o(".right-top-allmoney-time span").length>0)a.init(".right-top-allmoney-time span",1e3*v.sTime,1e3*v.eTime);a.init("#full-time",1e3*v.sTime,1e3*v.fullTime)}function n(e){var t=e*v.interest/100*v.days/365;return t=t.toFixed(2),s.addCommas(t)}var i,r,o=require("jquery"),s=require("common/util"),a=require("common/countDown"),l=require("common/Remoter"),m=new l("INVEST_DETAIL_START"),c=require("etpl"),u=require("./detail.tpl"),d=require("moment"),f=require("common/ui/Pager/Pager"),h=require("common/header"),g=new l("INVEST_DETAIL_CONFIRM_ADD"),p=o("#detail-error-span"),v={};return{init:e}});