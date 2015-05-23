/**
 * @ignore
 * @file Remoter.js
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-11-15
 */

define(function (require) {

    var config = require('./config');
    var $ = require('jquery');
    var global = require('common/global');
    var XEmitter = require('./XEmitter');

    /**
     * Remoter构造器
     * @param {string} urlName 请求地址
     * @constructor
     */
    function Remoter(urlName) {
        this.opt = {};
        this.opt.url = urlName;

        if (!config.URL[urlName]) {
            throw (['[err]', 'url:', urlName, 'undefined'].join(' '));
        }
    }

    /**
     * 给Remoter添加钩子
     * @type {Object}
     */
    Remoter.hooks = {
        token: global.get('token')
    };

    /**
     * Remoter原型链
     *
     * @type {Object}
     */
    Remoter.prototype = {
        constructor: Remoter,

        /**
         * 发送请求
         * @param {string} [method] 请求类型
         * @param {Object} data 请求参数
         * @return {*}
         */
        remote: function (method, data) {
            var me = this;

            if (typeof method !== 'string') {
                data = method;
                method = 'post';
            }

            data = $.extend({}, Remoter.hooks, data);

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
                        }
                        else if (status === 302) {
                            window.location.href = data.data.url;
                        }
                        else if (status === 101) {
                            // 图片验证码
                            me.emit('success', {
                                imgCode: true,
                                status: data.status,
                                data: data.data
                            });
                        }
                        else {
                            /**
                             * 触发失败回调
                             *
                             * @event#fail
                             */
                            me.emit('fail', data.statusInfo);
                        }
                    }
                    else if (status > 1024 && status < 99999) {

                        /**
                         * 触发成功回调
                         *
                         * @event#success
                         */
                        me.emit('success', {
                            status: status,
                            statusInfo: data.statusInfo,
                            bizError: true
                        });
                    }
                },
                error: function(e) {
                    /**
                     * 触发错误回调
                     *
                     * @event#error
                     */
                    me.emit('error');
                }
            });
        },

        /**
         * jsonp跨域方法
         * @param {Object} data jsonp需要数据
         * @return {*}
         */
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
