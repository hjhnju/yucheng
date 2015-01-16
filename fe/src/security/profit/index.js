/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 15-1-13
 */

define(function (require) {

    var $ = require('jquery');
    var header = require('common/header');

    function init() {
        $('.insurance-type-box').removeClass('current');
        $('.insurance-type-box.low').addClass('current');
        $('.nav-item-link:eq(2)').addClass('current');
        header.init();
    }






    return {
        init:init
    };
});
