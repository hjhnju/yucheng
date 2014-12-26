/*! 2014 Baidu Inc. All Rights Reserved */
define('common/config', [], function () {
    var rootUrl = '' + window.location.protocol + '//' + window.location.host;
    var URL = {
            REGIST_CHECKNAME_CHECK: rootUrl + '/user/registapi/checkname',
            REGIST_CHECKPHONE_CHECK: rootUrl + '/user/registapi/checkphone',
            REGIST_SENDSMSCODE_CHECK: rootUrl + '/user/registapi/sendsmscode',
            REGIST_CHECKSMSCODE_CHECK: rootUrl + '/user/registapi/checksmscode',
            REGIST_INDEX_CHECK: rootUrl + '/user/registapi/index',
            REGIST_CHECKINVITER_CHECK: rootUrl + '/user/registapi/checkinviter',
            LOGIN_INDEX_CHECK: rootUrl + '/user/loginapi/index',
            LOGIN_IMGCODE_ADD: rootUrl + '/user/loginapi/getauthimage',
            LOGIN_IMGCODE_CHECK: rootUrl + '/user/loginapi/checkauthimage',
            EDIT_CHECKPHONE_CHECK: rootUrl + '/test/account/edit/checkphone.json',
            EDIT_GETSMSCODE_CHECK: rootUrl + '/test/account/edit/getsmscode.json',
            EDIT_GETSMSCODE2ND_CHECK: rootUrl + '/test/account/edit/getsmscode.json',
            EDIT_PHONE_SUBMITE: rootUrl + '/test/account/edit/phoneSubmite.json',
            EDIT_EMAILCONFIRM: rootUrl + '/test/account/edit/emailConfirm.json',
            INVEST_LIST: rootUrl + '/invest/api',
            INVEST_DETAIL_CONFIRM: rootUrl + '/test/invest/detail.json',
            MY_INVEST_GET: rootUrl + '/test/account/invest/index.json',
            MY_INVEST_DETAIL: rootUrl + '/test/account/invest/repayplan.json',
            MY_INVEST_ENDED: rootUrl + '/test/account/invest/ended.json',
            MY_INVEST_TENDERING: rootUrl + '/test/account/invest/tendering.json',
            MY_INVEST_TENDERFAIL: rootUrl + '/test/account/invest/tenderfail.json'
        };
    return { URL: URL };
});