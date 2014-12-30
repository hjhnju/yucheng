define('echarts/echarts', function (require) {
    var ecConfig = require('./config');
    var zrUtil = require('zrender/tool/util');
    var zrEvent = require('zrender/tool/event');
    var self = {};
    var _canvasSupported = require('zrender/tool/env').canvasSupported;
    var _idBase = new Date() - 0;
    var _instances = {};
    var DOM_ATTRIBUTE_KEY = '_echarts_instance_';
    self.version = '2.1.9';
    self.dependencies = { zrender: '2.0.6' };
    self.init = function (dom, theme) {
        var zrender = require('zrender');
        if ((zrender.version || '1.0.3').replace('.', '') - 0 < self.dependencies.zrender.replace('.', '') - 0) {
            console.error('ZRender ' + (zrender.version || '1.0.3-') + ' is too old for ECharts ' + self.version + '. Current version need ZRender ' + self.dependencies.zrender + '+');
        }
        dom = dom instanceof Array ? dom[0] : dom;
        var key = dom.getAttribute(DOM_ATTRIBUTE_KEY);
        if (!key) {
            key = _idBase++;
            dom.setAttribute(DOM_ATTRIBUTE_KEY, key);
        }
        if (_instances[key]) {
            _instances[key].dispose();
        }
        _instances[key] = new Echarts(dom);
        _instances[key].id = key;
        _instances[key].canvasSupported = _canvasSupported;
        _instances[key].setTheme(theme);
        return _instances[key];
    };
    self.getInstanceById = function (key) {
        return _instances[key];
    };
    function MessageCenter() {
        zrEvent.Dispatcher.call(this);
    }
    zrUtil.merge(MessageCenter.prototype, zrEvent.Dispatcher.prototype, true);
    function Echarts(dom) {
        this._themeConfig = zrUtil.clone(ecConfig);
        this.dom = dom;
        this._connected = false;
        this._status = {
            dragIn: false,
            dragOut: false,
            needRefresh: false
        };
        this._curEventType = false;
        this._chartList = [];
        this._messageCenter = new MessageCenter();
        this._messageCenterOutSide = new MessageCenter();
        this.resize = this.resize();
        this._init();
    }
    var ZR_EVENT = require('zrender/config').EVENT;
    var ZR_EVENT_LISTENS = [
            'CLICK',
            'DBLCLICK',
            'MOUSEOVER',
            'MOUSEOUT',
            'DRAGSTART',
            'DRAGEND',
            'DRAGENTER',
            'DRAGOVER',
            'DRAGLEAVE',
            'DROP'
        ];
    function callChartListMethodReverse(ecInstance, methodName, arg0, arg1, arg2) {
        var chartList = ecInstance._chartList;
        var len = chartList.length;
        while (len--) {
            var chart = chartList[len];
            if (typeof chart[methodName] === 'function') {
                chart[methodName](arg0, arg1, arg2);
            }
        }
    }
    Echarts.prototype = {
        _init: function () {
            var self = this;
            var _zr = require('zrender').init(this.dom);
            this._zr = _zr;
            this._messageCenter.dispatch = function (type, event, eventPackage, that) {
                eventPackage = eventPackage || {};
                eventPackage.type = type;
                eventPackage.event = event;
                self._messageCenter.dispatchWithContext(type, eventPackage, that);
                if (type != 'HOVER' && type != 'MOUSEOUT') {
                    setTimeout(function () {
                        self._messageCenterOutSide.dispatchWithContext(type, eventPackage, that);
                    }, 50);
                } else {
                    self._messageCenterOutSide.dispatchWithContext(type, eventPackage, that);
                }
            };
            this._onevent = function (param) {
                return self.__onevent(param);
            };
            for (var e in ecConfig.EVENT) {
                if (e != 'CLICK' && e != 'DBLCLICK' && e != 'HOVER' && e != 'MOUSEOUT' && e != 'MAP_ROAM') {
                    this._messageCenter.bind(ecConfig.EVENT[e], this._onevent, this);
                }
            }
            var eventBehaviors = {};
            this._onzrevent = function (param) {
                return self[eventBehaviors[param.type]](param);
            };
            for (var i = 0, len = ZR_EVENT_LISTENS.length; i < len; i++) {
                var eventName = ZR_EVENT_LISTENS[i];
                var eventValue = ZR_EVENT[eventName];
                eventBehaviors[eventValue] = '_on' + eventName.toLowerCase();
                _zr.on(eventValue, this._onzrevent);
            }
            this.chart = {};
            this.component = {};
            var Island = require('./chart/island');
            this._island = new Island(this._themeConfig, this._messageCenter, _zr, {}, this);
            this.chart.island = this._island;
            var Toolbox = require('./component/toolbox');
            this._toolbox = new Toolbox(this._themeConfig, this._messageCenter, _zr, {}, this);
            this.component.toolbox = this._toolbox;
            var componentLibrary = require('./component');
            componentLibrary.define('title', require('./component/title'));
            componentLibrary.define('tooltip', require('./component/tooltip'));
            componentLibrary.define('legend', require('./component/legend'));
            if (_zr.getWidth() === 0 || _zr.getHeight() === 0) {
                console.error('Dom\u2019s width & height should be ready before init.');
            }
        },
        __onevent: function (param) {
            param.__echartsId = param.__echartsId || this.id;
            var fromMyself = param.__echartsId === this.id;
            if (!this._curEventType) {
                this._curEventType = param.type;
            }
            switch (param.type) {
            case ecConfig.EVENT.LEGEND_SELECTED:
                this._onlegendSelected(param);
                break;
            case ecConfig.EVENT.DATA_ZOOM:
                if (!fromMyself) {
                    var dz = this.component.dataZoom;
                    if (dz) {
                        dz.silence(true);
                        dz.absoluteZoom(param.zoom);
                        dz.silence(false);
                    }
                }
                this._ondataZoom(param);
                break;
            case ecConfig.EVENT.DATA_RANGE:
                fromMyself && this._ondataRange(param);
                break;
            case ecConfig.EVENT.MAGIC_TYPE_CHANGED:
                if (!fromMyself) {
                    var tb = this.component.toolbox;
                    if (tb) {
                        tb.silence(true);
                        tb.setMagicType(param.magicType);
                        tb.silence(false);
                    }
                }
                this._onmagicTypeChanged(param);
                break;
            case ecConfig.EVENT.DATA_VIEW_CHANGED:
                fromMyself && this._ondataViewChanged(param);
                break;
            case ecConfig.EVENT.TOOLTIP_HOVER:
                fromMyself && this._tooltipHover(param);
                break;
            case ecConfig.EVENT.RESTORE:
                this._onrestore();
                break;
            case ecConfig.EVENT.REFRESH:
                fromMyself && this._onrefresh(param);
                break;
            case ecConfig.EVENT.TOOLTIP_IN_GRID:
            case ecConfig.EVENT.TOOLTIP_OUT_GRID:
                if (!fromMyself) {
                    var grid = this.component.grid;
                    if (grid) {
                        this._zr.trigger('mousemove', {
                            connectTrigger: true,
                            zrenderX: grid.getX() + param.x * grid.getWidth(),
                            zrenderY: grid.getY() + param.y * grid.getHeight()
                        });
                    }
                } else if (this._connected) {
                    var grid = this.component.grid;
                    if (grid) {
                        param.x = (param.event.zrenderX - grid.getX()) / grid.getWidth();
                        param.y = (param.event.zrenderY - grid.getY()) / grid.getHeight();
                    }
                }
                break;
            }
            if (this._connected && fromMyself && this._curEventType === param.type) {
                for (var c in this._connected) {
                    this._connected[c].connectedEventHandler(param);
                }
                this._curEventType = null;
            }
            if (!fromMyself || !this._connected && fromMyself) {
                this._curEventType = null;
            }
        },
        _onclick: function (param) {
            callChartListMethodReverse(this, 'onclick', param);
            if (param.target) {
                var ecData = this._eventPackage(param.target);
                if (ecData && ecData.seriesIndex != null) {
                    this._messageCenter.dispatch(ecConfig.EVENT.CLICK, param.event, ecData, this);
                }
            }
        },
        _ondblclick: function (param) {
            callChartListMethodReverse(this, 'ondblclick', param);
            if (param.target) {
                var ecData = this._eventPackage(param.target);
                if (ecData && ecData.seriesIndex != null) {
                    this._messageCenter.dispatch(ecConfig.EVENT.DBLCLICK, param.event, ecData, this);
                }
            }
        },
        _onmouseover: function (param) {
            if (param.target) {
                var ecData = this._eventPackage(param.target);
                if (ecData && ecData.seriesIndex != null) {
                    this._messageCenter.dispatch(ecConfig.EVENT.HOVER, param.event, ecData, this);
                }
            }
        },
        _onmouseout: function (param) {
            if (param.target) {
                var ecData = this._eventPackage(param.target);
                if (ecData && ecData.seriesIndex != null) {
                    this._messageCenter.dispatch(ecConfig.EVENT.MOUSEOUT, param.event, ecData, this);
                }
            }
        },
        _ondragstart: function (param) {
            this._status = {
                dragIn: false,
                dragOut: false,
                needRefresh: false
            };
            callChartListMethodReverse(this, 'ondragstart', param);
        },
        _ondragenter: function (param) {
            callChartListMethodReverse(this, 'ondragenter', param);
        },
        _ondragover: function (param) {
            callChartListMethodReverse(this, 'ondragover', param);
        },
        _ondragleave: function (param) {
            callChartListMethodReverse(this, 'ondragleave', param);
        },
        _ondrop: function (param) {
            callChartListMethodReverse(this, 'ondrop', param, this._status);
            this._island.ondrop(param, this._status);
        },
        _ondragend: function (param) {
            callChartListMethodReverse(this, 'ondragend', param, this._status);
            this._timeline && this._timeline.ondragend(param, this._status);
            this._island.ondragend(param, this._status);
            if (this._status.needRefresh) {
                this._syncBackupData(this._option);
                var messageCenter = this._messageCenter;
                messageCenter.dispatch(ecConfig.EVENT.DATA_CHANGED, param.event, this._eventPackage(param.target), this);
                messageCenter.dispatch(ecConfig.EVENT.REFRESH, null, null, this);
            }
        },
        _onlegendSelected: function (param) {
            this._status.needRefresh = false;
            callChartListMethodReverse(this, 'onlegendSelected', param, this._status);
            if (this._status.needRefresh) {
                this._messageCenter.dispatch(ecConfig.EVENT.REFRESH, null, null, this);
            }
        },
        _ondataZoom: function (param) {
            this._status.needRefresh = false;
            callChartListMethodReverse(this, 'ondataZoom', param, this._status);
            if (this._status.needRefresh) {
                this._messageCenter.dispatch(ecConfig.EVENT.REFRESH, null, null, this);
            }
        },
        _ondataRange: function (param) {
            this._clearEffect();
            this._status.needRefresh = false;
            callChartListMethodReverse(this, 'ondataRange', param, this._status);
            if (this._status.needRefresh) {
                this._zr.refresh();
            }
        },
        _onmagicTypeChanged: function () {
            this._clearEffect();
            this._render(this._toolbox.getMagicOption());
        },
        _ondataViewChanged: function (param) {
            this._syncBackupData(param.option);
            this._messageCenter.dispatch(ecConfig.EVENT.DATA_CHANGED, null, param, this);
            this._messageCenter.dispatch(ecConfig.EVENT.REFRESH, null, null, this);
        },
        _tooltipHover: function (param) {
            var tipShape = [];
            callChartListMethodReverse(this, 'ontooltipHover', param, tipShape);
        },
        _onrestore: function () {
            this.restore();
        },
        _onrefresh: function (param) {
            this._refreshInside = true;
            this.refresh(param);
            this._refreshInside = false;
        },
        _syncBackupData: function (curOption) {
            this.component.dataZoom && this.component.dataZoom.syncBackupData(curOption);
        },
        _eventPackage: function (target) {
            if (target) {
                var ecData = require('./util/ecData');
                var seriesIndex = ecData.get(target, 'seriesIndex');
                var dataIndex = ecData.get(target, 'dataIndex');
                dataIndex = seriesIndex != -1 && this.component.dataZoom ? this.component.dataZoom.getRealDataIndex(seriesIndex, dataIndex) : dataIndex;
                return {
                    seriesIndex: seriesIndex,
                    seriesName: (ecData.get(target, 'series') || {}).name,
                    dataIndex: dataIndex,
                    data: ecData.get(target, 'data'),
                    name: ecData.get(target, 'name'),
                    value: ecData.get(target, 'value'),
                    special: ecData.get(target, 'special')
                };
            }
            return;
        },
        _render: function (magicOption) {
            this._mergeGlobalConifg(magicOption);
            var bgColor = magicOption.backgroundColor;
            if (bgColor) {
                if (!_canvasSupported && bgColor.indexOf('rgba') != -1) {
                    var cList = bgColor.split(',');
                    this.dom.style.filter = 'alpha(opacity=' + cList[3].substring(0, cList[3].lastIndexOf(')')) * 100 + ')';
                    cList.length = 3;
                    cList[0] = cList[0].replace('a', '');
                    this.dom.style.backgroundColor = cList.join(',') + ')';
                } else {
                    this.dom.style.backgroundColor = bgColor;
                }
            }
            this._zr.clearAnimation();
            this._chartList = [];
            var chartLibrary = require('./chart');
            var componentLibrary = require('./component');
            if (magicOption.xAxis || magicOption.yAxis) {
                magicOption.grid = magicOption.grid || {};
                magicOption.dataZoom = magicOption.dataZoom || {};
            }
            var componentList = [
                    'title',
                    'legend',
                    'tooltip',
                    'dataRange',
                    'roamController',
                    'grid',
                    'dataZoom',
                    'xAxis',
                    'yAxis',
                    'polar'
                ];
            var ComponentClass;
            var componentType;
            var component;
            for (var i = 0, l = componentList.length; i < l; i++) {
                componentType = componentList[i];
                component = this.component[componentType];
                if (magicOption[componentType]) {
                    if (component) {
                        component.refresh && component.refresh(magicOption);
                    } else {
                        ComponentClass = componentLibrary.get(/^[xy]Axis$/.test(componentType) ? 'axis' : componentType);
                        component = new ComponentClass(this._themeConfig, this._messageCenter, this._zr, magicOption, this, componentType);
                        this.component[componentType] = component;
                    }
                    this._chartList.push(component);
                } else if (component) {
                    component.dispose();
                    this.component[componentType] = null;
                    delete this.component[componentType];
                }
            }
            var ChartClass;
            var chartType;
            var chart;
            var chartMap = {};
            for (var i = 0, l = magicOption.series.length; i < l; i++) {
                chartType = magicOption.series[i].type;
                if (!chartType) {
                    console.error('series[' + i + '] chart type has not been defined.');
                    continue;
                }
                if (!chartMap[chartType]) {
                    chartMap[chartType] = true;
                    ChartClass = chartLibrary.get(chartType);
                    if (ChartClass) {
                        if (this.chart[chartType]) {
                            chart = this.chart[chartType];
                            chart.refresh(magicOption);
                        } else {
                            chart = new ChartClass(this._themeConfig, this._messageCenter, this._zr, magicOption, this);
                        }
                        this._chartList.push(chart);
                        this.chart[chartType] = chart;
                    } else {
                        console.error(chartType + ' has not been required.');
                    }
                }
            }
            for (chartType in this.chart) {
                if (chartType != ecConfig.CHART_TYPE_ISLAND && !chartMap[chartType]) {
                    this.chart[chartType].dispose();
                    this.chart[chartType] = null;
                    delete this.chart[chartType];
                }
            }
            this.component.grid && this.component.grid.refixAxisShape(this.component);
            this._island.refresh(magicOption);
            this._toolbox.refresh(magicOption);
            magicOption.animation && !magicOption.renderAsImage ? this._zr.refresh() : this._zr.render();
            var imgId = 'IMG' + this.id;
            var img = document.getElementById(imgId);
            if (magicOption.renderAsImage && _canvasSupported) {
                if (img) {
                    img.src = this.getDataURL(magicOption.renderAsImage);
                } else {
                    img = this.getImage(magicOption.renderAsImage);
                    img.id = imgId;
                    img.style.position = 'absolute';
                    img.style.left = 0;
                    img.style.top = 0;
                    this.dom.firstChild.appendChild(img);
                }
                this.un();
                this._zr.un();
                this._disposeChartList();
                this._zr.clear();
            } else if (img) {
                img.parentNode.removeChild(img);
            }
            img = null;
            this._option = magicOption;
        },
        restore: function () {
            this._clearEffect();
            this._option = zrUtil.clone(this._optionRestore);
            this._disposeChartList();
            this._island.clear();
            this._toolbox.reset(this._option, true);
            this._render(this._option);
        },
        refresh: function (param) {
            this._clearEffect();
            param = param || {};
            var magicOption = param.option;
            if (!this._refreshInside && magicOption) {
                magicOption = this.getOption();
                zrUtil.merge(magicOption, param.option, true);
                zrUtil.merge(this._optionRestore, param.option, true);
                this._toolbox.reset(magicOption);
            }
            this._island.refresh(magicOption);
            this._toolbox.refresh(magicOption);
            this._zr.clearAnimation();
            for (var i = 0, l = this._chartList.length; i < l; i++) {
                this._chartList[i].refresh && this._chartList[i].refresh(magicOption);
            }
            this.component.grid && this.component.grid.refixAxisShape(this.component);
            this._zr.refresh();
        },
        _disposeChartList: function () {
            this._clearEffect();
            this._zr.clearAnimation();
            var len = this._chartList.length;
            while (len--) {
                var chart = this._chartList[len];
                if (chart) {
                    var chartType = chart.type;
                    this.chart[chartType] && delete this.chart[chartType];
                    this.component[chartType] && delete this.component[chartType];
                    chart.dispose && chart.dispose();
                }
            }
            this._chartList = [];
        },
        _mergeGlobalConifg: function (magicOption) {
            var mergeList = [
                    'backgroundColor',
                    'calculable',
                    'calculableColor',
                    'calculableHolderColor',
                    'nameConnector',
                    'valueConnector',
                    'animation',
                    'animationThreshold',
                    'animationDuration',
                    'animationEasing',
                    'addDataAnimation',
                    'symbolList',
                    'DRAG_ENABLE_TIME'
                ];
            var len = mergeList.length;
            while (len--) {
                var mergeItem = mergeList[len];
                if (magicOption[mergeItem] == null) {
                    magicOption[mergeItem] = this._themeConfig[mergeItem];
                }
            }
            var themeColor = magicOption.color;
            if (!(themeColor && themeColor.length)) {
                themeColor = this._themeConfig.color;
            }
            if (!_canvasSupported) {
                magicOption.animation = false;
                magicOption.addDataAnimation = false;
            }
            this._zr.getColor = function (idx) {
                var zrColor = require('zrender/tool/color');
                return zrColor.getColor(idx, themeColor);
            };
        },
        setOption: function (option, notMerge) {
            if (!option.timeline) {
                return this._setOption(option, notMerge);
            } else {
                return this._setTimelineOption(option);
            }
        },
        _setOption: function (option, notMerge) {
            if (!notMerge && this._option) {
                this._option = zrUtil.merge(this.getOption(), zrUtil.clone(option), true);
            } else {
                this._option = zrUtil.clone(option);
            }
            this._optionRestore = zrUtil.clone(this._option);
            if (!this._option.series || this._option.series.length === 0) {
                this._zr.clear();
                return;
            }
            if (this.component.dataZoom && (this._option.dataZoom || this._option.toolbox && this._option.toolbox.feature && this._option.toolbox.feature.dataZoom && this._option.toolbox.feature.dataZoom.show)) {
                this.component.dataZoom.syncOption(this._option);
            }
            this._toolbox.reset(this._option);
            this._render(this._option);
            return this;
        },
        getOption: function () {
            var magicOption = zrUtil.clone(this._option);
            var self = this;
            function restoreOption(prop) {
                var restoreSource = self._optionRestore[prop];
                if (restoreSource) {
                    if (restoreSource instanceof Array) {
                        var len = restoreSource.length;
                        while (len--) {
                            magicOption[prop][len].data = zrUtil.clone(restoreSource[len].data);
                        }
                    } else {
                        magicOption[prop].data = zrUtil.clone(restoreSource.data);
                    }
                }
            }
            restoreOption('xAxis');
            restoreOption('yAxis');
            restoreOption('series');
            return magicOption;
        },
        setSeries: function (series, notMerge) {
            if (!notMerge) {
                this.setOption({ series: series });
            } else {
                this._option.series = series;
                this.setOption(this._option, notMerge);
            }
            return this;
        },
        getSeries: function () {
            return this.getOption().series;
        },
        _setTimelineOption: function (option) {
            this._timeline && this._timeline.dispose();
            var Timeline = require('./component/timeline');
            var timeline = new Timeline(this._themeConfig, this._messageCenter, this._zr, option, this);
            this._timeline = timeline;
            this.component.timeline = this._timeline;
            return this;
        },
        addData: function (seriesIdx, data, isHead, dataGrow, additionData) {
            var params = seriesIdx instanceof Array ? seriesIdx : [[
                        seriesIdx,
                        data,
                        isHead,
                        dataGrow,
                        additionData
                    ]];
            var magicOption = this.getOption();
            var optionRestore = this._optionRestore;
            for (var i = 0, l = params.length; i < l; i++) {
                seriesIdx = params[i][0];
                data = params[i][1];
                isHead = params[i][2];
                dataGrow = params[i][3];
                additionData = params[i][4];
                var seriesItem = optionRestore.series[seriesIdx];
                var inMethod = isHead ? 'unshift' : 'push';
                var outMethod = isHead ? 'pop' : 'shift';
                if (seriesItem) {
                    var seriesItemData = seriesItem.data;
                    var mSeriesItemData = magicOption.series[seriesIdx].data;
                    seriesItemData[inMethod](data);
                    mSeriesItemData[inMethod](data);
                    if (!dataGrow) {
                        seriesItemData[outMethod]();
                        data = mSeriesItemData[outMethod]();
                    }
                    if (additionData != null) {
                        var legend;
                        var legendData;
                        if (seriesItem.type === ecConfig.CHART_TYPE_PIE && (legend = optionRestore.legend) && (legendData = legend.data)) {
                            var mLegendData = magicOption.legend.data;
                            legendData[inMethod](additionData);
                            mLegendData[inMethod](additionData);
                            if (!dataGrow) {
                                var legendDataIdx = zrUtil.indexOf(legendData, data.name);
                                legendDataIdx != -1 && legendData.splice(legendDataIdx, 1);
                                legendDataIdx = zrUtil.indexOf(mLegendData, data.name);
                                legendDataIdx != -1 && mLegendData.splice(legendDataIdx, 1);
                            }
                        } else if (optionRestore.xAxis != null && optionRestore.yAxis != null) {
                            var axisData;
                            var mAxisData;
                            var axisIdx = seriesItem.xAxisIndex || 0;
                            if (optionRestore.xAxis[axisIdx].type == null || optionRestore.xAxis[axisIdx].type === 'category') {
                                axisData = optionRestore.xAxis[axisIdx].data;
                                mAxisData = magicOption.xAxis[axisIdx].data;
                                axisData[inMethod](additionData);
                                mAxisData[inMethod](additionData);
                                if (!dataGrow) {
                                    axisData[outMethod]();
                                    mAxisData[outMethod]();
                                }
                            }
                            axisIdx = seriesItem.yAxisIndex || 0;
                            if (optionRestore.yAxis[axisIdx].type === 'category') {
                                axisData = optionRestore.yAxis[axisIdx].data;
                                mAxisData = magicOption.yAxis[axisIdx].data;
                                axisData[inMethod](additionData);
                                mAxisData[inMethod](additionData);
                                if (!dataGrow) {
                                    axisData[outMethod]();
                                    mAxisData[outMethod]();
                                }
                            }
                        }
                    }
                    this._option.series[seriesIdx].data = magicOption.series[seriesIdx].data;
                }
            }
            this._zr.clearAnimation();
            var chartList = this._chartList;
            for (var i = 0, l = chartList.length; i < l; i++) {
                if (magicOption.addDataAnimation && chartList[i].addDataAnimation) {
                    chartList[i].addDataAnimation(params);
                }
            }
            this.component.dataZoom && this.component.dataZoom.syncOption(magicOption);
            this._option = magicOption;
            var self = this;
            setTimeout(function () {
                if (!self._zr) {
                    return;
                }
                self._zr.clearAnimation();
                for (var i = 0, l = chartList.length; i < l; i++) {
                    chartList[i].motionlessOnce = magicOption.addDataAnimation && chartList[i].addDataAnimation;
                }
                self._messageCenter.dispatch(ecConfig.EVENT.REFRESH, null, { option: magicOption }, self);
            }, magicOption.addDataAnimation ? 500 : 0);
            return this;
        },
        addMarkPoint: function (seriesIdx, markData) {
            return this._addMark(seriesIdx, markData, 'markPoint');
        },
        addMarkLine: function (seriesIdx, markData) {
            return this._addMark(seriesIdx, markData, 'markLine');
        },
        _addMark: function (seriesIdx, markData, markType) {
            var series = this._option.series;
            var seriesItem;
            if (series && (seriesItem = series[seriesIdx])) {
                var seriesR = this._optionRestore.series;
                var seriesRItem = seriesR[seriesIdx];
                var markOpt = seriesItem[markType];
                var markOptR = seriesRItem[markType];
                markOpt = seriesItem[markType] = markOpt || { data: [] };
                markOptR = seriesRItem[markType] = markOptR || { data: [] };
                for (var key in markData) {
                    if (key === 'data') {
                        markOpt.data = markOpt.data.concat(markData.data);
                        markOptR.data = markOptR.data.concat(markData.data);
                    } else if (typeof markData[key] != 'object' || markOpt[key] == null) {
                        markOpt[key] = markOptR[key] = markData[key];
                    } else {
                        zrUtil.merge(markOpt[key], markData[key], true);
                        zrUtil.merge(markOptR[key], markData[key], true);
                    }
                }
                var chart = this.chart[seriesItem.type];
                chart && chart.addMark(seriesIdx, markData, markType);
            }
            return this;
        },
        delMarkPoint: function (seriesIdx, markName) {
            return this._delMark(seriesIdx, markName, 'markPoint');
        },
        delMarkLine: function (seriesIdx, markName) {
            return this._delMark(seriesIdx, markName, 'markLine');
        },
        _delMark: function (seriesIdx, markName, markType) {
            var series = this._option.series;
            var seriesItem;
            var mark;
            var dataArray;
            if (!(series && (seriesItem = series[seriesIdx]) && (mark = seriesItem[markType]) && (dataArray = mark.data))) {
                return this;
            }
            markName = markName.split(' > ');
            var targetIndex = -1;
            for (var i = 0, l = dataArray.length; i < l; i++) {
                var dataItem = dataArray[i];
                if (dataItem instanceof Array) {
                    if (dataItem[0].name === markName[0] && dataItem[1].name === markName[1]) {
                        targetIndex = i;
                        break;
                    }
                } else if (dataItem.name === markName[0]) {
                    targetIndex = i;
                    break;
                }
            }
            if (targetIndex > -1) {
                dataArray.splice(targetIndex, 1);
                this._optionRestore.series[seriesIdx][markType].data.splice(targetIndex, 1);
                var chart = this.chart[seriesItem.type];
                chart && chart.delMark(seriesIdx, markName.join(' > '), markType);
            }
            return this;
        },
        getDom: function () {
            return this.dom;
        },
        getZrender: function () {
            return this._zr;
        },
        getDataURL: function (imgType) {
            if (!_canvasSupported) {
                return '';
            }
            if (this._chartList.length === 0) {
                var imgId = 'IMG' + this.id;
                var img = document.getElementById(imgId);
                if (img) {
                    return img.src;
                }
            }
            var tooltip = this.component.tooltip;
            tooltip && tooltip.hideTip();
            switch (imgType) {
            case 'jpeg':
                break;
            default:
                imgType = 'png';
            }
            var bgColor = this._option.backgroundColor;
            if (bgColor && bgColor.replace(' ', '') === 'rgba(0,0,0,0)') {
                bgColor = '#fff';
            }
            return this._zr.toDataURL('image/' + imgType, bgColor);
        },
        getImage: function (imgType) {
            var title = this._optionRestore.title;
            var imgDom = document.createElement('img');
            imgDom.src = this.getDataURL(imgType);
            imgDom.title = title && title.text || 'ECharts';
            return imgDom;
        },
        getConnectedDataURL: function (imgType) {
            if (!this.isConnected()) {
                return this.getDataURL(imgType);
            }
            var tempDom = this.dom;
            var imgList = {
                    'self': {
                        img: this.getDataURL(imgType),
                        left: tempDom.offsetLeft,
                        top: tempDom.offsetTop,
                        right: tempDom.offsetLeft + tempDom.offsetWidth,
                        bottom: tempDom.offsetTop + tempDom.offsetHeight
                    }
                };
            var minLeft = imgList.self.left;
            var minTop = imgList.self.top;
            var maxRight = imgList.self.right;
            var maxBottom = imgList.self.bottom;
            for (var c in this._connected) {
                tempDom = this._connected[c].getDom();
                imgList[c] = {
                    img: this._connected[c].getDataURL(imgType),
                    left: tempDom.offsetLeft,
                    top: tempDom.offsetTop,
                    right: tempDom.offsetLeft + tempDom.offsetWidth,
                    bottom: tempDom.offsetTop + tempDom.offsetHeight
                };
                minLeft = Math.min(minLeft, imgList[c].left);
                minTop = Math.min(minTop, imgList[c].top);
                maxRight = Math.max(maxRight, imgList[c].right);
                maxBottom = Math.max(maxBottom, imgList[c].bottom);
            }
            var zrDom = document.createElement('div');
            zrDom.style.position = 'absolute';
            zrDom.style.left = '-4000px';
            zrDom.style.width = maxRight - minLeft + 'px';
            zrDom.style.height = maxBottom - minTop + 'px';
            document.body.appendChild(zrDom);
            var zrImg = require('zrender').init(zrDom);
            var ImageShape = require('zrender/shape/Image');
            for (var c in imgList) {
                zrImg.addShape(new ImageShape({
                    style: {
                        x: imgList[c].left - minLeft,
                        y: imgList[c].top - minTop,
                        image: imgList[c].img
                    }
                }));
            }
            zrImg.render();
            var bgColor = this._option.backgroundColor;
            if (bgColor && bgColor.replace(/ /g, '') === 'rgba(0,0,0,0)') {
                bgColor = '#fff';
            }
            var image = zrImg.toDataURL('image/png', bgColor);
            setTimeout(function () {
                zrImg.dispose();
                zrDom.parentNode.removeChild(zrDom);
                zrDom = null;
            }, 100);
            return image;
        },
        getConnectedImage: function (imgType) {
            var title = this._optionRestore.title;
            var imgDom = document.createElement('img');
            imgDom.src = this.getConnectedDataURL(imgType);
            imgDom.title = title && title.text || 'ECharts';
            return imgDom;
        },
        on: function (eventName, eventListener) {
            this._messageCenterOutSide.bind(eventName, eventListener, this);
            return this;
        },
        un: function (eventName, eventListener) {
            this._messageCenterOutSide.unbind(eventName, eventListener);
            return this;
        },
        connect: function (connectTarget) {
            if (!connectTarget) {
                return this;
            }
            if (!this._connected) {
                this._connected = {};
            }
            if (connectTarget instanceof Array) {
                for (var i = 0, l = connectTarget.length; i < l; i++) {
                    this._connected[connectTarget[i].id] = connectTarget[i];
                }
            } else {
                this._connected[connectTarget.id] = connectTarget;
            }
            return this;
        },
        disConnect: function (connectTarget) {
            if (!connectTarget || !this._connected) {
                return this;
            }
            if (connectTarget instanceof Array) {
                for (var i = 0, l = connectTarget.length; i < l; i++) {
                    delete this._connected[connectTarget[i].id];
                }
            } else {
                delete this._connected[connectTarget.id];
            }
            for (var k in this._connected) {
                return k, this;
            }
            this._connected = false;
            return this;
        },
        connectedEventHandler: function (param) {
            if (param.__echartsId != this.id) {
                this._onevent(param);
            }
        },
        isConnected: function () {
            return !!this._connected;
        },
        showLoading: function (loadingOption) {
            var effectList = {
                    bar: require('zrender/loadingEffect/Bar'),
                    bubble: require('zrender/loadingEffect/Bubble'),
                    dynamicLine: require('zrender/loadingEffect/DynamicLine'),
                    ring: require('zrender/loadingEffect/Ring'),
                    spin: require('zrender/loadingEffect/Spin'),
                    whirling: require('zrender/loadingEffect/Whirling')
                };
            this._toolbox.hideDataView();
            loadingOption = loadingOption || {};
            var textStyle = loadingOption.textStyle || {};
            loadingOption.textStyle = textStyle;
            var finalTextStyle = zrUtil.merge(zrUtil.clone(textStyle), this._themeConfig.textStyle);
            textStyle.textFont = finalTextStyle.fontStyle + ' ' + finalTextStyle.fontWeight + ' ' + finalTextStyle.fontSize + 'px ' + finalTextStyle.fontFamily;
            textStyle.text = loadingOption.text || this._themeConfig.loadingText;
            if (loadingOption.x != null) {
                textStyle.x = loadingOption.x;
            }
            if (loadingOption.y != null) {
                textStyle.y = loadingOption.y;
            }
            loadingOption.effectOption = loadingOption.effectOption || {};
            loadingOption.effectOption.textStyle = textStyle;
            var Effect = loadingOption.effect;
            if (typeof Effect === 'string' || Effect == null) {
                Effect = effectList[loadingOption.effect || 'spin'];
            }
            this._zr.showLoading(new Effect(loadingOption.effectOption));
            return this;
        },
        hideLoading: function () {
            this._zr.hideLoading();
            return this;
        },
        setTheme: function (theme) {
            if (theme) {
                if (typeof theme === 'string') {
                    switch (theme) {
                    default:
                        theme = require('./theme/default');
                    }
                } else {
                    theme = theme || {};
                }
                for (var key in this._themeConfig) {
                    delete this._themeConfig[key];
                }
                for (var key in ecConfig) {
                    this._themeConfig[key] = zrUtil.clone(ecConfig[key]);
                }
                theme.color && (this._themeConfig.color = []);
                theme.symbolList && (this._themeConfig.symbolList = []);
                zrUtil.merge(this._themeConfig, zrUtil.clone(theme), true);
            }
            if (!_canvasSupported) {
                this._themeConfig.textStyle.fontFamily = this._themeConfig.textStyle.fontFamily2;
            }
            this._timeline && this._timeline.setTheme(true);
            this._optionRestore && this.restore();
        },
        resize: function () {
            var self = this;
            return function () {
                self._clearEffect();
                self._zr.resize();
                if (self._option && self._option.renderAsImage && _canvasSupported) {
                    self._render(self._option);
                    return self;
                }
                self._zr.clearAnimation();
                self._island.resize();
                self._toolbox.resize();
                self._timeline && self._timeline.resize();
                for (var i = 0, l = self._chartList.length; i < l; i++) {
                    self._chartList[i].resize && self._chartList[i].resize();
                }
                self.component.grid && self.component.grid.refixAxisShape(self.component);
                self._zr.refresh();
                self._messageCenter.dispatch(ecConfig.EVENT.RESIZE, null, null, self);
                return self;
            };
        },
        _clearEffect: function () {
            this._zr.modLayer(ecConfig.EFFECT_ZLEVEL, { motionBlur: false });
            this._zr.painter.clearLayer(ecConfig.EFFECT_ZLEVEL);
        },
        clear: function () {
            this._disposeChartList();
            this._zr.clear();
            this._option = {};
            this._optionRestore = {};
            this.dom.style.backgroundColor = null;
            return this;
        },
        dispose: function () {
            var key = this.dom.getAttribute(DOM_ATTRIBUTE_KEY);
            key && delete _instances[key];
            this._island.dispose();
            this._toolbox.dispose();
            this._timeline && this._timeline.dispose();
            this._messageCenter.unbind();
            this.clear();
            this._zr.dispose();
            this._zr = null;
        }
    };
    return self;
});

define('echarts', ['echarts/echarts'], function ( main ) { return main; });