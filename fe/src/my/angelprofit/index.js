/**
 * @ignore
 * @file index  爱心收益
 * @author fanyy 
 * @time 15-5-28
 */

define(function(require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var moment = require('moment');
    var commonData = require('common/data');
    var header = require('common/header');
    var Pager = require('common/ui/Pager/Pager');
    var Remoter = require('common/Remoter');
    var getAngelProfitList = new Remoter('MY_ANGELPROFIT_LIST');
    var tpl = require('./list.tpl');

    // 分页对象
    var pager;
    // 时间格式化
    var FORMATER = 'YYYY-MM-DD';

    var htmlContainer;

    /**
     * 初始化方法
     *
     * @public
     */
    function init() {
        htmlContainer = $('#my-angel-list');
        header.init();
        etpl.compile(tpl);

        getAngelProfitList.remote({
            page: 1
        });

        bindEvents();
        ajaxCallBack();
    }

    /**
     * 绑定dom事件
     *
     * @inner
     */
    function bindEvents() {
        // 获取收益详情按钮
        $('.my-invest-list').delegate('.view-plan-btn', 'click', function() {
            var allItem = $('.my-invest-item');
            // 如果当前为显示状态，则隐藏
            if ($(this).hasClass('current')) {
                allItem.removeClass('current');
                $(this).removeClass('current');
                return;
            }
            var value = $.trim($(this).attr('data-id'));
            item = $(this);

            allItem.removeClass('current');
            $('.view-plan-btn').removeClass('current');
            $(this).closest('.my-invest-item').addClass('current');
            $(this).addClass('current');

        });
    }


    /**
     * 绑定请求回调
     *
     * @inner
     */
    function ajaxCallBack() {
        // 回款中列表 成功
        getAngelProfitList.on('success', function(data) {
            if (data.bizError) {
                htmlContainer.render(etpl.render('Error', {
                    msg: data.statusInfo
                }));
            } else {
                if (!data.list.length) {
                    pager = null;
                    $('#my-angel-pager').html('');
                    htmlContainer.html(etpl.render('Error', {
                        msg: '您当前没有数据哟'
                    }));
                    return;
                }
                if (!pager) {
                    pager = new Pager($.extend({}, commonData.pagerOpt, {
                        main: $('#my-angel-pager'),
                        total: +data.pageall
                    }));

                    pager.on('change', function(e) {
                        getRemoteList(e.value);
                    });
                }

                pager.render(+data.page);
                pager.setOpt('pageall', +data.pageall);

                // 格式化时间
                var list =data.list;
                for (var i = 0, l = list.length; i < l; i++) {
                    list[i].timeInfo = moment.unix(list[i].tenderTime).format('YYYY-MM-DD HH:mm');
                    if (list[i].endTime) {
                        list[i].endTimeInfo = moment.unix(list[i].endTime).format(FORMATER);
                    }
                }

                htmlContainer.html(etpl.render('returnAngelProfitList', {
                    list: data.list
                }));
            }
        });
    }


    return {
        init: init
    };
});
