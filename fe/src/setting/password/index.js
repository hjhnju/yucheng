/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-13
 */

define(function (require) {

    var $ = require('jquery');
    var a = $('.login-username').children('.user-lable');
    var Remoter = require('common/Remoter');
    var submit = new Remoter('EDIT_CHANGEPWD_SUBMIT')

    function init (){
        $('.login-input').on({

            focus: function(){
                $(this).parent().children('.user-lable').addClass('hidden');
            },
            blur: function(){
                var val = $(this).val();
                !val && a.hasClass('hidden') && a.removeClass('hidden');
            }
        });

        $('.login-fastlogin').click(function () {
            submit.remote({

            })
        })

    }

    return {init:init};
});
