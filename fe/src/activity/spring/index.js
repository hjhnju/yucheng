/**
 * @ignore
  *@classDescription 春风化雨活动
 * @file index.js
 * @author fanyy
 * @time 15-4-9
 */

define(function (require) {

    var $ = require('jquery');  
     var etpl = require('etpl');
    var marquee =require('common/extra/jquery.marquee');
    var header = require('common/header');
    var dialog = require('common/ui/Dialog/Dialog');
    var tpl = require('activity/spring/index.tpl');

    function init() {
         etpl.compile(tpl);
         header.init();
          dialog.init();
         bindEvent();
    } 
    
    function bindEvent () {
          //实时播报滚动
         $('.activity-list-content-scroll').kxbdMarquee({
              isEqual:true,         //所有滚动的元素长宽是否相等,true,false 
              loop:0,               //循环滚动次数，0时无限         
              direction:"up",     //滚动方向，"left","right","up","down"         
              scrollAmount:1,       //步长         
              scrollDelay:20        //时长
         });

        // 点击快速绑定 出浮层
        $('.rule-btn').click(function () { 
            var ruleNum =  $(this).attr("for");
            dialog.show({
                width: 700,
                defaultTitle: false,
                content: etpl.render(ruleNum)
            });
 
        });
    }

    return {
        init: init
        
    };
});
