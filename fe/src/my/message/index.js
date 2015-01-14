/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-27
 */

define(function (require) {

    var $ = require('jquery');
    var etpl = require('etpl');
    var header = require('common/header');
    var moment = require('moment');
    var commonData = require('common/data');
    var Pager = require('common/ui/Pager/Pager');
    var Remoter = require('common/Remoter');
    var getList = new Remoter('MY_MSG_LIST');
    var setRead = new Remoter('MY_MSG_SETREAD_ADD');

    var tpl = require('./list.tpl');

    var status = 0;

    function init() {


        header.init();
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
            }
            else {

                if (!data.list.length) {
                    container.html(etpl.render('Error', {
                        msg: '您当前还没有消息哟'
                    }));
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
                }

                container.html(etpl.render('msgList', {
                    list: data.list
                }));
            }
        });

        container.delegate('.msg-content-text', 'click', function () {
            var id = $(this).attr('data-id');
            var parent = $(this).closest('.my-invest-item');

            parent.removeClass('unread');

            if (parent.hasClass('current')) {
                parent.removeClass('current');
            }
            else {
                parent.addClass('current');
                setRead.remote({
                    mid: id
                });
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
    return {
        init: init
    };
});
