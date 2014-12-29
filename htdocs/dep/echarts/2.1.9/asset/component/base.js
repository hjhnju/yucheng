define('echarts/component/base', function (require) {
    var ecConfig = require('../config');
    var ecData = require('../util/ecData');
    var ecQuery = require('../util/ecQuery');
    var number = require('../util/number');
    var zrUtil = require('zrender/tool/util');
    function Base(ecTheme, messageCenter, zr, option, myChart) {
        this.ecTheme = ecTheme;
        this.messageCenter = messageCenter;
        this.zr = zr;
        this.option = option;
        this.series = option.series;
        this.myChart = myChart;
        this.component = myChart.component;
        this._zlevelBase = this.getZlevelBase();
        this.shapeList = [];
        this.effectList = [];
        var self = this;
        self._onlegendhoverlink = function (param) {
            if (self.legendHoverLink) {
                var targetName = param.target;
                var name;
                for (var i = self.shapeList.length - 1; i >= 0; i--) {
                    name = self.type == ecConfig.CHART_TYPE_PIE || self.type == ecConfig.CHART_TYPE_FUNNEL ? ecData.get(self.shapeList[i], 'name') : (ecData.get(self.shapeList[i], 'series') || {}).name;
                    if (name == targetName && !self.shapeList[i].invisible) {
                        self.zr.addHoverShape(self.shapeList[i]);
                    }
                }
            }
        };
        messageCenter && messageCenter.bind(ecConfig.EVENT.LEGEND_HOVERLINK, this._onlegendhoverlink);
    }
    Base.prototype = {
        canvasSupported: require('zrender/tool/env').canvasSupported,
        getZlevelBase: function (contentType) {
            contentType = contentType || this.type + '';
            switch (contentType) {
            case ecConfig.COMPONENT_TYPE_GRID:
            case ecConfig.COMPONENT_TYPE_AXIS_CATEGORY:
            case ecConfig.COMPONENT_TYPE_AXIS_VALUE:
            case ecConfig.COMPONENT_TYPE_POLAR:
                return 0;
            case ecConfig.CHART_TYPE_LINE:
            case ecConfig.CHART_TYPE_BAR:
            case ecConfig.CHART_TYPE_SCATTER:
            case ecConfig.CHART_TYPE_PIE:
            case ecConfig.CHART_TYPE_RADAR:
            case ecConfig.CHART_TYPE_MAP:
            case ecConfig.CHART_TYPE_K:
            case ecConfig.CHART_TYPE_CHORD:
            case ecConfig.CHART_TYPE_GUAGE:
            case ecConfig.CHART_TYPE_FUNNEL:
            case ecConfig.CHART_TYPE_EVENTRIVER:
                return 2;
            case ecConfig.COMPONENT_TYPE_LEGEND:
            case ecConfig.COMPONENT_TYPE_DATARANGE:
            case ecConfig.COMPONENT_TYPE_DATAZOOM:
            case ecConfig.COMPONENT_TYPE_TIMELINE:
            case ecConfig.COMPONENT_TYPE_ROAMCONTROLLER:
                return 4;
            case ecConfig.CHART_TYPE_ISLAND:
                return 5;
            case ecConfig.COMPONENT_TYPE_TOOLBOX:
            case ecConfig.COMPONENT_TYPE_TITLE:
                return 6;
            case ecConfig.COMPONENT_TYPE_TOOLTIP:
                return 8;
            default:
                return 0;
            }
        },
        reformOption: function (opt) {
            return zrUtil.merge(opt || {}, zrUtil.clone(this.ecTheme[this.type] || {}));
        },
        reformCssArray: function (p) {
            if (p instanceof Array) {
                switch (p.length + '') {
                case '4':
                    return p;
                case '3':
                    return [
                        p[0],
                        p[1],
                        p[2],
                        p[1]
                    ];
                case '2':
                    return [
                        p[0],
                        p[1],
                        p[0],
                        p[1]
                    ];
                case '1':
                    return [
                        p[0],
                        p[0],
                        p[0],
                        p[0]
                    ];
                case '0':
                    return [
                        0,
                        0,
                        0,
                        0
                    ];
                }
            } else {
                return [
                    p,
                    p,
                    p,
                    p
                ];
            }
        },
        getShapeById: function (id) {
            for (var i = 0, l = this.shapeList.length; i < l; i++) {
                if (this.shapeList[i].id === id) {
                    return this.shapeList[i];
                }
            }
            return null;
        },
        getFont: function (textStyle) {
            var finalTextStyle = zrUtil.merge(zrUtil.clone(textStyle) || {}, this.ecTheme.textStyle);
            return finalTextStyle.fontStyle + ' ' + finalTextStyle.fontWeight + ' ' + finalTextStyle.fontSize + 'px ' + finalTextStyle.fontFamily;
        },
        getItemStyleColor: function (itemColor, seriesIndex, dataIndex, data) {
            return typeof itemColor === 'function' ? itemColor.call(this.myChart, {
                seriesIndex: seriesIndex,
                series: this.series[seriesIndex],
                dataIndex: dataIndex,
                data: data
            }) : itemColor;
        },
        subPixelOptimize: function (position, lineWidth) {
            if (lineWidth % 2 === 1) {
                position = Math.floor(position) + 0.5;
            } else {
                position = Math.round(position);
            }
            return position;
        },
        resize: function () {
            this.refresh && this.refresh();
            this.clearEffectShape && this.clearEffectShape(true);
            var self = this;
            setTimeout(function () {
                self.animationEffect && self.animationEffect();
            }, 200);
        },
        clear: function () {
            this.clearEffectShape && this.clearEffectShape();
            this.zr && this.zr.delShape(this.shapeList);
            this.shapeList = [];
        },
        dispose: function () {
            this.onbeforDispose && this.onbeforDispose();
            this.clear();
            this.shapeList = null;
            this.effectList = null;
            this.messageCenter && this.messageCenter.unbind(ecConfig.EVENT.LEGEND_HOVERLINK, this._onlegendhoverlink);
            this.onafterDispose && this.onafterDispose();
        },
        query: ecQuery.query,
        deepQuery: ecQuery.deepQuery,
        deepMerge: ecQuery.deepMerge,
        parsePercent: number.parsePercent,
        parseCenter: number.parseCenter,
        parseRadius: number.parseRadius,
        numAddCommas: number.addCommas
    };
    return Base;
});