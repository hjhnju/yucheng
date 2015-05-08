/**
 * @ignore
 * @file index.js  成功或者失败后跳转页面
 * @author fanyy 
 * @time 2015-04-06
 */ 

define(function (require) {

    var $ = require('jquery'); 

    function init (){
        changeEmail(); 
    }

    function changeEmail() { 
        var timer;
        var value = 6;

        timer = setInterval(function () {

            $('countdown').text(--value + '秒后返回');
            if(value === 0) {
                clearInterval(timer);
                window.location.href = '/account/overview/index';
            }

        },1000);



    }
    return {init:init};
});

