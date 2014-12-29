/*! 2014 Baidu Inc. All Rights Reserved */
define('setting/chpassword/index', [
    'require',
    'jquery',
    'common/Remoter',
    'etpl',
    './chpassword.tpl'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var chpwdSubmite = new Remoter('EDIT_CHPWD_SUBMITE');
    var testPwd = /^[a-zA-Z0-9!@#$%^&'\(\({}=+\-]{6,20}$/;
    var etpl = require('etpl');
    var tpl = require('./chpassword.tpl');
    function init() {
        bingEvent();
        etpl.compile(tpl);
    }
    function bingEvent() {
        $('.chpwd-link').click(function () {
            var newIpt = $('#new-ipt');
            var newval = +newIpt.val();
            var me = +$('#confirm-new-ipt').val();
            if (newval !== me) {
                $('#error').html('\u4E24\u6B21\u5BC6\u7801\u4E0D\u4E00\u81F4');
                return;
            }
            chpwdSubmite.remote({
                oldpwd: $('#old-ipt').val(),
                newpwd: newIpt.val()
            });
        });
        chpwdSubmite.on('success', function (data) {
            if (data && data.bizError) {
                $('#error').html(data.statusInfo);
            } else {
                var value = 8;
                var timer;
                timer = setInterval(function () {
                    $('#time-span').text(--value + '\u79D2\u540E\u81EA\u52A8\u8DF3\u8F6C');
                    if (value === 0) {
                        clearInterval(timer);
                        window.location.href = '/account/views/overview/index';
                    }
                }, 1000);
                $('#error').html('');
                $('#chpwd-box').html(etpl.render('list'));
            }
        });
    }
    return { init: init };
});