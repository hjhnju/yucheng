/**
 * @ignore
 * @file index.js
 * @author yangbinYB(1033371745@qq.com)
 * @time 14-12-16
 */

define(function (require) {

    var util = require('common/util');
    var header = require('common/header');
    var Remoter = require('common/Remoter');
    var config = require('common/config');
    var tpl = require('./list.tpl');
    var receiveAwards = new Remoter('ACCOUNT_AWARD_RECEIVEAWARDS');

    var inviteList=new Remoter('ACCOUNT_INVITE_LIST');
    var item;
    var codeUrl;


    // 分页对象
    var pager;

    // 记录列表状态
    var status = 1;

    // 用来记录被点击的回款计划按钮
    var item = null;

    // 时间格式化
    var FORMATER = 'YYYY.MM.DD';

    var htmlContainer;

     /**
     * 初始化方法
     *
     * @public
     */
    function init(url) {
        htmlContainer = $('#my-reward-list');
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
                        main: $('#my-reward-pager'),
                        total: +data.pageall
                    }));
                    
                    pager.on('change', function(e) {
                        getRemoteList(e.value);
                    });
                }

                pager.render(+data.page);

                renderHTML('returnTicketList', data);

                //点击兑换的事件
                $("#my-reward-list .status2").click(function () { 
                       toExchange.remote({
                            ticketid: $(this).attr("ticketid") 
                        }); 
                });
                
                //滑动提示框
                $('#my-reward-list .reward-ticket').mouseenter(function () {
                    $(this).find(".ticket-status-tips").slideDown();
                }).mouseleave(function () {
                     $(this).find(".ticket-status-tips").slideUp();
                });



            }
        }); 
        
         toExchange.on('success', function (data) {
              if (data.bizError) {
                 //兑换失败
                 $('.my-reward-tip').addClass('my-reward-tip-error');  
                 $('.my-reward-tip').html(data.statusInfo); 
              }
              else {
                 //兑换成功
                 $('.my-reward-tip').addClass('my-reward-tip-success');  
                  $('.my-reward-tip .statusInfo').html(data.msg); 
             }  
              $(".my-reward-tip").slideDown();
              $(".close-reward-tip").click(function() {
                     $(".my-reward-tip").slideUp();
             });
         });
    }


     /**
     * 发送请求
     * @param {number} page 页码
     */
    function getRemoteList(page) {
        htmlContainer.html(etpl.render('Loading'));
        $('#my-reward-pager').html('');

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

        // 格式化时间
        for (var i = 0, l = data.list.length; i < l; i++) { 
                data.list[i].valid_time = moment.unix(data.list[i].valid_time).format(FORMATER); 
                if(data.list[i].pay_time){
                     data.list[i].pay_time = moment.unix(data.list[i].pay_time).format(FORMATER); 
                }
                 if(data.list[i].pass_time){
                     data.list[i].pass_time = moment.unix(data.list[i].pass_time).format(FORMATER); 
                }
        }

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

