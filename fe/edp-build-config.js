exports.input = __dirname;

var path = require( 'path' );

var date = new Date();
var time = ''
    + date.getFullYear()
    + ('' + (date.getMonth() + 101)).substr(1)
    + ('' + (date.getDate() + 100)).substr(1);

exports.output = path.resolve( __dirname, 'output/v1', time + 'x4' );

// var moduleEntries = 'html,htm,phtml,tpl,vm,js';
// var pageEntries = 'html,htm,phtml,tpl,vm';

exports.getProcessors = function () {
    var lessProcessor = new LessCompiler({
        files: [
            'src/setting/login/index.less',
            'src/setting/regist/index.less',
            'src/setting/password/index.less',
            'src/my/invest/index.less',
            'src/my/account/index.less',
            'src/my/safe/index.less',
            'src/my/message/index.less',
            'src/my/reward/index.less',
            'src/my/extract/index.less',
            'src/my/operation/index.less',
            'src/my/topup/index.less',
            'src/my/success/index.less',
            'src/project/detail/index.less',
            'src/project/list/index.less',
            'src/setting/chpassword/index.less',
            'src/setting/phone/index.less',
            'src/setting/email/index.less',
            'src/setting/select3rd/index.less',
            'src/home/index.less',
            'src/company/contact/index.less',
            'src/company/detail/index.less',
            'src/company/infos/index.less',
            'src/company/team/index.less',
            'src/company/us/index.less',
            'src/loan/loanform/index.less',
            'src/loan/loan/index.less',
            'src/loan/success/index.less',
            'src/security/index/index.less',
            'src/security/profit/index.less',
            'src/security/focus/index.less',
            'src/guide/index.less'

        ]
    });
    var cssProcessor = new CssCompressor({
        files: [
            'src/setting/login/index.less',
            'src/setting/regist/index.less',
            'src/setting/password/index.less',
            'src/my/invest/index.less',
            'src/my/account/index.less',
            'src/my/safe/index.less',
            'src/my/message/index.less',
            'src/my/reward/index.less',
            'src/my/extract/index.less',
            'src/my/operation/index.less',
            'src/my/topup/index.less',
            'src/my/success/index.less',
            'src/project/detail/index.less',
            'src/project/list/index.less',
            'src/setting/chpassword/index.less',
            'src/setting/phone/index.less',
            'src/setting/email/index.less',
            'src/setting/select3rd/index.less',
            'src/home/index.less',
            'src/company/contact/index.less',
            'src/company/detail/index.less',
            'src/company/infos/index.less',
            'src/company/team/index.less',
            'src/company/us/index.less',
            'src/loan/loanform/index.less',
            'src/loan/loan/index.less',
            'src/loan/success/index.less',
            'src/security/index/index.less',
            'src/security/profit/index.less',
            'src/security/focus/index.less',
            'src/guide/index.less'
        ]
    });
    var moduleProcessor = new ModuleCompiler();
    var jsProcessor = new JsCompressor({
        files: [
            'src/setting/login/index.js',
            'src/setting/regist/index.js',
            'src/setting/password/index.js',
            'src/my/safe/index.js',
            'src/my/account/index.js',
            'src/my/invest/index.js',
            'src/my/message/index.js',
            'src/my/reward/index.js',
            'src/my/extract/index.js',
            'src/my/operation/index.js',
            'src/my/topup/index.js',
            'src/my/success/index.js',
            'src/project/detail/index.js',
            'src/project/list/index.js',
            'src/setting/chpassword/index.js',
            'src/setting/phone/index.js',
            'src/setting/email/index.js',
            'src/setting/select3rd/index.js',
            'src/home/index.js',
            'src/company/common/common.js',
            'src/company/detail/index.js',
            'src/company/infos/index.js',
            'src/loan/loanform/index.js',
            'src/loan/loan/index.js',
            'src/loan/success/index.js',
            'src/security/index/index.js',
            'src/security/profit/index.js',
            'src/security/focus/index.js',
            'src/guide/index.js'
        ]
    });
    var html2JsProcessor = new Html2JsCompiler({
        mode: 'compress',
        extnames: [ 'tpl' ],
        combine: true
    });
    var html2jsClearPorcessor = new Html2JsCompiler({
        extnames: 'tpl',
        clean: true
    });
    var pathMapperProcessor = new PathMapper();

    return {
        'release': [ lessProcessor, html2JsProcessor, moduleProcessor,
            html2jsClearPorcessor, pathMapperProcessor ],
        'default': [
            lessProcessor, cssProcessor, html2JsProcessor, moduleProcessor,
            html2jsClearPorcessor, jsProcessor, pathMapperProcessor
        ]
    };
};

exports.exclude = [
    //'tool',
    'doc',
    'test',
    'entry',
    'output',
    'mock',
    'node_modules',
    'module.conf',
    'package.json',
    '*.sh',
    'README.md',
    'dep/packages.manifest',
    'dep/*/*/test',
    'dep/*/*/doc',
    'dep/*/*/demo',
    'dep/*/*/tool',
    'dep/*/*/*.md',
    'dep/*/*/package.json',
    'edp-*',
    '.edpproj',
    '.svn',
    '.git',
    '.gitignore',
    '.idea',
    '.project',
    'Desktop.ini',
    'Thumbs.db',
    '.DS_Store',
    '*.tmp',
    '*.bak',
    '*.swp'
];

exports.injectProcessor = function ( processors ) {
    for ( var key in processors ) {
        global[ key ] = processors[ key ];
    }
};

