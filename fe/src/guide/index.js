/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 15-1-12
 */

define(function (require) {

    var $ = require('jquery');
    var header = require('common/header');

    function init() {
        header.init();
        bindEvent();
    }


    function bindEvent() {

        picRoll()

        function picRoll() {
            var left = $('.three-main-step-left');
            var right = $('.three-main-step-right');
            var item = $('.three-step-item');
            var length = 4;
            var index = 0;
            var list = $('.three-step-item-list');
            var listWidth = list.eq(0).width();

            var step = $('.three-step-box-number');

            left.click(function () {


                index = (index - 1 +length)%length;
                step.stop(true).attr('class','three-step-box-number step-' + index);
                item.stop(true).animate({
                    left: -index*listWidth
                },400)
            });

            right.click(function () {
                index = (index + 1)%length;
                step.stop(true).attr('class','three-step-box-number step-' + index);
                item.stop(true).animate({
                    left: -index*listWidth
                },400)
            });

        }

    }



    return {
        init:init
    };
});
