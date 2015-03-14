define([
    'require',
    '../component/base',
    './base',
    'zrender/shape/Polygon',
    '../component/polar',
    '../config',
    '../util/ecData',
    'zrender/tool/util',
    'zrender/tool/color',
    '../util/accMath',
    '../chart'
], function (require) {
    var ComponentBase = require('../component/base');
    var ChartBase = require('./base');
    var PolygonShape = require('zrender/shape/Polygon');
    require('../component/polar');
    var ecConfig = require('../config');
    var ecData = require('../util/ecData');
    var zrUtil = require('zrender/tool/util');
    var zrColor = require('zrender/tool/color');
    function Radar(ecTheme, messageCenter, zr, option, myChart) {
        ComponentBase.call(this, ecTheme, messageCenter, zr, option, myChart);
        ChartBase.call(this);
        this.refresh(option);
    }
    Radar.prototype = {
        type: ecConfig.CHART_TYPE_RADAR,
        _buildShape: function () {
            this.selectedMap = {};
            this._symbol = this.option.symbolList;
            this._queryTarget;
            this._dropBoxList = [];
            this._radarDataCounter = 0;
            var series = this.series;
            var legend = this.component.legend;
            var serieName;
            for (var i = 0, l = series.length; i < l; i++) {
                if (series[i].type === ecConfig.CHART_TYPE_RADAR) {
                    this.serie = this.reformOption(series[i]);
                    this.legendHoverLink = series[i].legendHoverLink || this.legendHoverLink;
                    serieName = this.serie.name || '';
                    this.selectedMap[serieName] = legend ? legend.isSelected(serieName) : true;
                    if (this.selectedMap[serieName]) {
                        this._queryTarget = [
                            this.serie,
                            this.option
                        ];
                        if (this.deepQuery(this._queryTarget, 'calculable')) {
                            this._addDropBox(i);
                        }
                        this._buildSingleRadar(i);
                        this.buildMark(i);
                    }
                }
            }
            this.addShapeList();
        },
        _buildSingleRadar: function (index) {
            var legend = this.component.legend;
            var iconShape;
            var data = this.serie.data;
            var defaultColor;
            var name;
            var pointList;
            var calculable = this.deepQuery(this._queryTarget, 'calculable');
            for (var i = 0; i < data.length; i++) {
                name = data[i].name || '';
                this.selectedMap[name] = legend ? legend.isSelected(name) : true;
                if (!this.selectedMap[name]) {
                    continue;
                }
                if (legend) {
                    defaultColor = legend.getColor(name);
                    iconShape = legend.getItemShape(name);
                    if (iconShape) {
                        iconShape.style.brushType = this.deepQuery([
                            data[i],
                            this.serie
                        ], 'itemStyle.normal.areaStyle') ? 'both' : 'stroke';
                        legend.setItemShape(name, iconShape);
                    }
                } else {
                    defaultColor = this.zr.getColor(i);
                }
                pointList = this._getPointList(this.serie.polarIndex, data[i]);
                this._addSymbol(pointList, defaultColor, i, index, this.serie.polarIndex);
                this._addDataShape(pointList, defaultColor, data[i], index, i, calculable);
                this._radarDataCounter++;
            }
        },
        _getPointList: function (polarIndex, dataArr) {
            var pointList = [];
            var vector;
            var polar = this.component.polar;
            var value;
            for (var i = 0, l = dataArr.value.length; i < l; i++) {
                value = dataArr.value[i].value != null ? dataArr.value[i].value : dataArr.value[i];
                vector = value != '-' ? polar.getVector(polarIndex, i, value) : false;
                if (vector) {
                    pointList.push(vector);
                }
            }
            return pointList;
        },
        _addSymbol: function (pointList, defaultColor, dataIndex, seriesIndex, polarIndex) {
            var series = this.series;
            var itemShape;
            var polar = this.component.polar;
            for (var i = 0, l = pointList.length; i < l; i++) {
                itemShape = this.getSymbolShape(this.deepMerge([
                    series[seriesIndex].data[dataIndex],
                    series[seriesIndex]
                ]), seriesIndex, series[seriesIndex].data[dataIndex].value[i], i, polar.getIndicatorText(polarIndex, i), pointList[i][0], pointList[i][1], this._symbol[this._radarDataCounter % this._symbol.length], defaultColor, '#fff', 'vertical');
                itemShape.zlevel = this._zlevelBase + 1;
                ecData.set(itemShape, 'data', series[seriesIndex].data[dataIndex]);
                ecData.set(itemShape, 'value', series[seriesIndex].data[dataIndex].value);
                ecData.set(itemShape, 'dataIndex', dataIndex);
                ecData.set(itemShape, 'special', i);
                this.shapeList.push(itemShape);
            }
        },
        _addDataShape: function (pointList, defaultColor, data, seriesIndex, dataIndex, calculable) {
            var series = this.series;
            var queryTarget = [
                    data,
                    this.serie
                ];
            var nColor = this.getItemStyleColor(this.deepQuery(queryTarget, 'itemStyle.normal.color'), seriesIndex, dataIndex, data);
            var nLineWidth = this.deepQuery(queryTarget, 'itemStyle.normal.lineStyle.width');
            var nLineType = this.deepQuery(queryTarget, 'itemStyle.normal.lineStyle.type');
            var nAreaColor = this.deepQuery(queryTarget, 'itemStyle.normal.areaStyle.color');
            var nIsAreaFill = this.deepQuery(queryTarget, 'itemStyle.normal.areaStyle');
            var shape = {
                    zlevel: this._zlevelBase,
                    style: {
                        pointList: pointList,
                        brushType: nIsAreaFill ? 'both' : 'stroke',
                        color: nAreaColor || nColor || (typeof defaultColor === 'string' ? zrColor.alpha(defaultColor, 0.5) : defaultColor),
                        strokeColor: nColor || defaultColor,
                        lineWidth: nLineWidth,
                        lineType: nLineType
                    },
                    highlightStyle: {
                        brushType: this.deepQuery(queryTarget, 'itemStyle.emphasis.areaStyle') || nIsAreaFill ? 'both' : 'stroke',
                        color: this.deepQuery(queryTarget, 'itemStyle.emphasis.areaStyle.color') || nAreaColor || nColor || (typeof defaultColor === 'string' ? zrColor.alpha(defaultColor, 0.5) : defaultColor),
                        strokeColor: this.getItemStyleColor(this.deepQuery(queryTarget, 'itemStyle.emphasis.color'), seriesIndex, dataIndex, data) || nColor || defaultColor,
                        lineWidth: this.deepQuery(queryTarget, 'itemStyle.emphasis.lineStyle.width') || nLineWidth,
                        lineType: this.deepQuery(queryTarget, 'itemStyle.emphasis.lineStyle.type') || nLineType
                    }
                };
            ecData.pack(shape, series[seriesIndex], seriesIndex, data, dataIndex, data.name, this.component.polar.getIndicator(series[seriesIndex].polarIndex));
            if (calculable) {
                shape.draggable = true;
                this.setCalculable(shape);
            }
            shape = new PolygonShape(shape);
            this.shapeList.push(shape);
        },
        _addDropBox: function (index) {
            var series = this.series;
            var polarIndex = this.deepQuery(this._queryTarget, 'polarIndex');
            if (!this._dropBoxList[polarIndex]) {
                var shape = this.component.polar.getDropBox(polarIndex);
                shape.zlevel = this._zlevelBase;
                this.setCalculable(shape);
                ecData.pack(shape, series, index, undefined, -1);
                this.shapeList.push(shape);
                this._dropBoxList[polarIndex] = true;
            }
        },
        ondragend: function (param, status) {
            var series = this.series;
            if (!this.isDragend || !param.target) {
                return;
            }
            var target = param.target;
            var seriesIndex = ecData.get(target, 'seriesIndex');
            var dataIndex = ecData.get(target, 'dataIndex');
            this.component.legend && this.component.legend.del(series[seriesIndex].data[dataIndex].name);
            series[seriesIndex].data.splice(dataIndex, 1);
            status.dragOut = true;
            status.needRefresh = true;
            this.isDragend = false;
            return;
        },
        ondrop: function (param, status) {
            var series = this.series;
            if (!this.isDrop || !param.target) {
                return;
            }
            var target = param.target;
            var dragged = param.dragged;
            var seriesIndex = ecData.get(target, 'seriesIndex');
            var dataIndex = ecData.get(target, 'dataIndex');
            var data;
            var legend = this.component.legend;
            var value;
            if (dataIndex === -1) {
                data = {
                    value: ecData.get(dragged, 'value'),
                    name: ecData.get(dragged, 'name')
                };
                series[seriesIndex].data.push(data);
                legend && legend.add(data.name, dragged.style.color || dragged.style.strokeColor);
            } else {
                var accMath = require('../util/accMath');
                data = series[seriesIndex].data[dataIndex];
                legend && legend.del(data.name);
                data.name += this.option.nameConnector + ecData.get(dragged, 'name');
                value = ecData.get(dragged, 'value');
                for (var i = 0; i < value.length; i++) {
                    data.value[i] = accMath.accAdd(data.value[i], value[i]);
                }
                legend && legend.add(data.name, dragged.style.color || dragged.style.strokeColor);
            }
            status.dragIn = status.dragIn || true;
            this.isDrop = false;
            return;
        },
        refresh: function (newOption) {
            if (newOption) {
                this.option = newOption;
                this.series = newOption.series;
            }
            this.backupShapeList();
            this._buildShape();
        }
    };
    zrUtil.inherits(Radar, ChartBase);
    zrUtil.inherits(Radar, ComponentBase);
    require('../chart').define('radar', Radar);
    return Radar;
});