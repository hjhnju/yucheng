define("guide/index",function(require){function e(){r.init(),t()}function t(){function e(){var e=n(".three-main-step-left"),t=n(".three-main-step-right"),r=n(".three-step-item"),i=4,o=0,s=n(".three-step-item-list"),a=s.eq(0).width(),l=n(".three-step-box-number");e.click(function(){o=(o-1+i)%i,l.stop(!0).attr("class","three-step-box-number step-"+o),r.stop(!0).animate({left:-o*a},400)}),t.click(function(){o=(o+1)%i,l.stop(!0).attr("class","three-step-box-number step-"+o),r.stop(!0).animate({left:-o*a},400)})}e()}var n=require("jquery"),r=require("common/header");return{init:e}});