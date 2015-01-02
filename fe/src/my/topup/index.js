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

        // 选择银行加状态
        $('.topup-select-con-box').click(function () {
            $('.topup-select-con-box').removeClass('current');
            $(this).addClass('current');

            $(this).find('.topup-select-ipt').prop('checked', true);
        });

        //点击充值
        $('#pay').click(function () {

            var value = $('#box-ipt').val();

            if(!value) {
                $('#topup-error').html('充值金额不能为空');
                return;
            }
        });

    }



    return {
        init:init
    };
});

