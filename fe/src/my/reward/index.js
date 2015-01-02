/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function (require) {

    var util = require('common/util');
    var header = require('common/header');
    var Remoter = require('common/Remoter');
    var receiveAwards = new Remoter('ACCOUNT_AWARD_RECEIVEAWARDS');
    var event;
    var codeUrl;

    function init(url) {
        codeUrl = url;
        header.init();
        bindEvent();

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
            });
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
        
        // 邀请奖励
        $('.reward-type-link-span').zclip({
            path: 'http://img.jb51.net/js/ZeroClipboard.swf',
            copy: $.trim($('.reward-type-link-http').html()),
            afterCopy: function() {
                alert('复制成功');
            }
        });

        // 生成二维码
        var qrcode = new QRCode(
            $('.erweima')[0], {
                width: 126, //宽度
                height: 126 //高度
            }
        );

        qrcode.makeCode(codeUrl);
    }

    return {
        init:init
    };
});

