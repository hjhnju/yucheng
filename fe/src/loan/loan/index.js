/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 15-1-6
 */

define(function (require) {

    var $ = require('jquery');
    var header = require('common/header');


    function init() {

        header.init();

        $('.nav-item-link').removeClass('current');

        $('.nav-item-link:eq(1)').addClass('current');
    }


    return {
        init: init
    };
});
