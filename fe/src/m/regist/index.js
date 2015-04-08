/**
 * @ignore
 * @deprecated 
 * @file index.js
 * @author fanyy 
 * @time 2015-04-06
 */

define(function(require) {

    var $ = require('jquery');
    var util = require('common/util');

    var Remoter = require('common/Remoter');
    var checkphone = new Remoter('REGIST_CHECKPHONE_CHECK');
    var sendsmscode = new Remoter('REGIST_SENDSMSCODE_CHECK');
    var checkInviter = new Remoter('REGIST_CHECKINVITER_CHECK');
    var checksmscode = new Remoter('REGIST_CHECKSMSCODE_CHECK');
    var registSubmit = new Remoter('REGIST_INDEX_CHECK');

    var testPwd = /^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/;

    /**
     * input集合
     * @type {Object}
     */
    var loginInput = {
        loginPwd: $('#regist-pwd'),
        loginPwd2: $('#regist-pwd2'),
        loginPhone: $('#regist-phone'),
        loginTest: $('#regist-testing'),
        loginTuiJian: $('#regist-tuijian')
    };

    /**
     * error集合
     * @type {Object}
     */
    var error = {
        phoneError: $('.regist-phone-error'),
        testError: $('.regist-phone-error'),
        pwdError: $('.regist-pwd-error'),
        pwd2Error: $('.regist-pwd-error'),
        tuiJianError: $('.regist-tuijian-error')
    };

    var allStatus = {
        phone: 0,
        pwd: 0, 
        vericode: 0,
        tui: 1
    };

    var mapInput = {
        phone: 'loginPhone',
        pwd: 'loginPwd',
        vericode: 'loginTest',
        tui: 'loginTuiJian'
    };

    var isthird;

    function init(third) {
        isthird = third ? 1 : 0;
        bindEvents();
        callBack();
    }

    function bindEvents() {
        // 控制placeHolder
        $('.regist .input').on({
            focus: function() {
                var parent = $(this).parent().parent();
                var error = parent.next();  
                error.html("\u0020\u0020");                    
            },
            blur: function() { 
                var value = $.trim($(this).val());
                var text = $(this).attr('data-text');
                var id = $(this).attr('id');

                if (!value) {
                    // 不是推荐
                    if (!$(this).hasClass('input-tuijian')) {
                        $('.' + id + '-error').html(text + '不能为空');
                    }
               
                }
            }
        });

        // 密码格式验证
        loginInput.loginPwd.blur(function() {
            var value = $.trim($(this).val());

            if (!value) {
                allStatus.pwd = 0;
                return;
            }

            if (!testPwd.test(value)) {
                allStatus.pwd = 0;
                error.pwdError.html('密码只能为 6 - 32 位数字，字母及常用符号组成');
                return;
            }

            allStatus.pwd = 1; 
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
                error.pwd2Error.html('两次输入的密码不一致 ');
                return;
            }

            allStatus.pwd = 1; 
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
                $('.regist-testing-btn').addClass('disabled');
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
        $('.regist-testing-btn').click(util.debounce(function(e) {
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

        // checkphoneCb
        checkphone.on('success', function(data) {
            if (data && data.bizError) {
                error.phoneError.html(data.statusInfo);
                allStatus.phone = 0;
                $('.regist-testing-btn').addClass('disabled');
            } else { 
                allStatus.phone = 1;
                $('.regist-testing-btn').removeClass('disabled');
            }
        });

        // checkInviterCb
        checkInviter.on('success', function(data) {
            if (data && data.bizError) {
                error.tuiJianError.html(data.statusInfo);
                allStatus.tui = 0;
            } else { 
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
                var test =$("#");
                wait.text('60秒后重新获取');
                wait.addClass('show');

                timer = setInterval(function() {

                    wait.text(--value + '秒后重新获取');
                    if (value < 0) {
                        clearInterval(timer);
                        wait.removeClass('show');
                    }

                }, 1000);
            }
        });

        checksmscode.on('success', function(data) {
            if (data && data.bizError) {
                error.testError.html(data.statusInfo);
                allStatus.vericode = 0;
            } else { 
                allStatus.vericode = 1;
            }
        });

        registSubmit.on('success', function(data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                window.location.href = '/m/open/index';
            }
        });


    }
        return {
            init: init
        };
    
});
