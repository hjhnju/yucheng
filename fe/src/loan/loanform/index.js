/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var loanSubmit = new Remoter('LOAN_REQUEST');
    var header = require('common/header');
    var text;

    /**
     * input集合
     * @type {Object}
     */
    var iptId = {
        title: $('#title'),
        money: $('#money'),
        user: $('#user'),
        phone: $('#phone'),
        textArea: $('#textarea')
    };
    /**
     * select集合
     * @type {Object}
     */
    var selId = {
        selectSchool: $('#select-school'),
        selectUse: $('#select-use'),
        selectTime: $('#select-time'),
        selectCity: $('#select-city'),
        selectType: $('#select-type')
    };

    var mapText = {
        title: '借款标题',
        money: '借款金额',
        user: '借款人',
        phone: '联系方式',
        textArea: '借款描述'
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


                if(!meVal) {
                    error.html(text + '内容不能为空')
                }
            }
        });

        // 提交发送请求
        $('.form-con-right-link').click(function () {

            for(var i=0; i< ipt.length; i++) {
                text = mapText[$(ipt[i]).attr('data-type')];
                if(!$(ipt[i]).val()) {
                    error.html(text + '内容不能为空');
                    return;
                }
            }

            loanSubmit.remote({
                title: iptId.title.val(),
                amount: iptId.money.val(),
                name: iptId.user.val(),
                contact: iptId.phone.val(),
                content: iptId.textArea.val(),
                school_type: selId.selectSchool.val(),
                use_type: selId.selectUse.val(),
                duration: selId.selectTime.val(),
                prov_id: selId.selectCity.val(),
                refund_type: selId.selectType.val()
            });
        });
        // loanSubmitCb
        loanSubmit.on('success', function (data) {
            if(data && data.bizError) {
                error.html(data.statusInfo);
            }

            else {
                window.location.href = '/loan/success';
            }
        });

    }

    return {
        init: init
    };
});
