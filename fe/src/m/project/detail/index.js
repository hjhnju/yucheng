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

    var flexsliderCount = 0;
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
        //点击标题滑出详情
        $(".box-content .pro-title").click(function() {


            var current = $(this).next();
            var all = $(".box-content .pro-content");
            current.slideToggle(1000, function() {flexSlider()});
            current.hasClass("current") ? current.removeClass("current") : (all.removeClass("current"), current.addClass("current"));

            var others = all.not(".current");
            others.slideUp();


        });
        slide();
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

    /*
     * 幻灯片效果
     * */
    function slide() {
        var blueimp = require('common/extra/Gallery/js/blueimp-gallery');
        $("#files .slides").click(function(event) {
            event = event || window.event;
            var target = event.target || event.srcElement;
            var link = target.src ? target.parentNode : target;
            var options = {
                index: link,
                event: event,
                onclosed: function() {}
            };
            var links = this.getElementsByTagName('a');
            blueimp(links, options);
        });
    }

    /*
      图片轮播效果
     */
    function flexSlider() {
        if (flexsliderCount == 0) {
            require('common/extra/FlexSlider/jquery.flexslider');
            $("#files").flexslider({
                animation: "slide",
                controlNav: false,
                minItems: 1, //{NEW} Integer: 一次最少展示滑动内容的单元个数
                maxItems: 1, //{NEW} Integer: 一次最多展示滑动内容的单元个数
                move: 1
            });
            flexsliderCount=1;
        }

    }
    return {
        init: init
    };
});
