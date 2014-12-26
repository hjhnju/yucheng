/*! 2014 Baidu Inc. All Rights Reserved */
define('echarts/chart/island', [
    'require',
    '../component/base',
    './base',
    'zrender/shape/Circle',
    '../config',
    '../util/ecData',
    'zrender/tool/util',
    'zrender/tool/event',
    'zrender/tool/color',
    '../util/accMath',
    '../chart'
], function (require) {
    var ComponentBase = require('../component/base');
    var ChartBase = require('./base');
    var CircleShape = require('zrender/shape/Circle');
    var ecConfig = require('../config');
    var ecData = require('../util/ecData');
    var zrUtil = require('zrender/tool/util');
    var zrEvent = require('zrender/tool/event');
    function Island(ecTheme, messageCenter, zr, option, myChart) {
        ComponentBase.call(this, ecTheme, messageCenter, zr, {}, myChart);
        ChartBase.call(this);
        this._nameConnector;
        this._valueConnector;
        this._zrHeight = this.zr.getHeight();
        this._zrWidth = this.zr.getWidth();
        var self = this;
        self.shapeHandler.onmousewheel = function (param) {
            var shape = param.target;
            var event = param.event;
            var delta = zrEvent.getDelta(event);
            delta = delta > 0 ? -1 : 1;
            shape.style.r -= delta;
            shape.style.r = shape.style.r < 5 ? 5 : shape.style.r;
            var value = ecData.get(shape, 'value');
            var dvalue = value * self.option.island.calculateStep;
            if (dvalue > 1) {
                value = Math.round(value - dvalue * delta);
            } else {
                value = (value - dvalue * delta).toFixed(2) - 0;
            }
            var name = ecData.get(shape, 'name');
            shape.style.text = name + ':' + value;
            ecData.set(shape, 'value', value);
            ecData.set(shape, 'name', name);
            self.zr.modShape(shape.id);
            self.zr.refresh();
            zrEvent.stop(event);
        };
    }
    Island.prototype = {
        type: ecConfig.CHART_TYPE_ISLAND,
        _combine: function (tarShape, srcShape) {
            var zrColor = require('zrender/tool/color');
            var accMath = require('../util/accMath');
            var value = accMath.accAdd(ecData.get(tarShape, 'value'), ecData.get(srcShape, 'value'));
            var name = ecData.get(tarShape, 'name') + this._nameConnector + ecData.get(srcShape, 'name');
            tarShape.style.text = name + this._valueConnector + value;
            ecData.set(tarShape, 'value', value);
            ecData.set(tarShape, 'name', name);
            tarShape.style.r = this.option.island.r;
            tarShape.style.color = zrColor.mix(tarShape.style.color, srcShape.style.color);
        },
        refresh: function (newOption) {
            if (newOption) {
                newOption.island = this.reformOption(newOption.island);
                this.option = newOption;
                this._nameConnector = this.option.nameConnector;
                this._valueConnector = this.option.valueConnector;
            }
        },
        getOption: function () {
            return this.option;
        },
        resize: function () {
            var newWidth = this.zr.getWidth();
            var newHieght = this.zr.getHeight();
            var xScale = newWidth / (this._zrWidth || newWidth);
            var yScale = newHieght / (this._zrHeight || newHieght);
            if (xScale === 1 && yScale === 1) {
                return;
            }
            this._zrWidth = newWidth;
            this._zrHeight = newHieght;
            for (var i = 0, l = this.shapeList.length; i < l; i++) {
                this.zr.modShape(this.shapeList[i].id, {
                    style: {
                        x: Math.round(this.shapeList[i].style.x * xScale),
                        y: Math.round(this.shapeList[i].style.y * yScale)
                    }
                });
            }
        },
        add: function (shape) {
            var name = ecData.get(shape, 'name');
            var value = ecData.get(shape, 'value');
            var seriesName = ecData.get(shape, 'series') != null ? ecData.get(shape, 'series').name : '';
            var font = this.getFont(this.option.island.textStyle);
            var islandShape = {
                    zlevel: this._zlevelBase,
                    style: {
                        x: shape.style.x,
                        y: shape.style.y,
                        r: this.option.island.r,
                        color: shape.style.color || shape.style.strokeColor,
                        text: name + this._valueConnector + value,
                        textFont: font
                    },
                    draggable: true,
                    hoverable: true,
                    onmousewheel: this.shapeHandler.onmousewheel,
                    _type: 'island'
                };
            if (islandShape.style.color === '#fff') {
                islandShape.style.color = shape.style.strokeColor;
            }
            this.setCalculable(islandShape);
            islandShape.dragEnableTime = 0;
            ecData.pack(islandShape, { name: seriesName }, -1, value, -1, name);
            islandShape = new CircleShape(islandShape);
            this.shapeList.push(islandShape);
            this.zr.addShape(islandShape);
        },
        del: function (shape) {
            this.zr.delShape(shape.id);
            var newShapeList = [];
            for (var i = 0, l = this.shapeList.length; i < l; i++) {
                if (this.shapeList[i].id != shape.id) {
                    newShapeList.push(this.shapeList[i]);
                }
            }
            this.shapeList = newShapeList;
        },
        ondrop: function (param, status) {
            if (!this.isDrop || !param.target) {
                return;
            }
            var target = param.target;
            var dragged = param.dragged;
            this._combine(target, dragged);
            this.zr.modShape(target.id);
            status.dragIn = true;
            this.isDrop = false;
            return;
        },
        ondragend: function (param, status) {
            var target = param.target;
            if (!this.isDragend) {
                if (!status.dragIn) {
                    target.style.x = zrEvent.getX(param.event);
                    target.style.y = zrEvent.getY(param.event);
                    this.add(target);
                    status.needRefresh = true;
                }
            } else {
                if (status.dragIn) {
                    this.del(target);
                    status.needRefresh = true;
                }
            }
            this.isDragend = false;
            return;
        }
    };
    zrUtil.inherits(Island, ChartBase);
    zrUtil.inherits(Island, ComponentBase);
    require('../chart').define('island', Island);
    return Island;
});