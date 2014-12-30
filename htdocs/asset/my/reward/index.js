define('my/reward/index', [
    'require',
    'jquery',
    'common/header',
    'common/Remoter'
], function (require) {
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
    }
    function jscopy() {
        window.clipboardData.setData('Text', document.getElementsByTagName('reward-type-link-http').innerHTML);
    }
    return { init: init };
});