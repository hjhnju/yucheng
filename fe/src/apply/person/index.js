/**
 * @ignore
 * @file index
 * @author fanyy
 * 借款请求 步骤4  person/index
 * @time 15-5-19
 */

define(function(require) {
    var $ = require('jquery');
    var applyCommon = require('apply/common/applyCommon');
    require('common/ui/Form/selectBox');
    var util = require('common/util');
    var Remoter = require('common/Remoter');
    var checkCellphone = new Remoter('REGIST_CHECKPHONE_CHECK');
    //var checkTelephone = new Remoter('REGIST_CHECKPHONE_CHECK');
    var checkCertificate = new Remoter('APPLY_VERIFY_CHECKIDCARD');
    var personSubmit = new Remoter('APPLY_PERSON_SUBMIT');

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
        house_type: $("[name='house_type']"), //＊您目前的住房类型
        is_criminal: $("[name='is_criminal']"), //您是否有犯罪记录
        is_lawsuit: $("[name='is_lawsuit']") //是否有未决诉讼  
    };

    //error集合
    var errorArray = {
        errorbox: $('#error-box'),
        certificate: $("#certificate-error"),
        detail_address: $("#detail_address-error"),
        cellphone: $("#cellphone-error"),
        telephone: $("#telephone-error")
    };

    //icon集合
    var iconArray = {
        certificate: $("#certificate-icon"),
        detail_address: $("#detail_address-icon"),
        cellphone: $("#cellphone-icon"),
        telephone: $("#telephone-icon")
    };


    var testRealname = /^[\u4e00-\u9fa5]{2,4}$/;

    var formParams;

    function init(rate1, rate2) {
        formParams = applyCommon.init(rate1, rate2);
        bindEvent();
        ajaxCallback();
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
                    return;
                } else {
                    icon.addClass('success');
                    error.html('');
                }
            },
        });

        var settings = {
            className: "select_body",
            optionColor: "#4fc501", // 下拉框选项滑动的颜色 
            selectedColor: "#4fc501", // 下拉框选项被选中的颜色 
            disabled: false, // 是否禁用,默认false不禁用 
            selectText: "", // 设置哪个选项被选中 
            onSelect: "" // 点击后选中回调函数  
        }

        //美化下拉框
        $(".loan .form-inpt select").selectBox(settings);

        //验证身份证号码 
        inputArray.certificate.blur(function(event) {
            var value = $.trim($(this).val());
            if (value) {
                checkCertificate.remote({
                    idcard: inputArray.certificate.val()
                });
            }
        });
        //验证手机号码 
        inputArray.cellphone.blur(function(event) {
            var value = $.trim($(this).val());
            if (value) {
                errorArray.cellphone.html('');
                iconArray.cellphone.addClass('success')
            }
            //暂时验证手机号码
            /*if (value) {
                checkCellphone.remote({
                    phone: inputArray.cellphone.val()
                });
            }*/
        });
        //验证固定电话 
        inputArray.telephone.blur(function(event) {
            var value = $.trim($(this).val());
            if (value) {
                errorArray.telephone.html('');
                iconArray.telephone.addClass('success')
            }
        });

        // 下一步 
        $('.loan .loan-submit').click(util.debounce(function(e) {
            e.preventDefault();
            for (var item in inputArray) {
                var input = inputArray[item];
                if (!input.val()) {
                    iconArray[item].addClass('error');
                    errorArray[item].html(input.attr('data-text') + "不能为空！");
                    return;
                }
            }
            for (var item in selectArray) {
                var select = selectArray[item];
                if (!select.val()) {
                    iconArray[item].addClass('error');
                    errorArray[item].html(select.attr('data-text') + "不能为空！");
                    return;
                }
            }
            if (!radioArray.house_type.val()) {
                iconArray.house_type.addClass('error');
                errorArray.house_type.html("住房类型不能为空！");
                return;
            }

            personSubmit.remote({
                detail_address: inputArray.detail_address.val(),
                certificate: inputArray.certificate.val(),
                cellphone: inputArray.cellphone.val(),
                telephone: inputArray.telephone.val(),

                house_type: radioArray.house_type.val(),
                is_criminal: radioArray.is_criminal.val(),
                is_lawsuit: radioArray.is_lawsuit.val(),

                scope_cash: selectArray.scope_cash.val(),
                scope_stock: selectArray.scope_stock.val()

            });


        }, 1000));

    }

    /**
     * 回调函数
     * @return {[type]} [description]
     */
    function ajaxCallback() {
        //Submit提交后
        personSubmit.on('success', function(data) {
            if (data && data.bizError) {
                errorArray.errorbox.html(data.statusInfo);
            } else {

            }
        });
        //验证身份证号码
        checkCertificate.on('success', function(data) {
            if (data && data.bizError) {
                iconArray.certificate.addClass('error')
                errorArray.certificate.html(data.statusInfo);
            } else {
                errorArray.certificate.html('');
                iconArray.certificate.addClass('success')
            }
        });
        //验证手机号码
        checkCellphone.on('success', function(data) {
            if (data && data.bizError) {
                iconArray.cellphone.addClass('error')
                errorArray.cellphone.html(data.statusInfo);
            } else {
                errorArray.cellphone.html('');
                iconArray.cellphone.addClass('success')
            }
        });
        //验证固定电话
        /*  checkTelephone.on('success', function(data) {
              if (data && data.bizError) {
                  iconArray.telephone.addClass('error')
                  errorArray.telephone.html(data.statusInfo);
              } else {
                  errorArray.telephone.html('');
                  iconArray.telephone.addClass('success')
              }
          });*/
    }
    return {
        init: init
    };
});
