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
    var recharge = new Remoter('ACCOUNT_CASH_RECHARGE_ADD');

    function init() {
        header.init();
        bindEvent();
    }

    function bindEvent() {


    }



    return {
        init:init
    };
});

