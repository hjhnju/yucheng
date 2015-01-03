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

        // 选择银行加状态
        $('.topup-select-con-box').click(function () {
            $('.topup-select-con-box').removeClass('current');
            $(this).addClass('current');

            $(this).find('.topup-select-ipt').prop('checked', true);
        });



        //点击充值
        $('#pay').click(function () {

            var value = $('#box-ipt').val();
            var id = $('.topup-select-con-box.current').find('.topup-select-ipt').attr('data-value');
            var iptvalue = $('.topup-money-box-ipt').val();
            var error = $('#topup-error');

            if(!value) {
                error.html('充值金额不能为空');
                return;
            }

            if(isNaN(iptvalue)) {
                error.html('输入金额必须是数字');
                return;
            }

            error.html('');
            recharge.remote({
                id: id,
                value: value
            });
        });

        //rechargeCb
        recharge.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                window.location.href = '/mock/entry/my/success.php';
            }
        })

    }



    return {
        init:init
    };
});

