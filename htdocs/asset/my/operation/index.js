define("my/operation/index",["require","common/Remoter","etpl","./operation.tpl","moment","common/ui/Pager/Pager","common/header"],function(require){function e(e){l.init(),m.pagesize=+e.pagesize,o.compile(s),t(),i.remote(),r=new a({total:+e.pageall,main:$("#page"),startPage:1}),r.render(+e.page),r.on("change",function(e){m.page=e.value,i.remote(m)})}function t(){$("#operation-data").delegate(".time-data-type-link","click",function(){$("#operation-data .time-data-type-link").removeClass("current"),$(this).addClass("current"),$("#time-start, #time-end").val(""),c.stime=0,c.etime=0,m.data=+$(this).attr("data-value"),m.page=1,i.remote("post",m)}),$("#operation-type").delegate(".time-data-type-link","click",function(){$("#operation-type .time-data-type-link").removeClass("current"),$(this).addClass("current"),m.type=+$(this).attr("data-value"),m.page=1,i.remote("post",m)}),$("#time-start").datepicker({format:"yyyy-mm-dd"}).on("changeDate",function(e){if(c.etime&&e.date.getTime()>c.etime)alert("开始时间不得大于结束时间"),$(this).val("");else c.stime=e.date.getTime()}),$("#time-end").datepicker({format:"yyyy-mm-dd"}).on("changeDate",function(e){if(e.date.getTime()<c.stime)alert("结束时间必须大于开始时间"),$(this).val("");else c.etime=e.date.getTime()}),$(".time-data-search").click(function(){if(c.stime&&c.etime)$("#operation-data .time-data-type-link").removeClass("current"),m.page=1,i.remote($.extend({},m,{startTime:c.stime/1e3,endTime:c.etime/1e3}));else alert("请选择开始时间和结束时间")}),i.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else r.setOpt("total",+e.pageall),r.render(+e.page),$(".operation-list").html(o.render("typeList",{list:e.list}))})}var r,n=require("common/Remoter"),i=new n("ACCOUNT_CASH_LIST"),o=require("etpl"),s=require("./operation.tpl"),a=(require("moment"),require("common/ui/Pager/Pager")),l=require("common/header"),m={type:0,data:0,page:1,pagesize:10},c={stime:0,etime:0};return{init:e}});