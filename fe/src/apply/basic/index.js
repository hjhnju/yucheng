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
    require('common/ui/Form/selectpick');
    var util = require('common/util');
    var Remoter = require('common/Remoter');

    //input集合
    var inputArray = {
        name: $("[name='name']"), //学校名称
        realname: $("[name='realname']"), //申请人姓名
        email: $("[name='email']"), //邮箱地址  
        password: $("[name='password']"), //密码
        password2: $("[name='password2']"), //确认密码
        imagecode: $('#loan-testing') //验证码
    };

    //error集合
    var errorArray = {
        errorbox: $("#error-box"),
        nameError: $("#name-error"), //学校名称
        realnameError: $("#realname-error"), //申请人姓名
        emailError: $("#email-error"), //邮箱地址  
        passwordError: $("#password-error"), //密码
        password2Error: $("#password2-error"), //确认密码
        imagecodeError: $('#loan-testing') //验证码
    };

    //icon集合
    var iconArray = {
        nameIcon: $("#name-icon"), //学校名称
        realnameIcon: $("#realname-icon"), //申请人姓名
        emailIcon: $("#email-icon"), //邮箱地址  
        passwordIcon: $("#password-icon"), //密码
        password2Icon: $("#password2-icon"), //确认密码
        imagecodeIcon: $('#imagecode-icon') //验证码
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
        $('.loan .form-inpt select').on({
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
            height: 30, // 下拉框的高度 
            width: 150, // 下拉框的宽度 
            optionColor: "#3BAFDA", // 下拉框选项滑动的颜色 
            selectedColor: "#3BAFDA", // 下拉框选项被选中的颜色 
            disabled: false, // 是否禁用,默认false不禁用 
            selectText: "", // 设置哪个选项被选中 
            onSelect: "" // 点击后选中回调函数 
        }
        //美化下拉框
        $(".loan .form-inpt select").selectpick(settings);

        //快速验证 
        $('.loan .loan-submit').click(util.debounce(function(e) {
            e.preventDefault();
            for (var item in inputArray) {
                item.trigger('blur');
            }

            verifySubmit.remote({
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
        // checkEmail  
        checkEmail.on('success', function(data) {
            if (data && data.bizError) {
                iconArray.emailIcon.addClass('error');
                errorArray.emailError.html('两次输入的密码不一致 ');
            } else {
                iconArray.emailIcon.addClass('success');
            }
        });
        //提交后
        verifySubmit.on('success', function(data) {
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
