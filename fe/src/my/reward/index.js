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
    var receiveAwards = new Remoter('ACCOUNT_AWARD_RECEIVEAWARDS');
    var event;

    function init() {
        header.init();
        bindEvent();
        $('.reward-type-link-span').click(function () {
            jscopy();

        });
    }

    function bindEvent() {

        //点击领取
        $('.table-tr-span').click(function () {
            event = $(this);
            if($(this).hasClass('current')) {
                return;
            }

            receiveAwards.remote({
                id: $(this).attr('data-id')
            })
        });
        //receiveAwardsCb
        receiveAwards.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                event.addClass('current');
                alert('领取成功');
            }
        });

    }

    function jscopy() {

        //var content = $('.reward-type-link-http').innerHTML;
        //
        //window.clipboardData.setData("Text",content);

        window.clipboardData.setData("Text",document.getElementsByTagName("reward-type-link-http").innerHTML);


    }



    return {
        init:init
    };
});

