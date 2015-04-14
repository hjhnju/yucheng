define('activity/spring/index', [
    'require',
    'jquery',
    'etpl',
    'common/extra/jquery.marquee',
    'common/header',
    'common/ui/Dialog/Dialog',
    'activity/spring/index.tpl'
], function (require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var marquee = require('common/extra/jquery.marquee');
    var header = require('common/header');
    var dialog = require('common/ui/Dialog/Dialog');
    var tpl = require('activity/spring/index.tpl');
    function init() {
        etpl.compile(tpl);
        header.init();
        dialog.init();
        bindEvent();
    }
    function bindEvent() {
        $('.activity-list-content-scroll').kxbdMarquee({
            isEqual: true,
            loop: 0,
            direction: 'up',
            scrollAmount: 1,
            scrollDelay: 20
        });
        $('.rule-btn').click(function () {
            var ruleNum = $(this).attr('for');
            dialog.show({
                width: 700,
                defaultTitle: false,
                content: etpl.render(ruleNum)
            });
        });
    }
    return { init: init };
});