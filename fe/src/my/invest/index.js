/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var moment = require('moment');
    var commonDate = require('common/data');
    var header = require('common/header');
    var Pager = require('common/ui/Pager/Pager');
    var Remoter = require('common/Remoter');
    var getReturnList = new Remoter('MY_INVEST_GET');
    var getReturnDetail = new Remoter('MY_INVEST_DETAIL');
    var getTenderingList = new Remoter('MY_INVEST_TENDERING');
    var getEndedList = new Remoter('MY_INVEST_ENDED');
    var getTenderFailList = new Remoter('MY_INVEST_TENDERFAIL');

    var tpl = require('./list.tpl');

    // 分页对象
    var pager;

    // 记录列表状态
    var status = 1;

    // 用来记录被点击的回款计划按钮
    var item = null;

    // 时间格式化
    var FORMATER = 'YYYY-MM-DD';

    var htmlContainer;

    /**
     * 初始化方法
     *
     * @public
     */
    function init() {
        htmlContainer = $('#my-invest-list');
        header.init();
        etpl.compile(tpl);

        getReturnList.remote({
            page: 1
        });

        ajaxCallBack();
        bindEvents();

    }

    /**
     * 绑定dom事件
     *
     * @inner
     */
    function bindEvents() {
        // 选择投资类型
        $('.my-invest-tab-item').click(function () {
            if (!$(this).hasClass('current')) {
                // 改变选中状态
                $('.my-invest-tab-item').removeClass('current');
                $(this).addClass('current');

                // 记录当前选中类型
                status = +$.trim($(this).attr('data-value'));

                // 获取数据
                getRemoteList(1);
            }
        });

        // 获取回款列表按钮
        $('.my-invest-list').delegate('.view-plan-btn', 'click', function () {
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

            // 获取内容后再次展开不再发送请求
            if (!$(this).hasClass('hasDetail')) {
                getReturnDetail.remote({
                    id: value
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
        // 回款中列表 成功
        getReturnList.on('success', function (data) {
            if (data.bizError) {
                renderError(data);
            }
            else {
                if (!pager) {
                    pager = new Pager($.extend({}, commonDate.pagerOpt, {
                        main: $('#my-invest-pager'),
                        total: +data.pageall
                    }));
                    
                    pager.render(+data.page);
                    
                    pager.on('change', function(e) {
                        getRemoteList(e.value);
                    });
                }

                renderHTML('returnMoneyList', data);
            }
        });

        // 回款中列表 失败
        getReturnList.on('fail', function (data) {
            renderError(data);
        });
        
        // 还款计划
        getReturnDetail.on('success', function (data) {
            var container = $(item).closest('.my-invest-item')
                .addClass('current').find('.my-invest-detail');

            if (data.bizError) {
                container.render(etpl.render('Error', {
                    msg: data.statusInfo
                }));
            }
            else {
                if (!item) {
                    return;
                }

                $(item).addClass('hasDetail');

                for (var i = 0, l = data.list.length; i < l; i++) {
                    data.list[i].timeInfo = moment.unix(data.list[i].time).format(FORMATER);
                }

                container.html(etpl.render('returnMoneyDetail', {
                    data: data
                }));
            }
        });

        // 投标中列表 成功
        getTenderingList.on('success', function (data) {
            if (data.bizError) {
                renderError(data);
            }
            else {
                renderHTML('tenderingList', data);
            }
        });

        // 投标中列表 失败
        getTenderingList.on('fail', function (data) {
            renderError(data);
        });

        // 已结束列表 成功
        getEndedList.on('success', function (data) {
            if (data.bizError) {
                renderError(data);
            }
            else {
                renderHTML('endedList', data);
            }
        });

        // 已结束列表 失败
        getEndedList.on('fail', function (data) {
            renderError(data);
        });

        // 投标失败 成功
        getTenderFailList.on('success', function (data) {
            if (data.bizError) {
                renderError(data);
            }
            else {
                renderHTML('tenderFailList', data);
            }
        });

        // 投标失败 失败
        getTenderFailList.on('fail', function (data) {
            renderError(data);
        });
    }

    /**
     * 发送请求
     * @param {number} page 页码
     */
    function getRemoteList(page) {
        htmlContainer.html(etpl.render('Loading'));
        $('#my-invest-pager').html('');

        switch (status) {
            case 1:
                getReturnList.remote({
                    page: page
                });
                break;
            case 2:
                getTenderingList.remote({
                    page: page
                });
                break;
            case 3:
                getEndedList.remote({
                    page: page
                });
                break;
            case 4:
                getTenderFailList.remote({
                    page: page
                });
                break;
        }
    }

    /**
     * 渲染页码
     * @param {string} tpl 模板target
     * @param {*} data 请求返回数据
     */
    function renderHTML(tpl, data) {

        pager.setOpt('pageall', +data.pageall);
        pager.render(+data.page);

        // 格式化时间
        for (var i = 0, l = data.list.length; i < l; i++) {
            data.list[i].timeInfo = moment.unix(data.list[i].tenderTime).format('YYYY-MM-DD hh:mm');
            if (data.list[i].endTime) {
                data.list[i].endTimeInfo = moment.unix(data.list[i].endTime).format(FORMATER);
            }
        }

        htmlContainer.html(etpl.render(tpl, {
            list: data.list
        }));
    }

    /**
     * 渲染错误提示
     * @param {*} data 请求返回的错误提示
     */
    function renderError(data) {
        htmlContainer.render(etpl.render('Error', {
            msg: data.statusInfo
        }));
    }

    return {
        init: init
    };
});
