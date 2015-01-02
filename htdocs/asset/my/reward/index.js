define('my/reward/index', function (require) {
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
        $('.table-tr-span').click(function () {
            event = $(this);
            if ($(this).hasClass('current')) {
                return;
            }
            receiveAwards.remote({ id: $(this).attr('data-id') });
        });
        receiveAwards.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                event.addClass('current');
                alert('\u9886\u53D6\u6210\u529F');
            }
        });
        $('.reward-type-link-span').zclip({
            path: 'http://img.jb51.net/js/ZeroClipboard.swf',
            copy: $.trim($('.reward-type-link-http').html()),
            afterCopy: function () {
                alert('\u590D\u5236\u6210\u529F');
            }
        });
        var qrcode = new QRCode($('.erweima')[0], {
                width: 126,
                height: 126
            });
        qrcode.makeCode(codeUrl);
    }
    return { init: init };
});