/*! 2014 Baidu Inc. All Rights Reserved */
define('setting/chpassword/index', [
    'require',
    'jquery',
    'common/Remoter'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    function init() {
        bingEvent();
    }
    function bingEvent() {
        $('.chpwd-input').on({
            focus: function () {
                $(this).removeClass('current');
                $(this).next().html('');
            },
            blur: function () {
                var me = $(this);
                var value = $(this).val();
                if (!value) {
                    me.addClass('current');
                    $(this).next().html('\u5BC6\u7801\u4E0D\u80FD\u4E3A\u7A7A');
                    return;
                }
                $(this).next().html('');
            }
        });
    }
    return { init: init };
});