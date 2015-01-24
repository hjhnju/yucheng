/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var line = require('./line');
    var header = require('common/header');
    var Remoter = require('common/Remoter');
    var getLine = new Remoter('LINE_GET');

    function init(status) {
        header.init();

        var container = $('#all-account-line');
        if (+status === 1) {
            getLine.remote();
        }

        getLine.on('success', function (data) {
            if (data.bizError) {
                container.html(etpl.render('Error', {
                    msg: data.statusInfo
                }));
            }
            else {
                line.render('all-account-line', data);
            }
        });
    }

    return {
        init: init
    };
});
