/**
 * @ignore
 * @file index
 * @author fanyy
 * 借款请求 步骤3 school
 * @time 15-5-19
 */

define(function(require) {
    var $ = require('jquery');
    var applyCommon = require('apply/common/applyCommon');
    require('common/ui/Form/selectBox');
    var util = require('common/util');
    var Remoter = require('common/Remoter');

    var schoolSubmit = new Remoter('APPLY_SCHOOL_SUBMIT');

    //select集合
    var selectArray = {
        purpose: $("#select-purpose"), //您的贷款使用用途是？
        guarantee_count: $("#select-guarantee_count") //兴教贷需要您的个人无限连带责任担保，除了您以外是否可以提供更多的担保人
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
        errorbox: $("#error-box"),
        purpose: $("#purpose-error"),
        guarantee_count: $("#guarantee_count-error"),
        address: $("#address-error"),
        total_student: $("#total_student-error"),
        staff: $("#staff-error"),
        branch_school: $("#branch_school-error")
    };

    //icon集合
    var iconArray = {
        purpose: $("#purpose-icon"),
        guarantee_count: $("#guarantee_count-icon"),
        address: $("#address-icon"),
        total_student: $("#total_student-icon"),
        staff: $("#staff-icon"),
        branch_school: $("#branch_school-icon")
    };



    var testNumber = /^\d+$/; //非负整数

    var formParams = {};

    //股东信息
    var stockArray = {
        stock: [],
        total: 0
    };

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

                icon.attr('class', "input-icon fl");
                error.html('');


            },
            blur: function() {
                var parent = $(this).parent().parent();
                var value = $.trim($(this).val());
                var text = $(this).attr('data-text');
                var icon = parent.find('.input-icon');
                var error = parent.find('.input-error');


                if (!value) {
                    icon.addClass('error');
                    error.html(text + '不能为空');
                }
            },
        });
        //股东输入事件单独处理
        $('.loan .stock-input input').on({
            focus: function() {
                errorArray.errorbox.html('');
            },
            blur: function() {
                var value = $.trim($(this).val());
                var text = $(this).attr('data-text');
                var name = $(this).attr('name');
                if (!value) {
                    errorArray.errorbox.html(text + '不能为空');
                } else {
                    if (name == "address") {
                        iconArray.address.addClass('success');
                        errorArray.address.html('');
                    }
                }
            },
        });

        //验证数字类型的输入框
        inputArray.total_student.blur(function(event) {
            var value = $.trim($(this).val());
            if (!testNumber.test(value)) {
                iconArray.total_student.addClass('error');
                errorArray.total_student.html(inputArray.total_student.attr('data-text') + '必须位数字！');
                return;
            }
            iconArray.total_student.addClass('success');
            errorArray.total_student.html('');
        });
        inputArray.staff.blur(function(event) {
            var value = $.trim($(this).val());
            if (!testNumber.test(value)) {
                iconArray.staff.addClass('error');
                errorArray.staff.html(inputArray.staff.attr('data-text') + '必须位数字！');
                return;
            }
            iconArray.staff.addClass('success');
            errorArray.staff.html('');
        });
        inputArray.branch_school.blur(function(event) {
            var value = $.trim($(this).val());
            if (!testNumber.test(value)) {
                iconArray.branch_school.addClass('error');
                errorArray.branch_school.html(inputArray.branch_school.attr('data-text') + '必须位数字！');
                return;
            }
            iconArray.branch_school.addClass('success');
            errorArray.branch_school.html('');
        });


        //select事件
        function selectEvent(e, value, text) {
            var parent = e.parent();
            var icon = parent.find('.input-icon');
            var error = parent.find('.input-error');
            var text = e.find('select').attr('data-text');

            if (!value) {
                icon.addClass('error');
                error.html(text + '不能为空');
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

            for (var item in stockInputArray) {
                var input = stockInputArray[item];
                if (!input.val()) {
                    errorArray.errorbox.html(input.attr('data-text') + '不能为空');
                    return;
                }
            }
            //先计算原有的股东数据
            stockArray = calStock();

            //新添加的股东数据
            var tempStock = {
                name: stockInputArray.name.val(),
                weight: stockInputArray.weight.val()
            };
            if (tempStock.weight < 20) {
                errorArray.errorbox.html('只需要填写占有股份20%以上的股东呦');
                return;
            }
            var total = stockArray.total;
            total = total + Number(tempStock.weight)

            if (total > 100) {
                errorArray.errorbox.html('再添加股份超过100%啦');
                return;
            }
            stockArray.total = total;

            //显示在列表里面
            var tr = '<tr weight=' + tempStock.weight + '><td>' + tempStock.name + '</td><td class="tr"><span class="weight">' + tempStock.weight + '</span> %</td><td class="tc"><i class="iconfont icon-delete del-stock"></i></td></tr>';
            $(tr).appendTo('.stock-list');
            $('.stock-total').html(stockArray.total);


            //删除股东
            $('.loan .del-stock').unbind('click').click(function(e) {

                /*  var tr = $(this).parent().parent();
                  var weight = tr.attr('weight');
                  tr.remove();
                  stockArray.total = stockArray.total - Number(weight);
                  $('.stock-total').html(stockArray.total + '%');
                  errorArray.errorbox.html('');*/
                delStock($(this));
            });

        }, 1000));

        //删除股东
        $('.loan .del-stock').unbind('click').click(function(e) {
            delStock($(this));
        });

        //删除股东的方法
        function delStock(e) {
            //TODO增加删除提示
            var tr = e.parent().parent();
            var weight = tr.attr('weight');
            tr.remove();
            stockArray.total = stockArray.total - Number(weight);
            $('.stock-total').html(stockArray.total);
            errorArray.errorbox.html('');
        };


        //下一步
        $('.loan .loan-submit').click(util.debounce(function(e) {
            e.preventDefault();
            for (var item in inputArray) {
                var input = inputArray[item];
                if (!input.val()) {
                    iconArray[item].addClass('error');
                    errorArray[item].html(input.attr('data-text') + '不能为空！');
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

            //计算股东数据
            /*   $('.stock-list tr').each(function() {
                   var tempStock = {
                       name: $(this).find("td:eq(0)").text(),
                       weight: $(this).find("td:eq(1)").find('.weight').text()
                   };
                   stockArray.stock.push(tempStock);
               });*/

            stockArray = calStock();

            schoolSubmit.remote({ 
                refer:util.getUrlParam('refer')=='1'?'/apply/review':'',
               
                address: inputArray.address.val(),
                total_student: inputArray.total_student.val(),
                staff: inputArray.staff.val(),
                branch_school: inputArray.branch_school.val(),

                guarantee_count: selectArray.guarantee_count.val(),
                purpose: selectArray.purpose.val(),
                stock: stockArray.stock
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
                errorArray.errorbox.html(data.statusInfo);
            } else {
                if (status === 302) {
                    window.location.href = data.data.url;
                }
            }
        });
    }

    //计算股东数据 
    function calStock() {
        var stockArrayTemp = {
            stock: [],
            total: 0
        };
        $('.stock-list tr').each(function() {
            var tempStock = {
                name: $(this).find("td:eq(0)").text(),
                weight: $(this).find("td:eq(1)").find('.weight').text()
            };
            stockArrayTemp.stock.push(tempStock);
            stockArrayTemp.total = stockArrayTemp.total + Number(tempStock.weight);
        });
        return stockArrayTemp;
    }
    return {
        init: init
    };
});
