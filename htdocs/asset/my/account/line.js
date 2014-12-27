/*! 2014 Baidu Inc. All Rights Reserved */
define('my/account/line', function () {
    var $ = require('jquery');
    var echarts;
    var hisCharts;
    var option = {
            tooltip: {
                trigger: 'axis',
                showDelay: 0,
                backgroundColor: '#f7f7f7',
                borderColor: '#d7d7d7',
                borderWidth: 1,
                borderRadius: 3,
                padding: 5,
                textStyle: { color: '#333' },
                axisPointer: {
                    lineStyle: {
                        color: '#fff',
                        width: 1
                    }
                },
                formatter: '\uFFE5{c}'
            },
            title: {
                text: '\u8FD1\u534A\u5E74\u6536\u76CA\u66F2\u7EBF',
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
                    splitLine: { show: false },
                    axisLine: { show: false },
                    axisLabel: {
                        textStyle: {
                            fontSize: '12',
                            color: '#333'
                        }
                    },
                    axisTick: { show: false },
                    boundaryGap: true,
                    data: []
                }],
            yAxis: [{
                    type: 'value',
                    splitArea: { show: false },
                    splitLine: { show: true },
                    axisLine: { show: false },
                    axisLabel: { show: true },
                    splitNumber: 3,
                    min: -1000,
                    max: 2000
                }],
            series: [{
                    tooltip: {
                        trigger: 'axis',
                        decoration: 'bottom'
                    },
                    name: '\u6536\u76CA',
                    type: 'line',
                    smooth: false,
                    symbol: 'emptyCircle',
                    symbolColor: '#F3746B',
                    symbolSize: 4,
                    itemStyle: {},
                    data: []
                }]
        };
    function render(containerId, data) {
        require([
            'echarts',
            'echarts/chart/line'
        ], function (ec) {
            echarts = ec;
            dispose();
            hisCharts = echarts.init($('#' + containerId)[0]);
            initData(data);
            hisCharts.setOption(option);
        });
    }
    function initData(data) {
        option.xAxis[0].data = data.x;
        option.series[0].data = data.y;
        hisCharts.setOption(option);
    }
    function dispose() {
        if (hisCharts && hisCharts.dispose) {
            try {
                hisCharts.dispose();
            } catch (e) {
                hisCharts.clear();
            }
        }
    }
    return {
        render: render,
        dispose: dispose
    };
});