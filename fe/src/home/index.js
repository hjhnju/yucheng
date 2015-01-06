/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-11
 */

define(function (require) {

    var $ = require('jquery');

    function init() {

        var header = require('common/header');
        header.init();
        lunbox($('.banner-floation'), $('.banner-item'), $('.banner-item-list'), $('.banner-select-link'));

    }


    function lunbox(overbox, item, list, btn) {

        var oDiv = overbox;
        var oUl = item;
        var timer;
        var now = 0;
        var now1 = 0;
        var aLi = list;
        var aLiWidth = aLi.eq(0).width();
        var small = btn;

        function lunbo() {
            if(now === aLi.size()-1) {
                list.eq(0).css({
                    "position": "relative",
                    "left": oUl.width()
                });
                now = 0;
            }
            else {
                now++;
            }
            now1++;
            small.removeClass('current');
            small.eq(now).addClass('current');

            oUl.stop(true,false).animate({'left': -aLiWidth*now1},400,function() {
                if(now === 0) {
                    list.eq(0).css({
                        "position": ""
                    });
                    oUl.css('left',0);
                    now1 = 0;
                }
            });
        }


        timer = setInterval(lunbo,3000);

        small.mouseenter(function() {
            clearInterval(timer);
            var index = small.index(this);
            now = index;
            now1 = index;

            small.removeClass('current');
            $(this).addClass('current');
            oUl.stop(true,false).animate({'left': -aLiWidth*index});

        });

        oDiv.mouseenter(function () {
            clearInterval(timer);
        });
        oDiv.mouseleave(function () {
            timer = setInterval(lunbo,3000);
        });


    }

    return {
        init:init
    };
});
