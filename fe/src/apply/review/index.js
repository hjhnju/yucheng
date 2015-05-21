/**
 * @ignore
 * @file index
 * @author fanyy
 * 借款请求 贷款信息确认 review
 * @time 15-5-19
 */

define(function(require) {
    var $ = require('jquery');
    var applyCommon = require('apply/common/applyCommon'); 
    var util = require('common/util');
    var Remoter = require('common/Remoter');

    var reviewSubmit=new Remoter('APPLY_REVIEW_SUBMIT');
 
 
    var formParams;

    function init(rate1, rate2) {
        formParams = applyCommon.init(rate1, rate2);
        bindEvent();
        ajaxCallback();
    }

    /**
     * 步骤二 绑定事件
     *  
     */
    function bindEvent() { 
        //下一步
        $('.loan .loan-submit').click(util.debounce(function(e) {
            e.preventDefault(); 
            //必须同意协议内容
            if (!$('#tiaoyue-itp')[0].checked) {
                $('#error-box').text('请同意用户条约!');
                return;
            }else{
                $('#error-box').text('');
            }
            reviewSubmit.remote();

        }, 1000));

    }

    /**
     * 回调函数
     * @return {[type]} [description]
     */
    function ajaxCallback() {
        //提交后
        reviewSubmit.on('success', function(data) {
            if (data && data.bizError) {
                errorArray.errorbox.html(data.statusInfo);
            } else { 
            }
        });
    }
    return {
        init: init
    };
});
