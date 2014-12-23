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
        REGIST_CHECKNAME_CHECK: rootUrl
            + '/user/registapi/checkname',
//            + '/test/user/regist/checkname.json',
        REGIST_CHECKPHONE_CHECK: rootUrl
            + '/user/registapi/checkphone',
//            + '/test/user/regist/checkphone.json',
        REGIST_SENDSMSCODE_CHECK: rootUrl
            + '/user/registapi/sendsmscode',
//            + '/test/user/regist/sendsmscode.json',
        REGIST_CHECKSMSCODE_CHECK: rootUrl
            + '/user/registapi/checksmscode',
        REGIST_INDEX_CHECK: rootUrl
            + '/user/registapi/index',
//            + '/test/user/regist/index.json',
        REGIST_CHECKINVITER_CHECK: rootUrl
            + '/user/registapi/checkinviter',
//            + '/test/user/regist/checkinviter.json',
        LOGIN_INDEX_CHECK: rootUrl
            + '/user/loginapi/index',
//            + '/test/user/login/index.json',
        LOGIN_IMGCODE_ADD: rootUrl
            + '/user/loginapi/getauthimage',
        LOGIN_IMGCODE_CHECK: rootUrl
            + '/user/loginapi/checkauthimage',
        EDIT_CHECKPHONE_CHECK: rootUrl
            //+ '/account/edit/checkphone.json',
            + '/test/account/edit/checkphone.json',
        EDIT_GETVERICODE_CHECK: rootUrl
            //+ '/account/edit/getvericode.json',
        + '/test/account/edit/getvericode.json',
        LIST_PLACE: rootUrl
            //+ '/account/edit/getvericode.json',
        + '/test/invest/list.json',
        LIST_BROMONEY: rootUrl
            //+ '/account/edit/getvericode.json',
        + '/test/invest/list.json',
        LIST_QIXIAN: rootUrl
            //+ '/account/edit/getvericode.json',
        + '/test/invest/list.json'
    };

    return {
        URL: URL
    };
});
