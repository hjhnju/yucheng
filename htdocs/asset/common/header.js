define('common/header', function (require) {
    var $ = require('jquery');
    function init() {
        easy();
    }
    function easy() {
        var timer;
        $('.attention-me').mouseenter(function () {
            clearTimeout(timer);
            $(this).children($('.xinlang-erweima')).addClass('current');
        });
        $('.attention-me').mouseleave(function () {
            timer = setTimeout(function () {
                $('.attention-me').children($('.xinlang-erweima')).removeClass('current');
            }, 100);
        });
    }
    return { init: init };
});