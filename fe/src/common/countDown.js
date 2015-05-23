/**
 * @ignore
 * @file countDown 倒计时
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 15-1-1
 */

define(function() {

    var $ = require('jquery');
    var moment = require('moment');
    var etpl = require('etpl');


    /**
     * 存储计时器id数组
     *
     * @type {Array}
     */
    var countList = [];

    /**
     * 倒计时开始
     *
     * @param {Object} dom dom元素或者jQuery对象
     * @param {number} stime 开始时间戳
     * @param {number} etime 结束时间戳
     */
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

        /**
         * 渲染数据
         *
         * @param {number} difftime 时间差
         * @param {number} idx 索引
         * @param {string} tpl target名字
         */
        function render(difftime, idx, tpl) {
            countList[idx] = setTimeout(function() {

                //时差差了8小时
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

    /**
     * 清空计时器
     *
     */
    function clear() {
        for (var i = 0, l = countList.length; i < l; i++) {
            clearTimeout(countList[i]);
        }
        countList.length = 0;
    }


    //间隔一定时间后执行某一函数
    function Interval(fun,time){
        if(!$.isFunction(fun)){
            return;
        }
       return  setInterval(function () {
            fun();  
        },time);
    }

    return {
        init: init,
        clear: clear,
        Interval:Interval
    };
});
