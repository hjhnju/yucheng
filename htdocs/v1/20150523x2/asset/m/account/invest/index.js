define('m/account/invest/index', [
    'require',
    'jquery',
    'm/common/common',
    'moment',
    'etpl',
    './list.tpl',
    'common/Remoter',
    'm/common/iscroll'
], function (require) {
    var $ = require('jquery');
    var common = require('m/common/common');
    var moment = require('moment');
    var etpl = require('etpl');
    var tpl = require('./list.tpl');
    var Remoter = require('common/Remoter');
    var getReturnList = new Remoter('MY_INVEST_GET');
    var getReturnDetail = new Remoter('MY_INVEST_DETAIL');
    var getTenderingList = new Remoter('MY_INVEST_TENDERING');
    var iScroll = require('m/common/iscroll');
    var myScroll, pullDownEl, pullDownOffset, pullUpEl, pullUpOffset, generatedCount = 0;
    var htmlContainer;
    var status = 1;
    var moneyPage = 1;
    var tenderingPage = 1;
    var payplanPage = 1;
    var pageTag = false;
    function init(payplan) {
        common.init();
        htmlContainer = $('#investlist-box');
        etpl.compile(tpl);
        if (payplan) {
            pageTag = payplan;
        }
        getRemoteList(1);
        bindEvent();
        ajaxCallback();
    }
    function bindEvent() {
        $('.my-invest-tab-item').click(function () {
            if (!$(this).hasClass('current')) {
                $('.my-invest-tab-item').removeClass('current');
                $(this).addClass('current');
                status = +$.trim($(this).attr('data-value'));
                getRemoteList(1);
            }
        });
        loaded();
    }
    function ajaxCallback() {
        getReturnList.on('success', function (data) {
            ajaxCallbackfun(data, 'returnMoneyList', pageTag);
        });
        getTenderingList.on('success', function (data) {
            ajaxCallbackfun(data, 'tenderingList', pageTag);
        });
        getReturnDetail.on('success', function (data) {
            ajaxCallbackfun(data, 'returnMoneyDetail', pageTag);
        });
    }
    function getRemoteList(page) {
        htmlContainer.html(etpl.render('Loading'));
        if (pageTag) {
            getReturnDetail.remote({ page: payplanPage++ });
        } else {
            switch (status) {
            case 1:
                getReturnList.remote({ page: moneyPage++ });
                break;
            case 2:
                getTenderingList.remote({ page: tenderingPage++ });
                break;
            }
        }
    }
    function ajaxCallbackfun(data, tpl, pageTag) {
        if (data.bizError) {
            renderError(data);
        } else {
            var pullUpEl = document.getElementById('pullUp');
            if (!data.list.length && data.page == 1) {
                htmlContainer.html(etpl.render('Error', { msg: '\u60A8\u5F53\u524D\u6CA1\u6709\u6570\u636E\u54DF' }));
                return;
            }
            if (data.page > data.pageall) {
                pullUpEl.className = '';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '\u5168\u90E8\u52A0\u8F7D\u5B8C\u6BD5';
            } else {
                if (pageTag) {
                    payplanPage = data.page;
                } else {
                    switch (status) {
                    case 1:
                        moneyPage = data.page;
                        break;
                    case 2:
                        tenderingPage = data.page;
                        break;
                    }
                }
                if (data.page == 1) {
                    pullUpEl.querySelector('.pullUpLabel').innerHTML = '\u4E0A\u62C9\u52A0\u8F7D\u66F4\u591A';
                }
                renderHTML(tpl, data);
                setTimeout(function () {
                    myScroll.refresh();
                }, 0);
            }
        }
    }
    function pullUpAction() {
        if (pageTag) {
            payplanPage++;
            getRemoteList(payplanPage);
        } else {
            switch (status) {
            case 1:
                moneyPage++;
                getRemoteList(moneyPage);
                break;
            case 2:
                tenderingPage++;
                getRemoteList(tenderingPage);
                break;
            }
        }
        myScroll.refresh();
    }
    function pullDownAction() {
        if (pageTag) {
            payplanPage = 1;
            getRemoteList(payplanPage);
        } else {
            switch (status) {
            case 1:
                moneyPage = 1;
                getRemoteList(moneyPage);
                break;
            case 2:
                tenderingPage = 1;
                getRemoteList(tenderingPage);
                break;
            }
        }
        myScroll.refresh();
    }
    function loaded() {
        myScroll = new iScroll('#wrapper', {
            probeType: 2,
            mouseWheel: false,
            bindToWrapper: true,
            scrollY: true
        });
        common.myScrollEvents(myScroll, pullUpAction, pullDownAction);
        setTimeout(function () {
            document.getElementById('wrapper').style.left = '0';
        }, 800);
        document.addEventListener('touchmove', function (e) {
            e.preventDefault();
        }, false);
    }
    function renderHTML(tpl, data) {
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