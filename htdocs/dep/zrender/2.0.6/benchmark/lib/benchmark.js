;
(function () {
    'use strict';
    var undefined;
    var objectTypes = {
            'function': true,
            'object': true
        };
    var root = objectTypes[typeof window] && window || this;
    var freeDefine = typeof define == 'function' && typeof define.amd == 'object' && define.amd && define;
    var freeExports = objectTypes[typeof exports] && exports && !exports.nodeType && exports;
    var freeModule = objectTypes[typeof module] && module && !module.nodeType && module;
    var freeGlobal = freeExports && freeModule && typeof global == 'object' && global;
    if (freeGlobal && (freeGlobal.global === freeGlobal || freeGlobal.window === freeGlobal || freeGlobal.self === freeGlobal)) {
        root = freeGlobal;
    }
    var freeRequire = typeof require == 'function' && require;
    var counter = 0;
    var moduleExports = freeModule && freeModule.exports === freeExports && freeExports;
    var rePrimitive = /^(?:boolean|number|string|undefined)$/;
    var uidCounter = 0;
    var contextProps = [
            'Array',
            'Date',
            'Function',
            'Math',
            'Object',
            'RegExp',
            'String',
            '_',
            'clearTimeout',
            'chrome',
            'chromium',
            'document',
            'java',
            'navigator',
            'phantom',
            'platform',
            'process',
            'runtime',
            'setTimeout'
        ];
    var divisors = {
            '1': 4096,
            '2': 512,
            '3': 64,
            '4': 8,
            '5': 0
        };
    var tTable = {
            '1': 12.706,
            '2': 4.303,
            '3': 3.182,
            '4': 2.776,
            '5': 2.571,
            '6': 2.447,
            '7': 2.365,
            '8': 2.306,
            '9': 2.262,
            '10': 2.228,
            '11': 2.201,
            '12': 2.179,
            '13': 2.16,
            '14': 2.145,
            '15': 2.131,
            '16': 2.12,
            '17': 2.11,
            '18': 2.101,
            '19': 2.093,
            '20': 2.086,
            '21': 2.08,
            '22': 2.074,
            '23': 2.069,
            '24': 2.064,
            '25': 2.06,
            '26': 2.056,
            '27': 2.052,
            '28': 2.048,
            '29': 2.045,
            '30': 2.042,
            'infinity': 1.96
        };
    var uTable = {
            '5': [
                0,
                1,
                2
            ],
            '6': [
                1,
                2,
                3,
                5
            ],
            '7': [
                1,
                3,
                5,
                6,
                8
            ],
            '8': [
                2,
                4,
                6,
                8,
                10,
                13
            ],
            '9': [
                2,
                4,
                7,
                10,
                12,
                15,
                17
            ],
            '10': [
                3,
                5,
                8,
                11,
                14,
                17,
                20,
                23
            ],
            '11': [
                3,
                6,
                9,
                13,
                16,
                19,
                23,
                26,
                30
            ],
            '12': [
                4,
                7,
                11,
                14,
                18,
                22,
                26,
                29,
                33,
                37
            ],
            '13': [
                4,
                8,
                12,
                16,
                20,
                24,
                28,
                33,
                37,
                41,
                45
            ],
            '14': [
                5,
                9,
                13,
                17,
                22,
                26,
                31,
                36,
                40,
                45,
                50,
                55
            ],
            '15': [
                5,
                10,
                14,
                19,
                24,
                29,
                34,
                39,
                44,
                49,
                54,
                59,
                64
            ],
            '16': [
                6,
                11,
                15,
                21,
                26,
                31,
                37,
                42,
                47,
                53,
                59,
                64,
                70,
                75
            ],
            '17': [
                6,
                11,
                17,
                22,
                28,
                34,
                39,
                45,
                51,
                57,
                63,
                67,
                75,
                81,
                87
            ],
            '18': [
                7,
                12,
                18,
                24,
                30,
                36,
                42,
                48,
                55,
                61,
                67,
                74,
                80,
                86,
                93,
                99
            ],
            '19': [
                7,
                13,
                19,
                25,
                32,
                38,
                45,
                52,
                58,
                65,
                72,
                78,
                85,
                92,
                99,
                106,
                113
            ],
            '20': [
                8,
                14,
                20,
                27,
                34,
                41,
                48,
                55,
                62,
                69,
                76,
                83,
                90,
                98,
                105,
                112,
                119,
                127
            ],
            '21': [
                8,
                15,
                22,
                29,
                36,
                43,
                50,
                58,
                65,
                73,
                80,
                88,
                96,
                103,
                111,
                119,
                126,
                134,
                142
            ],
            '22': [
                9,
                16,
                23,
                30,
                38,
                45,
                53,
                61,
                69,
                77,
                85,
                93,
                101,
                109,
                117,
                125,
                133,
                141,
                150,
                158
            ],
            '23': [
                9,
                17,
                24,
                32,
                40,
                48,
                56,
                64,
                73,
                81,
                89,
                98,
                106,
                115,
                123,
                132,
                140,
                149,
                157,
                166,
                175
            ],
            '24': [
                10,
                17,
                25,
                33,
                42,
                50,
                59,
                67,
                76,
                85,
                94,
                102,
                111,
                120,
                129,
                138,
                147,
                156,
                165,
                174,
                183,
                192
            ],
            '25': [
                10,
                18,
                27,
                35,
                44,
                53,
                62,
                71,
                80,
                89,
                98,
                107,
                117,
                126,
                135,
                145,
                154,
                163,
                173,
                182,
                192,
                201,
                211
            ],
            '26': [
                11,
                19,
                28,
                37,
                46,
                55,
                64,
                74,
                83,
                93,
                102,
                112,
                122,
                132,
                141,
                151,
                161,
                171,
                181,
                191,
                200,
                210,
                220,
                230
            ],
            '27': [
                11,
                20,
                29,
                38,
                48,
                57,
                67,
                77,
                87,
                97,
                107,
                118,
                125,
                138,
                147,
                158,
                168,
                178,
                188,
                199,
                209,
                219,
                230,
                240,
                250
            ],
            '28': [
                12,
                21,
                30,
                40,
                50,
                60,
                70,
                80,
                90,
                101,
                111,
                122,
                132,
                143,
                154,
                164,
                175,
                186,
                196,
                207,
                218,
                228,
                239,
                250,
                261,
                272
            ],
            '29': [
                13,
                22,
                32,
                42,
                52,
                62,
                73,
                83,
                94,
                105,
                116,
                127,
                138,
                149,
                160,
                171,
                182,
                193,
                204,
                215,
                226,
                238,
                249,
                260,
                271,
                282,
                294
            ],
            '30': [
                13,
                23,
                33,
                43,
                54,
                65,
                76,
                87,
                98,
                109,
                120,
                131,
                143,
                154,
                166,
                177,
                189,
                200,
                212,
                223,
                235,
                247,
                258,
                270,
                282,
                293,
                305,
                317
            ]
        };
    function runInContext(context) {
        var _ = context && context._ || req('lodash') || root._;
        if (!_) {
            Benchmark.runInContext = runInContext;
            return Benchmark;
        }
        context = context ? _.defaults(root.Object(), context, _.pick(root, contextProps)) : root;
        var Array = context.Array, Date = context.Date, Function = context.Function, Math = context.Math, Object = context.Object, RegExp = context.RegExp, String = context.String;
        var arrayRef = [], objectProto = Object.prototype;
        var abs = Math.abs, clearTimeout = context.clearTimeout, floor = Math.floor, log = Math.log, max = Math.max, min = Math.min, pow = Math.pow, push = arrayRef.push, setTimeout = context.setTimeout, shift = arrayRef.shift, slice = arrayRef.slice, sqrt = Math.sqrt, toString = objectProto.toString, unshift = arrayRef.unshift;
        var doc = isHostType(context, 'document') && context.document;
        var microtimeObject = req('microtime');
        var processObject = isHostType(context, 'process') && context.process;
        var trash = doc && doc.createElement('div');
        var uid = 'uid' + _.now();
        var calledBy = {};
        var support = {};
        (function () {
            support.browser = doc && isHostType(context, 'navigator') && !isHostType(context, 'phantom');
            support.java = isClassOf(context.java, 'JavaPackage');
            support.timeout = isHostType(context, 'setTimeout') && isHostType(context, 'clearTimeout');
            support.unshiftResult = !![].unshift(1);
            try {
                support.decompilation = Function(('return (' + function (x) {
                    return {
                        'x': '' + (1 + x) + '',
                        'y': 0
                    };
                } + ')').replace(/__cov__[^;]+;/g, ''))()(0).x === '1';
            } catch (e) {
                support.decompilation = false;
            }
        }());
        var timer = {
                'ns': Date,
                'start': null,
                'stop': null
            };
        function Benchmark(name, fn, options) {
            var bench = this;
            if (bench == null || bench.constructor != Benchmark) {
                return new Benchmark(name, fn, options);
            }
            if (_.isPlainObject(name)) {
                options = name;
            } else if (_.isFunction(name)) {
                options = fn;
                fn = name;
            } else if (_.isPlainObject(fn)) {
                options = fn;
                fn = null;
                bench.name = name;
            } else {
                bench.name = name;
            }
            setOptions(bench, options);
            bench.id || (bench.id = ++counter);
            bench.fn == null && (bench.fn = fn);
            bench.stats = cloneDeep(bench.stats);
            bench.times = cloneDeep(bench.times);
        }
        function Deferred(clone) {
            var deferred = this;
            if (deferred == null || deferred.constructor != Deferred) {
                return new Deferred(clone);
            }
            deferred.benchmark = clone;
            clock(deferred);
        }
        function Event(type) {
            var event = this;
            if (type instanceof Event) {
                return type;
            }
            return event == null || event.constructor != Event ? new Event(type) : _.assign(event, { 'timeStamp': _.now() }, typeof type == 'string' ? { 'type': type } : type);
        }
        function Suite(name, options) {
            var suite = this;
            if (suite == null || suite.constructor != Suite) {
                return new Suite(name, options);
            }
            if (_.isPlainObject(name)) {
                options = name;
            } else {
                suite.name = name;
            }
            setOptions(suite, options);
        }
        var cloneDeep = _.partialRight(_.cloneDeep, function (value) {
                return typeof value == 'object' && !_.isArray(value) && !_.isPlainObject(value) ? value : undefined;
            });
        function createFunction() {
            createFunction = function (args, body) {
                var result, anchor = freeDefine ? freeDefine.amd : Benchmark, prop = uid + 'createFunction';
                runScript((freeDefine ? 'define.amd.' : 'Benchmark.') + prop + '=function(' + args + '){' + body + '}');
                result = anchor[prop];
                delete anchor[prop];
                return result;
            };
            createFunction = support.browser && (createFunction('', 'return"' + uid + '"') || _.noop)() == uid ? createFunction : Function;
            return createFunction.apply(null, arguments);
        }
        function delay(bench, fn) {
            bench._timerId = _.delay(fn, bench.delay * 1000);
        }
        function destroyElement(element) {
            trash.appendChild(element);
            trash.innerHTML = '';
        }
        function getFirstArgument(fn) {
            return !_.has(fn, 'toString') && (/^[\s(]*function[^(]*\(([^\s,)]+)/.exec(fn) || 0)[1] || '';
        }
        function getMean(sample) {
            return _.reduce(sample, function (sum, x) {
                return sum + x;
            }) / sample.length || 0;
        }
        function getSource(fn) {
            var result = '';
            if (isStringable(fn)) {
                result = String(fn);
            } else if (support.decompilation) {
                result = _.result(/^[^{]+\{([\s\S]*)\}\s*$/.exec(fn), 1);
            }
            result = (result || '').replace(/^\s+|\s+$/g, '');
            return /^(?:\/\*+[\w\W]*?\*\/|\/\/.*?[\n\r\u2028\u2029]|\s)*(["'])use strict\1;?$/.test(result) ? '' : result;
        }
        function isClassOf(value, name) {
            return value != null && toString.call(value) == '[object ' + name + ']';
        }
        function isHostType(object, property) {
            if (object == null) {
                return false;
            }
            var type = typeof object[property];
            return !rePrimitive.test(type) && (type != 'object' || !!object[property]);
        }
        function isStringable(value) {
            return _.isString(value) || _.has(value, 'toString') && _.isFunction(value.toString);
        }
        function req(id) {
            try {
                var result = freeExports && freeRequire(id);
            } catch (e) {
            }
            return result || null;
        }
        function runScript(code) {
            var anchor = freeDefine ? define.amd : Benchmark, script = doc.createElement('script'), sibling = doc.getElementsByTagName('script')[0], parent = sibling.parentNode, prop = uid + 'runScript', prefix = '(' + (freeDefine ? 'define.amd.' : 'Benchmark.') + prop + '||function(){})();';
            try {
                script.appendChild(doc.createTextNode(prefix + code));
                anchor[prop] = function () {
                    destroyElement(script);
                };
            } catch (e) {
                parent = parent.cloneNode(false);
                sibling = null;
                script.text = code;
            }
            parent.insertBefore(script, sibling);
            delete anchor[prop];
        }
        function setOptions(object, options) {
            options = object.options = _.assign({}, cloneDeep(object.constructor.options), cloneDeep(options));
            _.forOwn(options, function (value, key) {
                if (value != null) {
                    if (/^on[A-Z]/.test(key)) {
                        _.each(key.split(' '), function (key) {
                            object.on(key.slice(2).toLowerCase(), value);
                        });
                    } else if (!_.has(object, key)) {
                        object[key] = cloneDeep(value);
                    }
                }
            });
        }
        function resolve() {
            var deferred = this, clone = deferred.benchmark, bench = clone._original;
            if (bench.aborted) {
                deferred.teardown();
                clone.running = false;
                cycle(deferred);
            } else if (++deferred.cycles < clone.count) {
                clone.compiled.call(deferred, context, timer);
            } else {
                timer.stop(deferred);
                deferred.teardown();
                delay(clone, function () {
                    cycle(deferred);
                });
            }
        }
        function filter(array, callback, thisArg) {
            if (callback === 'successful') {
                callback = function (bench) {
                    return bench.cycles && _.isFinite(bench.hz);
                };
            } else if (callback === 'fastest' || callback === 'slowest') {
                var result = filter(array, 'successful').sort(function (a, b) {
                        a = a.stats;
                        b = b.stats;
                        return (a.mean + a.moe > b.mean + b.moe ? 1 : -1) * (callback === 'fastest' ? 1 : -1);
                    });
                return _.filter(result, function (bench) {
                    return result[0].compare(bench) == 0;
                });
            }
            return _.filter(array, callback, thisArg);
        }
        function formatNumber(number) {
            number = String(number).split('.');
            return number[0].replace(/(?=(?:\d{3})+$)(?!\b)/g, ',') + (number[1] ? '.' + number[1] : '');
        }
        function invoke(benches, name) {
            var args, bench, queued, index = -1, eventProps = { 'currentTarget': benches }, options = {
                    'onStart': _.noop,
                    'onCycle': _.noop,
                    'onComplete': _.noop
                }, result = _.toArray(benches);
            function execute() {
                var listeners, async = isAsync(bench);
                if (async) {
                    bench.on('complete', getNext);
                    listeners = bench.events.complete;
                    listeners.splice(0, 0, listeners.pop());
                }
                result[index] = _.isFunction(bench && bench[name]) ? bench[name].apply(bench, args) : undefined;
                return !async && getNext();
            }
            function getNext(event) {
                var cycleEvent, last = bench, async = isAsync(last);
                if (async) {
                    last.off('complete', getNext);
                    last.emit('complete');
                }
                eventProps.type = 'cycle';
                eventProps.target = last;
                cycleEvent = Event(eventProps);
                options.onCycle.call(benches, cycleEvent);
                if (!cycleEvent.aborted && raiseIndex() !== false) {
                    bench = queued ? benches[0] : result[index];
                    if (isAsync(bench)) {
                        delay(bench, execute);
                    } else if (async) {
                        while (execute()) {
                        }
                    } else {
                        return true;
                    }
                } else {
                    eventProps.type = 'complete';
                    options.onComplete.call(benches, Event(eventProps));
                }
                if (event) {
                    event.aborted = true;
                } else {
                    return false;
                }
            }
            function isAsync(object) {
                var async = args[0] && args[0].async;
                return Object(object).constructor == Benchmark && name == 'run' && ((async == null ? object.options.async : async) && support.timeout || object.defer);
            }
            function raiseIndex() {
                index++;
                if (queued && index > 0) {
                    shift.call(benches);
                }
                return (queued ? benches.length : index < result.length) ? index : index = false;
            }
            if (_.isString(name)) {
                args = slice.call(arguments, 2);
            } else {
                options = _.assign(options, name);
                name = options.name;
                args = _.isArray(args = 'args' in options ? options.args : []) ? args : [args];
                queued = options.queued;
            }
            if (raiseIndex() !== false) {
                bench = result[index];
                eventProps.type = 'start';
                eventProps.target = bench;
                options.onStart.call(benches, Event(eventProps));
                if (benches.aborted && benches.constructor == Suite && name == 'run') {
                    eventProps.type = 'cycle';
                    options.onCycle.call(benches, Event(eventProps));
                    eventProps.type = 'complete';
                    options.onComplete.call(benches, Event(eventProps));
                } else {
                    if (isAsync(bench)) {
                        delay(bench, execute);
                    } else {
                        while (execute()) {
                        }
                    }
                }
            }
            return result;
        }
        function join(object, separator1, separator2) {
            var result = [], length = (object = Object(object)).length, arrayLike = length === length >>> 0;
            separator2 || (separator2 = ': ');
            _.each(object, function (value, key) {
                result.push(arrayLike ? value : key + separator2 + value);
            });
            return result.join(separator1 || ',');
        }
        function abortSuite() {
            var event, suite = this, resetting = calledBy.resetSuite;
            if (suite.running) {
                event = Event('abort');
                suite.emit(event);
                if (!event.cancelled || resetting) {
                    calledBy.abortSuite = true;
                    suite.reset();
                    delete calledBy.abortSuite;
                    if (!resetting) {
                        suite.aborted = true;
                        invoke(suite, 'abort');
                    }
                }
            }
            return suite;
        }
        function add(name, fn, options) {
            var suite = this, bench = new Benchmark(name, fn, options), event = Event({
                    'type': 'add',
                    'target': bench
                });
            if (suite.emit(event), !event.cancelled) {
                suite.push(bench);
            }
            return suite;
        }
        function cloneSuite(options) {
            var suite = this, result = new suite.constructor(_.assign({}, suite.options, options));
            _.forOwn(suite, function (value, key) {
                if (!_.has(result, key)) {
                    result[key] = value && _.isFunction(value.clone) ? value.clone() : cloneDeep(value);
                }
            });
            return result;
        }
        function filterSuite(callback) {
            var suite = this, result = new suite.constructor(suite.options);
            result.push.apply(result, filter(suite, callback));
            return result;
        }
        function resetSuite() {
            var event, suite = this, aborting = calledBy.abortSuite;
            if (suite.running && !aborting) {
                calledBy.resetSuite = true;
                suite.abort();
                delete calledBy.resetSuite;
            } else if ((suite.aborted || suite.running) && (suite.emit(event = Event('reset')), !event.cancelled)) {
                suite.aborted = suite.running = false;
                if (!aborting) {
                    invoke(suite, 'reset');
                }
            }
            return suite;
        }
        function runSuite(options) {
            var suite = this;
            suite.reset();
            suite.running = true;
            options || (options = {});
            invoke(suite, {
                'name': 'run',
                'args': options,
                'queued': options.queued,
                'onStart': function (event) {
                    suite.emit(event);
                },
                'onCycle': function (event) {
                    var bench = event.target;
                    if (bench.error) {
                        suite.emit({
                            'type': 'error',
                            'target': bench
                        });
                    }
                    suite.emit(event);
                    event.aborted = suite.aborted;
                },
                'onComplete': function (event) {
                    suite.running = false;
                    suite.emit(event);
                }
            });
            return suite;
        }
        function emit(type) {
            var listeners, object = this, event = Event(type), events = object.events, args = (arguments[0] = event, arguments);
            event.currentTarget || (event.currentTarget = object);
            event.target || (event.target = object);
            delete event.result;
            if (events && (listeners = _.has(events, event.type) && events[event.type])) {
                _.each(listeners.slice(), function (listener) {
                    if ((event.result = listener.apply(object, args)) === false) {
                        event.cancelled = true;
                    }
                    return !event.aborted;
                });
            }
            return event.result;
        }
        function listeners(type) {
            var object = this, events = object.events || (object.events = {});
            return _.has(events, type) ? events[type] : events[type] = [];
        }
        function off(type, listener) {
            var object = this, events = object.events;
            if (!events) {
                return object;
            }
            _.each(type ? type.split(' ') : events, function (listeners, type) {
                var index;
                if (typeof listeners == 'string') {
                    type = listeners;
                    listeners = _.has(events, type) && events[type];
                }
                if (listeners) {
                    if (listener) {
                        index = _.indexOf(listeners, listener);
                        if (index > -1) {
                            listeners.splice(index, 1);
                        }
                    } else {
                        listeners.length = 0;
                    }
                }
            });
            return object;
        }
        function on(type, listener) {
            var object = this, events = object.events || (object.events = {});
            _.each(type.split(' '), function (type) {
                (_.has(events, type) ? events[type] : events[type] = []).push(listener);
            });
            return object;
        }
        function abort() {
            var event, bench = this, resetting = calledBy.reset;
            if (bench.running) {
                event = Event('abort');
                bench.emit(event);
                if (!event.cancelled || resetting) {
                    calledBy.abort = true;
                    bench.reset();
                    delete calledBy.abort;
                    if (support.timeout) {
                        clearTimeout(bench._timerId);
                        delete bench._timerId;
                    }
                    if (!resetting) {
                        bench.aborted = true;
                        bench.running = false;
                    }
                }
            }
            return bench;
        }
        function clone(options) {
            var bench = this, result = new bench.constructor(_.assign({}, bench, options));
            result.options = _.assign({}, cloneDeep(bench.options), cloneDeep(options));
            _.forOwn(bench, function (value, key) {
                if (!_.has(result, key)) {
                    result[key] = cloneDeep(value);
                }
            });
            return result;
        }
        function compare(other) {
            var critical, zStat, bench = this, sample1 = bench.stats.sample, sample2 = other.stats.sample, size1 = sample1.length, size2 = sample2.length, maxSize = max(size1, size2), minSize = min(size1, size2), u1 = getU(sample1, sample2), u2 = getU(sample2, sample1), u = min(u1, u2);
            function getScore(xA, sampleB) {
                return _.reduce(sampleB, function (total, xB) {
                    return total + (xB > xA ? 0 : xB < xA ? 1 : 0.5);
                }, 0);
            }
            function getU(sampleA, sampleB) {
                return _.reduce(sampleA, function (total, xA) {
                    return total + getScore(xA, sampleB);
                }, 0);
            }
            function getZ(u) {
                return (u - size1 * size2 / 2) / sqrt(size1 * size2 * (size1 + size2 + 1) / 12);
            }
            if (bench == other) {
                return 0;
            }
            if (size1 + size2 > 30) {
                zStat = getZ(u);
                return abs(zStat) > 1.96 ? u == u1 ? 1 : -1 : 0;
            }
            critical = maxSize < 5 || minSize < 3 ? 0 : uTable[maxSize][minSize - 3];
            return u <= critical ? u == u1 ? 1 : -1 : 0;
        }
        function reset() {
            var bench = this;
            if (bench.running && !calledBy.abort) {
                calledBy.reset = true;
                bench.abort();
                delete calledBy.reset;
                return bench;
            }
            var event, index = 0, changes = { 'length': 0 }, queue = { 'length': 0 };
            var data = {
                    'destination': bench,
                    'source': _.assign({}, cloneDeep(bench.constructor.prototype), cloneDeep(bench.options))
                };
            do {
                _.forOwn(data.source, function (value, key) {
                    var changed, destination = data.destination, currValue = destination[key];
                    if (key.charAt(0) == '_') {
                        return;
                    }
                    if (value && typeof value == 'object') {
                        if (_.isArray(value)) {
                            if (!_.isArray(currValue)) {
                                changed = currValue = [];
                            }
                            if (currValue.length != value.length) {
                                changed = currValue = currValue.slice(0, value.length);
                                currValue.length = value.length;
                            }
                        } else if (!currValue || typeof currValue != 'object') {
                            changed = currValue = {};
                        }
                        if (changed) {
                            changes[changes.length++] = {
                                'destination': destination,
                                'key': key,
                                'value': currValue
                            };
                        }
                        queue[queue.length++] = {
                            'destination': currValue,
                            'source': value
                        };
                    } else if (value !== currValue && !(value == null || _.isFunction(value))) {
                        changes[changes.length++] = {
                            'destination': destination,
                            'key': key,
                            'value': value
                        };
                    }
                });
            } while (data = queue[index++]);
            if (changes.length && (bench.emit(event = Event('reset')), !event.cancelled)) {
                _.each(changes, function (data) {
                    data.destination[data.key] = data.value;
                });
            }
            return bench;
        }
        function toStringBench() {
            var bench = this, error = bench.error, hz = bench.hz, id = bench.id, stats = bench.stats, size = stats.sample.length, pm = support.java ? '+/-' : '\xB1', result = bench.name || (_.isNaN(id) ? id : '<Test #' + id + '>');
            if (error) {
                result += ': ' + join(error);
            } else {
                result += ' x ' + formatNumber(hz.toFixed(hz < 100 ? 2 : 0)) + ' ops/sec ' + pm + stats.rme.toFixed(2) + '% (' + size + ' run' + (size == 1 ? '' : 's') + ' sampled)';
            }
            return result;
        }
        function clock() {
            var applet, options = Benchmark.options, templateData = {}, timers = [{
                        'ns': timer.ns,
                        'res': max(0.0015, getRes('ms')),
                        'unit': 'ms'
                    }];
            clock = function (clone) {
                var deferred;
                if (clone instanceof Deferred) {
                    deferred = clone;
                    clone = deferred.benchmark;
                }
                var bench = clone._original, stringable = isStringable(bench.fn), count = bench.count = clone.count, decompilable = stringable || support.decompilation && (clone.setup !== _.noop || clone.teardown !== _.noop), id = bench.id, name = bench.name || (typeof id == 'number' ? '<Test #' + id + '>' : id), result = 0;
                clone.minTime = bench.minTime || (bench.minTime = bench.options.minTime = options.minTime);
                if (applet) {
                    try {
                        timer.ns.nanoTime();
                    } catch (e) {
                        timer.ns = new applet.Packages.nano();
                    }
                }
                var funcBody = deferred ? 'var d#=this,${fnArg}=d#,m#=d#.benchmark._original,f#=m#.fn,su#=m#.setup,td#=m#.teardown;' + 'if(!d#.cycles){' + 'd#.fn=function(){var ${fnArg}=d#;if(typeof f#=="function"){try{${fn}\n}catch(e#){f#(d#)}}else{${fn}\n}};' + 'd#.teardown=function(){d#.cycles=0;if(typeof td#=="function"){try{${teardown}\n}catch(e#){td#()}}else{${teardown}\n}};' + 'if(typeof su#=="function"){try{${setup}\n}catch(e#){su#()}}else{${setup}\n};' + 't#.start(d#);' + '}d#.fn();return{uid:"${uid}"}' : 'var r#,s#,m#=this,f#=m#.fn,i#=m#.count,n#=t#.ns;${setup}\n${begin};' + 'while(i#--){${fn}\n}${end};${teardown}\nreturn{elapsed:r#,uid:"${uid}"}';
                var compiled = bench.compiled = clone.compiled = createCompiled(bench, decompilable, deferred, funcBody), isEmpty = !(templateData.fn || stringable);
                try {
                    if (isEmpty) {
                        throw new Error('The test "' + name + '" is empty. This may be the result of dead code removal.');
                    } else if (!deferred) {
                        bench.count = 1;
                        compiled = decompilable && (compiled.call(bench, context, timer) || {}).uid == templateData.uid && compiled;
                        bench.count = count;
                    }
                } catch (e) {
                    compiled = null;
                    clone.error = e || new Error(String(e));
                    bench.count = count;
                }
                if (!compiled && !deferred && !isEmpty) {
                    funcBody = (stringable || decompilable && !clone.error ? 'function f#(){${fn}\n}var r#,s#,m#=this,i#=m#.count' : 'var r#,s#,m#=this,f#=m#.fn,i#=m#.count') + ',n#=t#.ns;${setup}\n${begin};m#.f#=f#;while(i#--){m#.f#()}${end};' + 'delete m#.f#;${teardown}\nreturn{elapsed:r#}';
                    compiled = createCompiled(bench, decompilable, deferred, funcBody);
                    try {
                        bench.count = 1;
                        compiled.call(bench, context, timer);
                        bench.count = count;
                        delete clone.error;
                    } catch (e) {
                        bench.count = count;
                        if (!clone.error) {
                            clone.error = e || new Error(String(e));
                        }
                    }
                }
                if (!clone.error) {
                    compiled = bench.compiled = clone.compiled = createCompiled(bench, decompilable, deferred, funcBody);
                    result = compiled.call(deferred || bench, context, timer).elapsed;
                }
                return result;
            };
            function createCompiled(bench, decompilable, deferred, body) {
                var fn = bench.fn, fnArg = deferred ? getFirstArgument(fn) || 'deferred' : '';
                templateData.uid = uid + uidCounter++;
                _.assign(templateData, {
                    'setup': decompilable ? getSource(bench.setup) : interpolate('m#.setup()'),
                    'fn': decompilable ? getSource(fn) : interpolate('m#.fn(' + fnArg + ')'),
                    'fnArg': fnArg,
                    'teardown': decompilable ? getSource(bench.teardown) : interpolate('m#.teardown()')
                });
                if (timer.unit == 'ns') {
                    if (timer.ns.nanoTime) {
                        _.assign(templateData, {
                            'begin': interpolate('s#=n#.nanoTime()'),
                            'end': interpolate('r#=(n#.nanoTime()-s#)/1e9')
                        });
                    } else {
                        _.assign(templateData, {
                            'begin': interpolate('s#=n#()'),
                            'end': interpolate('r#=n#(s#);r#=r#[0]+(r#[1]/1e9)')
                        });
                    }
                } else if (timer.unit == 'us') {
                    if (timer.ns.stop) {
                        _.assign(templateData, {
                            'begin': interpolate('s#=n#.start()'),
                            'end': interpolate('r#=n#.microseconds()/1e6')
                        });
                    } else {
                        _.assign(templateData, {
                            'begin': interpolate('s#=n#()'),
                            'end': interpolate('r#=(n#()-s#)/1e6')
                        });
                    }
                } else if (timer.ns.now) {
                    _.assign(templateData, {
                        'begin': interpolate('s#=n#.now()'),
                        'end': interpolate('r#=(n#.now()-s#)/1e3')
                    });
                } else {
                    _.assign(templateData, {
                        'begin': interpolate('s#=new n#().getTime()'),
                        'end': interpolate('r#=(new n#().getTime()-s#)/1e3')
                    });
                }
                timer.start = createFunction(interpolate('o#'), interpolate('var n#=this.ns,${begin};o#.elapsed=0;o#.timeStamp=s#'));
                timer.stop = createFunction(interpolate('o#'), interpolate('var n#=this.ns,s#=o#.timeStamp,${end};o#.elapsed=r#'));
                return createFunction(interpolate('window,t#'), 'var global = window, clearTimeout = global.clearTimeout, setTimeout = global.setTimeout;\n' + interpolate(body));
            }
            function getRes(unit) {
                var measured, begin, count = 30, divisor = 1000, ns = timer.ns, sample = [];
                while (count--) {
                    if (unit == 'us') {
                        divisor = 1000000;
                        if (ns.stop) {
                            ns.start();
                            while (!(measured = ns.microseconds())) {
                            }
                        } else {
                            begin = ns();
                            while (!(measured = ns() - begin)) {
                            }
                        }
                    } else if (unit == 'ns') {
                        divisor = 1000000000;
                        if (ns.nanoTime) {
                            begin = ns.nanoTime();
                            while (!(measured = ns.nanoTime() - begin)) {
                            }
                        } else {
                            begin = (begin = ns())[0] + begin[1] / divisor;
                            while (!(measured = (measured = ns())[0] + measured[1] / divisor - begin)) {
                            }
                            divisor = 1;
                        }
                    } else if (ns.now) {
                        begin = ns.now();
                        while (!(measured = ns.now() - begin)) {
                        }
                    } else {
                        begin = new ns().getTime();
                        while (!(measured = new ns().getTime() - begin)) {
                        }
                    }
                    if (measured > 0) {
                        sample.push(measured);
                    } else {
                        sample.push(Infinity);
                        break;
                    }
                }
                return getMean(sample) / divisor;
            }
            function interpolate(string) {
                return _.template(string.replace(/\#/g, /\d+/.exec(templateData.uid)))(templateData);
            }
            _.each(doc && doc.applets || [], function (element) {
                return !(timer.ns = applet = 'nanoTime' in element && element);
            });
            try {
                if (typeof timer.ns.nanoTime() == 'number') {
                    timers.push({
                        'ns': timer.ns,
                        'res': getRes('ns'),
                        'unit': 'ns'
                    });
                }
            } catch (e) {
            }
            try {
                if (timer.ns = new (context.chrome || context.chromium).Interval()) {
                    timers.push({
                        'ns': timer.ns,
                        'res': getRes('us'),
                        'unit': 'us'
                    });
                }
            } catch (e) {
            }
            if (processObject && typeof (timer.ns = processObject.hrtime) == 'function') {
                timers.push({
                    'ns': timer.ns,
                    'res': getRes('ns'),
                    'unit': 'ns'
                });
            }
            if (microtimeObject && typeof (timer.ns = microtimeObject.now) == 'function') {
                timers.push({
                    'ns': timer.ns,
                    'res': getRes('us'),
                    'unit': 'us'
                });
            }
            timer = _.min(timers, 'res');
            if (timer.unit != 'ns' && applet) {
                applet = destroyElement(applet);
            }
            if (timer.res == Infinity) {
                throw new Error('Benchmark.js was unable to find a working timer.');
            }
            options.minTime || (options.minTime = max(timer.res / 2 / 0.01, 0.05));
            return clock.apply(null, arguments);
        }
        function compute(bench, options) {
            options || (options = {});
            var async = options.async, elapsed = 0, initCount = bench.initCount, minSamples = bench.minSamples, queue = [], sample = bench.stats.sample;
            function enqueue() {
                queue.push(bench.clone({
                    '_original': bench,
                    'events': {
                        'abort': [update],
                        'cycle': [update],
                        'error': [update],
                        'start': [update]
                    }
                }));
            }
            function update(event) {
                var clone = this, type = event.type;
                if (bench.running) {
                    if (type == 'start') {
                        clone.count = bench.initCount;
                    } else {
                        if (type == 'error') {
                            bench.error = clone.error;
                        }
                        if (type == 'abort') {
                            bench.abort();
                            bench.emit('cycle');
                        } else {
                            event.currentTarget = event.target = bench;
                            bench.emit(event);
                        }
                    }
                } else if (bench.aborted) {
                    clone.events.abort.length = 0;
                    clone.abort();
                }
            }
            function evaluate(event) {
                var critical, df, mean, moe, rme, sd, sem, variance, clone = event.target, done = bench.aborted, now = _.now(), size = sample.push(clone.times.period), maxedOut = size >= minSamples && (elapsed += now - clone.times.timeStamp) / 1000 > bench.maxTime, times = bench.times, varOf = function (sum, x) {
                        return sum + pow(x - mean, 2);
                    };
                if (done || clone.hz == Infinity) {
                    maxedOut = !(size = sample.length = queue.length = 0);
                }
                if (!done) {
                    mean = getMean(sample);
                    variance = _.reduce(sample, varOf, 0) / (size - 1) || 0;
                    sd = sqrt(variance);
                    sem = sd / sqrt(size);
                    df = size - 1;
                    critical = tTable[Math.round(df) || 1] || tTable.infinity;
                    moe = sem * critical;
                    rme = moe / mean * 100 || 0;
                    _.assign(bench.stats, {
                        'deviation': sd,
                        'mean': mean,
                        'moe': moe,
                        'rme': rme,
                        'sem': sem,
                        'variance': variance
                    });
                    if (maxedOut) {
                        bench.initCount = initCount;
                        bench.running = false;
                        done = true;
                        times.elapsed = (now - times.timeStamp) / 1000;
                    }
                    if (bench.hz != Infinity) {
                        bench.hz = 1 / mean;
                        times.cycle = mean * bench.count;
                        times.period = mean;
                    }
                }
                if (queue.length < 2 && !maxedOut) {
                    enqueue();
                }
                event.aborted = done;
            }
            enqueue();
            invoke(queue, {
                'name': 'run',
                'args': { 'async': async },
                'queued': true,
                'onCycle': evaluate,
                'onComplete': function () {
                    bench.emit('complete');
                }
            });
        }
        function cycle(clone, options) {
            options || (options = {});
            var deferred;
            if (clone instanceof Deferred) {
                deferred = clone;
                clone = clone.benchmark;
            }
            var clocked, cycles, divisor, event, minTime, period, async = options.async, bench = clone._original, count = clone.count, times = clone.times;
            if (clone.running) {
                cycles = ++clone.cycles;
                clocked = deferred ? deferred.elapsed : clock(clone);
                minTime = clone.minTime;
                if (cycles > bench.cycles) {
                    bench.cycles = cycles;
                }
                if (clone.error) {
                    event = Event('error');
                    event.message = clone.error;
                    clone.emit(event);
                    if (!event.cancelled) {
                        clone.abort();
                    }
                }
            }
            if (clone.running) {
                bench.times.cycle = times.cycle = clocked;
                period = bench.times.period = times.period = clocked / count;
                bench.hz = clone.hz = 1 / period;
                bench.initCount = clone.initCount = count;
                clone.running = clocked < minTime;
                if (clone.running) {
                    if (!clocked && (divisor = divisors[clone.cycles]) != null) {
                        count = floor(4000000 / divisor);
                    }
                    if (count <= clone.count) {
                        count += Math.ceil((minTime - clocked) / period);
                    }
                    clone.running = count != Infinity;
                }
            }
            event = Event('cycle');
            clone.emit(event);
            if (event.aborted) {
                clone.abort();
            }
            if (clone.running) {
                clone.count = count;
                if (deferred) {
                    clone.compiled.call(deferred, context, timer);
                } else if (async) {
                    delay(clone, function () {
                        cycle(clone, options);
                    });
                } else {
                    cycle(clone);
                }
            } else {
                if (support.browser) {
                    runScript(uid + '=1;delete ' + uid);
                }
                clone.emit('complete');
            }
        }
        function run(options) {
            var bench = this, event = Event('start');
            bench.running = false;
            bench.reset();
            bench.running = true;
            bench.count = bench.initCount;
            bench.times.timeStamp = _.now();
            bench.emit(event);
            if (!event.cancelled) {
                options = { 'async': ((options = options && options.async) == null ? bench.async : options) && support.timeout };
                if (bench._original) {
                    if (bench.defer) {
                        Deferred(bench);
                    } else {
                        cycle(bench, options);
                    }
                } else {
                    compute(bench, options);
                }
            }
            return bench;
        }
        _.assign(Benchmark, {
            'options': {
                'async': false,
                'defer': false,
                'delay': 0.005,
                'id': undefined,
                'initCount': 1,
                'maxTime': 5,
                'minSamples': 5,
                'minTime': 0,
                'name': undefined,
                'onAbort': undefined,
                'onComplete': undefined,
                'onCycle': undefined,
                'onError': undefined,
                'onReset': undefined,
                'onStart': undefined
            },
            'platform': context.platform || req('platform') || {
                'description': context.navigator && context.navigator.userAgent || null,
                'layout': null,
                'product': null,
                'name': null,
                'manufacturer': null,
                'os': null,
                'prerelease': null,
                'version': null,
                'toString': function () {
                    return this.description || '';
                }
            },
            'version': '1.0.0'
        });
        _.assign(Benchmark, {
            'filter': filter,
            'formatNumber': formatNumber,
            'invoke': invoke,
            'join': join,
            'runInContext': runInContext,
            'support': support
        });
        _.each([
            'each',
            'forEach',
            'forOwn',
            'has',
            'indexOf',
            'map',
            'pluck',
            'reduce'
        ], function (methodName) {
            Benchmark[methodName] = _[methodName];
        });
        _.assign(Benchmark.prototype, {
            'count': 0,
            'cycles': 0,
            'hz': 0,
            'compiled': undefined,
            'error': undefined,
            'fn': undefined,
            'aborted': false,
            'running': false,
            'setup': _.noop,
            'teardown': _.noop,
            'stats': {
                'moe': 0,
                'rme': 0,
                'sem': 0,
                'deviation': 0,
                'mean': 0,
                'sample': [],
                'variance': 0
            },
            'times': {
                'cycle': 0,
                'elapsed': 0,
                'period': 0,
                'timeStamp': 0
            }
        });
        _.assign(Benchmark.prototype, {
            'abort': abort,
            'clone': clone,
            'compare': compare,
            'emit': emit,
            'listeners': listeners,
            'off': off,
            'on': on,
            'reset': reset,
            'run': run,
            'toString': toStringBench
        });
        _.assign(Deferred.prototype, {
            'benchmark': null,
            'cycles': 0,
            'elapsed': 0,
            'timeStamp': 0
        });
        _.assign(Deferred.prototype, { 'resolve': resolve });
        _.assign(Event.prototype, {
            'aborted': false,
            'cancelled': false,
            'currentTarget': undefined,
            'result': undefined,
            'target': undefined,
            'timeStamp': 0,
            'type': ''
        });
        Suite.options = { 'name': undefined };
        _.assign(Suite.prototype, {
            'length': 0,
            'aborted': false,
            'running': false
        });
        _.assign(Suite.prototype, {
            'abort': abortSuite,
            'add': add,
            'clone': cloneSuite,
            'emit': emit,
            'filter': filterSuite,
            'join': arrayRef.join,
            'listeners': listeners,
            'off': off,
            'on': on,
            'pop': arrayRef.pop,
            'push': push,
            'reset': resetSuite,
            'run': runSuite,
            'reverse': arrayRef.reverse,
            'shift': shift,
            'slice': slice,
            'sort': arrayRef.sort,
            'splice': arrayRef.splice,
            'unshift': unshift
        });
        _.assign(Benchmark, {
            'Deferred': Deferred,
            'Event': Event,
            'Suite': Suite
        });
        _.each([
            'each',
            'forEach',
            'indexOf',
            'map',
            'pluck',
            'reduce'
        ], function (methodName) {
            var func = _[methodName];
            Suite.prototype[methodName] = function () {
                var args = [this];
                push.apply(args, arguments);
                return func.apply(_, args);
            };
        });
        if (!_.support.spliceObjects) {
            _.each([
                'pop',
                'shift',
                'splice'
            ], function (methodName) {
                var func = arrayRef[methodName];
                Suite.prototype[methodName] = function () {
                    var value = this, result = func.apply(value, arguments);
                    if (value.length === 0) {
                        delete value[0];
                    }
                    return result;
                };
            });
        }
        if (!support.unshiftResult) {
            Suite.prototype.unshift = function () {
                var value = this;
                unshift.apply(value, arguments);
                return value.length;
            };
        }
        return Benchmark;
    }
    if (typeof define == 'function' && typeof define.amd == 'object' && define.amd) {
        define([
            'lodash',
            'platform'
        ], function (_, platform) {
            return runInContext({
                '_': _,
                'platform': platform
            });
        });
    } else {
        var Benchmark = runInContext();
        if (freeExports && freeModule) {
            if (moduleExports) {
                (freeModule.exports = Benchmark).Benchmark = Benchmark;
            } else {
                freeExports.Benchmark = Benchmark;
            }
        } else {
            root.Benchmark = Benchmark;
        }
    }
}.call(this));