/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function (require) {

    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var checkName = new Remoter('REGIST_CHECKNAME_EDIT');
    var checkphone = new Remoter('REGIST_CHECKPHONE_EDIT');
    var sendsmscode = new Remoter('REGIST_SENDSMSCODE_EDIT');
    var getVericode = new Remoter('REGIST_GETVERICODE');
    var checkInviter = new Remoter('REGIST_CHECKINVITER');
    var Index = new Remoter('REGIST_INDEX');

    var loginInput = {
        loginUser: $('#login-user'),
        loginPwd: $('#login-pwd'),
        loginPhone: $('#login-phone'),
        loginTest: $('#login-testing'),
        loginTuiJian: $('#login-login-tuijian')
    }

    var error = {
        userError: $('#login-user-error'),
        phoneError: $('#login-phone-error'),
        pwdError: $('#login-pwd-error'),
        testError: $('#login-testing-error'),
        tuiJian: $('#login-tuijian-error')
    };

    function init() {
        bindEvents();
        callBack();
    }

    function bindEvents() {
        // 控制placeHolder
        $('.login-input').on({
            focus: function () {
                var parent = $(this).parent();
                var Error = parent.children('.username-error');

                parent.removeClass('current');
                Error.html('');
                $(this).next().addClass('hidden');
            },
            blur: function () {

                var value = $.trim($(this).val());

                !value && $(this).next().removeClass('hidden');

            }
        });

        // 检查用户名
        $('#login-user').blur(function () {
            var value = $.trim($(this).val());
            if (value) {
                checkName.remote({
                    name: value
                });
            }
            else {
                error.userError.html('用户名不正确');
                $(this).parent().addClass('current');
            }
        });


        //检查手机号
        $('#login-phone').blur(function () {
            var value = $.trim($(this).val());

            if(value) {

                checkphone.remote({
                    phone: value
                });
            }
            else {
                $(this).parent().addClass('current');
                error.phoneError.html('手机号不存在');
            }
        });


        //检查验证码
        $('#login-testing').blur(function (data) {
            var value = $.trim($(this).val());

            if (value) {
                sendsmscode.remote({
                    ricode: value
                });
            }
            else {
                error.testError.html('验证码不能为空');
                $(this).parent().addClass('current');
            }
        })

        //检查是否获取验证码   这里没写完呢吧回家问老婆
        $('.login-username-testing').click(function () {

            getVericode.remote('post',{

            })
        });

        //检查推荐人
        $('#login-tuijian').blur(function (data) {
            var value = $.trim($(this).val());

            if(value) {
                checkInviter.remote({
                    inviter:value
                });
            }
            else {

            }
        });

        //检查快速注册
        $('.login-fastlogin').click(function () {
            var errors = $('.login-username.current');
            var status = false;

            if (errors.length) {
                alert('不成功');
                return;  //跳出click方法，不往下执行
            }

            $('.login-input').each(function () {

                if (!$.trim($(this).val()) && !$(this).hasClass('login-tuijian')) {
                    status = true;
                    return;
                }
            });

            if (status) {
                alert('不成功');
                return;
            }

            Index.remote({
                name: loginInput.loginUser.val(),
                passwd: loginInput.loginPwd.val(),
                phone: loginInput.loginPhone.val(),
                inviter: loginInput.loginTuiJian.val()
            });

        });

    }

    function callBack() {

        var timer;

        // checkNameCb
        checkName.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                error.userError.append('<span class="username-error-span"></span>');
            }
        });

        //checkphoneCb
        checkphone.on('success',function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                error.phoneError.append('<span class="username-error-span"></span>');
                //alert('手机可用')
            }
        });

        //sendsmscodeCb
        sendsmscode.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo)
            }
            else {
               //alert('你成功了');
                error.testError.append('<span class="username-error-span"></span>');
            }
        });

        //checkInviterCb
        checkInviter.on('success',function (data) {
           if(data && data.bizError) {
               alert(data.statusInfo);
           }
            else {

           }
        });

        //getVericodeCb
        getVericode.on('success',function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                var value = + $('#testing-wait').text();
                $('#testing-wait').addClass('show');
                timer = setInterval(function () {

                    //console.log(value);

                    $('#testing-wait').text(value--);
                    if(value < 0) {
                        clearInterval(timer);
                        $('#testing-wait').removeClass('show');
                        $('#testing-wait').text('10');
                    }

                },1000);


            }
        });



    }
    return {
        init: init
    };
});
