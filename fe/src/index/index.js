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


    }

    return {
        init:init
    };
});
