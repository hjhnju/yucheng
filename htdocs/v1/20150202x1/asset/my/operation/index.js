define("my/operation/index",["require","common/Remoter","etpl","./operation.tpl","moment","common/ui/Pager/Pager","common/header"],function(require){function e(e){l.init(),c.pagesize=+e.pagesize,o.compile(s),t(),i.remote(c),r=new a({total:+e.pageall,main:$("#page"),startPage:1}),r.render(+e.page),r.on("change",function(e){c.page=e.value,m.html(o.render("Loading")),i.remote(c)})}function t(){$("#operation-data").delegate(".time-data-type-link","click",function(){$("#operation-data .time-data-type-link").removeClass("current"),$(this).addClass("current"),$("#time-start, #time-end").val(""),c.startTime=0,c.endTime=0,c.data=+$(this).attr("data-value"),c.page=1,m.html(o.render("Loading")),i.remote("post",c)}),$("#operation-type").delegate(".time-data-type-link","click",function(){$("#operation-type .time-data-type-link").removeClass("current"),$(this).addClass("current"),c.type=+$(this).attr("data-value"),c.page=1,m.html(o.render("Loading")),i.remote("post",c)}),$("#time-start").datepicker({format:"yyyy-mm-dd"}).on("changeDate",function(e){if(u.etime&&e.date.getTime()>u.etime)alert("开始时间不得大于结束时间"),$(this).val("");else u.stime=e.date.getTime()}),$("#time-end").datepicker({format:"yyyy-mm-dd"}).on("changeDate",function(e){if(e.date.getTime()<u.stime)alert("结束时间必须大于开始时间"),$(this).val("");else u.etime=e.date.getTime()}),$(".time-data-search").click(function(){if(u.stime&&u.etime)$("#operation-data .time-data-type-link").removeClass("current"),c.page=1,m.html(o.render("Loading")),c.startTime=u.stime/1e3,c.endTime=u.etime/1e3,i.remote(c);else alert("请选择开始时间和结束时间")}),i.on("success",function(e){if(e&&e.bizError)m.html(o.render("Error",{msg:e.statusInfo}));else{if(!e.list.length)return $(".operation-list").html(o.render("Error",{msg:"当前还没有数据哟"})),void $("#page").html("");r.setOpt("total",+e.pageall),r.render(+e.page),m.html(o.render("typeList",{list:e.list}))}}),i.on("fail",function(e){m.html(o.render("Error",{msg:e}))})}var r,n=require("common/Remoter"),i=new n("ACCOUNT_CASH_LIST"),o=require("etpl"),s=require("./operation.tpl"),a=(require("moment"),require("common/ui/Pager/Pager")),l=require("common/header"),m=$(".operation-list"),c={type:1,data:1,page:1,pagesize:10,startTime:0,endTime:0},u={stime:0,etime:0};return{init:e}});