/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {
    // var $ = require('jquery');
    var line = require('./line');

    function init() {
        line.render('all-account-line', {});
    }

    return {
        init: init
    };
});
