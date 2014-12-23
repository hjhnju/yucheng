/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {

    var $ = require('jquery');
    var Remoter = require('common/Remoter');
    var place = new Remoter('LIST_PLACE');
    var broMoney = new Remoter('LIST_BROMONEY');
    var qiXian = new Remoter('LIST_QIXIAN');
    var etpl = require('etpl');
    var tpl = require('./list.tpl');
    var Pager = require('common/ui/Pager/Pager');
    var pager;
    var type;
    pager = new Pager({
        //total: data.pageall,
        main: $('#test2')
    });

    var option = {
        'type_id': 0,
        'cat_id': 0,
        'duration': 0,
        'page': 1,
        'pagesize': 10
    }

    function init() {

        etpl.compile(tpl);

        bindEvent();


    }

    function bindEvent() {

        //标的类型
        $('.type_id').click(function () {
            $('.type_id').removeClass('current');
            $(this).addClass('current');
            option.type_id = +$(this).attr('data-value');

            place.remote('post',option);
        });

        //placeCb
        place.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                if (!pager) {
                    pager = new Pager({
                        total: data.pageall,
                        main: $('#test2')
                    });

                    pager.on('change', function (e) {
                        place.remote('post', {
                            page: e.value
                        });
                    });
                }
                pager.render(+data.page);

                $('#works-item').html(etpl.render('worksList',{
                    list: data.list,
                    type: type
                }));



                $('#invest-main').html(etpl.render('list',{
                    list: data.list
                }));

            }
        });

        //借款类型
        $('.cat_id').click(function () {
            $('.cat_id').removeClass('current');
            $(this).addClass('current');
            option.cat_id = +$(this).attr('data-value');

            broMoney.remote('post',option);
        });

        broMoney.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {

                if (!pager) {
                    pager = new Pager({
                        total: data.pageall,
                        main: $('#test2')
                    });

                    pager.on('change', function (e) {
                        broMoney.remote('post', {
                            page: e.value
                        });
                    });
                }
                pager.render(+data.page);

                $('#invest-main').html(etpl.render('list',{
                    list: data.list
                }))
            }
        });

        //借款期限
        $('.qixian').click(function () {
            $('.qixian').removeClass('current');
            $(this).addClass('current');
            option.qixian = +$(this).attr('data-value');

            qiXian.remote('post',option);
        });

        //qiXianCb
        qiXian.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {

                if (!pager) {
                    pager = new Pager({
                        total: data.pageall,
                        main: $('#test2')
                    });

                    pager.on('change', function (e) {
                        qiXian.remote('post', {
                            page: e.value
                        });
                    });
                }
                pager.render(+data.page);

                $('#invest-main').html(etpl.render('list',{
                    list: data.list
                }))
            }
        });


    }







    return {
        init:init
    };
});
