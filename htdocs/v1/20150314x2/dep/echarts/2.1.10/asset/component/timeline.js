define([
    'require',
    './base',
    'zrender/shape/Rectangle',
    '../util/shape/Icon',
    '../util/shape/Chain',
    '../config',
    'zrender/tool/util',
    'zrender/tool/area',
    'zrender/tool/event',
    '../component'
], function (require) {
    var Base = require('./base');
    var RectangleShape = require('zrender/shape/Rectangle');
    var IconShape = require('../util/shape/Icon');
    var ChainShape = require('../util/shape/Chain');
    var ecConfig = require('../config');
    var zrUtil = require('zrender/tool/util');
    var zrArea = require('zrender/tool/area');
    var zrEvent = require('zrender/tool/event');
    function Timeline(ecTheme, messageCenter, zr, option, myChart) {
        Base.call(this, ecTheme, messageCenter, zr, option, myChart);
        var self = this;
        self._onclick = function (param) {
            return self.__onclick(param);
        };
        self._ondrift = function (dx, dy) {
            return self.__ondrift(this, dx, dy);
        };
        self._ondragend = function () {
            return self.__ondragend();
        };
        self._setCurrentOption = function () {
            var timelineOption = self.timelineOption;
            self.currentIndex %= timelineOption.data.length;
            var curOption = self.options[self.currentIndex] || {};
            self.myChart.setOption(curOption, timelineOption.notMerge);
            self.messageCenter.dispatch(ecConfig.EVENT.TIMELINE_CHANGED, null, {
                currentIndex: self.currentIndex,
                data: timelineOption.data[self.currentIndex].name != null ? timelineOption.data[self.currentIndex].name : timelineOption.data[self.currentIndex]
            }, self.myChart);
        };
        self._onFrame = function () {
            self._setCurrentOption();
            self._syncHandleShape();
            if (self.timelineOption.autoPlay) {
                self.playTicket = setTimeout(function () {
                    self.currentIndex += 1;
                    if (!self.timelineOption.loop && self.currentIndex >= self.timelineOption.data.length) {
                        self.currentIndex = self.timelineOption.data.length - 1;
                        self.stop();
                        return;
                    }
                    self._onFrame();
                }, self.timelineOption.playInterval);
            }
        };
        this.setTheme(false);
        this.options = this.option.options;
        this.currentIndex = this.timelineOption.currentIndex % this.timelineOption.data.length;
        if (!this.timelineOption.notMerge && this.currentIndex !== 0) {
            this.options[this.currentIndex] = zrUtil.merge(this.options[this.currentIndex], this.options[0]);
        }
        if (this.timelineOption.show) {
            this._buildShape();
            this._syncHandleShape();
        }
        this._setCurrentOption();
        if (this.timelineOption.autoPlay) {
            var self = this;
            this.playTicket = setTimeout(function () {
                self.play();
            }, this.ecTheme.animationDuration);
        }
    }
    Timeline.prototype = {
        type: ecConfig.COMPONENT_TYPE_TIMELINE,
        _buildShape: function () {
            this._location = this._getLocation();
            this._buildBackground();
            this._buildControl();
            this._chainPoint = this._getChainPoint();
            if (this.timelineOption.label.show) {
                var interval = this._getInterval();
                for (var i = 0, len = this._chainPoint.length; i < len; i += interval) {
                    this._chainPoint[i].showLabel = true;
                }
            }
            this._buildChain();
            this._buildHandle();
            for (var i = 0, l = this.shapeList.length; i < l; i++) {
                this.zr.addShape(this.shapeList[i]);
            }
        },
        _getLocation: function () {
            var timelineOption = this.timelineOption;
            var padding = this.reformCssArray(this.timelineOption.padding);
            var zrWidth = this.zr.getWidth();
            var x = this.parsePercent(timelineOption.x, zrWidth);
            var x2 = this.parsePercent(timelineOption.x2, zrWidth);
            var width;
            if (timelineOption.width == null) {
                width = zrWidth - x - x2;
                x2 = zrWidth - x2;
            } else {
                width = this.parsePercent(timelineOption.width, zrWidth);
                x2 = x + width;
            }
            var zrHeight = this.zr.getHeight();
            var height = this.parsePercent(timelineOption.height, zrHeight);
            var y;
            var y2;
            if (timelineOption.y != null) {
                y = this.parsePercent(timelineOption.y, zrHeight);
                y2 = y + height;
            } else {
                y2 = zrHeight - this.parsePercent(timelineOption.y2, zrHeight);
                y = y2 - height;
            }
            return {
                x: x + padding[3],
                y: y + padding[0],
                x2: x2 - padding[1],
                y2: y2 - padding[2],
                width: width - padding[1] - padding[3],
                height: height - padding[0] - padding[2]
            };
        },
        _getReformedLabel: function (idx) {
            var timelineOption = this.timelineOption;
            var data = timelineOption.data[idx].name != null ? timelineOption.data[idx].name : timelineOption.data[idx];
            var formatter = timelineOption.data[idx].formatter || timelineOption.label.formatter;
            if (formatter) {
                if (typeof formatter === 'function') {
                    data = formatter.call(this.myChart, data);
                } else if (typeof formatter === 'string') {
                    data = formatter.replace('{value}', data);
                }
            }
            return data;
        },
        _getInterval: function () {
            var chainPoint = this._chainPoint;
            var timelineOption = this.timelineOption;
            var interval = timelineOption.label.interval;
            if (interval === 'auto') {
                var fontSize = timelineOption.label.textStyle.fontSize;
                var data = timelineOption.data;
                var dataLength = timelineOption.data.length;
                if (dataLength > 3) {
                    var isEnough = false;
                    var labelSpace;
                    var labelSize;
                    interval = 0;
                    while (!isEnough && interval < dataLength) {
                        interval++;
                        isEnough = true;
                        for (var i = interval; i < dataLength; i += interval) {
                            labelSpace = chainPoint[i].x - chainPoint[i - interval].x;
                            if (timelineOption.label.rotate !== 0) {
                                labelSize = fontSize;
                            } else if (data[i].textStyle) {
                                labelSize = zrArea.getTextWidth(chainPoint[i].name, chainPoint[i].textFont);
                            } else {
                                var label = chainPoint[i].name + '';
                                var wLen = (label.match(/\w/g) || '').length;
                                var oLen = label.length - wLen;
                                labelSize = wLen * fontSize * 2 / 3 + oLen * fontSize;
                            }
                            if (labelSpace < labelSize) {
                                isEnough = false;
                                break;
                            }
                        }
                    }
                } else {
                    interval = 1;
                }
            } else {
                interval = interval - 0 + 1;
            }
            return interval;
        },
        _getChainPoint: function () {
            var timelineOption = this.timelineOption;
            var symbol = timelineOption.symbol.toLowerCase();
            var symbolSize = timelineOption.symbolSize;
            var rotate = timelineOption.label.rotate;
            var textStyle = timelineOption.label.textStyle;
            var textFont = this.getFont(textStyle);
            var dataTextStyle;
            var data = timelineOption.data;
            var x = this._location.x;
            var y = this._location.y + this._location.height / 4 * 3;
            var width = this._location.x2 - this._location.x;
            var len = data.length;
            function _getName(i) {
                return data[i].name != null ? data[i].name : data[i] + '';
            }
            var xList = [];
            if (len > 1) {
                var boundaryGap = width / len;
                boundaryGap = boundaryGap > 50 ? 50 : boundaryGap < 20 ? 5 : boundaryGap;
                width -= boundaryGap * 2;
                if (timelineOption.type === 'number') {
                    for (var i = 0; i < len; i++) {
                        xList.push(x + boundaryGap + width / (len - 1) * i);
                    }
                } else {
                    xList[0] = new Date(_getName(0).replace(/-/g, '/'));
                    xList[len - 1] = new Date(_getName(len - 1).replace(/-/g, '/')) - xList[0];
                    for (var i = 1; i < len; i++) {
                        xList[i] = x + boundaryGap + width * (new Date(_getName(i).replace(/-/g, '/')) - xList[0]) / xList[len - 1];
                    }
                    xList[0] = x + boundaryGap;
                }
            } else {
                xList.push(x + width / 2);
            }
            var list = [];
            var curSymbol;
            var n;
            var isEmpty;
            var textAlign;
            var rotation;
            for (var i = 0; i < len; i++) {
                x = xList[i];
                curSymbol = data[i].symbol && data[i].symbol.toLowerCase() || symbol;
                if (curSymbol.match('empty')) {
                    curSymbol = curSymbol.replace('empty', '');
                    isEmpty = true;
                } else {
                    isEmpty = false;
                }
                if (curSymbol.match('star')) {
                    n = curSymbol.replace('star', '') - 0 || 5;
                    curSymbol = 'star';
                }
                dataTextStyle = data[i].textStyle ? zrUtil.merge(data[i].textStyle || {}, textStyle) : textStyle;
                textAlign = dataTextStyle.align || 'center';
                if (rotate) {
                    textAlign = rotate > 0 ? 'right' : 'left';
                    rotation = [
                        rotate * Math.PI / 180,
                        x,
                        y - 5
                    ];
                } else {
                    rotation = false;
                }
                list.push({
                    x: x,
                    n: n,
                    isEmpty: isEmpty,
                    symbol: curSymbol,
                    symbolSize: data[i].symbolSize || symbolSize,
                    color: data[i].color,
                    borderColor: data[i].borderColor,
                    borderWidth: data[i].borderWidth,
                    name: this._getReformedLabel(i),
                    textColor: dataTextStyle.color,
                    textAlign: textAlign,
                    textBaseline: dataTextStyle.baseline || 'middle',
                    textX: x,
                    textY: y - (rotate ? 5 : 0),
                    textFont: data[i].textStyle ? this.getFont(dataTextStyle) : textFont,
                    rotation: rotation,
                    showLabel: false
                });
            }
            return list;
        },
        _buildBackground: function () {
            var timelineOption = this.timelineOption;
            var padding = this.reformCssArray(this.timelineOption.padding);
            var width = this._location.width;
            var height = this._location.height;
            if (timelineOption.borderWidth !== 0 || timelineOption.backgroundColor.replace(/\s/g, '') != 'rgba(0,0,0,0)') {
                this.shapeList.push(new RectangleShape({
                    zlevel: this._zlevelBase,
                    hoverable: false,
                    style: {
                        x: this._location.x - padding[3],
                        y: this._location.y - padding[0],
                        width: width + padding[1] + padding[3],
                        height: height + padding[0] + padding[2],
                        brushType: timelineOption.borderWidth === 0 ? 'fill' : 'both',
                        color: timelineOption.backgroundColor,
                        strokeColor: timelineOption.borderColor,
                        lineWidth: timelineOption.borderWidth
                    }
                }));
            }
        },
        _buildControl: function () {
            var self = this;
            var timelineOption = this.timelineOption;
            var lineStyle = timelineOption.lineStyle;
            var controlStyle = timelineOption.controlStyle;
            if (timelineOption.controlPosition === 'none') {
                return;
            }
            var iconSize = 15;
            var iconGap = 5;
            var x;
            if (timelineOption.controlPosition === 'left') {
                x = this._location.x;
                this._location.x += (iconSize + iconGap) * 3;
            } else {
                x = this._location.x2 - ((iconSize + iconGap) * 3 - iconGap);
                this._location.x2 -= (iconSize + iconGap) * 3;
            }
            var y = this._location.y;
            var iconStyle = {
                    zlevel: this._zlevelBase + 1,
                    style: {
                        iconType: 'timelineControl',
                        symbol: 'last',
                        x: x,
                        y: y,
                        width: iconSize,
                        height: iconSize,
                        brushType: 'stroke',
                        color: controlStyle.normal.color,
                        strokeColor: controlStyle.normal.color,
                        lineWidth: lineStyle.width
                    },
                    highlightStyle: {
                        color: controlStyle.emphasis.color,
                        strokeColor: controlStyle.emphasis.color,
                        lineWidth: lineStyle.width + 1
                    },
                    clickable: true
                };
            this._ctrLastShape = new IconShape(iconStyle);
            this._ctrLastShape.onclick = function () {
                self.last();
            };
            this.shapeList.push(this._ctrLastShape);
            x += iconSize + iconGap;
            this._ctrPlayShape = new IconShape(zrUtil.clone(iconStyle));
            this._ctrPlayShape.style.brushType = 'fill';
            this._ctrPlayShape.style.symbol = 'play';
            this._ctrPlayShape.style.status = this.timelineOption.autoPlay ? 'playing' : 'stop';
            this._ctrPlayShape.style.x = x;
            this._ctrPlayShape.onclick = function () {
                if (self._ctrPlayShape.style.status === 'stop') {
                    self.play();
                } else {
                    self.stop();
                }
            };
            this.shapeList.push(this._ctrPlayShape);
            x += iconSize + iconGap;
            this._ctrNextShape = new IconShape(zrUtil.clone(iconStyle));
            this._ctrNextShape.style.symbol = 'next';
            this._ctrNextShape.style.x = x;
            this._ctrNextShape.onclick = function () {
                self.next();
            };
            this.shapeList.push(this._ctrNextShape);
        },
        _buildChain: function () {
            var timelineOption = this.timelineOption;
            var lineStyle = timelineOption.lineStyle;
            this._timelineShae = {
                zlevel: this._zlevelBase,
                style: {
                    x: this._location.x,
                    y: this.subPixelOptimize(this._location.y, lineStyle.width),
                    width: this._location.x2 - this._location.x,
                    height: this._location.height,
                    chainPoint: this._chainPoint,
                    brushType: 'both',
                    strokeColor: lineStyle.color,
                    lineWidth: lineStyle.width,
                    lineType: lineStyle.type
                },
                hoverable: false,
                clickable: true,
                onclick: this._onclick
            };
            this._timelineShae = new ChainShape(this._timelineShae);
            this.shapeList.push(this._timelineShae);
        },
        _buildHandle: function () {
            var curPoint = this._chainPoint[this.currentIndex];
            var symbolSize = curPoint.symbolSize + 1;
            symbolSize = symbolSize < 5 ? 5 : symbolSize;
            this._handleShape = {
                zlevel: this._zlevelBase + 1,
                hoverable: false,
                draggable: true,
                style: {
                    iconType: 'diamond',
                    n: curPoint.n,
                    x: curPoint.x - symbolSize,
                    y: this._location.y + this._location.height / 4 - symbolSize,
                    width: symbolSize * 2,
                    height: symbolSize * 2,
                    brushType: 'both',
                    textPosition: 'specific',
                    textX: curPoint.x,
                    textY: this._location.y - this._location.height / 4,
                    textAlign: 'center',
                    textBaseline: 'middle'
                },
                highlightStyle: {},
                ondrift: this._ondrift,
                ondragend: this._ondragend
            };
            this._handleShape = new IconShape(this._handleShape);
            this.shapeList.push(this._handleShape);
        },
        _syncHandleShape: function () {
            if (!this.timelineOption.show) {
                return;
            }
            var timelineOption = this.timelineOption;
            var cpStyle = timelineOption.checkpointStyle;
            var curPoint = this._chainPoint[this.currentIndex];
            this._handleShape.style.text = cpStyle.label.show ? curPoint.name : '';
            this._handleShape.style.textFont = curPoint.textFont;
            this._handleShape.style.n = curPoint.n;
            if (cpStyle.symbol === 'auto') {
                this._handleShape.style.iconType = curPoint.symbol != 'none' ? curPoint.symbol : 'diamond';
            } else {
                this._handleShape.style.iconType = cpStyle.symbol;
                if (cpStyle.symbol.match('star')) {
                    this._handleShape.style.n = cpStyle.symbol.replace('star', '') - 0 || 5;
                    this._handleShape.style.iconType = 'star';
                }
            }
            var symbolSize;
            if (cpStyle.symbolSize === 'auto') {
                symbolSize = curPoint.symbolSize + 2;
                symbolSize = symbolSize < 5 ? 5 : symbolSize;
            } else {
                symbolSize = cpStyle.symbolSize - 0;
            }
            this._handleShape.style.color = cpStyle.color === 'auto' ? curPoint.color ? curPoint.color : timelineOption.controlStyle.emphasis.color : cpStyle.color;
            this._handleShape.style.textColor = cpStyle.label.textStyle.color === 'auto' ? this._handleShape.style.color : cpStyle.label.textStyle.color;
            this._handleShape.highlightStyle.strokeColor = this._handleShape.style.strokeColor = cpStyle.borderColor === 'auto' ? curPoint.borderColor ? curPoint.borderColor : '#fff' : cpStyle.borderColor;
            this._handleShape.style.lineWidth = cpStyle.borderWidth === 'auto' ? curPoint.borderWidth ? curPoint.borderWidth : 0 : cpStyle.borderWidth - 0;
            this._handleShape.highlightStyle.lineWidth = this._handleShape.style.lineWidth + 1;
            this.zr.animate(this._handleShape.id, 'style').when(500, {
                x: curPoint.x - symbolSize,
                textX: curPoint.x,
                y: this._location.y + this._location.height / 4 - symbolSize,
                width: symbolSize * 2,
                height: symbolSize * 2
            }).start('ExponentialOut');
        },
        _findChainIndex: function (x) {
            var chainPoint = this._chainPoint;
            var len = chainPoint.length;
            if (x <= chainPoint[0].x) {
                return 0;
            } else if (x >= chainPoint[len - 1].x) {
                return len - 1;
            }
            for (var i = 0; i < len - 1; i++) {
                if (x >= chainPoint[i].x && x <= chainPoint[i + 1].x) {
                    return Math.abs(x - chainPoint[i].x) < Math.abs(x - chainPoint[i + 1].x) ? i : i + 1;
                }
            }
        },
        __onclick: function (param) {
            var x = zrEvent.getX(param.event);
            var newIndex = this._findChainIndex(x);
            if (newIndex === this.currentIndex) {
                return true;
            }
            this.currentIndex = newIndex;
            this.timelineOption.autoPlay && this.stop();
            clearTimeout(this.playTicket);
            this._onFrame();
        },
        __ondrift: function (shape, dx) {
            this.timelineOption.autoPlay && this.stop();
            var chainPoint = this._chainPoint;
            var len = chainPoint.length;
            var newIndex;
            if (shape.style.x + dx <= chainPoint[0].x - chainPoint[0].symbolSize) {
                shape.style.x = chainPoint[0].x - chainPoint[0].symbolSize;
                newIndex = 0;
            } else if (shape.style.x + dx >= chainPoint[len - 1].x - chainPoint[len - 1].symbolSize) {
                shape.style.x = chainPoint[len - 1].x - chainPoint[len - 1].symbolSize;
                newIndex = len - 1;
            } else {
                shape.style.x += dx;
                newIndex = this._findChainIndex(shape.style.x);
            }
            var curPoint = chainPoint[newIndex];
            var symbolSize = curPoint.symbolSize + 2;
            shape.style.iconType = curPoint.symbol;
            shape.style.n = curPoint.n;
            shape.style.textX = shape.style.x + symbolSize / 2;
            shape.style.y = this._location.y + this._location.height / 4 - symbolSize;
            shape.style.width = symbolSize * 2;
            shape.style.height = symbolSize * 2;
            shape.style.text = curPoint.name;
            if (newIndex === this.currentIndex) {
                return true;
            }
            this.currentIndex = newIndex;
            if (this.timelineOption.realtime) {
                clearTimeout(this.playTicket);
                var self = this;
                this.playTicket = setTimeout(function () {
                    self._setCurrentOption();
                }, 200);
            }
            return true;
        },
        __ondragend: function () {
            this.isDragend = true;
        },
        ondragend: function (param, status) {
            if (!this.isDragend || !param.target) {
                return;
            }
            !this.timelineOption.realtime && this._setCurrentOption();
            status.dragOut = true;
            status.dragIn = true;
            status.needRefresh = false;
            this.isDragend = false;
            this._syncHandleShape();
            return;
        },
        last: function () {
            this.timelineOption.autoPlay && this.stop();
            this.currentIndex -= 1;
            if (this.currentIndex < 0) {
                this.currentIndex = this.timelineOption.data.length - 1;
            }
            this._onFrame();
            return this.currentIndex;
        },
        next: function () {
            this.timelineOption.autoPlay && this.stop();
            this.currentIndex += 1;
            if (this.currentIndex >= this.timelineOption.data.length) {
                this.currentIndex = 0;
            }
            this._onFrame();
            return this.currentIndex;
        },
        play: function (targetIndex, autoPlay) {
            if (this._ctrPlayShape && this._ctrPlayShape.style.status != 'playing') {
                this._ctrPlayShape.style.status = 'playing';
                this.zr.modShape(this._ctrPlayShape.id);
                this.zr.refresh();
            }
            this.timelineOption.autoPlay = autoPlay != null ? autoPlay : true;
            if (!this.timelineOption.autoPlay) {
                clearTimeout(this.playTicket);
            }
            this.currentIndex = targetIndex != null ? targetIndex : this.currentIndex + 1;
            if (this.currentIndex >= this.timelineOption.data.length) {
                this.currentIndex = 0;
            }
            this._onFrame();
            return this.currentIndex;
        },
        stop: function () {
            if (this._ctrPlayShape && this._ctrPlayShape.style.status != 'stop') {
                this._ctrPlayShape.style.status = 'stop';
                this.zr.modShape(this._ctrPlayShape.id);
                this.zr.refresh();
            }
            this.timelineOption.autoPlay = false;
            clearTimeout(this.playTicket);
            return this.currentIndex;
        },
        resize: function () {
            if (this.timelineOption.show) {
                this.clear();
                this._buildShape();
                this._syncHandleShape();
            }
        },
        setTheme: function (needRefresh) {
            this.timelineOption = this.reformOption(zrUtil.clone(this.option.timeline));
            this.timelineOption.label.textStyle = zrUtil.merge(this.timelineOption.label.textStyle || {}, this.ecTheme.textStyle);
            this.timelineOption.checkpointStyle.label.textStyle = zrUtil.merge(this.timelineOption.checkpointStyle.label.textStyle || {}, this.ecTheme.textStyle);
            if (!this.myChart.canvasSupported) {
                this.timelineOption.realtime = false;
            }
            if (this.timelineOption.show && needRefresh) {
                this.clear();
                this._buildShape();
                this._syncHandleShape();
            }
        },
        onbeforDispose: function () {
            clearTimeout(this.playTicket);
        }
    };
    function timelineControl(ctx, style) {
        var lineWidth = 2;
        var x = style.x + lineWidth;
        var y = style.y + lineWidth + 2;
        var width = style.width - lineWidth;
        var height = style.height - lineWidth;
        var symbol = style.symbol;
        if (symbol === 'last') {
            ctx.moveTo(x + width - 2, y + height / 3);
            ctx.lineTo(x + width - 2, y);
            ctx.lineTo(x + 2, y + height / 2);
            ctx.lineTo(x + width - 2, y + height);
            ctx.lineTo(x + width - 2, y + height / 3 * 2);
            ctx.moveTo(x, y);
            ctx.lineTo(x, y);
        } else if (symbol === 'next') {
            ctx.moveTo(x + 2, y + height / 3);
            ctx.lineTo(x + 2, y);
            ctx.lineTo(x + width - 2, y + height / 2);
            ctx.lineTo(x + 2, y + height);
            ctx.lineTo(x + 2, y + height / 3 * 2);
            ctx.moveTo(x, y);
            ctx.lineTo(x, y);
        } else if (symbol === 'play') {
            if (style.status === 'stop') {
                ctx.moveTo(x + 2, y);
                ctx.lineTo(x + width - 2, y + height / 2);
                ctx.lineTo(x + 2, y + height);
                ctx.lineTo(x + 2, y);
            } else {
                var delta = style.brushType === 'both' ? 2 : 3;
                ctx.rect(x + 2, y, delta, height);
                ctx.rect(x + width - delta - 2, y, delta, height);
            }
        } else if (symbol.match('image')) {
            var imageLocation = '';
            imageLocation = symbol.replace(new RegExp('^image:\\/\\/'), '');
            symbol = IconShape.prototype.iconLibrary.image;
            symbol(ctx, {
                x: x,
                y: y,
                width: width,
                height: height,
                image: imageLocation
            });
        }
    }
    IconShape.prototype.iconLibrary['timelineControl'] = timelineControl;
    zrUtil.inherits(Timeline, Base);
    require('../component').define('timeline', Timeline);
    return Timeline;
});