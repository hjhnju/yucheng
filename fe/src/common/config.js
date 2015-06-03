/**
 * @ignore
 * @file config.js
 * @author mySunShinning(441984145@qq.com)
 * @time 14-11-15
 */

define(function () {

    /**
     * 页面域名获取
     * @type {string}
     */
    var rootUrl = ''
        + window.location.protocol
        + '//'
        + window.location.host;

    /**
     * URL对象，存储这个页面的全部URL
     *
     * @type {Object}
     */
    var URL = {
        ROOT: rootUrl,
        IMG_GET: rootUrl
            + '/user/imagecode/getimage?type=',
        REGIST_CHECKNAME_CHECK: rootUrl
            + '/user/registapi/checkname',
//            + '/test/user/registapi/checkname.json',
        REGIST_CHECKPHONE_CHECK: rootUrl
            + '/user/registapi/checkphone',
            //+ '/test/user/registapi/checkphone.json',
        REGIST_SENDSMSCODE_CHECK: rootUrl
            + '/user/registapi/sendsmscode',
            //+ '/test/user/registapi/sendsmscode.json',
        REGIST_CHECKSMSCODE_CHECK: rootUrl
            + '/user/registapi/checksmscode',
              //+ '/test/user/registapi/sendsmscode.json',
        REGIST_INDEX_CHECK: rootUrl
            + '/user/registapi/index',
            //+ '/test/user/registapi/index.json',
        REGIST_CHECKINVITER_CHECK: rootUrl
            + '/user/registapi/checkinviter',
            //+ '/test/user/registapi/checkinviter.json',
        LOGIN_INDEX_CHECK: rootUrl
            + '/user/loginapi/index',
//            + '/test/user/loginapi/index.json',
        LOGIN_IMGCODE_ADD: rootUrl
            + '/user/loginapi/getauthimageurl',
        LOGIN_IMGCODE_CHECK: rootUrl
            + '/user/loginapi/checkauthimage',
        EDIT_GETSMSCODE_CHECK: rootUrl
            + '/account/edit/getsmscode',
        //+ '/test/account/edit/getsmscode.json',
        EDIT_PHONE_SUBMITE: rootUrl
            + '/account/editapi/checkphone',
        //+ '/test/account/edit/phoneSubmite.json',
        EDIT_PHONE_SUBMITE2ND: rootUrl
        + '/account/editapi/bindnewphone',
        //+ '/test/account/edit/phoneSubmite.json',
        EDIT_EMAILCONFIRM: rootUrl
            + '/account/editapi/newemail',
        //+ '/test/account/edit/emailConfirm.json',
        EDIT_CHPWD_SUBMITE: rootUrl
            + '/account/editapi/modifypwd',
        //+ '/test/account/edit/emailConfirm.json',
        ACCOUNT_AWARD_RECEIVEAWARDS: rootUrl
        + '/account/award/receiveawards',
        //+ '/test/account/award/receiveAwards.json',
        ACCOUNT_CASH_LIST: rootUrl
        + '/account/cashapi/list',
//        + '/test/account/cash/list.json',
        LINE_GET: rootUrl
            + '/account/overview/profitCurve',
        SECURE_DEGREE: rootUrl
            + '/account/secure/securedegree',
        //    + '/test/account/secure/secureDegree.json',
        SECURE_UNBIND_THIRD: rootUrl
            + '/account/editapi/unbindthird',
        ACCOUNT_CASH_RECHARGE_ADD: rootUrl
        + '/account/cashapi/recharge',
            //+ '/test/account/cash/recharge.json',
        ACCOUNT_CASH_WITHDRAW_ADD: rootUrl
        + '/account/cashapi/withdraw',
        //+ '/test/account/cash/withdraw.json',
        INVEST_LIST: rootUrl
            + '/invest/api',
        //+ '/test/invest/api.json',
        INVEST_DETAIL_START: rootUrl
            + '/invest/list',
        //+ '/test/invest/list.json',
        INVEST_DETAIL_CONFIRM_ADD: rootUrl
            + '/invest/tender',
        MY_INVEST_GET: rootUrl
            + '/account/invest/backing',
            //+ '/test/account/invest/backing.json',
        MY_INVEST_DETAIL: rootUrl
            + '/account/invest/repayplan',
            //+ '/test/account/invest/repayplan.json',
        MY_INVEST_ENDED: rootUrl
            + '/account/invest/ended',
            //+ '/test/account/invest/ended.json',
        MY_INVEST_TENDERING: rootUrl
            + '/account/invest/tendering',
            //+ '/test/account/invest/tendering.json',
        MY_INVEST_TENDERFAIL: rootUrl
            + '/account/invest/tenderfail',
            //+ '/test/account/invest/tenderfail.json',
        MY_MSG_LIST: rootUrl
            + '/msg/list',
            //+ '/test/account/message/msglist.json',
        MY_MSG_SETREAD_ADD: rootUrl
            + '/msg/read',
            //+ '/test/account/message/setread.json',
        MY_MSG_DEL:rootUrl
            +"/msg/del",
        MY_MSG_DELALL:rootUrl
            +"/msg/delall",
        MY_MSG_SETREADALL:rootUrl
            +"/msg/readall",
            
        COMPANY_INFOS_LIST: rootUrl
            + '/infos/infoapi',
        USER_REGISTAPI_MODIFYPWD: rootUrl
            + '/user/registapi/modifypwd',
            //+ '/test/user/registapi/modifypwd.json'
        LOAN_REQUEST: rootUrl
            + '/loan/request',
            //+ '/test/loan/request.json'
        ACCOUNT_AWARDAPI_TICKETS:rootUrl
            +'/account/awardapi/tickets',
             //+ '/test/account/awardapi/tickets.json'
        ACCOUNT_AWARDAPI_EXCHANGE:rootUrl
            +'/account/awardapi/exchange',
             //+ '/test/account/awardapi/exchange.json' 
        ACCOUNT_INVITEAPI_LIST:rootUrl
            +'/account/Inviteapi/list',
             //+ '/test/account/Inviteapi/list.json' 
        APPLY_VERIFY_CHECKEMAIL :rootUrl
            +'/apply/verify/checkemail',
        APPLY_VERIFY_SUBMIT :rootUrl
            +'/apply/verify/submit',
        APPLY_BASIC_SUBMIT :rootUrl
            +'/apply/basic/submit',
        APPLY_SCHOOL_SUBMIT :rootUrl
            +'/apply/school/submit',
        APPLY_PERSON_SUBMIT :rootUrl
            +'/apply/person/submit',
        APPLY_REVIEW_SUBMIT :rootUrl
            +'/apply/review/submit',
        APPLY_VERIFY_CHECKIDCARD :rootUrl
            +'/apply/verify/checkidcard',
        MY_APPLY_GET :rootUrl
            +'/apply/api/index',
        MY_ANGEL_LIST :rootUrl
            +'/angel/api/list',
        MY_ANGEL_ADD :rootUrl
            +'/angel/api/add',
        MY_ANGELPROFIT_LIST :rootUrl
            +'/account/angelprofit/list',
        MY_ANGELPROFIT_DETAIL :rootUrl
            +'/account/angelprofit/repayplan'
            
            

        };

    return {
        URL: URL
    };
});
