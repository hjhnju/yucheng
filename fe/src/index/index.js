/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-11
 */

define(function (require) {

    var $ = require('jquery');

    function init() {

        easy();

    }
    function easy() {

        $('.nav-item-link').click(function () {
            $('.nav-item-link').removeClass('curremt');
            $(this).addClass('current');
        })
    }
    return {init:init};
});
