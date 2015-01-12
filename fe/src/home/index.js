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

        timer = setTimeout(lunbo, 2000);

        function lunbo() {
            small.removeClass('current');
            small.eq(now).addClass('current');
            oUl.stop(true).animate({
                'left': -now * aLiWidth + 'px'

            }, 400, function () {
                timer && clearTimeout(timer);
                now = ++now % length;
                timer = setTimeout(lunbo, 2000);
            });
        }

        small.mouseenter(function () {
            timer && clearTimeout(timer);
            var index = $(this).index();
            now = index;

            small.removeClass('current');
            $(this).addClass('current');
            timer && clearTimeout(timer);

            oUl.stop(true).animate({
                'left': -now * aLiWidth + 'px'

            }, 400, function () {
                now = ++now % length;
                timer = setTimeout(lunbo, 2000);
            });

        });
        small.mouseleave(function () {
            timer = setTimeout(lunbo, 1000);
        });
    }
    return {
        init: init
    };
});
