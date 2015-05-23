/**
 * @ignore
 * @file bid.js
 * @author fanyy
 * @time 15-5-5
 */

define(function(require) {
    var $ = require('jquery');
    var common = require('m/common/common');
    var Remoter = require('common/Remoter');
    var util = require('common/util');
    var investError = $('#chongzhi-error');

    var model = {};
    /**
     * 初始化方法
     * @param {Object} initData
     */
    function init(initData) {
            $.extend(model, initData);

            model.amountRest = +initData.amountRest.replace(',', '');
            model.days = +initData.days;
            model.userAmount = +initData.userAmount.replace(',', '');
            model.interest = +initData.interest;
            bindEvent();
        }
        /**
         * 绑定事件
         */
    function bindEvent() {


        // 确定投资
        $('.confirm-submit').click(function() {

            $('#invest-form').trigger('submit');
        });
        // 全部投资
        $('.confirm-all').click(function() {
            var ipt = $('.right-top-ipt-input');
            var tip = $('.chongzhi-span');

            investError.hasClass('show') && investError.removeClass('show');

            if (ipt[0].disabled) {
                return;
            } 
            var value=Math.min(model.userAmount, model.amountRest);
            ipt.val(value); 
            tip.html(caculateIncome(value || 0));
        });

        // 点差消失error
        $('.detail-error-cha').click(function() {
            $(this).parent().remove();
        });
        // form 提交
        $('#invest-form').on('submit', function() {
            var ipt = $('.right-top-ipt-input');
            var value = +$.trim(ipt.val());

            // 已经提示error或者不可投资
            if (ipt[0].disabled) {
                return false;
            }
            //未同意协议不可投资
            if (!$('#tiaoyue-itp')[0].checked) { 
                investError.addClass('show').html('请同意用户协议');
                return false;
            }

            // 输入不能为空
            if (!value) {
                investError.addClass('show').html('输入不能为空');
                return false;
            }

            // 输入不合法
            if (isNaN(value)) {
                investError.addClass('show').html('输入内容不合法');
                return false;
            }

            // 最后一标不得小于100
            if (model.amountRest < 100 && value !== model.amountRest) {
                investError.addClass('show').html('投标金额必须为' + model.amountRest + '元');
                return false;
            }

            // 不可大于可用余额
            if (value > model.userAmount) {
                investError.addClass('show').html('可用余额不足');
                return false;
            }

            // 输入不能小于100
            if (value < 100 && value !== model.amountRest) {
                investError.addClass('show').html('最小投标金额100元');
                return false;
            }

            // 超过可投金额
            if (value > model.amountRest) {
                investError.addClass('show').html('可投金额不足');
                return false;
            }

        });
        // 投资盈利计算
        $('.right-top-ipt-input').on({ 
            keyup: function() {
                var value = +$.trim($(this).val());
                var tip = $('.chongzhi-span');

                if (isNaN(value)) {
                    tip.html('0.00');
                    $(this).val(0);
                    return;
                }
                
                 //不能超过最大值
                var max =$(this).attr("max");
                if(value>max){
                	$(this).val(max);
                }


                tip.html(caculateIncome(+$.trim($(this).val()) || 0));



            },
            blur: function() {
                /*var value = +$.trim($(this).val());

                if (isNaN(value)) {
                    investError.addClass('show').html('输入内容不合法');
                }*/

                 
            }
        });
    }

    function caculateIncome(money) {
        var income = money * model.interest / 100 * model.days / 365;
        income = income.toFixed(2);
        return util.addCommas(income);
    }
    return {
        init: init
    };
});
