/*! 2014 Baidu Inc. All Rights Reserved */
define('setting/common/picScroll', function (require) {
    var $ = require('jquery');
    function init() {
        picScroll();
    }
    function picScroll() {
        var oUl = $('.login-item');
        var aLi = $('.login-item-list');
        var aLiWidth = aLi.eq(0).width();
        var left = $('.login-right-linkleft.left');
        var right = $('.login-right-linkleft.right');
        var oUlWith = aLi.eq(0).width() * aLi.size();
        var now = 0;
        var length = 3;
        oUl.css('width', oUlWith + 'px');
        left.click(function () {
            now = (now - 1 + length) % length;
            oUl.stop(true).animate({ 'left': -aLiWidth * now + 'px' }, 500);
        });
        right.click(function () {
            now = (now + 1) % length;
            oUl.stop(true).animate({ 'left': -aLiWidth * now + 'px' }, 500);
        });
    }
    return { init: init };
});