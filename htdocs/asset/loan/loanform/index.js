define('loan/loanform/index', [
    'require',
    'jquery',
    'common/Remoter',
    'common/header'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var loanSubmit = new Remoter('LOAN_REQUEST');
    var header = require('common/header');
    var text;
    var iptId = {
            title: $('#title'),
            money: $('#money'),
            user: $('#user'),
            phone: $('#phone'),
            textArea: $('#textarea')
        };
    var selId = {
            selectSchool: $('#select-school'),
            selectUse: $('#select-use'),
            selectTime: $('#select-time'),
            selectCity: $('#select-city'),
            selectType: $('#select-type')
        };
    var mapText = {
            title: '\u501F\u6B3E\u6807\u9898',
            money: '\u501F\u6B3E\u91D1\u989D',
            user: '\u501F\u6B3E\u4EBA',
            phone: '\u8054\u7CFB\u65B9\u5F0F',
            textArea: '\u501F\u6B3E\u63CF\u8FF0'
        };
    function init() {
        bindEvent();
        header.init();
    }
    function bindEvent() {
        var ipt = $('.loan-form-right-ipt');
        var error = $('.form-error');
        ipt.on({
            focus: function () {
                error.html('');
            },
            blur: function () {
                var meVal = $(this).val();
                var text = $(this).parent().prev().text();
                if (!meVal) {
                    error.html(text + '\u5185\u5BB9\u4E0D\u80FD\u4E3A\u7A7A');
                }
            }
        });
        $('.form-con-right-link').click(function () {
            for (var i = 0; i < ipt.length; i++) {
                text = mapText[$(ipt[i]).attr('data-type')];
                if (!$(ipt[i]).val()) {
                    error.html(text + '\u5185\u5BB9\u4E0D\u80FD\u4E3A\u7A7A');
                    return;
                }
            }
            loanSubmit.remote({
                title: iptId.title.val(),
                money: iptId.money.val(),
                user: iptId.user.val(),
                phone: iptId.phone.val(),
                textarea: iptId.textArea.val(),
                schoolType: selId.selectSchool.val(),
                using: selId.selectUse.val(),
                time: selId.selectTime.val(),
                city: selId.selectCity.val(),
                returnType: selId.selectType.val()
            });
        });
        loanSubmit.on('success', function (data) {
            if (data && data.bizError) {
                error.html(data.statusInfo);
            } else {
                window.location.href = '/mock/entry/';
            }
        });
    }
    return { init: init };
});