define('common/header', function (require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var tpl = require('./common.tpl');
    function init() {
        etpl.compile(tpl);
        footer();
        easy();
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
            }, 100);
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
            }, 100);
        });
    }
    return { init: init };
});