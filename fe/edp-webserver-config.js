var phpcgi = require('node-phpcgi')({
    documentRoot: __dirname,
    handler: 'php-cgi'
});

exports.port = 8849;
exports.directoryIndexes = true;
exports.documentRoot = __dirname;
exports.getLocations = function () {
    return [
        { 
            location: /\/$/, 
            handler: [
                home('index.html'),
                livereload()
            ]
        },
        {
            location: '/*.html',
            handler: [
                file(),
                livereload()
            ]
        },
        {
            location: '/*.php',
            handler: [
                function (context) {
                    context.stop();
                    phpcgi(context.request, context.response, function (err) {
                        context.start();
                    });
                }
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
                    '/user/regist/checkname',
                    '/user/regist/checkphone',
                    '/user/regist/sendsmscode',
                    '/user/regist/index',
                    '/user/regist/checkinviter',
                    '/user/regist/checksmscode',
                    '/user/login/index'
                ];

                return mapper.indexOf(request.pathname) !== -1;

            },
            handler: [
                proxy('123.57.46.229', 8301)
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
