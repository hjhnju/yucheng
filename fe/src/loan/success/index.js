/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var config = require('common/config');
    var header = require('common/header');
    var etpl = require('etpl');
    var tpl = require('./success.tpl');

    function init (){
        changeEmail();
        header.init();
        etpl.compile(tpl);
        $('#success-id').html(etpl.render('Loan'));
    }

    function changeEmail() {


        var timer;
        var value = 6;


        timer = setInterval(function () {

            $('#time-span').text(--value + '秒后自动跳转');
            if(value === 0) {
                clearInterval(timer);
                window.location.href = '/index/index';
            }

        },1000);



    }
    return {init:init};
});

