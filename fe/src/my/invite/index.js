/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function (require) {
 
    var etpl = require('etpl');
    var tpl = require('./list.tpl');

    var util = require('common/util');
    var header = require('common/header');

    var Pager = require('common/ui/Pager/Pager'); 
 
    var commonData = require('common/data');
    var Remoter = require('common/Remoter');
    var config = require('common/config');
    var receiveAwards = new Remoter('ACCOUNT_AWARD_RECEIVEAWARDS'); 
    var inviteList=new Remoter('ACCOUNT_INVITEAPI_LIST');

    var item;
    var codeUrl;


    // 分页对象
    var pager; 

    // 时间格式化
    var FORMATER = 'YYYY.MM.DD';

    var htmlContainer;

     /**
     * 初始化方法
     *
     * @public
     */
    function init(url) {
        htmlContainer = $('#my-invite-list');
        etpl.compile(tpl);
        codeUrl = url;
        header.init();

        inviteList.remote({
            page: 1
        });

        ajaxCallBack();
        bindEvent(); 

    }

    function bindEvent() {

        //点击领取
        $('.table-tr-span').click(util.debounce(function (e) {
            e.preventDefault();
            item = $(this);

            if($(this).hasClass('current')) {
                return;
            }

            receiveAwards.remote({
                id: $(this).attr('data-id')
            });
        }, 1000));

        //receiveAwardsCb
        receiveAwards.on('success', function (data) {
            if(data && data.bizError) {
                alert(data.statusInfo);
            }
            else {
                item.addClass('current').html('已领取' + data.amount + '元');
                alert('领取成功');
            }
        }); 
        
       // 邀请奖励
        $('.reward-type-link-span').zclip({
            path: config.URL.ROOT + '/static/ZeroClipboard.swf',
            copy: $.trim($('.reward-type-link-http').html()),
            afterCopy: function() {
                alert('复制成功');
            }
        });

        // 生成二维码
        var qrcode = new QRCode(
            $('.erweima')[0], {
                width: 126, //宽度
                height: 126 //高度
            }
        );
        qrcode.makeCode(codeUrl);
    }

    
     /**
     * 绑定请求回调
     *
     * @inner
     */
    function ajaxCallBack() {
        // 邀请列表 成功
        inviteList.on('success', function (data) {
            if (data.bizError) {
                renderError(data);
            }
            else {
                if (!data.list.length) {
                    pager = null;
                    $('#my-invite-pager').html('');
                    htmlContainer.html(etpl.render('Error', {
                        msg: '您还没有邀请好友哟，赶快去邀请好友参与活动'
                    }));
                    return;
                }
                if (!pager) {
                    pager = new Pager($.extend({}, commonData.pagerOpt, {
                        main: $('#my-invite-pager'),
                        total: +data.pageall
                    }));
                    
                    pager.on('change', function(e) {
                        getRemoteList(e.value);
                    });
                }

                pager.render(+data.page);

                renderHTML('returnInviteList', data);
 
            }
        }); 
        
    }


     /**
     * 发送请求
     * @param {number} page 页码
     */
    function getRemoteList(page) {
        htmlContainer.html(etpl.render('Loading'));
        $('#my-invite-pager').html('');

         inviteList.remote({
             page: page
        }); 
    }

    /**
     * 渲染页码
     * @param {string} tpl 模板target
     * @param {*} data 请求返回数据
     */
    function renderHTML(tpl, data) {

        pager.setOpt('pageall', +data.pageall);
        pager.render(+data.page); 

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
        init:init
    };
});

