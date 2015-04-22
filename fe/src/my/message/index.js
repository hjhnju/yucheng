/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-27
 */

define(function(require) {

    var $ = require('jquery');
    var etpl = require('etpl');
    var header = require('common/header');
    var moment = require('moment');
    var commonData = require('common/data');
    var Pager = require('common/ui/Pager/Pager');
    var dialog = require('common/ui/Dialog/Dialog');
    var Remoter = require('common/Remoter');
    var getList = new Remoter('MY_MSG_LIST');
    var setRead = new Remoter('MY_MSG_SETREAD_ADD');

    var delMsg = new Remoter('MY_MSG_DEL');
    var delAllMsg = new Remoter('MY_MSG_DELALL');
    var setReadAll = new Remoter('MY_MSG_SETREADALL');


    var tpl = require('./list.tpl');

    var status = 0;

    function init() {
        header.init();
        dialog.init();
        var container = $('#my-msg-list');
        var pager;

        etpl.compile(tpl);

        getList.remote({
            status: status,
            page: 1
        });

        bindEvents();
        ajaxCallBack();

    }

    /**
     * 绑定事件
     *
     * @inner
     */
    function bindEvents() {
        container.delegate('.msg-content-text', 'click', function() {
            var id = $(this).attr('data-id');
            var parent = $(this).closest('.my-invest-item');
            var detail = parent.find("my-msg-detail");

            parent.removeClass('unread');

            if (parent.hasClass('current')) {
                parent.removeClass('current');
                detail.slideUp();
            } else {
                parent.addClass('current');
                detail.slideDown();
                setRead.remote({
                    mid: id
                });
            }



        });

        container.delegate('.close-detail', 'click', function() {
            $(this).closest('.my-invest-item').removeClass('current');
            $(this).closest(".my-msg-detail").slideUp();
        });

        $('.my-invest-tab-item').click(function() {
            status = +$(this).attr('data-value');
            $('.my-invest-tab-item').removeClass('current');
            $(this).addClass('current');
            getList.remote({
                status: status,
                page: 1
            });
        });

        //删除选定消息事件
        container.delegate('.del-msg', 'click', function() {

            /*  dialog.show({
                 width: 500,
                 defaultTitle: false,
                 content: etpl.render('fixBox')
             });*/
            if (confirm("您确定要删除此条消息么？")) {
                var id = $(this).parent().prev().find('.msg-content-text').attr('data-id');
                delMsg.remote({
                    mid: id
                });
            }

        });

        //全部标记为已读 
        $('.set-readAll-btn').click(function() {

            if (confirm("您确定要把所有消息设置为已读么？")) {
                var userid=$(this).attr('data-userid'); 
                setReadAll.remote({
                    uid : userid
                });
            }

        });
    }

    /**
     * 绑定请求回调
     *
     * @inner
     */
    function ajaxCallBack() {
        //消息列表请求成功
        getList.on('success', function(data) {
            if (data.bizError) {
                container.html(etpl.render('Error', data.statusInfo));
            } else {

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

                    pager.on('change', function(e) {
                        getList.remote({
                            status: status,
                            page: +e.value
                        });
                    });
                }

                pager.setOpt('total', +data.pageall);
                pager.render(+data.page);

                for (var i = 0, l = data.list.length; i < l; i++) {
                    var tmp = data.list[i];
                    tmp.timeInfo = moment.unix(+tmp.time).format('YYYY-MM-DD HH:mm');
                }

                container.html(etpl.render('msgList', {
                    list: data.list
                }));
            }
        });

        //删除消息成功
        delMsg.on('success', function(data) {
            if (data.bizError) {
                alert(data.statusInfo);
            } else {
                getList.remote({
                    status: status,
                    page: 1
                });
            }


        });
        //全部标记为已读
        setReadAll.on('success', function(data) {
            if (data.bizError) {
                alert(data.statusInfo);
            } else {
                getList.remote({
                    status: status,
                    page: 1
                });
            }

        });
        //删除全部消息
        delAllMsg.on('success', function(data) {

        });
    }
    return {
        init: init
    };
});
