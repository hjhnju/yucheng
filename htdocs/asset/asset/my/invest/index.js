/*! 2014 Baidu Inc. All Rights Reserved */
define('my/invest/index', [
    'require',
    'jquery',
    'etpl',
    'moment',
    'common/data',
    'common/header',
    'common/ui/Pager/Pager',
    'common/Remoter',
    './list.tpl'
], function (require) {
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
    var pager;
    var status = 1;
    var item = null;
    var FORMATER = 'YYYY-MM-DD';
    var htmlContainer;
    function init() {
        htmlContainer = $('#my-invest-list');
        header.init();
        etpl.compile(tpl);
        getReturnList.remote({ page: 1 });
        ajaxCallBack();
        bindEvents();
    }
    function bindEvents() {
        $('.my-invest-tab-item').click(function () {
            if (!$(this).hasClass('current')) {
                $('.my-invest-tab-item').removeClass('current');
                $(this).addClass('current');
                status = +$.trim($(this).attr('data-value'));
                getRemoteList(1);
            }
        });
        $('.my-invest-list').delegate('.view-plan-btn', 'click', function () {
            var allItem = $('.my-invest-item');
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
            if (!$(this).hasClass('hasDetail')) {
                getReturnDetail.remote({ id: value });
            }
        });
    }
    function ajaxCallBack() {
        getReturnList.on('success', function (data) {
            if (data.bizError) {
                renderError(data);
            } else {
                if (!pager) {
                    pager = new Pager($.extend({}, commonDate.pagerOpt, {
                        main: $('#my-invest-pager'),
                        total: +data.pageall
                    }));
                    pager.render(+data.page);
                    pager.on('change', function (e) {
                        getRemoteList(e.value);
                    });
                }
                renderHTML('returnMoneyList', data);
            }
        });
        getReturnList.on('fail', function (data) {
            renderError(data);
        });
        getReturnDetail.on('success', function (data) {
            var container = $(item).closest('.my-invest-item').addClass('current').find('.my-invest-detail');
            if (data.bizError) {
                container.render(etpl.render('Error', { msg: data.statusInfo }));
            } else {
                if (!item) {
                    return;
                }
                $(item).addClass('hasDetail');
                for (var i = 0, l = data.list.length; i < l; i++) {
                    data.list[i].timeInfo = moment.unix(data.list[i].time).format(FORMATER);
                }
                container.html(etpl.render('returnMoneyDetail', { data: data }));
            }
        });
        getTenderingList.on('success', function (data) {
            if (data.bizError) {
                renderError(data);
            } else {
                renderHTML('tenderingList', data);
            }
        });
        getTenderingList.on('fail', function (data) {
            renderError(data);
        });
        getEndedList.on('success', function (data) {
            if (data.bizError) {
                renderError(data);
            } else {
                renderHTML('endedList', data);
            }
        });
        getEndedList.on('fail', function (data) {
            renderError(data);
        });
        getTenderFailList.on('success', function (data) {
            if (data.bizError) {
                renderError(data);
            } else {
                renderHTML('tenderFailList', data);
            }
        });
        getTenderFailList.on('fail', function (data) {
            renderError(data);
        });
    }
    function getRemoteList(page) {
        htmlContainer.html(etpl.render('Loading'));
        $('#my-invest-pager').html('');
        switch (status) {
        case 1:
            getReturnList.remote({ page: page });
            break;
        case 2:
            getTenderingList.remote({ page: page });
            break;
        case 3:
            getEndedList.remote({ page: page });
            break;
        case 4:
            getTenderFailList.remote({ page: page });
            break;
        }
    }
    function renderHTML(tpl, data) {
        pager.setOpt('pageall', +data.pageall);
        pager.render(+data.page);
        for (var i = 0, l = data.list.length; i < l; i++) {
            data.list[i].timeInfo = moment.unix(data.list[i].tenderTime).format(FORMATER);
            if (data.list[i].endTime) {
                data.list[i].endTimeInfo = moment.unix(data.list[i].endTime).format(FORMATER);
            }
        }
        htmlContainer.html(etpl.render(tpl, { list: data.list }));
    }
    function renderError(data) {
        htmlContainer.render(etpl.render('Error', { msg: data.statusInfo }));
    }
    return { init: init };
});