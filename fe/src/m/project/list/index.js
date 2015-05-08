/**
 * @ignore
 * @file index.js
 * @author fanyy
 * @time 15-4-28
 */

define(function(require) {

    var $ = require('jquery');
    var common = require('m/common/common');

    var Remoter = require('common/Remoter');
    var getList = new Remoter('INVEST_LIST');
    var etpl = require('etpl');
    var tpl = require('./list.tpl');
    /*var Pager = require('common/ui/Pager/Pager');

    var pager;*/
    var iScroll = require('m/common/iscroll');
    var myScroll,
        pullDownEl, pullDownOffset,
        pullUpEl, pullUpOffset,
        generatedCount = 0;

    var htmlContainer;


    var option = {
        'type_id': 0,
        'cat_id': 0,
        'duration': 0,
        'page': 1,
        'pagesize': 10
    };

    function init(opt) {
        common.init();

        htmlContainer = $('#investlist-box');

        option.pagesize = +opt.pagesize;

        etpl.compile(tpl);

        getList.remote(option);



        bindEvent();
        ajaxCallback();
    }


    /*
     *绑定事件
     */
    function bindEvent() {
        /* document.addEventListener('DOMContentLoaded', function() {
             setTimeout(loaded, 200);
         }, false);*/
        loaded();
    }


    /*
     *ajax回调函数
     * 
     */
    function ajaxCallback() {
        //getListCb
        getList.on('success', function(data) {
            if (data && data.bizError) {
                htmlContainer.html(etpl.render('Error'), {
                    msg: data.statusInfo
                });
            } else {
                 var  pullUpEl = document.getElementById('pullUp'); 
                if (!data.list.length&& data.page==1) {
                    htmlContainer.html(etpl.render('Error', {
                        msg: '当前没有数据呦'
                    }));
                    return;
                }
                if (data.page > data.pageall) {

                    //下拉没有更多啦
                        pullUpEl.className = '';
                        pullUpEl.querySelector('.pullUpLabel').innerHTML = '全部加载完毕'; 

                } else {
                    //更新当前页码
                    option.page = data.page;

                    //显示加载后的数据 
                    if (data.page == 1) {
                        htmlContainer.html(etpl.render("returnInvestList", {
                            list: data.list
                        }));
                        pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多';
                    } else {
                        htmlContainer.append(etpl.render("returnInvestList", {
                            list: data.list
                        }));
                    }

                    //刷新myScroll
                    setTimeout(function() {
                        myScroll.refresh();
                    }, 0);

                }



            }
        });
    }



    /**
     * 向上滑动加载列表
     */
    function pullUpAction() {
            option.page++; //当前页面加1，加载下一页 

            //ajax获取数据
            //setTimeout(function() { // <-- Simulate network congestion, remove setTimeout from production!  
            getList.remote(option);
            myScroll.refresh(); //// 数据加载完成后，调用界面更新方法 Remember to refresh when contents are loaded (ie: on ajax completion)
            // }, 1000); // <-- Simulate network congestion, remove setTimeout from production!
        }
        /**
         * 下拉加载最新数据
         * 
         */
    function pullDownAction() {
        option.page = 1; //第一页最新数据

        //ajax获取数据
        //setTimeout(function() { // <-- Simulate network congestion, remove setTimeout from production!  
        getList.remote(option);
        myScroll.refresh(); //// 数据加载完成后，调用界面更新方法 Remember to refresh when contents are loaded (ie: on ajax completion)
        // }, 1000); // <-- Simulate network congestion, remove setTimeout from production!
    }

    function loaded() { 
        myScroll = new iScroll('#wrapper', {
            probeType: 2,
            mouseWheel: false,
            bindToWrapper: true,
            scrollY: true
        });
        common.myScrollEvents(myScroll,pullUpAction,pullDownAction);


        setTimeout(function() {
            document.getElementById('wrapper').style.left = '0';
        }, 800);
        document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    }



    return {
        init: init
    };
});
