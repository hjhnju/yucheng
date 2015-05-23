/**
 * @ignore
 * @file index.js
 * @author fanyy
 * @time 15-5-8
 */

define(function(require) {

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
    var myScroll,
        pullDownEl, pullDownOffset,
        pullUpEl, pullUpOffset,
        generatedCount = 0;

    var htmlContainer;


    // 记录列表状态
    var status = 1;

    //还款中页码数
    var moneyPage = 1;

    //投标中页码数
    var tenderingPage = 1;


    //还款计划页码数
    var payplanPage = 1;

    //页面tag  true:还款计划页面  false：投标记录页面
    var pageTag = false;


    function init(payplan) {
        common.init();
        htmlContainer = $('#investlist-box');
        etpl.compile(tpl);

        if (payplan) {
            //还款计划页面
            pageTag = payplan;
        }

        getRemoteList(1);
        bindEvent();
        ajaxCallback();
    }


    /*
     *绑定事件
     */
    function bindEvent() {
        // 选择投资类型
        $('.my-invest-tab-item').click(function() {
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
        loaded();
    } 

    /*
     *ajax回调函数
     * 
     */
    function ajaxCallback() {
        // 回款中列表 成功
        getReturnList.on('success', function(data) {
            ajaxCallbackfun(data, "returnMoneyList", pageTag);
        });

        // 投标中列表 成功
        getTenderingList.on('success', function(data) {
            ajaxCallbackfun(data, "tenderingList", pageTag);
        });
        // 还款计划
        getReturnDetail.on('success', function(data) {
            ajaxCallbackfun(data, "returnMoneyDetail", pageTag);
        });
    }

    /**
     * 发送请求
     * @param  {[type]} page [页码]
     * @return {[type]}      [description]
     */
    function getRemoteList(page) {
            htmlContainer.html(etpl.render('Loading'));
            if (pageTag) {
                getReturnDetail.remote({
                    page: payplanPage++
                });
            } else {
                switch (status) {
                    case 1:
                        getReturnList.remote({
                            page: moneyPage++
                        });
                        break;
                    case 2:
                        getTenderingList.remote({
                            page: tenderingPage++
                        });
                        break;
                }
            }
        }
        /**
         * ajax返回后执行的函数
         * @param  {[type]} data    [ajax数据]
         * @param  {[type]} tpl     [模板名称]
         * @param  {[type]} pageTag [页面tage]
         * @return {[type]}         [description]
         */
    function ajaxCallbackfun(data, tpl, pageTag) {
        if (data.bizError) {
            renderError(data);
        } else {
            var pullUpEl = document.getElementById('pullUp');
            if (!data.list.length && data.page == 1) {
                htmlContainer.html(etpl.render('Error', {
                    msg: '您当前没有数据哟'
                }));
                return;
            }
            if (data.page > data.pageall) {

                //下拉没有更多啦
                pullUpEl.className = '';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '全部加载完毕';

            } else {
                //更新当前页码 
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
                //显示加载后的数据 
                if (data.page == 1) {
                    pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多';
                }
                renderHTML(tpl, data);
                //刷新myScroll
                setTimeout(function() {
                    myScroll.refresh();
                }, 0);
            }
        }
    }






    /**
     * 向上滑动加载列表
     */
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
            myScroll.refresh(); //刷新滚动框
        }
        /**
         * 下拉加载最新数据
         * 
         */
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


        setTimeout(function() {
            document.getElementById('wrapper').style.left = '0';
        }, 800);
        document.addEventListener('touchmove', function(e) {
            e.preventDefault();
        }, false);
    }


    /**
     * 渲染页面
     * @param {string} tpl 模板target
     * @param {*} data 请求返回数据
     */
    function renderHTML(tpl, data) {
            // 格式化时间
            for (var i = 0, l = data.list.length; i < l; i++) {
                data.list[i].timeInfo = moment.unix(data.list[i].tenderTime).format('YYYY-MM-DD HH:mm');
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
