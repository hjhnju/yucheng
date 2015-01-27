/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    // var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var getList = new Remoter('ACCOUNT_CASH_LIST');
    var etpl = require('etpl');
    var tpl = require('./operation.tpl');
    var moment = require('moment');
    var Pager = require('common/ui/Pager/Pager');
    var pager;
    var header = require('common/header');

    var htmlContainer = $('.operation-list');

    var option = {
        'type': 0,
        'data': 0,
        'page': 1,
        'pagesize': 10
    };

    /**
     * 时间选择
     * @type {Object}
     */
    var selectDate = {
        stime: 0,
        etime: 0
    };

    function init(opt) {
        header.init();

        option.pagesize = +opt.pagesize;
        etpl.compile(tpl);
        bindEvent();
        getList.remote(option);

        pager = new Pager({
            total: +opt.pageall,
            main: $('#page'),
            startPage: 1
        });
        pager.render(+opt.page);

        pager.on('change', function (data) {
            option.page = data.value;
            htmlContainer.html(etpl.render('Loading'));
            getList.remote(option);
        });

    }

    function bindEvent() {

        // 点击日期筛选
        $('#operation-data').delegate('.time-data-type-link', 'click', function () {
            $('#operation-data .time-data-type-link').removeClass('current');
            $(this).addClass('current');

            $('#time-start, #time-end').val('');
            selectDate.stime = 0;
            selectDate.etime = 0;

            option.data = +$(this).attr('data-value');
            option.page = 1;
            htmlContainer.html(etpl.render('Loading'));
            getList.remote('post', option);
        });

        // 点击种类筛选
        $('#operation-type').delegate('.time-data-type-link', 'click', function () {
            $('#operation-type .time-data-type-link').removeClass('current');
            $(this).addClass('current');

            option.type = +$(this).attr('data-value');
            option.page = 1;
            htmlContainer.html(etpl.render('Loading'));
            getList.remote('post', option);
        });

        // 开始时间选择
        $('#time-start').datepicker({
            format: 'yyyy-mm-dd'
        }).on('changeDate', function (e) {
            if (selectDate.etime && e.date.getTime() > selectDate.etime) {
                alert('开始时间不得大于结束时间');
                $(this).val('');
            }
            else {
                selectDate.stime = e.date.getTime();
            }
        });

        $('#time-end').datepicker({
            format: 'yyyy-mm-dd'
        }).on('changeDate', function (e) {
            if (e.date.getTime() < selectDate.stime) {
                alert('结束时间必须大于开始时间');
                $(this).val('');
            }
            else {
                selectDate.etime = e.date.getTime();
            }
        });

        // 搜索
        $('.time-data-search').click(function () {
            if (selectDate.stime && selectDate.etime) {
                $('#operation-data .time-data-type-link').removeClass('current');
                option.page = 1;
                htmlContainer.html(etpl.render('Loading'));
                getList.remote($.extend({}, option, {
                    startTime: selectDate.stime / 1000,
                    endTime: selectDate.etime / 1000
                }));
            }
            else {
                alert('请选择开始时间和结束时间');
            }
        });

        // startCb
        getList.on('success', function (data) {
            if(data && data.bizError) {

                htmlContainer.html(etpl.render('Error', {
                    msg: data.statusInfo
                }));
            }
            else {
                if (!data.list.length) {
                    $('.operation-list').html(etpl.render('Error', {
                        msg: '当前还没有数据哟'
                    }));
                    $('#page').html('');
                    return;
                }

                pager.setOpt('total', +data.pageall);
                pager.render(+data.page);

                htmlContainer.html(etpl.render('typeList', {
                    list: data.list
                }));
            }
        });

        // startCb
        getList.on('fail', function (msg) {

            htmlContainer.html(etpl.render('Error', {
                msg: msg
            }));

        });
    }


    return {
        init:init
    };
});
