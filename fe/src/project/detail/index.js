/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var util = require('common/util');
    var countDown = require('common/countDown');
    var Remoter = require('common/Remoter');
    var start = new Remoter('INVEST_DETAIL_START');
    var etpl = require('etpl');
    var tpl = require('./detail.tpl');
    var moment = require('moment');
    var Pager = require('common/ui/Pager/Pager');
    var header = require('common/header');

    var investTender = new Remoter('INVEST_DETAIL_CONFIRM_ADD');
    var pager;

    var model = {};

    /**
     * 初始化方法
     * @param {string} id
     */
    function init(initData) {
        header.init();
        $.extend(model, initData);

        model.amountRest = +initData.amountRest.replace(',', '');
        model.days = +initData.days;
        model.userAmount = +initData.userAmount.replace(',', '');
        model.interest = +initData.interest;

        etpl.compile(tpl);
        bindEvent();
        start.remote({
            page: 1,
            id: model.id
        });

        start.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                if(!pager) {
                    pager = new Pager({
                        total: +data.pageall,
                        main: $('#page'),
                        startPage: 1
                    });

                    pager.on('change', function (e) {
                        start.remote({
                            page: e.value,
                            id: model.id
                        });
                    })
                }

                pager.render(+data.page);
                for(var i = 0, l = data.list.length; i<l; i++) {
                    var tmp = data.list[i];
                    tmp.timeInfo = moment.unix(+tmp.create_time).format('YYYY-MM-DD hh:mm:ss');
                }
                $('#toulist').html(etpl.render('list', {
                    list:data.list
                }))
            }
        })

    }

    /**
     * 绑定事件
     */
    function bindEvent() {

        $('.showproject').click(function () {
            $(this).closest('.project-main').attr('class', 'project-main project');
        });

        $('.showfile').click(function () {
            $(this).closest('.project-main').attr('class', 'project-main file');
        });

        $('.showrecord').click(function () {
            $(this).closest('.project-main').attr('class', 'project-main record');
        });

        // 全部投资
        $('.confirm-all').click(function () {
            $('.right-top-ipt-input').val(Math.min(model.userAmount, model.amountRest));
        });

        // 确定投资
        $('.confirm-submit').click(function () {
            investTender.remote({
                id: model.id,
                amount: +$('.right-top-ipt-input').val() || 0
            });
        });

        // 投资盈利计算
        $('.right-top-ipt-input').on({
            keydown: function () {
                var value = +$.trim($(this).val());
                var min = Math.min(model.userAmount, model.amountRest);
                if (isNaN(value)) {
                    return;
                }

                if (value > min) {
                    $(this).val(min);
                    value = min;
                }

                $('.chongzhi-span').html(caculateIncome(value));
            },

            keyup: function () {
                var value = +$.trim($(this).val());
                var min = Math.min(model.userAmount, model.amountRest);
                if (isNaN(value)) {
                    return;
                }

                if (value > min) {
                    $(this).val(min);
                }

            }
        });

        if ($('.right-top-allmoney-time span').length > 0) {
            countDown.init('.right-top-allmoney-time span', model.sTime * 1000, model.eTime * 1000);
        }

        // 加入满标时间
        countDown.init('#full-time', model.sTime * 1000, model.fullTime * 1000);
    }

    function caculateIncome(money) {
        var income = money * model.interest / 100 * model.days / 365;
        income = income.toFixed(2);
        return util.addCommas(income);
    }

    return {
        init:init
    };
});
