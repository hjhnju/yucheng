/**
 * @ignore
 * @file index
 * @author mySunShinning(441984145@qq.com)
 *         yangbinYB(1033371745@qq.com)
 * @time 14-12-22
 */

define(function () {
    var $ = require('jquery');

    var echarts;
    var hisCharts;

    /**
     * echarts option
     *
     * @type {Object}
     */
    var option = {
        tooltip: {
            trigger: 'axis',
            showDelay: 0,
            backgroundColor: '#f7f7f7',
            borderColor: '#d7d7d7',
            borderWidth: 1,
            borderRadius: 3,
            padding: 5,
            textStyle: {
                color: '#333'
            },
            axisPointer: {
                lineStyle : {
                    color: '#fff',
                    width: 1
                }
            },
            formatter: '￥{c}'
        },
        title: {
            text: '近半年收益曲线',
            textStyle: {
                fontSize: 16,
                color: '#333'
            },
            padding: 20
        },
        grid: {
            borderColor: '#FFF',
            borderWidth: '0'
        },
        animation: false,
        xAxis: [{
            splitLine: {
                show: false
            },
            axisLine: {
                show: false
            },
            axisLabel: {
                textStyle: {
                    fontSize: '12',
                    color: '#333'
                }
            },
            axisTick: {
                show: false
            },
            boundaryGap: true,
            data: ['2014-06', '2014-07', '2014-08', '2014-09', '2014-10', '2014-11']
            // data: []
        }],
        yAxis: [{
            type: 'value',
            splitArea: {
                show: false
            },
            splitLine: {
                show: true
            },
            axisLine: {
                show: false
            },
            axisLabel: {
                show: true
            },
            splitNumber: 3,
            min: -1000,
            max: 2000
        }],
        series: [{
            tooltip: {
                trigger: 'axis',
                decoration: 'bottom'
            },
            name: '收益',
            type: 'line',
            smooth: false,
            symbol: 'emptyCircle', // 系列级个性化拐点图形
            symbolColor: '#F3746B',
            symbolSize: 4,
            itemStyle: {

            },
            // data: []
            data: [0, 0, 0, 500, 1000, 0]
        }]
    };

    /**
     * 初始化
     *
     * @param {string} containerId 容器的选择器
     * @param {Object} data 图表数据
     * @param {string} type 数据类型（daily 每天  weekly 每周）
     */
    function render(containerId, data) {

        // 按需加载
        require(
            [
                'echarts',
                'echarts/chart/line'
            ],
            function (ec) {

                echarts = ec;
                dispose();
                hisCharts = echarts.init($('#' + containerId)[0]);

                // initData(data);
                hisCharts.setOption(option);

                // $(window).resize(hisCharts.resize);
            }
        );


    }

    /**
     * 初始化每天图表
     * @param    {Object} data 图表数据
     */
    function initData(data) {

        var dataList = $.extend(true, [], data.y);

        dataList.sort(function (a, b) {
            return a - b;
        });

        hisCharts.setOption(option);

        hisCharts.setOption({
            yAxis: [{
                max: dataList[dataList.length - 1]
            }],
            xAxis: [{
                data: data.user.x
            }],
            series: [{
                data: data.user.y
            }]
        });
    }

    /**
     * 销毁图表
     */
    function dispose() {
        if (hisCharts && hisCharts.dispose) {
            try {
                hisCharts.dispose();
            }
            catch (e) {
                hisCharts.clear();
            }
        }
    }

    return {
        render: render,
        dispose: dispose
    };
});
