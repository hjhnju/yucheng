/**
 * @ignore
 * @file index
 * @author fanyy
 * 借款请求 步骤3
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
        purpose: $("#select-purpose"), //您的贷款使用用途是？
        ismoreguarantee: $("#select-ismoreguarantee") //兴教贷需要您的个人无限连带责任担保，除了您以外是否可以提供更多的担保人
    };

    //input集合
    var inputArray = {
        address: $("[name='address']"), //学校地址
        total_student: $("[name='total_student']"), //您的学校有多少学生？
        staff: $("[name='staff']"), //您的学校有多少教职工？
        branch_school: $("[name='branch_school']") //您已开了几所分校？ 

    };

    //stockInput集合
    var stockInputArray = {
        name: $("[name='name']"), //姓名
        weight: $("[name='weight']") //比重  
    };

    //error集合
    var errorArray = {
        purpose: $("#purpose-error"),
        ismoreguarantee: $("#ismoreguarantee-error"),
        address: $("#address-error"),
        total_student: $("#total_student-error"),
        staff: $("#staff-error"),
        branch_school: $("#branch_school-error")
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

        //select事件
        function selectEvent(e, value, text) {
            var parent = e.parent();
            var icon = parent.find('.input-icon');
            var error = parent.find('.input-error');

            if (!value) {
                icon.addClass('error');
                error.html('不能为空');
            } else {
                icon.attr('class', "input-icon fl");
                error.html('');
            }
        }

        var settings = {
            className: "select_body",
            optionColor: "#4fc501", // 下拉框选项滑动的颜色 
            selectedColor: "#4fc501", // 下拉框选项被选中的颜色 
            disabled: false, // 是否禁用,默认false不禁用 
            selectText: "", // 设置哪个选项被选中 
            onSelect: function(e, value, text) {
                selectEvent(e, value, text); // 点击后选中回调函数 
            }
        }

        //美化下拉框
        $(".loan .form-inpt select").selectBox(settings);

        // 添加股东
        $('.loan .add-stock').click(util.debounce(function(e) {
            e.preventDefault();

            for (var select in selectArray) {
                if (!selectArray[select].val()) {
                    iconArray[select].addClass('error');
                    errorArray[select].html('不能为空');
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

        //下一步
        $('.loan .loan-submit').click(util.debounce(function(e) {
            e.preventDefault();
            for (var item in inputArray) {
                var input = inputArray[item];
                if (!input.val()) {
                    iconArray[item].addClass('error');
                     errorArray[item].html('不能为空！');
                    return;
                }
            }
            for (var item in selectArray) {
                var input = selectArray[item];
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
