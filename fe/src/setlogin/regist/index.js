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
    var checkVericode = new Remoter('REGIST_CHECKVERICODE_EDIT');
    var checkReferee = new Remoter('REGIST_CHECKREFEREE_EDIT');
    var getVericode = new Remoter('REGIST_GETVERICODE');
    //var Index = new Remoter('RESET_INDEX');

    function init() {
        bindEvents();
    }

    function bindEvents() {
        var text = $('.login-username.current .username-error').html('内容不能为空');
        console.log(text);
        // 控制placeHolder
        $('.login-input').on({
            focus: function () {
                var error = $(this).parent().children('.username-error');

                $('.login-username').removeClass('current');
                error.html(null);
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
                alert('用户名不能为空');
            }
        });

        // checkNameCb
        checkName.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                alert('用户名可使用');
            }
        });

        //检查手机号
        $('#login-phone').blur (function () {
            var value = $.trim($(this).val());
            var error = $(this).parent().children('.username-error');

            if(value) {

                checkphone.remote({
                    phone: value
                });
            }
            else {
                $(this).parent().addClass('current');
                error.html('手机号不能为空');
            }
        });

        //checkphoneCb
        checkphone.on('success',function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                alert('手机可用')
            }
        });

        //检查验证码
        $('#login-testing').blur(function (data) {
            var value = $.trim($(this).val());
            var error = $(this).parent().children('.username-error');

            if (value) {
                checkVericode.remote({
                    ricode: value
                });
            }
            else {
                error.html('验证码不能为空');
                $(this).parent().addClass('current');
            }
        })

        //checkVericodeCb
        checkVericode.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo)
            }
            else {
                alert('你成功了');
            }
        });

        //检查是否获取验证码   这里没写完呢吧回家问老婆
        $('.login-username-testing').click(function () {

            getVericode.remote('post',{

            })
        });

        //getVericodeCb
        getVericode.on('success',function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                alert("发送成功");
            }
        });



        //检查推荐人
        $('#login-tuijian').blur(function (data) {
            var value = $.trim($(this).val());
            var error = $(this).parent().children('.username-error');

            if(value) {
                checkReferee.remote({
                    referee:value
                });
            }
            else {

            }
        });

        //checkRefereeCb
        checkReferee.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                alert('你成功了');
            }
        });

        //检查快速注册
        $('.login-fastlogin').click(function () {
            var oDiv = $('.login-username');
            if(!oDiv.hasClass('current')) {
                alert('成功');
            }
            else {
                alert(text);
            }
        });


    }
    return {
        init: init
    };
});
