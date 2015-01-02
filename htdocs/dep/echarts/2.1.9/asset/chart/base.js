define('echarts/chart/base', [
    'require',
    'zrender/shape/Image',
    '../util/shape/Icon',
    '../util/shape/MarkLine',
    '../util/shape/Symbol',
    '../config',
    '../util/ecData',
    '../util/ecAnimation',
    '../util/ecEffect',
    '../util/accMath',
    'zrender/tool/util',
    'zrender/tool/area'
], function (require) {
    var ImageShape = require('zrender/shape/Image');
    var IconShape = require('../util/shape/Icon');
    var MarkLineShape = require('../util/shape/MarkLine');
    var SymbolShape = require('../util/shape/Symbol');
    var ecConfig = require('../config');
    var ecData = require('../util/ecData');
    var ecAnimation = require('../util/ecAnimation');
    var ecEffect = require('../util/ecEffect');
    var accMath = require('../util/accMath');
    var zrUtil = require('zrender/tool/util');
    var zrArea = require('zrender/tool/area');
    function Base() {
        var self = this;
        this.selectedMap = {};
        this.lastShapeList = [];
        this.shapeHandler = {
            onclick: function () {
                self.isClick = true;
            },
            ondragover: function (param) {
                var calculableShape = param.target;
                calculableShape.highlightStyle = calculableShape.highlightStyle || {};
                var highlightStyle = calculableShape.highlightStyle;
                var brushType = highlightStyle.brushTyep;
                var strokeColor = highlightStyle.strokeColor;
                var lineWidth = highlightStyle.lineWidth;
                highlightStyle.brushType = 'stroke';
                highlightStyle.strokeColor = self.ecTheme.calculableColor;
                highlightStyle.lineWidth = calculableShape.type === 'icon' ? 30 : 10;
                self.zr.addHoverShape(calculableShape);
                setTimeout(function () {
                    if (calculableShape.highlightStyle) {
                        calculableShape.highlightStyle.brushType = brushType;
                        calculableShape.highlightStyle.strokeColor = strokeColor;
                        calculableShape.highlightStyle.lineWidth = lineWidth;
                    }
                }, 20);
            },
            ondrop: function (param) {
                if (ecData.get(param.dragged, 'data') != null) {
                    self.isDrop = true;
                }
            },
            ondragend: function () {
                self.isDragend = true;
            }
        };
    }
    Base.prototype = {
        setCalculable: function (shape) {
            shape.dragEnableTime = this.ecTheme.DRAG_ENABLE_TIME;
            shape.ondragover = this.shapeHandler.ondragover;
            shape.ondragend = this.shapeHandler.ondragend;
            shape.ondrop = this.shapeHandler.ondrop;
            return shape;
        },
        ondrop: function (param, status) {
            if (!this.isDrop || !param.target || status.dragIn) {
                return;
            }
            var target = param.target;
            var dragged = param.dragged;
            var seriesIndex = ecData.get(target, 'seriesIndex');
            var dataIndex = ecData.get(target, 'dataIndex');
            var series = this.series;
            var data;
            var legend = this.component.legend;
            if (dataIndex === -1) {
                if (ecData.get(dragged, 'seriesIndex') == seriesIndex) {
                    status.dragOut = status.dragIn = status.needRefresh = true;
                    this.isDrop = false;
                    return;
                }
                data = {
                    value: ecData.get(dragged, 'value'),
                    name: ecData.get(dragged, 'name')
                };
                if (this.type === ecConfig.CHART_TYPE_PIE && data.value < 0) {
                    data.value = 0;
                }
                var hasFind = false;
                var sData = series[seriesIndex].data;
                for (var i = 0, l = sData.length; i < l; i++) {
                    if (sData[i].name === data.name && sData[i].value === '-') {
                        series[seriesIndex].data[i].value = data.value;
                        hasFind = true;
                    }
                }
                !hasFind && series[seriesIndex].data.push(data);
                legend && legend.add(data.name, dragged.style.color || dragged.style.strokeColor);
            } else {
                data = this.option.series[seriesIndex].data[dataIndex] || '-';
                if (data.value != null) {
                    if (data.value != '-') {
                        this.option.series[seriesIndex].data[dataIndex].value = accMath.accAdd(this.option.series[seriesIndex].data[dataIndex].value, ecData.get(dragged, 'value'));
                    } else {
                        this.option.series[seriesIndex].data[dataIndex].value = ecData.get(dragged, 'value');
                    }
                    if (this.type === ecConfig.CHART_TYPE_FUNNEL || this.type === ecConfig.CHART_TYPE_PIE) {
                        legend && legend.getRelatedAmount(data.name) === 1 && this.component.legend.del(data.name);
                        data.name += this.option.nameConnector + ecData.get(dragged, 'name');
                        legend && legend.add(data.name, dragged.style.color || dragged.style.strokeColor);
                    }
                } else {
                    if (data != '-') {
                        this.option.series[seriesIndex].data[dataIndex] = accMath.accAdd(this.option.series[seriesIndex].data[dataIndex], ecData.get(dragged, 'value'));
                    } else {
                        this.option.series[seriesIndex].data[dataIndex] = ecData.get(dragged, 'value');
                    }
                }
            }
            status.dragIn = status.dragIn || true;
            this.isDrop = false;
            var self = this;
            setTimeout(function () {
                self.zr.trigger('mousemove', param.event);
            }, 300);
            return;
        },
        ondragend: function (param, status) {
            if (!this.isDragend || !param.target || status.dragOut) {
                return;
            }
            var target = param.target;
            var seriesIndex = ecData.get(target, 'seriesIndex');
            var dataIndex = ecData.get(target, 'dataIndex');
            var series = this.series;
            if (series[seriesIndex].data[dataIndex].value != null) {
                series[seriesIndex].data[dataIndex].value = '-';
                var name = series[seriesIndex].data[dataIndex].name;
                if (this.component.legend && this.component.legend.getRelatedAmount(name) === 0) {
                    this.component.legend.del(name);
                }
            } else {
                series[seriesIndex].data[dataIndex] = '-';
            }
            status.dragOut = true;
            status.needRefresh = true;
            this.isDragend = false;
            return;
        },
        onlegendSelected: function (param, status) {
            var legendSelected = param.selected;
            for (var itemName in this.selectedMap) {
                if (this.selectedMap[itemName] != legendSelected[itemName]) {
                    status.needRefresh = true;
                }
                this.selectedMap[itemName] = legendSelected[itemName];
            }
            return;
        },
        _bulidPosition: function () {
            this._symbol = this.option.symbolList;
            this._sIndex2ShapeMap = {};
            this._sIndex2ColorMap = {};
            this.selectedMap = {};
            this.xMarkMap = {};
            var series = this.series;
            var _position2sIndexMap = {
                    top: [],
                    bottom: [],
                    left: [],
                    right: [],
                    other: []
                };
            var xAxisIndex;
            var yAxisIndex;
            var xAxis;
            var yAxis;
            for (var i = 0, l = series.length; i < l; i++) {
                if (series[i].type === this.type) {
                    series[i] = this.reformOption(series[i]);
                    this.legendHoverLink = series[i].legendHoverLink || this.legendHoverLink;
                    xAxisIndex = series[i].xAxisIndex;
                    yAxisIndex = series[i].yAxisIndex;
                    xAxis = this.component.xAxis.getAxis(xAxisIndex);
                    yAxis = this.component.yAxis.getAxis(yAxisIndex);
                    if (xAxis.type === ecConfig.COMPONENT_TYPE_AXIS_CATEGORY) {
                        _position2sIndexMap[xAxis.getPosition()].push(i);
                    } else if (yAxis.type === ecConfig.COMPONENT_TYPE_AXIS_CATEGORY) {
                        _position2sIndexMap[yAxis.getPosition()].push(i);
                    } else {
                        _position2sIndexMap.other.push(i);
                    }
                }
            }
            for (var position in _position2sIndexMap) {
                if (_position2sIndexMap[position].length > 0) {
                    this._buildSinglePosition(position, _position2sIndexMap[position]);
                }
            }
            this.addShapeList();
        },
        _buildSinglePosition: function (position, seriesArray) {
            var mapData = this._mapData(seriesArray);
            var locationMap = mapData.locationMap;
            var maxDataLength = mapData.maxDataLength;
            if (maxDataLength === 0 || locationMap.length === 0) {
                return;
            }
            switch (position) {
            case 'bottom':
            case 'top':
                this._buildHorizontal(seriesArray, maxDataLength, locationMap, this.xMarkMap);
                break;
            case 'left':
            case 'right':
                this._buildVertical(seriesArray, maxDataLength, locationMap, this.xMarkMap);
                break;
            case 'other':
                this._buildOther(seriesArray, maxDataLength, locationMap, this.xMarkMap);
                break;
            }
            for (var i = 0, l = seriesArray.length; i < l; i++) {
                this.buildMark(seriesArray[i]);
            }
        },
        _mapData: function (seriesArray) {
            var series = this.series;
            var serie;
            var dataIndex = 0;
            var stackMap = {};
            var magicStackKey = '__kener__stack__';
            var stackKey;
            var serieName;
            var legend = this.component.legend;
            var locationMap = [];
            var maxDataLength = 0;
            var iconShape;
            for (var i = 0, l = seriesArray.length; i < l; i++) {
                serie = series[seriesArray[i]];
                serieName = serie.name;
                this._sIndex2ShapeMap[seriesArray[i]] = this._sIndex2ShapeMap[seriesArray[i]] || this.query(serie, 'symbol') || this._symbol[i % this._symbol.length];
                if (legend) {
                    this.selectedMap[serieName] = legend.isSelected(serieName);
                    this._sIndex2ColorMap[seriesArray[i]] = legend.getColor(serieName);
                    iconShape = legend.getItemShape(serieName);
                    if (iconShape) {
                        if (this.type == ecConfig.CHART_TYPE_LINE) {
                            iconShape.style.iconType = 'legendLineIcon';
                            iconShape.style.symbol = this._sIndex2ShapeMap[seriesArray[i]];
                        } else if (serie.itemStyle.normal.barBorderWidth > 0) {
                            iconShape.style.x += 1;
                            iconShape.style.y += 1;
                            iconShape.style.width -= 2;
                            iconShape.style.height -= 2;
                            iconShape.style.strokeColor = iconShape.highlightStyle.strokeColor = serie.itemStyle.normal.barBorderColor;
                            iconShape.highlightStyle.lineWidth = 3;
                            iconShape.style.brushType = 'both';
                        }
                        legend.setItemShape(serieName, iconShape);
                    }
                } else {
                    this.selectedMap[serieName] = true;
                    this._sIndex2ColorMap[seriesArray[i]] = this.zr.getColor(seriesArray[i]);
                }
                if (this.selectedMap[serieName]) {
                    stackKey = serie.stack || magicStackKey + seriesArray[i];
                    if (stackMap[stackKey] == null) {
                        stackMap[stackKey] = dataIndex;
                        locationMap[dataIndex] = [seriesArray[i]];
                        dataIndex++;
                    } else {
                        locationMap[stackMap[stackKey]].push(seriesArray[i]);
                    }
                }
                maxDataLength = Math.max(maxDataLength, serie.data.length);
            }
            return {
                locationMap: locationMap,
                maxDataLength: maxDataLength
            };
        },
        _calculMarkMapXY: function (xMarkMap, locationMap, xy) {
            var series = this.series;
            for (var j = 0, k = locationMap.length; j < k; j++) {
                for (var m = 0, n = locationMap[j].length; m < n; m++) {
                    var seriesIndex = locationMap[j][m];
                    var valueIndex = xy == 'xy' ? 0 : '';
                    if (xy.indexOf('x') != '-1') {
                        if (xMarkMap[seriesIndex]['counter' + valueIndex] > 0) {
                            xMarkMap[seriesIndex]['average' + valueIndex] = (xMarkMap[seriesIndex]['sum' + valueIndex] / xMarkMap[seriesIndex]['counter' + valueIndex]).toFixed(2) - 0;
                        }
                        var x = this.component.xAxis.getAxis(series[seriesIndex].xAxisIndex || 0).getCoord(xMarkMap[seriesIndex]['average' + valueIndex]);
                        xMarkMap[seriesIndex]['averageLine' + valueIndex] = [
                            [
                                x,
                                this.component.grid.getYend()
                            ],
                            [
                                x,
                                this.component.grid.getY()
                            ]
                        ];
                        xMarkMap[seriesIndex]['minLine' + valueIndex] = [
                            [
                                xMarkMap[seriesIndex]['minX' + valueIndex],
                                this.component.grid.getYend()
                            ],
                            [
                                xMarkMap[seriesIndex]['minX' + valueIndex],
                                this.component.grid.getY()
                            ]
                        ];
                        xMarkMap[seriesIndex]['maxLine' + valueIndex] = [
                            [
                                xMarkMap[seriesIndex]['maxX' + valueIndex],
                                this.component.grid.getYend()
                            ],
                            [
                                xMarkMap[seriesIndex]['maxX' + valueIndex],
                                this.component.grid.getY()
                            ]
                        ];
                        xMarkMap[seriesIndex].isHorizontal = false;
                    }
                    valueIndex = xy == 'xy' ? 1 : '';
                    if (xy.indexOf('y') != '-1') {
                        if (xMarkMap[seriesIndex]['counter' + valueIndex] > 0) {
                            xMarkMap[seriesIndex]['average' + valueIndex] = (xMarkMap[seriesIndex]['sum' + valueIndex] / xMarkMap[seriesIndex]['counter' + valueIndex]).toFixed(2) - 0;
                        }
                        var y = this.component.yAxis.getAxis(series[seriesIndex].yAxisIndex || 0).getCoord(xMarkMap[seriesIndex]['average' + valueIndex]);
                        xMarkMap[seriesIndex]['averageLine' + valueIndex] = [
                            [
                                this.component.grid.getX(),
                                y
                            ],
                            [
                                this.component.grid.getXend(),
                                y
                            ]
                        ];
                        xMarkMap[seriesIndex]['minLine' + valueIndex] = [
                            [
                                this.component.grid.getX(),
                                xMarkMap[seriesIndex]['minY' + valueIndex]
                            ],
                            [
                                this.component.grid.getXend(),
                                xMarkMap[seriesIndex]['minY' + valueIndex]
                            ]
                        ];
                        xMarkMap[seriesIndex]['maxLine' + valueIndex] = [
                            [
                                this.component.grid.getX(),
                                xMarkMap[seriesIndex]['maxY' + valueIndex]
                            ],
                            [
                                this.component.grid.getXend(),
                                xMarkMap[seriesIndex]['maxY' + valueIndex]
                            ]
                        ];
                        xMarkMap[seriesIndex].isHorizontal = true;
                    }
                }
            }
        },
        addLabel: function (tarShape, serie, data, name, orient) {
            var queryTarget = [
                    data,
                    serie
                ];
            var nLabel = this.deepMerge(queryTarget, 'itemStyle.normal.label');
            var eLabel = this.deepMerge(queryTarget, 'itemStyle.emphasis.label');
            var nTextStyle = nLabel.textStyle || {};
            var eTextStyle = eLabel.textStyle || {};
            if (nLabel.show) {
                tarShape.style.text = this._getLabelText(serie, data, name, 'normal');
                tarShape.style.textPosition = nLabel.position == null ? orient === 'horizontal' ? 'right' : 'top' : nLabel.position;
                tarShape.style.textColor = nTextStyle.color;
                tarShape.style.textFont = this.getFont(nTextStyle);
            }
            if (eLabel.show) {
                tarShape.highlightStyle.text = this._getLabelText(serie, data, name, 'emphasis');
                tarShape.highlightStyle.textPosition = nLabel.show ? tarShape.style.textPosition : eLabel.position == null ? orient === 'horizontal' ? 'right' : 'top' : eLabel.position;
                tarShape.highlightStyle.textColor = eTextStyle.color;
                tarShape.highlightStyle.textFont = this.getFont(eTextStyle);
            }
            return tarShape;
        },
        _getLabelText: function (serie, data, name, status) {
            var formatter = this.deepQuery([
                    data,
                    serie
                ], 'itemStyle.' + status + '.label.formatter');
            if (!formatter && status === 'emphasis') {
                formatter = this.deepQuery([
                    data,
                    serie
                ], 'itemStyle.normal.label.formatter');
            }
            var value = data != null ? data.value != null ? data.value : data : '-';
            if (formatter) {
                if (typeof formatter === 'function') {
                    return formatter.call(this.myChart, serie.name, name, value);
                } else if (typeof formatter === 'string') {
                    formatter = formatter.replace('{a}', '{a0}').replace('{b}', '{b0}').replace('{c}', '{c0}');
                    formatter = formatter.replace('{a0}', serie.name).replace('{b0}', name).replace('{c0}', this.numAddCommas(value));
                    return formatter;
                }
            } else {
                return this.numAddCommas(value);
            }
        },
        buildMark: function (seriesIndex) {
            var serie = this.series[seriesIndex];
            if (this.selectedMap[serie.name]) {
                serie.markPoint && this._buildMarkPoint(seriesIndex);
                serie.markLine && this._buildMarkLine(seriesIndex);
            }
        },
        _buildMarkPoint: function (seriesIndex) {
            var attachStyle = (this.markAttachStyle || {})[seriesIndex];
            var serie = this.series[seriesIndex];
            var _zlevelBase = this.getZlevelBase();
            var mpData;
            var pos;
            var markPoint = zrUtil.clone(serie.markPoint);
            for (var i = 0, l = markPoint.data.length; i < l; i++) {
                mpData = markPoint.data[i];
                pos = this.getMarkCoord(seriesIndex, mpData);
                markPoint.data[i].x = mpData.x != null ? mpData.x : pos[0];
                markPoint.data[i].y = mpData.y != null ? mpData.y : pos[1];
                if (mpData.type && (mpData.type === 'max' || mpData.type === 'min')) {
                    markPoint.data[i].value = pos[3];
                    markPoint.data[i].name = mpData.name || mpData.type;
                    markPoint.data[i].symbolSize = markPoint.data[i].symbolSize || zrArea.getTextWidth(pos[3], this.getFont()) / 2 + 5;
                }
            }
            var shapeList = this._markPoint(seriesIndex, markPoint);
            for (var i = 0, l = shapeList.length; i < l; i++) {
                shapeList[i].zlevel = _zlevelBase + 1;
                for (var key in attachStyle) {
                    shapeList[i][key] = zrUtil.clone(attachStyle[key]);
                }
                this.shapeList.push(shapeList[i]);
            }
            if (this.type === ecConfig.CHART_TYPE_FORCE || this.type === ecConfig.CHART_TYPE_CHORD) {
                for (var i = 0, l = shapeList.length; i < l; i++) {
                    this.zr.addShape(shapeList[i]);
                }
            }
        },
        _buildMarkLine: function (seriesIndex) {
            var attachStyle = (this.markAttachStyle || {})[seriesIndex];
            var serie = this.series[seriesIndex];
            var _zlevelBase = this.getZlevelBase();
            var mlData;
            var pos;
            var markLine = zrUtil.clone(serie.markLine);
            for (var i = 0, l = markLine.data.length; i < l; i++) {
                mlData = markLine.data[i];
                if (mlData.type && (mlData.type === 'max' || mlData.type === 'min' || mlData.type === 'average')) {
                    pos = this.getMarkCoord(seriesIndex, mlData);
                    markLine.data[i] = [
                        zrUtil.clone(mlData),
                        {}
                    ];
                    markLine.data[i][0].name = mlData.name || mlData.type;
                    markLine.data[i][0].value = pos[3];
                    pos = pos[2];
                    mlData = [
                        {},
                        {}
                    ];
                } else {
                    pos = [
                        this.getMarkCoord(seriesIndex, mlData[0]),
                        this.getMarkCoord(seriesIndex, mlData[1])
                    ];
                }
                if (pos == null || pos[0] == null || pos[1] == null) {
                    continue;
                }
                markLine.data[i][0].x = mlData[0].x != null ? mlData[0].x : pos[0][0];
                markLine.data[i][0].y = mlData[0].y != null ? mlData[0].y : pos[0][1];
                markLine.data[i][1].x = mlData[1].x != null ? mlData[1].x : pos[1][0];
                markLine.data[i][1].y = mlData[1].y != null ? mlData[1].y : pos[1][1];
            }
            var shapeList = this._markLine(seriesIndex, markLine);
            for (var i = 0, l = shapeList.length; i < l; i++) {
                shapeList[i].zlevel = _zlevelBase + 1;
                for (var key in attachStyle) {
                    shapeList[i][key] = zrUtil.clone(attachStyle[key]);
                }
                this.shapeList.push(shapeList[i]);
            }
            if (this.type === ecConfig.CHART_TYPE_FORCE || this.type === ecConfig.CHART_TYPE_CHORD) {
                for (var i = 0, l = shapeList.length; i < l; i++) {
                    this.zr.addShape(shapeList[i]);
                }
            }
        },
        _markPoint: function (seriesIndex, mpOption) {
            var serie = this.series[seriesIndex];
            var component = this.component;
            zrUtil.merge(mpOption, this.ecTheme.markPoint);
            mpOption.name = serie.name;
            var pList = [];
            var data = mpOption.data;
            var itemShape;
            var dataRange = component.dataRange;
            var legend = component.legend;
            var color;
            var value;
            var queryTarget;
            var nColor;
            var eColor;
            var effect;
            var zrWidth = this.zr.getWidth();
            var zrHeight = this.zr.getHeight();
            if (!mpOption.large) {
                for (var i = 0, l = data.length; i < l; i++) {
                    if (data[i].x == null || data[i].y == null) {
                        continue;
                    }
                    value = data[i] != null && data[i].value != null ? data[i].value : '';
                    if (legend) {
                        color = legend.getColor(serie.name);
                    }
                    if (dataRange) {
                        color = isNaN(value) ? color : dataRange.getColor(value);
                        queryTarget = [
                            data[i],
                            mpOption
                        ];
                        nColor = this.deepQuery(queryTarget, 'itemStyle.normal.color') || color;
                        eColor = this.deepQuery(queryTarget, 'itemStyle.emphasis.color') || nColor;
                        if (nColor == null && eColor == null) {
                            continue;
                        }
                    }
                    color = color == null ? this.zr.getColor(seriesIndex) : color;
                    data[i].tooltip = data[i].tooltip || mpOption.tooltip || { trigger: 'item' };
                    data[i].name = data[i].name != null ? data[i].name : '';
                    data[i].value = value;
                    itemShape = this.getSymbolShape(mpOption, seriesIndex, data[i], i, data[i].name, this.parsePercent(data[i].x, zrWidth), this.parsePercent(data[i].y, zrHeight), 'pin', color, 'rgba(0,0,0,0)', 'horizontal');
                    itemShape._mark = 'point';
                    effect = this.deepMerge([
                        data[i],
                        mpOption
                    ], 'effect');
                    if (effect.show) {
                        itemShape.effect = effect;
                    }
                    if (serie.type === ecConfig.CHART_TYPE_MAP) {
                        itemShape._geo = this.getMarkGeo(data[i]);
                    }
                    ecData.pack(itemShape, serie, seriesIndex, data[i], i, data[i].name, value);
                    pList.push(itemShape);
                }
            } else {
                itemShape = this.getLargeMarkPoingShape(seriesIndex, mpOption);
                itemShape._mark = 'largePoint';
                itemShape && pList.push(itemShape);
            }
            return pList;
        },
        _markLine: function (seriesIndex, mlOption) {
            var serie = this.series[seriesIndex];
            var component = this.component;
            zrUtil.merge(mlOption, this.ecTheme.markLine);
            mlOption.symbol = mlOption.symbol instanceof Array ? mlOption.symbol.length > 1 ? mlOption.symbol : [
                mlOption.symbol[0],
                mlOption.symbol[0]
            ] : [
                mlOption.symbol,
                mlOption.symbol
            ];
            mlOption.symbolSize = mlOption.symbolSize instanceof Array ? mlOption.symbolSize.length > 1 ? mlOption.symbolSize : [
                mlOption.symbolSize[0],
                mlOption.symbolSize[0]
            ] : [
                mlOption.symbolSize,
                mlOption.symbolSize
            ];
            mlOption.symbolRotate = mlOption.symbolRotate instanceof Array ? mlOption.symbolRotate.length > 1 ? mlOption.symbolRotate : [
                mlOption.symbolRotate[0],
                mlOption.symbolRotate[0]
            ] : [
                mlOption.symbolRotate,
                mlOption.symbolRotate
            ];
            mlOption.name = serie.name;
            var pList = [];
            var data = mlOption.data;
            var itemShape;
            var dataRange = component.dataRange;
            var legend = component.legend;
            var color;
            var value;
            var queryTarget;
            var nColor;
            var eColor;
            var effect;
            var zrWidth = this.zr.getWidth();
            var zrHeight = this.zr.getHeight();
            var mergeData;
            for (var i = 0, l = data.length; i < l; i++) {
                if (data[i][0].x == null || data[i][0].y == null || data[i][1].x == null || data[i][1].y == null) {
                    continue;
                }
                color = legend ? legend.getColor(serie.name) : this.zr.getColor(seriesIndex);
                mergeData = this.deepMerge(data[i]);
                value = mergeData != null && mergeData.value != null ? mergeData.value : '';
                if (dataRange) {
                    color = isNaN(value) ? color : dataRange.getColor(value);
                    queryTarget = [
                        mergeData,
                        mlOption
                    ];
                    nColor = this.deepQuery(queryTarget, 'itemStyle.normal.color') || color;
                    eColor = this.deepQuery(queryTarget, 'itemStyle.emphasis.color') || nColor;
                    if (nColor == null && eColor == null) {
                        continue;
                    }
                }
                data[i][0].tooltip = mergeData.tooltip || { trigger: 'item' };
                data[i][0].name = data[i][0].name != null ? data[i][0].name : '';
                data[i][1].name = data[i][1].name != null ? data[i][1].name : '';
                data[i][0].value = data[i][0].value != null ? data[i][0].value : '';
                itemShape = this.getLineMarkShape(mlOption, seriesIndex, data[i], i, this.parsePercent(data[i][0].x, zrWidth), this.parsePercent(data[i][0].y, zrHeight), this.parsePercent(data[i][1].x, zrWidth), this.parsePercent(data[i][1].y, zrHeight), color);
                itemShape._mark = 'line';
                effect = this.deepMerge([
                    mergeData,
                    mlOption
                ], 'effect');
                if (effect.show) {
                    itemShape.effect = effect;
                }
                if (serie.type === ecConfig.CHART_TYPE_MAP) {
                    itemShape._geo = [
                        this.getMarkGeo(data[i][0]),
                        this.getMarkGeo(data[i][1])
                    ];
                }
                ecData.pack(itemShape, serie, seriesIndex, data[i][0], i, data[i][0].name + (data[i][1].name !== '' ? ' > ' + data[i][1].name : ''), value);
                pList.push(itemShape);
            }
            return pList;
        },
        getMarkCoord: function () {
            return [
                0,
                0
            ];
        },
        getSymbolShape: function (serie, seriesIndex, data, dataIndex, name, x, y, symbol, color, emptyColor, orient) {
            var queryTarget = [
                    data,
                    serie
                ];
            var value = data != null ? data.value != null ? data.value : data : '-';
            symbol = this.deepQuery(queryTarget, 'symbol') || symbol;
            var symbolSize = this.deepQuery(queryTarget, 'symbolSize');
            symbolSize = typeof symbolSize === 'function' ? symbolSize(value) : symbolSize;
            var symbolRotate = this.deepQuery(queryTarget, 'symbolRotate');
            var normal = this.deepMerge(queryTarget, 'itemStyle.normal');
            var emphasis = this.deepMerge(queryTarget, 'itemStyle.emphasis');
            var nBorderWidth = normal.borderWidth != null ? normal.borderWidth : normal.lineStyle && normal.lineStyle.width;
            if (nBorderWidth == null) {
                nBorderWidth = symbol.match('empty') ? 2 : 0;
            }
            var eBorderWidth = emphasis.borderWidth != null ? emphasis.borderWidth : emphasis.lineStyle && emphasis.lineStyle.width;
            if (eBorderWidth == null) {
                eBorderWidth = nBorderWidth + 2;
            }
            var itemShape = new IconShape({
                    style: {
                        iconType: symbol.replace('empty', '').toLowerCase(),
                        x: x - symbolSize,
                        y: y - symbolSize,
                        width: symbolSize * 2,
                        height: symbolSize * 2,
                        brushType: 'both',
                        color: symbol.match('empty') ? emptyColor : this.getItemStyleColor(normal.color, seriesIndex, dataIndex, data) || color,
                        strokeColor: normal.borderColor || this.getItemStyleColor(normal.color, seriesIndex, dataIndex, data) || color,
                        lineWidth: nBorderWidth
                    },
                    highlightStyle: {
                        color: symbol.match('empty') ? emptyColor : this.getItemStyleColor(emphasis.color, seriesIndex, dataIndex, data),
                        strokeColor: emphasis.borderColor || normal.borderColor || this.getItemStyleColor(normal.color, seriesIndex, dataIndex, data) || color,
                        lineWidth: eBorderWidth
                    },
                    clickable: this.deepQuery(queryTarget, 'clickable')
                });
            if (symbol.match('image')) {
                itemShape.style.image = symbol.replace(new RegExp('^image:\\/\\/'), '');
                itemShape = new ImageShape({
                    style: itemShape.style,
                    highlightStyle: itemShape.highlightStyle,
                    clickable: this.deepQuery(queryTarget, 'clickable')
                });
            }
            if (symbolRotate != null) {
                itemShape.rotation = [
                    symbolRotate * Math.PI / 180,
                    x,
                    y
                ];
            }
            if (symbol.match('star')) {
                itemShape.style.iconType = 'star';
                itemShape.style.n = symbol.replace('empty', '').replace('star', '') - 0 || 5;
            }
            if (symbol === 'none') {
                itemShape.invisible = true;
                itemShape.hoverable = false;
            }
            itemShape = this.addLabel(itemShape, serie, data, name, orient);
            if (symbol.match('empty')) {
                if (itemShape.style.textColor == null) {
                    itemShape.style.textColor = itemShape.style.strokeColor;
                }
                if (itemShape.highlightStyle.textColor == null) {
                    itemShape.highlightStyle.textColor = itemShape.highlightStyle.strokeColor;
                }
            }
            ecData.pack(itemShape, serie, seriesIndex, data, dataIndex, name);
            itemShape._x = x;
            itemShape._y = y;
            itemShape._dataIndex = dataIndex;
            itemShape._seriesIndex = seriesIndex;
            return itemShape;
        },
        getLineMarkShape: function (mlOption, seriesIndex, data, dataIndex, xStart, yStart, xEnd, yEnd, color) {
            var value0 = data[0] != null ? data[0].value != null ? data[0].value : data[0] : '-';
            var value1 = data[1] != null ? data[1].value != null ? data[1].value : data[1] : '-';
            var symbol = [
                    this.query(data[0], 'symbol') || mlOption.symbol[0],
                    this.query(data[1], 'symbol') || mlOption.symbol[1]
                ];
            var symbolSize = [
                    this.query(data[0], 'symbolSize') || mlOption.symbolSize[0],
                    this.query(data[1], 'symbolSize') || mlOption.symbolSize[1]
                ];
            symbolSize[0] = typeof symbolSize[0] === 'function' ? symbolSize[0](value0) : symbolSize[0];
            symbolSize[1] = typeof symbolSize[1] === 'function' ? symbolSize[1](value1) : symbolSize[1];
            var symbolRotate = [
                    this.query(data[0], 'symbolRotate') || mlOption.symbolRotate[0],
                    this.query(data[1], 'symbolRotate') || mlOption.symbolRotate[1]
                ];
            var queryTarget = [
                    data[0],
                    mlOption
                ];
            var normal = this.deepMerge(queryTarget, 'itemStyle.normal');
            normal.color = this.getItemStyleColor(normal.color, seriesIndex, dataIndex, data);
            var emphasis = this.deepMerge(queryTarget, 'itemStyle.emphasis');
            emphasis.color = this.getItemStyleColor(emphasis.color, seriesIndex, dataIndex, data);
            var nlineStyle = normal.lineStyle;
            var elineStyle = emphasis.lineStyle;
            var nBorderWidth = nlineStyle.width;
            if (nBorderWidth == null) {
                nBorderWidth = normal.borderWidth;
            }
            var eBorderWidth = elineStyle.width;
            if (eBorderWidth == null) {
                eBorderWidth = emphasis.borderWidth != null ? emphasis.borderWidth : nBorderWidth + 2;
            }
            var itemShape = new MarkLineShape({
                    style: {
                        smooth: mlOption.smooth ? 'spline' : false,
                        symbol: symbol,
                        symbolSize: symbolSize,
                        symbolRotate: symbolRotate,
                        xStart: xStart,
                        yStart: yStart,
                        xEnd: xEnd,
                        yEnd: yEnd,
                        brushType: 'both',
                        lineType: nlineStyle.type,
                        shadowColor: nlineStyle.shadowColor || nlineStyle.color || normal.borderColor || normal.color || color,
                        shadowBlur: nlineStyle.shadowBlur,
                        shadowOffsetX: nlineStyle.shadowOffsetX,
                        shadowOffsetY: nlineStyle.shadowOffsetY,
                        color: normal.color || color,
                        strokeColor: nlineStyle.color || normal.borderColor || normal.color || color,
                        lineWidth: nBorderWidth,
                        symbolBorderColor: normal.borderColor || normal.color || color,
                        symbolBorder: normal.borderWidth
                    },
                    highlightStyle: {
                        shadowColor: elineStyle.shadowColor,
                        shadowBlur: elineStyle.shadowBlur,
                        shadowOffsetX: elineStyle.shadowOffsetX,
                        shadowOffsetY: elineStyle.shadowOffsetY,
                        color: emphasis.color || normal.color || color,
                        strokeColor: elineStyle.color || nlineStyle.color || emphasis.borderColor || normal.borderColor || emphasis.color || normal.color || color,
                        lineWidth: eBorderWidth,
                        symbolBorderColor: emphasis.borderColor || normal.borderColor || emphasis.color || normal.color || color,
                        symbolBorder: emphasis.borderWidth == null ? normal.borderWidth + 2 : emphasis.borderWidth
                    },
                    clickable: this.deepQuery(queryTarget, 'clickable')
                });
            itemShape = this.addLabel(itemShape, mlOption, data[0], data[0].name + ' : ' + data[1].name);
            itemShape._x = xEnd;
            itemShape._y = yEnd;
            return itemShape;
        },
        getLargeMarkPoingShape: function (seriesIndex, mpOption) {
            var serie = this.series[seriesIndex];
            var component = this.component;
            var data = mpOption.data;
            var itemShape;
            var dataRange = component.dataRange;
            var legend = component.legend;
            var color;
            var value;
            var queryTarget = [
                    data[0],
                    mpOption
                ];
            var nColor;
            var eColor;
            var effect;
            if (legend) {
                color = legend.getColor(serie.name);
            }
            if (dataRange) {
                value = data[0] != null ? data[0].value != null ? data[0].value : data[0] : '-';
                color = isNaN(value) ? color : dataRange.getColor(value);
                nColor = this.deepQuery(queryTarget, 'itemStyle.normal.color') || color;
                eColor = this.deepQuery(queryTarget, 'itemStyle.emphasis.color') || nColor;
                if (nColor == null && eColor == null) {
                    return;
                }
            }
            color = this.deepMerge(queryTarget, 'itemStyle.normal').color || color;
            var symbol = this.deepQuery(queryTarget, 'symbol') || 'circle';
            symbol = symbol.replace('empty', '').replace(/\d/g, '');
            effect = this.deepMerge([
                data[0],
                mpOption
            ], 'effect');
            var devicePixelRatio = window.devicePixelRatio || 1;
            itemShape = new SymbolShape({
                style: {
                    pointList: data,
                    color: color,
                    strokeColor: color,
                    shadowColor: effect.shadowColor || color,
                    shadowBlur: (effect.shadowBlur != null ? effect.shadowBlur : 8) * devicePixelRatio,
                    size: this.deepQuery(queryTarget, 'symbolSize'),
                    iconType: symbol,
                    brushType: 'fill',
                    lineWidth: 1
                },
                draggable: false,
                hoverable: false
            });
            if (effect.show) {
                itemShape.effect = effect;
            }
            return itemShape;
        },
        backupShapeList: function () {
            if (this.shapeList && this.shapeList.length > 0) {
                this.lastShapeList = this.shapeList;
                this.shapeList = [];
            } else {
                this.lastShapeList = [];
            }
        },
        addShapeList: function () {
            var maxLenth = this.option.animationThreshold / (this.canvasSupported ? 2 : 4);
            var lastShapeList = this.lastShapeList;
            var shapeList = this.shapeList;
            var duration = lastShapeList.length > 0 ? 500 : this.query(this.option, 'animationDuration');
            var easing = this.query(this.option, 'animationEasing');
            var delay;
            var key;
            var oldMap = {};
            var newMap = {};
            if (this.option.animation && !this.option.renderAsImage && shapeList.length < maxLenth && !this.motionlessOnce) {
                for (var i = 0, l = lastShapeList.length; i < l; i++) {
                    key = this._getAnimationKey(lastShapeList[i]);
                    if (key.match('undefined')) {
                        this.zr.delShape(lastShapeList[i].id);
                    } else {
                        key += lastShapeList[i].type;
                        oldMap[key] = lastShapeList[i];
                    }
                }
                for (var i = 0, l = shapeList.length; i < l; i++) {
                    key = this._getAnimationKey(shapeList[i]);
                    if (key.match('undefined')) {
                        this.zr.addShape(shapeList[i]);
                    } else {
                        key += shapeList[i].type;
                        newMap[key] = shapeList[i];
                    }
                }
                for (key in oldMap) {
                    if (!newMap[key]) {
                        this.zr.delShape(oldMap[key].id);
                    }
                }
                for (key in newMap) {
                    if (oldMap[key]) {
                        this.zr.delShape(oldMap[key].id);
                        this._animateMod(oldMap[key], newMap[key], duration, easing);
                    } else {
                        delay = (this.type == ecConfig.CHART_TYPE_LINE || this.type == ecConfig.CHART_TYPE_RADAR) && key.indexOf('icon') !== 0 ? duration / 2 : 0;
                        this._animateMod(false, newMap[key], duration, easing, delay);
                    }
                }
                this.zr.refresh();
                this.animationEffect();
            } else {
                this.motionlessOnce = false;
                this.zr.delShape(lastShapeList);
                for (var i = 0, l = shapeList.length; i < l; i++) {
                    this.zr.addShape(shapeList[i]);
                }
            }
        },
        _getAnimationKey: function (shape) {
            if (this.type != ecConfig.CHART_TYPE_MAP) {
                return ecData.get(shape, 'seriesIndex') + '_' + ecData.get(shape, 'dataIndex') + (shape._mark ? shape._mark : '') + (this.type === ecConfig.CHART_TYPE_RADAR ? ecData.get(shape, 'special') : '');
            } else {
                return ecData.get(shape, 'seriesIndex') + '_' + ecData.get(shape, 'dataIndex') + (shape._mark ? shape._mark : 'undefined');
            }
        },
        _animateMod: function (oldShape, newShape, duration, easing, delay) {
            switch (newShape.type) {
            case 'broken-line':
            case 'half-smooth-polygon':
                ecAnimation.pointList(this.zr, oldShape, newShape, duration, easing);
                break;
            case 'rectangle':
                ecAnimation.rectangle(this.zr, oldShape, newShape, duration, easing);
                break;
            case 'icon':
                ecAnimation.icon(this.zr, oldShape, newShape, duration, easing, delay);
                break;
            case 'candle':
                if (duration > 500) {
                    ecAnimation.candle(this.zr, oldShape, newShape, duration, easing);
                } else {
                    this.zr.addShape(newShape);
                }
                break;
            case 'ring':
            case 'sector':
            case 'circle':
                if (duration > 500) {
                    ecAnimation.ring(this.zr, oldShape, newShape, duration + (ecData.get(newShape, 'dataIndex') || 0) % 20 * 100, easing);
                } else if (newShape.type === 'sector') {
                    ecAnimation.sector(this.zr, oldShape, newShape, duration, easing);
                } else {
                    this.zr.addShape(newShape);
                }
                break;
            case 'text':
                ecAnimation.text(this.zr, oldShape, newShape, duration, easing);
                break;
            case 'polygon':
                if (duration > 500) {
                    ecAnimation.polygon(this.zr, oldShape, newShape, duration, easing);
                } else {
                    ecAnimation.pointList(this.zr, oldShape, newShape, duration, easing);
                }
                break;
            case 'ribbon':
                ecAnimation.ribbon(this.zr, oldShape, newShape, duration, easing);
                break;
            case 'gauge-pointer':
                ecAnimation.gaugePointer(this.zr, oldShape, newShape, duration, easing);
                break;
            case 'mark-line':
                ecAnimation.markline(this.zr, oldShape, newShape, duration, easing);
                break;
            case 'bezier-curve':
            case 'line':
                ecAnimation.line(this.zr, oldShape, newShape, duration, easing);
                break;
            default:
                this.zr.addShape(newShape);
                break;
            }
        },
        animationMark: function (duration, easing, addShapeList) {
            var shapeList = addShapeList || this.shapeList;
            for (var i = 0, l = shapeList.length; i < l; i++) {
                if (!shapeList[i]._mark) {
                    continue;
                }
                this._animateMod(false, shapeList[i], duration, easing);
            }
            this.animationEffect(addShapeList);
        },
        animationEffect: function (addShapeList) {
            !addShapeList && this.clearEffectShape();
            var shapeList = addShapeList || this.shapeList;
            if (shapeList == null) {
                return;
            }
            var zlevel = ecConfig.EFFECT_ZLEVEL;
            if (this.canvasSupported) {
                this.zr.modLayer(zlevel, {
                    motionBlur: true,
                    lastFrameAlpha: 0.95
                });
            }
            var shape;
            for (var i = 0, l = shapeList.length; i < l; i++) {
                shape = shapeList[i];
                if (!(shape._mark && shape.effect && shape.effect.show && ecEffect[shape._mark])) {
                    continue;
                }
                ecEffect[shape._mark](this.zr, this.effectList, shape, zlevel);
                this.effectList[this.effectList.length - 1]._mark = shape._mark;
            }
        },
        clearEffectShape: function (clearMotionBlur) {
            if (this.zr && this.effectList && this.effectList.length > 0) {
                clearMotionBlur && this.zr.modLayer(ecConfig.EFFECT_ZLEVEL, { motionBlur: false });
                this.zr.delShape(this.effectList);
            }
            this.effectList = [];
        },
        addMark: function (seriesIndex, markData, markType) {
            var serie = this.series[seriesIndex];
            if (this.selectedMap[serie.name]) {
                var duration = 500;
                var easing = this.query(this.option, 'animationEasing');
                var oriMarkData = serie[markType].data;
                var lastLength = this.shapeList.length;
                serie[markType].data = markData.data;
                this['_build' + markType.replace('m', 'M')](seriesIndex);
                if (this.option.animation && !this.option.renderAsImage) {
                    this.animationMark(duration, easing, this.shapeList.slice(lastLength));
                } else {
                    for (var i = lastLength, l = this.shapeList.length; i < l; i++) {
                        this.zr.addShape(this.shapeList[i]);
                    }
                    this.zr.refresh();
                }
                serie[markType].data = oriMarkData;
            }
        },
        delMark: function (seriesIndex, markName, markType) {
            markType = markType.replace('mark', '').replace('large', '').toLowerCase();
            var serie = this.series[seriesIndex];
            if (this.selectedMap[serie.name]) {
                var needRefresh = false;
                var shapeList = [
                        this.shapeList,
                        this.effectList
                    ];
                var len = 2;
                while (len--) {
                    for (var i = 0, l = shapeList[len].length; i < l; i++) {
                        if (shapeList[len][i]._mark == markType && ecData.get(shapeList[len][i], 'seriesIndex') == seriesIndex && ecData.get(shapeList[len][i], 'name') == markName) {
                            this.zr.delShape(shapeList[len][i].id);
                            shapeList[len].splice(i, 1);
                            needRefresh = true;
                            break;
                        }
                    }
                }
                needRefresh && this.zr.refresh();
            }
        }
    };
    return Base;
});