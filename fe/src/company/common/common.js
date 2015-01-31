/**
 * @ignore
 * @file common
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 15-1-3
 */

define(function (require) {
    var $ = require('jquery');
    var header = require('common/header');

    function init() {
        header.init();
        //$('.nav-item-link:eq(4)').addClass('current');
    }

    return {
        init: init
    };
});
