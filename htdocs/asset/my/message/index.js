define('my/message/index', [
    'require',
    'jquery',
    'etpl',
    'moment',
    'common/data',
    'common/ui/Pager/Pager',
    'common/Remoter',
    './list.tpl'
], function (require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var moment = require('moment');
    var commonData = require('common/data');
    var Pager = require('common/ui/Pager/Pager');
    var Remoter = require('common/Remoter');
    var getList = new Remoter('MY_MSG_LIST');
    var setRead = new Remoter('MY_MSG_SETREAD_ADD');
    var tpl = require('./list.tpl');
    var status = 0;
    var mapType = {
            1: '\u5145\u503C',
            2: '\u63D0\u73B0'
        };
    function init() {
        var container = $('#my-msg-list');
        var pager;
        etpl.compile(tpl);
        getList.remote({
            status: status,
            page: 1
        });
        getList.on('success', function (data) {
            if (data.bizError) {
                container.html(etpl.render('Error', data.statusInfo));
            } else {
                if (!data.list.length) {
                    container.html(etpl.render('Error', { msg: '\u60A8\u5F53\u524D\u8FD8\u6CA1\u6709\u6D88\u606F\u54DF' }));
                    $('#my-msg-pager').html('');
                    return;
                }
                if (!pager) {
                    pager = new Pager($.extend({}, commonData.pagerOpt, {
                        main: $('#my-msg-pager'),
                        total: +data.pageall
                    }));
                    pager.on('change', function (e) {
                        getList.remote({
                            status: status,
                            page: +e.value
                        });
                    });
                }
                pager.render(+data.page);
                for (var i = 0, l = data.list.length; i < l; i++) {
                    var tmp = data.list[i];
                    tmp.timeInfo = moment.unix(+tmp.time).format('YYYY-MM-DD hh:mm');
                    tmp.typeInfo = mapType[tmp.type];
                }
                container.html(etpl.render('msgList', { list: data.list }));
            }
        });
        container.delegate('.msg-content-text', 'click', function () {
            var id = $(this).attr('data-id');
            var parent = $(this).closest('.my-invest-item');
            parent.removeClass('unread');
            if (parent.hasClass('current')) {
                parent.removeClass('current');
            } else {
                parent.addClass('current');
                setRead.remote({ mid: id });
            }
        });
        container.delegate('.close-detail', 'click', function () {
            $(this).closest('.my-invest-item').removeClass('current');
        });
        $('.my-invest-tab-item').click(function () {
            status = +$(this).attr('data-value');
            $('.my-invest-tab-item').removeClass('current');
            $(this).addClass('current');
            getList.remote({
                status: status,
                page: 1
            });
        });
    }
    return { init: init };
});