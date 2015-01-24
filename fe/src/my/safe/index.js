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
    var delBind = new Remoter('SECURE_UNBIND_THIRD');


    function init() {
        header.init();
        bindEvent();
        // $('.finish-grade-refresh').trigger('click');
    }

    function bindEvent() {

        //重新检测
        $('.finish-grade-refresh').click(function () {

            secureDergee.remote();
            $('.fen').html('检测中...');
        });

        //secureDergeeCb
        secureDergee.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                $('.finish-main-grade-box-internal').css('width',data.score + '%');
                $('.fen').html(data.score + '分')
            }
        });

        delBind.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                window.location.href = window.location.href;
            }
        });

        // 解绑第三方账号
        $('#bindthird').click(function () {
            if (confirm('确定解绑第三方账号吗？')) {
                delBind.remote();
            }
        });

    }


    return {
        init:init
    };
});

 