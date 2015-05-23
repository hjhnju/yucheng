;
(function () {
    'use strict';
    var objectTypes = {
            'function': true,
            'object': true
        };
    var root = objectTypes[typeof window] && window || this;
    var oldRoot = root;
    var freeExports = objectTypes[typeof exports] && exports;
    var freeModule = objectTypes[typeof module] && module && !module.nodeType && module;
    var freeGlobal = freeExports && freeModule && typeof global == 'object' && global;
    if (freeGlobal && (freeGlobal.global === freeGlobal || freeGlobal.window === freeGlobal || freeGlobal.self === freeGlobal)) {
        root = freeGlobal;
    }
    var maxSafeInteger = Math.pow(2, 53) - 1;
    var reOpera = /Opera/;
    var thisBinding = this;
    var objectProto = Object.prototype;
    var hasOwnProperty = objectProto.hasOwnProperty;
    var toString = objectProto.toString;
    function capitalize(string) {
        string = String(string);
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    function cleanupOS(os, pattern, label) {
        var data = {
                '6.3': '8.1',
                '6.2': '8',
                '6.1': 'Server 2008 R2 / 7',
                '6.0': 'Server 2008 / Vista',
                '5.2': 'Server 2003 / XP 64-bit',
                '5.1': 'XP',
                '5.01': '2000 SP1',
                '5.0': '2000',
                '4.0': 'NT',
                '4.90': 'ME'
            };
        if (pattern && label && /^Win/i.test(os) && (data = data[0, /[\d.]+$/.exec(os)])) {
            os = 'Windows ' + data;
        }
        os = String(os);
        if (pattern && label) {
            os = os.replace(RegExp(pattern, 'i'), label);
        }
        os = format(os.replace(/ ce$/i, ' CE').replace(/hpw/i, 'web').replace(/Macintosh/, 'Mac OS').replace(/_PowerPC/i, ' OS').replace(/(OS X) [^ \d]+/i, '$1').replace(/Mac (OS X)/, '$1').replace(/\/(\d)/, ' $1').replace(/_/g, '.').replace(/(?: BePC|[ .]*fc[ \d.]+)$/i, '').replace(/x86\.64/gi, 'x86_64').replace(/(Windows Phone)(?! OS)/, '$1 OS').split(' on ')[0]);
        return os;
    }
    function each(object, callback) {
        var index = -1, length = object ? object.length : 0;
        if (typeof length == 'number' && length > -1 && length <= maxSafeInteger) {
            while (++index < length) {
                callback(object[index], index, object);
            }
        } else {
            forOwn(object, callback);
        }
    }
    function format(string) {
        string = trim(string);
        return /^(?:webOS|i(?:OS|P))/.test(string) ? string : capitalize(string);
    }
    function forOwn(object, callback) {
        for (var key in object) {
            if (hasOwnProperty.call(object, key)) {
                callback(object[key], key, object);
            }
        }
    }
    function getClassOf(value) {
        return value == null ? capitalize(value) : toString.call(value).slice(8, -1);
    }
    function isHostType(object, property) {
        var type = object != null ? typeof object[property] : 'number';
        return !/^(?:boolean|number|string|undefined)$/.test(type) && (type == 'object' ? !!object[property] : true);
    }
    function qualify(string) {
        return String(string).replace(/([ -])(?!$)/g, '$1?');
    }
    function reduce(array, callback) {
        var accumulator = null;
        each(array, function (value, index) {
            accumulator = callback(accumulator, value, index, array);
        });
        return accumulator;
    }
    function trim(string) {
        return String(string).replace(/^ +| +$/g, '');
    }
    function parse(ua) {
        var context = root;
        var isCustomContext = ua && typeof ua == 'object' && getClassOf(ua) != 'String';
        if (isCustomContext) {
            context = ua;
            ua = null;
        }
        var nav = context.navigator || {};
        var userAgent = nav.userAgent || '';
        ua || (ua = userAgent);
        var isModuleScope = isCustomContext || thisBinding == oldRoot;
        var likeChrome = isCustomContext ? !!nav.likeChrome : /\bChrome\b/.test(ua) && !/internal|\n/i.test(toString.toString());
        var objectClass = 'Object', airRuntimeClass = isCustomContext ? objectClass : 'ScriptBridgingProxyObject', enviroClass = isCustomContext ? objectClass : 'Environment', javaClass = isCustomContext && context.java ? 'JavaPackage' : getClassOf(context.java), phantomClass = isCustomContext ? objectClass : 'RuntimeObject';
        var java = /Java/.test(javaClass) && context.java;
        var rhino = java && getClassOf(context.environment) == enviroClass;
        var alpha = java ? 'a' : '\u03B1';
        var beta = java ? 'b' : '\u03B2';
        var doc = context.document || {};
        var opera = context.operamini || context.opera;
        var operaClass = reOpera.test(operaClass = isCustomContext && opera ? opera['[[Class]]'] : getClassOf(opera)) ? operaClass : opera = null;
        var data;
        var arch = ua;
        var description = [];
        var prerelease = null;
        var useFeatures = ua == userAgent;
        var version = useFeatures && opera && typeof opera.version == 'function' && opera.version();
        var isSpecialCasedOS;
        var layout = getLayout([
                {
                    'label': 'WebKit',
                    'pattern': 'AppleWebKit'
                },
                'iCab',
                'Presto',
                'NetFront',
                'Tasman',
                'Trident',
                'KHTML',
                'Gecko'
            ]);
        var name = getName([
                'Adobe AIR',
                'Arora',
                'Avant Browser',
                'Breach',
                'Camino',
                'Epiphany',
                'Fennec',
                'Flock',
                'Galeon',
                'GreenBrowser',
                'iCab',
                'Iceweasel',
                {
                    'label': 'SRWare Iron',
                    'pattern': 'Iron'
                },
                'K-Meleon',
                'Konqueror',
                'Lunascape',
                'Maxthon',
                'Midori',
                'Nook Browser',
                'PhantomJS',
                'Raven',
                'Rekonq',
                'RockMelt',
                'SeaMonkey',
                {
                    'label': 'Silk',
                    'pattern': '(?:Cloud9|Silk-Accelerated)'
                },
                'Sleipnir',
                'SlimBrowser',
                'Sunrise',
                'Swiftfox',
                'WebPositive',
                'Opera Mini',
                {
                    'label': 'Opera Mini',
                    'pattern': 'OPiOS'
                },
                'Opera',
                {
                    'label': 'Opera',
                    'pattern': 'OPR'
                },
                'Chrome',
                {
                    'label': 'Chrome Mobile',
                    'pattern': '(?:CriOS|CrMo)'
                },
                {
                    'label': 'Firefox',
                    'pattern': '(?:Firefox|Minefield)'
                },
                {
                    'label': 'IE',
                    'pattern': 'MSIE'
                },
                'Safari'
            ]);
        var product = getProduct([
                {
                    'label': 'BlackBerry',
                    'pattern': 'BB10'
                },
                'BlackBerry',
                {
                    'label': 'Galaxy S',
                    'pattern': 'GT-I9000'
                },
                {
                    'label': 'Galaxy S2',
                    'pattern': 'GT-I9100'
                },
                {
                    'label': 'Galaxy S3',
                    'pattern': 'GT-I9300'
                },
                {
                    'label': 'Galaxy S4',
                    'pattern': 'GT-I9500'
                },
                'Google TV',
                'iPad',
                'iPod',
                'iPhone',
                'Kindle',
                {
                    'label': 'Kindle Fire',
                    'pattern': '(?:Cloud9|Silk-Accelerated)'
                },
                'Nook',
                'PlayBook',
                'PlayStation 4',
                'PlayStation 3',
                'PlayStation Vita',
                'TouchPad',
                'Transformer',
                {
                    'label': 'Wii U',
                    'pattern': 'WiiU'
                },
                'Wii',
                'Xbox One',
                {
                    'label': 'Xbox 360',
                    'pattern': 'Xbox'
                },
                'Xoom'
            ]);
        var manufacturer = getManufacturer({
                'Apple': {
                    'iPad': 1,
                    'iPhone': 1,
                    'iPod': 1
                },
                'Amazon': {
                    'Kindle': 1,
                    'Kindle Fire': 1
                },
                'Asus': { 'Transformer': 1 },
                'Barnes & Noble': { 'Nook': 1 },
                'BlackBerry': { 'PlayBook': 1 },
                'Google': { 'Google TV': 1 },
                'HP': { 'TouchPad': 1 },
                'HTC': {},
                'LG': {},
                'Microsoft': {
                    'Xbox': 1,
                    'Xbox One': 1
                },
                'Motorola': { 'Xoom': 1 },
                'Nintendo': {
                    'Wii U': 1,
                    'Wii': 1
                },
                'Nokia': {},
                'Samsung': {
                    'Galaxy S': 1,
                    'Galaxy S2': 1,
                    'Galaxy S3': 1,
                    'Galaxy S4': 1
                },
                'Sony': {
                    'PlayStation 4': 1,
                    'PlayStation 3': 1,
                    'PlayStation Vita': 1
                }
            });
        var os = getOS([
                'Android',
                'CentOS',
                'Debian',
                'Fedora',
                'FreeBSD',
                'Gentoo',
                'Haiku',
                'Kubuntu',
                'Linux Mint',
                'Red Hat',
                'SuSE',
                'Ubuntu',
                'Xubuntu',
                'Cygwin',
                'Symbian OS',
                'hpwOS',
                'webOS ',
                'webOS',
                'Tablet OS',
                'Linux',
                'Mac OS X',
                'Macintosh',
                'Mac',
                'Windows 98;',
                'Windows '
            ]);
        function getLayout(guesses) {
            return reduce(guesses, function (result, guess) {
                return result || RegExp('\\b' + (guess.pattern || qualify(guess)) + '\\b', 'i').exec(ua) && (guess.label || guess);
            });
        }
        function getManufacturer(guesses) {
            return reduce(guesses, function (result, value, key) {
                return result || (value[product] || value[0, /^[a-z]+(?: +[a-z]+\b)*/i.exec(product)] || RegExp('\\b' + qualify(key) + '(?:\\b|\\w*\\d)', 'i').exec(ua)) && key;
            });
        }
        function getName(guesses) {
            return reduce(guesses, function (result, guess) {
                return result || RegExp('\\b' + (guess.pattern || qualify(guess)) + '\\b', 'i').exec(ua) && (guess.label || guess);
            });
        }
        function getOS(guesses) {
            return reduce(guesses, function (result, guess) {
                var pattern = guess.pattern || qualify(guess);
                if (!result && (result = RegExp('\\b' + pattern + '(?:/[\\d.]+|[ \\w.]*)', 'i').exec(ua))) {
                    result = cleanupOS(result, pattern, guess.label || guess);
                }
                return result;
            });
        }
        function getProduct(guesses) {
            return reduce(guesses, function (result, guess) {
                var pattern = guess.pattern || qualify(guess);
                if (!result && (result = RegExp('\\b' + pattern + ' *\\d+[.\\w_]*', 'i').exec(ua) || RegExp('\\b' + pattern + '(?:; *(?:[a-z]+[_-])?[a-z]+\\d+|[^ ();-]*)', 'i').exec(ua))) {
                    if ((result = String(guess.label && !RegExp(pattern, 'i').test(guess.label) ? guess.label : result).split('/'))[1] && !/[\d.]+/.test(result[0])) {
                        result[0] += ' ' + result[1];
                    }
                    guess = guess.label || guess;
                    result = format(result[0].replace(RegExp(pattern, 'i'), guess).replace(RegExp('; *(?:' + guess + '[_-])?', 'i'), ' ').replace(RegExp('(' + guess + ')[-_.]?(\\w)', 'i'), '$1 $2'));
                }
                return result;
            });
        }
        function getVersion(patterns) {
            return reduce(patterns, function (result, pattern) {
                return result || (RegExp(pattern + '(?:-[\\d.]+/|(?: for [\\w-]+)?[ /-])([\\d.]+[^ ();/_-]*)', 'i').exec(ua) || 0)[1] || null;
            });
        }
        function toStringPlatform() {
            return this.description || '';
        }
        layout && (layout = [layout]);
        if (manufacturer && !product) {
            product = getProduct([manufacturer]);
        }
        if (data = /Google TV/.exec(product)) {
            product = data[0];
        }
        if (/\bSimulator\b/i.test(ua)) {
            product = (product ? product + ' ' : '') + 'Simulator';
        }
        if (name == 'Opera Mini' && /OPiOS/.test(ua)) {
            description.push('running in Turbo / Uncompressed mode');
        }
        if (/^iP/.test(product)) {
            name || (name = 'Safari');
            os = 'iOS' + ((data = / OS ([\d_]+)/i.exec(ua)) ? ' ' + data[1].replace(/_/g, '.') : '');
        } else if (name == 'Konqueror' && !/buntu/i.test(os)) {
            os = 'Kubuntu';
        } else if (manufacturer && manufacturer != 'Google' && (/Chrome/.test(name) && !/Mobile Safari/.test(ua) || /Vita/.test(product))) {
            name = 'Android Browser';
            os = /Android/.test(os) ? os : 'Android';
        } else if (!name || (data = !/\bMinefield\b|\(Android;/i.test(ua) && /Firefox|Safari/.exec(name))) {
            if (name && !product && /[\/,]|^[^(]+?\)/.test(ua.slice(ua.indexOf(data + '/') + 8))) {
                name = null;
            }
            if ((data = product || manufacturer || os) && (product || manufacturer || /Android|Symbian OS|Tablet OS|webOS/.test(os))) {
                name = /[a-z]+(?: Hat)?/i.exec(/Android/.test(os) ? os : data) + ' Browser';
            }
        }
        if ((data = /\((Mobile|Tablet).*?Firefox/i.exec(ua)) && data[1]) {
            os = 'Firefox OS';
            if (!product) {
                product = data[1];
            }
        }
        if (!version) {
            version = getVersion([
                '(?:Cloud9|CriOS|CrMo|Iron|Opera ?Mini|OPiOS|OPR|Raven|Silk(?!/[\\d.]+$))',
                'Version',
                qualify(name),
                '(?:Firefox|Minefield|NetFront)'
            ]);
        }
        if (layout == 'iCab' && parseFloat(version) > 3) {
            layout = ['WebKit'];
        } else if (data = /Opera/.test(name) && (/OPR/.test(ua) ? 'Blink' : 'Presto') || /\b(?:Midori|Nook|Safari)\b/i.test(ua) && 'WebKit' || !layout && /\bMSIE\b/i.test(ua) && (os == 'Mac OS' ? 'Tasman' : 'Trident')) {
            layout = [data];
        } else if (/PlayStation(?! Vita)/i.test(name) && layout == 'WebKit') {
            layout = ['NetFront'];
        }
        if (name != 'IE' && layout == 'Trident' && (data = /\brv:([\d.]+)/.exec(ua))) {
            if (name) {
                description.push('identifying as ' + name + (version ? ' ' + version : ''));
            }
            name = 'IE';
            version = data[1];
        }
        if (useFeatures) {
            if (isHostType(context, 'global')) {
                if (java) {
                    data = java.lang.System;
                    arch = data.getProperty('os.arch');
                    os = os || data.getProperty('os.name') + ' ' + data.getProperty('os.version');
                }
                if (isModuleScope && isHostType(context, 'system') && (data = [context.system])[0]) {
                    os || (os = data[0].os || null);
                    try {
                        data[1] = context.require('ringo/engine').version;
                        version = data[1].join('.');
                        name = 'RingoJS';
                    } catch (e) {
                        if (data[0].global.system == context.system) {
                            name = 'Narwhal';
                        }
                    }
                } else if (typeof context.process == 'object' && (data = context.process)) {
                    name = 'Node.js';
                    arch = data.arch;
                    os = data.platform;
                    version = /[\d.]+/.exec(data.version)[0];
                } else if (rhino) {
                    name = 'Rhino';
                }
            } else if (getClassOf(data = context.runtime) == airRuntimeClass) {
                name = 'Adobe AIR';
                os = data.flash.system.Capabilities.os;
            } else if (getClassOf(data = context.phantom) == phantomClass) {
                name = 'PhantomJS';
                version = (data = data.version || null) && data.major + '.' + data.minor + '.' + data.patch;
            } else if (typeof doc.documentMode == 'number' && (data = /\bTrident\/(\d+)/i.exec(ua))) {
                version = [
                    version,
                    doc.documentMode
                ];
                if ((data = +data[1] + 4) != version[1]) {
                    description.push('IE ' + version[1] + ' mode');
                    layout && (layout[1] = '');
                    version[1] = data;
                }
                version = name == 'IE' ? String(version[1].toFixed(1)) : version[0];
            }
            os = os && format(os);
        }
        if (version && (data = /(?:[ab]|dp|pre|[ab]\d+pre)(?:\d+\+?)?$/i.exec(version) || /(?:alpha|beta)(?: ?\d)?/i.exec(ua + ';' + (useFeatures && nav.appMinorVersion)) || /\bMinefield\b/i.test(ua) && 'a')) {
            prerelease = /b/i.test(data) ? 'beta' : 'alpha';
            version = version.replace(RegExp(data + '\\+?$'), '') + (prerelease == 'beta' ? beta : alpha) + (/\d+\+?/.exec(data) || '');
        }
        if (name == 'Fennec' || name == 'Firefox' && /Android|Firefox OS/.test(os)) {
            name = 'Firefox Mobile';
        } else if (name == 'Maxthon' && version) {
            version = version.replace(/\.[\d.]+/, '.x');
        } else if (name == 'Silk') {
            if (!/Mobi/i.test(ua)) {
                os = 'Android';
                description.unshift('desktop mode');
            }
            if (/Accelerated *= *true/i.test(ua)) {
                description.unshift('accelerated');
            }
        } else if (name == 'IE' && (data = (/; *(?:XBLWP|ZuneWP)(\d+)/i.exec(ua) || 0)[1])) {
            name += ' Mobile';
            os = 'Windows Phone OS ' + data + '.x';
            description.unshift('desktop mode');
        } else if (/Xbox/i.test(product)) {
            os = null;
            if (product == 'Xbox 360' && /IEMobile/.test(ua)) {
                description.unshift('mobile mode');
            }
        } else if ((name == 'Chrome' || name == 'IE' || name && !product && !/Browser|Mobi/.test(name)) && (os == 'Windows CE' || /Mobi/i.test(ua))) {
            name += ' Mobile';
        } else if (name == 'IE' && useFeatures && context.external === null) {
            description.unshift('platform preview');
        } else if ((/BlackBerry/.test(product) || /BB10/.test(ua)) && (data = (RegExp(product.replace(/ +/g, ' *') + '/([.\\d]+)', 'i').exec(ua) || 0)[1] || version)) {
            data = [
                data,
                /BB10/.test(ua)
            ];
            os = (data[1] ? (product = null, manufacturer = 'BlackBerry') : 'Device Software') + ' ' + data[0];
            version = null;
        } else if (this != forOwn && (product != 'Wii' && (useFeatures && opera || /Opera/.test(name) && /\b(?:MSIE|Firefox)\b/i.test(ua) || name == 'Firefox' && /OS X (?:\d+\.){2,}/.test(os) || name == 'IE' && (os && !/^Win/.test(os) && version > 5.5 || /Windows XP/.test(os) && version > 8 || version == 8 && !/Trident/.test(ua)))) && !reOpera.test(data = parse.call(forOwn, ua.replace(reOpera, '') + ';')) && data.name) {
            data = 'ing as ' + data.name + ((data = data.version) ? ' ' + data : '');
            if (reOpera.test(name)) {
                if (/IE/.test(data) && os == 'Mac OS') {
                    os = null;
                }
                data = 'identify' + data;
            } else {
                data = 'mask' + data;
                if (operaClass) {
                    name = format(operaClass.replace(/([a-z])([A-Z])/g, '$1 $2'));
                } else {
                    name = 'Opera';
                }
                if (/IE/.test(data)) {
                    os = null;
                }
                if (!useFeatures) {
                    version = null;
                }
            }
            layout = ['Presto'];
            description.push(data);
        }
        if (data = (/\bAppleWebKit\/([\d.]+\+?)/i.exec(ua) || 0)[1]) {
            data = [
                parseFloat(data.replace(/\.(\d)$/, '.0$1')),
                data
            ];
            if (name == 'Safari' && data[1].slice(-1) == '+') {
                name = 'WebKit Nightly';
                prerelease = 'alpha';
                version = data[1].slice(0, -1);
            } else if (version == data[1] || version == (data[2] = (/\bSafari\/([\d.]+\+?)/i.exec(ua) || 0)[1])) {
                version = null;
            }
            data[1] = (/\bChrome\/([\d.]+)/i.exec(ua) || 0)[1];
            if (data[0] == 537.36 && data[2] == 537.36 && parseFloat(data[1]) >= 28) {
                layout = ['Blink'];
            }
            if (!useFeatures || !likeChrome && !data[1]) {
                layout && (layout[1] = 'like Safari');
                data = (data = data[0], data < 400 ? 1 : data < 500 ? 2 : data < 526 ? 3 : data < 533 ? 4 : data < 534 ? '4+' : data < 535 ? 5 : data < 537 ? 6 : data < 538 ? 7 : '7');
            } else {
                layout && (layout[1] = 'like Chrome');
                data = data[1] || (data = data[0], data < 530 ? 1 : data < 532 ? 2 : data < 532.05 ? 3 : data < 533 ? 4 : data < 534.03 ? 5 : data < 534.07 ? 6 : data < 534.1 ? 7 : data < 534.13 ? 8 : data < 534.16 ? 9 : data < 534.24 ? 10 : data < 534.3 ? 11 : data < 535.01 ? 12 : data < 535.02 ? '13+' : data < 535.07 ? 15 : data < 535.11 ? 16 : data < 535.19 ? 17 : data < 536.05 ? 18 : data < 536.1 ? 19 : data < 537.01 ? 20 : data < 537.11 ? '21+' : data < 537.13 ? 23 : data < 537.18 ? 24 : data < 537.24 ? 25 : data < 537.36 ? 26 : layout != 'Blink' ? '27' : '28');
            }
            layout && (layout[1] += ' ' + (data += typeof data == 'number' ? '.x' : /[.+]/.test(data) ? '' : '+'));
            if (name == 'Safari' && (!version || parseInt(version) > 45)) {
                version = data;
            }
        }
        if (name == 'Opera' && (data = /(?:zbov|zvav)$/.exec(os))) {
            name += ' ';
            description.unshift('desktop mode');
            if (data == 'zvav') {
                name += 'Mini';
                version = null;
            } else {
                name += 'Mobile';
            }
        } else if (name == 'Safari' && /Chrome/.exec(layout && layout[1])) {
            description.unshift('desktop mode');
            name = 'Chrome Mobile';
            version = null;
            if (/OS X/.test(os)) {
                manufacturer = 'Apple';
                os = 'iOS 4.3+';
            } else {
                os = null;
            }
        }
        if (version && version.indexOf(data = /[\d.]+$/.exec(os)) == 0 && ua.indexOf('/' + data + '-') > -1) {
            os = trim(os.replace(data, ''));
        }
        if (layout && !/Avant|Nook/.test(name) && (/Browser|Lunascape|Maxthon/.test(name) || /^(?:Adobe|Arora|Breach|Midori|Opera|Phantom|Rekonq|Rock|Sleipnir|Web)/.test(name) && layout[1])) {
            (data = layout[layout.length - 1]) && description.push(data);
        }
        if (description.length) {
            description = ['(' + description.join('; ') + ')'];
        }
        if (manufacturer && product && product.indexOf(manufacturer) < 0) {
            description.push('on ' + manufacturer);
        }
        if (product) {
            description.push((/^on /.test(description[description.length - 1]) ? '' : 'on ') + product);
        }
        if (os) {
            data = / ([\d.+]+)$/.exec(os);
            isSpecialCasedOS = data && os.charAt(os.length - data[0].length - 1) == '/';
            os = {
                'architecture': 32,
                'family': data && !isSpecialCasedOS ? os.replace(data[0], '') : os,
                'version': data ? data[1] : null,
                'toString': function () {
                    var version = this.version;
                    return this.family + (version && !isSpecialCasedOS ? ' ' + version : '') + (this.architecture == 64 ? ' 64-bit' : '');
                }
            };
        }
        if ((data = /\b(?:AMD|IA|Win|WOW|x86_|x)64\b/i.exec(arch)) && !/\bi686\b/i.test(arch)) {
            if (os) {
                os.architecture = 64;
                os.family = os.family.replace(RegExp(' *' + data), '');
            }
            if (name && (/WOW64/i.test(ua) || useFeatures && /\w(?:86|32)$/.test(nav.cpuClass || nav.platform) && !/^win32$/i.test(nav.platform))) {
                description.unshift('32-bit');
            }
        }
        ua || (ua = null);
        var platform = {};
        platform.description = ua;
        platform.layout = layout && layout[0];
        platform.manufacturer = manufacturer;
        platform.name = name;
        platform.prerelease = prerelease;
        platform.product = product;
        platform.ua = ua;
        platform.version = name && version;
        platform.os = os || {
            'architecture': null,
            'family': null,
            'version': null,
            'toString': function () {
                return 'null';
            }
        };
        platform.parse = parse;
        platform.toString = toStringPlatform;
        if (platform.version) {
            description.unshift(version);
        }
        if (platform.name) {
            description.unshift(name);
        }
        if (os && name && !(os == String(os).split(' ')[0] && (os == name.split(' ')[0] || product))) {
            description.push(product ? '(' + os + ')' : 'on ' + os);
        }
        if (description.length) {
            platform.description = description.join(' ');
        }
        return platform;
    }
    if (typeof define == 'function' && typeof define.amd == 'object' && define.amd) {
        define([], function () {
            return parse();
        });
    } else if (freeExports && freeModule) {
        forOwn(parse(), function (value, key) {
            freeExports[key] = value;
        });
    } else {
        root.platform = parse();
    }
}.call(this));