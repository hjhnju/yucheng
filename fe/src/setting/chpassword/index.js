/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var Remoter = require('common/Remoter');

    function init (){
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

                if(!value) {
                    me.addClass('current');
                    $(this).next().html('密码不能为空');

                    return;
                }
                $(this).next().html('');

            }
        })
    }

    return {
        init:init
    };
});

