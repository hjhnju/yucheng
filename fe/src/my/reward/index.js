/**
 * @ignore
 * @file index.js
 * @author fanyy
 * @time 15-4-11
 */

define(function (require) {
    var $ = require('jquery');
    var etpl = require('etpl');
    var tpl = require('./list.tpl');
    
    var Pager = require('common/ui/Pager/Pager');
     var moment = require('moment');
    var commonData = require('common/data');
    var header = require('common/header');
    var Remoter = require('common/Remoter'); 
    var ticketsList=new Remoter('ACCOUNT_AWARDAPI_TICKETS');
    var toExchange=new Remoter('ACCOUNT_AWARDAPI_EXCHANGE');

    // 分页对象
    var pager;

    // 记录列表状态
    var status = 2;

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
    function init( ) {
         
        htmlContainer = $('#my-reward-list');
        header.init();
        etpl.compile(tpl);
        
         ticketsList.remote({
            page: 1,
             status:2
        });

        ajaxCallBack();
        bindEvents(); 

    }

    function bindEvents() {

         // 选择奖券类型
        $('.my-reward-tab-item').click(function () {
            if (!$(this).hasClass('current')) {
                // 改变选中状态
                $('.my-reward-tab-item').removeClass('current');
                $(this).addClass('current');

                // 记录当前选中类型
                status = +$.trim($(this).attr('data-value'));

                // 获取数据
                 pager = null;
                getRemoteList(1);
            }
        });


        
    }
    
  /**
     * 绑定请求回调
     *
     * @inner
     */
    function ajaxCallBack() {
        // 奖券列表 成功
        ticketsList.on('success', function (data) {
            if (data.bizError) {
                renderError(data);
            }
            else {
                if (!data.list.length) {
                    pager = null;
                    $('#my-reward-pager').html('');
                    htmlContainer.html(etpl.render('Error', {
                        msg: '您当前没有奖券哟'
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
                $("#my-reward-list .status2").click(function (e) {  
                      $(this).unbind("click"); //移除click
                      $(this).find(".ticket-status2-tips").show();
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
                 $(".my-reward-tip").slideDown();  
              }
              else {
                 //兑换成功  
                  $(".my-reward-tip").slideDown();
                  $('.my-reward-tip').addClass('my-reward-tip-success');  
                  $('.my-reward-tip .statusInfo').html(data.msg);  
             }  
              $(".close-reward-tip").click(function() {
                    $(".my-reward-tip").slideUp(); 
              });
              //刷新列表
              //当前页码
              var currentPage=$("#my-reward-pager .ui-pager-box .current").attr("data-value");
                  getRemoteList(currentPage?currentPage:1);
              
         });
    }


     /**
     * 发送请求
     * @param {number} page 页码
     */
    function getRemoteList(page) {
        htmlContainer.html(etpl.render('Loading'));
        $('#my-reward-pager').html('');

         ticketsList.remote({
                    page: page,
                    status:status
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

