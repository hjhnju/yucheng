/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var getList = new Remoter('ACCOUNT_CASH_LIST');
    var etpl = require('etpl');
    var tpl = require('./operation.tpl');
    var moment = require('moment');
    var Pager = require('common/ui/Pager/Pager');
    var pager;

    var option = {
        'type': 0,
        'data': 0,
        'page': 1,
        'pageall': 10
    };


    function init() {
        etpl.compile(tpl);
        bindEvent();

        getList.remote();

    }

    function bindEvent() {

        // 点击日期筛选
        $('.time-data-type-link').click(function () {
            $('.time-data-type-link').removeClass('current');
            $(this).addClass('current');

            option.type = +$(this).attr('data-value');
            option.page = 1;
            getList.remote('post', option);
        });

        // startCb
        getList.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                $('.operation-list').html(etpl.render('typeList', {
                    list: data.list
                }));
            }
        });
    }


    return {
        init:init
    };
});
