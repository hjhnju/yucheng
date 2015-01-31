define('setting/success/index', function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var config = require('common/config');
    var header = require('common/header');
    function init() {
        changeEmail();
        header.init();
    }
    function changeEmail() {
        var timer;
        var value = 6;
        timer = setInterval(function () {
            $('#time-span').text(--value + '\u79D2\u540E\u81EA\u52A8\u8DF3\u8F6C');
            if (value === 0) {
                clearInterval(timer);
                window.location.href = '/account/overview/index';
            }
        }, 1000);
    }
    return { init: init };
});