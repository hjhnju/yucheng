define('project/detail/index', function (require) {
    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var start = new Remoter('INVEST_DETAIL_START');
    var etpl = require('etpl');
    var tpl = require('./detail.tpl');
    var moment = require('moment');
    var Pager = require('common/ui/Pager/Pager');
    var pager;
    function init(id) {
        etpl.compile(tpl);
        bindEvent();
        start.remote({
            page: 1,
            id: id
        });
        start.on('success', function (data) {
            if (data && data.bizError) {
                alert(data.statusInfo);
            } else {
                if (!pager) {
                    pager = new Pager({
                        total: +data.pageall,
                        main: $('#page'),
                        startPage: 1
                    });
                    pager.on('change', function (e) {
                        start.remote({
                            page: e.value,
                            id: id
                        });
                    });
                }
                pager.render(+data.page);
                for (var i = 0, l = data.list.length; i < l; i++) {
                    var tmp = data.list[i];
                    tmp.timeInfo = moment.unix(+tmp.create_time).format('YYYY-MM-DD hh:mm:ss');
                }
                $('#toulist').html(etpl.render('list', { list: data.list }));
            }
        });
    }
    function bindEvent() {
        $('.showproject').click(function () {
            $(this).closest('.project-main').attr('class', 'project-main project');
        });
        $('.showfile').click(function () {
            $(this).closest('.project-main').attr('class', 'project-main file');
        });
        $('.showrecord').click(function () {
            $(this).closest('.project-main').attr('class', 'project-main record');
        });
    }
    return { init: init };
});