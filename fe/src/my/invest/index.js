/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function (require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var commonDate = require('common/data');
    var header = require('common/header');
    var Pager = require('common/ui/Pager/Pager');
    var Remoter = require('common/Remoter');
    var getInvestList = new Remoter('MY_INVEST_GET');
    var getInvestDetail = new Remoter('MY_INVEST_DETAIL');

    var tpl = require('./list.tpl');

    var pager;
    var status = 1;
    var item = null;

    function init() {
        header.init();
        etpl.compile(tpl);

        getInvestList.remote({
            page: 1,
            status: status
        });

        ajaxCallBack();
        bindEvents();
    }

    function bindEvents() {
        $('.my-invest-list').delegate('.view-plan-btn', 'click', function () {
            var value = $.trim($(this).attr('data-id'));
            item = $(this);

            $('.my-invest-item').removeClass('current');
            $(this).closest('.my-invest-item').addClass('current');
            getInvestDetail.remote({
                id: value
            });
        });
    }

    function ajaxCallBack() {
        getInvestList.on('success', function (data) {
            if (data.bizError) {
                 alert(data.statusInfo);
            }
            else {
                $('#my-invest-list').html(etpl.render('returnMoneyList', {
                    list: data.list
                }));
            }
        });

        getInvestDetail.on('success', function (data) {
            if (data.bizError) {
                alert(data.statusInfo);
            }
            else {
                if (!item) {
                    return;
                }
                var container = $(item).closest('.my-invest-item')
                    .addClass('current').find('.my-invest-detail');

                container.html(etpl.render('returnMoneyDetail', {
                    data: data
                }));
            }
        });
    }

    return {
        init: init
    };
});
