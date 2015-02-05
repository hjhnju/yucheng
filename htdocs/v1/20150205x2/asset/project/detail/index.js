define("project/detail/index",function(require){function e(e){i=o("#toulist"),h.init(),o.extend(v,e),v.amountRest=+e.amountRest.replace(",",""),v.days=+e.days,v.userAmount=+e.userAmount.replace(",",""),v.interest=+e.interest,c.compile(u),t(),i.html(c.render("Loading")),m.remote({page:1,id:v.id}),m.on("success",function(e){if(e&&e.bizError)g.parent().addClass("show"),g.html(e.statusInfo);else{if(!e.list.length)return o("#page").html(""),void i.html(c.render("Error",{msg:"当前没有数据哟"}));if(!r)r=new f({total:+e.pageall,main:o("#page"),startPage:1}),r.on("change",function(e){m.remote({page:e.value,id:v.id})});r.render(+e.page);for(var t=0,n=e.list.length;n>t;t++){var s=e.list[t];s.timeInfo=d.unix(+s.create_time).format("YYYY-MM-DD HH:mm")}i.html(c.render("list",{list:e.list}))}})}function t(){if(o(".showproject").click(function(){o(this).closest(".project-main").attr("class","project-main project")}),o(".showfile").click(function(){o(this).closest(".project-main").attr("class","project-main file")}),o(".showrecord").click(function(){o(this).closest(".project-main").attr("class","project-main record")}),o(".confirm-all").click(function(){var e=o(".right-top-ipt-input");if(p.hasClass("show")&&p.removeClass("show"),!e[0].disabled)e.val(Math.min(v.userAmount,v.amountRest))}),o(".detail-error-cha").click(function(){o(this).parent().remove()}),o(".confirm-submit").click(function(){o("#invest-form").trigger("submit")}),o("#invest-form").on("submit",function(){var e=o(".right-top-ipt-input"),t=+o.trim(e.val());if(e[0].disabled)return!1;if(!t)return p.addClass("show").html("输入不能为空"),!1;if(isNaN(t))return p.addClass("show").html("输入内容不合法"),!1;if(v.amountRest<100&&t!==v.amountRest)return p.addClass("show").html("投标金额必须为"+v.amountRest+"元"),!1;if(t>v.userAmount)return p.addClass("show").html("可用余额不足"),!1;if(100>t&&t!==v.amountRest)return p.addClass("show").html("最小投标金额100元"),!1;if(t>v.amountRest)return p.addClass("show").html("可投金额不足"),!1;else return void 0}),o(".right-top-ipt-input").on({keyup:function(){var e=+o.trim(o(this).val()),t=o(".chongzhi-span");if(isNaN(e))return void t.html("0.00");else return void t.html(n(+o.trim(o(this).val())||0))},blur:function(){var e=+o.trim(o(this).val());if(isNaN(e))p.addClass("show").html("输入内容不合法")}}),o(".right-top-allmoney-time span").length>0)a.init(".right-top-allmoney-time span",1e3*v.sTime,1e3*v.eTime);a.init("#full-time",1e3*v.sTime,1e3*v.fullTime)}function n(e){var t=e*v.interest/100*v.days/365;return t=t.toFixed(2),s.addCommas(t)}var r,i,o=require("jquery"),s=require("common/util"),a=require("common/countDown"),l=require("common/Remoter"),m=new l("INVEST_DETAIL_START"),c=require("etpl"),u=require("./detail.tpl"),d=require("moment"),f=require("common/ui/Pager/Pager"),h=require("common/header"),g=(new l("INVEST_DETAIL_CONFIRM_ADD"),o("#detail-error-span")),p=o("#chongzhi-error"),v={};return{init:e}});