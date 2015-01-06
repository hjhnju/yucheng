define('loan/loanstart/index', [
    'require',
    'jquery'
], function (require) {
    var $ = require('jquery');
    $('.nav-item-link').removeClass('current');
    $('.nav-item-link:eq(1)').addClass('current');
    return { init: init };
});