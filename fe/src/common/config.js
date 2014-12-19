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
        REGIST_CHECKNAME_EDIT: rootUrl
            //+ '/user/regist/checkname',
            + '/test/user/regist/checkname.json',
        REGIST_CHECKPHONE_EDIT: rootUrl
            //+ '/user/regist/checkphone',
            + '/test/user/regist/checkphone.json',
        REGIST_SENDSMSCODE_EDIT: rootUrl
            //+ '/user/regist/sendsmscode',
            + '/test/user/regist/sendsmscode.json',
        REGIST_GETVERICODE: rootUrl
            //+ '/user/regist/getvericode',
            + '/test/user/regist/getvericode.json',
        REGIST_INDEX: rootUrl
            //+ '/user/regist/index',
            + '/test/user/regist/index.json',
        REGIST_CHECKINVITER: rootUrl
            //+ '/user/regist/checkinviter',
            + '/test/user/regist/checkinviter.json',
        LOGIN_INDEX: rootUrl
            //+ '/user/login/index',
            + '/test/user/login/index.json'
    };

    return {
        URL: URL
    };
});
