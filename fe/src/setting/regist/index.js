/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function(require) {

    var $ = require('jquery');
    require("common/ui/Form/icheck");
    var picScroll = require('../common/picScroll');
    var util = require('common/util');
    var header = require('common/header');
    var dialog = require('common/ui/Dialog/Dialog');
    var login = require('setting/login/index');
    var etpl = require('etpl');
    var tpl = require('./regist.tpl');

    var Remoter = require('common/Remoter');
    var checkName = new Remoter('REGIST_CHECKNAME_CHECK');
    var checkphone = new Remoter('REGIST_CHECKPHONE_CHECK');
    var sendsmscode = new Remoter('REGIST_SENDSMSCODE_CHECK');
    var checkInviter = new Remoter('REGIST_CHECKINVITER_CHECK');
    var checksmscode = new Remoter('REGIST_CHECKSMSCODE_CHECK');
    var registSubmit = new Remoter('REGIST_INDEX_CHECK');

    var testPwd = /^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/;
    var CORRECT = '<span class="username-error-span"></span>';

    /**
     * input集合
     * @type {Object}
     */
    var loginInput = {
        loginUser: $('#regist-user'),
        loginPwd: $('#regist-pwd'),
        loginPwd2: $('#regist-pwd2'),
        loginPhone: $('#regist-phone'),
        loginTest: $('#regist-testing'),
        loginTuiJian: $('#regist-tuijian'),

        loginEmail: $('#regist-email'),
        loginImgcode: $('#regist-email')
    };

    /**
     * error集合
     * @type {Object}
     */
    var error = {
        userError: $('#regist-user-error'),
        phoneError: $('#regist-phone-error'),
        pwd2Error: $('#regist-pwd2-error'),
        pwdError: $('#regist-pwd-error'),
        testError: $('#regist-testing-error'),
        tuiJianError: $('#regist-tuijian-error')
    };
    /**
     * 提示集合
     * @type {Object}
     */
    var point = {
        userPointIcon: $('#regist-user-point'),
        phonePointIcon: $('#regist-phone-point'),
        pwdPointIcon: $('#regist-pwd-point'),
        pwd2PointIcon: $('#regist-pwd2-point'),
        testPointIcon: $('#regist-testing-point'),
        tuiJianPointIcon: $('#regist-tuijian-point'),

        userPointTip: $('#regist-user-point').next(),
        phonePointTip: $('#regist-phone-point').next(),
        pwdPointTip: $('#regist-pwd-point').next(),
        pwd2PointTip: $('#regist-pwd2-point').next(),
        testPointTip: $('#regist-testing-point').next(),
        tuiJianPointTip: $('#regist-tuijian-point').next()
    };
    var allStatus = {
        user: 0,
        phone: 0,
        pwd: 0,
        vericode: 0,
        tui: 1
    };

    var mapInput = {
        user: 'loginUser',
        phone: 'loginPhone',
        pwd: 'loginPwd',
        vericode: 'loginTest',
        tui: 'loginTuiJian'
    };

    var isthird;

    var usertype = 3;

    function init(third) {
        isthird = third ? 1 : 0;
        //header.init();
        etpl.compile(tpl);
        picScroll.init();
        dialog.init();
        bindEvents();
        callBack();
        yazma();
    }

    function bindEvents() {
        //选择注册类型//美化radio 
        $('.regist-radio input').on('ifChecked', function() {
            usertype = $('input:radio[name="usertype"]:checked').val();
            $('.regist-box-content').hide();
            $('.regist-box-content[data-tab="' + usertype + '"]').show();
        }).iCheck();


        // 控制placeHolder
        $('.regist .login-input').on({
            focus: function() {
                var parent = $(this).parent();
                var error = parent.children('.username-error');

                var id = $(this).attr('id');
                var pointIcon = parent.find("#" + id + "-point");
                var pointTip = parent.find(".username-point");

                parent.removeClass('current');
                error.html('');

                pointIcon.html("");
                pointTip.show();
                $(this).next().addClass('hidden');
            },
            blur: function() {
                var parent = $(this).parent();
                var value = $.trim($(this).val());
                var text = $(this).attr('data-text');
                var id = $(this).attr('id');


                if (!value) {
                    // 不是推荐
                    if (!$(this).hasClass('login-tuijian')) {
                        parent.addClass('current');
                        $('#' + id + '-error').html(text + '不能为空');
                    }

                    $(this).next().removeClass('hidden');
                }
            },
        });


        if (usertype == 1) { //投资账户 
            // 检查用户名
            loginInput.loginUser.blur(function() {
                var value = $.trim($(this).val());

                if (value) {
                    checkName.remote({
                        name: value
                    });
                } else {
                    allStatus.user = 0;
                }
            });
            // 检查手机号
            loginInput.loginPhone.blur(function() {
                var value = $.trim($(this).val());

                if (value) {
                    checkphone.remote({
                        phone: value
                    });
                } else {
                    allStatus.phone = 0;
                    $('.login-username-testing').addClass('disabled');
                }
            });
            // 检查验证码
            loginInput.loginTest.blur(function() {
                var value = $.trim($(this).val());
                var phone = $.trim(loginInput.loginPhone.val());

                if (!phone) {
                    loginInput.loginPhone.trigger('blur');
                    allStatus.vericode = 0;
                    return;
                }

                if (value) {
                    checksmscode.remote({
                        vericode: value,
                        phone: phone,
                        type: 'regist'
                    });
                } else {
                    allStatus.vericode = 0;
                }
            });

            // 检查是否获取验证码
            $('.login-username-testing').click(util.debounce(function(e) {
                e.preventDefault();

                var value = $.trim(loginInput.loginPhone.val());

                if (!$(this).hasClass('disabled') && value) {
                    sendsmscode.remote({
                        phone: value,
                        type: 'regist'
                    });
                }

            }, 1000));

            // 检查推荐人
            loginInput.loginTuiJian.blur(function() {
                var value = $.trim($(this).val());

                if (value) {
                    checkInviter.remote({
                        inviter: value
                    });
                }

            });
        } else if (usertype == 3) { //贷款账户
            //邮箱验证格式
            loginInput.loginEmail.blur(function() {
                var value = $.trim($(this).val());
                if (value) {
                    checkName.remote({
                        name: value
                    });
                } else {
                    allStatus.user = 0;
                }
            });
        }



        // 密码格式验证
        loginInput.loginPwd.blur(function() {
            var value = $.trim($(this).val());

            if (!value) {
                allStatus.pwd = 0;
                return;
            }

            if (!testPwd.test(value)) {
                allStatus.pwd = 0;
                $(this).parent().addClass('current');
                error.pwd2Error.html('密码只能为 6 - 32 位数字，字母及常用符号组成');
                return;
            }

            allStatus.pwd = 1;
            // error.pwdError.html(CORRECT);
            point.pwdPointTip.hide();
            point.pwdPointIcon.html(CORRECT);
        });

        // 确认密码格式验证
        loginInput.loginPwd2.blur(function() {
            var pwd = $.trim($(loginInput.loginPwd).val());
            var value = $.trim($(this).val());

            if (!value) {
                allStatus.pwd = 0;
                return;
            }

            //检测两次密码是否一致
            if (value != pwd) {
                allStatus.pwd = 0;
                $(this).parent().addClass('current');
                error.pwd2Error.html('两次输入的密码不一致 ');
                return;
            }

            allStatus.pwd = 1;
            // error.pwdError.html(CORRECT);
            point.pwd2PointTip.hide();
            point.pwd2PointIcon.html(CORRECT);
        });





        // 检查快速注册
        $('.regist .login-fastlogin').click(util.debounce(function(e) {
            e.preventDefault();

            var status = 1;

            if (!$('#tiaoyue-itp')[0].checked) {
                alert('请同意用户条约');
                return;
            }

            for (var name in allStatus) {
                if (allStatus.hasOwnProperty(name)) {
                    if (!allStatus[name]) {
                        loginInput[mapInput[name]].trigger('blur');
                        status = 0;
                    }
                }
            }

            status && registSubmit.remote({
                name: loginInput.loginUser.val(),
                passwd: loginInput.loginPwd.val(),
                phone: loginInput.loginPhone.val(),
                inviter: loginInput.loginTuiJian.val(),
                vericode: loginInput.loginTest.val(),
                isthird: isthird
            });

        }, 1000));

    }

    function callBack() {

        var timer;

        // checkNameCb
        checkName.on('success', function(data) {
            if (data && data.bizError) {
                loginInput.loginUser.parent().addClass('current');
                error.userError.html(data.statusInfo);
                allStatus.user = 0;
            } else {
                // error.userError.html(CORRECT);
                point.userPointTip.hide();
                point.userPointIcon.html(CORRECT);
                allStatus.user = 1;
            }
        });

        // checkphoneCb
        checkphone.on('success', function(data) {
            if (data && data.bizError) {
                loginInput.loginPhone.parent().addClass('current');
                error.phoneError.html(data.statusInfo);
                allStatus.phone = 0;
                $('.login-username-testing').addClass('disabled');
            } else {
                //error.phoneError.html(CORRECT);
                point.phonePointTip.hide();
                point.phonePointIcon.html(CORRECT);
                allStatus.phone = 1;
                $('.login-username-testing').removeClass('disabled');
            }
        });

        // checkInviterCb
        checkInviter.on('success', function(data) {
            if (data && data.bizError) {
                loginInput.loginTuiJian.parent().addClass('current');
                error.tuiJianError.html(data.statusInfo);
                allStatus.tui = 0;
            } else {
                // error.tuiJianError.html(CORRECT);
                point.tuiJianPointTip.hide();
                point.tuiJianPoint.html(CORRECT);
                allStatus.tui = 1;
            }
        });

        // sendsmscodeCb
        sendsmscode.on('success', function(data) {
            var value = 60;
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                var wait = $('#testing-wait');

                var testing = $(".login-username-testing");
                var testTip = $(".testTip");

                wait.text('60秒后重新获取验证码');
                wait.addClass('show');

                testing.addClass("login-username-testing-wait");
                testing.addClass("disabled");
                testTip.hide();

                timer = setInterval(function() {

                    wait.text(--value + '秒后重新获取验证码');
                    if (value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                        testing.removeClass("disabled");
                        testing.removeClass("login-username-testing-wait");
                    }

                }, 1000);
            }
        });

        checksmscode.on('success', function(data) {
            if (data && data.bizError) {
                loginInput.loginTest.parent().addClass('current');
                error.testError.html(data.statusInfo);
                allStatus.vericode = 0;
            } else {
                //error.testError.html(CORRECT);
                point.testPointTip.hide();
                point.testPointIcon.html(CORRECT);
                allStatus.vericode = 1;
            }
        });

        registSubmit.on('success', function(data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                window.location.href = '/user/open/index';
            }
        });



        // 点击快速绑定 出浮层
        $('.fix-box-register').click(function() {

            dialog.show({
                width: 500,
                defaultTitle: false,
                content: etpl.render('fixBox')
            });

            login.init('registBind');
        });
    }

    /**
     *验证码效果
     */
    function yazma() {
        var e = $(".login-username-testing").not("disabled");
        e.on("mouseenter", function() {
            if (!e.hasClass("disabled")) {
                $(".testTip").show(200);
            }
        }).on("mouseleave", function() {
            if (!e.hasClass("disabled")) {
                $(".testTip").hide(200);
            }
        });
    }
    return {
        init: init
    };
});
