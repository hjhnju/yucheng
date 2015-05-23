define('m/common/common', [
    'require',
    './common.tpl',
    'etpl',
    'm/common/fastclick'
], function (require) {
    var tpl = require('./common.tpl');
    var etpl = require('etpl');
    var FastClick = require('m/common/fastclick');
    function init() {
        etpl.compile(tpl);
        FastClick.attach(document.body);
    }
    function myScrollEvents(myScroll, pullUpAction, pullDownAction) {
        var pullDownEl = document.getElementById('pullDown');
        var pullDownOffset = pullDownEl.offsetHeight;
        var pullUpEl = document.getElementById('pullUp');
        var pullUpOffset = pullUpEl.offsetHeight;
        myScroll.on('scroll', function () {
            if (this.y > 5 && !pullDownEl.className.match('flip')) {
                pullDownEl.className = 'flip';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '\u91CA\u653E\u540E\u52A0\u8F7D\u6700\u65B0';
                this.minScrollY = 0;
            } else if (this.y < 5 && pullDownEl.className.match('flip')) {
                pullDownEl.className = '';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '\u4E0B\u62C9\u52A0\u8F7D\u66F4\u591A';
                this.minScrollY = -pullDownOffset;
            } else if (this.y < this.maxScrollY - 5 && !pullUpEl.className.match('flip')) {
                pullUpEl.className = 'flip';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '\u91CA\u653E\u540E\u52A0\u8F7D\u6700\u65B0';
                this.maxScrollY = this.maxScrollY;
            } else if (this.y > this.maxScrollY + 5 && pullUpEl.className.match('flip')) {
                pullUpEl.className = '';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '\u4E0A\u62C9\u52A0\u8F7D\u66F4\u591A';
                this.maxScrollY = pullUpOffset;
            }
        });
        myScroll.on('scrollEnd', function () {
            if (pullDownEl.className.match('flip')) {
                pullDownEl.className = 'loading';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '\u6B63\u5728\u52AA\u529B\u52A0\u8F7D\u4E2D...';
                pullDownAction();
            } else if (pullUpEl.className.match('flip')) {
                pullUpEl.className = 'loading';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '\u6B63\u5728\u52AA\u529B\u52A0\u8F7D\u4E2D...';
                pullUpAction();
            }
        });
        myScroll.on('refresh', function () {
            if (pullDownEl.className.match('loading')) {
                pullDownEl.className = '';
                pullDownEl.querySelector('.pullDownLabel').innerHTML = '\u4E0B\u62C9\u52A0\u8F7D\u66F4\u591A';
            } else if (pullUpEl.className.match('loading')) {
                pullUpEl.className = '';
                pullUpEl.querySelector('.pullUpLabel').innerHTML = '\u4E0A\u62C9\u52A0\u8F7D\u66F4\u591A';
            }
        });
    }
    return {
        init: init,
        myScrollEvents: myScrollEvents
    };
});