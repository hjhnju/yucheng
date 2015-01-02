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
        + '/account/cash/list',
//        + '/test/account/cash/list.json',
        LINE_GET: rootUrl
            + '/account/overview/profitCurve',
        SECURE_DEGREE: rootUrl
            + '/account/secure/securedegree',
        //    + '/test/account/secure/secureDegree.json',
        INVEST_LIST: rootUrl
            + '/invest/api',
        //+ '/test/invest/api.json',
        INVEST_DETAIL_START: rootUrl
            + '/invest/list',
//        + '/test/invest/list.json',
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
            + '/msg/read'
            //+ '/test/account/message/setread.json'
    };

    return {
        URL: URL
    };
});
