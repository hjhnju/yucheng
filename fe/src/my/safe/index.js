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
    var secureDergee = new Remoter('SECURE_DEGREE');


    function init() {
        header.init();
        bindEvent();
    }

    function bindEvent() {

        //重新检测
        $('.finish-grade-refresh').click(function () {

            secureDergee.remote();
        });

        //secureDergeeCb
        secureDergee.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {

            }
        })

    }


    return {
        init:init
    };
});

 