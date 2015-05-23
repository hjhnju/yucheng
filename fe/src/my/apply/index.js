/**
 * @ignore
 * @file list 我的贷款
 * @author fanyy
 * @time 15-5-55
 */

define(function(require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var moment = require('moment');
    var commonData = require('common/data');
    var header = require('common/header');
    var Pager = require('common/ui/Pager/Pager');
    var Remoter = require('common/Remoter');
    var getApplyList = new Remoter('MY_APPLY_GET');

    var tpl = require('./list.tpl');

    // 分页对象
    var pager;

    // 记录列表状态
    var status = 0;


    // 时间格式化
    var FORMATER = 'YYYY-MM-DD';

    var htmlContainer;
    var pagerContainer;

    /**
     * 初始化方法
     *
     * @public
     */
    function init() {
        htmlContainer = $('#my-apply-list');
        pagerContainer = $('#my-apply-pager');
        header.init();
        etpl.compile(tpl);

        getApplyList.remote({
            page: 1
        });

        bindEvents();
        ajaxCallBack();

    }

    /**
     * 绑定dom事件
     *
     * @inner
     */
    function bindEvents() {

    }

    /**
     * 绑定请求回调
     *
     * @inner
     */
    function ajaxCallBack() {
        // 我的贷款申请列表 成功
        getApplyList.on('success', function(data) {
            if (data.bizError) {
                renderError(data);
            } else {
                if (!data.list.length) {
                    pager = null;
                    pagerContainer.html('');
                    htmlContainer.html(etpl.render('Error', {
                        msg: '您当前没有数据哟'
                    }));
                    return;
                }
                if (!pager) {
                    pager = new Pager($.extend({}, commonData.pagerOpt, {
                        main: pagerContainer,
                        total: +data.pageall
                    }));

                    pager.on('change', function(e) {
                        //getRemoteList(e.value);
                        getApplyList.remote({
                            page: e.value
                        });
                    });
                }

                pager.render(+data.page);

                renderHTML('returnApplyList', data);
            }
        });

        // 我的贷款申请列表 失败
        getApplyList.on('fail', function(data) {
            renderError(data);
        });

    }

    /**
     * 发送请求
     * @param {number} page 页码
     */
    function getRemoteList(page) {
        htmlContainer.html(etpl.render('Loading'));
        pagerContainer.html('');
        switch (status) {
            case 1:
                getApplyList.remote({
                    page: page
                });
                break;
        }
    }

    /**
     * 渲染页码
     * @param {string} tpl 模板target
     * @param {*} data 请求返回数据
     */
    function renderHTML(tpl, data) {

        pager.setOpt('pageall', +data.pageall);
        pager.render(+data.page);

        // 格式化时间
      /*  for (var i = 0, l = data.list.length; i < l; i++) {
            var temp = data.list[i].apply;
            //创建时间
            temp.timeInfo = moment.unix(temp.create_time).format(FORMATER);
            data.list[i].apply = temp;
        }*/

        htmlContainer.html(etpl.render(tpl, {
            list: data.list
        }));
    }

    /**
     * 渲染错误提示
     * @param {*} data 请求返回的错误提示
     */
    function renderError(data) {
        htmlContainer.render(etpl.render('Error', {
            msg: data.statusInfo
        }));
    }

    return {
        init: init
    };
});
