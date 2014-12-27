/*! 2014 Baidu Inc. All Rights Reserved */
define('echarts/component/axis', [
    'require',
    './base',
    'zrender/shape/Line',
    '../config',
    '../util/ecData',
    'zrender/tool/util',
    'zrender/tool/color',
    './categoryAxis',
    './valueAxis',
    '../component'
], function (require) {
    var Base = require('./base');
    var LineShape = require('zrender/shape/Line');
    var ecConfig = require('../config');
    var ecData = require('../util/ecData');
    var zrUtil = require('zrender/tool/util');
    var zrColor = require('zrender/tool/color');
    function Axis(ecTheme, messageCenter, zr, option, myChart, axisType) {
        Base.call(this, ecTheme, messageCenter, zr, option, myChart);
        this.axisType = axisType;
        this._axisList = [];
        this.refresh(option);
    }
    Axis.prototype = {
        type: ecConfig.COMPONENT_TYPE_AXIS,
        axisBase: {
            _buildAxisLine: function () {
                var lineWidth = this.option.axisLine.lineStyle.width;
                var halfLineWidth = lineWidth / 2;
                var axShape = {
                        _axisShape: 'axisLine',
                        zlevel: this._zlevelBase + 1,
                        hoverable: false
                    };
                switch (this.option.position) {
                case 'left':
                    axShape.style = {
                        xStart: this.grid.getX() - halfLineWidth,
                        yStart: this.grid.getYend(),
                        xEnd: this.grid.getX() - halfLineWidth,
                        yEnd: this.grid.getY(),
                        lineCap: 'round'
                    };
                    break;
                case 'right':
                    axShape.style = {
                        xStart: this.grid.getXend() + halfLineWidth,
                        yStart: this.grid.getYend(),
                        xEnd: this.grid.getXend() + halfLineWidth,
                        yEnd: this.grid.getY(),
                        lineCap: 'round'
                    };
                    break;
                case 'bottom':
                    axShape.style = {
                        xStart: this.grid.getX(),
                        yStart: this.grid.getYend() + halfLineWidth,
                        xEnd: this.grid.getXend(),
                        yEnd: this.grid.getYend() + halfLineWidth,
                        lineCap: 'round'
                    };
                    break;
                case 'top':
                    axShape.style = {
                        xStart: this.grid.getX(),
                        yStart: this.grid.getY() - halfLineWidth,
                        xEnd: this.grid.getXend(),
                        yEnd: this.grid.getY() - halfLineWidth,
                        lineCap: 'round'
                    };
                    break;
                }
                if (this.option.name !== '') {
                    axShape.style.text = this.option.name;
                    axShape.style.textPosition = this.option.nameLocation;
                    axShape.style.textFont = this.getFont(this.option.nameTextStyle);
                    if (this.option.nameTextStyle.align) {
                        axShape.style.textAlign = this.option.nameTextStyle.align;
                    }
                    if (this.option.nameTextStyle.baseline) {
                        axShape.style.textBaseline = this.option.nameTextStyle.baseline;
                    }
                    if (this.option.nameTextStyle.color) {
                        axShape.style.textColor = this.option.nameTextStyle.color;
                    }
                }
                axShape.style.strokeColor = this.option.axisLine.lineStyle.color;
                axShape.style.lineWidth = lineWidth;
                if (this.isHorizontal()) {
                    axShape.style.yStart = axShape.style.yEnd = this.subPixelOptimize(axShape.style.yEnd, lineWidth);
                } else {
                    axShape.style.xStart = axShape.style.xEnd = this.subPixelOptimize(axShape.style.xEnd, lineWidth);
                }
                axShape.style.lineType = this.option.axisLine.lineStyle.type;
                axShape = new LineShape(axShape);
                this.shapeList.push(axShape);
            },
            _axisLabelClickable: function (clickable, axShape) {
                if (clickable) {
                    ecData.pack(axShape, undefined, -1, undefined, -1, axShape.style.text);
                    axShape.hoverable = true;
                    axShape.clickable = true;
                    axShape.highlightStyle = {
                        color: zrColor.lift(axShape.style.color, 1),
                        brushType: 'fill'
                    };
                    return axShape;
                } else {
                    return axShape;
                }
            },
            refixAxisShape: function (zeroX, zeroY) {
                if (!this.option.axisLine.onZero) {
                    return;
                }
                var tickLength;
                if (this.isHorizontal() && zeroY != null) {
                    for (var i = 0, l = this.shapeList.length; i < l; i++) {
                        if (this.shapeList[i]._axisShape === 'axisLine') {
                            this.shapeList[i].style.yStart = this.shapeList[i].style.yEnd = this.subPixelOptimize(zeroY, this.shapeList[i].stylelineWidth);
                            this.zr.modShape(this.shapeList[i].id);
                        } else if (this.shapeList[i]._axisShape === 'axisTick') {
                            tickLength = this.shapeList[i].style.yEnd - this.shapeList[i].style.yStart;
                            this.shapeList[i].style.yStart = zeroY - tickLength;
                            this.shapeList[i].style.yEnd = zeroY;
                            this.zr.modShape(this.shapeList[i].id);
                        }
                    }
                }
                if (!this.isHorizontal() && zeroX != null) {
                    for (var i = 0, l = this.shapeList.length; i < l; i++) {
                        if (this.shapeList[i]._axisShape === 'axisLine') {
                            this.shapeList[i].style.xStart = this.shapeList[i].style.xEnd = this.subPixelOptimize(zeroX, this.shapeList[i].stylelineWidth);
                            this.zr.modShape(this.shapeList[i].id);
                        } else if (this.shapeList[i]._axisShape === 'axisTick') {
                            tickLength = this.shapeList[i].style.xEnd - this.shapeList[i].style.xStart;
                            this.shapeList[i].style.xStart = zeroX;
                            this.shapeList[i].style.xEnd = zeroX + tickLength;
                            this.zr.modShape(this.shapeList[i].id);
                        }
                    }
                }
            },
            getPosition: function () {
                return this.option.position;
            },
            isHorizontal: function () {
                return this.option.position === 'bottom' || this.option.position === 'top';
            }
        },
        reformOption: function (opt) {
            if (!opt || opt instanceof Array && opt.length === 0) {
                opt = [{ type: ecConfig.COMPONENT_TYPE_AXIS_VALUE }];
            } else if (!(opt instanceof Array)) {
                opt = [opt];
            }
            if (opt.length > 2) {
                opt = [
                    opt[0],
                    opt[1]
                ];
            }
            if (this.axisType === 'xAxis') {
                if (!opt[0].position || opt[0].position != 'bottom' && opt[0].position != 'top') {
                    opt[0].position = 'bottom';
                }
                if (opt.length > 1) {
                    opt[1].position = opt[0].position === 'bottom' ? 'top' : 'bottom';
                }
                for (var i = 0, l = opt.length; i < l; i++) {
                    opt[i].type = opt[i].type || 'category';
                    opt[i].xAxisIndex = i;
                    opt[i].yAxisIndex = -1;
                }
            } else {
                if (!opt[0].position || opt[0].position != 'left' && opt[0].position != 'right') {
                    opt[0].position = 'left';
                }
                if (opt.length > 1) {
                    opt[1].position = opt[0].position === 'left' ? 'right' : 'left';
                }
                for (var i = 0, l = opt.length; i < l; i++) {
                    opt[i].type = opt[i].type || 'value';
                    opt[i].xAxisIndex = -1;
                    opt[i].yAxisIndex = i;
                }
            }
            return opt;
        },
        refresh: function (newOption) {
            var axisOption;
            if (newOption) {
                this.option = newOption;
                if (this.axisType === 'xAxis') {
                    this.option.xAxis = this.reformOption(newOption.xAxis);
                    axisOption = this.option.xAxis;
                } else {
                    this.option.yAxis = this.reformOption(newOption.yAxis);
                    axisOption = this.option.yAxis;
                }
                this.series = newOption.series;
            }
            var CategoryAxis = require('./categoryAxis');
            var ValueAxis = require('./valueAxis');
            var len = Math.max(axisOption && axisOption.length || 0, this._axisList.length);
            for (var i = 0; i < len; i++) {
                if (this._axisList[i] && newOption && (!axisOption[i] || this._axisList[i].type != axisOption[i].type)) {
                    this._axisList[i].dispose && this._axisList[i].dispose();
                    this._axisList[i] = false;
                }
                if (this._axisList[i]) {
                    this._axisList[i].refresh && this._axisList[i].refresh(axisOption ? axisOption[i] : false, this.series);
                } else if (axisOption && axisOption[i]) {
                    this._axisList[i] = axisOption[i].type === 'category' ? new CategoryAxis(this.ecTheme, this.messageCenter, this.zr, axisOption[i], this.myChart, this.axisBase) : new ValueAxis(this.ecTheme, this.messageCenter, this.zr, axisOption[i], this.myChart, this.axisBase, this.series);
                }
            }
        },
        getAxis: function (idx) {
            return this._axisList[idx];
        },
        clear: function () {
            for (var i = 0, l = this._axisList.length; i < l; i++) {
                this._axisList[i].dispose && this._axisList[i].dispose();
            }
            this._axisList = [];
        }
    };
    zrUtil.inherits(Axis, Base);
    require('../component').define('axis', Axis);
    return Axis;
});