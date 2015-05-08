/**
 * @ignore
 * @file Pager.js
 * @author mySunShinning(441984145@qq.com)
 * @time 14-11-18
 */

define(function(require) {

    var XEmitter = require('common/XEmitter');
    var $ = require('jquery');
    var etpl = require('etpl');

    var tpl = require('./pager.tpl');

    etpl.compile(tpl);

    function Pager(opt) {
        this.opt = {};
        $.extend(this.opt, Pager.defaultOpt, opt || {});

        this.init();
    }

    Pager.prototype = {
        constructor: Pager,

        init: function() {
            var me = this;
            this.opt.main = $(this.opt.main);
            this.render(0);

            this.opt.main.delegate('.ui-pager-item', 'click', function(e) {
                e.preventDefault();
                var value = +$.trim($(this).attr('data-value'));
                me.emit('change', {
                    e: e,
                    value: value,
                    target: $(this)[0]
                });
            });
        },

        /**
         * 渲染页面
         * @param {number} page 页码value值
         */
        render: function(page) {
            // 总数为1时不显示分页
            if (this.opt.total === 1) {
                this.opt.main.html('');
                return;
            }

            var result = this.calculateItem(page - this.opt.startPage + 1);

            this.opt.main.html(etpl.render('ui-pager', {
                data: $.extend({}, result, {
                    prevText: this.opt.prevText,
                    nextText: this.opt.nextText
                })
            }));
        },
        /**
         * 计算显示数据
         * @param {number} page 实际页码数
         * @returns {Object}
         */
        calculateItem: function(page) {
            page = page || 1;
            var result = {
                hasprev: 1,
                hasnext: 1,
                prev: this.opt.startPage ? page - 1 : page - 2,
                next: this.opt.startPage ? page + 1 : page,
                pages: [],
                currentPage: -1,
                page: page
            };

            var startLine = this.opt.front;
            var endLine = this.opt.total - this.opt.end;
            var showCount = this.opt.front + this.opt.end + 1;
            var start;
            var end;

            if (+this.opt.total === 1 || page === +this.opt.total) {
                result.hasnext = 0;
            }
            if (page === 1) {
                result.hasprev = 0;
            }

            // 中间
            if (page > startLine && page < endLine) {
                start = page - this.opt.front;
                end = page + this.opt.end;
            }

            // 小于最大个数
            else if (this.opt.total < showCount) {
                start = 1;
                end = this.opt.total;
            }
            // 小于开始位置
            else if (page <= startLine) {
                start = 1;
                end = showCount;
            }
            // 大于截止位置
            else if (page >= endLine) {
                start = this.opt.total - showCount + 1;
                end = this.opt.total;
            }
            if (this.opt.isPageNum) {
                var i = 0;
                for (var j = start; j <= end; j++) {
                    result.pages.push({
                        value: j,
                        index: this.opt.startPage ? j : j - 1
                    });
                    if (j === page) {
                        result.currentPage = i;
                    }
                    i++;
                }
            }


            return result;
        },

        setOpt: function(key, value) {
            if (this.opt.hasOwnProperty(key)) {
                this.opt[key] = value;
            }
        }
    };

    Pager.defaultOpt = {
        startPage: 0,
        page: 0,
        total: 10,
        main: $('body'),
        prevText: '上一页',
        nextText: '下一页',
        front: 4,
        end: 5,
        isPageNum: true //是否显示页码
    };

    XEmitter.mixin(Pager);

    return Pager;
});
