define('my/account/index', [
    'require',
    'jquery',
    'etpl',
    './line',
    'common/Remoter'
], function (require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var line = require('./line');
    var Remoter = require('common/Remoter');
    var getLine = new Remoter('LINE_GET');
    function init(status) {
        var container = $('#all-account-line');
        if (+status === 1) {
            getLine.remote();
        }
        getLine.on('success', function (data) {
            if (data.bizError) {
                container.html(etpl.render('Error', data.statusInfo));
            } else {
                line.render('all-account-line', data);
            }
        });
    }
    return { init: init };
});