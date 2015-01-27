var phpcgi = require('node-phpcgi')({
    documentRoot: __dirname,
    handler: 'php-cgi'
});

exports.port = 8805;
exports.directoryIndexes = true;
exports.documentRoot = __dirname;
exports.getLocations = function () {
    return [
        { 
            location: /\/$/, 
            handler: [
                home('index.html')
            ]
        },
        {
            location: '/*.html',
            handler: [
                file()
            ]
        },
        //{
        //    location: '/*.php',
        //    handler: [
        //        function (context) {
        //            context.stop();
        //            phpcgi(context.request, context.response, function (err) {
        //                context.start();
        //            });
        //        }
        //    ]
        //},
        {
            location: /\.php($|\?)/,
            handler: [
                php('/usr/local/Cellar/php54/5.4.35/bin/php-cgi')  //斌斌的
                //php('/usr/local/Cellar/php54/5.4.31/bin/php-cgi')  //老婆的
            ]
        },
        { 
            location: /^\/redirect-local/, 
            handler: redirect('redirect-target', false) 
        },
        { 
            location: /^\/redirect-remote/, 
            handler: redirect('http://www.baidu.com', false) 
        },
        { 
            location: /^\/redirect-target/, 
            handler: content('redirectd!') 
        },
        {
            location: '/empty', 
            handler: empty() 
        },
        { 
            location: /\.css($|\?)/, 
            handler: [
                autocss()
            ]
        },
        { 
            location: /\.less($|\?)/, 
            handler: [
                file(),
                less()
            ]
        },
        { 
            location: /\.styl($|\?)/, 
            handler: [
                file(),
                stylus()
            ]
        },
        {
            location: /\.tpl\.js($|\?)/,
            handler: [
                html2js()
            ]
        },
        {
            location: function (request) {

                var mapper = [
                    '/user/registapi/checkname',
                    '/user/registapi/checkphone',
                    '/user/registapi/sendsmscode',
                    '/user/registapi/index',
                    '/user/registapi/checkinviter',
                    '/user/registapi/checksmscode',
                    '/user/loginapi/index',
                    '/user/loginapi/getauthimageurl',
                    '/user/loginapi/checkauthimage',
                    '/invest/api',
                    '/account/edit/getsmscode',
                    '/account/editapi/newemail',
                    '/account/editapi/checkphone',
                    '/account/editapi/bindnewphone',
                    '/account/invest/backing',
                    '/account/invest/repayplan',
                    '/account/invest/ended',
                    '/account/invest/tendering',
                    '/account/invest/tenderfail',
                    '/account/secure/securedegree',
                    '/account/editapi/modifypwd',
                    '/account/editapi/newemail',
                    '/account/overview/profitCurve',
                    '/account/award/receiveawards',
                    '/msg/list',
                    '/msg/read',
                    '/user/imagecode/getimage',
                    '/invest/tender',
                    '/account/cashapi/recharge',
                    '/account/cashapi/withdraw',
                    '/account/cashapi/list',
                    '/infos/post/list',
                    '/user/registapi/modifypwd',
                    '/loan/request'
                ];

                return mapper.indexOf(request.pathname) !== -1;

            },
            handler: [
                proxy('123.57.46.229', 8082)  //李璐
                //proxy('123.57.46.229', 8301)  //胡伟
                //proxy('123.57.46.229', 8600)  //松芳
                //proxy('123.57.46.229', 8081)  //俊华

            ]
        },
        { 
            location: /^.*$/, 
            handler: [
                file(),
                proxyNoneExists()
            ]
        }
    ];
};

exports.injectResource = function ( res ) {
    for ( var key in res ) {
        global[ key ] = res[ key ];
    }
};
