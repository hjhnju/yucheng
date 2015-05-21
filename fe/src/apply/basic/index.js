/**
 * @ignore
 * @file index
 * @author fanyy
 * 借款请求  步骤二
 * @time 15-5-18
 */

define(function(require) {
    var $ = require('jquery');
    var applyCommon = require('apply/common/applyCommon');
    require('common/ui/Form/selectBox');
    var util = require('common/util');
    var Remoter = require('common/Remoter');

    var basicSubmit = new Remoter('APPLY_BASIC_SUBMIT');

    //select集合
    var selectArray = {
        type: $("#select-type"), //学校类型
        nature: $("#select-nature"), //学校主体性质
        province: $("#select-province"), //省
        city: $("#select-city"), //市
        school_source: $("#select-school_source"), //从哪里了解到我们
        year: $("#select-year") //建校时间 
    };

    //radio集合
    var radioArray = {
        is_annual_income: $("[name='is_annual_income']"), //年收入是否超过¥5,000,000
        is_profit: $("[name='is_profit']"), //最近一年是否盈利
        is_other_business: $("[name='is_other_business']") //除了本学校外，您是否还经营房地产、钢铁、采矿等类型业务  
    };

    //error集合
    var errorArray = {
        errorbox: $('#error-box'),
        type: $("#type-error"), //学校类型
        nature: $("#nature-error"), //学校主体性质
        province: $("#province-error"), //省
        city: $("#city-error"), //市
        school_source: $("#school_source-error"), //从哪里了解到我们
        year: $("#year-error") //建校时间 
    };

    //icon集合
    var iconArray = {
        type: $("#type-icon"), //学校类型
        nature: $("#nature-icon"), //学校主体性质
        province: $("#province-icon"), //省
        city: $("#city-icon"), //市
        school_source: $("#school_source-icon"), //从哪里了解到我们
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
            var select = e.find('select');
            var text = select.attr('data-text');

            if (!value) {
                icon.addClass('error');
                error.html(text + '不能为空');
            } else {
                icon.attr('class', "input-icon fl");
                error.html('');

                //城市级联单独处理
                if (select.attr('id') == 'select-province') {
                    selectArray.city.find('option').each(function(index, el) {
                        var option = $(this);
                        var pid = option.attr('pid');
                        if (pid && pid != value) {
                            option.attr('disabled', 'true');
                        } else {
                            option.removeAttr('disabled');
                        }
                        $('#selectBox_span_select-city').text('选择城市');
                    });
                }

                //根据学校性质更改收入money
                 if (select.attr('id') == 'select-type') {
                    var money = select.find('option:selected').attr('data-money');
                    $('.data-money').html(money+"");
                }

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

            for (var item in selectArray) {
                var select = selectArray[item];
                if (!select.val()) {
                    iconArray[item].addClass('error');
                    errorArray[item].html(select.attr('data-text') + '不能为空');
                    return;
                }
            }

            basicSubmit.remote({
                province: selectArray.province.val(),
                city: selectArray.city.val(),
                type: selectArray.type.val(),
                nature: selectArray.nature.val(),
                year: selectArray.year.val(),
                school_source: selectArray.school_source.val(),

                is_annual_income: $('input:radio[name="is_annual_income"]:checked'),
                is_profit: $('input:radio[name="is_profit"]:checked'),
                is_other_business: $('input:radio[name="is_other_business"]:checked')

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
                errorArray.errorbox.html(data.statusInfo);
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
