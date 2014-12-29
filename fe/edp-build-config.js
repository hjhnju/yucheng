exports.input = __dirname;

var path = require( 'path' );
exports.output = path.resolve( __dirname, 'output' );

// var moduleEntries = 'html,htm,phtml,tpl,vm,js';
// var pageEntries = 'html,htm,phtml,tpl,vm';

exports.getProcessors = function () {
    var lessProcessor = new LessCompiler({
        files: [
            'src/setting/login/index.less',
            'src/setting/regist/index.less',
            'src/my/invest/index.less',
            'src/my/account/index.less',
            'src/my/safe/index.less',
            'src/my/message/index.less',
            'src/project/detail/index.less',
            'src/project/list/index.less',
            'src/setting/chpassword/index.less',
            'src/setting/phone/index.less',
            'src/setting/email/index.less'
        ]
    });
    var cssProcessor = new CssCompressor({
        files: [
            'src/setting/login/index.less',
            'src/setting/regist/index.less',
            'src/my/invest/index.less',
            'src/my/account/index.less',
            'src/my/safe/index.less',
            'src/my/message/index.less',
            'src/project/detail/index.less',
            'src/project/list/index.less',
            'src/setting/chpassword/index.less',
            'src/setting/phone/index.less',
            'src/setting/email/index.less'
        ]
    });
    var moduleProcessor = new ModuleCompiler();
    var jsProcessor = new JsCompressor({
        files: [
            'src/setting/login/index.js',
            'src/setting/regist/index.js',
            'src/my/safe/index.js',
            'src/my/account/index.js',
            'src/my/invest/index.js',
            'src/my/message/index.js',
            'src/project/detail/index.js',
            'src/project/list/index.js',
            'src/setting/chpassword/index.js',
            'src/setting/phone/index.js',
            'src/setting/email/index.js'
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
    var addCopyright = new AddCopyright();

    return {
        'release': [ lessProcessor, moduleProcessor, pathMapperProcessor ],
        'default': [
            lessProcessor, cssProcessor, html2JsProcessor, moduleProcessor,
            html2jsClearPorcessor, jsProcessor, pathMapperProcessor, addCopyright
        ]
    };
};

exports.exclude = [
    //'tool',
    'doc',
    'test',
    'entry',
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

