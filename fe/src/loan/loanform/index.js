/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var loanSubmit = new Remoter('LOAN_REQUEST');

    function init() {
        bindEvent();

    }

    function bindEvent() {
        var ipt = $('.loan-form-right-ipt');
        var value = ipt.val();
        var error = $('.form-error');


        ipt.on({

            focus: function () {
                error.html('');
            },
            blur: function () {
                var meVal = $(this).val();
                var text = $(this).parent().prev().text();

                if(!meVal) {
                    error.html(text + '内容不能为空')
                }
            }
        });



        $('.form-con-right-link').click(function () {
            if(!value) {
                error.html('带*的选项内容不能为空')
                return;
            }
            loanSubmit.remote({

            });
        });
    }

    return {
        init: init
    };
});
