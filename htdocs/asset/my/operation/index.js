/*! 2015 Baidu Inc. All Rights Reserved */
define("my/operation/index",function(require){function e(e){l.pagesize=+e.pagesize,s.compile(o),t(),r.remote(),n=new a({total:+e.pageall,main:$("#page"),startPage:1}),n.render(+e.page),n.on("change",function(e){l.page=e.value,r.remote(l)})}function t(){$("#operation-data").delegate(".time-data-type-link","click",function(){$("#operation-data .time-data-type-link").removeClass("current"),$(this).addClass("current"),$("#time-start, #time-end").val(""),m.stime=0,m.etime=0,l.data=+$(this).attr("data-value"),l.page=1,r.remote("post",l)}),$("#operation-type").delegate(".time-data-type-link","click",function(){$("#operation-type .time-data-type-link").removeClass("current"),$(this).addClass("current"),l.type=+$(this).attr("data-value"),l.page=1,r.remote("post",l)}),$("#time-start").datepicker({format:"yyyy-mm-dd"}).on("changeDate",function(e){if(m.etime&&e.date.getTime()>m.etime)alert("开始时间不得大于结束时间"),$(this).val("");else m.stime=e.date.getTime()}),$("#time-end").datepicker({format:"yyyy-mm-dd"}).on("changeDate",function(e){if(e.date.getTime()<m.stime)alert("结束时间必须大于开始时间"),$(this).val("");else m.etime=e.date.getTime()}),$(".time-data-search").click(function(){if(m.stime&&m.etime)$("#operation-data .time-data-type-link").removeClass("current"),l.page=1,r.remote($.extend({},l,{startTime:m.stime/1e3,endTime:m.etime/1e3}));else alert("请选择开始时间和结束时间")}),r.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else n.setOpt("total",+e.pageall),n.render(+e.page),$(".operation-list").html(s.render("typeList",{list:e.list}))})}var n,i=require("common/Remoter"),r=new i("ACCOUNT_CASH_LIST"),s=require("etpl"),o=require("./operation.tpl"),a=(require("moment"),require("common/ui/Pager/Pager")),l={type:0,data:0,page:1,pagesize:10},m={stime:0,etime:0};return{init:e}});