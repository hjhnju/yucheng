/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var getList = new Remoter('INVEST_LIST');
    var etpl = require('etpl');
    var tpl = require('./list.tpl');
    var Pager = require('common/ui/Pager/Pager');
    var header = require('common/header');
    var pager;
    var type;

    var htmlContainer;


    var option = {
        'type_id': 0,
        'cat_id': 0,
        'duration': 0,
        'page': 1,
        'pagesize': 10
    };

    function init(opt) {

        htmlContainer = $('#invest-main');

        $('.nav-item-link:eq(0)').addClass('current');


        header.init();

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

        //标的类型
        $('.type_id').click(function () {
            $('.type_id').removeClass('current');
            $(this).addClass('current');
            option.type_id = +$(this).attr('data-value');
            option.page = 1;

            htmlContainer.html(etpl.render('Loading'));

            getList.remote('post',option);
        });

        //getListCb
        getList.on('success', function (data) {
            if(data && data.bizError) {
                htmlContainer.html(etpl.render('Error'), {
                    msg: data.statusInfo
                });
            }
            else {

                if (!data.list.length) {
                    $('#test2').html('');
                    htmlContainer.html(etpl.render('Error', {
                        msg: '当前还没有数据哟'
                    }));
                    return;
                }

                pager.setOpt('total', +data.pageall);
                pager.render(+data.page);

                htmlContainer.html(etpl.render('list',{

                    list: data.list
                }));

            }
        });

        //借款类型
        $('.cat_id').click(function () {
            $('.cat_id').removeClass('current');
            $(this).addClass('current');
            option.cat_id = +$(this).attr('data-value');
            option.page = 1;

            htmlContainer.html(etpl.render('Loading'));
            getList.remote('post',option);
        });

        //借款期限
        $('.qixian').click(function () {
            $('.qixian').removeClass('current');
            $(this).addClass('current');
            option.duration = +$(this).attr('data-value');

            htmlContainer.html(etpl.render('Loading'));
            getList.remote('post',option);
        });

    }

    return {
        init:init
    };
});
