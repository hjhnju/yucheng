/**
 * @ignore
 * @file index.js
 * @author fanyy 
 * @time 15-4-6
 */

define(function (require) {

    var $ = require('jquery');
    var header = require('common/header');

    function init() {
        header.init(); 
    }
 
    return {
        init:init
    };
});
