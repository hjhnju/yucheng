define("loan/loanform/index",["require","jquery","common/Remoter","common/header"],function(require){function e(){t(),a.init()}function t(){var e=r(".loan-form-right-ipt"),t=r(".form-error");e.on({focus:function(){t.html("")},blur:function(){var e=r(this).val(),n=r(this).parent().prev().text();if(!e)t.html(n+"内容不能为空")}}),r(".form-con-right-link").click(function(){for(var i=0;i<e.length;i++)if(n=c[r(e[i]).attr("data-type")],!r(e[i]).val())return void t.html(n+"内容不能为空");o.remote({title:s.title.val(),money:s.money.val(),user:s.user.val(),phone:s.phone.val(),textarea:s.textArea.val(),schoolType:l.selectSchool.val(),using:l.selectUse.val(),time:l.selectTime.val(),city:l.selectCity.val(),returnType:l.selectType.val()})}),o.on("success",function(e){if(e&&e.bizError)t.html(e.statusInfo);else window.location.href="/mock/entry/"})}var n,r=require("jquery"),i=require("common/Remoter"),o=new i("LOAN_REQUEST"),a=require("common/header"),s={title:r("#title"),money:r("#money"),user:r("#user"),phone:r("#phone"),textArea:r("#textarea")},l={selectSchool:r("#select-school"),selectUse:r("#select-use"),selectTime:r("#select-time"),selectCity:r("#select-city"),selectType:r("#select-type")},c={title:"借款标题",money:"借款金额",user:"借款人",phone:"联系方式",textArea:"借款描述"};return{init:e}});