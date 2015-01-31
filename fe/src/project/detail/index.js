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
    var investError = $('#chongzhi-error');

    var model = {};

    /**
     * 初始化方法
     * @param {Object} initData
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
                    tmp.timeInfo = moment.unix(+tmp.create_time).format('YYYY-MM-DD HH:mm');
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

            investError.hasClass('show') && investError.removeClass('show');

            if (ipt[0].disabled) {
                return;
            }

            ipt.val(Math.min(model.userAmount, model.amountRest));
        });

        // 点差消失error
        $('.detail-error-cha').click(function () {
            $(this).parent().remove();
        });

        // 确定投资
        $('.confirm-submit').click(function () {
            var ipt = $('.right-top-ipt-input');
            var value = +$.trim(ipt.val());

            // 已经提示error或者不可投资
            if (ipt[0].disabled) {
                return;
            }

            // 输入不能为空
            if (!value) {
                investError.addClass('show').html('输入不能为空');
                return;
            }

            // 输入不合法
            if (isNaN(value)) {
                investError.addClass('show').html('输入内容不合法');
                return;
            }

            // 最后一标不得小于100
            if (model.amountRest < 100 && value < model.amountRest) {
                investError.addClass('show').html('投标金额必须为' + model.amountRest + '元');
                return;
            }

            // 不可大于可用余额
            if (value > model.userAmount) {
                investError.addClass('show').html('可用余额不足');
                return;
            }

            // 输入不能小于100
            if (value < 100) {
                investError.addClass('show').html('最小投标金额100元');
                return;
            }

            // 超过可投金额
            if (value > model.amountRest) {
                investError.addClass('show').html('可投金额不足');
                return;
            }

            $('#invest-form').get(0).submit();
        });

        // 投资盈利计算
        $('.right-top-ipt-input').on({
            keyup: function () {
                var value = +$.trim($(this).val());
                var tip = $('.chongzhi-span');

                if (isNaN(value)) {
                    tip.html('0.00');
                    return;
                }

                tip.html(caculateIncome(+$.trim($(this).val()) || 0));
            },
            blur: function () {
                var value = +$.trim($(this).val());

                if (isNaN(value)) {
                    investError.addClass('show').html('输入内容不合法');
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
