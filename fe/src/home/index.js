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
        //var now1 = 0;
        var aLi = list;
        var aLiWidth = aLi.eq(0).width();
        var small = btn;
        var status = 0;

        function lunbo() {
            var left;
            //timer && clearTimeout(timer);
            if (now === aLi.length - 1) {
                list.eq(0).css({
                    "position": "relative",
                    "left": oUl.width()
                });
                status = 1;
                now = 0;

                left = -aLiWidth * aLi.length;
            }
            else {
                now++;
                left = -aLiWidth * now;
            }
            //now1++;
            small.removeClass('current');
            small.eq(now).addClass('current');

            oUl.stop(true).animate({
                'left': left + 'px'
            }, 400, function () {
                if (now === 0) {
                    list.eq(0).css({
                        "position": ""
                    });
                    oUl.css('left', 0);
                    //now1 = 0;
                    status = 0;
                }

                timer = setTimeout(lunbo, 5000);
            });

        }


        timer = setTimeout(lunbo, 5000);


        small.mouseenter(function () {
            timer && clearTimeout(timer);
            var index = $(this).index();
            now = index;
            //now1 = index;
            if(status && !index) {
                list.eq(0).css({
                    "position": ""
                });
                //now1 = 0;
                status = 0;
            }

            small.removeClass('current');
            $(this).addClass('current');
            oUl.stop(true).animate({
                'left': -aLiWidth * index
            }, function () {

            });

        });
        small.mouseleave(function () {

            timer = setTimeout(lunbo, 1000);
        });

        //oDiv.mouseenter(function () {
        //    timer && clearTimeout(timer);
        //});
        //oDiv.mouseleave(function () {
        //    timer = setTimeout(lunbo, 1000);
        //});


    }

    return {
        init: init
    };
});
