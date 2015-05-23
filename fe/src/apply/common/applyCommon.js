/**
 * @ignore
 * @file applycommon
 * @author fanyy
 * 借款请求  通用模块
 * @time 15-5-15
 */

define(function(require) {
    var $ = require('jquery');
    require("common/ui/RangeSlider/rangeSlider");
    require("common/ui/Form/icheck");
    var util = require('common/util');
    var header = require('common/header');


    var loanForm = $("#loan-form");
    var rate1 = 0.13,
        rate2 = 0.18;

    var Vt = /%20/g,
        Ut = /\[\]$/,
        Yt = /\r?\n/g,
        Jt = /^(?:submit|button|image|reset|file)$/i,
        Gt = /^(?:input|select|textarea|keygen)/i;
    _e = /^(?:checkbox|radio)$/i;
    var testNumber = /^\d+$/; //非负整数

    //动态表格集合
    var fromTable = {
        amount: $("[amount]"), //贷款总额
        duration: $("[duration]"), //贷款期限，以天为单位
        service_charge: $("[service_charge]"), //服务费
        rate: $("[rate]"), //利率
        interest_month: $("[interest_month]"), //月平均利息
        real_amount: $("[real_amount]"), //实际到账
        total: $("[total]") //总还款金额
    };

    //通用input集合
    var commInputArray = {
        amount: $("[name='amount']"), //贷款总额
        duration: $("[name='duration']") //贷款期限，以天为单位 

    };

    var formParams;
    var loanNumSlider;

    function init(tempRate1, tempRate2) {
        if (isNaN(tempRate1) && isNaN(tempRate2)) {
            rate1 = tempRate1;
            rate2 = tempRate2;
        }
        header.init();
        bindEvent();

        return formParams;
    }

    /**
     * 通用步骤事件
     * @return {[type]} [description]
     */
    function bindEvent() {


        //读取缓存
        var amountCookie = util.getCookie('amount');
        var durationCookie = util.getCookie('duration');
        var service_chargeCookie = util.getCookie('service_charge');
        if (amountCookie && durationCookie) {
            commInputArray.amount.val(amountCookie);
            $("input[name='duration'][value=" + durationCookie + "]").attr("checked", true);
        }


        //页面加载后先执行一遍计算金额 
        formParams = calParams();
        renderHTML(formParams);

        //美化radio
        $('.loan-radio input').on('ifChecked', function() {
            formParams = calParams(), renderHTML(formParams);
        }).iCheck();

        //贷款数额滑块
        loanNumSlider = $(".loan-num").ionRangeSlider({
            min: 5000,
            max: 3000000,
            step: 1000,
            from: 500000,
            hide_min_max: true,
            hide_from_to: true,
            grid: false,
        });
        //上下箭头改变贷款金额事件
        $("#loan-form .up,#loan-form .down").click(function(event) {
            var amount = util.removeCommas(commInputArray
                .amount.val());
            if ($(this).hasClass('up')) {
                amount = amount + 1000;
            } else if ($(this).hasClass('down')) {
                amount <= 5000 ? amount = 5000 : amount = amount - 1000;
            } else {
                return;
            }
            commInputArray.amount.val(util.addCommas(amount));
            commInputArray.amount.trigger("change");
        });
        //自己输入金额
        $("input.loan-num").blur(function(event) {
            var value = util.removeCommas(commInputArray.amount.val());
            if (!testNumber.test(value)) {
                value = 5000;
            }
            value > 3000000 ? value = 3000000 : (value < 5000 ? value = 5000 : value = value);
            //格式化
            commInputArray.amount.val(util.addCommas(value));
        });

        //贷款金额数量变化事件 
        $("#loan-form").change(function() {
            formParams = calParams(), renderHTML(formParams);
        });
    }

    /**
     * 计算form表单参数
     * @return {[json]} [description]
     */
    function calParams() {
        var e = {};
        var array = loanForm.serializeArray();
        $.map(array, function(t, i) {
            if (t.name == "duration") {
                //期限类型单独处理
                var duration = $('input:radio[name="duration"]:checked');
                e["duration_step_count"] = duration.attr('data-step');
                e["duration_type"] = duration.attr("duration_type");
            }
            e[t.name] = util.removeCommas(t.value);
        });

        /*   
        //IE8不支持 reduce方法
           var e = loanForm.serializeArray().reduce(function(e,t) { 
                 return e[t.name] = util.removeCommas(t.value), e;
             }, {});
        */

        //写入缓存
        util.setCookie('amount', e.amount, 1, '/apply');
        util.setCookie('duration', e.duration, 1, '/apply');
        util.setCookie('service_charge', e.service_charge, 1, '/apply');
        return loansCalculator(e);
    }



    /**
     * 贷款计算器
     * @return {[json]} [贷款数据]
     * 输入：amount[贷款总额],duration[贷款期限，以天为单位]
     *  
     */
    function loansCalculator(e) {
        //(贷款总额*利率)/365*30
        var amount = e.amount; //贷款总额
        var duration = e.duration_type == 2 ? (e.duration * 30) : e.duration; //贷款期限，都换算成天计算

        var service_charge = amount * 0.005 + (amount * 0.003) * e.duration_step_count;
        var interest_month1 = (amount * rate1) / 365 * 30;
        var interest_month2 = (amount * rate2) / 365 * 30;
        var real_amount = amount - service_charge;
        var total1 = amount + ((amount * rate1) / 365 * duration);
        var total2 = amount + ((amount * rate2) / 365 * duration);
        var temp = {
            service_charge: service_charge, //服务费
            rate1: rate1, //利率1
            rate2: rate2, //利率2
            interest_month1: interest_month1.toFixed(2), //月平均利息1
            interest_month2: interest_month2.toFixed(2), //月平均利息2
            real_amount: real_amount, //实际到账
            total1: total1.toFixed(2), //总还款金额1
            total2: total2.toFixed(2) //总还款金额2
        }
        $.extend(e, temp);
        return e;
        /*      return { 
                  amount: amount, //贷款总额
                  duration: duration, //贷款期限， 
                  duration_type: e.duration_type, //期限类型
                  service_charge: service_charge, //服务费
                  rate1: rate1, //利率1
                  rate2: rate2, //利率2
                  interest_month1: interest_month1.toFixed(2), //月平均利息1
                  interest_month2: interest_month2.toFixed(2), //月平均利息2
                  real_amount: real_amount, //实际到账
                  total1: total1.toFixed(2), //总还款金额1
                  total2: total2.toFixed(2) //总还款金额2

              }*/
    }

    /**
     * 渲染 fromTable 
     * @param {json} e 表单参数 
     */
    function renderHTML(e) {
        //期次 
        $('.duration-step-count').text(e.duration_step_count);

        //贷款信息表格
        fromTable.amount.html("￥" + util.addCommas(e.amount));
        fromTable.duration.html(e.duration + (e.duration_type == "2" ? "个月" : "天"));
        fromTable.service_charge.html("￥" + util.addCommas(e.service_charge));
        fromTable.rate.html(util.toPercent(e.rate1) + "~" + util.toPercent(e.rate2));
        fromTable.interest_month.html("￥" + util.addCommas(e.interest_month1) + "~￥" + util.addCommas(e.interest_month2));
        fromTable.real_amount.html("￥" + util.addCommas(e.real_amount));
        fromTable.total.html("￥" + util.addCommas(e.total1) + "~￥" + util.addCommas(e.total2));

    }

    function calformParams(tempRate1, tempRate2) {
        if (isNaN(tempRate1) && isNaN(tempRate2)) {
            rate1 = tempRate1;
            rate2 = tempRate2;
        }
        formParams = calParams();
        return formParams;
    }

    return {
        init: init,
        calformParams: calformParams
    };
});
