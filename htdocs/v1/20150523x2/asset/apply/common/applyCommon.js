define('apply/common/applyCommon', [
    'require',
    'jquery',
    'common/ui/RangeSlider/rangeSlider',
    'common/ui/Form/icheck',
    'common/util',
    'common/header'
], function (require) {
    var $ = require('jquery');
    require('common/ui/RangeSlider/rangeSlider');
    require('common/ui/Form/icheck');
    var util = require('common/util');
    var header = require('common/header');
    var loanForm = $('#loan-form');
    var rate1 = 0.13, rate2 = 0.18;
    var Vt = /%20/g, Ut = /\[\]$/, Yt = /\r?\n/g, Jt = /^(?:submit|button|image|reset|file)$/i, Gt = /^(?:input|select|textarea|keygen)/i;
    _e = /^(?:checkbox|radio)$/i;
    var testNumber = /^\d+$/;
    var fromTable = {
            amount: $('[amount]'),
            duration: $('[duration]'),
            service_charge: $('[service_charge]'),
            rate: $('[rate]'),
            interest_month: $('[interest_month]'),
            real_amount: $('[real_amount]'),
            total: $('[total]')
        };
    var commInputArray = {
            amount: $('[name=\'amount\']'),
            duration: $('[name=\'duration\']')
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
    function bindEvent() {
        var amountCookie = util.getCookie('amount');
        var durationCookie = util.getCookie('duration');
        var service_chargeCookie = util.getCookie('service_charge');
        if (amountCookie && durationCookie) {
            commInputArray.amount.val(amountCookie);
            $('input[name=\'duration\'][value=' + durationCookie + ']').attr('checked', true);
        }
        formParams = calParams();
        renderHTML(formParams);
        $('.loan-radio input').on('ifChecked', function () {
            formParams = calParams(), renderHTML(formParams);
        }).iCheck();
        loanNumSlider = $('.loan-num').ionRangeSlider({
            min: 5000,
            max: 3000000,
            step: 1000,
            from: 500000,
            hide_min_max: true,
            hide_from_to: true,
            grid: false
        });
        $('#loan-form .up,#loan-form .down').click(function (event) {
            var amount = util.removeCommas(commInputArray.amount.val());
            if ($(this).hasClass('up')) {
                amount = amount + 1000;
            } else if ($(this).hasClass('down')) {
                amount <= 5000 ? amount = 5000 : amount = amount - 1000;
            } else {
                return;
            }
            commInputArray.amount.val(util.addCommas(amount));
            commInputArray.amount.trigger('change');
        });
        $('input.loan-num').blur(function (event) {
            var value = util.removeCommas(commInputArray.amount.val());
            if (!testNumber.test(value)) {
                value = 5000;
            }
            value > 3000000 ? value = 3000000 : value < 5000 ? value = 5000 : value = value;
            commInputArray.amount.val(util.addCommas(value));
        });
        $('#loan-form').change(function () {
            formParams = calParams(), renderHTML(formParams);
        });
    }
    function calParams() {
        var e = {};
        var array = loanForm.serializeArray();
        $.map(array, function (t, i) {
            if (t.name == 'duration') {
                var duration = $('input:radio[name="duration"]:checked');
                e['duration_step_count'] = duration.attr('data-step');
                e['duration_type'] = duration.attr('duration_type');
            }
            e[t.name] = util.removeCommas(t.value);
        });
        util.setCookie('amount', e.amount, 1, '/apply');
        util.setCookie('duration', e.duration, 1, '/apply');
        util.setCookie('service_charge', e.service_charge, 1, '/apply');
        return loansCalculator(e);
    }
    function loansCalculator(e) {
        var amount = e.amount;
        var duration = e.duration_type == 2 ? e.duration * 30 : e.duration;
        var service_charge = amount * 0.005 + amount * 0.003 * e.duration_step_count;
        var interest_month1 = amount * rate1 / 365 * 30;
        var interest_month2 = amount * rate2 / 365 * 30;
        var real_amount = amount - service_charge;
        var total1 = amount + amount * rate1 / 365 * duration;
        var total2 = amount + amount * rate2 / 365 * duration;
        var temp = {
                service_charge: service_charge,
                rate1: rate1,
                rate2: rate2,
                interest_month1: interest_month1.toFixed(2),
                interest_month2: interest_month2.toFixed(2),
                real_amount: real_amount,
                total1: total1.toFixed(2),
                total2: total2.toFixed(2)
            };
        $.extend(e, temp);
        return e;
    }
    function renderHTML(e) {
        $('.duration-step-count').text(e.duration_step_count);
        fromTable.amount.html('\uFFE5' + util.addCommas(e.amount));
        fromTable.duration.html(e.duration + (e.duration_type == '2' ? '\u4E2A\u6708' : '\u5929'));
        fromTable.service_charge.html('\uFFE5' + util.addCommas(e.service_charge));
        fromTable.rate.html(util.toPercent(e.rate1) + '~' + util.toPercent(e.rate2));
        fromTable.interest_month.html('\uFFE5' + util.addCommas(e.interest_month1) + '~\uFFE5' + util.addCommas(e.interest_month2));
        fromTable.real_amount.html('\uFFE5' + util.addCommas(e.real_amount));
        fromTable.total.html('\uFFE5' + util.addCommas(e.total1) + '~\uFFE5' + util.addCommas(e.total2));
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