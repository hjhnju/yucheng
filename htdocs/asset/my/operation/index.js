define('my/operation/index', function (require) {
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
            'pagesize': 10
        };
    function init(opt) {
        option.pagesize = +opt.pagesize;
        etpl.compile(tpl);
        bindEvent();
        getList.remote();
        pager = new Pager({
            total: +opt.pageall,
            main: $('#page'),
            startPage: 1
        });
        pager.render(+opt.page);
        pager.on('change', function (data) {
            option.page = data.value;
            getList.remote(option);
        });
    }
    function bindEvent() {
        $('#operation-data').delegate('.time-data-type-link', 'click', function () {
            $('#operation-data .time-data-type-link').removeClass('current');
            $(this).addClass('current');
            option.data = +$(this).attr('data-value');
            option.page = 1;
            getList.remote('post', option);
        });
        $('#operation-type').delegate('.time-data-type-link', 'click', function () {
            $('#operation-type .time-data-type-link').removeClass('current');
            $(this).addClass('current');
            option.type = +$(this).attr('data-value');
            option.page = 1;
            getList.remote('post', option);
        });
        getList.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                pager.setOpt('total', +data.pageall);
                pager.render(+data.page);
                $('.operation-list').html(etpl.render('typeList', { list: data.list }));
            }
        });
    }
    return { init: init };
});