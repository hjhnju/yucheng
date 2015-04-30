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
    var getList = new Remoter('INVEST_DETAIL_START');
    var etpl = require('etpl');
    var tpl = require('./index.tpl');
    var Pager = require('common/ui/Pager/Pager');
    var iScroll = require('m/common/iscroll'); 
    var moment = require('moment');
    var pager;
    var model = {};

    var htmlContainer;

    var myScroll;


    /**
     * 初始化方法
     * @param {Object} initData
     */
    function init(initData) {
        htmlContainer = $('#toulist');

        common.init();
        $.extend(model, initData);

        model.amountRest = +initData.amountRest.replace(',', '');
        model.days = +initData.days;
        model.userAmount = +initData.userAmount.replace(',', '');
        model.interest = +initData.interest;

        etpl.compile(tpl);
        getList.remote({
            page: 1,
            id: model.id
        });

        bindEvent();
        ajaxCallback();
    }

    /*
     *绑定事件
     */
    function bindEvent() {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(loaded, 200);
        }, false);
        //点击标题滑出详情
        $(".operate-content .list-title").click(function() {
            var current = $(this).next();
            var all = $(".operate-content .list-text");
            current.slideToggle();
            current.hasClass("current") ? current.removeClass("current") : (all.removeClass("current"), current.addClass("current"));

            var others = $(".operate-content .list-text").not(".current");
            others.slideUp();
        });


        //图片左右滚动 
        var picScroll = $("#files");
        if (picScroll) {
            myScroll = new iScroll(picScroll, {
                scrollX: true,
                scrollY: false,
                mouseWheel: false
            });
            document.addEventListener('touchmove', function(e) {
                e.preventDefault();
            }, false);
        }

    }


    /*
     *ajax回调函数
     * 
     */
    function ajaxCallback() {
        getList.on('success', function(data) {
            if (data && data.bizError) {
                Error.parent().addClass('show');
                Error.html(data.statusInfo);
            } else {

                if (!data.list.length) {
                    $('#page').html('');
                    htmlContainer.html(etpl.render('Error', {
                        msg: '当前没有数据哟'
                    }));
                    return;
                }

                if (!pager) {
                    pager = new Pager({
                        total: +data.pageall,
                        main: $('#page'),
                        startPage: 1
                    });

                    pager.on('change', function(e) {
                        getList.remote({
                            page: e.value,
                            id: model.id
                        });
                    })
                }

                pager.render(+data.page);

                for (var i = 0, l = data.list.length; i < l; i++) {
                    var tmp = data.list[i];
                    tmp.timeInfo = moment.unix(+tmp.create_time).format('YYYY-MM-DD HH:mm');
                }

                htmlContainer.html(etpl.render('list', {
                    list: data.list
                }));
            }
        })

    }


    return {
        init: init
    };
});
