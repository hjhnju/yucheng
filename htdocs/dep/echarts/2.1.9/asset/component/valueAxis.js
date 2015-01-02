/*! 2015 Baidu Inc. All Rights Reserved */
define('echarts/component/valueAxis', function (require) {
    var Base = require('./base');
    var TextShape = require('zrender/shape/Text');
    var LineShape = require('zrender/shape/Line');
    var RectangleShape = require('zrender/shape/Rectangle');
    var ecConfig = require('../config');
    var ecDate = require('../util/date');
    var zrUtil = require('zrender/tool/util');
    function ValueAxis(ecTheme, messageCenter, zr, option, myChart, axisBase, series) {
        if (!series || series.length === 0) {
            console.err('option.series.length == 0.');
            return;
        }
        Base.call(this, ecTheme, messageCenter, zr, option, myChart);
        this.series = series;
        this.grid = this.component.grid;
        for (var method in axisBase) {
            this[method] = axisBase[method];
        }
        this.refresh(option, series);
    }
    ValueAxis.prototype = {
        type: ecConfig.COMPONENT_TYPE_AXIS_VALUE,
        _buildShape: function () {
            this._hasData = false;
            this._calculateValue();
            if (!this._hasData || !this.option.show) {
                return;
            }
            this.option.splitArea.show && this._buildSplitArea();
            this.option.splitLine.show && this._buildSplitLine();
            this.option.axisLine.show && this._buildAxisLine();
            this.option.axisTick.show && this._buildAxisTick();
            this.option.axisLabel.show && this._buildAxisLabel();
            for (var i = 0, l = this.shapeList.length; i < l; i++) {
                this.zr.addShape(this.shapeList[i]);
            }
        },
        _buildAxisTick: function () {
            var axShape;
            var data = this._valueList;
            var dataLength = this._valueList.length;
            var tickOption = this.option.axisTick;
            var length = tickOption.length;
            var color = tickOption.lineStyle.color;
            var lineWidth = tickOption.lineStyle.width;
            if (this.isHorizontal()) {
                var yPosition = this.option.position === 'bottom' ? tickOption.inside ? this.grid.getYend() - length - 1 : this.grid.getYend() + 1 : tickOption.inside ? this.grid.getY() + 1 : this.grid.getY() - length - 1;
                var x;
                for (var i = 0; i < dataLength; i++) {
                    x = this.subPixelOptimize(this.getCoord(data[i]), lineWidth);
                    axShape = {
                        _axisShape: 'axisTick',
                        zlevel: this._zlevelBase,
                        hoverable: false,
                        style: {
                            xStart: x,
                            yStart: yPosition,
                            xEnd: x,
                            yEnd: yPosition + length,
                            strokeColor: color,
                            lineWidth: lineWidth
                        }
                    };
                    this.shapeList.push(new LineShape(axShape));
                }
            } else {
                var xPosition = this.option.position === 'left' ? tickOption.inside ? this.grid.getX() + 1 : this.grid.getX() - length - 1 : tickOption.inside ? this.grid.getXend() - length - 1 : this.grid.getXend() + 1;
                var y;
                for (var i = 0; i < dataLength; i++) {
                    y = this.subPixelOptimize(this.getCoord(data[i]), lineWidth);
                    axShape = {
                        _axisShape: 'axisTick',
                        zlevel: this._zlevelBase,
                        hoverable: false,
                        style: {
                            xStart: xPosition,
                            yStart: y,
                            xEnd: xPosition + length,
                            yEnd: y,
                            strokeColor: color,
                            lineWidth: lineWidth
                        }
                    };
                    this.shapeList.push(new LineShape(axShape));
                }
            }
        },
        _buildAxisLabel: function () {
            var axShape;
            var data = this._valueList;
            var dataLength = this._valueList.length;
            var rotate = this.option.axisLabel.rotate;
            var margin = this.option.axisLabel.margin;
            var clickable = this.option.axisLabel.clickable;
            var textStyle = this.option.axisLabel.textStyle;
            if (this.isHorizontal()) {
                var yPosition;
                var baseLine;
                if (this.option.position === 'bottom') {
                    yPosition = this.grid.getYend() + margin;
                    baseLine = 'top';
                } else {
                    yPosition = this.grid.getY() - margin;
                    baseLine = 'bottom';
                }
                for (var i = 0; i < dataLength; i++) {
                    axShape = {
                        zlevel: this._zlevelBase,
                        hoverable: false,
                        style: {
                            x: this.getCoord(data[i]),
                            y: yPosition,
                            color: typeof textStyle.color === 'function' ? textStyle.color(data[i]) : textStyle.color,
                            text: this._valueLabel[i],
                            textFont: this.getFont(textStyle),
                            textAlign: textStyle.align || 'center',
                            textBaseline: textStyle.baseline || baseLine
                        }
                    };
                    if (rotate) {
                        axShape.style.textAlign = rotate > 0 ? this.option.position === 'bottom' ? 'right' : 'left' : this.option.position === 'bottom' ? 'left' : 'right';
                        axShape.rotation = [
                            rotate * Math.PI / 180,
                            axShape.style.x,
                            axShape.style.y
                        ];
                    }
                    this.shapeList.push(new TextShape(this._axisLabelClickable(clickable, axShape)));
                }
            } else {
                var xPosition;
                var align;
                if (this.option.position === 'left') {
                    xPosition = this.grid.getX() - margin;
                    align = 'right';
                } else {
                    xPosition = this.grid.getXend() + margin;
                    align = 'left';
                }
                for (var i = 0; i < dataLength; i++) {
                    axShape = {
                        zlevel: this._zlevelBase,
                        hoverable: false,
                        style: {
                            x: xPosition,
                            y: this.getCoord(data[i]),
                            color: typeof textStyle.color === 'function' ? textStyle.color(data[i]) : textStyle.color,
                            text: this._valueLabel[i],
                            textFont: this.getFont(textStyle),
                            textAlign: textStyle.align || align,
                            textBaseline: textStyle.baseline || i === 0 && this.option.name !== '' ? 'bottom' : i === dataLength - 1 && this.option.name !== '' ? 'top' : 'middle'
                        }
                    };
                    if (rotate) {
                        axShape.rotation = [
                            rotate * Math.PI / 180,
                            axShape.style.x,
                            axShape.style.y
                        ];
                    }
                    this.shapeList.push(new TextShape(this._axisLabelClickable(clickable, axShape)));
                }
            }
        },
        _buildSplitLine: function () {
            var axShape;
            var data = this._valueList;
            var dataLength = this._valueList.length;
            var sLineOption = this.option.splitLine;
            var lineType = sLineOption.lineStyle.type;
            var lineWidth = sLineOption.lineStyle.width;
            var color = sLineOption.lineStyle.color;
            color = color instanceof Array ? color : [color];
            var colorLength = color.length;
            if (this.isHorizontal()) {
                var sy = this.grid.getY();
                var ey = this.grid.getYend();
                var x;
                for (var i = 0; i < dataLength; i++) {
                    x = this.subPixelOptimize(this.getCoord(data[i]), lineWidth);
                    axShape = {
                        zlevel: this._zlevelBase,
                        hoverable: false,
                        style: {
                            xStart: x,
                            yStart: sy,
                            xEnd: x,
                            yEnd: ey,
                            strokeColor: color[i % colorLength],
                            lineType: lineType,
                            lineWidth: lineWidth
                        }
                    };
                    this.shapeList.push(new LineShape(axShape));
                }
            } else {
                var sx = this.grid.getX();
                var ex = this.grid.getXend();
                var y;
                for (var i = 0; i < dataLength; i++) {
                    y = this.subPixelOptimize(this.getCoord(data[i]), lineWidth);
                    axShape = {
                        zlevel: this._zlevelBase,
                        hoverable: false,
                        style: {
                            xStart: sx,
                            yStart: y,
                            xEnd: ex,
                            yEnd: y,
                            strokeColor: color[i % colorLength],
                            lineType: lineType,
                            lineWidth: lineWidth
                        }
                    };
                    this.shapeList.push(new LineShape(axShape));
                }
            }
        },
        _buildSplitArea: function () {
            var axShape;
            var color = this.option.splitArea.areaStyle.color;
            if (!(color instanceof Array)) {
                axShape = {
                    zlevel: this._zlevelBase,
                    hoverable: false,
                    style: {
                        x: this.grid.getX(),
                        y: this.grid.getY(),
                        width: this.grid.getWidth(),
                        height: this.grid.getHeight(),
                        color: color
                    }
                };
                this.shapeList.push(new RectangleShape(axShape));
            } else {
                var colorLength = color.length;
                var data = this._valueList;
                var dataLength = this._valueList.length;
                if (this.isHorizontal()) {
                    var y = this.grid.getY();
                    var height = this.grid.getHeight();
                    var lastX = this.grid.getX();
                    var curX;
                    for (var i = 0; i <= dataLength; i++) {
                        curX = i < dataLength ? this.getCoord(data[i]) : this.grid.getXend();
                        axShape = {
                            zlevel: this._zlevelBase,
                            hoverable: false,
                            style: {
                                x: lastX,
                                y: y,
                                width: curX - lastX,
                                height: height,
                                color: color[i % colorLength]
                            }
                        };
                        this.shapeList.push(new RectangleShape(axShape));
                        lastX = curX;
                    }
                } else {
                    var x = this.grid.getX();
                    var width = this.grid.getWidth();
                    var lastYend = this.grid.getYend();
                    var curY;
                    for (var i = 0; i <= dataLength; i++) {
                        curY = i < dataLength ? this.getCoord(data[i]) : this.grid.getY();
                        axShape = {
                            zlevel: this._zlevelBase,
                            hoverable: false,
                            style: {
                                x: x,
                                y: curY,
                                width: width,
                                height: lastYend - curY,
                                color: color[i % colorLength]
                            }
                        };
                        this.shapeList.push(new RectangleShape(axShape));
                        lastYend = curY;
                    }
                }
            }
        },
        _calculateValue: function () {
            if (isNaN(this.option.min - 0) || isNaN(this.option.max - 0)) {
                var data = {};
                var xIdx;
                var yIdx;
                var legend = this.component.legend;
                for (var i = 0, l = this.series.length; i < l; i++) {
                    if (this.series[i].type != ecConfig.CHART_TYPE_LINE && this.series[i].type != ecConfig.CHART_TYPE_BAR && this.series[i].type != ecConfig.CHART_TYPE_SCATTER && this.series[i].type != ecConfig.CHART_TYPE_K && this.series[i].type != ecConfig.CHART_TYPE_EVENTRIVER) {
                        continue;
                    }
                    if (legend && !legend.isSelected(this.series[i].name)) {
                        continue;
                    }
                    xIdx = this.series[i].xAxisIndex || 0;
                    yIdx = this.series[i].yAxisIndex || 0;
                    if (this.option.xAxisIndex != xIdx && this.option.yAxisIndex != yIdx) {
                        continue;
                    }
                    this._calculSum(data, i);
                }
                var oriData;
                for (var i in data) {
                    oriData = data[i];
                    for (var j = 0, k = oriData.length; j < k; j++) {
                        if (!isNaN(oriData[j])) {
                            this._hasData = true;
                            this._min = oriData[j];
                            this._max = oriData[j];
                            break;
                        }
                    }
                    if (this._hasData) {
                        break;
                    }
                }
                for (var i in data) {
                    oriData = data[i];
                    for (var j = 0, k = oriData.length; j < k; j++) {
                        if (!isNaN(oriData[j])) {
                            this._min = Math.min(this._min, oriData[j]);
                            this._max = Math.max(this._max, oriData[j]);
                        }
                    }
                }
                var gap = Math.abs(this._max - this._min);
                this._min = isNaN(this.option.min - 0) ? this._min - Math.abs(gap * this.option.boundaryGap[0]) : this.option.min - 0;
                this._max = isNaN(this.option.max - 0) ? this._max + Math.abs(gap * this.option.boundaryGap[1]) : this.option.max - 0;
                if (this._min === this._max) {
                    if (this._max === 0) {
                        this._max = 1;
                    } else if (this._max > 0) {
                        this._min = this._max / this.option.splitNumber != null ? this.option.splitNumber : 5;
                    } else {
                        this._max = this._max / this.option.splitNumber != null ? this.option.splitNumber : 5;
                    }
                }
                this.option.type != 'time' ? this._reformValue(this.option.scale) : this._reformTimeValue();
            } else {
                this._hasData = true;
                this._min = this.option.min - 0;
                this._max = this.option.max - 0;
                this.option.type != 'time' ? this._customerValue() : this._reformTimeValue();
            }
        },
        _calculSum: function (data, i) {
            var key = this.series[i].name || 'kener';
            var value;
            var oriData;
            if (!this.series[i].stack) {
                data[key] = data[key] || [];
                if (this.series[i].type != ecConfig.CHART_TYPE_EVENTRIVER) {
                    oriData = this.series[i].data;
                    for (var j = 0, k = oriData.length; j < k; j++) {
                        value = oriData[j].value != null ? oriData[j].value : oriData[j];
                        if (this.series[i].type === ecConfig.CHART_TYPE_K) {
                            data[key].push(value[0]);
                            data[key].push(value[1]);
                            data[key].push(value[2]);
                            data[key].push(value[3]);
                        } else if (value instanceof Array) {
                            if (this.option.xAxisIndex != -1) {
                                data[key].push(this.option.type != 'time' ? value[0] : ecDate.getNewDate(value[0]));
                            }
                            if (this.option.yAxisIndex != -1) {
                                data[key].push(this.option.type != 'time' ? value[1] : ecDate.getNewDate(value[1]));
                            }
                        } else {
                            data[key].push(value);
                        }
                    }
                } else {
                    oriData = this.series[i].eventList;
                    for (var j = 0, k = oriData.length; j < k; j++) {
                        var evolution = oriData[j].evolution;
                        for (var m = 0, n = evolution.length; m < n; m++) {
                            data[key].push(ecDate.getNewDate(evolution[m].time));
                        }
                    }
                }
            } else {
                var keyP = '__Magic_Key_Positive__' + this.series[i].stack;
                var keyN = '__Magic_Key_Negative__' + this.series[i].stack;
                data[keyP] = data[keyP] || [];
                data[keyN] = data[keyN] || [];
                data[key] = data[key] || [];
                oriData = this.series[i].data;
                for (var j = 0, k = oriData.length; j < k; j++) {
                    value = oriData[j].value != null ? oriData[j].value : oriData[j];
                    if (value === '-') {
                        continue;
                    }
                    value = value - 0;
                    if (value >= 0) {
                        if (data[keyP][j] != null) {
                            data[keyP][j] += value;
                        } else {
                            data[keyP][j] = value;
                        }
                    } else {
                        if (data[keyN][j] != null) {
                            data[keyN][j] += value;
                        } else {
                            data[keyN][j] = value;
                        }
                    }
                    if (this.option.scale) {
                        data[key].push(value);
                    }
                }
            }
        },
        _reformValue: function (scale) {
            var smartSteps = require('../util/smartSteps');
            var splitNumber = this.option.splitNumber;
            if (!scale && this._min >= 0 && this._max >= 0) {
                this._min = 0;
            }
            if (!scale && this._min <= 0 && this._max <= 0) {
                this._max = 0;
            }
            var stepOpt = smartSteps(this._min, this._max, splitNumber);
            splitNumber = splitNumber != null ? splitNumber : stepOpt.secs;
            this.option.splitNumber = splitNumber;
            this._min = stepOpt.min;
            this._max = stepOpt.max;
            this._valueList = stepOpt.pnts;
            this._reformLabelData();
        },
        _reformTimeValue: function () {
            var splitNumber = this.option.splitNumber != null ? this.option.splitNumber : 5;
            var curValue = ecDate.getAutoFormatter(this._min, this._max, splitNumber);
            var formatter = curValue.formatter;
            var gapValue = curValue.gapValue;
            this._valueList = [ecDate.getNewDate(this._min)];
            var startGap;
            switch (formatter) {
            case 'week':
                startGap = ecDate.nextMonday(this._min);
                break;
            case 'month':
                startGap = ecDate.nextNthOnMonth(this._min, 1);
                break;
            case 'quarter':
                startGap = ecDate.nextNthOnQuarterYear(this._min, 1);
                break;
            case 'half-year':
                startGap = ecDate.nextNthOnHalfYear(this._min, 1);
                break;
            case 'year':
                startGap = ecDate.nextNthOnYear(this._min, 1);
                break;
            default:
                if (gapValue <= 3600000 * 2) {
                    startGap = (Math.floor(this._min / gapValue) + 1) * gapValue;
                } else {
                    startGap = ecDate.getNewDate(this._min - -gapValue);
                    startGap.setHours(Math.round(startGap.getHours() / 6) * 6);
                    startGap.setMinutes(0);
                    startGap.setSeconds(0);
                }
                break;
            }
            if (startGap - this._min < gapValue / 2) {
                startGap -= -gapValue;
            }
            curValue = ecDate.getNewDate(startGap);
            splitNumber *= 1.5;
            while (splitNumber-- >= 0) {
                if (formatter == 'month' || formatter == 'quarter' || formatter == 'half-year' || formatter == 'year') {
                    curValue.setDate(1);
                }
                if (this._max - curValue < gapValue / 2) {
                    break;
                }
                this._valueList.push(curValue);
                curValue = ecDate.getNewDate(curValue - -gapValue);
            }
            this._valueList.push(ecDate.getNewDate(this._max));
            this._reformLabelData(formatter);
        },
        _customerValue: function () {
            var accMath = require('../util/accMath');
            var splitNumber = this.option.splitNumber != null ? this.option.splitNumber : 5;
            var splitGap = (this._max - this._min) / splitNumber;
            this._valueList = [];
            for (var i = 0; i <= splitNumber; i++) {
                this._valueList.push(accMath.accAdd(this._min, accMath.accMul(splitGap, i)));
            }
            this._reformLabelData();
        },
        _reformLabelData: function (timeFormatter) {
            this._valueLabel = [];
            var formatter = this.option.axisLabel.formatter;
            if (formatter) {
                for (var i = 0, l = this._valueList.length; i < l; i++) {
                    if (typeof formatter === 'function') {
                        this._valueLabel.push(timeFormatter ? formatter.call(this.myChart, this._valueList[i], timeFormatter) : formatter.call(this.myChart, this._valueList[i]));
                    } else if (typeof formatter === 'string') {
                        this._valueLabel.push(timeFormatter ? ecDate.format(formatter, this._valueList[i]) : formatter.replace('{value}', this._valueList[i]));
                    }
                }
            } else if (timeFormatter) {
                for (var i = 0, l = this._valueList.length; i < l; i++) {
                    this._valueLabel.push(ecDate.format(timeFormatter, this._valueList[i]));
                }
            } else {
                for (var i = 0, l = this._valueList.length; i < l; i++) {
                    this._valueLabel.push(this.numAddCommas(this._valueList[i]));
                }
            }
        },
        getExtremum: function () {
            this._calculateValue();
            return {
                min: this._min,
                max: this._max
            };
        },
        refresh: function (newOption, newSeries) {
            if (newOption) {
                this.option = this.reformOption(newOption);
                this.option.axisLabel.textStyle = zrUtil.merge(this.option.axisLabel.textStyle || {}, this.ecTheme.textStyle);
                this.series = newSeries;
            }
            if (this.zr) {
                this.clear();
                this._buildShape();
            }
        },
        getCoord: function (value) {
            value = value < this._min ? this._min : value;
            value = value > this._max ? this._max : value;
            var result;
            if (!this.isHorizontal()) {
                result = this.grid.getYend() - (value - this._min) / (this._max - this._min) * this.grid.getHeight();
            } else {
                result = this.grid.getX() + (value - this._min) / (this._max - this._min) * this.grid.getWidth();
            }
            return result;
        },
        getCoordSize: function (value) {
            if (!this.isHorizontal()) {
                return Math.abs(value / (this._max - this._min) * this.grid.getHeight());
            } else {
                return Math.abs(value / (this._max - this._min) * this.grid.getWidth());
            }
        },
        getValueFromCoord: function (coord) {
            var result;
            if (!this.isHorizontal()) {
                coord = coord < this.grid.getY() ? this.grid.getY() : coord;
                coord = coord > this.grid.getYend() ? this.grid.getYend() : coord;
                result = this._max - (coord - this.grid.getY()) / this.grid.getHeight() * (this._max - this._min);
            } else {
                coord = coord < this.grid.getX() ? this.grid.getX() : coord;
                coord = coord > this.grid.getXend() ? this.grid.getXend() : coord;
                result = this._min + (coord - this.grid.getX()) / this.grid.getWidth() * (this._max - this._min);
            }
            return result.toFixed(2) - 0;
        },
        isMaindAxis: function (value) {
            for (var i = 0, l = this._valueList.length; i < l; i++) {
                if (this._valueList[i] === value) {
                    return true;
                }
            }
            return false;
        }
    };
    zrUtil.inherits(ValueAxis, Base);
    require('../component').define('valueAxis', ValueAxis);
    return ValueAxis;
});