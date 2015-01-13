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

        var oUl = item;
        var timer;
        var now = 0;
        var aLi = list;
        var aLiWidth = aLi.eq(0).width();
        var small = btn;
        var length = aLi.length;
        var left = $('.jiantou-left');
        var right = $('.jiantou-right');

        left.click(function () {

            timer && clearTimeout(timer);
            now = (now - 1 + length)%length;
            console.log(now);

            lunbo(now);

        });

        right.click(function () {

            timer && clearTimeout(timer);
            now = (now+1) % length;
            console.log(now);
            lunbo(now);

        });

        function lunbo(index) {
            now = index;
            small.removeClass('current');
            small.eq(now).addClass('current');
            oUl.stop(true).animate({
                'left': -now * aLiWidth + 'px'
            }, 300, function () {
                timer = setTimeout(function () {
                    now = ++now % length;
                    lunbo(now);
                }, 5000);
            });
        }

        small.mouseenter(function () {
            timer && clearTimeout(timer);
            var index = $(this).index();
            lunbo(index);
        });


        timer = setTimeout(function(){
            lunbo(0);
        }, 5000);

    }
    return {
        init: init
    };
});
