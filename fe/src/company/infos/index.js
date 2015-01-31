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

    var type;

    var pager;

    function init(opt) {
        var container = $('#infos-list');
        type = opt.type;

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
                page: +e.value,
                type: type
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

                if (!data.list.length) {
                    if (type === 'post') {
                        container.html(etpl.render('Error', {
                            msg: '当前没有公告'
                        }));
                    }
                    else {
                        container.html(etpl.render('Error', {
                            msg: '当前没有媒体信息'
                        }));
                    }
                    $('#pager').html('');
                    return;
                }

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
