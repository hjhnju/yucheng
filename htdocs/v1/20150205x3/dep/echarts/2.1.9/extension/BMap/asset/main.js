define(function (require) {
    function BMapExt(obj, BMap, ec) {
        this._init(obj, BMap, ec);
    }
    ;
    BMapExt.prototype._echartsContainer = null;
    BMapExt.prototype._map = null;
    BMapExt.prototype._ec = null;
    BMapExt.prototype._geoCoord = [];
    BMapExt.prototype._mapOffset = [
        0,
        0
    ];
    BMapExt.prototype._init = function (obj, BMap, ec) {
        var self = this;
        self._map = obj.constructor == BMap.Map ? obj : new BMap.Map(obj);
        function Overlay() {
        }
        Overlay.prototype = new BMap.Overlay();
        Overlay.prototype.initialize = function (map) {
            var size = map.getSize();
            var div = self._echartsContainer = document.createElement('div');
            div.style.position = 'absolute';
            div.style.height = size.height + 'px';
            div.style.width = size.width + 'px';
            div.style.top = 0;
            div.style.left = 0;
            map.getPanes().labelPane.appendChild(div);
            return div;
        };
        Overlay.prototype.draw = function () {
        };
        var myOverlay = new Overlay();
        self.getEchartsContainer = function () {
            return self._echartsContainer;
        };
        self.getMap = function () {
            return self._map;
        };
        self.onmoving = null;
        self.onmoveend = null;
        self.onzoom = null;
        self.geoCoord2Pixel = function (geoCoord) {
            var point = new BMap.Point(geoCoord[0], geoCoord[1]);
            var pos = self._map.pointToOverlayPixel(point);
            return [
                pos.x,
                pos.y
            ];
        };
        self.pixel2GeoCoord = function (pixel) {
            var point = self._map.overlayPixelToPoint({
                    x: pixel[0],
                    y: pixel[1]
                });
            return [
                point.lng,
                point.lat
            ];
        };
        self.initECharts = function () {
            self._ec = ec.init.apply(self, arguments);
            self._bindEvent();
            return self._ec;
        };
        self.getECharts = function () {
            return self._ec;
        };
        self.getMapOffset = function () {
            return self._mapOffset;
        };
        self.setOption = function (option) {
            var series = option.series || {};
            for (var i = 0, item; item = series[i++];) {
                var geoCoord = item.geoCoord;
                if (geoCoord) {
                    for (var k in geoCoord) {
                        self._geoCoord[k] = geoCoord[k];
                    }
                }
            }
            for (var i = 0, item; item = series[i++];) {
                var markPoint = item.markPoint || {};
                var markLine = item.markLine || {};
                var data = markPoint.data;
                if (data && data.length) {
                    for (var k in data) {
                        self._AddPos(data[k]);
                    }
                }
                data = markLine.data;
                if (data && data.length) {
                    for (var k in data) {
                        self._AddPos(data[k][0]);
                        self._AddPos(data[k][1]);
                    }
                }
            }
            self._ec.setOption(option);
        };
        self._AddPos = function (obj) {
            var coord = this._geoCoord[obj.name];
            var pos = this.geoCoord2Pixel(coord);
            obj.x = pos[0] - self._mapOffset[0];
            obj.y = pos[1] - self._mapOffset[1];
        };
        self._bindEvent = function () {
            self._map.addEventListener('zoomend', _zoomChangeHandler);
            self._map.addEventListener('moving', _moveHandler('moving'));
            self._map.addEventListener('moveend', _moveHandler('moveend'));
            self._ec.getZrender().on('dragstart', _dragZrenderHandler(true));
            self._ec.getZrender().on('dragend', _dragZrenderHandler(false));
        };
        function _zoomChangeHandler() {
            _fireEvent('zoom');
        }
        function _moveHandler(type) {
            return function () {
                var offsetEle = self._echartsContainer.parentNode.parentNode.parentNode;
                self._mapOffset = [
                    -parseInt(offsetEle.style.left) || 0,
                    -parseInt(offsetEle.style.top) || 0
                ];
                self._echartsContainer.style.left = self._mapOffset[0] + 'px';
                self._echartsContainer.style.top = self._mapOffset[1] + 'px';
                _fireEvent(type);
            };
        }
        function _dragZrenderHandler(isStart) {
            return function () {
                var func = isStart ? 'disableDragging' : 'enableDragging';
                self._map[func]();
            };
        }
        function _fireEvent(type) {
            var func = self['on' + type];
            if (func) {
                func();
            } else {
                self.refresh();
            }
        }
        self.refresh = function () {
            if (self._ec) {
                var option = self._ec.getOption();
                self._ec.clear();
                self.setOption(option);
            }
        };
        self._map.addOverlay(myOverlay);
    };
    return BMapExt;
});