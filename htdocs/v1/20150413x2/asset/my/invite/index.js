define("my/invite/index",["require","common/util","common/header","common/Remoter","common/config"],function(require){function e(e){i=e,o.init(),t()}function t(){$(".table-tr-span").click(r.debounce(function(e){if(e.preventDefault(),n=$(this),!$(this).hasClass("current"))l.remote({id:$(this).attr("data-id")})},1e3)),l.on("success",function(e){if(e&&e.bizError)alert(e.statusInfo);else n.addClass("current").html("已领取"+e.amount+"元"),alert("领取成功")}),$(".reward-type-link-span").zclip({path:a.URL.ROOT+"/static/ZeroClipboard.swf",copy:$.trim($(".reward-type-link-http").html()),afterCopy:function(){alert("复制成功")}});var e=new QRCode($(".erweima")[0],{width:126,height:126});e.makeCode(i)}var n,i,r=require("common/util"),o=require("common/header"),s=require("common/Remoter"),a=require("common/config"),l=new s("ACCOUNT_AWARD_RECEIVEAWARDS");return{init:e}});