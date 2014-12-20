/*! 2014 Baidu Inc. All Rights Reserved */
define('echarts/chart/force', function (require) {
    'use strict';
    var ComponentBase = require('../component/base');
    var ChartBase = require('./base');
    var Graph = require('../data/Graph');
    var ForceLayout = require('../layout/Force');
    var LineShape = require('zrender/shape/Line');
    var BezierCurveShape = require('zrender/shape/BezierCurve');
    var ImageShape = require('zrender/shape/Image');
    var IconShape = require('../util/shape/Icon');
    var ecConfig = require('../config');
    var ecData = require('../util/ecData');
    var zrUtil = require('zrender/tool/util');
    var zrConfig = require('zrender/config');
    var vec2 = require('zrender/tool/vector');
    function Force(ecTheme, messageCenter, zr, option, myChart) {
        var self = this;
        ComponentBase.call(this, ecTheme, messageCenter, zr, option, myChart);
        ChartBase.call(this);
        this.__nodePositionMap = {};
        this._graph = new Graph(true);
        this._layout = new ForceLayout();
        this._layout.onupdate = function () {
            self._step();
        };
        this._steps = 1;
        this.ondragstart = function () {
            ondragstart.apply(self, arguments);
        };
        this.ondragend = function () {
            ondragend.apply(self, arguments);
        };
        this.ondrop = function () {
        };
        this.shapeHandler.ondragstart = function () {
            self.isDragstart = true;
        };
        this.onmousemove = function () {
            onmousemove.apply(self, arguments);
        };
        this.refresh(option);
    }
    Force.prototype = {
        constructor: Force,
        type: ecConfig.CHART_TYPE_FORCE,
        _init: function () {
            var legend = this.component.legend;
            var series = this.series;
            var serieName;
            this.clear();
            for (var i = 0, l = series.length; i < l; i++) {
                var serie = series[i];
                if (serie.type === ecConfig.CHART_TYPE_FORCE) {
                    series[i] = this.reformOption(series[i]);
                    serieName = series[i].name || '';
                    this.selectedMap[serieName] = legend ? legend.isSelected(serieName) : true;
                    if (!this.selectedMap[serieName]) {
                        continue;
                    }
                    this.buildMark(i);
                    this._initSerie(serie, i);
                    break;
                }
            }
        },
        _getNodeCategory: function (serie, node) {
            return serie.categories && serie.categories[node.category || 0];
        },
        _getNodeQueryTarget: function (serie, node, type) {
            type = type || 'normal';
            var category = this._getNodeCategory(serie, node) || {};
            return [
                node.itemStyle && node.itemStyle[type],
                category && category.itemStyle && category.itemStyle[type],
                serie.itemStyle[type].nodeStyle
            ];
        },
        _getEdgeQueryTarget: function (serie, edge, type) {
            type = type || 'normal';
            return [
                edge.itemStyle && edge.itemStyle[type],
                serie.itemStyle[type].linkStyle
            ];
        },
        _initSerie: function (serie, serieIdx) {
            this._temperature = 1;
            if (serie.data) {
                this._graph = this._getSerieGraphFromDataMatrix(serie);
            } else {
                this._graph = this._getSerieGraphFromNodeLinks(serie);
            }
            this._buildLinkShapes(serie, serieIdx);
            this._buildNodeShapes(serie, serieIdx);
            this.zr.modLayer(this.getZlevelBase(), {
                panable: serie.roam === true || serie.roam === 'move',
                zoomable: serie.roam === true || serie.roam === 'scale'
            });
            this._initLayout(serie);
            this._step();
        },
        _getSerieGraphFromDataMatrix: function (serie) {
            var nodesData = [];
            var count = 0;
            var matrix = [];
            for (var i = 0; i < serie.matrix.length; i++) {
                matrix[i] = serie.matrix[i].slice();
            }
            var data = serie.data || serie.nodes;
            for (var i = 0; i < data.length; i++) {
                var node = {};
                var group = data[i];
                for (var key in group) {
                    if (key === 'name') {
                        node['id'] = group['name'];
                    } else {
                        node[key] = group[key];
                    }
                }
                var category = this._getNodeCategory(serie, group);
                var name = category ? category.name : group.name;
                this.selectedMap[name] = this.isSelected(name);
                if (this.selectedMap[name]) {
                    nodesData.push(node);
                    count++;
                } else {
                    matrix.splice(count, 1);
                    for (var j = 0; j < matrix.length; j++) {
                        matrix[j].splice(count, 1);
                    }
                }
            }
            var graph = Graph.fromMatrix(nodesData, matrix, true);
            graph.eachNode(function (n, idx) {
                n.layout = {
                    size: n.data.value,
                    mass: 0
                };
                n.rawIndex = idx;
            });
            graph.eachEdge(function (e) {
                e.layout = { weight: e.data.weight };
            });
            return graph;
        },
        _getSerieGraphFromNodeLinks: function (serie) {
            var graph = new Graph(true);
            var nodes = serie.data || serie.nodes;
            for (var i = 0, len = nodes.length; i < len; i++) {
                var n = nodes[i];
                if (!n || n.ignore) {
                    continue;
                }
                var category = this._getNodeCategory(serie, n);
                var name = category ? category.name : n.name;
                this.selectedMap[name] = this.isSelected(name);
                if (this.selectedMap[name]) {
                    var node = graph.addNode(n.name, n);
                    node.rawIndex = i;
                }
            }
            for (var i = 0, len = serie.links.length; i < len; i++) {
                var e = serie.links[i];
                var n1 = e.source;
                var n2 = e.target;
                if (typeof n1 === 'number') {
                    n1 = nodes[n1];
                    if (n1) {
                        n1 = n1.name;
                    }
                }
                if (typeof n2 === 'number') {
                    n2 = nodes[n2];
                    if (n2) {
                        n2 = n2.name;
                    }
                }
                var edge = graph.addEdge(n1, n2, e);
                if (edge) {
                    edge.rawIndex = i;
                }
            }
            graph.eachNode(function (n) {
                var value = n.data.value;
                if (value == null) {
                    value = 0;
                    for (var i = 0; i < n.edges.length; i++) {
                        value += n.edges[i].data.weight || 0;
                    }
                }
                n.layout = {
                    size: value,
                    mass: 0
                };
            });
            graph.eachEdge(function (e) {
                e.layout = { weight: e.data.weight == null ? 1 : e.data.weight };
            });
            return graph;
        },
        _initLayout: function (serie) {
            var graph = this._graph;
            var len = graph.nodes.length;
            var minRadius = this.query(serie, 'minRadius');
            var maxRadius = this.query(serie, 'maxRadius');
            this._steps = serie.steps || 1;
            this._layout.center = this.parseCenter(this.zr, serie.center);
            this._layout.width = this.parsePercent(serie.size, this.zr.getWidth());
            this._layout.height = this.parsePercent(serie.size, this.zr.getHeight());
            this._layout.large = serie.large;
            this._layout.scaling = serie.scaling;
            this._layout.ratioScaling = serie.ratioScaling;
            this._layout.gravity = serie.gravity;
            this._layout.temperature = 1;
            this._layout.coolDown = serie.coolDown;
            this._layout.preventNodeEdgeOverlap = serie.preventOverlap;
            this._layout.preventNodeOverlap = serie.preventOverlap;
            var min = Infinity;
            var max = -Infinity;
            for (var i = 0; i < len; i++) {
                var gNode = graph.nodes[i];
                max = Math.max(gNode.layout.size, max);
                min = Math.min(gNode.layout.size, min);
            }
            var divider = max - min;
            for (var i = 0; i < len; i++) {
                var gNode = graph.nodes[i];
                if (divider > 0) {
                    gNode.layout.size = (gNode.layout.size - min) * (maxRadius - minRadius) / divider + minRadius;
                    gNode.layout.mass = gNode.layout.size / maxRadius;
                } else {
                    gNode.layout.size = (maxRadius - minRadius) / 2;
                    gNode.layout.mass = 0.5;
                }
            }
            for (var i = 0; i < len; i++) {
                var gNode = graph.nodes[i];
                if (typeof this.__nodePositionMap[gNode.id] !== 'undefined') {
                    gNode.layout.position = vec2.create();
                    vec2.copy(gNode.layout.position, this.__nodePositionMap[gNode.id]);
                } else if (typeof gNode.data.initial !== 'undefined') {
                    gNode.layout.position = vec2.create();
                    vec2.copy(gNode.layout.position, gNode.data.initial);
                } else {
                    var center = this._layout.center;
                    var size = Math.min(this._layout.width, this._layout.height);
                    gNode.layout.position = _randomInSquare(center[0], center[1], size * 0.8);
                }
                var style = gNode.shape.style;
                var radius = gNode.layout.size;
                style.width = style.width || radius * 2;
                style.height = style.height || radius * 2;
                style.x = -style.width / 2;
                style.y = -style.height / 2;
                vec2.copy(gNode.shape.position, gNode.layout.position);
            }
            len = graph.edges.length;
            max = -Infinity;
            for (var i = 0; i < len; i++) {
                var e = graph.edges[i];
                if (e.layout.weight > max) {
                    max = e.layout.weight;
                }
            }
            for (var i = 0; i < len; i++) {
                var e = graph.edges[i];
                e.layout.weight /= max;
            }
            this._layout.init(graph, serie.useWorker);
        },
        _buildNodeShapes: function (serie, serieIdx) {
            var graph = this._graph;
            var categories = this.query(serie, 'categories');
            graph.eachNode(function (node) {
                var category = this._getNodeCategory(serie, node.data);
                var queryTarget = [
                        node.data,
                        category,
                        serie
                    ];
                var styleQueryTarget = this._getNodeQueryTarget(serie, node.data);
                var emphasisStyleQueryTarget = this._getNodeQueryTarget(serie, node.data, 'emphasis');
                var shape = new IconShape({
                        style: {
                            x: 0,
                            y: 0,
                            color: this.deepQuery(styleQueryTarget, 'color'),
                            brushType: 'both',
                            strokeColor: this.deepQuery(styleQueryTarget, 'strokeColor') || this.deepQuery(styleQueryTarget, 'borderColor'),
                            lineWidth: this.deepQuery(styleQueryTarget, 'lineWidth') || this.deepQuery(styleQueryTarget, 'borderWidth')
                        },
                        highlightStyle: {
                            color: this.deepQuery(emphasisStyleQueryTarget, 'color', 'emphasis'),
                            strokeColor: this.deepQuery(emphasisStyleQueryTarget, 'strokeColor', 'emphasis') || this.deepQuery(emphasisStyleQueryTarget, 'borderColor', 'emphasis'),
                            lineWidth: this.deepQuery(emphasisStyleQueryTarget, 'lineWidth', 'emphasis') || this.deepQuery(emphasisStyleQueryTarget, 'borderWidth', 'emphasis')
                        },
                        clickable: serie.clickable,
                        zlevel: this.getZlevelBase()
                    });
                if (!shape.style.color) {
                    shape.style.color = category ? this.getColor(category.name) : this.getColor(node.id);
                }
                shape.style.iconType = this.deepQuery(queryTarget, 'symbol');
                shape.style.width = shape.style.height = (this.deepQuery(queryTarget, 'symbolSize') || 0) * 2;
                if (shape.style.iconType.match('image')) {
                    shape.style.image = shape.style.iconType.replace(new RegExp('^image:\\/\\/'), '');
                    shape = new ImageShape({
                        style: shape.style,
                        highlightStyle: shape.highlightStyle,
                        clickable: shape.clickable
                    });
                }
                if (this.deepQuery(queryTarget, 'itemStyle.normal.label.show')) {
                    shape.style.text = node.data.label == null ? node.id : node.data.label;
                    shape.style.textPosition = this.deepQuery(queryTarget, 'itemStyle.normal.label.position');
                    shape.style.textColor = this.deepQuery(queryTarget, 'itemStyle.normal.label.textStyle.color');
                    shape.style.textFont = this.getFont(this.deepQuery(queryTarget, 'itemStyle.normal.label.textStyle') || {});
                }
                if (this.deepQuery(queryTarget, 'itemStyle.emphasis.label.show')) {
                    shape.highlightStyle.textPosition = this.deepQuery(queryTarget, 'itemStyle.emphasis.label.position');
                    shape.highlightStyle.textColor = this.deepQuery(queryTarget, 'itemStyle.emphasis.label.textStyle.color');
                    shape.highlightStyle.textFont = this.getFont(this.deepQuery(queryTarget, 'itemStyle.emphasis.label.textStyle') || {});
                }
                if (this.deepQuery(queryTarget, 'draggable')) {
                    this.setCalculable(shape);
                    shape.dragEnableTime = 0;
                    shape.draggable = true;
                    shape.ondragstart = this.shapeHandler.ondragstart;
                    shape.ondragover = null;
                }
                var categoryName = '';
                if (typeof node.category !== 'undefined') {
                    var category = categories[node.category];
                    categoryName = category && category.name || '';
                }
                ecData.pack(shape, serie, serieIdx, node.data, node.rawIndex, node.data.name || '', node.category);
                this.shapeList.push(shape);
                this.zr.addShape(shape);
                node.shape = shape;
            }, this);
        },
        _buildLinkShapes: function (serie, serieIdx) {
            var graph = this._graph;
            var len = graph.edges.length;
            for (var i = 0; i < len; i++) {
                var gEdge = graph.edges[i];
                var link = gEdge.data;
                var source = gEdge.node1;
                var target = gEdge.node2;
                var queryTarget = this._getEdgeQueryTarget(serie, gEdge);
                var linkType = this.deepQuery(queryTarget, 'type');
                if (serie.linkSymbol && serie.linkSymbol !== 'none') {
                    linkType = 'line';
                }
                var LinkShapeCtor = linkType === 'line' ? LineShape : BezierCurveShape;
                var linkShape = new LinkShapeCtor({
                        style: {
                            xStart: 0,
                            yStart: 0,
                            xEnd: 0,
                            yEnd: 0,
                            lineWidth: 1
                        },
                        clickable: this.query(serie, 'clickable'),
                        highlightStyle: {},
                        zlevel: this.getZlevelBase()
                    });
                zrUtil.merge(linkShape.style, this.query(serie, 'itemStyle.normal.linkStyle'), true);
                zrUtil.merge(linkShape.highlightStyle, this.query(serie, 'itemStyle.emphasis.linkStyle'), true);
                if (typeof link.itemStyle !== 'undefined') {
                    if (link.itemStyle.normal) {
                        zrUtil.merge(linkShape.style, link.itemStyle.normal, true);
                    }
                    if (link.itemStyle.emphasis) {
                        zrUtil.merge(linkShape.highlightStyle, link.itemStyle.emphasis, true);
                    }
                }
                linkShape.style.lineWidth = linkShape.style.lineWidth || linkShape.style.width;
                linkShape.style.strokeColor = linkShape.style.strokeColor || linkShape.style.color;
                linkShape.highlightStyle.lineWidth = linkShape.highlightStyle.lineWidth || linkShape.highlightStyle.width;
                linkShape.highlightStyle.strokeColor = linkShape.highlightStyle.strokeColor || linkShape.highlightStyle.color;
                ecData.pack(linkShape, serie, serieIdx, gEdge.data, gEdge.rawIndex == null ? i : gEdge.rawIndex, gEdge.data.name || source.id + ' - ' + target.id, source.id, target.id);
                this.shapeList.push(linkShape);
                this.zr.addShape(linkShape);
                gEdge.shape = linkShape;
                if (serie.linkSymbol && serie.linkSymbol !== 'none') {
                    var symbolShape = new IconShape({
                            style: {
                                x: -5,
                                y: 0,
                                width: serie.linkSymbolSize[0],
                                height: serie.linkSymbolSize[1],
                                iconType: serie.linkSymbol,
                                brushType: 'fill',
                                color: linkShape.style.strokeColor,
                                opacity: linkShape.style.opacity,
                                shadowBlur: linkShape.style.shadowBlur,
                                shadowColor: linkShape.style.shadowColor,
                                shadowOffsetX: linkShape.style.shadowOffsetX,
                                shadowOffsetY: linkShape.style.shadowOffsetY
                            },
                            highlightStyle: { brushType: 'fill' },
                            position: [
                                0,
                                0
                            ],
                            rotation: 0
                        });
                    linkShape._symbolShape = symbolShape;
                    this.shapeList.push(symbolShape);
                    this.zr.addShape(symbolShape);
                }
            }
        },
        _updateLinkShapes: function () {
            var v = vec2.create();
            var edges = this._graph.edges;
            for (var i = 0, len = edges.length; i < len; i++) {
                var edge = edges[i];
                var sourceShape = edge.node1.shape;
                var targetShape = edge.node2.shape;
                var p1 = sourceShape.position;
                var p2 = targetShape.position;
                edge.shape.style.xStart = p1[0];
                edge.shape.style.yStart = p1[1];
                edge.shape.style.xEnd = p2[0];
                edge.shape.style.yEnd = p2[1];
                if (edge.shape.type === 'bezier-curve') {
                    edge.shape.style.cpX1 = (p1[0] + p2[0]) / 2 - (p2[1] - p1[1]) / 4;
                    edge.shape.style.cpY1 = (p1[1] + p2[1]) / 2 - (p1[0] - p2[0]) / 4;
                }
                edge.shape.modSelf();
                if (edge.shape._symbolShape) {
                    var symbolShape = edge.shape._symbolShape;
                    vec2.copy(symbolShape.position, targetShape.position);
                    vec2.sub(v, sourceShape.position, targetShape.position);
                    vec2.normalize(v, v);
                    vec2.scaleAndAdd(symbolShape.position, symbolShape.position, v, targetShape.style.width / 2 + 2);
                    var angle = Math.atan2(v[1], v[0]);
                    symbolShape.rotation = Math.PI / 2 - angle;
                    symbolShape.modSelf();
                }
            }
        },
        _syncNodePositions: function () {
            var graph = this._graph;
            for (var i = 0; i < graph.nodes.length; i++) {
                var gNode = graph.nodes[i];
                var position = gNode.layout.position;
                var node = gNode.data;
                var shape = gNode.shape;
                if (shape.fixed || node.fixX && node.fixY) {
                    vec2.copy(position, shape.position);
                } else if (node.fixX) {
                    position[0] = shape.position[0];
                    shape.position[1] = position[1];
                } else if (node.fixY) {
                    position[1] = shape.position[1];
                    shape.position[0] = position[0];
                } else if (isNaN(node.fixX - 0) == false && isNaN(node.fixY - 0) == false) {
                    shape.position[0] += (position[0] - shape.position[0]) * node.fixX;
                    position[0] = shape.position[0];
                    shape.position[1] += (position[1] - shape.position[1]) * node.fixY;
                    position[1] = shape.position[1];
                } else if (isNaN(node.fixX - 0) == false) {
                    shape.position[0] += (position[0] - shape.position[0]) * node.fixX;
                    position[0] = shape.position[0];
                    shape.position[1] = position[1];
                } else if (isNaN(node.fixY - 0) == false) {
                    shape.position[1] += (position[1] - shape.position[1]) * node.fixY;
                    position[1] = shape.position[1];
                    shape.position[0] = position[0];
                } else {
                    vec2.copy(shape.position, position);
                }
                var nodeName = node.name;
                if (nodeName) {
                    var gPos = this.__nodePositionMap[nodeName];
                    if (!gPos) {
                        gPos = this.__nodePositionMap[nodeName] = vec2.create();
                    }
                    vec2.copy(gPos, position);
                }
                shape.modSelf();
            }
        },
        _step: function (e) {
            this._syncNodePositions();
            this._updateLinkShapes();
            this.zr.refreshNextFrame();
            if (this._layout.temperature > 0.01) {
                this._layout.step(this._steps);
            } else {
                this.messageCenter.dispatch(ecConfig.EVENT.FORCE_LAYOUT_END, {}, {}, this.myChart);
            }
        },
        refresh: function (newOption) {
            if (newOption) {
                this.option = newOption;
                this.series = this.option.series;
            }
            this.legend = this.component.legend;
            if (this.legend) {
                this.getColor = function (param) {
                    return this.legend.getColor(param);
                };
                this.isSelected = function (param) {
                    return this.legend.isSelected(param);
                };
            } else {
                var colorMap = {};
                var count = 0;
                this.getColor = function (key) {
                    if (colorMap[key]) {
                        return colorMap[key];
                    }
                    if (!colorMap[key]) {
                        colorMap[key] = this.zr.getColor(count++);
                    }
                    return colorMap[key];
                };
                this.isSelected = function () {
                    return true;
                };
            }
            this._init();
        },
        dispose: function () {
            this.clear();
            this.shapeList = null;
            this.effectList = null;
            this._layout.dispose();
            this._layout = null;
            this.__nodePositionMap = {};
        },
        getPosition: function () {
            var position = [];
            this._graph.eachNode(function (n) {
                if (n.layout) {
                    position.push({
                        name: n.data.name,
                        position: Array.prototype.slice.call(n.layout.position)
                    });
                }
            });
            return position;
        }
    };
    function ondragstart(param) {
        if (!this.isDragstart || !param.target) {
            return;
        }
        var shape = param.target;
        shape.fixed = true;
        this.isDragstart = false;
        this.zr.on(zrConfig.EVENT.MOUSEMOVE, this.onmousemove);
    }
    function onmousemove() {
        this._layout.temperature = 0.8;
        this._step();
    }
    function ondragend(param, status) {
        if (!this.isDragend || !param.target) {
            return;
        }
        var shape = param.target;
        shape.fixed = false;
        status.dragIn = true;
        status.needRefresh = false;
        this.isDragend = false;
        this.zr.un(zrConfig.EVENT.MOUSEMOVE, this.onmousemove);
    }
    function _randomInSquare(x, y, size) {
        var v = vec2.create();
        v[0] = (Math.random() - 0.5) * size + x;
        v[1] = (Math.random() - 0.5) * size + y;
        return v;
    }
    zrUtil.inherits(Force, ChartBase);
    zrUtil.inherits(Force, ComponentBase);
    require('../chart').define('force', Force);
    return Force;
});