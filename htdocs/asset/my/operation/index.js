define('my/operation/index', function (require) {
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
    var selectDate = {
            stime: 0,
            etime: 0
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
            $('#time-start, #time-end').val('');
            selectDate.stime = 0;
            selectDate.etime = 0;
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
        $('#time-start').datepicker({ format: 'yyyy-mm-dd' }).on('changeDate', function (e) {
            if (selectDate.etime && e.date.getTime() > selectDate.etime) {
                alert('\u5F00\u59CB\u65F6\u95F4\u4E0D\u5F97\u5927\u4E8E\u7ED3\u675F\u65F6\u95F4');
                $(this).val('');
            } else {
                selectDate.stime = e.date.getTime();
            }
        });
        $('#time-end').datepicker({ format: 'yyyy-mm-dd' }).on('changeDate', function (e) {
            if (e.date.getTime() < selectDate.stime) {
                alert('\u7ED3\u675F\u65F6\u95F4\u5FC5\u987B\u5927\u4E8E\u5F00\u59CB\u65F6\u95F4');
                $(this).val('');
            } else {
                selectDate.etime = e.date.getTime();
            }
        });
        $('.time-data-search').click(function () {
            if (selectDate.stime && selectDate.etime) {
                $('#operation-data .time-data-type-link').removeClass('current');
                option.page = 1;
                getList.remote($.extend({}, option, {
                    startTime: selectDate.stime / 1000,
                    endTime: selectDate.etime / 1000
                }));
            } else {
                alert('\u8BF7\u9009\u62E9\u5F00\u59CB\u65F6\u95F4\u548C\u7ED3\u675F\u65F6\u95F4');
            }
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