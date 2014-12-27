/*! 2014 Baidu Inc. All Rights Reserved */
define('zrender/tool/event', [
    'require',
    '../mixin/Eventful'
], function (require) {
    'use strict';
    var Eventful = require('../mixin/Eventful');
    function getX(e) {
        return typeof e.zrenderX != 'undefined' && e.zrenderX || typeof e.offsetX != 'undefined' && e.offsetX || typeof e.layerX != 'undefined' && e.layerX || typeof e.clientX != 'undefined' && e.clientX;
    }
    function getY(e) {
        return typeof e.zrenderY != 'undefined' && e.zrenderY || typeof e.offsetY != 'undefined' && e.offsetY || typeof e.layerY != 'undefined' && e.layerY || typeof e.clientY != 'undefined' && e.clientY;
    }
    function getDelta(e) {
        return typeof e.zrenderDelta != 'undefined' && e.zrenderDelta || typeof e.wheelDelta != 'undefined' && e.wheelDelta || typeof e.detail != 'undefined' && -e.detail;
    }
    var stop = typeof window.addEventListener === 'function' ? function (e) {
            e.preventDefault();
            e.stopPropagation();
            e.cancelBubble = true;
        } : function (e) {
            e.returnValue = false;
            e.cancelBubble = true;
        };
    return {
        getX: getX,
        getY: getY,
        getDelta: getDelta,
        stop: stop,
        Dispatcher: Eventful
    };
});