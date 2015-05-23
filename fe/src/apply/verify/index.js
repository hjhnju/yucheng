/**
 * @ignore
 * @file index
 * @author fanyy
 * 借款请求  步骤一
 * @time 15-5-13
 */

define(function(require) {
    var $ = require('jquery');
    var applyCommon = require('apply/common/applyCommon');
    var util = require('common/util');
    var config = require('common/config');
    var Remoter = require('common/Remoter');
    var verifySubmit = new Remoter('APPLY_VERIFY_SUBMIT');
    var checkEmail = new Remoter('APPLY_VERIFY_CHECKEMAIL');

    //input集合
    var inputArray = {
        name: $("[name='name']"), //学校名称
        realname: $("[name='realname']"), //申请人姓名
        email: $("[name='email']"), //邮箱地址  
        password: $("[name='password']"), //密码
        password2: $("[name='password2']"), //确认密码
        imagecode: $('#loan-imagecode') //验证码
    };

    //是否验证
    var statusArray = {
        name: 1,
        realname: 1,
        email: 1,
        password: 1,
        password2: 1,
        imagecode: 1
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

    var imgUrl = $('#login-img-url');
    var IMGURL = config.URL.IMG_GET;
    var imgcodeType = 'regist';


    function init(rate1, rate2) {
        formParams = applyCommon.init(rate1, rate2);
        bindEvent();
        ajaxCallback();
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

            if (!testName.test(value)) {
                iconArray.nameIcon.addClass('error');
                errorArray.nameError.html('与营业执照或登记证书一致');
                return;
            }
            iconArray.nameIcon.addClass('success');
        });
        //验证申请人姓名 ，必须为汉字  
        inputArray.realname.blur(function() {
            var value = $.trim($(this).val());

            if (!testName.test(value)) {
                iconArray.realnameIcon.addClass('error');
                errorArray.realnameError.html('请输入真实姓名');
                return;
            }
            iconArray.realnameIcon.addClass('success');
        });

        // 密码格式验证
        inputArray.password.blur(function() {
            var value = $.trim($(this).val());


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
                iconArray.password2Icon.addClass('error');
                errorArray.password2Error.html('确认密码不能为空');
                return;
            }
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

        //图片验证码 
        imgUrl.click(function(e) {
            e.preventDefault();
            $(this).attr('src', IMGURL  + imgcodeType + '&r=' + new Date().getTime());
        });

        //快速验证 
        $('.loan .loan-submit').click(util.debounce(function(e) {
            e.preventDefault();

            if ($(this).hasClass('login')) {
                statusArray.password = 0;
                statusArray.password2 = 0;
                statusArray.imagecode = 0;
            }
            for (var item in inputArray) {
                if (!inputArray[item].val() && statusArray[item]) {
                    iconArray[item + "Icon"].addClass('error');
                    errorArray[item + "Error"].html(inputArray[item].attr('data-text') + '不能为空');
                    return;
                }
            }

            verifySubmit.remote({
                amount: formParams.amount,
                duration: formParams.duration,
                duration_type: formParams.duration_type,
                service_charge: formParams.service_charge,

                name: inputArray.name.val(),
                realname: inputArray.realname.val(),
                email: inputArray.email.val(),

                password: statusArray.password ? inputArray.password.val() : '',
                imagecode: statusArray.imagecode ? inputArray.imagecode.val() : ''

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
                errorArray.emailError.html(data.statusInfo);
            } else {
                iconArray.emailIcon.addClass('success');
                errorArray.emailError.html('');
            }
        });
        //提交后
        verifySubmit.on('success', function(data) {
            if (data.imgCode) {
                errorArray.imgcode.html(data.statusInfo);
                imgUrl.attr('src', data.data.url  + imgcodeType + '&r=' + new Date().getTime());
            } else if (data && data.bizError) {
                errorArray.errorbox.html(data.statusInfo);
            } else {

            }
        });
    }
    return {
        init: init
    };
});
