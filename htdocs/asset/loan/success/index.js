define('loan/success/index', [
    'require',
    'jquery',
    'common/Remoter',
    'common/config',
    'common/header',
    'etpl',
    './success.tpl'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var config = require('common/config');
    var header = require('common/header');
    var etpl = require('etpl');
    var tpl = require('./success.tpl');
    function init() {
        changeEmail();
        header.init();
        etpl.compile(tpl);
        $('#success-id').html(etpl.render('Loan'));
    }
    function changeEmail() {
        var timer;
        var value = 6;
        timer = setInterval(function () {
            $('#time-span').text(--value + '\u79D2\u540E\u81EA\u52A8\u8DF3\u8F6C');
            if (value === 0) {
                clearInterval(timer);
                window.location.href = '/index/index';
            }
        }, 1000);
    }
    return { init: init };
});