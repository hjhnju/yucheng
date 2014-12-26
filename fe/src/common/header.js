/**
 * @ignore
 * @file header.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-18
 */

define(function (require) {

    var $ = require('jquery');
    var etpl = require('etpl');

    var tpl = require('./common.tpl');


    function init() {
        etpl.compile(tpl);
        easy();
    }

    function easy() {
        var timer;
        var item;

        $('.attention-me').mouseenter(function () {
            if (item && item !== $(this)) {
                $('.xinlang-erweima').removeClass('current');
            }
            clearTimeout(timer);
            item = $(this);
            $(this).children('.xinlang-erweima').addClass('current');
        }).mouseleave(function () {
            timer = setTimeout(function () {
                $('.attention-me').children('.xinlang-erweima').removeClass('current');
            }, 100);
        });

    }

    return {
        init:init
    };
});
