/*! 2014 Baidu Inc. All Rights Reserved */
define('echarts/component/dataRange', [
    'require',
    './base',
    'zrender/shape/Text',
    'zrender/shape/Rectangle',
    '../util/shape/HandlePolygon',
    '../config',
    'zrender/tool/util',
    'zrender/tool/event',
    'zrender/tool/area',
    'zrender/tool/color',
    '../component'
], function (require) {
    var Base = require('./base');
    var TextShape = require('zrender/shape/Text');
    var RectangleShape = require('zrender/shape/Rectangle');
    var HandlePolygonShape = require('../util/shape/HandlePolygon');
    var ecConfig = require('../config');
    var zrUtil = require('zrender/tool/util');
    var zrEvent = require('zrender/tool/event');
    var zrArea = require('zrender/tool/area');
    var zrColor = require('zrender/tool/color');
    function DataRange(ecTheme, messageCenter, zr, option, myChart) {
        if (typeof this.query(option, 'dataRange.min') == 'undefined' || typeof this.query(option, 'dataRange.max') == 'undefined') {
            console.error('option.dataRange.min or option.dataRange.max has not been defined.');
            return;
        }
        Base.call(this, ecTheme, messageCenter, zr, option, myChart);
        var self = this;
        self._ondrift = function (dx, dy) {
            return self.__ondrift(this, dx, dy);
        };
        self._ondragend = function () {
            return self.__ondragend();
        };
        self._dataRangeSelected = function (param) {
            return self.__dataRangeSelected(param);
        };
        self._dispatchHoverLink = function (param) {
            return self.__dispatchHoverLink(param);
        };
        self._onhoverlink = function (params) {
            return self.__onhoverlink(params);
        };
        this._selectedMap = {};
        this._range = {};
        this.refresh(option);
        messageCenter.bind(ecConfig.EVENT.HOVER, this._onhoverlink);
    }
    DataRange.prototype = {
        type: ecConfig.COMPONENT_TYPE_DATARANGE,
        _textGap: 10,
        _buildShape: function () {
            this._itemGroupLocation = this._getItemGroupLocation();
            this._buildBackground();
            if (this.dataRangeOption.splitNumber <= 0 || this.dataRangeOption.calculable) {
                this._buildGradient();
            } else {
                this._buildItem();
            }
            if (this.dataRangeOption.show) {
                for (var i = 0, l = this.shapeList.length; i < l; i++) {
                    this.zr.addShape(this.shapeList[i]);
                }
            }
            this._syncShapeFromRange();
        },
        _buildItem: function () {
            var data = this._valueTextList;
            var dataLength = data.length;
            var itemName;
            var itemShape;
            var textShape;
            var font = this.getFont(this.dataRangeOption.textStyle);
            var lastX = this._itemGroupLocation.x;
            var lastY = this._itemGroupLocation.y;
            var itemWidth = this.dataRangeOption.itemWidth;
            var itemHeight = this.dataRangeOption.itemHeight;
            var itemGap = this.dataRangeOption.itemGap;
            var textHeight = zrArea.getTextHeight('\u56FD', font);
            var color;
            if (this.dataRangeOption.orient == 'vertical' && this.dataRangeOption.x == 'right') {
                lastX = this._itemGroupLocation.x + this._itemGroupLocation.width - itemWidth;
            }
            var needValueText = true;
            if (this.dataRangeOption.text) {
                needValueText = false;
                if (this.dataRangeOption.text[0]) {
                    textShape = this._getTextShape(lastX, lastY, this.dataRangeOption.text[0]);
                    if (this.dataRangeOption.orient == 'horizontal') {
                        lastX += zrArea.getTextWidth(this.dataRangeOption.text[0], font) + this._textGap;
                    } else {
                        lastY += textHeight + this._textGap;
                        textShape.style.y += textHeight / 2 + this._textGap;
                        textShape.style.textBaseline = 'bottom';
                    }
                    this.shapeList.push(new TextShape(textShape));
                }
            }
            for (var i = 0; i < dataLength; i++) {
                itemName = data[i];
                color = this.getColorByIndex(i);
                itemShape = this._getItemShape(lastX, lastY, itemWidth, itemHeight, this._selectedMap[i] ? color : '#ccc');
                itemShape._idx = i;
                itemShape.onmousemove = this._dispatchHoverLink;
                itemShape.onclick = this._dataRangeSelected;
                this.shapeList.push(new RectangleShape(itemShape));
                if (needValueText) {
                    textShape = {
                        zlevel: this._zlevelBase,
                        style: {
                            x: lastX + itemWidth + 5,
                            y: lastY,
                            color: this._selectedMap[i] ? this.dataRangeOption.textStyle.color : '#ccc',
                            text: data[i],
                            textFont: font,
                            textBaseline: 'top'
                        },
                        highlightStyle: { brushType: 'fill' },
                        clickable: true
                    };
                    if (this.dataRangeOption.orient == 'vertical' && this.dataRangeOption.x == 'right') {
                        textShape.style.x -= itemWidth + 10;
                        textShape.style.textAlign = 'right';
                    }
                    textShape._idx = i;
                    textShape.onclick = this._dataRangeSelected;
                    this.shapeList.push(new TextShape(textShape));
                }
                if (this.dataRangeOption.orient == 'horizontal') {
                    lastX += itemWidth + (needValueText ? 5 : 0) + (needValueText ? zrArea.getTextWidth(itemName, font) : 0) + itemGap;
                } else {
                    lastY += itemHeight + itemGap;
                }
            }
            if (!needValueText && this.dataRangeOption.text[1]) {
                if (this.dataRangeOption.orient == 'horizontal') {
                    lastX = lastX - itemGap + this._textGap;
                } else {
                    lastY = lastY - itemGap + this._textGap;
                }
                textShape = this._getTextShape(lastX, lastY, this.dataRangeOption.text[1]);
                if (this.dataRangeOption.orient != 'horizontal') {
                    textShape.style.y -= 5;
                    textShape.style.textBaseline = 'top';
                }
                this.shapeList.push(new TextShape(textShape));
            }
        },
        _buildGradient: function () {
            var itemShape;
            var textShape;
            var font = this.getFont(this.dataRangeOption.textStyle);
            var lastX = this._itemGroupLocation.x;
            var lastY = this._itemGroupLocation.y;
            var itemWidth = this.dataRangeOption.itemWidth;
            var itemHeight = this.dataRangeOption.itemHeight;
            var textHeight = zrArea.getTextHeight('\u56FD', font);
            var needValueText = true;
            if (this.dataRangeOption.text) {
                needValueText = false;
                if (this.dataRangeOption.text[0]) {
                    textShape = this._getTextShape(lastX, lastY, this.dataRangeOption.text[0]);
                    if (this.dataRangeOption.orient == 'horizontal') {
                        lastX += zrArea.getTextWidth(this.dataRangeOption.text[0], font) + this._textGap;
                    } else {
                        lastY += textHeight + this._textGap;
                        textShape.style.y += textHeight / 2 + this._textGap;
                        textShape.style.textBaseline = 'bottom';
                    }
                    this.shapeList.push(new TextShape(textShape));
                }
            }
            var zrColor = require('zrender/tool/color');
            var per = 1 / (this.dataRangeOption.color.length - 1);
            var colorList = [];
            for (var i = 0, l = this.dataRangeOption.color.length; i < l; i++) {
                colorList.push([
                    i * per,
                    this.dataRangeOption.color[i]
                ]);
            }
            if (this.dataRangeOption.orient == 'horizontal') {
                itemShape = {
                    zlevel: this._zlevelBase,
                    style: {
                        x: lastX,
                        y: lastY,
                        width: itemWidth * 10,
                        height: itemHeight,
                        color: zrColor.getLinearGradient(lastX, lastY, lastX + itemWidth * 10, lastY, colorList)
                    },
                    hoverable: false
                };
                lastX += itemWidth * 10 + this._textGap;
            } else {
                itemShape = {
                    zlevel: this._zlevelBase,
                    style: {
                        x: lastX,
                        y: lastY,
                        width: itemWidth,
                        height: itemHeight * 10,
                        color: zrColor.getLinearGradient(lastX, lastY, lastX, lastY + itemHeight * 10, colorList)
                    },
                    hoverable: false
                };
                lastY += itemHeight * 10 + this._textGap;
            }
            this.shapeList.push(new RectangleShape(itemShape));
            this._calculableLocation = itemShape.style;
            if (this.dataRangeOption.calculable) {
                this._buildFiller();
                this._bulidMask();
                this._bulidHandle();
            }
            this._buildIndicator();
            if (!needValueText && this.dataRangeOption.text[1]) {
                textShape = this._getTextShape(lastX, lastY, this.dataRangeOption.text[1]);
                this.shapeList.push(new TextShape(textShape));
            }
        },
        _buildIndicator: function () {
            var x = this._calculableLocation.x;
            var y = this._calculableLocation.y;
            var width = this._calculableLocation.width;
            var height = this._calculableLocation.height;
            var size = 5;
            var pointList;
            var textPosition;
            if (this.dataRangeOption.orient == 'horizontal') {
                if (this.dataRangeOption.y != 'bottom') {
                    pointList = [
                        [
                            x,
                            y + height
                        ],
                        [
                            x - size,
                            y + height + size
                        ],
                        [
                            x + size,
                            y + height + size
                        ]
                    ];
                    textPosition = 'bottom';
                } else {
                    pointList = [
                        [
                            x,
                            y
                        ],
                        [
                            x - size,
                            y - size
                        ],
                        [
                            x + size,
                            y - size
                        ]
                    ];
                    textPosition = 'top';
                }
            } else {
                if (this.dataRangeOption.x != 'right') {
                    pointList = [
                        [
                            x + width,
                            y
                        ],
                        [
                            x + width + size,
                            y - size
                        ],
                        [
                            x + width + size,
                            y + size
                        ]
                    ];
                    textPosition = 'right';
                } else {
                    pointList = [
                        [
                            x,
                            y
                        ],
                        [
                            x - size,
                            y - size
                        ],
                        [
                            x - size,
                            y + size
                        ]
                    ];
                    textPosition = 'left';
                }
            }
            this._indicatorShape = {
                style: {
                    pointList: pointList,
                    color: '#fff',
                    __rect: {
                        x: Math.min(pointList[0][0], pointList[1][0]),
                        y: Math.min(pointList[0][1], pointList[1][1]),
                        width: size * (this.dataRangeOption.orient == 'horizontal' ? 2 : 1),
                        height: size * (this.dataRangeOption.orient == 'horizontal' ? 1 : 2)
                    }
                },
                highlightStyle: {
                    brushType: 'fill',
                    textPosition: textPosition,
                    textColor: this.dataRangeOption.textStyle.color
                },
                hoverable: false
            };
            this._indicatorShape = new HandlePolygonShape(this._indicatorShape);
        },
        _buildFiller: function () {
            this._fillerShape = {
                zlevel: this._zlevelBase + 1,
                style: {
                    x: this._calculableLocation.x,
                    y: this._calculableLocation.y,
                    width: this._calculableLocation.width,
                    height: this._calculableLocation.height,
                    color: 'rgba(255,255,255,0)'
                },
                highlightStyle: {
                    strokeColor: 'rgba(255,255,255,0.5)',
                    lineWidth: 1
                },
                draggable: true,
                ondrift: this._ondrift,
                ondragend: this._ondragend,
                onmousemove: this._dispatchHoverLink,
                _type: 'filler'
            };
            this._fillerShape = new RectangleShape(this._fillerShape);
            this.shapeList.push(this._fillerShape);
        },
        _bulidHandle: function () {
            var x = this._calculableLocation.x;
            var y = this._calculableLocation.y;
            var width = this._calculableLocation.width;
            var height = this._calculableLocation.height;
            var font = this.getFont(this.dataRangeOption.textStyle);
            var textHeight = zrArea.getTextHeight('\u56FD', font);
            var textWidth = Math.max(zrArea.getTextWidth(this._textFormat(this.dataRangeOption.max), font), zrArea.getTextWidth(this._textFormat(this.dataRangeOption.min), font)) + 2;
            var pointListStart;
            var textXStart;
            var textYStart;
            var coverRectStart;
            var pointListEnd;
            var textXEnd;
            var textYEnd;
            var coverRectEnd;
            if (this.dataRangeOption.orient == 'horizontal') {
                if (this.dataRangeOption.y != 'bottom') {
                    pointListStart = [
                        [
                            x,
                            y
                        ],
                        [
                            x,
                            y + height + textHeight
                        ],
                        [
                            x - textHeight,
                            y + height + textHeight
                        ],
                        [
                            x - 1,
                            y + height
                        ],
                        [
                            x - 1,
                            y
                        ]
                    ];
                    textXStart = x - textWidth / 2 - textHeight;
                    textYStart = y + height + textHeight / 2 + 2;
                    coverRectStart = {
                        x: x - textWidth - textHeight,
                        y: y + height,
                        width: textWidth + textHeight,
                        height: textHeight
                    };
                    pointListEnd = [
                        [
                            x + width,
                            y
                        ],
                        [
                            x + width,
                            y + height + textHeight
                        ],
                        [
                            x + width + textHeight,
                            y + height + textHeight
                        ],
                        [
                            x + width + 1,
                            y + height
                        ],
                        [
                            x + width + 1,
                            y
                        ]
                    ];
                    textXEnd = x + width + textWidth / 2 + textHeight;
                    textYEnd = textYStart;
                    coverRectEnd = {
                        x: x + width,
                        y: y + height,
                        width: textWidth + textHeight,
                        height: textHeight
                    };
                } else {
                    pointListStart = [
                        [
                            x,
                            y + height
                        ],
                        [
                            x,
                            y - textHeight
                        ],
                        [
                            x - textHeight,
                            y - textHeight
                        ],
                        [
                            x - 1,
                            y
                        ],
                        [
                            x - 1,
                            y + height
                        ]
                    ];
                    textXStart = x - textWidth / 2 - textHeight;
                    textYStart = y - textHeight / 2 - 2;
                    coverRectStart = {
                        x: x - textWidth - textHeight,
                        y: y - textHeight,
                        width: textWidth + textHeight,
                        height: textHeight
                    };
                    pointListEnd = [
                        [
                            x + width,
                            y + height
                        ],
                        [
                            x + width,
                            y - textHeight
                        ],
                        [
                            x + width + textHeight,
                            y - textHeight
                        ],
                        [
                            x + width + 1,
                            y
                        ],
                        [
                            x + width + 1,
                            y + height
                        ]
                    ];
                    textXEnd = x + width + textWidth / 2 + textHeight;
                    textYEnd = textYStart;
                    coverRectEnd = {
                        x: x + width,
                        y: y - textHeight,
                        width: textWidth + textHeight,
                        height: textHeight
                    };
                }
            } else {
                textWidth += textHeight;
                if (this.dataRangeOption.x != 'right') {
                    pointListStart = [
                        [
                            x,
                            y
                        ],
                        [
                            x + width + textHeight,
                            y
                        ],
                        [
                            x + width + textHeight,
                            y - textHeight
                        ],
                        [
                            x + width,
                            y - 1
                        ],
                        [
                            x,
                            y - 1
                        ]
                    ];
                    textXStart = x + width + textWidth / 2 + textHeight / 2;
                    textYStart = y - textHeight / 2;
                    coverRectStart = {
                        x: x + width,
                        y: y - textHeight,
                        width: textWidth + textHeight,
                        height: textHeight
                    };
                    pointListEnd = [
                        [
                            x,
                            y + height
                        ],
                        [
                            x + width + textHeight,
                            y + height
                        ],
                        [
                            x + width + textHeight,
                            y + textHeight + height
                        ],
                        [
                            x + width,
                            y + 1 + height
                        ],
                        [
                            x,
                            y + height + 1
                        ]
                    ];
                    textXEnd = textXStart;
                    textYEnd = y + height + textHeight / 2;
                    coverRectEnd = {
                        x: x + width,
                        y: y + height,
                        width: textWidth + textHeight,
                        height: textHeight
                    };
                } else {
                    pointListStart = [
                        [
                            x + width,
                            y
                        ],
                        [
                            x - textHeight,
                            y
                        ],
                        [
                            x - textHeight,
                            y - textHeight
                        ],
                        [
                            x,
                            y - 1
                        ],
                        [
                            x + width,
                            y - 1
                        ]
                    ];
                    textXStart = x - textWidth / 2 - textHeight / 2;
                    textYStart = y - textHeight / 2;
                    coverRectStart = {
                        x: x - textWidth - textHeight,
                        y: y - textHeight,
                        width: textWidth + textHeight,
                        height: textHeight
                    };
                    pointListEnd = [
                        [
                            x + width,
                            y + height
                        ],
                        [
                            x - textHeight,
                            y + height
                        ],
                        [
                            x - textHeight,
                            y + textHeight + height
                        ],
                        [
                            x,
                            y + 1 + height
                        ],
                        [
                            x + width,
                            y + height + 1
                        ]
                    ];
                    textXEnd = textXStart;
                    textYEnd = y + height + textHeight / 2;
                    coverRectEnd = {
                        x: x - textWidth - textHeight,
                        y: y + height,
                        width: textWidth + textHeight,
                        height: textHeight
                    };
                }
            }
            this._startShape = {
                style: {
                    pointList: pointListStart,
                    text: this._textFormat(this.dataRangeOption.max),
                    textX: textXStart,
                    textY: textYStart,
                    color: this.getColor(this.dataRangeOption.max),
                    rect: coverRectStart,
                    x: pointListStart[0][0],
                    y: pointListStart[0][1],
                    _x: pointListStart[0][0],
                    _y: pointListStart[0][1]
                }
            };
            this._startShape.highlightStyle = {
                strokeColor: this._startShape.style.color,
                lineWidth: 1
            };
            this._endShape = {
                style: {
                    pointList: pointListEnd,
                    text: this._textFormat(this.dataRangeOption.min),
                    textX: textXEnd,
                    textY: textYEnd,
                    color: this.getColor(this.dataRangeOption.min),
                    rect: coverRectEnd,
                    x: pointListEnd[0][0],
                    y: pointListEnd[0][1],
                    _x: pointListEnd[0][0],
                    _y: pointListEnd[0][1]
                }
            };
            this._endShape.highlightStyle = {
                strokeColor: this._endShape.style.color,
                lineWidth: 1
            };
            this._startShape.zlevel = this._endShape.zlevel = this._zlevelBase + 1;
            this._startShape.draggable = this._endShape.draggable = true;
            this._startShape.ondrift = this._endShape.ondrift = this._ondrift;
            this._startShape.ondragend = this._endShape.ondragend = this._ondragend;
            this._startShape.style.textColor = this._endShape.style.textColor = this.dataRangeOption.textStyle.color;
            this._startShape.style.textAlign = this._endShape.style.textAlign = 'center';
            this._startShape.style.textPosition = this._endShape.style.textPosition = 'specific';
            this._startShape.style.textBaseline = this._endShape.style.textBaseline = 'middle';
            this._startShape.style.width = this._endShape.style.width = 0;
            this._startShape.style.height = this._endShape.style.height = 0;
            this._startShape.style.textPosition = this._endShape.style.textPosition = 'specific';
            this._startShape = new HandlePolygonShape(this._startShape);
            this._endShape = new HandlePolygonShape(this._endShape);
            this.shapeList.push(this._startShape);
            this.shapeList.push(this._endShape);
        },
        _bulidMask: function () {
            var x = this._calculableLocation.x;
            var y = this._calculableLocation.y;
            var width = this._calculableLocation.width;
            var height = this._calculableLocation.height;
            this._startMask = {
                zlevel: this._zlevelBase + 1,
                style: {
                    x: x,
                    y: y,
                    width: this.dataRangeOption.orient == 'horizontal' ? 0 : width,
                    height: this.dataRangeOption.orient == 'horizontal' ? height : 0,
                    color: '#ccc'
                },
                hoverable: false
            };
            this._endMask = {
                zlevel: this._zlevelBase + 1,
                style: {
                    x: this.dataRangeOption.orient == 'horizontal' ? x + width : x,
                    y: this.dataRangeOption.orient == 'horizontal' ? y : y + height,
                    width: this.dataRangeOption.orient == 'horizontal' ? 0 : width,
                    height: this.dataRangeOption.orient == 'horizontal' ? height : 0,
                    color: '#ccc'
                },
                hoverable: false
            };
            this._startMask = new RectangleShape(this._startMask);
            this._endMask = new RectangleShape(this._endMask);
            this.shapeList.push(this._startMask);
            this.shapeList.push(this._endMask);
        },
        _buildBackground: function () {
            var padding = this.reformCssArray(this.dataRangeOption.padding);
            this.shapeList.push(new RectangleShape({
                zlevel: this._zlevelBase,
                hoverable: false,
                style: {
                    x: this._itemGroupLocation.x - padding[3],
                    y: this._itemGroupLocation.y - padding[0],
                    width: this._itemGroupLocation.width + padding[3] + padding[1],
                    height: this._itemGroupLocation.height + padding[0] + padding[2],
                    brushType: this.dataRangeOption.borderWidth === 0 ? 'fill' : 'both',
                    color: this.dataRangeOption.backgroundColor,
                    strokeColor: this.dataRangeOption.borderColor,
                    lineWidth: this.dataRangeOption.borderWidth
                }
            }));
        },
        _getItemGroupLocation: function () {
            var data = this._valueTextList;
            var dataLength = data.length;
            var itemGap = this.dataRangeOption.itemGap;
            var itemWidth = this.dataRangeOption.itemWidth;
            var itemHeight = this.dataRangeOption.itemHeight;
            var totalWidth = 0;
            var totalHeight = 0;
            var font = this.getFont(this.dataRangeOption.textStyle);
            var textHeight = zrArea.getTextHeight('\u56FD', font);
            if (this.dataRangeOption.orient == 'horizontal') {
                if (this.dataRangeOption.text || this.dataRangeOption.splitNumber <= 0 || this.dataRangeOption.calculable) {
                    totalWidth = (this.dataRangeOption.splitNumber <= 0 || this.dataRangeOption.calculable ? itemWidth * 10 + itemGap : dataLength * (itemWidth + itemGap)) + (this.dataRangeOption.text && typeof this.dataRangeOption.text[0] != 'undefined' ? zrArea.getTextWidth(this.dataRangeOption.text[0], font) + this._textGap : 0) + (this.dataRangeOption.text && typeof this.dataRangeOption.text[1] != 'undefined' ? zrArea.getTextWidth(this.dataRangeOption.text[1], font) + this._textGap : 0);
                } else {
                    itemWidth += 5;
                    for (var i = 0; i < dataLength; i++) {
                        totalWidth += itemWidth + zrArea.getTextWidth(data[i], font) + itemGap;
                    }
                }
                totalWidth -= itemGap;
                totalHeight = Math.max(textHeight, itemHeight);
            } else {
                var maxWidth;
                if (this.dataRangeOption.text || this.dataRangeOption.splitNumber <= 0 || this.dataRangeOption.calculable) {
                    totalHeight = (this.dataRangeOption.splitNumber <= 0 || this.dataRangeOption.calculable ? itemHeight * 10 + itemGap : dataLength * (itemHeight + itemGap)) + (this.dataRangeOption.text && typeof this.dataRangeOption.text[0] != 'undefined' ? this._textGap + textHeight : 0) + (this.dataRangeOption.text && typeof this.dataRangeOption.text[1] != 'undefined' ? this._textGap + textHeight : 0);
                    maxWidth = Math.max(zrArea.getTextWidth(this.dataRangeOption.text && this.dataRangeOption.text[0] || '', font), zrArea.getTextWidth(this.dataRangeOption.text && this.dataRangeOption.text[1] || '', font));
                    totalWidth = Math.max(itemWidth, maxWidth);
                } else {
                    totalHeight = (itemHeight + itemGap) * dataLength;
                    itemWidth += 5;
                    maxWidth = 0;
                    for (var i = 0; i < dataLength; i++) {
                        maxWidth = Math.max(maxWidth, zrArea.getTextWidth(data[i], font));
                    }
                    totalWidth = itemWidth + maxWidth;
                }
                totalHeight -= itemGap;
            }
            var padding = this.reformCssArray(this.dataRangeOption.padding);
            var x;
            var zrWidth = this.zr.getWidth();
            switch (this.dataRangeOption.x) {
            case 'center':
                x = Math.floor((zrWidth - totalWidth) / 2);
                break;
            case 'left':
                x = padding[3] + this.dataRangeOption.borderWidth;
                break;
            case 'right':
                x = zrWidth - totalWidth - padding[1] - this.dataRangeOption.borderWidth;
                break;
            default:
                x = this.parsePercent(this.dataRangeOption.x, zrWidth);
                x = isNaN(x) ? 0 : x;
                break;
            }
            var y;
            var zrHeight = this.zr.getHeight();
            switch (this.dataRangeOption.y) {
            case 'top':
                y = padding[0] + this.dataRangeOption.borderWidth;
                break;
            case 'bottom':
                y = zrHeight - totalHeight - padding[2] - this.dataRangeOption.borderWidth;
                break;
            case 'center':
                y = Math.floor((zrHeight - totalHeight) / 2);
                break;
            default:
                y = this.parsePercent(this.dataRangeOption.y, zrHeight);
                y = isNaN(y) ? 0 : y;
                break;
            }
            if (this.dataRangeOption.calculable) {
                var handlerWidth = Math.max(zrArea.getTextWidth(this.dataRangeOption.max, font), zrArea.getTextWidth(this.dataRangeOption.min, font)) + textHeight;
                if (this.dataRangeOption.orient == 'horizontal') {
                    if (x < handlerWidth) {
                        x = handlerWidth;
                    }
                    if (x + totalWidth + handlerWidth > zrWidth) {
                        x -= handlerWidth;
                    }
                } else {
                    if (y < textHeight) {
                        y = textHeight;
                    }
                    if (y + totalHeight + textHeight > zrHeight) {
                        y -= textHeight;
                    }
                }
            }
            return {
                x: x,
                y: y,
                width: totalWidth,
                height: totalHeight
            };
        },
        _getTextShape: function (x, y, text) {
            return {
                zlevel: this._zlevelBase,
                style: {
                    x: this.dataRangeOption.orient == 'horizontal' ? x : this._itemGroupLocation.x + this._itemGroupLocation.width / 2,
                    y: this.dataRangeOption.orient == 'horizontal' ? this._itemGroupLocation.y + this._itemGroupLocation.height / 2 : y,
                    color: this.dataRangeOption.textStyle.color,
                    text: text,
                    textFont: this.getFont(this.dataRangeOption.textStyle),
                    textBaseline: this.dataRangeOption.orient == 'horizontal' ? 'middle' : 'top',
                    textAlign: this.dataRangeOption.orient == 'horizontal' ? 'left' : 'center'
                },
                hoverable: false
            };
        },
        _getItemShape: function (x, y, width, height, color) {
            return {
                zlevel: this._zlevelBase,
                style: {
                    x: x,
                    y: y + 1,
                    width: width,
                    height: height - 2,
                    color: color
                },
                highlightStyle: {
                    strokeColor: color,
                    lineWidth: 1
                },
                clickable: true
            };
        },
        __ondrift: function (shape, dx, dy) {
            var x = this._calculableLocation.x;
            var y = this._calculableLocation.y;
            var width = this._calculableLocation.width;
            var height = this._calculableLocation.height;
            if (this.dataRangeOption.orient == 'horizontal') {
                if (shape.style.x + dx <= x) {
                    shape.style.x = x;
                } else if (shape.style.x + dx + shape.style.width >= x + width) {
                    shape.style.x = x + width - shape.style.width;
                } else {
                    shape.style.x += dx;
                }
            } else {
                if (shape.style.y + dy <= y) {
                    shape.style.y = y;
                } else if (shape.style.y + dy + shape.style.height >= y + height) {
                    shape.style.y = y + height - shape.style.height;
                } else {
                    shape.style.y += dy;
                }
            }
            if (shape._type == 'filler') {
                this._syncHandleShape();
            } else {
                this._syncFillerShape(shape);
            }
            if (this.dataRangeOption.realtime) {
                this._syncData();
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
            !this.dataRangeOption.realtime && this._syncData();
            status.dragOut = true;
            status.dragIn = true;
            if (!this.dataRangeOption.realtime) {
                this.messageCenter.dispatch(ecConfig.EVENT.DATA_RANGE, null, {
                    range: {
                        start: this._range.end,
                        end: this._range.start
                    }
                }, this.myChart);
            }
            status.needRefresh = false;
            this.isDragend = false;
            return;
        },
        _syncShapeFromRange: function () {
            var range = this.dataRangeOption.range || {};
            this._range.end = typeof this._range.end != 'undefined' ? this._range.end : typeof range.start != 'undefined' ? range.start : 0;
            this._range.start = typeof this._range.start != 'undefined' ? this._range.start : typeof range.end != 'undefined' ? range.end : 100;
            if (this._range.start != 100 || this._range.end !== 0) {
                if (this.dataRangeOption.orient == 'horizontal') {
                    var width = this._fillerShape.style.width;
                    this._fillerShape.style.x += width * (100 - this._range.start) / 100;
                    this._fillerShape.style.width = width * (this._range.start - this._range.end) / 100;
                } else {
                    var height = this._fillerShape.style.height;
                    this._fillerShape.style.y += height * (100 - this._range.start) / 100;
                    this._fillerShape.style.height = height * (this._range.start - this._range.end) / 100;
                }
                this.zr.modShape(this._fillerShape.id);
                this._syncHandleShape();
            }
        },
        _syncHandleShape: function () {
            var x = this._calculableLocation.x;
            var y = this._calculableLocation.y;
            var width = this._calculableLocation.width;
            var height = this._calculableLocation.height;
            if (this.dataRangeOption.orient == 'horizontal') {
                this._startShape.style.x = this._fillerShape.style.x;
                this._startMask.style.width = this._startShape.style.x - x;
                this._endShape.style.x = this._fillerShape.style.x + this._fillerShape.style.width;
                this._endMask.style.x = this._endShape.style.x;
                this._endMask.style.width = x + width - this._endShape.style.x;
                this._range.start = Math.ceil(100 - (this._startShape.style.x - x) / width * 100);
                this._range.end = Math.floor(100 - (this._endShape.style.x - x) / width * 100);
            } else {
                this._startShape.style.y = this._fillerShape.style.y;
                this._startMask.style.height = this._startShape.style.y - y;
                this._endShape.style.y = this._fillerShape.style.y + this._fillerShape.style.height;
                this._endMask.style.y = this._endShape.style.y;
                this._endMask.style.height = y + height - this._endShape.style.y;
                this._range.start = Math.ceil(100 - (this._startShape.style.y - y) / height * 100);
                this._range.end = Math.floor(100 - (this._endShape.style.y - y) / height * 100);
            }
            this._syncShape();
        },
        _syncFillerShape: function (e) {
            var x = this._calculableLocation.x;
            var y = this._calculableLocation.y;
            var width = this._calculableLocation.width;
            var height = this._calculableLocation.height;
            var a;
            var b;
            if (this.dataRangeOption.orient == 'horizontal') {
                a = this._startShape.style.x;
                b = this._endShape.style.x;
                if (e.id == this._startShape.id && a >= b) {
                    b = a;
                    this._endShape.style.x = a;
                } else if (e.id == this._endShape.id && a >= b) {
                    a = b;
                    this._startShape.style.x = a;
                }
                this._fillerShape.style.x = a;
                this._fillerShape.style.width = b - a;
                this._startMask.style.width = a - x;
                this._endMask.style.x = b;
                this._endMask.style.width = x + width - b;
                this._range.start = Math.ceil(100 - (a - x) / width * 100);
                this._range.end = Math.floor(100 - (b - x) / width * 100);
            } else {
                a = this._startShape.style.y;
                b = this._endShape.style.y;
                if (e.id == this._startShape.id && a >= b) {
                    b = a;
                    this._endShape.style.y = a;
                } else if (e.id == this._endShape.id && a >= b) {
                    a = b;
                    this._startShape.style.y = a;
                }
                this._fillerShape.style.y = a;
                this._fillerShape.style.height = b - a;
                this._startMask.style.height = a - y;
                this._endMask.style.y = b;
                this._endMask.style.height = y + height - b;
                this._range.start = Math.ceil(100 - (a - y) / height * 100);
                this._range.end = Math.floor(100 - (b - y) / height * 100);
            }
            this._syncShape();
        },
        _syncShape: function () {
            this._startShape.position = [
                this._startShape.style.x - this._startShape.style._x,
                this._startShape.style.y - this._startShape.style._y
            ];
            this._startShape.style.text = this._textFormat(this._gap * this._range.start + this.dataRangeOption.min);
            this._startShape.style.color = this._startShape.highlightStyle.strokeColor = this.getColor(this._gap * this._range.start + this.dataRangeOption.min);
            this._endShape.position = [
                this._endShape.style.x - this._endShape.style._x,
                this._endShape.style.y - this._endShape.style._y
            ];
            this._endShape.style.text = this._textFormat(this._gap * this._range.end + this.dataRangeOption.min);
            this._endShape.style.color = this._endShape.highlightStyle.strokeColor = this.getColor(this._gap * this._range.end + this.dataRangeOption.min);
            this.zr.modShape(this._startShape.id);
            this.zr.modShape(this._endShape.id);
            this.zr.modShape(this._startMask.id);
            this.zr.modShape(this._endMask.id);
            this.zr.modShape(this._fillerShape.id);
            this.zr.refresh();
        },
        _syncData: function () {
            if (this.dataRangeOption.realtime) {
                this.messageCenter.dispatch(ecConfig.EVENT.DATA_RANGE, null, {
                    range: {
                        start: this._range.end,
                        end: this._range.start
                    }
                }, this.myChart);
            }
        },
        __dataRangeSelected: function (param) {
            var idx = param.target._idx;
            this._selectedMap[idx] = !this._selectedMap[idx];
            this.messageCenter.dispatch(ecConfig.EVENT.REFRESH, null, null, this.myChart);
        },
        __dispatchHoverLink: function (param) {
            var valueMin;
            var valueMax;
            if (this.dataRangeOption.calculable) {
                var totalValue = this.dataRangeOption.max - this.dataRangeOption.min;
                var curValue;
                if (this.dataRangeOption.orient == 'horizontal') {
                    curValue = (1 - (zrEvent.getX(param.event) - this._calculableLocation.x) / this._calculableLocation.width) * totalValue;
                } else {
                    curValue = (1 - (zrEvent.getY(param.event) - this._calculableLocation.y) / this._calculableLocation.height) * totalValue;
                }
                valueMin = curValue - totalValue * 0.05;
                valueMax = curValue + totalValue * 0.05;
            } else {
                var idx = param.target._idx;
                valueMax = (this._colorList.length - idx) * this._gap + this.dataRangeOption.min;
                valueMin = valueMax - this._gap;
            }
            this.messageCenter.dispatch(ecConfig.EVENT.DATA_RANGE_HOVERLINK, param.event, {
                valueMin: valueMin,
                valueMax: valueMax
            }, this.myChart);
            return;
        },
        __onhoverlink: function (param) {
            if (this.dataRangeOption.show && this.dataRangeOption.hoverLink && this._indicatorShape && param && param.seriesIndex != null && param.dataIndex != null) {
                var curValue = param.value;
                if (isNaN(curValue)) {
                    return;
                }
                if (curValue < this.dataRangeOption.min) {
                    curValue = this.dataRangeOption.min;
                } else if (curValue > this.dataRangeOption.max) {
                    curValue = this.dataRangeOption.max;
                }
                if (this.dataRangeOption.orient == 'horizontal') {
                    this._indicatorShape.position = [
                        (this.dataRangeOption.max - curValue) / (this.dataRangeOption.max - this.dataRangeOption.min) * this._calculableLocation.width,
                        0
                    ];
                } else {
                    this._indicatorShape.position = [
                        0,
                        (this.dataRangeOption.max - curValue) / (this.dataRangeOption.max - this.dataRangeOption.min) * this._calculableLocation.height
                    ];
                }
                this._indicatorShape.style.text = param.value;
                this._indicatorShape.style.color = this.getColor(curValue);
                this.zr.addHoverShape(this._indicatorShape);
            }
        },
        _textFormat: function (valueStart, valueEnd) {
            valueStart = valueStart.toFixed(this.dataRangeOption.precision);
            valueEnd = typeof valueEnd != 'undefined' ? valueEnd.toFixed(this.dataRangeOption.precision) : '';
            if (this.dataRangeOption.formatter) {
                if (typeof this.dataRangeOption.formatter == 'string') {
                    return this.dataRangeOption.formatter.replace('{value}', valueStart).replace('{value2}', valueEnd);
                } else if (typeof this.dataRangeOption.formatter == 'function') {
                    return this.dataRangeOption.formatter.call(this.myChart, valueStart, valueEnd);
                }
            }
            if (valueEnd !== '') {
                return valueStart + ' - ' + valueEnd;
            }
            return valueStart;
        },
        refresh: function (newOption) {
            if (newOption) {
                this.option = newOption;
                this.option.dataRange = this.reformOption(this.option.dataRange);
                this.dataRangeOption = this.option.dataRange;
                if (!this.myChart.canvasSupported) {
                    this.dataRangeOption.realtime = false;
                }
                var splitNumber = this.dataRangeOption.splitNumber <= 0 || this.dataRangeOption.calculable ? 100 : this.dataRangeOption.splitNumber;
                this._colorList = zrColor.getGradientColors(this.dataRangeOption.color, Math.max((splitNumber - this.dataRangeOption.color.length) / (this.dataRangeOption.color.length - 1), 0) + 1);
                if (this._colorList.length > splitNumber) {
                    var len = this._colorList.length;
                    var newColorList = [this._colorList[0]];
                    var step = len / (splitNumber - 1);
                    for (var i = 1; i < splitNumber - 1; i++) {
                        newColorList.push(this._colorList[Math.floor(i * step)]);
                    }
                    newColorList.push(this._colorList[len - 1]);
                    this._colorList = newColorList;
                }
                var precision = this.dataRangeOption.precision;
                this._gap = (this.dataRangeOption.max - this.dataRangeOption.min) / splitNumber;
                while (this._gap.toFixed(precision) - 0 != this._gap && precision < 5) {
                    precision++;
                }
                this.dataRangeOption.precision = precision;
                this._gap = ((this.dataRangeOption.max - this.dataRangeOption.min) / splitNumber).toFixed(precision) - 0;
                this._valueTextList = [];
                for (var i = 0; i < splitNumber; i++) {
                    this._selectedMap[i] = true;
                    this._valueTextList.unshift(this._textFormat(i * this._gap + this.dataRangeOption.min, (i + 1) * this._gap + this.dataRangeOption.min));
                }
            }
            this.clear();
            this._buildShape();
        },
        getColor: function (value) {
            if (isNaN(value)) {
                return null;
            }
            if (this.dataRangeOption.min == this.dataRangeOption.max) {
                return this._colorList[0];
            }
            if (value < this.dataRangeOption.min) {
                value = this.dataRangeOption.min;
            } else if (value > this.dataRangeOption.max) {
                value = this.dataRangeOption.max;
            }
            if (this.dataRangeOption.calculable) {
                if (value - (this._gap * this._range.start + this.dataRangeOption.min) > 0.00005 || value - (this._gap * this._range.end + this.dataRangeOption.min) < -0.00005) {
                    return null;
                }
            }
            var idx = this._colorList.length - Math.ceil((value - this.dataRangeOption.min) / (this.dataRangeOption.max - this.dataRangeOption.min) * this._colorList.length);
            if (idx == this._colorList.length) {
                idx--;
            }
            if (this._selectedMap[idx]) {
                return this._colorList[idx];
            } else {
                return null;
            }
        },
        getColorByIndex: function (idx) {
            if (idx >= this._colorList.length) {
                idx = this._colorList.length - 1;
            } else if (idx < 0) {
                idx = 0;
            }
            return this._colorList[idx];
        },
        onbeforDispose: function () {
            this.messageCenter.unbind(ecConfig.EVENT.HOVER, this._onhoverlink);
        }
    };
    zrUtil.inherits(DataRange, Base);
    require('../component').define('dataRange', DataRange);
    return DataRange;
});