define('common/config', function () {
    var rootUrl = '' + window.location.protocol + '//' + window.location.host;
    var URL = {
            REGIST_CHECKNAME_CHECK: rootUrl + '/user/regist/checkname',
            REGIST_CHECKPHONE_CHECK: rootUrl + '/user/regist/checkphone',
            REGIST_SENDSMSCODE_CHECK: rootUrl + '/user/regist/sendsmscode',
            REGIST_CHECKSMSCODE_CHECK: rootUrl + '/user/regist/checksmscode',
            REGIST_INDEX_CHECK: rootUrl + '/user/regist/index',
            REGIST_CHECKINVITER_CHECK: rootUrl + '/user/regist/checkinviter',
            LOGIN_INDEX_CHECK: rootUrl + '/user/login/index',
            LOGIN_IMGCODE_ADD: rootUrl + '/user/login/getauthimage',
            LOGIN_IMGCODE_CHECK: rootUrl + '/user/login/checkauthimage'
        };
    return { URL: URL };
});