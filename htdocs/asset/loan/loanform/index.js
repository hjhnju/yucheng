define('loan/loanform/index', [
    'require',
    'jquery',
    'common/Remoter'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var getLine = new Remoter('LINE_GET');
    function init() {
    }
    return { init: init };
});