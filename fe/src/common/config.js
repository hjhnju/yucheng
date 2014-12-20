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
            + '/user/regist/checkname',
//            + '/test/user/regist/checkname.json',
        REGIST_CHECKPHONE_CHECK: rootUrl
            + '/user/regist/checkphone',
//            + '/test/user/regist/checkphone.json',
        REGIST_SENDSMSCODE_CHECK: rootUrl
            + '/user/regist/sendsmscode',
//            + '/test/user/regist/sendsmscode.json',
        REGIST_CHECKSMSCODE_CHECK: rootUrl
            + '/user/regist/checksmscode',
        REGIST_INDEX_CHECK: rootUrl
            + '/user/regist/index',
//            + '/test/user/regist/index.json',
        REGIST_CHECKINVITER_CHECK: rootUrl
            + '/user/regist/checkinviter',
//            + '/test/user/regist/checkinviter.json',
        LOGIN_INDEX_CHECK: rootUrl
            + '/user/login/index'
//            + '/test/user/login/index.json'
    };

    return {
        URL: URL
    };
});
