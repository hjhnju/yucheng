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


    var loginError = $('#login-error');
    var imgUrl = $('#login-img-url');

    function init() {
        header.init();
    }

    function bindEvent() {

        $('#phone').click (function () {

        })

    }


    return {
        init:init
    };
});

 