/*! 2015 Baidu Inc. All Rights Reserved */
define('echarts/chart/map', function (require) {
    var ComponentBase = require('../component/base');
    var ChartBase = require('./base');
    var TextShape = require('zrender/shape/Text');
    var PathShape = require('zrender/shape/Path');
    var CircleShape = require('zrender/shape/Circle');
    var RectangleShape = require('zrender/shape/Rectangle');
    var LineShape = require('zrender/shape/Line');
    var PolygonShape = require('zrender/shape/Polygon');
    var EllipseShape = require('zrender/shape/Ellipse');
    require('../component/dataRange');
    require('../component/roamController');
    var ecConfig = require('../config');
    var ecData = require('../util/ecData');
    var zrUtil = require('zrender/tool/util');
    var zrConfig = require('zrender/config');
    var zrEvent = require('zrender/tool/event');
    var _mapParams = require('../util/mapData/params').params;
    var _textFixed = require('../util/mapData/textFixed');
    var _geoCoord = require('../util/mapData/geoCoord');
    function Map(ecTheme, messageCenter, zr, option, myChart) {
        ComponentBase.call(this, ecTheme, messageCenter, zr, option, myChart);
        ChartBase.call(this);
        var self = this;
        self._onmousewheel = function (params) {
            return self.__onmousewheel(params);
        };
        self._onmousedown = function (params) {
            return self.__onmousedown(params);
        };
        self._onmousemove = function (params) {
            return self.__onmousemove(params);
        };
        self._onmouseup = function (params) {
            return self.__onmouseup(params);
        };
        self._onroamcontroller = function (params) {
            return self.__onroamcontroller(params);
        };
        self._ondrhoverlink = function (params) {
            return self.__ondrhoverlink(params);
        };
        this._isAlive = true;
        this._selectedMode = {};
        this._activeMapType = {};
        this._clickable = {};
        this._hoverable = {};
        this._showLegendSymbol = {};
        this._selected = {};
        this._mapTypeMap = {};
        this._mapDataMap = {};
        this._nameMap = {};
        this._specialArea = {};
        this._refreshDelayTicket;
        this._mapDataRequireCounter;
        this._markAnimation = false;
        this._hoverLinkMap = {};
        this._roamMap = {};
        this._scaleLimitMap = {};
        this._mx;
        this._my;
        this._mousedown;
        this._justMove;
        this._curMapType;
        this.refresh(option);
        this.zr.on(zrConfig.EVENT.MOUSEWHEEL, this._onmousewheel);
        this.zr.on(zrConfig.EVENT.MOUSEDOWN, this._onmousedown);
        messageCenter.bind(ecConfig.EVENT.ROAMCONTROLLER, this._onroamcontroller);
        messageCenter.bind(ecConfig.EVENT.DATA_RANGE_HOVERLINK, this._ondrhoverlink);
    }
    Map.prototype = {
        type: ecConfig.CHART_TYPE_MAP,
        _buildShape: function () {
            var series = this.series;
            this.selectedMap = {};
            this._activeMapType = {};
            var legend = this.component.legend;
            var seriesName;
            var valueData = {};
            var mapType;
            var data;
            var name;
            var mapSeries = {};
            var mapValuePrecision = {};
            var valueCalculation = {};
            for (var i = 0, l = series.length; i < l; i++) {
                if (series[i].type == ecConfig.CHART_TYPE_MAP) {
                    series[i] = this.reformOption(series[i]);
                    mapType = series[i].mapType;
                    mapSeries[mapType] = mapSeries[mapType] || {};
                    mapSeries[mapType][i] = true;
                    mapValuePrecision[mapType] = mapValuePrecision[mapType] || series[i].mapValuePrecision;
                    this._scaleLimitMap[mapType] = this._scaleLimitMap[mapType] || {};
                    series[i].scaleLimit && zrUtil.merge(this._scaleLimitMap[mapType], series[i].scaleLimit, true);
                    this._roamMap[mapType] = series[i].roam || this._roamMap[mapType];
                    this._hoverLinkMap[mapType] = series[i].dataRangeHoverLink || this._hoverLinkMap[mapType];
                    this._nameMap[mapType] = this._nameMap[mapType] || {};
                    series[i].nameMap && zrUtil.merge(this._nameMap[mapType], series[i].nameMap, true);
                    this._activeMapType[mapType] = true;
                    if (series[i].textFixed) {
                        zrUtil.merge(_textFixed, series[i].textFixed, true);
                    }
                    if (series[i].geoCoord) {
                        zrUtil.merge(_geoCoord, series[i].geoCoord, true);
                    }
                    this._selectedMode[mapType] = this._selectedMode[mapType] || series[i].selectedMode;
                    if (this._hoverable[mapType] == null || this._hoverable[mapType]) {
                        this._hoverable[mapType] = series[i].hoverable;
                    }
                    if (this._clickable[mapType] == null || this._clickable[mapType]) {
                        this._clickable[mapType] = series[i].clickable;
                    }
                    if (this._showLegendSymbol[mapType] == null || this._showLegendSymbol[mapType]) {
                        this._showLegendSymbol[mapType] = series[i].showLegendSymbol;
                    }
                    valueCalculation[mapType] = valueCalculation[mapType] || series[i].mapValueCalculation;
                    seriesName = series[i].name;
                    this.selectedMap[seriesName] = legend ? legend.isSelected(seriesName) : true;
                    if (this.selectedMap[seriesName]) {
                        valueData[mapType] = valueData[mapType] || {};
                        data = series[i].data;
                        for (var j = 0, k = data.length; j < k; j++) {
                            name = this._nameChange(mapType, data[j].name);
                            valueData[mapType][name] = valueData[mapType][name] || { seriesIndex: [] };
                            for (var key in data[j]) {
                                if (key != 'value') {
                                    valueData[mapType][name][key] = data[j][key];
                                } else if (!isNaN(data[j].value)) {
                                    valueData[mapType][name].value == null && (valueData[mapType][name].value = 0);
                                    valueData[mapType][name].value += data[j].value;
                                }
                            }
                            valueData[mapType][name].seriesIndex.push(i);
                        }
                    }
                }
            }
            this._mapDataRequireCounter = 0;
            for (var mt in valueData) {
                this._mapDataRequireCounter++;
            }
            this._clearSelected();
            if (this._mapDataRequireCounter === 0) {
                this.clear();
                this.zr && this.zr.delShape(this.lastShapeList);
                this.lastShapeList = [];
            }
            for (var mt in valueData) {
                if (valueCalculation[mt] && valueCalculation[mt] == 'average') {
                    for (var k in valueData[mt]) {
                        valueData[mt][k].value = (valueData[mt][k].value / valueData[mt][k].seriesIndex.length).toFixed(mapValuePrecision[mt]) - 0;
                    }
                }
                this._mapDataMap[mt] = this._mapDataMap[mt] || {};
                if (this._mapDataMap[mt].mapData) {
                    this._mapDataCallback(mt, valueData[mt], mapSeries[mt])(this._mapDataMap[mt].mapData);
                } else if (_mapParams[mt.replace(/\|.*/, '')].getGeoJson) {
                    this._specialArea[mt] = _mapParams[mt.replace(/\|.*/, '')].specialArea || this._specialArea[mt];
                    _mapParams[mt.replace(/\|.*/, '')].getGeoJson(this._mapDataCallback(mt, valueData[mt], mapSeries[mt]));
                }
            }
        },
        _mapDataCallback: function (mt, vd, ms) {
            var self = this;
            return function (md) {
                if (!self._isAlive || self._activeMapType[mt] == null) {
                    return;
                }
                if (mt.indexOf('|') != -1) {
                    md = self._getSubMapData(mt, md);
                }
                self._mapDataMap[mt].mapData = md;
                if (md.firstChild) {
                    self._mapDataMap[mt].rate = 1;
                    self._mapDataMap[mt].projection = require('../util/projection/svg');
                } else {
                    self._mapDataMap[mt].rate = 0.75;
                    self._mapDataMap[mt].projection = require('../util/projection/normal');
                }
                self._buildMap(mt, self._getProjectionData(mt, md, ms), vd, ms);
                self._buildMark(mt, ms);
                if (--self._mapDataRequireCounter <= 0) {
                    self.addShapeList();
                    self.zr.refresh();
                }
            };
        },
        _clearSelected: function () {
            for (var k in this._selected) {
                if (!this._activeMapType[this._mapTypeMap[k]]) {
                    delete this._selected[k];
                    delete this._mapTypeMap[k];
                }
            }
        },
        _getSubMapData: function (mapType, mapData) {
            var subType = mapType.replace(/^.*\|/, '');
            var features = mapData.features;
            for (var i = 0, l = features.length; i < l; i++) {
                if (features[i].properties && features[i].properties.name == subType) {
                    features = features[i];
                    if (subType == 'United States of America' && features.geometry.coordinates.length > 1) {
                        features = {
                            geometry: {
                                coordinates: features.geometry.coordinates.slice(5, 6),
                                type: features.geometry.type
                            },
                            id: features.id,
                            properties: features.properties,
                            type: features.type
                        };
                    }
                    break;
                }
            }
            return {
                'type': 'FeatureCollection',
                'features': [features]
            };
        },
        _getProjectionData: function (mapType, mapData, mapSeries) {
            var normalProjection = this._mapDataMap[mapType].projection;
            var province = [];
            var bbox = this._mapDataMap[mapType].bbox || normalProjection.getBbox(mapData, this._specialArea[mapType]);
            var transform;
            if (!this._mapDataMap[mapType].hasRoam) {
                transform = this._getTransform(bbox, mapSeries, this._mapDataMap[mapType].rate);
            } else {
                transform = this._mapDataMap[mapType].transform;
            }
            var lastTransform = this._mapDataMap[mapType].lastTransform || { scale: {} };
            var pathArray;
            if (transform.left != lastTransform.left || transform.top != lastTransform.top || transform.scale.x != lastTransform.scale.x || transform.scale.y != lastTransform.scale.y) {
                pathArray = normalProjection.geoJson2Path(mapData, transform, this._specialArea[mapType]);
                lastTransform = zrUtil.clone(transform);
            } else {
                transform = this._mapDataMap[mapType].transform;
                pathArray = this._mapDataMap[mapType].pathArray;
            }
            this._mapDataMap[mapType].bbox = bbox;
            this._mapDataMap[mapType].transform = transform;
            this._mapDataMap[mapType].lastTransform = lastTransform;
            this._mapDataMap[mapType].pathArray = pathArray;
            var position = [
                    transform.left,
                    transform.top
                ];
            for (var i = 0, l = pathArray.length; i < l; i++) {
                province.push(this._getSingleProvince(mapType, pathArray[i], position));
            }
            if (this._specialArea[mapType]) {
                for (var area in this._specialArea[mapType]) {
                    province.push(this._getSpecialProjectionData(mapType, mapData, area, this._specialArea[mapType][area], position));
                }
            }
            if (mapType == 'china') {
                var leftTop = this.geo2pos(mapType, _geoCoord['\u5357\u6D77\u8BF8\u5C9B'] || _mapParams['\u5357\u6D77\u8BF8\u5C9B'].textCoord);
                var scale = transform.scale.x / 10.5;
                var textPosition = [
                        32 * scale + leftTop[0],
                        83 * scale + leftTop[1]
                    ];
                if (_textFixed['\u5357\u6D77\u8BF8\u5C9B']) {
                    textPosition[0] += _textFixed['\u5357\u6D77\u8BF8\u5C9B'][0];
                    textPosition[1] += _textFixed['\u5357\u6D77\u8BF8\u5C9B'][1];
                }
                province.push({
                    name: this._nameChange(mapType, '\u5357\u6D77\u8BF8\u5C9B'),
                    path: _mapParams['\u5357\u6D77\u8BF8\u5C9B'].getPath(leftTop, scale),
                    position: position,
                    textX: textPosition[0],
                    textY: textPosition[1]
                });
            }
            return province;
        },
        _getSpecialProjectionData: function (mapType, mapData, areaName, mapSize, position) {
            mapData = this._getSubMapData('x|' + areaName, mapData);
            var normalProjection = require('../util/projection/normal');
            var bbox = normalProjection.getBbox(mapData);
            var leftTop = this.geo2pos(mapType, [
                    mapSize.left,
                    mapSize.top
                ]);
            var rightBottom = this.geo2pos(mapType, [
                    mapSize.left + mapSize.width,
                    mapSize.top + mapSize.height
                ]);
            var width = Math.abs(rightBottom[0] - leftTop[0]);
            var height = Math.abs(rightBottom[1] - leftTop[1]);
            var mapWidth = bbox.width;
            var mapHeight = bbox.height;
            var xScale = width / 0.75 / mapWidth;
            var yScale = height / mapHeight;
            if (xScale > yScale) {
                xScale = yScale * 0.75;
                width = mapWidth * xScale;
            } else {
                yScale = xScale;
                xScale = yScale * 0.75;
                height = mapHeight * yScale;
            }
            var transform = {
                    OffsetLeft: leftTop[0],
                    OffsetTop: leftTop[1],
                    scale: {
                        x: xScale,
                        y: yScale
                    }
                };
            var pathArray = normalProjection.geoJson2Path(mapData, transform);
            return this._getSingleProvince(mapType, pathArray[0], position);
        },
        _getSingleProvince: function (mapType, path, position) {
            var textPosition;
            var name = path.properties.name;
            var textFixed = _textFixed[name] || [
                    0,
                    0
                ];
            if (_geoCoord[name]) {
                textPosition = this.geo2pos(mapType, _geoCoord[name]);
            } else if (path.cp) {
                textPosition = [
                    path.cp[0] + textFixed[0],
                    path.cp[1] + textFixed[1]
                ];
            } else {
                var bbox = this._mapDataMap[mapType].bbox;
                textPosition = this.geo2pos(mapType, [
                    bbox.left + bbox.width / 2,
                    bbox.top + bbox.height / 2
                ]);
                textPosition[0] += textFixed[0];
                textPosition[1] += textFixed[1];
            }
            path.name = this._nameChange(mapType, name);
            path.position = position;
            path.textX = textPosition[0];
            path.textY = textPosition[1];
            return path;
        },
        _getTransform: function (bbox, mapSeries, rate) {
            var series = this.series;
            var mapLocation;
            var x;
            var cusX;
            var y;
            var cusY;
            var width;
            var height;
            var zrWidth = this.zr.getWidth();
            var zrHeight = this.zr.getHeight();
            var padding = Math.round(Math.min(zrWidth, zrHeight) * 0.02);
            for (var key in mapSeries) {
                mapLocation = series[key].mapLocation || {};
                cusX = mapLocation.x || cusX;
                cusY = mapLocation.y || cusY;
                width = mapLocation.width || width;
                height = mapLocation.height || height;
            }
            x = this.parsePercent(cusX, zrWidth);
            x = isNaN(x) ? padding : x;
            y = this.parsePercent(cusY, zrHeight);
            y = isNaN(y) ? padding : y;
            width = width == null ? zrWidth - x - 2 * padding : this.parsePercent(width, zrWidth);
            height = height == null ? zrHeight - y - 2 * padding : this.parsePercent(height, zrHeight);
            var mapWidth = bbox.width;
            var mapHeight = bbox.height;
            var xScale = width / rate / mapWidth;
            var yScale = height / mapHeight;
            if (xScale > yScale) {
                xScale = yScale * rate;
                width = mapWidth * xScale;
            } else {
                yScale = xScale;
                xScale = yScale * rate;
                height = mapHeight * yScale;
            }
            if (isNaN(cusX)) {
                cusX = cusX || 'center';
                switch (cusX + '') {
                case 'center':
                    x = Math.floor((zrWidth - width) / 2);
                    break;
                case 'right':
                    x = zrWidth - width;
                    break;
                }
            }
            if (isNaN(cusY)) {
                cusY = cusY || 'center';
                switch (cusY + '') {
                case 'center':
                    y = Math.floor((zrHeight - height) / 2);
                    break;
                case 'bottom':
                    y = zrHeight - height;
                    break;
                }
            }
            return {
                left: x,
                top: y,
                width: width,
                height: height,
                baseScale: 1,
                scale: {
                    x: xScale,
                    y: yScale
                }
            };
        },
        _buildMap: function (mapType, mapData, valueData, mapSeries) {
            var series = this.series;
            var legend = this.component.legend;
            var dataRange = this.component.dataRange;
            var seriesName;
            var name;
            var data;
            var value;
            var queryTarget;
            var defaultOption = this.ecTheme.map;
            var color;
            var font;
            var style;
            var highlightStyle;
            var shape;
            var textShape;
            for (var i = 0, l = mapData.length; i < l; i++) {
                style = zrUtil.clone(mapData[i]);
                highlightStyle = {
                    name: style.name,
                    path: style.path,
                    position: zrUtil.clone(style.position)
                };
                name = style.name;
                data = valueData[name];
                if (data) {
                    queryTarget = [data];
                    seriesName = '';
                    for (var j = 0, k = data.seriesIndex.length; j < k; j++) {
                        queryTarget.push(series[data.seriesIndex[j]]);
                        seriesName += series[data.seriesIndex[j]].name + ' ';
                        if (legend && this._showLegendSymbol[mapType] && legend.hasColor(series[data.seriesIndex[j]].name)) {
                            this.shapeList.push(new CircleShape({
                                zlevel: this._zlevelBase + 1,
                                position: zrUtil.clone(style.position),
                                _mapType: mapType,
                                style: {
                                    x: style.textX + 3 + j * 7,
                                    y: style.textY - 10,
                                    r: 3,
                                    color: legend.getColor(series[data.seriesIndex[j]].name)
                                },
                                hoverable: false
                            }));
                        }
                    }
                    queryTarget.push(defaultOption);
                    value = data.value;
                } else {
                    data = '-';
                    seriesName = '';
                    queryTarget = [];
                    for (var key in mapSeries) {
                        queryTarget.push(series[key]);
                    }
                    queryTarget.push(defaultOption);
                    value = '-';
                }
                color = dataRange && !isNaN(value) ? dataRange.getColor(value) : null;
                style.color = style.color || color || this.getItemStyleColor(this.deepQuery(queryTarget, 'itemStyle.normal.color'), data.seriesIndex, -1, data) || this.deepQuery(queryTarget, 'itemStyle.normal.areaStyle.color');
                style.strokeColor = style.strokeColor || this.deepQuery(queryTarget, 'itemStyle.normal.borderColor');
                style.lineWidth = style.lineWidth || this.deepQuery(queryTarget, 'itemStyle.normal.borderWidth');
                highlightStyle.color = this.getItemStyleColor(this.deepQuery(queryTarget, 'itemStyle.emphasis.color'), data.seriesIndex, -1, data) || this.deepQuery(queryTarget, 'itemStyle.emphasis.areaStyle.color') || style.color;
                highlightStyle.strokeColor = this.deepQuery(queryTarget, 'itemStyle.emphasis.borderColor') || style.strokeColor;
                highlightStyle.lineWidth = this.deepQuery(queryTarget, 'itemStyle.emphasis.borderWidth') || style.lineWidth;
                style.brushType = highlightStyle.brushType = style.brushType || 'both';
                style.lineJoin = highlightStyle.lineJoin = 'round';
                style._name = highlightStyle._name = name;
                font = this.deepQuery(queryTarget, 'itemStyle.normal.label.textStyle');
                textShape = {
                    zlevel: this._zlevelBase + 1,
                    position: zrUtil.clone(style.position),
                    _mapType: mapType,
                    _geo: this.pos2geo(mapType, [
                        style.textX,
                        style.textY
                    ]),
                    style: {
                        brushType: 'fill',
                        x: style.textX,
                        y: style.textY,
                        text: this.getLabelText(name, value, queryTarget, 'normal'),
                        _name: name,
                        textAlign: 'center',
                        color: this.deepQuery(queryTarget, 'itemStyle.normal.label.show') ? this.deepQuery(queryTarget, 'itemStyle.normal.label.textStyle.color') : 'rgba(0,0,0,0)',
                        textFont: this.getFont(font)
                    }
                };
                textShape._style = zrUtil.clone(textShape.style);
                textShape.highlightStyle = zrUtil.clone(textShape.style);
                if (this.deepQuery(queryTarget, 'itemStyle.emphasis.label.show')) {
                    textShape.highlightStyle.text = this.getLabelText(name, value, queryTarget, 'emphasis');
                    textShape.highlightStyle.color = this.deepQuery(queryTarget, 'itemStyle.emphasis.label.textStyle.color') || textShape.style.color;
                    font = this.deepQuery(queryTarget, 'itemStyle.emphasis.label.textStyle') || font;
                    textShape.highlightStyle.textFont = this.getFont(font);
                } else {
                    textShape.highlightStyle.color = 'rgba(0,0,0,0)';
                }
                shape = {
                    zlevel: this._zlevelBase,
                    position: zrUtil.clone(style.position),
                    style: style,
                    highlightStyle: highlightStyle,
                    _style: zrUtil.clone(style),
                    _mapType: mapType
                };
                if (style.scale != null) {
                    shape.scale = zrUtil.clone(style.scale);
                }
                textShape = new TextShape(textShape);
                switch (shape.style.shapeType) {
                case 'rectangle':
                    shape = new RectangleShape(shape);
                    break;
                case 'line':
                    shape = new LineShape(shape);
                    break;
                case 'circle':
                    shape = new CircleShape(shape);
                    break;
                case 'polygon':
                    shape = new PolygonShape(shape);
                    break;
                case 'ellipse':
                    shape = new EllipseShape(shape);
                    break;
                default:
                    shape = new PathShape(shape);
                    if (shape.buildPathArray) {
                        shape.style.pathArray = shape.buildPathArray(shape.style.path);
                    }
                    break;
                }
                if (this._selectedMode[mapType] && this._selected[name] || data.selected && this._selected[name] !== false) {
                    textShape.style = textShape.highlightStyle;
                    shape.style = shape.highlightStyle;
                }
                textShape.clickable = shape.clickable = this._clickable[mapType] && (data.clickable == null || data.clickable);
                if (this._selectedMode[mapType]) {
                    this._selected[name] = this._selected[name] != null ? this._selected[name] : data.selected;
                    this._mapTypeMap[name] = mapType;
                    if (data.selectable == null || data.selectable) {
                        shape.clickable = textShape.clickable = true;
                        shape.onclick = textShape.onclick = this.shapeHandler.onclick;
                    }
                }
                if (this._hoverable[mapType] && (data.hoverable == null || data.hoverable)) {
                    textShape.hoverable = shape.hoverable = true;
                    shape.hoverConnect = textShape.id;
                    textShape.hoverConnect = shape.id;
                } else {
                    textShape.hoverable = shape.hoverable = false;
                }
                ecData.pack(textShape, {
                    name: seriesName,
                    tooltip: this.deepQuery(queryTarget, 'tooltip')
                }, 0, data, 0, name);
                this.shapeList.push(textShape);
                ecData.pack(shape, {
                    name: seriesName,
                    tooltip: this.deepQuery(queryTarget, 'tooltip')
                }, 0, data, 0, name);
                this.shapeList.push(shape);
            }
        },
        _buildMark: function (mapType, mapSeries) {
            this._seriesIndexToMapType = this._seriesIndexToMapType || {};
            this.markAttachStyle = this.markAttachStyle || {};
            var position = [
                    this._mapDataMap[mapType].transform.left,
                    this._mapDataMap[mapType].transform.top
                ];
            if (mapType == 'none') {
                position = [
                    0,
                    0
                ];
            }
            for (var sIdx in mapSeries) {
                this._seriesIndexToMapType[sIdx] = mapType;
                this.markAttachStyle[sIdx] = {
                    position: position,
                    _mapType: mapType
                };
                this.buildMark(sIdx);
            }
        },
        getMarkCoord: function (seriesIndex, mpData) {
            return mpData.geoCoord || _geoCoord[mpData.name] ? this.geo2pos(this._seriesIndexToMapType[seriesIndex], mpData.geoCoord || _geoCoord[mpData.name]) : [
                0,
                0
            ];
        },
        getMarkGeo: function (mpData) {
            return mpData.geoCoord || _geoCoord[mpData.name];
        },
        _nameChange: function (mapType, name) {
            return this._nameMap[mapType][name] || name;
        },
        getLabelText: function (name, value, queryTarget, status) {
            var formatter = this.deepQuery(queryTarget, 'itemStyle.' + status + '.label.formatter');
            if (formatter) {
                if (typeof formatter == 'function') {
                    return formatter.call(this.myChart, name, value);
                } else if (typeof formatter == 'string') {
                    formatter = formatter.replace('{a}', '{a0}').replace('{b}', '{b0}');
                    formatter = formatter.replace('{a0}', name).replace('{b0}', value);
                    return formatter;
                }
            } else {
                return name;
            }
        },
        _findMapTypeByPos: function (mx, my) {
            var transform;
            var left;
            var top;
            var width;
            var height;
            for (var mapType in this._mapDataMap) {
                transform = this._mapDataMap[mapType].transform;
                if (!transform || !this._roamMap[mapType] || !this._activeMapType[mapType]) {
                    continue;
                }
                left = transform.left;
                top = transform.top;
                width = transform.width;
                height = transform.height;
                if (mx >= left && mx <= left + width && my >= top && my <= top + height) {
                    return mapType;
                }
            }
            return;
        },
        __onmousewheel: function (params) {
            if (this.shapeList.length <= 0) {
                return;
            }
            var event = params.event;
            var mx = zrEvent.getX(event);
            var my = zrEvent.getY(event);
            var delta;
            var eventDelta = zrEvent.getDelta(event);
            var mapType;
            var mapTypeControl = params.mapTypeControl;
            if (!mapTypeControl) {
                mapTypeControl = {};
                mapType = this._findMapTypeByPos(mx, my);
                if (mapType && this._roamMap[mapType] && this._roamMap[mapType] != 'move') {
                    mapTypeControl[mapType] = true;
                }
            }
            var haveScale = false;
            for (mapType in mapTypeControl) {
                if (mapTypeControl[mapType]) {
                    haveScale = true;
                    var transform = this._mapDataMap[mapType].transform;
                    var left = transform.left;
                    var top = transform.top;
                    var width = transform.width;
                    var height = transform.height;
                    var geoAndPos = this.pos2geo(mapType, [
                            mx - left,
                            my - top
                        ]);
                    if (eventDelta > 0) {
                        delta = 1.2;
                        if (this._scaleLimitMap[mapType].max != null && transform.baseScale >= this._scaleLimitMap[mapType].max) {
                            continue;
                        }
                    } else {
                        delta = 1 / 1.2;
                        if (this._scaleLimitMap[mapType].min != null && transform.baseScale <= this._scaleLimitMap[mapType].min) {
                            continue;
                        }
                    }
                    transform.baseScale *= delta;
                    transform.scale.x *= delta;
                    transform.scale.y *= delta;
                    transform.width = width * delta;
                    transform.height = height * delta;
                    this._mapDataMap[mapType].hasRoam = true;
                    this._mapDataMap[mapType].transform = transform;
                    geoAndPos = this.geo2pos(mapType, geoAndPos);
                    transform.left -= geoAndPos[0] - (mx - left);
                    transform.top -= geoAndPos[1] - (my - top);
                    this._mapDataMap[mapType].transform = transform;
                    this.clearEffectShape(true);
                    for (var i = 0, l = this.shapeList.length; i < l; i++) {
                        if (this.shapeList[i]._mapType == mapType) {
                            this.shapeList[i].position[0] = transform.left;
                            this.shapeList[i].position[1] = transform.top;
                            if (this.shapeList[i].type == 'path' || this.shapeList[i].type == 'symbol' || this.shapeList[i].type == 'circle' || this.shapeList[i].type == 'rectangle' || this.shapeList[i].type == 'polygon' || this.shapeList[i].type == 'line' || this.shapeList[i].type == 'ellipse') {
                                this.shapeList[i].scale[0] *= delta;
                                this.shapeList[i].scale[1] *= delta;
                            } else if (this.shapeList[i].type == 'mark-line') {
                                this.shapeList[i].style.pointListLength = undefined;
                                this.shapeList[i].style.pointList = false;
                                geoAndPos = this.geo2pos(mapType, this.shapeList[i]._geo[0]);
                                this.shapeList[i].style.xStart = geoAndPos[0];
                                this.shapeList[i].style.yStart = geoAndPos[1];
                                geoAndPos = this.geo2pos(mapType, this.shapeList[i]._geo[1]);
                                this.shapeList[i]._x = this.shapeList[i].style.xEnd = geoAndPos[0];
                                this.shapeList[i]._y = this.shapeList[i].style.yEnd = geoAndPos[1];
                            } else if (this.shapeList[i].type == 'icon') {
                                geoAndPos = this.geo2pos(mapType, this.shapeList[i]._geo);
                                this.shapeList[i].style.x = this.shapeList[i].style._x = geoAndPos[0] - this.shapeList[i].style.width / 2;
                                this.shapeList[i].style.y = this.shapeList[i].style._y = geoAndPos[1] - this.shapeList[i].style.height / 2;
                            } else {
                                geoAndPos = this.geo2pos(mapType, this.shapeList[i]._geo);
                                this.shapeList[i].style.x = geoAndPos[0];
                                this.shapeList[i].style.y = geoAndPos[1];
                                if (this.shapeList[i].type == 'text') {
                                    this.shapeList[i]._style.x = this.shapeList[i].highlightStyle.x = geoAndPos[0];
                                    this.shapeList[i]._style.y = this.shapeList[i].highlightStyle.y = geoAndPos[1];
                                }
                            }
                            this.zr.modShape(this.shapeList[i].id);
                        }
                    }
                }
            }
            if (haveScale) {
                zrEvent.stop(event);
                this.zr.refresh();
                var self = this;
                clearTimeout(this._refreshDelayTicket);
                this._refreshDelayTicket = setTimeout(function () {
                    self && self.shapeList && self.animationEffect();
                }, 100);
                this.messageCenter.dispatch(ecConfig.EVENT.MAP_ROAM, params.event, { type: 'scale' }, this.myChart);
            }
        },
        __onmousedown: function (params) {
            if (this.shapeList.length <= 0) {
                return;
            }
            var target = params.target;
            if (target && target.draggable) {
                return;
            }
            var event = params.event;
            var mx = zrEvent.getX(event);
            var my = zrEvent.getY(event);
            var mapType = this._findMapTypeByPos(mx, my);
            if (mapType && this._roamMap[mapType] && this._roamMap[mapType] != 'scale') {
                this._mousedown = true;
                this._mx = mx;
                this._my = my;
                this._curMapType = mapType;
                this.zr.on(zrConfig.EVENT.MOUSEUP, this._onmouseup);
                var self = this;
                setTimeout(function () {
                    self.zr.on(zrConfig.EVENT.MOUSEMOVE, self._onmousemove);
                }, 100);
            }
        },
        __onmousemove: function (params) {
            if (!this._mousedown || !this._isAlive) {
                return;
            }
            var event = params.event;
            var mx = zrEvent.getX(event);
            var my = zrEvent.getY(event);
            var transform = this._mapDataMap[this._curMapType].transform;
            transform.hasRoam = true;
            transform.left -= this._mx - mx;
            transform.top -= this._my - my;
            this._mx = mx;
            this._my = my;
            this._mapDataMap[this._curMapType].transform = transform;
            for (var i = 0, l = this.shapeList.length; i < l; i++) {
                if (this.shapeList[i]._mapType == this._curMapType) {
                    this.shapeList[i].position[0] = transform.left;
                    this.shapeList[i].position[1] = transform.top;
                    this.zr.modShape(this.shapeList[i].id);
                }
            }
            this.messageCenter.dispatch(ecConfig.EVENT.MAP_ROAM, params.event, { type: 'move' }, this.myChart);
            this.clearEffectShape(true);
            this.zr.refresh();
            this._justMove = true;
            zrEvent.stop(event);
        },
        __onmouseup: function (params) {
            var event = params.event;
            this._mx = zrEvent.getX(event);
            this._my = zrEvent.getY(event);
            this._mousedown = false;
            var self = this;
            setTimeout(function () {
                self._justMove && self.animationEffect();
                self._justMove = false;
                self.zr.un(zrConfig.EVENT.MOUSEMOVE, self._onmousemove);
                self.zr.un(zrConfig.EVENT.MOUSEUP, self._onmouseup);
            }, 120);
        },
        __onroamcontroller: function (params) {
            var event = params.event;
            event.zrenderX = this.zr.getWidth() / 2;
            event.zrenderY = this.zr.getHeight() / 2;
            var mapTypeControl = params.mapTypeControl;
            var top = 0;
            var left = 0;
            var step = params.step;
            switch (params.roamType) {
            case 'scaleUp':
                event.zrenderDelta = 1;
                this.__onmousewheel({
                    event: event,
                    mapTypeControl: mapTypeControl
                });
                return;
            case 'scaleDown':
                event.zrenderDelta = -1;
                this.__onmousewheel({
                    event: event,
                    mapTypeControl: mapTypeControl
                });
                return;
            case 'up':
                top = -step;
                break;
            case 'down':
                top = step;
                break;
            case 'left':
                left = -step;
                break;
            case 'right':
                left = step;
                break;
            }
            var transform;
            var curMapType;
            for (curMapType in mapTypeControl) {
                if (!this._mapDataMap[curMapType] || !this._activeMapType[curMapType]) {
                    continue;
                }
                transform = this._mapDataMap[curMapType].transform;
                transform.hasRoam = true;
                transform.left -= left;
                transform.top -= top;
                this._mapDataMap[curMapType].transform = transform;
            }
            for (var i = 0, l = this.shapeList.length; i < l; i++) {
                curMapType = this.shapeList[i]._mapType;
                if (!mapTypeControl[curMapType] || !this._activeMapType[curMapType]) {
                    continue;
                }
                transform = this._mapDataMap[curMapType].transform;
                this.shapeList[i].position[0] = transform.left;
                this.shapeList[i].position[1] = transform.top;
                this.zr.modShape(this.shapeList[i].id);
            }
            this.messageCenter.dispatch(ecConfig.EVENT.MAP_ROAM, params.event, { type: 'move' }, this.myChart);
            this.clearEffectShape(true);
            this.zr.refresh();
            clearTimeout(this.dircetionTimer);
            var self = this;
            this.dircetionTimer = setTimeout(function () {
                self.animationEffect();
            }, 150);
        },
        __ondrhoverlink: function (param) {
            var curMapType;
            var value;
            for (var i = 0, l = this.shapeList.length; i < l; i++) {
                curMapType = this.shapeList[i]._mapType;
                if (!this._hoverLinkMap[curMapType] || !this._activeMapType[curMapType]) {
                    continue;
                }
                value = ecData.get(this.shapeList[i], 'value');
                if (value != null && value >= param.valueMin && value <= param.valueMax) {
                    this.zr.addHoverShape(this.shapeList[i]);
                }
            }
        },
        onclick: function (params) {
            if (!this.isClick || !params.target || this._justMove || params.target.type == 'icon') {
                return;
            }
            this.isClick = false;
            var target = params.target;
            var name = target.style._name;
            var len = this.shapeList.length;
            var mapType = target._mapType || '';
            if (this._selectedMode[mapType] == 'single') {
                for (var p in this._selected) {
                    if (this._selected[p] && this._mapTypeMap[p] == mapType) {
                        for (var i = 0; i < len; i++) {
                            if (this.shapeList[i].style._name == p && this.shapeList[i]._mapType == mapType) {
                                this.shapeList[i].style = this.shapeList[i]._style;
                                this.zr.modShape(this.shapeList[i].id);
                            }
                        }
                        p != name && (this._selected[p] = false);
                    }
                }
            }
            this._selected[name] = !this._selected[name];
            for (var i = 0; i < len; i++) {
                if (this.shapeList[i].style._name == name && this.shapeList[i]._mapType == mapType) {
                    if (this._selected[name]) {
                        this.shapeList[i].style = this.shapeList[i].highlightStyle;
                    } else {
                        this.shapeList[i].style = this.shapeList[i]._style;
                    }
                    this.zr.modShape(this.shapeList[i].id);
                }
            }
            this.messageCenter.dispatch(ecConfig.EVENT.MAP_SELECTED, params.event, {
                selected: this._selected,
                target: name
            }, this.myChart);
            this.zr.refresh();
            var self = this;
            setTimeout(function () {
                self.zr.trigger(zrConfig.EVENT.MOUSEMOVE, params.event);
            }, 100);
        },
        refresh: function (newOption) {
            if (newOption) {
                this.option = newOption;
                this.series = newOption.series;
            }
            if (this._mapDataRequireCounter > 0) {
                this.clear();
            } else {
                this.backupShapeList();
            }
            this._buildShape();
            this.zr.refreshHover();
        },
        ondataRange: function (param, status) {
            if (this.component.dataRange) {
                this.refresh();
                status.needRefresh = true;
            }
            return;
        },
        pos2geo: function (mapType, p) {
            if (!this._mapDataMap[mapType].transform) {
                return null;
            }
            return this._mapDataMap[mapType].projection.pos2geo(this._mapDataMap[mapType].transform, p);
        },
        getGeoByPos: function (mapType, p) {
            if (!this._mapDataMap[mapType].transform) {
                return null;
            }
            var position = [
                    this._mapDataMap[mapType].transform.left,
                    this._mapDataMap[mapType].transform.top
                ];
            if (p instanceof Array) {
                p[0] -= position[0];
                p[1] -= position[1];
            } else {
                p.x -= position[0];
                p.y -= position[1];
            }
            return this.pos2geo(mapType, p);
        },
        geo2pos: function (mapType, p) {
            if (!this._mapDataMap[mapType].transform) {
                return null;
            }
            return this._mapDataMap[mapType].projection.geo2pos(this._mapDataMap[mapType].transform, p);
        },
        getPosByGeo: function (mapType, p) {
            if (!this._mapDataMap[mapType].transform) {
                return null;
            }
            var pos = this.geo2pos(mapType, p);
            pos[0] += this._mapDataMap[mapType].transform.left;
            pos[1] += this._mapDataMap[mapType].transform.top;
            return pos;
        },
        getMapPosition: function (mapType) {
            if (!this._mapDataMap[mapType].transform) {
                return null;
            }
            return [
                this._mapDataMap[mapType].transform.left,
                this._mapDataMap[mapType].transform.top
            ];
        },
        onbeforDispose: function () {
            this._isAlive = false;
            this.zr.un(zrConfig.EVENT.MOUSEWHEEL, this._onmousewheel);
            this.zr.un(zrConfig.EVENT.MOUSEDOWN, this._onmousedown);
            this.messageCenter.unbind(ecConfig.EVENT.ROAMCONTROLLER, this._onroamcontroller);
            this.messageCenter.unbind(ecConfig.EVENT.DATA_RANGE_HOVERLINK, this._ondrhoverlink);
        }
    };
    zrUtil.inherits(Map, ChartBase);
    zrUtil.inherits(Map, ComponentBase);
    require('../chart').define('map', Map);
    return Map;
});