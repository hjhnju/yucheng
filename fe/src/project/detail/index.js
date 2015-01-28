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

    var htmlContainer;

    var Error = $('#detail-error-span');

    var model = {};

    /**
     * 初始化方法
     * @param {string} id
     */
    function init(initData) {
        htmlContainer = $('#toulist');

        header.init();
        $.extend(model, initData);

        model.amountRest = +initData.amountRest.replace(',', '');
        model.days = +initData.days;
        model.userAmount = +initData.userAmount.replace(',', '');
        model.interest = +initData.interest;

        etpl.compile(tpl);
        bindEvent();
        htmlContainer.html(etpl.render('Loading'));
        start.remote({
            page: 1,
            id: model.id
        });

        start.on('success', function (data) {
            if(data && data.bizError) {
                Error.parent().addClass('show');
                Error.html(data.statusInfo);
            }
            else {

                if (!data.list.length) {
                    $('#page').html('');
                    htmlContainer.html(etpl.render('Error', {
                        msg: '当前没有数据哟'
                    }));
                    return;
                }

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

                htmlContainer.html(etpl.render('list', {
                    list:data.list
                }));
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
            var ipt = $('.right-top-ipt-input');
            var error = $('.chongzhi-error');

            error.hasClass('show') && error.removeClass('show');

            !ipt[0].disabled && ipt.val(Math.min(model.userAmount, model.amountRest));
        });

        // 点差消失error
        $('.detail-error-cha').click(function () {
            $(this).parent().remove();
        });

        // 确定投资
        $('.confirm-submit').click(function () {
//            investTender.remote({
//                id: model.id,
//                amount: +$('.right-top-ipt-input').val() || 0
//            });
            var ipt = $('.right-top-ipt-input');
            var error = $('.chongzhi-error');

            !ipt[0].disabled && !error.hasClass('show') && $('#invest-form').get(0).submit();
        });

        // 投资盈利计算
        $('.right-top-ipt-input').on({
            keydown: function () {
                var value = +$.trim($(this).val());
                var min = Math.min(model.userAmount, model.amountRest);
                var error = $('.chongzhi-error');
                if (isNaN(value)) {
                    error.addClass('show').html('输入内容不合法');
                    return;
                }

                if (value > min) {
                    error.addClass('show').html('投资金额不得超过可用余额和可投金额');
                    value > model.amountRest && $(this).val(model.amountRest);
                }
                else {
                    error.removeClass('show');
                }

                $('.chongzhi-span').html(caculateIncome(+$.trim($(this).val()) || 0));
            },

            keyup: function () {
                var value = +$.trim($(this).val());
                var min = Math.min(model.userAmount, model.amountRest);
                var error = $('.chongzhi-error');

                if (isNaN(value)) {
                    error.addClass('show').html('输入内容不合法');
                    return;
                }

                if (value > min) {
                    error.addClass('show').html('投资金额不得超过可用余额和可投金额');
                    value > model.amountRest && $(this).val(model.amountRest);
                }
                else {
                    error.removeClass('show');
                }

                $('.chongzhi-span').html(caculateIncome(+$.trim($(this).val()) || 0));
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
