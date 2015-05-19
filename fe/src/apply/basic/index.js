/**
 * @ignore
 * @file index
 * @author fanyy
 * 借款请求
 * @time 15-5-13
 */

define(function(require) {
    var $ = require('jquery');
    var applyCommon = require('apply/common/applyCommon');
    require('common/ui/Form/selectBox');
    var util = require('common/util');
    var Remoter = require('common/Remoter');

    //var basicSubmit=new Remoter('sssss');

    //select集合
    var selectArray = {
        type: $("#select-type"), //学校类型
        nature: $("#select-nature"), //学校主体性质
        province: $("#select-province"), //省
        city: $("#select-city"), //市
        schoolSource: $("#select-schoolSource"), //从哪里了解到我们
        year: $("#select-year") //建校时间 
    };

    //radio集合
    var radioArray = {
        incomeYear: $("[name='incomeYear']"), //年收入是否超过¥5,000,000
        profit: $("[name='profit']"), //最近一年是否盈利
        otherBusiness: $("[name='otherBusiness']") //除了本学校外，您是否还经营房地产、钢铁、采矿等类型业务  
    };

    //error集合
    var errorArray = {
        type: $("#type-error"), //学校类型
        nature: $("#nature-error"), //学校主体性质
        province: $("#province-error"), //省
        city: $("#city-error"), //市
        schoolSource: $("#schoolSource-error"), //从哪里了解到我们
        year: $("#year-error") //建校时间 
    };

    //icon集合
    var iconArray = {
        type: $("#type-icon"), //学校类型
        nature: $("#nature-icon"), //学校主体性质
        province: $("#province-icon"), //省
        city: $("#city-icon"), //市
        schoolSource: $("#schoolSource-icon"), //从哪里了解到我们
        year: $("#year-icon") //建校时间 
    };

    var testPwd = /^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/;
    var testName = /[\u4e00-\u9fa5]+/i;
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

        //提交评估 
        $('.loan .loan-submit').click(util.debounce(function(e) {
            e.preventDefault();

            for (var select in selectArray) {
                if (!selectArray[select].val()) {
                    iconArray[select].addClass('error');
                    errorArray[select].html('不能为空');
                    return;
                }
            }  

            basicSubmit.remote({
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

    }

    /**
     * 回调函数
     * @return {[type]} [description]
     */
    function ajaxCallback() {
        //提交后
        basicSubmit.on('success', function(data) {
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
