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
    var util = require('common/util');
    var Remoter = require('common/Remoter');
    var verifySubmit = new Remoter('LOAN_REQUEST');
    var checkEmail = new Remoter('LOAN_REQUEST');

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
     * 步骤一 绑定事件
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

        //验证学校名称 ，必须为汉字  
        inputArray.name.blur(function() {
            var value = $.trim($(this).val());

            if (!value) {
                return;
            }

            if (!testName.test(value)) {
                iconArray.nameIcon.addClass('error');
                errorArray.nameError.html('与营业执照或登记证书一致');
                return;
            }
            iconArray.nameIcon.addClass('success');
        });
        //验证申请人姓名 ，必须为汉字 ，2-4个汉字 
        inputArray.realname.blur(function() {
            var value = $.trim($(this).val());

            if (!value) {
                return;
            }
            if (!testRealname.test(value)) {
                iconArray.realnameIcon.addClass('error');
                errorArray.realnameError.html('请输入真实姓名');
                return;
            }
            iconArray.realnameIcon.addClass('success');
        });

        // 密码格式验证
        inputArray.password.blur(function() {
            var value = $.trim($(this).val());

            if (!value) {
                return;
            }

            if (!testPwd.test(value)) {
                iconArray.passwordIcon.addClass('error');
                errorArray.passwordError.html('密码只能为 6 - 32 位数字，字母及常用符号组成');
                return;
            }
            iconArray.passwordIcon.addClass('success');
        });
        // 确认密码格式验证
        inputArray.password2.blur(function() {
            var pwd = $.trim($(inputArray.password).val());
            var value = $.trim($(this).val());

            if (!value) {
                return;
            }
            var lable = $(this).next();
            //检测两次密码是否一致
            if (value != pwd) {
                iconArray.password2Icon.addClass('error');
                errorArray.password2Error.html('两次输入的密码不一致 ');
                return;
            }
            iconArray.password2Icon.addClass('success');
        });
        // 检查邮箱
        inputArray.email.blur(function() {
            var value = $.trim($(this).val());

            if (value) {
                checkEmail.remote({
                    email: value
                });
            } 
        });

        //快速验证 
        $('.loan .loan-submit').click(util.debounce(function(e) {
            e.preventDefault();
        
             for (var item in inputArray) {
                if (!inputArray[item].val()) {
                    iconArray[item+"Icon"].addClass('error');
                    errorArray[item+"Error"].html('不能为空');
                    return;
                }
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
