define('project/list/index', [
    'require',
    'jquery',
    'common/Remoter',
    'etpl',
    './list.tpl',
    'common/ui/Pager/Pager'
], function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var getList = new Remoter('INVEST_LIST');
    var etpl = require('etpl');
    var tpl = require('./list.tpl');
    var Pager = require('common/ui/Pager/Pager');
    var pager;
    var type;
    var option = {
            'type_id': 0,
            'cat_id': 0,
            'duration': 0,
            'page': 1,
            'pagesize': 10
        };
    function init(opt) {
        option.pagesize = +opt.pagesize;
        etpl.compile(tpl);
        bindEvent();
        pager = new Pager({
            total: +opt.pageall,
            main: $('#test2'),
            startPage: 1
        });
        pager.render(+opt.page);
        pager.on('change', function (data) {
            option.page = data.value;
            getList.remote(option);
        });
    }
    function bindEvent() {
        $('.type_id').click(function () {
            $('.type_id').removeClass('current');
            $(this).addClass('current');
            option.type_id = +$(this).attr('data-value');
            option.page = 1;
            getList.remote('post', option);
        });
        getList.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                pager.setOpt('total', +data.pageall);
                pager.render(+data.page);
                $('#invest-main').html(etpl.render('list', { list: data.list }));
            }
        });
        $('.cat_id').click(function () {
            $('.cat_id').removeClass('current');
            $(this).addClass('current');
            option.cat_id = +$(this).attr('data-value');
            option.page = 1;
            getList.remote('post', option);
        });
        $('.qixian').click(function () {
            $('.qixian').removeClass('current');
            $(this).addClass('current');
            option.duration = +$(this).attr('data-value');
            getList.remote('post', option);
        });
    }
    return { init: init };
});