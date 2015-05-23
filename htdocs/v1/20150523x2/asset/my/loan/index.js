define('my/loan/index', [
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
    var commonData = require('common/data');
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
        htmlContainer = $('#my-loan-list');
        header.init();
        etpl.compile(tpl);
        getReturnList.remote({ page: 1 });
        ajaxCallBack();
        bindEvents();
    }
    function bindEvents() {
        $('.my-loan-tab-item').click(function () {
            if (!$(this).hasClass('current')) {
                $('.my-loan-tab-item').removeClass('current');
                $(this).addClass('current');
                status = +$.trim($(this).attr('data-value'));
                getRemoteList(1);
            }
        });
        $('.my-loan-list').delegate('.view-plan-btn', 'click', function () {
            var allItem = $('.my-loan-item');
            if ($(this).hasClass('current')) {
                allItem.removeClass('current');
                $(this).removeClass('current');
                return;
            }
            var value = $.trim($(this).attr('data-id'));
            item = $(this);
            allItem.removeClass('current');
            $('.view-plan-btn').removeClass('current');
            $(this).closest('.my-loan-item').addClass('current');
            $(this).addClass('current');
            if (!$(this).hasClass('hasDetail')) {
                getReturnDetail.remote({ loan_id: value });
            }
        });
    }
    function ajaxCallBack() {
        getReturnList.on('success', function (data) {
            if (data.bizError) {
                renderError(data);
            } else {
                if (!data.list.length) {
                    pager = null;
                    $('#my-loan-pager').html('');
                    htmlContainer.html(etpl.render('Error', { msg: '\u60A8\u5F53\u524D\u6CA1\u6709\u6570\u636E\u54DF' }));
                    return;
                }
                if (!pager) {
                    pager = new Pager($.extend({}, commonData.pagerOpt, {
                        main: $('#my-loan-pager'),
                        total: +data.pageall
                    }));
                    pager.on('change', function (e) {
                        getRemoteList(e.value);
                    });
                }
                pager.render(+data.page);
                renderHTML('returnMoneyList', data);
            }
        });
        getReturnList.on('fail', function (data) {
            renderError(data);
        });
        getReturnDetail.on('success', function (data) {
            var container = $(item).closest('.my-loan-item').addClass('current').find('.my-loan-detail');
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
                if (!data.list.length) {
                    pager = null;
                    $('#my-loan-pager').html('');
                    htmlContainer.html(etpl.render('Error', { msg: '\u60A8\u5F53\u524D\u6CA1\u6709\u6570\u636E\u54DF' }));
                    return;
                }
                if (!pager) {
                    pager = new Pager($.extend({}, commonData.pagerOpt, {
                        main: $('#my-loan-pager'),
                        total: +data.pageall
                    }));
                    pager.on('change', function (e) {
                        getRemoteList(e.value);
                    });
                }
                pager.render(+data.page);
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
                if (!data.list.length) {
                    pager = null;
                    $('#my-loan-pager').html('');
                    htmlContainer.html(etpl.render('Error', { msg: '\u60A8\u5F53\u524D\u6CA1\u6709\u6570\u636E\u54DF' }));
                    return;
                }
                if (!pager) {
                    pager = new Pager($.extend({}, commonData.pagerOpt, {
                        main: $('#my-loan-pager'),
                        total: +data.pageall
                    }));
                    pager.on('change', function (e) {
                        getRemoteList(e.value);
                    });
                }
                pager.render(+data.page);
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
                if (!data.list.length) {
                    pager = null;
                    $('#my-loan-pager').html('');
                    htmlContainer.html(etpl.render('Error', { msg: '\u60A8\u5F53\u524D\u6CA1\u6709\u6570\u636E\u54DF' }));
                    return;
                }
                if (!pager) {
                    pager = new Pager($.extend({}, commonData.pagerOpt, {
                        main: $('#my-loan-pager'),
                        total: +data.pageall
                    }));
                    pager.on('change', function (e) {
                        getRemoteList(e.value);
                    });
                }
                pager.render(+data.page);
                renderHTML('tenderFailList', data);
            }
        });
        getTenderFailList.on('fail', function (data) {
            renderError(data);
        });
    }
    function getRemoteList(page) {
        htmlContainer.html(etpl.render('Loading'));
        $('#my-loan-pager').html('');
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
            data.list[i].timeInfo = moment.unix(data.list[i].tenderTime).format('YYYY-MM-DD HH:mm');
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