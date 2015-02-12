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
    var config = require('common/config');
    var receiveAwards = new Remoter('ACCOUNT_AWARD_RECEIVEAWARDS');
    var item;
    var codeUrl;

    function init(url) {
        codeUrl = url;
        header.init();
        bindEvent();

    }

    function bindEvent() {

        //点击领取
        $('.table-tr-span').click(util.debounce(function (e) {
            e.preventDefault();
            item = $(this);

            if($(this).hasClass('current')) {
                return;
            }

            receiveAwards.remote({
                id: $(this).attr('data-id')
            });
        }, 1000));

        //receiveAwardsCb
        receiveAwards.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                item.addClass('current').html('已领取' + data.amount + '元');
                alert('领取成功');
            }
        });
        
        // 邀请奖励
        $('.reward-type-link-span').zclip({
            path: config.URL.ROOT + '/static/ZeroClipboard.swf',
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

