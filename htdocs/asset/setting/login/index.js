define('setting/login/index', function (require) {
    var $ = require('jquery');
    var picScroll = require('../common/picScroll');
    var Remoter = require('common/Remoter');
    var loginCheck = new Remoter('LOGIN_INDEX_CHECK');
    var imgcodeCheck = new Remoter('LOGIN_IMGCODE_CHECK');
    var imgcodeGet = new Remoter('LOGIN_IMGCODE_ADD');
    var loginError = $('#login-error');
    var imgUrl = $('#login-img-url');
    function init() {
        picScroll.init();
        bindEvents();
        bindCallback();
    }
    function bindEvents() {
        $('.login .login-input').on({
            focus: function () {
                var parent = $(this).parent();
                var error = parent.children('.username-error');
                parent.removeClass('current');
                error.html('');
                $(this).next().addClass('hidden');
            },
            blur: function () {
                var value = $.trim($(this).val());
                !value && $(this).next().removeClass('hidden');
            }
        });
        $('#login-testing').blur(function () {
            var value = $.trim($(this).val());
            if (!value) {
                $(this).parent().addClass('current');
                $('#login-testing-error').html('\u9A8C\u8BC1\u7801\u4E0D\u80FD\u4E3A\u7A7A');
                return;
            }
            imgcodeCheck.remove({ imgcode: value });
        });
        imgUrl.click(function (e) {
            e.preventDefault();
            imgcodeGet.remote();
        });
        $('.login .login-fastlogin').click(function (e) {
            e.preventDefault();
            var user = $.trim($('#login-user').val());
            var pwd = $.trim($('#login-pwd').val());
            if (!user || !pwd) {
                loginError.html('\u7528\u6237\u540D\u6216\u5BC6\u7801\u4E0D\u80FD\u4E3A\u7A7A');
            } else {
                loginCheck.remote({
                    name: user,
                    passwd: pwd
                });
            }
        });
    }
    function bindCallback() {
        loginCheck.on('success', function (data) {
            if (data.imgCode) {
                var imgBox = $('.login-display-none:eq(0)');
                imgBox.removeClass('login-display-none');
                imgUrl.attr('src', data.data.url);
            } else if (data.bizError) {
                $('#login-error').html(data.statusInfo);
            } else {
                window.location.href = 'http://www.baidu.com';
            }
        });
        imgcodeGet.on('success', function (data) {
            if (data.bizError) {
                alert(data.statusInfo);
            } else {
                imgUrl.attr('src', data.url);
            }
        });
        imgcodeCheck.on('success', function (data) {
            if (data.bizError) {
                alert(data.statusInfo);
            } else {
                $('#login-testing-error').html('<span class="username-error-span"></span>');
            }
        });
    }
    return { init: init };
});