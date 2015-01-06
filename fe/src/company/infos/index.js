/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 15-1-3
 */

define(function (require) {

    var $ = require('jquery');
    var etpl = require('etpl');
    var header = require('common/header');
    var Pager = require('common/ui/Pager/Pager');
    var commonData = require('common/data');
    var Remoter = require('common/Remoter');
    var getList = new Remoter('COMPANY_INFOS_LIST');

    var tpl = require('./list.tpl');

    var pager;

    function init(opt) {
        var container = $('#infos-list');

        header.init();
        etpl.compile(tpl);

        // 初始化分页
        pager = new Pager($.extend({}, commonData.pagerOpt, {
            total: +opt.pageall,
            main: $('#pager')
        }));

        // 分页选择
        pager.on('change', function (e) {
            container.html(etpl.render('Loading'));
            getList.remote({
                page: +e.value
            });
        });

        pager.render(+opt.page);

        getList.on('success', function (data) {
            if (data.bizError) {
                container.html(etpl.render('Error', {
                    msg: data.statusInfo
                }));

            }
            else {

                pager.render(+data.page);

                container.html(etpl.render('infosList', {
                    list: data.list
                }));
            }
        })
    }

    return {
        init: init
    };
});
