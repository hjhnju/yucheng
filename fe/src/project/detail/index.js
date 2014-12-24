/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var confirm = new Remoter('INVEST_DETAIL_CONFIRM');





    function init() {

        bindEvent();





    }
     function bindEvent() {
         var valBox = +$('#money-all').text();
         var valIpt = $('.right-top-ipt-input');
         var valMy = +$('#my-money').text();


         $('.right-top-btn-confirm').click(function () {
             confirm

         });
     }







    return {
        init:init
    };
});
