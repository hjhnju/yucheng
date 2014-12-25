/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function (require) {

    var $ = require('jquery');
    var header = require('common/header');
    var Remoter = require('common/Remoter');


    function init() {
        header.init();
        bindEvent();
    }

    function bindEvent() {

        $('.testing-link').click(function () {

        })


    }


    return {
        init:init
    };
});

 