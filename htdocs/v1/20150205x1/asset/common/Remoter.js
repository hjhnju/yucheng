define('common/Remoter', function (require) {
    var config = require('./config');
    var $ = require('jquery');
    var global = require('common/global');
    var XEmitter = require('./XEmitter');
    function Remoter(urlName) {
        this.opt = {};
        this.opt.url = urlName;
        if (!config.URL[urlName]) {
            throw [
                '[err]',
                'url:',
                urlName,
                'undefined'
            ].join(' ');
        }
    }
    Remoter.hooks = { token: global.get('token') };
    Remoter.prototype = {
        constructor: Remoter,
        remote: function (method, data) {
            var me = this;
            if (typeof method !== 'string') {
                data = method;
                method = 'post';
            }
            if (/EDIT|DEL|ADD|CHECK/g.test(me.opt.url)) {
                data = $.extend({}, Remoter.hooks, data);
            }
            return $.ajax({
                url: config.URL[me.opt.url],
                type: method || 'post',
                async: true,
                data: method === 'get' ? $.param(data) : data,
                dataType: 'json',
                success: function (data) {
                    var status = +data.status;
                    if (status < 1025) {
                        if (status === 0) {
                            me.emit('success', data.data);
                        } else if (status === 302) {
                            window.location.href = data.data.url;
                        } else if (status === 101) {
                            me.emit('success', {
                                imgCode: true,
                                status: data.status,
                                data: data.data
                            });
                        } else {
                            me.emit('fail', data.statusInfo);
                        }
                    } else if (status > 1024 && status < 99999) {
                        me.emit('success', {
                            status: status,
                            statusInfo: data.statusInfo,
                            bizError: true
                        });
                    }
                },
                error: function () {
                    me.emit('error');
                }
            });
        },
        jsonp: function (data) {
            var me = this;
            return $.jsonp({
                url: config.URL[me.opt.url],
                callback: 'callback',
                data: data,
                success: function (data) {
                    me.emit('success', data);
                },
                error: function () {
                    me.emit('error');
                }
            });
        }
    };
    XEmitter.mixin(Remoter);
    return Remoter;
});