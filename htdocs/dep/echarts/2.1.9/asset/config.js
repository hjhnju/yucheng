/*! 2014 Baidu Inc. All Rights Reserved */
define('echarts/config', function () {
    var config = {
            CHART_TYPE_LINE: 'line',
            CHART_TYPE_BAR: 'bar',
            CHART_TYPE_SCATTER: 'scatter',
            CHART_TYPE_PIE: 'pie',
            CHART_TYPE_RADAR: 'radar',
            CHART_TYPE_MAP: 'map',
            CHART_TYPE_K: 'k',
            CHART_TYPE_ISLAND: 'island',
            CHART_TYPE_FORCE: 'force',
            CHART_TYPE_CHORD: 'chord',
            CHART_TYPE_GAUGE: 'gauge',
            CHART_TYPE_FUNNEL: 'funnel',
            CHART_TYPE_EVENTRIVER: 'eventRiver',
            COMPONENT_TYPE_TITLE: 'title',
            COMPONENT_TYPE_LEGEND: 'legend',
            COMPONENT_TYPE_DATARANGE: 'dataRange',
            COMPONENT_TYPE_DATAVIEW: 'dataView',
            COMPONENT_TYPE_DATAZOOM: 'dataZoom',
            COMPONENT_TYPE_TOOLBOX: 'toolbox',
            COMPONENT_TYPE_TOOLTIP: 'tooltip',
            COMPONENT_TYPE_GRID: 'grid',
            COMPONENT_TYPE_AXIS: 'axis',
            COMPONENT_TYPE_POLAR: 'polar',
            COMPONENT_TYPE_X_AXIS: 'xAxis',
            COMPONENT_TYPE_Y_AXIS: 'yAxis',
            COMPONENT_TYPE_AXIS_CATEGORY: 'categoryAxis',
            COMPONENT_TYPE_AXIS_VALUE: 'valueAxis',
            COMPONENT_TYPE_TIMELINE: 'timeline',
            COMPONENT_TYPE_ROAMCONTROLLER: 'roamController',
            backgroundColor: 'rgba(0,0,0,0)',
            color: [
                '#ff7f50',
                '#87cefa',
                '#da70d6',
                '#32cd32',
                '#6495ed',
                '#ff69b4',
                '#ba55d3',
                '#cd5c5c',
                '#ffa500',
                '#40e0d0',
                '#1e90ff',
                '#ff6347',
                '#7b68ee',
                '#00fa9a',
                '#ffd700',
                '#6699FF',
                '#ff6666',
                '#3cb371',
                '#b8860b',
                '#30e0e0'
            ],
            title: {
                text: '',
                subtext: '',
                x: 'left',
                y: 'top',
                backgroundColor: 'rgba(0,0,0,0)',
                borderColor: '#ccc',
                borderWidth: 0,
                padding: 5,
                itemGap: 5,
                textStyle: {
                    fontSize: 18,
                    fontWeight: 'bolder',
                    color: '#333'
                },
                subtextStyle: { color: '#aaa' }
            },
            legend: {
                show: true,
                orient: 'horizontal',
                x: 'center',
                y: 'top',
                backgroundColor: 'rgba(0,0,0,0)',
                borderColor: '#ccc',
                borderWidth: 0,
                padding: 5,
                itemGap: 10,
                itemWidth: 20,
                itemHeight: 14,
                textStyle: { color: '#333' },
                selectedMode: true
            },
            dataRange: {
                show: true,
                orient: 'vertical',
                x: 'left',
                y: 'bottom',
                backgroundColor: 'rgba(0,0,0,0)',
                borderColor: '#ccc',
                borderWidth: 0,
                padding: 5,
                itemGap: 10,
                itemWidth: 20,
                itemHeight: 14,
                precision: 0,
                splitNumber: 5,
                calculable: false,
                hoverLink: true,
                realtime: true,
                color: [
                    '#006edd',
                    '#e0ffff'
                ],
                textStyle: { color: '#333' }
            },
            toolbox: {
                show: false,
                orient: 'horizontal',
                x: 'right',
                y: 'top',
                color: [
                    '#1e90ff',
                    '#22bb22',
                    '#4b0082',
                    '#d2691e'
                ],
                disableColor: '#ddd',
                effectiveColor: 'red',
                backgroundColor: 'rgba(0,0,0,0)',
                borderColor: '#ccc',
                borderWidth: 0,
                padding: 5,
                itemGap: 10,
                itemSize: 16,
                showTitle: true,
                feature: {
                    mark: {
                        show: false,
                        title: {
                            mark: '\u8F85\u52A9\u7EBF\u5F00\u5173',
                            markUndo: '\u5220\u9664\u8F85\u52A9\u7EBF',
                            markClear: '\u6E05\u7A7A\u8F85\u52A9\u7EBF'
                        },
                        lineStyle: {
                            width: 1,
                            color: '#1e90ff',
                            type: 'dashed'
                        }
                    },
                    dataZoom: {
                        show: false,
                        title: {
                            dataZoom: '\u533A\u57DF\u7F29\u653E',
                            dataZoomReset: '\u533A\u57DF\u7F29\u653E\u540E\u9000'
                        }
                    },
                    dataView: {
                        show: false,
                        title: '\u6570\u636E\u89C6\u56FE',
                        readOnly: false,
                        lang: [
                            '\u6570\u636E\u89C6\u56FE',
                            '\u5173\u95ED',
                            '\u5237\u65B0'
                        ]
                    },
                    magicType: {
                        show: false,
                        title: {
                            line: '\u6298\u7EBF\u56FE\u5207\u6362',
                            bar: '\u67F1\u5F62\u56FE\u5207\u6362',
                            stack: '\u5806\u79EF',
                            tiled: '\u5E73\u94FA',
                            force: '\u529B\u5BFC\u5411\u5E03\u5C40\u56FE\u5207\u6362',
                            chord: '\u548C\u5F26\u56FE\u5207\u6362',
                            pie: '\u997C\u56FE\u5207\u6362',
                            funnel: '\u6F0F\u6597\u56FE\u5207\u6362'
                        },
                        type: []
                    },
                    restore: {
                        show: false,
                        title: '\u8FD8\u539F'
                    },
                    saveAsImage: {
                        show: false,
                        title: '\u4FDD\u5B58\u4E3A\u56FE\u7247',
                        type: 'png',
                        lang: ['\u70B9\u51FB\u4FDD\u5B58']
                    }
                }
            },
            tooltip: {
                show: true,
                showContent: true,
                trigger: 'item',
                islandFormatter: '{a} <br/>{b} : {c}',
                showDelay: 20,
                hideDelay: 100,
                transitionDuration: 0.4,
                backgroundColor: 'rgba(0,0,0,0.7)',
                borderColor: '#333',
                borderRadius: 4,
                borderWidth: 0,
                padding: 5,
                axisPointer: {
                    type: 'line',
                    lineStyle: {
                        color: '#48b',
                        width: 2,
                        type: 'solid'
                    },
                    crossStyle: {
                        color: '#1e90ff',
                        width: 1,
                        type: 'dashed'
                    },
                    shadowStyle: {
                        color: 'rgba(150,150,150,0.3)',
                        width: 'auto',
                        type: 'default'
                    }
                },
                textStyle: { color: '#fff' }
            },
            dataZoom: {
                show: false,
                orient: 'horizontal',
                backgroundColor: 'rgba(0,0,0,0)',
                dataBackgroundColor: '#eee',
                fillerColor: 'rgba(144,197,237,0.2)',
                handleColor: 'rgba(70,130,180,0.8)',
                showDetail: true,
                realtime: true
            },
            grid: {
                x: 80,
                y: 60,
                x2: 80,
                y2: 60,
                backgroundColor: 'rgba(0,0,0,0)',
                borderWidth: 1,
                borderColor: '#ccc'
            },
            categoryAxis: {
                show: true,
                position: 'bottom',
                name: '',
                nameLocation: 'end',
                nameTextStyle: {},
                boundaryGap: true,
                axisLine: {
                    show: true,
                    onZero: true,
                    lineStyle: {
                        color: '#48b',
                        width: 2,
                        type: 'solid'
                    }
                },
                axisTick: {
                    show: true,
                    interval: 'auto',
                    inside: false,
                    length: 5,
                    lineStyle: {
                        color: '#333',
                        width: 1
                    }
                },
                axisLabel: {
                    show: true,
                    interval: 'auto',
                    rotate: 0,
                    margin: 8,
                    textStyle: { color: '#333' }
                },
                splitLine: {
                    show: true,
                    lineStyle: {
                        color: ['#ccc'],
                        width: 1,
                        type: 'solid'
                    }
                },
                splitArea: {
                    show: false,
                    areaStyle: {
                        color: [
                            'rgba(250,250,250,0.3)',
                            'rgba(200,200,200,0.3)'
                        ]
                    }
                }
            },
            valueAxis: {
                show: true,
                position: 'left',
                name: '',
                nameLocation: 'end',
                nameTextStyle: {},
                boundaryGap: [
                    0,
                    0
                ],
                axisLine: {
                    show: true,
                    onZero: true,
                    lineStyle: {
                        color: '#48b',
                        width: 2,
                        type: 'solid'
                    }
                },
                axisTick: {
                    show: false,
                    inside: false,
                    length: 5,
                    lineStyle: {
                        color: '#333',
                        width: 1
                    }
                },
                axisLabel: {
                    show: true,
                    rotate: 0,
                    margin: 8,
                    textStyle: { color: '#333' }
                },
                splitLine: {
                    show: true,
                    lineStyle: {
                        color: ['#ccc'],
                        width: 1,
                        type: 'solid'
                    }
                },
                splitArea: {
                    show: false,
                    areaStyle: {
                        color: [
                            'rgba(250,250,250,0.3)',
                            'rgba(200,200,200,0.3)'
                        ]
                    }
                }
            },
            polar: {
                center: [
                    '50%',
                    '50%'
                ],
                radius: '75%',
                startAngle: 90,
                boundaryGap: [
                    0,
                    0
                ],
                splitNumber: 5,
                name: {
                    show: true,
                    textStyle: { color: '#333' }
                },
                axisLine: {
                    show: true,
                    lineStyle: {
                        color: '#ccc',
                        width: 1,
                        type: 'solid'
                    }
                },
                axisLabel: {
                    show: false,
                    textStyle: { color: '#333' }
                },
                splitArea: {
                    show: true,
                    areaStyle: {
                        color: [
                            'rgba(250,250,250,0.3)',
                            'rgba(200,200,200,0.3)'
                        ]
                    }
                },
                splitLine: {
                    show: true,
                    lineStyle: {
                        width: 1,
                        color: '#ccc'
                    }
                },
                type: 'polygon'
            },
            timeline: {
                show: true,
                type: 'time',
                notMerge: false,
                realtime: true,
                x: 80,
                x2: 80,
                y2: 0,
                height: 50,
                backgroundColor: 'rgba(0,0,0,0)',
                borderColor: '#ccc',
                borderWidth: 0,
                padding: 5,
                controlPosition: 'left',
                autoPlay: false,
                loop: true,
                playInterval: 2000,
                lineStyle: {
                    width: 1,
                    color: '#666',
                    type: 'dashed'
                },
                label: {
                    show: true,
                    interval: 'auto',
                    rotate: 0,
                    textStyle: { color: '#333' }
                },
                checkpointStyle: {
                    symbol: 'auto',
                    symbolSize: 'auto',
                    color: 'auto',
                    borderColor: 'auto',
                    borderWidth: 'auto',
                    label: {
                        show: false,
                        textStyle: { color: 'auto' }
                    }
                },
                controlStyle: {
                    normal: { color: '#333' },
                    emphasis: { color: '#1e90ff' }
                },
                symbol: 'emptyDiamond',
                symbolSize: 4,
                currentIndex: 0
            },
            roamController: {
                show: true,
                x: 'left',
                y: 'top',
                width: 80,
                height: 120,
                backgroundColor: 'rgba(0,0,0,0)',
                borderColor: '#ccc',
                borderWidth: 0,
                padding: 5,
                handleColor: '#6495ed',
                fillerColor: '#fff',
                step: 15,
                mapTypeControl: null
            },
            bar: {
                clickable: true,
                legendHoverLink: true,
                xAxisIndex: 0,
                yAxisIndex: 0,
                barMinHeight: 0,
                barGap: '30%',
                barCategoryGap: '20%',
                itemStyle: {
                    normal: {
                        barBorderColor: '#fff',
                        barBorderRadius: 0,
                        barBorderWidth: 0,
                        label: { show: false }
                    },
                    emphasis: {
                        barBorderColor: '#fff',
                        barBorderRadius: 0,
                        barBorderWidth: 0,
                        label: { show: false }
                    }
                }
            },
            line: {
                clickable: true,
                legendHoverLink: true,
                xAxisIndex: 0,
                yAxisIndex: 0,
                itemStyle: {
                    normal: {
                        label: { show: false },
                        lineStyle: {
                            width: 2,
                            type: 'solid',
                            shadowColor: 'rgba(0,0,0,0)',
                            shadowBlur: 0,
                            shadowOffsetX: 0,
                            shadowOffsetY: 0
                        }
                    },
                    emphasis: { label: { show: false } }
                },
                symbolSize: 2,
                showAllSymbol: false
            },
            k: {
                clickable: true,
                legendHoverLink: false,
                xAxisIndex: 0,
                yAxisIndex: 0,
                itemStyle: {
                    normal: {
                        color: '#fff',
                        color0: '#00aa11',
                        lineStyle: {
                            width: 1,
                            color: '#ff3200',
                            color0: '#00aa11'
                        }
                    },
                    emphasis: {}
                }
            },
            scatter: {
                clickable: true,
                legendHoverLink: true,
                xAxisIndex: 0,
                yAxisIndex: 0,
                symbolSize: 4,
                large: false,
                largeThreshold: 2000,
                itemStyle: {
                    normal: {
                        label: {
                            show: false,
                            formatter: function (a, b, c) {
                                if (typeof c[2] != 'undefined') {
                                    return c[2];
                                } else {
                                    return c[0] + ' , ' + c[1];
                                }
                            }
                        }
                    },
                    emphasis: {
                        label: {
                            show: false,
                            formatter: function (a, b, c) {
                                if (typeof c[2] != 'undefined') {
                                    return c[2];
                                } else {
                                    return c[0] + ' , ' + c[1];
                                }
                            }
                        }
                    }
                }
            },
            radar: {
                clickable: true,
                legendHoverLink: true,
                polarIndex: 0,
                itemStyle: {
                    normal: {
                        label: { show: false },
                        lineStyle: {
                            width: 2,
                            type: 'solid'
                        }
                    },
                    emphasis: { label: { show: false } }
                },
                symbolSize: 2
            },
            pie: {
                clickable: true,
                legendHoverLink: true,
                center: [
                    '50%',
                    '50%'
                ],
                radius: [
                    0,
                    '75%'
                ],
                clockWise: true,
                startAngle: 90,
                minAngle: 0,
                selectedOffset: 10,
                itemStyle: {
                    normal: {
                        borderColor: 'rgba(0,0,0,0)',
                        borderWidth: 1,
                        label: {
                            show: true,
                            position: 'outer'
                        },
                        labelLine: {
                            show: true,
                            length: 20,
                            lineStyle: {
                                width: 1,
                                type: 'solid'
                            }
                        }
                    },
                    emphasis: {
                        borderColor: 'rgba(0,0,0,0)',
                        borderWidth: 1,
                        label: { show: false },
                        labelLine: {
                            show: false,
                            length: 20,
                            lineStyle: {
                                width: 1,
                                type: 'solid'
                            }
                        }
                    }
                }
            },
            map: {
                mapType: 'china',
                mapValuePrecision: 0,
                showLegendSymbol: true,
                dataRangeHoverLink: true,
                hoverable: true,
                clickable: true,
                itemStyle: {
                    normal: {
                        borderColor: 'rgba(0,0,0,0)',
                        borderWidth: 1,
                        areaStyle: { color: '#ccc' },
                        label: {
                            show: false,
                            textStyle: { color: 'rgb(139,69,19)' }
                        }
                    },
                    emphasis: {
                        borderColor: 'rgba(0,0,0,0)',
                        borderWidth: 1,
                        areaStyle: { color: 'rgba(255,215,0,0.8)' },
                        label: {
                            show: false,
                            textStyle: { color: 'rgb(100,0,0)' }
                        }
                    }
                }
            },
            force: {
                center: [
                    '50%',
                    '50%'
                ],
                size: '100%',
                preventOverlap: false,
                coolDown: 0.99,
                minRadius: 10,
                maxRadius: 20,
                ratioScaling: false,
                large: false,
                useWorker: false,
                steps: 1,
                scaling: 1,
                gravity: 1,
                symbol: 'circle',
                symbolSize: 0,
                linkSymbol: null,
                linkSymbolSize: [
                    10,
                    15
                ],
                draggable: true,
                clickable: true,
                roam: false,
                itemStyle: {
                    normal: {
                        label: {
                            show: false,
                            position: 'inside'
                        },
                        nodeStyle: {
                            brushType: 'both',
                            borderColor: '#5182ab',
                            borderWidth: 1
                        },
                        linkStyle: {
                            color: '#5182ab',
                            width: 1,
                            type: 'line'
                        }
                    },
                    emphasis: {
                        label: { show: false },
                        nodeStyle: {},
                        linkStyle: { opacity: 0 }
                    }
                }
            },
            chord: {
                clickable: true,
                radius: [
                    '65%',
                    '75%'
                ],
                center: [
                    '50%',
                    '50%'
                ],
                padding: 2,
                sort: 'none',
                sortSub: 'none',
                startAngle: 90,
                clockWise: true,
                ribbonType: true,
                minRadius: 10,
                maxRadius: 20,
                symbol: 'circle',
                showScale: false,
                showScaleText: false,
                itemStyle: {
                    normal: {
                        borderWidth: 0,
                        borderColor: '#000',
                        label: {
                            show: true,
                            rotate: false,
                            distance: 5
                        },
                        chordStyle: {
                            width: 1,
                            color: 'black',
                            borderWidth: 1,
                            borderColor: '#999',
                            opacity: 0.5
                        }
                    },
                    emphasis: {
                        borderWidth: 0,
                        borderColor: '#000',
                        chordStyle: {
                            width: 1,
                            color: 'black',
                            borderWidth: 1,
                            borderColor: '#999'
                        }
                    }
                }
            },
            gauge: {
                center: [
                    '50%',
                    '50%'
                ],
                legendHoverLink: true,
                radius: '75%',
                startAngle: 225,
                endAngle: -45,
                min: 0,
                max: 100,
                precision: 0,
                splitNumber: 10,
                axisLine: {
                    show: true,
                    lineStyle: {
                        color: [
                            [
                                0.2,
                                '#228b22'
                            ],
                            [
                                0.8,
                                '#48b'
                            ],
                            [
                                1,
                                '#ff4500'
                            ]
                        ],
                        width: 30
                    }
                },
                axisTick: {
                    show: true,
                    splitNumber: 5,
                    length: 8,
                    lineStyle: {
                        color: '#eee',
                        width: 1,
                        type: 'solid'
                    }
                },
                axisLabel: {
                    show: true,
                    textStyle: { color: 'auto' }
                },
                splitLine: {
                    show: true,
                    length: 30,
                    lineStyle: {
                        color: '#eee',
                        width: 2,
                        type: 'solid'
                    }
                },
                pointer: {
                    show: true,
                    length: '80%',
                    width: 8,
                    color: 'auto'
                },
                title: {
                    show: true,
                    offsetCenter: [
                        0,
                        '-40%'
                    ],
                    textStyle: {
                        color: '#333',
                        fontSize: 15
                    }
                },
                detail: {
                    show: true,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderWidth: 0,
                    borderColor: '#ccc',
                    width: 100,
                    height: 40,
                    offsetCenter: [
                        0,
                        '40%'
                    ],
                    textStyle: {
                        color: 'auto',
                        fontSize: 30
                    }
                }
            },
            funnel: {
                clickable: true,
                legendHoverLink: true,
                x: 80,
                y: 60,
                x2: 80,
                y2: 60,
                min: 0,
                max: 100,
                minSize: '0%',
                maxSize: '100%',
                sort: 'descending',
                gap: 0,
                funnelAlign: 'center',
                itemStyle: {
                    normal: {
                        borderColor: '#fff',
                        borderWidth: 1,
                        label: {
                            show: true,
                            position: 'outer'
                        },
                        labelLine: {
                            show: true,
                            length: 10,
                            lineStyle: {
                                width: 1,
                                type: 'solid'
                            }
                        }
                    },
                    emphasis: {
                        borderColor: 'rgba(0,0,0,0)',
                        borderWidth: 1,
                        label: { show: true },
                        labelLine: { show: true }
                    }
                }
            },
            eventRiver: {
                clickable: true,
                legendHoverLink: true,
                itemStyle: {
                    normal: {
                        borderColor: 'rgba(0,0,0,0)',
                        borderWidth: 1,
                        label: {
                            show: true,
                            position: 'inside',
                            formatter: '{b}'
                        }
                    },
                    emphasis: {
                        borderColor: 'rgba(0,0,0,0)',
                        borderWidth: 1,
                        label: { show: true }
                    }
                }
            },
            island: {
                r: 15,
                calculateStep: 0.1
            },
            markPoint: {
                clickable: true,
                symbol: 'pin',
                symbolSize: 10,
                large: false,
                effect: {
                    show: false,
                    loop: true,
                    period: 15,
                    scaleSize: 2
                },
                itemStyle: {
                    normal: {
                        borderWidth: 2,
                        label: {
                            show: true,
                            position: 'inside'
                        }
                    },
                    emphasis: { label: { show: true } }
                }
            },
            markLine: {
                clickable: true,
                symbol: [
                    'circle',
                    'arrow'
                ],
                symbolSize: [
                    2,
                    4
                ],
                large: false,
                effect: {
                    show: false,
                    loop: true,
                    period: 15,
                    scaleSize: 2
                },
                itemStyle: {
                    normal: {
                        borderWidth: 1.5,
                        label: {
                            show: true,
                            position: 'end'
                        },
                        lineStyle: { type: 'dashed' }
                    },
                    emphasis: {
                        label: { show: false },
                        lineStyle: {}
                    }
                }
            },
            textStyle: {
                decoration: 'none',
                fontFamily: 'Arial, Verdana, sans-serif',
                fontFamily2: '\u5FAE\u8F6F\u96C5\u9ED1',
                fontSize: 12,
                fontStyle: 'normal',
                fontWeight: 'normal'
            },
            EVENT: {
                REFRESH: 'refresh',
                RESTORE: 'restore',
                RESIZE: 'resize',
                CLICK: 'click',
                DBLCLICK: 'dblclick',
                HOVER: 'hover',
                MOUSEOUT: 'mouseout',
                DATA_CHANGED: 'dataChanged',
                DATA_ZOOM: 'dataZoom',
                DATA_RANGE: 'dataRange',
                DATA_RANGE_HOVERLINK: 'dataRangeHoverLink',
                LEGEND_SELECTED: 'legendSelected',
                LEGEND_HOVERLINK: 'legendHoverLink',
                MAP_SELECTED: 'mapSelected',
                PIE_SELECTED: 'pieSelected',
                MAGIC_TYPE_CHANGED: 'magicTypeChanged',
                DATA_VIEW_CHANGED: 'dataViewChanged',
                TIMELINE_CHANGED: 'timelineChanged',
                MAP_ROAM: 'mapRoam',
                FORCE_LAYOUT_END: 'forceLayoutEnd',
                TOOLTIP_HOVER: 'tooltipHover',
                TOOLTIP_IN_GRID: 'tooltipInGrid',
                TOOLTIP_OUT_GRID: 'tooltipOutGrid',
                ROAMCONTROLLER: 'roamController'
            },
            DRAG_ENABLE_TIME: 120,
            EFFECT_ZLEVEL: 7,
            symbolList: [
                'circle',
                'rectangle',
                'triangle',
                'diamond',
                'emptyCircle',
                'emptyRectangle',
                'emptyTriangle',
                'emptyDiamond'
            ],
            loadingText: 'Loading...',
            calculable: false,
            calculableColor: 'rgba(255,165,0,0.6)',
            calculableHolderColor: '#ccc',
            nameConnector: ' & ',
            valueConnector: ': ',
            animation: true,
            addDataAnimation: true,
            animationThreshold: 2000,
            animationDuration: 2000,
            animationEasing: 'ExponentialOut'
        };
    return config;
});