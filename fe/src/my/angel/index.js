/**
 * @ignore
 * @file index
 * @author fanyy 
 * @time 15-5-28
 */

define(function(require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var moment = require('moment');
    var commonData = require('common/data');
    var header = require('common/header');
    var Pager = require('common/ui/Pager/Pager');
    var Remoter = require('common/Remoter');
    var getAngelList = new Remoter('MY_ANGEL_LIST');
    var addAngel = new Remoter('MY_ANGEL_ADD');
    var tpl = require('./list.tpl');

    // 分页对象
    var pager;
    // 时间格式化
    var FORMATER = 'YYYY-MM-DD';

    var htmlContainer;

    /**
     * 初始化方法
     *
     * @public
     */
    function init() {
        htmlContainer = $('#my-angel-list');
        header.init();
        etpl.compile(tpl);

        getAngelList.remote({
            page: 1
        });

        ajaxCallBack();
        bindEvents();
    }

    /**
     * 绑定dom事件
     *
     * @inner
     */
    function bindEvents() {
        //添加爱心天使
        $('.add-angel-btn').click(function(e) {
            e.preventDefault();
            var value = $('.add-angel-input').val();
            if (!value) {
                $('.add-error').html("天使码不能为空哦！");
                return;
            }
            addAngel.remote({
                code: value
            });
        });
    }


    /**
     * 绑定请求回调
     *
     * @inner
     */
    function ajaxCallBack() {
        // 回款中列表 成功
        getAngelList.on('success', function(data) {
            if (data.bizError) {
                htmlContainer.render(etpl.render('Error', {
                    msg: data.statusInfo
                }));
            } else {
                if (!data.list.length) {
                    pager = null;
                    $('#my-angel-pager').html('');
         /*           htmlContainer.html(etpl.render('Error', {
                        msg: '您当前没有数据哟'
                    }));*/
                    return;
                }
                if (!pager) {
                    pager = new Pager($.extend({}, commonData.pagerOpt, {
                        main: $('#my-angel-pager'),
                        total: +data.pageall
                    }));

                    pager.on('change', function(e) {
                        getAngelList(e.value);
                    });
                }

                pager.render(+data.page);
                pager.setOpt('pageall', +data.pageall);

                htmlContainer.html(etpl.render('returnAngelList', {
                    list: data.list
                }));
            }
        }); 
        //添加爱心天使 成功
        addAngel.on('success', function(data) {
            if (data.bizError) {
                $('.add-error').html(data.statusInfo);
            } else {
                getAngelList.remote({
                    page: 1
                });
            }
        });
    }


    return {
        init: init
    };
});
