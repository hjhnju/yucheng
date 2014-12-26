/*! 2014 Baidu Inc. All Rights Reserved */
define('my/account/index', [
    'require',
    './line'
], function (require) {
    var line = require('./line');
    function init() {
        line.render('all-account-line', {});
    }
    return { init: init };
});