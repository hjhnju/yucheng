define("apply/review/index",["require","jquery","apply/common/applyCommon","common/util","common/Remoter"],function(require){function e(e,r){i=o.init(e,r),t(),n()}function t(){r(".loan .loan-submit").click(s.debounce(function(e){if(e.preventDefault(),!r("#tiaoyue-itp")[0].checked)return void r("#error-box").html("请同意用户条约!");else r("#error-box").html("");return r("#error-box").html(""),void l.remote({amount:i.amount,duration:i.duration,duration_type:i.duration_type,service_charge:i.service_charge})},1e3))}function n(){l.on("success",function(e){if(e&&e.bizError)r("#error-box").html(e.statusInfo);else;})}var i,r=require("jquery"),o=require("apply/common/applyCommon"),s=require("common/util"),a=require("common/Remoter"),l=new a("APPLY_REVIEW_SUBMIT");return{init:e}});