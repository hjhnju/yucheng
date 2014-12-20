define('common/config', function () {
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
            LOGIN_IMGCODE_CHECK: rootUrl + '/user/loginapi/checkauthimage'
        };
    return { URL: URL };
});