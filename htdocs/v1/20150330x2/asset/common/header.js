define('common/header', [
    'require',
    'jquery',
    'etpl',
    './common.tpl'
], function (require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var tpl = require('./common.tpl');
    function init() {
        etpl.compile(tpl);
        footer();
        easy();
        loginRegister();
    }
    function easy() {
        var timer;
        var item;
        $('.attention-me').mouseenter(function () {
            if (item && item !== $(this)) {
                $('.xinlang-erweima').removeClass('current');
            }
            clearTimeout(timer);
            item = $(this);
            $(this).children('.xinlang-erweima').addClass('current');
        }).mouseleave(function () {
            timer = setTimeout(function () {
                $('.attention-me').children('.xinlang-erweima').removeClass('current');
            }, 0);
        });
    }
    function footer() {
        var timer;
        var item;
        $('.footer-site-me-xinlang').mouseenter(function () {
            if (item && item !== $(this)) {
                $('.xinlang-erweima').removeClass('current');
            }
            clearTimeout(timer);
            item = $(this);
            $(this).children('.xinlang-erweima').addClass('current');
        }).mouseleave(function () {
            timer = setTimeout(function () {
                $('.footer-site-me-xinlang').children('.xinlang-erweima').removeClass('current');
            }, 0);
        });
    }
    function loginRegister() {
        var e = $('.login-register');
        e.on('mouseenter', 'a', function () {
            $(this).hasClass('login') ? e.removeClass('register-hover').addClass('login-hover') : e.removeClass('login-hover').addClass('register-hover');
        }).on('mouseleave', 'a', function () {
            e.hasClass('register-hover') || e.hasClass('login-hover') || e.addClass('login-hover');
        });
    }
    function scrollUp() {
    }
    return { init: init };
});