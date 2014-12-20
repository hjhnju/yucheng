exports.input = __dirname;

var path = require( 'path' );
exports.output = path.resolve( __dirname, 'output' );

// var moduleEntries = 'html,htm,phtml,tpl,vm,js';
// var pageEntries = 'html,htm,phtml,tpl,vm';

exports.getProcessors = function () {
    var lessProcessor = new LessCompiler();
    var cssProcessor = new CssCompressor({
        files: [
            'src/setting/login/index.less',
            'src/setting/regist/index.less'
        ]
    });
    var moduleProcessor = new ModuleCompiler();
    var jsProcessor = new JsCompressor({
        files: [
            'src/setting/login/index.js',
            'src/setting/regist/index.js'
        ]
    });
    var pathMapperProcessor = new PathMapper();
    var addCopyright = new AddCopyright();

    return {
        'default': [ lessProcessor, moduleProcessor, pathMapperProcessor ],
        'release': [
            lessProcessor, cssProcessor, moduleProcessor,
            jsProcessor, pathMapperProcessor, addCopyright
        ]
    };
};

exports.exclude = [
    'tool',
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

