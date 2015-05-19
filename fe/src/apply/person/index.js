/**
 * @ignore
 * @file index
 * @author fanyy
 * 借款请求 步骤4
 * @time 15-5-19
 */

define(function(require) {
    var $ = require('jquery');
    var applyCommon = require('apply/common/applyCommon');
    require('common/ui/Form/selectBox');
    var util = require('common/util');
    var Remoter = require('common/Remoter');

    //var schoolSubmit=new Remoter('sssss');

    //select集合
    var selectArray = {
        scope_cash: $("#select-scope_cash"), //现金账户余额
        scope_stock: $("#select-scope_stock") //股票、债券等其他有价证券资产
    };

    //input集合
    var inputArray = {
        certificate: $("[name='certificate']"), //身份证
        detail_address: $("[name='detail_address']"), //＊您的住房详细地址
        cellphone: $("[name='cellphone']"), //手机号码
        telephone: $("[name='telephone']") //固定电话

    };

    //radio集合
    var radioArray = {
        house_type: $("[name='house_type']"),//＊您目前的住房类型
        is_criminal: $("[name='name']"), //您是否有犯罪记录
        is_lawsuit: $("[name='is_lawsuit']") //是否有未决诉讼  
    };

    //error集合
    var errorArray = {
        certificate: $("#certificate-error"),
        detail_address: $("#detail_address-error"),
        cellphone: $("#cellphone-error"),
        telephone: $("#telephone-error")
    };

    //icon集合
    var iconArray = {
        purpose: $("#purpose-icon"),
        ismoreguarantee: $("#ismoreguarantee-icon"),
        address: $("#address-icon"),
        total_student: $("#total_student-icon"),
        staff: $("#staff-icon"),
        branch_school: $("#branch_school-icon")
    };


    var testRealname = /^[\u4e00-\u9fa5]{2,4}$/;

    var formParams;

    function init(rate1, rate2) {
        formParams = applyCommon.init(rate1, rate2);
        bindEvent();
    }

    /**
     * 步骤二 绑定事件
     *  
     */
    function bindEvent() {
        // 控制placeHolder
        $('.loan .form-inpt input').on({
            focus: function() {
                var parent = $(this).parent().parent();
                var icon = parent.find('.input-icon');
                var error = parent.find('.input-error');
                var lable = $(this).next();

                lable.addClass('hidden');
                icon.attr('class', "input-icon fl");
                error.html('');


            },
            blur: function() {
                var parent = $(this).parent().parent();
                var value = $.trim($(this).val());
                var text = $(this).attr('data-text');
                var icon = parent.find('.input-icon');
                var error = parent.find('.input-error');
                var lable = $(this).next();

                if (!value) {
                    lable.removeClass('hidden');
                    icon.addClass('error');
                    error.html(text + '不能为空');
                }
            },
        });

        

        var settings = {
            className: "select_body",
            optionColor: "#4fc501", // 下拉框选项滑动的颜色 
            selectedColor: "#4fc501", // 下拉框选项被选中的颜色 
            disabled: false, // 是否禁用,默认false不禁用 
            selectText: "", // 设置哪个选项被选中 
            onSelect: ""  // 点击后选中回调函数  
        }

        //美化下拉框
        $(".loan .form-inpt select").selectBox(settings);

        // 下一步
        $('.loan .add-stock').click(util.debounce(function(e) {
            e.preventDefault();
 
            for (var item in inputArray) {
                var input = inputArray[item];
                if (!input.val()) { 
                     iconArray[item].addClass('error');
                     errorArray[item].html('不能为空！');
                    return;
                }
            }

            schoolSubmit.remote({
                amount: formParams.amount,
                duration: formParams.duration,
                durationType: formParams.durationType,
                serviceCharge: formParams.serviceCharge,
                userid: $("[name=userid]"), //用户ID,

                nature: selectArray.nature.val(),
                province: selectArray.province.val(),
                city: selectArray.city.val(),
                school_source: selectArray.schoolSource.val(),
                year: selectArray.year.val(),

                incomeYear: radioArray.incomeYear.val(),
                profit: radioArray.profit.val(),
                otherBusiness: radioArray.otherBusiness.val(),
            });

        }, 1000));

        //添加股东
        $('.loan .loan-submit').click(util.debounce(function(e) {
            e.preventDefault();
            for (var item in stockInputArray) {
                var input = stockInputArray[item];
                if (!input.val()) {
                    $("#error-box").html(input.attr('data-text') + "不能为空！");
                    return;
                }
            }
            schoolSubmit.remote({
                amount: formParams.amount,
                duration: formParams.duration,
                durationType: formParams.durationType,
                serviceCharge: formParams.serviceCharge,
                userid: $("[name=userid]"), //用户ID,

                realname: inputArray.realname.val(),
                name: inputArray.name.val(),
                email: inputArray.email.val(),
                password: inputArray.password.val(),
                imagecode: inputArray.imagecode.val()
            });

        }, 1000));

    }

    /**
     * 回调函数
     * @return {[type]} [description]
     */
    function ajaxCallback() {
        //提交后
        schoolSubmit.on('success', function(data) {
            if (data && data.bizError) {
                errorArray.errorbox(data.statusInfo);
            } else {
                if (status === 302) {
                    window.location.href = data.data.url;
                }
            }
        });
    }
    return {
        init: init
    };
});
