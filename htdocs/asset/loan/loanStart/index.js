define('loan/loanstart/index', [
    'require',
    'jquery',
    'common/header'
], function (require) {
    var $ = require('jquery');
    var header = require('common/header');
    function init() {
        header.init();
        $('.nav-item-link').removeClass('current');
        $('.nav-item-link:eq(1)').addClass('current');
    }
    return { init: init };
});