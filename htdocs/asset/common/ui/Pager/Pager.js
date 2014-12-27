/*! 2014 Baidu Inc. All Rights Reserved */
define('common/ui/Pager/Pager', function (require) {
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
        init: function () {
            var me = this;
            this.opt.main = $(this.opt.main);
            this.render(0);
            this.opt.main.delegate('.ui-pager-item', 'click', function (e) {
                e.preventDefault();
                var value = +$.trim($(this).attr('data-value'));
                me.emit('change', {
                    e: e,
                    value: value,
                    target: $(this)[0]
                });
            });
        },
        render: function (page) {
            var result = this.calculateItem(page - this.opt.startPage + 1);
            this.opt.main.html(etpl.render('ui-pager', {
                data: $.extend({}, result, {
                    prevText: this.opt.prevText,
                    nextText: this.opt.nextText
                })
            }));
        },
        calculateItem: function (page) {
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
            if (page > startLine && page < endLine) {
                start = page - this.opt.front;
                end = page + this.opt.end;
            } else if (this.opt.total < showCount) {
                start = 1;
                end = this.opt.total;
            } else if (page <= startLine) {
                start = 1;
                end = showCount;
            } else if (page >= endLine) {
                start = this.opt.total - showCount + 1;
                end = this.opt.total;
            }
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
            return result;
        },
        setOpt: function (key, value) {
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
        prevText: '\u4E0A\u4E00\u9875',
        nextText: '\u4E0B\u4E00\u9875',
        front: 4,
        end: 5
    };
    XEmitter.mixin(Pager);
    return Pager;
});