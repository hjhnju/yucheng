define('common/countDown', [
    'jquery',
    'moment',
    'etpl'
], function () {
    var $ = require('jquery');
    var moment = require('moment');
    var etpl = require('etpl');
    var countList = [];
    function init(dom, stime, etime) {
        var startTime = stime;
        var endTime = etime;
        var index = countList.length++;
        function counting() {
            var today = new Date().getTime();
            if (startTime > today) {
                render(startTime - today, index, 'startTime');
            } else if (endTime > today) {
                render(endTime - today, index, 'leftTime');
            } else {
                clearTimeout(countList[index]);
                $(dom).html(etpl.render('over'));
            }
        }
        function render(difftime, idx, tpl) {
            countList[idx] = setTimeout(function () {
                var time = moment(difftime - 8 * 60 * 60 * 1000).toArray();
                var result = {
                        d: time[1] ? moment(difftime).dayOfYear() - 2 : time[2] - 1,
                        h: time[3],
                        M: time[4],
                        s: time[5]
                    };
                $(dom).html(etpl.render(tpl, result));
                counting();
            }, 1000);
        }
        counting();
    }
    function clear() {
        for (var i = 0, l = countList.length; i < l; i++) {
            clearTimeout(countList[i]);
        }
        countList.length = 0;
    }
    return {
        init: init,
        clear: clear
    };
});